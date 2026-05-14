<?php
/**
 * ============================================================================
 * TOTP (Time-based One-Time Password) Service
 * ============================================================================
 * 
 * PURPOSE: Generate and validate 2FA TOTP codes for user authentication
 * 
 * FEATURES:
 *   - RFC 6238 compliant TOTP generation
 *   - 30-second time window (standard)
 *   - 6-digit codes
 *   - QR code generation for easy setup
 *   - Recovery code generation/validation
 * 
 * USAGE:
 *   $this->load->library('Totp_service');
 *   $secret = $this->totp_service->generate_secret();
 *   $qr_url = $this->totp_service->get_qr_code($secret, 'user@example.com');
 *   $is_valid = $this->totp_service->verify_code($secret, '123456');
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Totp_service
{
    /**
     * Generate a random base32 secret for TOTP
     */
    public function generate_secret($length = 32)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        
        for ($i = 0; $i < $length; $i++) {
            $secret .= $chars[rand(0, strlen($chars) - 1)];
        }
        
        return $secret;
    }

    /**
     * Generate TOTP code from secret
     */
    public function generate_code($secret, $time = null)
    {
        if ($time === null) {
            $time = floor(time() / 30);
        }
        
        $secret = $this->base32_decode($secret);
        $hmac = hash_hmac('sha1', pack('N', $time), $secret, true);
        $offset = ord(substr($hmac, -1)) & 0x0f;
        $code = (unpack('N', substr($hmac, $offset, 4))[1] & 0x7fffffff) % 1000000;
        
        return str_pad($code, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Verify TOTP code with time window tolerance
     */
    public function verify_code($secret, $code, $window = 1)
    {
        $time = floor(time() / 30);
        
        // Check current time and previous/next windows
        for ($i = -$window; $i <= $window; $i++) {
            if ($this->generate_code($secret, $time + $i) === str_pad($code, 6, '0', STR_PAD_LEFT)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Generate QR code URL for authenticator apps
     */
    public function get_qr_code($secret, $email, $company = 'Cloud Services')
    {
        $label = urlencode("{$company} ({$email})");
        $params = "secret={$secret}&issuer={$company}";
        
        return "otpauth://totp/{$label}?{$params}";
    }

    /**
     * Generate QR code as PNG image using Google Charts API
     */
    public function get_qr_image($secret, $email, $company = 'Cloud Services')
    {
        $qr_url = $this->get_qr_code($secret, $email, $company);
        $encoded = urlencode($qr_url);
        
        return "https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl={$encoded}";
    }

    /**
     * Generate recovery codes (backup codes)
     */
    public function generate_recovery_codes($count = 10, $length = 8)
    {
        $codes = [];
        
        for ($i = 0; $i < $count; $i++) {
            $code = '';
            for ($j = 0; $j < $length; $j++) {
                $code .= rand(0, 9);
            }
            $codes[] = $code;
        }
        
        return $codes;
    }

    /**
     * Hash recovery code for storage
     */
    public function hash_recovery_code($code)
    {
        return password_hash($code, PASSWORD_BCRYPT);
    }

    /**
     * Verify recovery code
     */
    public function verify_recovery_code($code, $hash)
    {
        return password_verify($code, $hash);
    }

    /**
     * Base32 decode (RFC 4648)
     */
    private function base32_decode($input)
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $input = strtoupper($input);
        $bits = '';
        
        for ($i = 0; $i < strlen($input); $i++) {
            $val = strpos($alphabet, $input[$i]);
            if ($val === false) {
                return false;
            }
            $bits .= str_pad(decbin($val), 5, '0', STR_PAD_LEFT);
        }
        
        $output = '';
        for ($i = 0; $i < strlen($bits); $i += 8) {
            $byte = substr($bits, $i, 8);
            if (strlen($byte) < 8) {
                break;
            }
            $output .= chr(bindec(str_pad($byte, 8, '0', STR_PAD_LEFT)));
        }
        
        return $output;
    }
}
