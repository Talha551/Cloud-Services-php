<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solusvm_client
{
    private $base_url = '';
    private $api_token = '';
    private $api_prefix = '/api/v1';
    private $timeout = 30;

    public function __construct()
    {
        $ci =& get_instance();
        $ci->load->config('solusvm', true);
        $cfg = $ci->config->item('solusvm', 'solusvm');
        if (is_array($cfg)) {
            $this->base_url = isset($cfg['base_url']) ? rtrim($cfg['base_url'], '/') : '';
            $this->api_token = isset($cfg['api_token']) ? trim($cfg['api_token']) : '';
            $this->api_prefix = isset($cfg['api_prefix']) ? $cfg['api_prefix'] : '/api/v1';
            $this->timeout = isset($cfg['timeout']) ? (int) $cfg['timeout'] : 30;
        }
    }

    public function is_configured()
    {
        return $this->base_url !== '' && $this->api_token !== '';
    }

    public function create_server($payload)
    {
        return $this->request('POST', '/servers', $payload);
    }

    public function reinstall_server($server_id, $payload)
    {
        $result = $this->request('POST', '/servers/'.(int) $server_id.'/reinstall', $payload);
        if ($result['ok']) {
            return $result;
        }

        // Fallback for providers that expect alternate field names.
        $fallback_payloads = array();
        if (is_array($payload) && isset($payload['os']) && (int) $payload['os'] > 0) {
            $p1 = $payload;
            $p1['os_image_version'] = (int) $payload['os'];
            unset($p1['os']);
            $fallback_payloads[] = $p1;

            $p2 = $payload;
            $p2['os_image_id'] = (int) $payload['os'];
            unset($p2['os']);
            $fallback_payloads[] = $p2;
        }
        if (is_array($payload) && isset($payload['application']) && (int) $payload['application'] > 0) {
            $p3 = $payload;
            $p3['application_id'] = (int) $payload['application'];
            unset($p3['application']);
            $fallback_payloads[] = $p3;
        }

        foreach ($fallback_payloads as $fp) {
            $retry = $this->request('POST', '/servers/'.(int) $server_id.'/reinstall', $fp);
            if ($retry['ok']) {
                return $retry;
            }
        }

        return $result;
    }

    public function get_server($server_id)
    {
        return $this->request('GET', '/servers/'.(int) $server_id);
    }

    public function list_servers()
    {
        return $this->request('GET', '/servers');
    }

    public function start_server($server_id)
    {
        return $this->request('POST', '/servers/'.(int) $server_id.'/start');
    }

    public function stop_server($server_id)
    {
        return $this->request('POST', '/servers/'.(int) $server_id.'/stop');
    }

    public function restart_server($server_id)
    {
        return $this->request('POST', '/servers/'.(int) $server_id.'/restart');
    }

    public function vnc_up($server_id)
    {
        return $this->request('POST', '/servers/'.(int) $server_id.'/vnc_up');
    }

    public function change_root_password($server_id, $password)
    {
        $sid = (int) $server_id;
        $payload = array('password' => (string) $password);

        // Try known SolusVM 2.x endpoints for root password change.
        $endpoints = array(
            array('POST',  '/servers/'.$sid.'/reset_password', array(
                'password' => (string) $password,
                'send_password_to_current_user' => false,
            )),
            array('POST',  '/servers/'.$sid.'/change-root-password'),
            array('POST',  '/servers/'.$sid.'/change_root_password'),
            array('POST',  '/servers/'.$sid.'/change_password'),
            array('PUT',   '/servers/'.$sid.'/password'),
            array('PATCH', '/servers/'.$sid, $payload),
        );

        $last_result = null;
        foreach ($endpoints as $ep) {
            $method = $ep[0];
            $path   = $ep[1];
            $body   = isset($ep[2]) ? $ep[2] : $payload;
            $result = $this->request($method, $path, $body);
            $last_result = $result;
            if ($result['ok']) {
                return $result;
            }
            // Stop on auth/validation errors — no point trying other endpoints.
            if (in_array($result['status'], array(401, 403, 422, 400), true)) {
                return $result;
            }
            // Only continue on 404/405 (endpoint not found on this provider).
        }

        // DO NOT fall back to reinstall — that would wipe the OS.
        // Return the last failure with a clear message.
        if ($last_result !== null && empty($last_result['error'])) {
            $last_result['error'] = 'This SolusVM installation does not expose a root password change endpoint.';
        }
        return $last_result ?: array(
            'ok' => false, 'status' => 0,
            'error' => 'Password change not supported by provider API.',
            'data' => null,
        );
    }

    public function update_account_password($password)
    {
        $payload = array('password' => (string) $password);
        return $this->request('PATCH', '/account', $payload);
    }

    public function list_applications()
    {
        return $this->request('GET', '/applications');
    }

    public function list_os_images()
    {
        $paths = array('/os-image-versions', '/os_images', '/os-images');
        foreach ($paths as $path) {
            $result = $this->request('GET', $path);
            if ($result['ok'] && isset($result['data']['data']) && is_array($result['data']['data'])) {
                return $result;
            }
        }

        return array(
            'ok' => false,
            'status' => 404,
            'error' => 'OS list endpoint not available on provider',
            'data' => null,
        );
    }

    public function list_plans()
    {
        $paths = array('/plans', '/plan-versions');
        foreach ($paths as $path) {
            $result = $this->request('GET', $path);
            if ($result['ok'] && isset($result['data']['data']) && is_array($result['data']['data'])) {
                return $result;
            }
        }

        return array(
            'ok' => false,
            'status' => 404,
            'error' => 'Plan list endpoint not available on provider',
            'data' => null,
        );
    }

    public function list_locations()
    {
        $paths = array('/locations');
        foreach ($paths as $path) {
            $result = $this->request('GET', $path);
            if ($result['ok'] && isset($result['data']['data']) && is_array($result['data']['data'])) {
                return $result;
            }
        }

        return array(
            'ok' => false,
            'status' => 404,
            'error' => 'Location list endpoint not available on provider',
            'data' => null,
        );
    }

    public function request($method, $path, $payload = null)
    {
        if (!$this->is_configured()) {
            return array(
                'ok' => false,
                'status' => 0,
                'error' => 'SolusVM is not configured. Set SOLUSVM_BASE_URL and SOLUSVM_API_TOKEN.',
                'data' => null,
            );
        }

        $url = $this->base_url.$this->api_prefix.$path;
        $ch = curl_init($url);
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->api_token,
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

        if ($payload !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        }

        $raw = curl_exec($ch);
        $errno = curl_errno($ch);
        $err = curl_error($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        log_message('debug', '[SolusVM] '.$method.' '.$url.' => HTTP '.$status
            .($payload !== null ? ' | payload: '.json_encode($payload) : '')
            .' | response: '.substr((string) $raw, 0, 500));

        if ($errno) {
            return array(
                'ok' => false,
                'status' => 0,
                'error' => 'cURL error: '.$err,
                'data' => null,
            );
        }

        $decoded = json_decode((string) $raw, true);
        $ok = $status >= 200 && $status < 300;
        $business_error = null;

        if ($ok && is_array($decoded)) {
            if (array_key_exists('success', $decoded) && $decoded['success'] === false) {
                $ok = false;
                $business_error = isset($decoded['message']) ? (string) $decoded['message'] : 'Provider returned success=false';
            } elseif (array_key_exists('ok', $decoded) && $decoded['ok'] === false) {
                $ok = false;
                $business_error = isset($decoded['message']) ? (string) $decoded['message'] : 'Provider returned ok=false';
            } elseif (isset($decoded['status']) && is_string($decoded['status'])) {
                $st = strtolower(trim($decoded['status']));
                if (in_array($st, array('error', 'failed', 'failure'), true)) {
                    $ok = false;
                    $business_error = isset($decoded['message']) ? (string) $decoded['message'] : ('Provider status: '.$decoded['status']);
                }
            }
        }

        return array(
            'ok' => $ok,
            'status' => $status,
            'error' => $ok ? null : ($business_error !== null ? $business_error : ('HTTP '.$status)),
            'data' => is_array($decoded) ? $decoded : array('raw' => $raw),
        );
    }
}
