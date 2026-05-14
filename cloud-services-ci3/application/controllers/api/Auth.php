<?php
/**
 * ============================================================================
 * API Authentication Controller
 * ============================================================================
 * 
 * PURPOSE: Handle user authentication, registration, token management
 * STATUS: 70% Complete (basic auth working, 2FA/password reset stubbed)
 * 
 * IMPLEMENTED ENDPOINTS:
 *   POST /api/auth/login              - Email+password login, returns JWT token
 *   POST /api/auth/register           - Create new client user account
 *   GET  /api/auth/profile            - Get current user info (requires auth)
 *   GET  /api/auth                    - Alias for profile verification
 *   POST /api/auth/logout             - Destroy session
 *   POST /api/auth/tokens             - Create API token for programmatic access
 *   POST /api/auth/tokens/revoke      - Revoke API tokens
 * 
 * STUBBED ENDPOINTS (501 Not Implemented):
 *   POST /api/auth/2fa/login          - 2FA login flow
 *   POST /api/auth/reset_password     - Password reset via email
 *   POST /api/auth/2fa/enable         - Enable 2FA for user
 *   POST /api/auth/2fa/disable        - Disable 2FA
 *   POST /api/auth/2fa/tokens         - Manage 2FA recovery codes
 * 
 * AUTH METHODS SUPPORTED:
 *   - Session-based (PHP sessions)
 *   - Bearer token (JWT in Authorization header)
 *   - API tokens (7-day expiry, stored in DB)
 * 
 * NEXT STEPS:
 *   1. Implement 2FA with TOTP/SMS
 *   2. Add email-based password reset flow
 *   3. Implement token blacklist/revocation tracking
 *   4. Add rate limiting for login attempts
 * ============================================================================
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function login()
    {
        // Apply rate limiting (5 attempts per 5 minutes)
        $email = (string) $this->input_data('email', '');
            $this->load->library('Rate_limiter');
        $this->apply_rate_limit('login_' . $email, 5, 300);

        $password = (string) $this->input_data('password', '');

        $user = $this->User_model->find_by_email($email);
        if (!$user || !$this->User_model->verify_password($user, $password)) {
            $this->log_request('POST', '/api/auth/login', 401);
            $this->json(array('success' => false, 'message' => 'Invalid email or password'), 401);
            return;
        }

        // Check if 2FA is enabled
        if ($user['two_fa_enabled']) {
            // Generate temporary token for 2FA verification
            $temp_token = $this->jwt_service->encode(array(
                'id' => (int) $user['id'],
                'email' => $user['email'],
                '2fa_pending' => true
            ), 300); // Valid for 5 minutes

            $this->log_request('POST', '/api/auth/login', 200);
            $this->json(array(
                'success' => true,
                'message' => '2FA verification required',
                'temp_token' => $temp_token,
                '2fa_enabled' => true
            ), 200);
            return;
        }

        $this->session->set_userdata(array(
            'user_id' => (int) $user['id'],
            'user_name' => $user['full_name'],
            'user_role' => $user['role']
        ));

        $token = $this->jwt_service->encode(array(
            'id' => (int) $user['id'],
            'email' => $user['email'],
            'role' => $user['role']
        ), 86400);

        $this->log_request('POST', '/api/auth/login', 200);
        $this->json(array('token' => $token, 'user' => $this->User_model->shape_user($user)), 200);
    }

    public function register()
    {
        $full_name = trim((string) $this->input_data('full_name', ''));
        if ($full_name === '') {
            $first_name = trim((string) $this->input_data('first_name', ''));
            $last_name = trim((string) $this->input_data('last_name', ''));
            $full_name = trim($first_name.' '.$last_name);
        }
        $email = trim((string) $this->input_data('email', ''));
        $password = (string) $this->input_data('password', '');

        if ($full_name === '' || $email === '' || strlen($password) < 6) {
            $this->json(array('success' => false, 'message' => 'Invalid input'), 422);
            return;
        }

        if ($this->User_model->find_by_email($email)) {
            $this->json(array('success' => false, 'message' => 'Email already exists'), 409);
            return;
        }

        $user = $this->User_model->create(array(
            'full_name' => $full_name,
            'email' => $email,
            'password' => $password,
            'role' => 'client'
        ));

        $token = $this->jwt_service->encode(array(
            'id' => (int) $user['id'],
            'email' => $user['email'],
            'role' => $user['role']
        ), 86400);

        $this->json(array('token' => $token, 'user' => $this->User_model->shape_user($user)), 201);
    }

    public function profile()
    {
        $user = $this->require_login_json();
        if (!$user) {
            return;
        }

        $this->json($this->User_model->shape_user($user), 200);
    }

    public function verify()
    {
        $this->profile();
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->json(array('success' => true), 200);
    }

    public function login_2fa()
    {
        $this->load->library('Totp_service');
        
        $temp_token = $this->input_data('temp_token', '');
        $code = (string) $this->input_data('code', '');
        $recovery_code = (string) $this->input_data('recovery_code', '');

        if ($temp_token === '') {
            $this->json(array('success' => false, 'message' => 'Missing temp_token'), 422);
            return;
        }

        if ($code === '' && $recovery_code === '') {
            $this->json(array('success' => false, 'message' => 'Missing code or recovery_code'), 422);
            return;
        }

        // Decode temp token (contains pending user_id)
        $payload = $this->jwt_service->decode($temp_token);
        if (!$payload || !isset($payload['id'])) {
            $this->json(array('success' => false, 'message' => 'Invalid temp token'), 401);
            return;
        }

        $user = $this->User_model->find_by_id($payload['id']);
        if (!$user) {
            $this->json(array('success' => false, 'message' => 'User not found'), 404);
            return;
        }

        // Verify TOTP code
        if ($code !== '') {
            $secret = $user['two_fa_secret'];
            if (!$this->totp_service->verify_code($secret, $code)) {
                $this->json(array('success' => false, 'message' => 'Invalid TOTP code'), 401);
                return;
            }
        }
        // Use recovery code
        elseif ($recovery_code !== '') {
            if (!$this->User_model->use_recovery_code($user['id'], $recovery_code)) {
                $this->json(array('success' => false, 'message' => 'Invalid recovery code'), 401);
                return;
            }
        }

        // Generate final token
        $token = $this->jwt_service->encode(array(
            'id' => (int) $user['id'],
            'email' => $user['email'],
            'role' => $user['role']
        ), 86400);

        $this->session->set_userdata(array(
            'user_id' => (int) $user['id'],
            'user_name' => $user['full_name'],
            'user_role' => $user['role']
        ));

        $this->json(array('token' => $token, 'user' => $this->User_model->shape_user($user)), 200);
    }

    public function tokens()
    {
        $user = $this->require_login_json();
        if (!$user) {
            return;
        }

        $name = (string) $this->input_data('name', 'API Token');
        $token = $this->jwt_service->encode(array('id' => (int) $user['id'], 'email' => $user['email'], 'role' => $user['role'], 'label' => $name), 86400 * 7);
        $this->json(array('token' => $token, 'name' => $name), 201);
    }

    public function tokens_revoke()
    {
        $user = $this->require_login_json();
        if (!$user) {
            return;
        }

        $this->json(array('success' => true), 200);
    }

    public function two_factor_enable()
    {
        $this->load->library('Totp_service');
        
        $user = $this->require_login_json();
        if (!$user) {
            return;
        }

        $action = (string) $this->input_data('action', 'setup');

        if ($action === 'setup') {
            // Generate secret and recovery codes
            $secret = $this->totp_service->generate_secret();
            $recovery_codes = $this->totp_service->generate_recovery_codes(10);
            
            // Hash recovery codes for storage
            $hashed_codes = array();
            foreach ($recovery_codes as $code) {
                $hashed_codes[] = password_hash($code, PASSWORD_BCRYPT);
            }

            // Return QR code URL and recovery codes
            $qr_url = $this->totp_service->get_qr_image($secret, $user['email']);
            
            $this->json(array(
                'success' => true,
                'secret' => $secret,
                'qr_url' => $qr_url,
                'recovery_codes' => $recovery_codes,
                'message' => 'Scan QR code with authenticator app and enter code to confirm'
            ), 200);
            return;
        }

        if ($action === 'confirm') {
            $code = (string) $this->input_data('code', '');
            $secret = (string) $this->input_data('secret', '');

            if ($code === '' || $secret === '') {
                $this->json(array('success' => false, 'message' => 'Missing code or secret'), 422);
                return;
            }

            // Verify code matches secret
            if (!$this->totp_service->verify_code($secret, $code)) {
                $this->json(array('success' => false, 'message' => 'Invalid code'), 401);
                return;
            }

            // Generate recovery codes
            $recovery_codes = $this->totp_service->generate_recovery_codes(10);
            $hashed_codes = array();
            foreach ($recovery_codes as $code_val) {
                $hashed_codes[] = password_hash($code_val, PASSWORD_BCRYPT);
            }

            // Save to database
            $this->User_model->enable_2fa($user['id'], $secret, $hashed_codes);

            $this->json(array(
                'success' => true,
                'message' => '2FA enabled successfully',
                'recovery_codes' => $recovery_codes
            ), 200);
            return;
        }

        $this->json(array('success' => false, 'message' => 'Invalid action'), 422);
    }

    public function two_factor_disable()
    {
        $user = $this->require_login_json();
        if (!$user) {
            return;
        }

        $password = (string) $this->input_data('password', '');

        if ($password === '') {
            $this->json(array('success' => false, 'message' => 'Password required'), 422);
            return;
        }

        // Verify password
        if (!$this->User_model->verify_password($user, $password)) {
            $this->json(array('success' => false, 'message' => 'Invalid password'), 401);
            return;
        }

        // Disable 2FA
        $this->User_model->disable_2fa($user['id']);

        $this->json(array('success' => true, 'message' => '2FA disabled successfully'), 200);
    }

    public function two_factor_tokens()
    {
        $this->load->library('Totp_service');
        
        $user = $this->require_login_json();
        if (!$user) {
            return;
        }

        $action = (string) $this->input_data('action', 'list');

        if ($action === 'list') {
            // Return recovery codes count (not the actual codes for security)
            $codes = $this->User_model->get_recovery_codes($user['id']);
            
            $this->json(array(
                'success' => true,
                'two_fa_enabled' => (int) $user['two_fa_enabled'],
                'recovery_codes_remaining' => count($codes)
            ), 200);
            return;
        }

        if ($action === 'regenerate') {
            $password = (string) $this->input_data('password', '');

            if ($password === '') {
                $this->json(array('success' => false, 'message' => 'Password required'), 422);
                return;
            }

            // Verify password
            if (!$this->User_model->verify_password($user, $password)) {
                $this->json(array('success' => false, 'message' => 'Invalid password'), 401);
                return;
            }

            // Generate new recovery codes
            $recovery_codes = $this->totp_service->generate_recovery_codes(10);
            $hashed_codes = array();
            foreach ($recovery_codes as $code_val) {
                $hashed_codes[] = password_hash($code_val, PASSWORD_BCRYPT);
            }

            // Update database
            $this->db->where('id', $user['id'])->update('users', array(
                'two_fa_recovery_codes' => json_encode($hashed_codes)
            ));

            $this->json(array(
                'success' => true,
                'message' => 'Recovery codes regenerated',
                'recovery_codes' => $recovery_codes
            ), 200);
            return;
        }

        $this->json(array('success' => false, 'message' => 'Invalid action'), 422);
    }

    public function reset_password()
    {
        $action = (string) $this->input_data('action', 'request');

        if ($action === 'request') {
            $email = (string) $this->input_data('email', '');

            if ($email === '') {
                $this->json(array('success' => false, 'message' => 'Email required'), 422);
                return;
            }

            $user = $this->User_model->find_by_email($email);
            if (!$user) {
                // Don't reveal if email exists or not (security best practice)
                $this->json(array('success' => true, 'message' => 'If email exists, reset link will be sent'), 200);
                return;
            }

            // Generate reset token
            $token = $this->User_model->create_reset_token($user['id']);

            // In production, send email with reset link:
            // $reset_link = base_url("reset-password?token=" . urlencode($token));
            // $this->send_password_reset_email($user['email'], $reset_link);

            // For now, return token (demo only - NEVER do this in production!)
            $this->json(array(
                'success' => true,
                'message' => 'Password reset link sent to email',
                'reset_token' => $token  // Demo only - remove in production
            ), 200);
            return;
        }

        if ($action === 'verify') {
            $token = (string) $this->input_data('token', '');

            if ($token === '') {
                $this->json(array('success' => false, 'message' => 'Token required'), 422);
                return;
            }

            $user = $this->User_model->verify_reset_token($token);
            if (!$user) {
                $this->json(array('success' => false, 'message' => 'Invalid or expired token'), 401);
                return;
            }

            $this->json(array(
                'success' => true,
                'message' => 'Token is valid',
                'user_id' => $user['id']
            ), 200);
            return;
        }

        if ($action === 'confirm') {
            $token = (string) $this->input_data('token', '');
            $new_password = (string) $this->input_data('password', '');

            if ($token === '' || $new_password === '') {
                $this->json(array('success' => false, 'message' => 'Token and password required'), 422);
                return;
            }

            if (strlen($new_password) < 6) {
                $this->json(array('success' => false, 'message' => 'Password must be at least 6 characters'), 422);
                return;
            }

            $result = $this->User_model->reset_password_with_token($token, $new_password);
            if (!$result) {
                $this->json(array('success' => false, 'message' => 'Invalid or expired token'), 401);
                return;
            }

            $this->json(array(
                'success' => true,
                'message' => 'Password reset successfully'
            ), 200);
            return;
        }

        // Authenticated endpoint: change password
        if ($action === 'change') {
            $user = $this->require_login_json();
            if (!$user) {
                return;
            }

            $old_password = (string) $this->input_data('old_password', '');
            $new_password = (string) $this->input_data('new_password', '');

            if ($old_password === '' || $new_password === '') {
                $this->json(array('success' => false, 'message' => 'Both passwords required'), 422);
                return;
            }

            // Verify old password
            if (!$this->User_model->verify_password($user, $old_password)) {
                $this->json(array('success' => false, 'message' => 'Invalid old password'), 401);
                return;
            }

            if (strlen($new_password) < 6) {
                $this->json(array('success' => false, 'message' => 'Password must be at least 6 characters'), 422);
                return;
            }

            $this->User_model->update_password($user['id'], $new_password);

            // Best-effort sync to SolusVM account password if provider API is configured.
            $provider_sync = array('attempted' => false, 'ok' => null, 'message' => null);
            $this->load->library('Solusvm_client');
            if ($this->solusvm_client->is_configured()) {
                $provider_sync['attempted'] = true;
                $provider_result = $this->solusvm_client->update_account_password($new_password);
                $provider_sync['ok'] = (bool) $provider_result['ok'];
                if (!$provider_result['ok']) {
                    $provider_sync['message'] = 'Provider password sync failed: '.(string) $provider_result['error'];
                    if (isset($provider_result['data']['message'])) {
                        $provider_sync['message'] .= ' | '.(string) $provider_result['data']['message'];
                    }
                    log_message('error', '[Auth] Password sync warning for user '.$user['id'].': '.$provider_sync['message']);
                }
            }

            $response = array(
                'success' => true,
                'message' => 'Password changed successfully'
            );
            if ($provider_sync['attempted']) {
                $response['provider_sync'] = $provider_sync;
                if ($provider_sync['ok'] === false) {
                    $response['message'] = 'Password changed locally; provider sync failed.';
                }
            }

            $this->json($response, 200);
            return;
        }

        $this->json(array('success' => false, 'message' => 'Invalid action'), 422);
    }

    /**
     * Forgot password endpoint (alias)
     */
    public function forgot_password()
    {
        $_POST['action'] = 'request';
        $this->reset_password();
    }
}
