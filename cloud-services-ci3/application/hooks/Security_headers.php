<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Security_headers
{
    public function apply()
    {
        $ci =& get_instance();

        $ci->output->set_header('X-Frame-Options: SAMEORIGIN');
        $ci->output->set_header('X-Content-Type-Options: nosniff');
        $ci->output->set_header('Referrer-Policy: strict-origin-when-cross-origin');
        $ci->output->set_header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

        // Keep CSP compatible with existing CDN usage (Tailwind/noVNC/Google Fonts).
        $csp = "default-src 'self'; "
            . "script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdn.jsdelivr.net https://unpkg.com; "
            . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; "
            . "font-src 'self' https://fonts.gstatic.com data:; "
            . "img-src 'self' data: https:; "
            . "connect-src 'self' ws: wss: https:; "
            . "frame-ancestors 'self';";
        $ci->output->set_header('Content-Security-Policy: '.$csp);
    }
}
