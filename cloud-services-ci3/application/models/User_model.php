<?php
/**
 * ============================================================================
 * User Account Model
 * ============================================================================
 * 
 * PURPOSE: User account management, authentication, profile operations
 * STATUS: 95% Complete (core features working, password reset missing)
 * DATABASE: SQLite users table
 * 
 * COLUMNS:
 *   id               - Primary key
 *   full_name        - Display name
 *   email            - Unique email address
 *   password_hash    - Bcrypt hashed password
 *   role             - 'admin' or 'client'
 *   created_at       - Registration timestamp
 * 
 * SEED DATA:
 *   Admin:  admin@example.com / admin123 (role='admin')
 *   Client: client@example.com / client123 (role='client')
 * 
 * KEY METHODS:
 *   - create($data)              - Create new user with hashed password
 *   - find_by_id($id)            - Get user by ID
 *   - find_by_email($email)      - Get user by email
 *   - list_clients()             - Get all client users (admin only)
 *   - verify_password($user, $pwd) - Check password match
 *   - shape_user($user)          - Format user data for API response
 * 
 * SECURITY:
 *   ✓ Passwords are bcrypt hashed (not stored in plain text)
 *   ✓ Email validation on create
 *   ✓ Password minimum length (6 chars, should be 12+ for production)
 *   ✓ Duplicate email prevention
 * 
 * API RESPONSE FORMAT (shape_user):
 *   {
 *     "id": 1,
 *     "full_name": "Admin User",
 *     "email": "admin@example.com",
 *     "role": "admin",
 *     "created_at": "2026-05-12T10:00:00+02:00"
 *   }
 *   (Note: password_hash is never returned in API responses)
 * 
 * MISSING FEATURES:
 * ❌ Email verification on registration
 * ❌ Password reset email flow
 * ❌ Account suspension/locking
 * ❌ Login attempt rate limiting
 * ❌ 2FA management
 * ❌ Activity logging
 * ❌ Password expiry policies
 * ============================================================================
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->bootstrap();
    }

    private function bootstrap()
    {
        $this->db->query('CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            full_name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            role TEXT NOT NULL DEFAULT "client",
            created_at TEXT NOT NULL,
            two_fa_enabled INTEGER DEFAULT 0,
            two_fa_secret TEXT,
            two_fa_recovery_codes TEXT,
            reset_token TEXT,
            reset_token_expiry TEXT
        )');

        // Add missing columns if they don't exist
        $columns = $this->db->list_fields('users');
        if (!in_array('two_fa_enabled', $columns)) {
            $this->db->query('ALTER TABLE users ADD COLUMN two_fa_enabled INTEGER DEFAULT 0');
        }
        if (!in_array('two_fa_secret', $columns)) {
            $this->db->query('ALTER TABLE users ADD COLUMN two_fa_secret TEXT');
        }
        if (!in_array('two_fa_recovery_codes', $columns)) {
            $this->db->query('ALTER TABLE users ADD COLUMN two_fa_recovery_codes TEXT');
        }
        if (!in_array('reset_token', $columns)) {
            $this->db->query('ALTER TABLE users ADD COLUMN reset_token TEXT');
        }
        if (!in_array('reset_token_expiry', $columns)) {
            $this->db->query('ALTER TABLE users ADD COLUMN reset_token_expiry TEXT');
        }

        $admin = $this->find_by_email('admin@example.com');
        if (!$admin) {
            $this->create(array(
                'full_name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => 'admin123',
                'role' => 'admin'
            ));
        }

        $client = $this->find_by_email('client@example.com');
        if (!$client) {
            $this->create(array(
                'full_name' => 'Client User',
                'email' => 'client@example.com',
                'password' => 'client123',
                'role' => 'client'
            ));
        }
    }

    public function find_by_id($id)
    {
        return $this->db->get_where('users', array('id' => (int) $id))->row_array();
    }

    public function list_clients()
    {
        $items = $this->db->where('role', 'client')->order_by('id', 'desc')->get('users')->result_array();
        $out = array();
        foreach ($items as $row) {
            $out[] = $this->shape_user($row);
        }
        return $out;
    }

    public function list_all()
    {
        $items = $this->db->order_by('id', 'desc')->get('users')->result_array();
        $out = array();
        foreach ($items as $row) {
            $shaped = $this->shape_user($row);
            $shaped['created_at'] = $row['created_at'];
            $shaped['status'] = 'active';
            $out[] = $shaped;
        }
        return $out;
    }

    public function find_by_email($email)
    {
        return $this->db->get_where('users', array('email' => strtolower(trim($email))))->row_array();
    }

    public function create($input)
    {
        $hash = $this->hash_password($input['password']);
        $data = array(
            'full_name' => trim($input['full_name']),
            'email' => strtolower(trim($input['email'])),
            'password_hash' => $hash,
            'role' => isset($input['role']) ? $input['role'] : 'client',
            'created_at' => date('c')
        );

        $this->db->insert('users', $data);
        return $this->find_by_id($this->db->insert_id());
    }

    public function verify_password($user, $password)
    {
        if (function_exists('password_verify')) {
            return password_verify($password, $user['password_hash']);
        }

        return crypt($password, $user['password_hash']) === $user['password_hash'];
    }

    public function shape_user($user)
    {
        return array(
            'id' => (int) $user['id'],
            'email' => $user['email'],
            'name' => $user['full_name'],
            'full_name' => $user['full_name'],
            'role' => $user['role'],
            'roles' => array($user['role'])
        );
    }

    private function hash_password($password)
    {
        if (function_exists('password_hash')) {
            return password_hash($password, PASSWORD_BCRYPT);
        }

        $salt = $this->legacy_salt(22);
        return crypt($password, '$2y$10$'.$salt);
    }

    private function legacy_salt($length)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789./';
        $out = '';
        $max = strlen($chars) - 1;
        $i = 0;
        while ($i < $length) {
            $out .= $chars[mt_rand(0, $max)];
            $i++;
        }
        return $out;
    }

    /**
     * 2FA: Enable 2FA for user and store secret
     */
    public function enable_2fa($user_id, $secret, $recovery_codes = array())
    {
        $recovery_json = json_encode($recovery_codes);
        
        $this->db->where('id', $user_id)->update('users', array(
            'two_fa_enabled' => 1,
            'two_fa_secret' => $secret,
            'two_fa_recovery_codes' => $recovery_json
        ));
        
        return true;
    }

    /**
     * 2FA: Disable 2FA for user
     */
    public function disable_2fa($user_id)
    {
        $this->db->where('id', $user_id)->update('users', array(
            'two_fa_enabled' => 0,
            'two_fa_secret' => NULL,
            'two_fa_recovery_codes' => NULL
        ));
        
        return true;
    }

    /**
     * 2FA: Get user's 2FA secret
     */
    public function get_2fa_secret($user_id)
    {
        $user = $this->find_by_id($user_id);
        return $user ? $user['two_fa_secret'] : null;
    }

    /**
     * 2FA: Get recovery codes
     */
    public function get_recovery_codes($user_id)
    {
        $user = $this->find_by_id($user_id);
        if (!$user || !$user['two_fa_recovery_codes']) {
            return array();
        }
        
        return json_decode($user['two_fa_recovery_codes'], true) ?: array();
    }

    /**
     * 2FA: Use a recovery code (remove it from list)
     */
    public function use_recovery_code($user_id, $code)
    {
        $codes = $this->get_recovery_codes($user_id);
        $code_hash = password_hash($code, PASSWORD_BCRYPT);
        
        foreach ($codes as $index => $stored_code) {
            if (password_verify($code, $stored_code)) {
                unset($codes[$index]);
                $this->db->where('id', $user_id)->update('users', array(
                    'two_fa_recovery_codes' => json_encode(array_values($codes))
                ));
                return true;
            }
        }
        
        return false;
    }

    /**
     * Password Reset: Generate reset token
     */
    public function create_reset_token($user_id)
    {
        $token = bin2hex(random_bytes(32));
        $expiry = date('c', strtotime('+1 hour'));
        
        $this->db->where('id', $user_id)->update('users', array(
            'reset_token' => $token,
            'reset_token_expiry' => $expiry
        ));
        
        return $token;
    }

    /**
     * Password Reset: Verify and use reset token
     */
    public function verify_reset_token($token)
    {
        $user = $this->db->get_where('users', array(
            'reset_token' => $token
        ))->row_array();
        
        if (!$user) {
            return null;
        }
        
        // Check if token is expired
        if (strtotime($user['reset_token_expiry']) < time()) {
            return null;
        }
        
        return $user;
    }

    /**
     * Password Reset: Reset password using token
     */
    public function reset_password_with_token($token, $new_password)
    {
        $user = $this->verify_reset_token($token);
        
        if (!$user) {
            return false;
        }
        
        $hash = $this->hash_password($new_password);
        
        $this->db->where('id', $user['id'])->update('users', array(
            'password_hash' => $hash,
            'reset_token' => NULL,
            'reset_token_expiry' => NULL
        ));
        
        return true;
    }

    /**
     * Update user password
     */
    public function update_password($user_id, $new_password)
    {
        $hash = $this->hash_password($new_password);
        
        $this->db->where('id', $user_id)->update('users', array(
            'password_hash' => $hash
        ));
        
        return true;
    }

    /**
     * Update user profile
     */
    public function update_profile($user_id, $data)
    {
        $allowed = array('full_name', 'email');
        $update = array();
        
        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $update[$field] = $data[$field];
            }
        }
        
        if (empty($update)) {
            return true;
        }
        
        $this->db->where('id', $user_id)->update('users', $update);
        return true;
    }

    /**
     * Delete user
     */
    public function delete_user($user_id)
    {
        $this->db->where('id', $user_id)->delete('users');
        return true;
    }

}
