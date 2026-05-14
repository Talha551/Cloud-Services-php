<?php
/**
 * ============================================================================
 * API Request Logger
 * ============================================================================
 * 
 * PURPOSE: Log all API requests for monitoring, debugging, and audit trails
 * 
 * FEATURES:
 *   - Log all requests to file/database
 *   - Track response times
 *   - Log errors and exceptions
 *   - Filter sensitive data (passwords, tokens)
 * 
 * USAGE:
 *   $this->load->library('Request_logger');
 *   $this->request_logger->log('GET /api/auth/profile', 200, 0.025);
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Request_logger
{
    private $ci;
    private $log_path;
    private $sensitive_keys = array('password', 'token', 'secret', 'api_key', 'access_token', 'refresh_token');

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->log_path = APPPATH . 'logs/api_requests.log';
    }

    /**
     * Log API request
     */
    public function log($method, $uri, $status_code, $response_time = 0, $user_id = null, $data = array())
    {
        $entry = array(
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => strtoupper($method),
            'uri' => $uri,
            'status_code' => (int) $status_code,
            'response_time_ms' => round($response_time * 1000, 2),
            'user_id' => $user_id,
            'ip_address' => $this->ci->input->ip_address(),
            'user_agent' => substr($this->ci->input->user_agent(), 0, 255),
            'data' => $this->sanitize($data)
        );

        $this->write_log($entry);
    }

    /**
     * Log error
     */
    public function log_error($message, $code = null, $file = null, $line = null)
    {
        $entry = array(
            'timestamp' => date('Y-m-d H:i:s'),
            'type' => 'ERROR',
            'message' => $message,
            'code' => $code,
            'file' => $file,
            'line' => $line,
            'ip_address' => $this->ci->input->ip_address()
        );

        $this->write_log($entry);
    }

    /**
     * Sanitize sensitive data
     */
    private function sanitize($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        $sanitized = array();
        
        foreach ($data as $key => $value) {
            if ($this->is_sensitive($key)) {
                $sanitized[$key] = '***REDACTED***';
            } else if (is_array($value)) {
                $sanitized[$key] = $this->sanitize($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Check if key is sensitive
     */
    private function is_sensitive($key)
    {
        $key_lower = strtolower($key);
        
        foreach ($this->sensitive_keys as $sensitive) {
            if (strpos($key_lower, $sensitive) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Write log entry
     */
    private function write_log($entry)
    {
        $line = json_encode($entry) . PHP_EOL;
        @file_put_contents($this->log_path, $line, FILE_APPEND | LOCK_EX);
    }

    /**
     * Get recent logs
     */
    public function get_recent($limit = 100)
    {
        if (!file_exists($this->log_path)) {
            return array();
        }

        $lines = array_slice(
            file($this->log_path, FILE_SKIP_EMPTY_LINES),
            -$limit
        );

        $logs = array();
        foreach ($lines as $line) {
            $entry = json_decode(trim($line), true);
            if ($entry) {
                $logs[] = $entry;
            }
        }

        return $logs;
    }

    /**
     * Clear old logs
     */
    public function cleanup($days = 30)
    {
        if (!file_exists($this->log_path)) {
            return;
        }

        $lines = file($this->log_path, FILE_SKIP_EMPTY_LINES);
        $cutoff_time = strtotime("-{$days} days");
        $filtered = array();

        foreach ($lines as $line) {
            $entry = json_decode(trim($line), true);
            if ($entry && strtotime($entry['timestamp']) > $cutoff_time) {
                $filtered[] = $line;
            }
        }

        @file_put_contents($this->log_path, implode('', $filtered), LOCK_EX);
    }

    /**
     * Get statistics
     */
    public function get_stats($hours = 1)
    {
        $logs = $this->get_recent(10000); // Get all recent logs
        $cutoff_time = time() - ($hours * 3600);

        $stats = array(
            'total_requests' => 0,
            'error_count' => 0,
            'avg_response_time' => 0,
            'status_codes' => array(),
            'endpoints' => array()
        );

        $response_times = array();

        foreach ($logs as $log) {
            if ($log['type'] === 'ERROR') {
                $stats['error_count']++;
                continue;
            }

            if (strtotime($log['timestamp']) < $cutoff_time) {
                continue;
            }

            $stats['total_requests']++;
            $response_times[] = $log['response_time_ms'];

            // Track status codes
            $status = $log['status_code'];
            $stats['status_codes'][$status] = ($stats['status_codes'][$status] ?? 0) + 1;

            // Track endpoints
            $endpoint = $log['method'] . ' ' . $log['uri'];
            $stats['endpoints'][$endpoint] = ($stats['endpoints'][$endpoint] ?? 0) + 1;
        }

        if (!empty($response_times)) {
            $stats['avg_response_time'] = round(array_sum($response_times) / count($response_times), 2);
        }

        return $stats;
    }
}
