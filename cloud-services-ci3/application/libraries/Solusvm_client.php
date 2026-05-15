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
        $base = is_array($payload) ? $payload : array();

        $fallback_payloads = array();
        $fallback_payloads[] = $base;

        if (isset($base['plan']) && !isset($base['plan_id'])) {
            $p = $base;
            $p['plan_id'] = (int) $base['plan'];
            $fallback_payloads[] = $p;
        }
        if (isset($base['location']) && !isset($base['location_id'])) {
            $p = $base;
            $p['location_id'] = (int) $base['location'];
            $fallback_payloads[] = $p;
        }
        if (isset($base['os']) && !isset($base['os_image_version'])) {
            $p = $base;
            $p['os_image_version'] = (int) $base['os'];
            $fallback_payloads[] = $p;
        }
        if (isset($base['os']) && !isset($base['os_image_id'])) {
            $p = $base;
            $p['os_image_id'] = (int) $base['os'];
            $fallback_payloads[] = $p;
        }

        $unique_payloads = array();
        foreach ($fallback_payloads as $fp) {
            $key = json_encode($fp);
            if (!isset($unique_payloads[$key])) {
                $unique_payloads[$key] = $fp;
            }
        }

        $endpoints = array('/servers', '/servers/create');
        $last_result = null;

        foreach ($endpoints as $path) {
            foreach ($unique_payloads as $fp) {
                $result = $this->request('POST', $path, $fp);
                $last_result = $result;
                if ($result['ok']) {
                    return $result;
                }
                if (in_array((int) $result['status'], array(401, 403), true)) {
                    return $result;
                }
            }
        }

        return $last_result ?: array(
            'ok' => false,
            'status' => 0,
            'error' => 'Provider create request failed before response.',
            'data' => null,
        );
    }

    public function reinstall_server($server_id, $payload)
    {
        $result = $this->request('POST', '/servers/'.(int) $server_id.'/reinstall', $payload);
        if ($result['ok']) {
            return $result;
        }

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

    public function delete_server($server_id)
    {
        $sid = (int) $server_id;
        $attempts = array(
            array('DELETE', '/servers/'.$sid),
            array('POST', '/servers/'.$sid.'/delete'),
            array('POST', '/servers/'.$sid.'/destroy'),
        );

        $last_result = null;
        foreach ($attempts as $attempt) {
            $result = $this->request($attempt[0], $attempt[1]);
            $last_result = $result;
            if ($result['ok']) {
                return $result;
            }
            if (in_array((int) $result['status'], array(401, 403), true)) {
                return $result;
            }
        }

        return $last_result ?: array(
            'ok' => false,
            'status' => 0,
            'error' => 'Provider delete request failed before response.',
            'data' => null,
        );
    }

    public function vnc_up($server_id)
    {
        return $this->request('POST', '/servers/'.(int) $server_id.'/vnc_up');
    }

    public function change_root_password($server_id, $password)
    {
        $sid = (int) $server_id;
        $payload = array('password' => (string) $password);

        $endpoints = array(
            array('POST', '/servers/'.$sid.'/reset_password', array(
                'password' => (string) $password,
                'send_password_to_current_user' => false,
            )),
            array('POST', '/servers/'.$sid.'/change-root-password'),
            array('POST', '/servers/'.$sid.'/change_root_password'),
            array('POST', '/servers/'.$sid.'/change_password'),
            array('PUT', '/servers/'.$sid.'/password'),
            array('PATCH', '/servers/'.$sid, $payload),
        );

        $last_result = null;
        foreach ($endpoints as $ep) {
            $method = $ep[0];
            $path = $ep[1];
            $body = isset($ep[2]) ? $ep[2] : $payload;
            $result = $this->request($method, $path, $body);
            $last_result = $result;
            if ($result['ok']) {
                return $result;
            }
            if (in_array((int) $result['status'], array(401, 403, 422, 400), true)) {
                return $result;
            }
        }

        if ($last_result !== null && empty($last_result['error'])) {
            $last_result['error'] = 'This SolusVM installation does not expose a root password change endpoint.';
        }

        return $last_result ?: array(
            'ok' => false,
            'status' => 0,
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
        $paths = array('/applications', '/apps');
        foreach ($paths as $path) {
            $result = $this->request('GET', $path);
            if ($result['ok']) {
                $rows = $this->extract_rows_from_decoded(isset($result['data']) ? $result['data'] : array());
                if (!empty($rows)) {
                    return $result;
                }
            }
        }

        return $this->request('GET', '/applications');
    }

    public function list_os_images()
    {
        $paths = array('/os-image-versions', '/os_images', '/os-images', '/os-images/versions');
        foreach ($paths as $path) {
            $result = $this->request('GET', $path);
            if ($result['ok']) {
                $rows = $this->extract_rows_from_decoded(isset($result['data']) ? $result['data'] : array());
                if (!empty($rows)) {
                    return $result;
                }
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
            if ($result['ok']) {
                $rows = $this->extract_rows_from_decoded(isset($result['data']) ? $result['data'] : array());
                if (!empty($rows)) {
                    return $result;
                }
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
            if ($result['ok']) {
                $rows = $this->extract_rows_from_decoded(isset($result['data']) ? $result['data'] : array());
                if (!empty($rows)) {
                    return $result;
                }
            }
        }

        return array(
            'ok' => false,
            'status' => 404,
            'error' => 'Location list endpoint not available on provider',
            'data' => null,
        );
    }

    private function extract_rows_from_decoded($decoded)
    {
        if (!is_array($decoded) || empty($decoded)) {
            return array();
        }

        if (array_keys($decoded) === range(0, count($decoded) - 1)) {
            return $decoded;
        }

        if (isset($decoded['data']) && is_array($decoded['data'])) {
            $nested = $decoded['data'];
            if (array_keys($nested) === range(0, count($nested) - 1)) {
                return $nested;
            }
            foreach (array('data', 'items', 'results', 'rows') as $k) {
                if (isset($nested[$k]) && is_array($nested[$k]) && array_keys($nested[$k]) === range(0, count($nested[$k]) - 1)) {
                    return $nested[$k];
                }
            }
        }

        foreach (array('items', 'results', 'rows') as $k) {
            if (isset($decoded[$k]) && is_array($decoded[$k]) && array_keys($decoded[$k]) === range(0, count($decoded[$k]) - 1)) {
                return $decoded[$k];
            }
        }

        return array();
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
