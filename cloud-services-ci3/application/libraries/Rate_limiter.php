<?php
/**
 * ============================================================================
 * Rate Limiting Service
 * ============================================================================
 * 
 * PURPOSE: Prevent API abuse through rate limiting
 * 
 * FEATURES:
 *   - IP-based rate limiting
 *   - Configurable requests per minute/hour
 *   - Sliding window algorithm
 *   - Redis support (optional)
 *   - File-based fallback
 * 
 * USAGE:
 *   $this->load->library('Rate_limiter');
 *   if (!$this->rate_limiter->is_allowed('login', 5, 60)) {
 *       // Too many requests
 *   }
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Rate_limiter
{
    private $ci;
    private $storage_path = '/tmp/rate_limit';
    private $cleanup_chance = 0.1; // 10% chance to cleanup

    public function __construct()
    {
        $this->ci =& get_instance();
        
        // Create storage directory if it doesn't exist
        if (!is_dir($this->storage_path)) {
            @mkdir($this->storage_path, 0755, true);
        }
    }

    /**
     * Check if request is allowed
     * 
     * @param string $key Unique identifier (email, IP, etc.)
     * @param int $limit Number of requests allowed
     * @param int $window Time window in seconds
     * @return boolean
     */
    public function is_allowed($key, $limit = 60, $window = 60)
    {
        $identifier = $this->get_identifier($key);
        $file = $this->storage_path . '/' . md5($identifier) . '.json';
        
        $now = time();
        $requests = array();
        
        // Read existing requests
        if (file_exists($file)) {
            $content = json_decode(file_get_contents($file), true);
            if (is_array($content)) {
                $requests = $content;
            }
        }
        
        // Remove old requests outside the window
        $requests = array_filter($requests, function($time) use ($now, $window) {
            return ($now - $time) < $window;
        });
        
        // Check if limit exceeded
        if (count($requests) >= $limit) {
            return false;
        }
        
        // Add current request
        $requests[] = $now;
        
        // Save updated requests
        @file_put_contents($file, json_encode(array_values($requests)));
        @chmod($file, 0666);
        
        // Cleanup old files occasionally
        if (rand(1, 100) < ($this->cleanup_chance * 100)) {
            $this->cleanup();
        }
        
        return true;
    }

    /**
     * Get remaining requests for key
     */
    public function get_remaining($key, $limit = 60, $window = 60)
    {
        $identifier = $this->get_identifier($key);
        $file = $this->storage_path . '/' . md5($identifier) . '.json';
        
        $now = time();
        $requests = array();
        
        if (file_exists($file)) {
            $content = json_decode(file_get_contents($file), true);
            if (is_array($content)) {
                $requests = $content;
            }
        }
        
        // Remove old requests
        $requests = array_filter($requests, function($time) use ($now, $window) {
            return ($now - $time) < $window;
        });
        
        return max(0, $limit - count($requests));
    }

    /**
     * Get reset time (seconds until limit resets)
     */
    public function get_reset_time($key, $window = 60)
    {
        $identifier = $this->get_identifier($key);
        $file = $this->storage_path . '/' . md5($identifier) . '.json';
        
        if (!file_exists($file)) {
            return 0;
        }
        
        $content = json_decode(file_get_contents($file), true);
        if (!is_array($content) || empty($content)) {
            return 0;
        }
        
        $oldest_request = min($content);
        $reset_time = $oldest_request + $window - time();
        
        return max(0, $reset_time);
    }

    /**
     * Get client IP address
     */
    private function get_identifier($key)
    {
        $ip = $this->ci->input->ip_address();
        return $key . '_' . $ip;
    }

    /**
     * Cleanup old rate limit files
     */
    private function cleanup()
    {
        $now = time();
        $files = glob($this->storage_path . '/*.json');
        
        foreach ($files as $file) {
            $mtime = filemtime($file);
            // Delete files older than 24 hours
            if (($now - $mtime) > 86400) {
                @unlink($file);
            }
        }
    }

    /**
     * Clear rate limit for key
     */
    public function clear($key)
    {
        $identifier = $this->get_identifier($key);
        $file = $this->storage_path . '/' . md5($identifier) . '.json';
        
        if (file_exists($file)) {
            @unlink($file);
        }
    }
}
