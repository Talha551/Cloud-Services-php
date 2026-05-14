<?php
/**
 * ============================================================================
 * Base API Controller
 * ============================================================================
 * 
 * PURPOSE: Shared functionality for all API controllers (auth, response formatting)
 * STATUS: 90% Complete (core features working, rate limiting/logging missing)
 * 
 * KEY FEATURES:
 *   1. JWT Authentication Support
 *      - Bearer token parsing from Authorization header
 *      - JWT decode & validation via jwt_service library
 *      - Fallback to session-based auth
 *   
 *   2. Input Handling
 *      - JSON body parsing (raw input stream)
 *      - Form POST data support
 *      - Default value fallbacks
 *   
 *   3. Response Formatting
 *      - JSON output with status codes
 *      - Consistent error responses (401, 403, 404, 422)
 *      - Proper Content-Type headers
 *   
 *   4. Authorization Helpers
 *      - current_user(): Get authenticated user (JWT or session)
 *      - require_login_json(): Enforce auth for API endpoints
 *      - require_login_web(): Enforce auth for web pages
 *      - is_admin(): Check admin role
 * 
 * AUTHENTICATION FLOW:
 *   1. Check Bearer token in Authorization header
 *   2. Decode JWT using jwt_service library
 *   3. Verify user exists in database
 *   4. Fallback to CI session if token absent
 *   5. Return null if no auth found
 * 
 * PROTECTED METHODS:
 *   - input_data($key, $default)      - Get input from POST/JSON
 *   - json($payload, $status_code)   - Output JSON response
 *   - current_user()                  - Get logged-in user
 *   - require_login_json()            - Enforce auth (API)
 *   - require_login_web()             - Enforce auth (web)
 *   - is_admin($user)                 - Check admin role
 * 
 * SECURITY NOTES:
 * ⚠️  No rate limiting implemented
 * ⚠️  No CORS headers (add if needed for React app)
 * ⚠️  No request logging/audit trail
 * ⚠️  JWT tokens not validated for expiry (should add)
 * ⚠️  No API token blacklist on logout
 * 
 * TODO:
 *   - Add request/response logging
 *   - Implement rate limiting
 *   - Add CORS support for React frontend
 *   - Add request ID tracking
 *   - Implement token expiry validation
 * ============================================================================
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    private $json_body = NULL;
    private $request_start_time;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('jwt_service');
        $this->request_start_time = microtime(true);
    }

    protected function input_data($key, $default)
    {
        $post_value = $this->input->post($key, true);
        if ($post_value !== NULL && $post_value !== '') {
            return $post_value;
        }

        if ($this->json_body === NULL) {
            $raw = $this->input->raw_input_stream;
            $decoded = json_decode($raw, true);
            $this->json_body = is_array($decoded) ? $decoded : array();
        }

        if (isset($this->json_body[$key])) {
            return $this->json_body[$key];
        }

        return $default;
    }

    protected function json($payload, $status_code)
    {
        $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($payload));
    }

    protected function current_user()
    {
        $token = $this->bearer_token();
        if ($token) {
            $payload = $this->jwt_service->decode($token);
            if ($payload && isset($payload['id'])) {
                $user = $this->User_model->find_by_id((int) $payload['id']);
                if ($user) {
                    return $user;
                }
            }
        }

        $user_id = (int) $this->session->userdata('user_id');
        if (!$user_id) {
            return NULL;
        }

        return $this->User_model->find_by_id($user_id);
    }

    protected function require_login_json()
    {
        $user = $this->current_user();
        if (!$user) {
            $this->json(array('success' => false, 'message' => 'Unauthorized'), 401);
            return NULL;
        }
        return $user;
    }

    protected function require_login_web()
    {
        if (!$this->session->userdata('user_id')) {
            redirect('login');
            return false;
        }
        return true;
    }

    protected function is_admin($user)
    {
        return is_array($user) && isset($user['role']) && $user['role'] === 'admin';
    }

    /**
     * Apply rate limiting for endpoint
     * 
     * @param string $key Unique identifier (email, IP, endpoint)
     * @param int $limit Requests allowed
     * @param int $window Time window in seconds
     */
    protected function apply_rate_limit($key, $limit = 60, $window = 60)
    {
        $this->load->library('Rate_limiter');
        if (!$this->rate_limiter->is_allowed($key, $limit, $window)) {
            $remaining = $this->rate_limiter->get_remaining($key, $limit, $window);
            $reset_time = $this->rate_limiter->get_reset_time($key, $window);
            
            $this->output
                ->set_status_header(429)
                ->set_header('X-RateLimit-Limit: '.$limit)
                ->set_header('X-RateLimit-Remaining: '.$remaining)
                ->set_header('X-RateLimit-Reset: '.(time() + $reset_time))
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode(array(
                    'success' => false,
                    'message' => 'Rate limit exceeded',
                    'retry_after' => $reset_time
                )));
            
            exit;
        }
        
        // Set rate limit headers
        
        $remaining = $this->rate_limiter->get_remaining($key, $limit, $window);
        $reset_time = $this->rate_limiter->get_reset_time($key, $window);
        $this->output
            ->set_header('X-RateLimit-Limit: '.$limit)
            ->set_header('X-RateLimit-Remaining: '.$remaining)
            ->set_header('X-RateLimit-Reset: '.(time() + $reset_time));
    }

    /**
     * Log API request
     */
    protected function log_request($method = null, $uri = null, $status_code = null, $data = null)
    {
        if ($method === null) {
            $method = $_SERVER['REQUEST_METHOD'];
        }
        
        if ($uri === null) {
            $uri = $_SERVER['REQUEST_URI'];
        }
        
        if ($status_code === null) {
            $status_code = http_response_code();
        }
        
        $response_time = microtime(true) - $this->request_start_time;
        $user = $this->current_user();
        $user_id = $user ? $user['id'] : null;
            $this->load->library('Request_logger');
        
        $this->request_logger->log($method, $uri, $status_code, $response_time, $user_id, $data ?? array());
    }

    /**
     * Set CORS headers for React app
     */
    protected function set_cors_headers()
    {
        $this->output
            ->set_header('Access-Control-Allow-Origin: *')
            ->set_header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS')
            ->set_header('Access-Control-Allow-Headers: Content-Type, Authorization')
            ->set_header('Access-Control-Max-Age: 86400');
    }

    private function bearer_token()
    {
        $headers = array();
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        }

        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers['Authorization'] = $_SERVER['HTTP_AUTHORIZATION'];
        }

        if (!isset($headers['Authorization'])) {
            return NULL;
        }

        $value = trim($headers['Authorization']);
        if (stripos($value, 'Bearer ') !== 0) {
            return NULL;
        }

        return trim(substr($value, 7));
    }
}
