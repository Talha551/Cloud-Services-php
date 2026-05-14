<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jwt_service
{
    private $secret;

    public function __construct()
    {
        $this->secret = getenv('JWT_SECRET') ? getenv('JWT_SECRET') : 'change-me-secret';
    }

    public function encode($payload, $ttl_seconds)
    {
        $header = array('typ' => 'JWT', 'alg' => 'HS256');
        $now = time();
        $payload['iat'] = $now;
        $payload['exp'] = $now + (int) $ttl_seconds;

        $segments = array(
            $this->base64url_encode(json_encode($header)),
            $this->base64url_encode(json_encode($payload))
        );

        $signature = hash_hmac('sha256', implode('.', $segments), $this->secret, true);
        $segments[] = $this->base64url_encode($signature);

        return implode('.', $segments);
    }

    public function decode($jwt)
    {
        if (!is_string($jwt) || strpos($jwt, '.') === false) {
            return NULL;
        }

        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return NULL;
        }

        $header = json_decode($this->base64url_decode($parts[0]), true);
        $payload = json_decode($this->base64url_decode($parts[1]), true);
        $signature = $this->base64url_decode($parts[2]);

        if (!is_array($header) || !is_array($payload)) {
            return NULL;
        }
        if (!isset($header['alg']) || $header['alg'] !== 'HS256') {
            return NULL;
        }

        $expected = hash_hmac('sha256', $parts[0].'.'.$parts[1], $this->secret, true);
        if (!$this->hash_equals_safe($expected, $signature)) {
            return NULL;
        }

        if (isset($payload['exp']) && time() >= (int) $payload['exp']) {
            return NULL;
        }

        return $payload;
    }

    private function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64url_decode($data)
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }

    private function hash_equals_safe($known, $user)
    {
        if (function_exists('hash_equals')) {
            return hash_equals($known, $user);
        }

        if (!is_string($known) || !is_string($user) || strlen($known) !== strlen($user)) {
            return false;
        }

        $res = 0;
        $len = strlen($known);
        $i = 0;
        while ($i < $len) {
            $res |= ord($known[$i]) ^ ord($user[$i]);
            $i++;
        }

        return $res === 0;
    }
}
