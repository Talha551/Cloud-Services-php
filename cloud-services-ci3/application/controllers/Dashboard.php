<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    private function seed_rows($type)
    {
        if ($type === 'projects') {
            return array(
                array('name' => 'Production Cluster', 'description' => 'Main production workloads', 'servers_count' => 4, 'owner' => 'admin@example.com', 'created_at' => date('c')),
                array('name' => 'Staging', 'description' => 'Pre-release environment', 'servers_count' => 2, 'owner' => 'admin@example.com', 'created_at' => date('c'))
            );
        }
        if ($type === 'locations') {
            return array(
                array('name' => 'Frankfurt', 'country' => 'Germany', 'status' => 'active'),
                array('name' => 'Amsterdam', 'country' => 'Netherlands', 'status' => 'active'),
                array('name' => 'New York', 'country' => 'USA', 'status' => 'active')
            );
        }
        if ($type === 'backups') {
            return array(
                array('name' => 'daily-backup-001', 'type' => 'snapshot', 'size' => '18 GB', 'status' => 'completed'),
                array('name' => 'daily-backup-002', 'type' => 'snapshot', 'size' => '22 GB', 'status' => 'completed')
            );
        }
        if ($type === 'ip_blocks') {
            return array(
                array('cidr' => '192.0.2.0/28', 'version' => 'IPv4', 'status' => 'allocated'),
                array('cidr' => '2001:db8::/64', 'version' => 'IPv6', 'status' => 'allocated')
            );
        }
        if ($type === 'compute_resources') {
            return array(
                array('node' => 'compute-01', 'cpu' => '32 vCPU', 'memory' => '128 GB', 'status' => 'healthy'),
                array('node' => 'compute-02', 'cpu' => '48 vCPU', 'memory' => '256 GB', 'status' => 'healthy')
            );
        }
        if ($type === 'os_images') {
            return array(
                array('name' => 'Ubuntu 22.04 LTS', 'type' => 'linux', 'status' => 'available'),
                array('name' => 'Debian 12', 'type' => 'linux', 'status' => 'available'),
                array('name' => 'AlmaLinux 9', 'type' => 'linux', 'status' => 'available')
            );
        }
        return array();
    }

    private function ensure_admin()
    {
        if (!$this->require_login_web()) {
            return NULL;
        }

        $user = $this->current_user();
        if (!$this->is_admin($user)) {
            redirect('client');
            return NULL;
        }
        return $user;
    }

    private function ensure_client()
    {
        if (!$this->require_login_web()) {
            return NULL;
        }

        $user = $this->current_user();
        if ($this->is_admin($user)) {
            redirect('admin');
            return NULL;
        }

        return $user;
    }

    private function audit_event($user, $action, $details = array())
    {
        $user_id = is_array($user) && isset($user['id']) ? (int) $user['id'] : NULL;
        if (!$user) {
            return;
        }
        $ip = (string) $this->input->ip_address();
        $this->Service_model->add_audit_log($user_id, $action, $details, $ip);
    }

    private function extract_trailing_id($value)
    {
        $str = trim((string) $value);
        if ($str === '') {
            return 0;
        }

        if (ctype_digit($str)) {
            return (int) $str;
        }

        if (preg_match('/(\d+)\s*$/', $str, $m)) {
            return (int) $m[1];
        }

        return 0;
    }

    private function mapped_solus_id($raw_id, $map)
    {
        $id = (int) $raw_id;
        if ($id <= 0) {
            return 0;
        }
        if (is_array($map) && isset($map[$id])) {
            return (int) $map[$id];
        }
        if (is_array($map) && isset($map[(string) $id])) {
            return (int) $map[(string) $id];
        }
        return $id;
    }

    private function first_provider_id_from_rows($rows)
    {
        if (!is_array($rows) || empty($rows)) {
            return 0;
        }

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            if (isset($row['id']) && (int) $row['id'] > 0) {
                return (int) $row['id'];
            }
            if (isset($row['value']) && (int) $row['value'] > 0) {
                return (int) $row['value'];
            }
        }

        return 0;
    }

    private function get_solus_config()
    {
        $this->load->config('solusvm', true);
        $cfg = $this->config->item('solusvm', 'solusvm');
        if (!is_array($cfg)) {
            $cfg = $this->config->item('solusvm');
        }
        return is_array($cfg) ? $cfg : array();
    }

    private function provision_service_on_provider($service)
    {
        $cfg = $this->get_solus_config();

        $plan_map = isset($cfg['plan_map']) && is_array($cfg['plan_map']) ? $cfg['plan_map'] : array();
        $location_map = isset($cfg['location_map']) && is_array($cfg['location_map']) ? $cfg['location_map'] : array();
        $os_map = isset($cfg['os_map']) && is_array($cfg['os_map']) ? $cfg['os_map'] : array();

        $local_plan_id = isset($service['plan_id']) ? (int) $service['plan_id'] : 0;
        $raw_location_id = $this->extract_trailing_id(isset($service['location']) ? $service['location'] : '');
        $raw_os_id = $this->extract_trailing_id(isset($service['os']) ? $service['os'] : '');

        $solus_plan_id = $this->mapped_solus_id($local_plan_id, $plan_map);
        if ($solus_plan_id <= 0 && isset($cfg['default_plan_id'])) {
            $solus_plan_id = (int) $cfg['default_plan_id'];
        }
        $solus_location_id = $this->mapped_solus_id($raw_location_id, $location_map);
        $solus_os_id = $this->mapped_solus_id($raw_os_id, $os_map);

        if ($solus_plan_id <= 0 || $solus_location_id <= 0 || $solus_os_id <= 0) {
            return array(
                'ok' => false,
                'message' => 'Provider provisioning skipped: missing Solus plan/location/os mapping.',
                'remote_id' => 0,
            );
        }

        $hostname = trim((string) (isset($service['hostname']) ? $service['hostname'] : ''));
        if ($hostname === '') {
            $hostname = trim((string) (isset($service['name']) ? $service['name'] : ''));
        }
        if ($hostname === '') {
            $hostname = 'service-'.(int) (isset($service['id']) ? $service['id'] : 0);
        }

        $this->load->library('Solusvm_client');
        if (!$this->solusvm_client->is_configured()) {
            return array(
                'ok' => false,
                'message' => 'SolusVM is not configured.',
                'remote_id' => 0,
            );
        }

        // If IDs are still missing/invalid, pull provider catalogs and choose first valid IDs.
        if ($solus_plan_id <= 0) {
            $plans_result = $this->solusvm_client->list_plans();
            if ($plans_result['ok'] && isset($plans_result['data']['data']) && is_array($plans_result['data']['data'])) {
                $solus_plan_id = $this->first_provider_id_from_rows($plans_result['data']['data']);
            }
        }
        if ($solus_location_id <= 0) {
            $locations_result = $this->solusvm_client->list_locations();
            if ($locations_result['ok'] && isset($locations_result['data']['data']) && is_array($locations_result['data']['data'])) {
                $solus_location_id = $this->first_provider_id_from_rows($locations_result['data']['data']);
            }
        }
        if ($solus_os_id <= 0) {
            $os_result = $this->solusvm_client->list_os_images();
            if ($os_result['ok'] && isset($os_result['data']['data']) && is_array($os_result['data']['data'])) {
                $solus_os_id = $this->first_provider_id_from_rows($os_result['data']['data']);
            }
        }

        if ($solus_plan_id <= 0 || $solus_location_id <= 0 || $solus_os_id <= 0) {
            return array(
                'ok' => false,
                'message' => 'Provider provisioning skipped: unable to resolve valid plan/location/os IDs from provider.',
                'remote_id' => 0,
            );
        }

        $this->session->set_flashdata('client_service_success', 'Reinstall/Install request accepted by SolusVM.');
        $payload = array(
            'plan' => $solus_plan_id,
            'location' => $solus_location_id,
            'os' => $solus_os_id,
        );
        $result = $this->solusvm_client->create_server($payload);

        if (!$result['ok']) {
            $provider_msg = isset($result['data']['message']) ? strtolower((string) $result['data']['message']) : '';
            $needs_location_retry = strpos($provider_msg, 'location') !== false && strpos($provider_msg, 'not exists') !== false;
            $needs_os_retry = strpos($provider_msg, 'os') !== false && strpos($provider_msg, 'not exists') !== false;
            $needs_plan_retry = strpos($provider_msg, 'plan') !== false && strpos($provider_msg, 'not exists') !== false;

            if ($needs_location_retry || $needs_os_retry || $needs_plan_retry) {
                if ($needs_plan_retry) {
                    $plans_result = $this->solusvm_client->list_plans();
                    if ($plans_result['ok'] && isset($plans_result['data']['data']) && is_array($plans_result['data']['data'])) {
                        $solus_plan_id = $this->first_provider_id_from_rows($plans_result['data']['data']);
                    }
                }
                if ($needs_location_retry) {
                    $locations_result = $this->solusvm_client->list_locations();
                    if ($locations_result['ok'] && isset($locations_result['data']['data']) && is_array($locations_result['data']['data'])) {
                        $solus_location_id = $this->first_provider_id_from_rows($locations_result['data']['data']);
                    }
                }
                if ($needs_os_retry) {
                    $os_result = $this->solusvm_client->list_os_images();
                    if ($os_result['ok'] && isset($os_result['data']['data']) && is_array($os_result['data']['data'])) {
                        $solus_os_id = $this->first_provider_id_from_rows($os_result['data']['data']);
                    }
                }

                if ($solus_plan_id > 0 && $solus_location_id > 0 && $solus_os_id > 0) {
                    $payload['plan'] = $solus_plan_id;
                    $payload['location'] = $solus_location_id;
                    $payload['os'] = $solus_os_id;
                    $result = $this->solusvm_client->create_server($payload);
                }
            }
        }

        if (!$result['ok']) {
            $msg = 'Provider create failed: '.(string) $result['error'];
            if (isset($result['data']['message'])) {
                $msg .= ' | '.$result['data']['message'];
            }
            return array(
                'ok' => false,
                'message' => $msg,
                'remote_id' => 0,
            );
        }

        $remote_id = 0;
        if (isset($result['data']['data']['id'])) {
            $remote_id = (int) $result['data']['data']['id'];
        } elseif (isset($result['data']['id'])) {
            $remote_id = (int) $result['data']['id'];
        }

        $created_password = null;
        if (isset($result['data']['data']['password']) && trim((string) $result['data']['data']['password']) !== '') {
            $created_password = (string) $result['data']['data']['password'];
        }

        return array(
            'ok' => $remote_id > 0,
            'message' => $remote_id > 0 ? 'Provider server created successfully.' : 'Provider create accepted but server id missing.',
            'remote_id' => $remote_id,
            'result' => $result,
            'password' => $created_password,
        );
    }

    private function render_admin($view, $data)
    {
        if (!isset($data['active_nav']) || trim((string) $data['active_nav']) === '') {
            $admin_nav_map = array(
                'portal/admin_dashboard' => 'dashboard',
                'portal/admin_servers' => 'servers',
                'portal/admin_server_create' => 'servers',
                'portal/admin_server_detail' => 'servers',
                'portal/admin_compute_resources' => 'compute-resources',
                'portal/admin_plans' => 'plans',
                'portal/admin_os_images' => 'os-images',
                'portal/admin_users' => 'users',
                'portal/admin_projects' => 'projects',
                'portal/admin_clients' => 'clients',
                'portal/admin_invoices' => 'invoices',
                'portal/admin_orders' => 'orders',
                'portal/admin_domains' => 'domains',
                'portal/admin_tickets' => 'tickets',
                'portal/admin_audit_logs' => 'audit-logs',
                'portal/admin_locations' => 'locations',
                'portal/admin_backups' => 'backups',
                'portal/admin_ip_blocks' => 'ip-blocks',
            );
            $data['active_nav'] = isset($admin_nav_map[$view]) ? $admin_nav_map[$view] : 'dashboard';
        }
        if (!isset($data['page_title']) && isset($data['title'])) {
            $data['page_title'] = $data['title'];
        }
        $this->load->view('portal/admin_header', $data);
        $this->load->view($view, $data);
        $this->load->view('portal/admin_footer');
    }

    private function render_client($view, $data)
    {
        if (!isset($data['active_nav']) || trim((string) $data['active_nav']) === '') {
            $client_nav_map = array(
                'portal/client_dashboard' => 'dashboard',
                'portal/client_services' => 'services',
                'portal/client_service_detail' => 'services',
                'portal/client_console' => 'services',
                'portal/client_orders' => 'orders',
                'portal/client_invoices' => 'invoices',
                'portal/client_tickets' => 'tickets',
                'portal/client_store' => 'store',
                'portal/client_checkout' => 'store',
            );
            $data['active_nav'] = isset($client_nav_map[$view]) ? $client_nav_map[$view] : 'dashboard';
        }
        $this->load->view('portal/client_header', $data);
        $this->load->view($view, $data);
        $this->load->view('portal/client_footer');
    }

    private function build_console_session($service)
    {
        $provider_server_id = (int) (isset($service['provider_server_id']) ? $service['provider_server_id'] : 0);
        if ($provider_server_id <= 0) {
            return array('ok' => false, 'message' => 'Service is not linked with provider yet.');
        }

        $this->load->library('Solusvm_client');
        if (!$this->solusvm_client->is_configured()) {
            return array('ok' => false, 'message' => 'SolusVM is not configured.');
        }

        $vnc = $this->solusvm_client->vnc_up($provider_server_id);
        if (!$vnc['ok']) {
            return array('ok' => false, 'message' => 'Unable to open VNC session: '.(string) $vnc['error']);
        }

        $vnc_root = isset($vnc['data']) && is_array($vnc['data']) ? $vnc['data'] : array();
        $vnc_data = isset($vnc_root['data']) && is_array($vnc_root['data']) ? $vnc_root['data'] : $vnc_root;
        if (isset($vnc_data['data']) && is_array($vnc_data['data'])) {
            $vnc_data = $vnc_data['data'];
        }

        $pick = function ($arr, $keys) {
            if (!is_array($arr)) {
                return '';
            }
            foreach ($keys as $k) {
                if (isset($arr[$k]) && is_scalar($arr[$k])) {
                    $v = trim((string) $arr[$k]);
                    if ($v !== '') {
                        return $v;
                    }
                }
            }
            return '';
        };

        $token = $pick($vnc_data, array('url', 'token', 'vnc_token', 'path', 'vnc_path', 'websocket_path'));
        $proxy = $pick($vnc_data, array('vnc_proxy_url', 'proxy_url', 'vnc_proxy', 'proxy', 'websocket_proxy_url'));
        $http_console_url = $pick($vnc_data, array('http_console_url', 'console_url', 'vnc_url', 'link', 'href'));
        $ws_url = $pick($vnc_data, array('ws_url', 'websocket_url', 'vnc_ws_url', 'ws'));

        if ($ws_url === '' && preg_match('#^wss?://#i', $token)) {
            $ws_url = $token;
        }
        if ($http_console_url === '' && preg_match('#^https?://#i', $token)) {
            $http_console_url = $token;
        }

        if (($http_console_url === '' && $ws_url === '') && $token !== '' && $proxy !== '') {
            $http_console_url = preg_match('/[?&=]$/', $proxy)
                ? ($proxy.ltrim($token, '/'))
                : (rtrim($proxy, '/').'/'.ltrim($token, '/'));
        }

        if ($ws_url === '' && $http_console_url !== '') {
            if (preg_match('#^wss?://#i', $http_console_url)) {
                $ws_url = $http_console_url;
            } else {
                $ws_url = preg_replace('#^https://#i', 'wss://', $http_console_url);
                $ws_url = preg_replace('#^http://#i', 'ws://', $ws_url);
            }
        }
        if ($http_console_url === '' && $ws_url !== '') {
            $http_console_url = preg_replace('#^wss://#i', 'https://', $ws_url);
            $http_console_url = preg_replace('#^ws://#i', 'http://', $http_console_url);
        }

        if ($http_console_url === '' && $ws_url === '') {
            $keys = is_array($vnc_data) ? implode(', ', array_keys($vnc_data)) : 'none';
            return array('ok' => false, 'message' => 'Provider did not return usable console URL. Keys: '.$keys);
        }

        $server_result = $this->solusvm_client->get_server($provider_server_id);
        $server_data = isset($server_result['data']['data']) && is_array($server_result['data']['data']) ? $server_result['data']['data'] : array();
        $settings = isset($server_data['settings']) && is_array($server_data['settings']) ? $server_data['settings'] : array();

        return array(
            'ok' => true,
            'provider_server_id' => $provider_server_id,
            'http_console_url' => $http_console_url,
            'ws_url' => $ws_url,
            'vnc_password' => isset($settings['vnc_password']) ? (string) $settings['vnc_password'] : '',
            'vnc_username' => isset($settings['user']) ? (string) $settings['user'] : 'root',
        );
    }

    public function index()
    {
        if (!$this->require_login_web()) {
            return;
        }

        $user = $this->current_user();
        redirect($this->is_admin($user) ? 'admin' : 'client');
    }

    public function admin_home()
    {
        $user = $this->ensure_admin();
        if (!$user) {
            return;
        }

        $services = $this->Service_model->list_for_user(array('role' => 'admin', 'id' => 0));
        $plans = $this->Service_model->list_plans();
        $clients = $this->User_model->list_clients();
        $running = 0;
        $stopped = 0;
        foreach ($services as $svc) {
            $st = strtolower((string) (isset($svc['status']) ? $svc['status'] : ''));
            if ($st === 'running' || $st === 'active') {
                $running++;
            } elseif ($st === 'stopped') {
                $stopped++;
            }
        }

        $this->render_admin('portal/admin_dashboard', array(
            'title' => 'Admin Dashboard',
            'user' => $user,
            'services' => $services,
            'plans' => $plans,
            'clients' => $clients,
            'running_count' => $running,
            'stopped_count' => $stopped,
        ));
    }

    public function admin_servers()
    {
        $user = $this->ensure_admin();
        if (!$user) {
            return;
        }
        $this->render_admin('portal/admin_servers', array(
            'title' => 'Servers',
            'user' => $user,
            'rows' => $this->Service_model->list_for_user(array('role' => 'admin', 'id' => 0)),
        ));
    }

    public function admin_server_create()
    {
        $user = $this->ensure_admin();
        if (!$user) {
            return;
        }
        $cfg = $this->get_solus_config();
        $this->render_admin('portal/admin_server_create', array(
            'title' => 'Create Server',
            'user' => $user,
            'plans' => $this->Service_model->list_plans(),
            'solus_base_url' => isset($cfg['base_url']) ? (string) $cfg['base_url'] : '',
            'solus_configured' => !empty($cfg['base_url']) && !empty($cfg['api_token']),
            'flash_success' => $this->session->flashdata('success'),
            'flash_error' => $this->session->flashdata('error'),
            'flash_result' => $this->session->flashdata('result'),
        ));
    }

    public function admin_server_provision()
    {
        $user = $this->ensure_admin();
        if (!$user) {
            return;
        }
        if (strtoupper((string) $this->input->method()) !== 'POST') {
            redirect('admin/servers/create');
            return;
        }

        $hostname = trim((string) $this->input->post('hostname', true));
        $local_plan_id = (int) $this->input->post('local_plan_id', true);
        $location_label = trim((string) $this->input->post('location_label', true));
        $os_label = trim((string) $this->input->post('os_label', true));
        $vps_password = trim((string) $this->input->post('vps_password', true));

        if ($hostname === '' || $local_plan_id <= 0) {
            $this->session->set_flashdata('error', 'Please fill all required fields.');
            redirect('admin/servers/create');
            return;
        }

        $this->load->library('Solusvm_client');
        if (!$this->solusvm_client->is_configured()) {
            $this->session->set_flashdata('error', 'SolusVM is not configured.');
            redirect('admin/servers/create');
            return;
        }

        $cfg = $this->get_solus_config();
        $plan_map = isset($cfg['plan_map']) && is_array($cfg['plan_map']) ? $cfg['plan_map'] : array();
        $location_map = isset($cfg['location_map']) && is_array($cfg['location_map']) ? $cfg['location_map'] : array();
        $os_map = isset($cfg['os_map']) && is_array($cfg['os_map']) ? $cfg['os_map'] : array();

        $solus_plan_id = $this->mapped_solus_id($local_plan_id, $plan_map);
        if ($solus_plan_id <= 0 && isset($cfg['default_plan_id'])) {
            $solus_plan_id = (int) $cfg['default_plan_id'];
        }

        // Fixed UI options => deterministic local IDs
        $location_key = 0;
        if (strcasecmp($location_label, 'Frankfurt') === 0) {
            $location_key = 1;
        } elseif (strcasecmp($location_label, 'Amsterdam') === 0) {
            $location_key = 2;
        } elseif (strcasecmp($location_label, 'New York') === 0) {
            $location_key = 3;
        }

        $os_key = 0;
        if (stripos($os_label, 'Ubuntu 22.04') !== false) {
            $os_key = 1;
        } elseif (stripos($os_label, 'Debian 12') !== false) {
            $os_key = 2;
        } elseif (stripos($os_label, 'AlmaLinux 9') !== false) {
            $os_key = 3;
        }

        $solus_location_id = $this->mapped_solus_id($location_key, $location_map);
        $solus_os_id = $this->mapped_solus_id($os_key, $os_map);

        if ($solus_plan_id <= 0) {
            $plans_result = $this->solusvm_client->list_plans();
            if ($plans_result['ok'] && isset($plans_result['data']['data']) && is_array($plans_result['data']['data'])) {
                $solus_plan_id = $this->first_provider_id_from_rows($plans_result['data']['data']);
            }
        }

        if ($solus_location_id <= 0) {
            $locations_result = $this->solusvm_client->list_locations();
            if ($locations_result['ok'] && isset($locations_result['data']['data']) && is_array($locations_result['data']['data'])) {
                foreach ($locations_result['data']['data'] as $loc) {
                    if (!is_array($loc) || empty($loc['name'])) {
                        continue;
                    }
                    if (stripos((string) $loc['name'], $location_label) !== false) {
                        $solus_location_id = (int) (isset($loc['id']) ? $loc['id'] : 0);
                        break;
                    }
                }
                if ($solus_location_id <= 0) {
                    $solus_location_id = $this->first_provider_id_from_rows($locations_result['data']['data']);
                }
            }
        }

        if ($solus_os_id <= 0) {
            $os_result = $this->solusvm_client->list_os_images();
            if ($os_result['ok'] && isset($os_result['data']['data']) && is_array($os_result['data']['data'])) {
                foreach ($os_result['data']['data'] as $img) {
                    if (!is_array($img)) {
                        continue;
                    }
                    $label = '';
                    foreach (array('label', 'name', 'title', 'version_name') as $k) {
                        if (!empty($img[$k])) {
                            $label = (string) $img[$k];
                            break;
                        }
                    }
                    if ($label !== '' && stripos($label, $os_label) !== false) {
                        $solus_os_id = (int) (isset($img['id']) ? $img['id'] : 0);
                        break;
                    }
                }
                if ($solus_os_id <= 0) {
                    $solus_os_id = $this->first_provider_id_from_rows($os_result['data']['data']);
                }
            }
        }

        if ($solus_plan_id <= 0 || $solus_location_id <= 0 || $solus_os_id <= 0) {
            $this->session->set_flashdata('error', 'Could not auto-resolve provider Plan/Location/OS IDs. Check SolusVM mapping config once.');
            redirect('admin/servers/create');
            return;
        }

        $payload = array(
            'plan' => $solus_plan_id,
            'location' => $solus_location_id,
            'os' => $solus_os_id,
            'hostname' => $hostname,
        );
        if ($vps_password !== '') {
            $payload['password'] = $vps_password;
        }

        $result = $this->solusvm_client->create_server($payload);
        if (!$result['ok']) {
            $provider_msg = isset($result['data']['message']) ? strtolower((string) $result['data']['message']) : '';
            $needs_location_retry = strpos($provider_msg, 'location') !== false && strpos($provider_msg, 'not exists') !== false;
            $needs_os_retry = strpos($provider_msg, 'os') !== false && strpos($provider_msg, 'not exists') !== false;
            $needs_plan_retry = strpos($provider_msg, 'plan') !== false && strpos($provider_msg, 'not exists') !== false;

            if ($needs_plan_retry || $needs_location_retry || $needs_os_retry) {
                if ($needs_plan_retry) {
                    $plans_result = $this->solusvm_client->list_plans();
                    if ($plans_result['ok'] && isset($plans_result['data']['data']) && is_array($plans_result['data']['data'])) {
                        $solus_plan_id = $this->first_provider_id_from_rows($plans_result['data']['data']);
                    }
                }
                if ($needs_location_retry) {
                    $locations_result = $this->solusvm_client->list_locations();
                    if ($locations_result['ok'] && isset($locations_result['data']['data']) && is_array($locations_result['data']['data'])) {
                        $solus_location_id = 0;
                        foreach ($locations_result['data']['data'] as $loc) {
                            if (!is_array($loc) || empty($loc['name'])) {
                                continue;
                            }
                            if (stripos((string) $loc['name'], $location_label) !== false) {
                                $solus_location_id = (int) (isset($loc['id']) ? $loc['id'] : 0);
                                break;
                            }
                        }
                        if ($solus_location_id <= 0) {
                            $solus_location_id = $this->first_provider_id_from_rows($locations_result['data']['data']);
                        }
                    }
                }
                if ($needs_os_retry) {
                    $os_result = $this->solusvm_client->list_os_images();
                    if ($os_result['ok'] && isset($os_result['data']['data']) && is_array($os_result['data']['data'])) {
                        $solus_os_id = 0;
                        foreach ($os_result['data']['data'] as $img) {
                            if (!is_array($img)) {
                                continue;
                            }
                            $label = '';
                            foreach (array('label', 'name', 'title', 'version_name') as $k) {
                                if (!empty($img[$k])) {
                                    $label = (string) $img[$k];
                                    break;
                                }
                            }
                            if ($label !== '' && stripos($label, $os_label) !== false) {
                                $solus_os_id = (int) (isset($img['id']) ? $img['id'] : 0);
                                break;
                            }
                        }
                        if ($solus_os_id <= 0) {
                            $solus_os_id = $this->first_provider_id_from_rows($os_result['data']['data']);
                        }
                    }
                }

                if ($solus_plan_id > 0 && $solus_location_id > 0 && $solus_os_id > 0) {
                    $payload['plan'] = $solus_plan_id;
                    $payload['location'] = $solus_location_id;
                    $payload['os'] = $solus_os_id;
                    $result = $this->solusvm_client->create_server($payload);
                }
            }
        }

        if (!$result['ok']) {
            $msg = 'Provider create failed: '.(string) $result['error'];
            if (isset($result['data']['message']) && trim((string) $result['data']['message']) !== '') {
                $msg .= ' | '.(string) $result['data']['message'];
            }
            $this->session->set_flashdata('error', $msg);
            $this->session->set_flashdata('result', json_encode($result, JSON_PRETTY_PRINT));
            redirect('admin/servers/create');
            return;
        }

        $remote_id = 0;
        $created_password = NULL;
        if (isset($result['data']['data']['id'])) {
            $remote_id = (int) $result['data']['data']['id'];
        }
        if (isset($result['data']['data']['password']) && trim((string) $result['data']['data']['password']) !== '') {
            $created_password = (string) $result['data']['data']['password'];
        }
        if ($created_password === NULL && $vps_password !== '') {
            $created_password = $vps_password;
        }

        $this->db->insert('services', array(
            'user_id' => 1,
            'plan_id' => $local_plan_id,
            'provider_server_id' => $remote_id > 0 ? $remote_id : NULL,
            'name' => $hostname,
            'hostname' => $hostname,
            'status' => 'active',
            'os' => $os_label,
            'location' => $location_label,
            'ip_address' => '',
            'created_at' => date('c'),
        ));
        $local_service_id = (int) $this->db->insert_id();

        if ($local_service_id && $created_password !== NULL) {
            $this->Service_model->set_root_password($local_service_id, $created_password);
        }

        $this->session->set_flashdata('success', 'Server created successfully. Local Service ID: '.$local_service_id.' | Provider ID: '.$remote_id);
        $this->session->set_flashdata('result', json_encode($result, JSON_PRETTY_PRINT));
        redirect('admin/servers/create');
    }

    public function admin_server_detail($service_id)
    {
        $user = $this->ensure_admin();
        if (!$user) {
            return;
        }
        $service = $this->Service_model->find_for_user((int) $service_id, array('role' => 'admin', 'id' => 0));
        if (!$service) {
            show_404();
            return;
        }

        $provider_server = NULL;
        $provider_ip = isset($service['ip_address']) ? (string) $service['ip_address'] : '';
        $provider_os_name = isset($service['os']) ? (string) $service['os'] : '';
        $provider_app_name = '';
        $provider_ips = array();
        $provider_ipv6 = '';
        $provider_uptime = '';
        $provider_resources = array('vcpu' => 0, 'memory' => 0, 'disk' => 0);
        $provider_bandwidth_limit = 0;
        $provider_bandwidth_used = 0;
        $provider_is_processing = false;
        $provider_app_login_link = '';
        $available_os = array();
        $applications = array();
        $os_dropdown_note = '';
        $provider_server_id = (int) (isset($service['provider_server_id']) ? $service['provider_server_id'] : 0);
        if ($provider_server_id > 0) {
            $this->load->library('Solusvm_client');
            if ($this->solusvm_client->is_configured()) {
                $sres = $this->solusvm_client->get_server($provider_server_id);
                if ($sres['ok'] && isset($sres['data']['data']) && is_array($sres['data']['data'])) {
                    $provider_server = $sres['data']['data'];
                    if (!empty($provider_server['status'])) {
                        $provider_is_processing = in_array(strtolower((string) $provider_server['status']), array('processing', 'building', 'reinstalling', 'restarting', 'migrating'), true);
                    }
                    if (isset($provider_server['name']) && is_scalar($provider_server['name']) && trim((string) $provider_server['name']) !== '') {
                        $service['name'] = (string) $provider_server['name'];
                    }
                    if (!empty($provider_server['ips'][0])) {
                        if (is_scalar($provider_server['ips'][0])) {
                            $provider_ip = (string) $provider_server['ips'][0];
                        } elseif (is_array($provider_server['ips'][0])) {
                            foreach (array('address', 'ip', 'ipv4') as $ip_key) {
                                if (!empty($provider_server['ips'][0][$ip_key]) && is_scalar($provider_server['ips'][0][$ip_key])) {
                                    $provider_ip = (string) $provider_server['ips'][0][$ip_key];
                                    break;
                                }
                            }
                        }
                        $provider_ips = $provider_server['ips'];
                    }
                    if (!empty($provider_server['ip']) && is_scalar($provider_server['ip'])) {
                        $provider_ip = (string) $provider_server['ip'];
                    }
                    if (!empty($provider_server['plan']['name']) && is_scalar($provider_server['plan']['name'])) {
                        $service['plan_name'] = (string) $provider_server['plan']['name'];
                    }
                    if (!empty($provider_server['location']['name']) && is_scalar($provider_server['location']['name'])) {
                        $service['location'] = (string) $provider_server['location']['name'];
                    }
                }
                $os_res = $this->solusvm_client->list_os_images();
                if ($os_res['ok'] && isset($os_res['data']['data']) && is_array($os_res['data']['data'])) {
                    $available_os = $os_res['data']['data'];
                } else {
                    $os_dropdown_note = 'OS list could not be fetched from provider.';
                }
                $app_res = $this->solusvm_client->list_applications();
                if ($app_res['ok'] && isset($app_res['data']['data']) && is_array($app_res['data']['data'])) {
                    $applications = $app_res['data']['data'];
                }
            }
        }

        if ($provider_ip !== '') {
            $service['ip_address'] = $provider_ip;
        }

        $this->render_admin('portal/admin_server_detail', array(
            'title' => 'Server Detail',
            'user' => $user,
            'service' => $service,
            'vps_password' => isset($service['root_password']) ? $service['root_password'] : '',
            'provider_ip' => $provider_ip,
            'provider_os_name' => $provider_os_name,
            'provider_app_name' => $provider_app_name,
            'provider_ips' => $provider_ips,
            'provider_ipv6' => $provider_ipv6,
            'provider_uptime' => $provider_uptime,
            'provider_resources' => $provider_resources,
            'provider_bandwidth_limit' => $provider_bandwidth_limit,
            'provider_bandwidth_used' => $provider_bandwidth_used,
            'provider_server' => $provider_server,
            'provider_is_processing' => $provider_is_processing,
            'provider_app_login_link' => $provider_app_login_link,
            'available_os' => $available_os,
            'applications' => $applications,
            'os_dropdown_note' => $os_dropdown_note,
            'flash_success' => $this->session->flashdata('admin_service_success'),
            'flash_error' => $this->session->flashdata('admin_service_error'),
        ));
    }

    public function admin_service_console($service_id)
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $service = $this->Service_model->find_for_user((int) $service_id, array('role' => 'admin', 'id' => 0));
        if (!$service) { show_404(); return; }

        $session = $this->build_console_session($service);
        if (empty($session['ok'])) {
            $this->session->set_flashdata('admin_service_error', isset($session['message']) ? $session['message'] : 'Unable to open console session.');
            redirect('admin/servers/'.(int) $service['id']);
            return;
        }

        $this->render_admin('portal/client_console', array(
            'title' => 'Console',
            'user' => $user,
            'service' => $service,
            'provider_server_id' => (int) $session['provider_server_id'],
            'ws_url' => $session['ws_url'],
            'http_console_url' => $session['http_console_url'],
            'vnc_password' => $session['vnc_password'],
            'vnc_username' => $session['vnc_username'],
            'session_refresh_url' => site_url('admin/servers/'.(int) $service['id'].'/console/session'),
            'back_url' => site_url('admin/servers/'.(int) $service['id']),
        ));
    }

    public function admin_service_console_session($service_id)
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $service = $this->Service_model->find_for_user((int) $service_id, array('role' => 'admin', 'id' => 0));
        if (!$service) {
            return $this->json(array('ok' => false, 'message' => 'Service not found.'), 404);
        }

        $session = $this->build_console_session($service);
        if (empty($session['ok'])) {
            return $this->json(array('ok' => false, 'message' => isset($session['message']) ? $session['message'] : 'Session refresh failed.'), 422);
        }

        return $this->json(array(
            'ok' => true,
            'ws_url' => $session['ws_url'],
            'http_console_url' => $session['http_console_url'],
            'vnc_password' => $session['vnc_password'],
            'vnc_username' => $session['vnc_username'],
        ), 200);
    }

    public function admin_service_action($service_id, $action)
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $service = $this->Service_model->find_for_user((int) $service_id, array('role' => 'admin', 'id' => 0));
        if (!$service) { show_404(); return; }

        $action = strtolower(trim((string) $action));
        $local_status_map = array('start' => 'running', 'stop' => 'stopped', 'restart' => 'running');
        if (!isset($local_status_map[$action])) {
            $this->session->set_flashdata('admin_service_error', 'Unsupported action.');
            redirect('admin/servers/'.(int) $service_id);
            return;
        }

        $provider_server_id = (int) (isset($service['provider_server_id']) ? $service['provider_server_id'] : 0);
        if ($provider_server_id > 0) {
            $this->load->library('Solusvm_client');
            if ($this->solusvm_client->is_configured()) {
                $result = array('ok' => false, 'error' => 'Unknown error');
                if ($action === 'start') {
                    $result = $this->solusvm_client->start_server($provider_server_id);
                } elseif ($action === 'stop') {
                    $result = $this->solusvm_client->stop_server($provider_server_id);
                } elseif ($action === 'restart') {
                    $result = $this->solusvm_client->restart_server($provider_server_id);
                }
                if (!$result['ok']) {
                    $this->session->set_flashdata('admin_service_error', 'Provider action failed: '.(string) $result['error']);
                    redirect('admin/servers/'.(int) $service_id);
                    return;
                }
            }
        }

        $this->Service_model->set_status((int) $service_id, $local_status_map[$action]);
        $this->session->set_flashdata('admin_service_success', 'Action "'.$action.'" sent successfully.');
        redirect('admin/servers/'.(int) $service_id);
    }

    public function admin_service_reinstall($service_id)
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $service = $this->Service_model->find_for_user((int) $service_id, array('role' => 'admin', 'id' => 0));
        if (!$service) { show_404(); return; }

        $provider_server_id = (int) (isset($service['provider_server_id']) ? $service['provider_server_id'] : 0);
        if ($provider_server_id <= 0) {
            $this->session->set_flashdata('admin_service_error', 'Service is not linked to provider.');
            redirect('admin/servers/'.(int) $service_id);
            return;
        }

        $os = (int) $this->input->post('os', true);
        $application = (int) $this->input->post('application', true);
        $password = trim((string) $this->input->post('password', true));
        $application_data_raw = trim((string) $this->input->post('application_data', true));
        $application_data = NULL;
        if ($application_data_raw !== '') {
            $decoded = json_decode($application_data_raw, true);
            if (is_array($decoded)) {
                $application_data = $decoded;
            }
        }

        $payload = array();
        if ($os > 0) { $payload['os'] = $os; }
        if ($application > 0) { $payload['application'] = $application; }
        if ($password !== '') { $payload['password'] = $password; }
        if (is_array($application_data)) { $payload['application_data'] = $application_data; }

        if (empty($payload)) {
            $this->session->set_flashdata('admin_service_error', 'Please select at least OS or Application.');
            redirect('admin/servers/'.(int) $service_id);
            return;
        }

        $this->load->library('Solusvm_client');
        $result = $this->solusvm_client->reinstall_server($provider_server_id, $payload);
        if (!$result['ok']) {
            $this->session->set_flashdata('admin_service_error', 'Reinstall failed: '.(string) $result['error']);
            redirect('admin/servers/'.(int) $service_id);
            return;
        }

        if ($password !== '') {
            $this->Service_model->set_root_password((int) $service_id, $password);
        }
        $this->Service_model->set_status((int) $service_id, 'reinstalling');
        $this->session->set_flashdata('admin_service_success', 'Reinstall/Install request accepted by SolusVM.');
        redirect('admin/servers/'.(int) $service_id);
    }

    public function admin_service_change_password($service_id)
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $service = $this->Service_model->find_for_user((int) $service_id, array('role' => 'admin', 'id' => 0));
        if (!$service) { show_404(); return; }

        $password = trim((string) $this->input->post('password', true));
        if ($password === '' || strlen($password) < 8) {
            $this->session->set_flashdata('admin_service_error', 'Password must be at least 8 characters.');
            redirect('admin/servers/'.(int) $service_id);
            return;
        }

        $provider_server_id = (int) (isset($service['provider_server_id']) ? $service['provider_server_id'] : 0);
        if ($provider_server_id > 0) {
            $this->load->library('Solusvm_client');
            $result = $this->solusvm_client->change_root_password($provider_server_id, $password);
            if (!$result['ok']) {
                $this->session->set_flashdata('admin_service_error', 'Provider password change failed: '.(string) $result['error']);
                redirect('admin/servers/'.(int) $service_id);
                return;
            }
        }

        $this->Service_model->set_root_password((int) $service_id, $password);
        $this->session->set_flashdata('admin_service_success', 'Root password updated successfully.');
        redirect('admin/servers/'.(int) $service_id);
    }

    public function admin_service_provision($service_id)
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $service = $this->Service_model->find_for_user((int) $service_id, array('role' => 'admin', 'id' => 0));
        if (!$service) { show_404(); return; }

        if ((int) (isset($service['provider_server_id']) ? $service['provider_server_id'] : 0) > 0) {
            $this->session->set_flashdata('admin_service_success', 'Service is already linked with provider.');
            redirect('admin/servers/'.(int) $service_id);
            return;
        }

        $provision = $this->provision_service_on_provider($service);
        if (!empty($provision['ok']) && (int) $provision['remote_id'] > 0) {
            $this->Service_model->set_provider_server_id((int) $service_id, (int) $provision['remote_id']);
            if (!empty($provision['password'])) {
                $this->Service_model->set_root_password((int) $service_id, $provision['password']);
            }
            $this->session->set_flashdata('admin_service_success', 'Provider linked successfully: '.$provision['remote_id']);
        } else {
            $this->session->set_flashdata('admin_service_error', isset($provision['message']) ? $provision['message'] : 'Provider provisioning failed.');
        }
        redirect('admin/servers/'.(int) $service_id);
    }

    public function admin_plans()
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $this->render_admin('portal/admin_plans', array('title' => 'Plans', 'user' => $user, 'rows' => $this->Service_model->list_plans()));
    }

    public function admin_os_images()
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $rows = $this->seed_rows('os_images');
        $this->render_admin('portal/admin_os_images', array('title' => 'OS Images', 'user' => $user, 'rows' => $rows));
    }

    public function admin_users()
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $this->render_admin('portal/admin_users', array('title' => 'Users', 'user' => $user, 'rows' => $this->User_model->list_all()));
    }

    public function admin_projects()
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $this->render_admin('portal/admin_projects', array('title' => 'Projects', 'user' => $user, 'rows' => $this->seed_rows('projects')));
    }

    public function client_home()
    {
        $user = $this->ensure_client();
        if (!$user) {
            return;
        }
        $this->render_client('portal/client_dashboard', array(
            'title' => 'Dashboard',
            'user' => $user,
            'services' => $this->Service_model->list_for_user($user),
            'orders' => $this->Service_model->list_orders_for_user($user),
            'invoices' => $this->Service_model->list_invoices_for_user($user),
        ));
    }

    public function services()
    {
        if (!$this->require_login_web()) { return; }
        $user = $this->current_user();
        if ($this->is_admin($user)) {
            $this->admin_servers();
            return;
        }
        $this->render_client('portal/client_services', array(
            'title' => 'My Services',
            'user' => $user,
            'services' => $this->Service_model->list_for_user($user),
        ));
    }

    public function client_service_detail($service_id)
    {
        $user = $this->ensure_client(); if (!$user) { return; }
        $service = $this->Service_model->find_for_user((int) $service_id, $user);
        if (!$service) { show_404(); return; }

        $provider_server = NULL;
        $provider_ip = isset($service['ip_address']) ? (string) $service['ip_address'] : '';
        $provider_os_name = isset($service['os']) ? (string) $service['os'] : '';
        $provider_app_name = '';
        $provider_ips = array();
        $provider_ipv6 = '';
        $provider_uptime = '';
        $provider_resources = array();
        $provider_bandwidth_limit = 0;
        $provider_bandwidth_used = 0;
        $provider_is_processing = false;
        $provider_app_login_link = '';
        $available_os = array();
        $applications = array();
        $os_dropdown_note = '';

        $provider_server_id = (int) (isset($service['provider_server_id']) ? $service['provider_server_id'] : 0);
        if ($provider_server_id > 0) {
            $this->load->library('Solusvm_client');
            if ($this->solusvm_client->is_configured()) {
                $sres = $this->solusvm_client->get_server($provider_server_id);
                if ($sres['ok'] && isset($sres['data']['data']) && is_array($sres['data']['data'])) {
                    $provider_server = $sres['data']['data'];
                    if (!empty($provider_server['status'])) {
                        $provider_is_processing = in_array(strtolower((string) $provider_server['status']), array('processing', 'building', 'reinstalling', 'restarting', 'migrating'), true);
                    }
                    if (isset($provider_server['name']) && is_scalar($provider_server['name']) && trim((string) $provider_server['name']) !== '') {
                        $service['name'] = (string) $provider_server['name'];
                    }
                    if (!empty($provider_server['ips'][0])) {
                        if (is_scalar($provider_server['ips'][0])) {
                            $provider_ip = (string) $provider_server['ips'][0];
                        } elseif (is_array($provider_server['ips'][0])) {
                            foreach (array('address', 'ip', 'ipv4') as $ip_key) {
                                if (!empty($provider_server['ips'][0][$ip_key]) && is_scalar($provider_server['ips'][0][$ip_key])) {
                                    $provider_ip = (string) $provider_server['ips'][0][$ip_key];
                                    break;
                                }
                            }
                        }
                        $provider_ips = $provider_server['ips'];
                    }
                    if (!empty($provider_server['ip']) && is_scalar($provider_server['ip'])) {
                        $provider_ip = (string) $provider_server['ip'];
                    }
                    if (!empty($provider_server['plan']['name']) && is_scalar($provider_server['plan']['name'])) {
                        $service['plan_name'] = (string) $provider_server['plan']['name'];
                    }
                    if (!empty($provider_server['location']['name']) && is_scalar($provider_server['location']['name'])) {
                        $service['location'] = (string) $provider_server['location']['name'];
                    }
                }
                $os_res = $this->solusvm_client->list_os_images();
                if ($os_res['ok'] && isset($os_res['data']['data']) && is_array($os_res['data']['data'])) {
                    $available_os = $os_res['data']['data'];
                } else {
                    $os_dropdown_note = 'OS list could not be fetched from provider.';
                }
                $app_res = $this->solusvm_client->list_applications();
                if ($app_res['ok'] && isset($app_res['data']['data']) && is_array($app_res['data']['data'])) {
                    $applications = $app_res['data']['data'];
                }
            }
        }

        if ($provider_ip !== '') {
            $service['ip_address'] = $provider_ip;
        }

        $this->render_client('portal/client_service_detail', array(
            'title' => 'Service Detail',
            'user' => $user,
            'service' => $service,
            'flash_success' => $this->session->flashdata('client_service_success'),
            'flash_error' => $this->session->flashdata('client_service_error'),
            'provider_server' => $provider_server,
            'provider_ip' => $provider_ip,
            'provider_os_name' => $provider_os_name,
            'provider_app_name' => $provider_app_name,
            'provider_ips' => $provider_ips,
            'provider_ipv6' => $provider_ipv6,
            'provider_uptime' => $provider_uptime,
            'provider_resources' => $provider_resources,
            'provider_bandwidth_limit' => $provider_bandwidth_limit,
            'provider_bandwidth_used' => $provider_bandwidth_used,
            'provider_is_processing' => $provider_is_processing,
            'provider_app_login_link' => $provider_app_login_link,
            'available_os' => $available_os,
            'applications' => $applications,
            'os_dropdown_note' => $os_dropdown_note,
            'vps_password' => isset($service['root_password']) ? (string) $service['root_password'] : '',
        ));
    }

    public function client_service_console($service_id)
    {
        $user = $this->ensure_client(); if (!$user) { return; }
        $service = $this->Service_model->find_for_user((int) $service_id, $user);
        if (!$service) { show_404(); return; }

        $session = $this->build_console_session($service);
        if (empty($session['ok'])) {
            $this->session->set_flashdata('client_service_error', isset($session['message']) ? $session['message'] : 'Unable to open console session.');
            redirect('client/services/'.(int) $service['id']);
            return;
        }

        $this->render_client('portal/client_console', array(
            'title' => 'Console',
            'user' => $user,
            'service' => $service,
            'provider_server_id' => (int) $session['provider_server_id'],
            'ws_url' => $session['ws_url'],
            'http_console_url' => $session['http_console_url'],
            'vnc_password' => $session['vnc_password'],
            'vnc_username' => $session['vnc_username'],
            'session_refresh_url' => site_url('client/services/'.(int) $service['id'].'/console/session'),
        ));
    }

    public function client_service_console_session($service_id)
    {
        $user = $this->ensure_client(); if (!$user) { return; }
        $service = $this->Service_model->find_for_user((int) $service_id, $user);
        if (!$service) {
            return $this->json(array('ok' => false, 'message' => 'Service not found.'), 404);
        }

        $session = $this->build_console_session($service);
        if (empty($session['ok'])) {
            return $this->json(array('ok' => false, 'message' => isset($session['message']) ? $session['message'] : 'Session refresh failed.'), 422);
        }

        return $this->json(array(
            'ok' => true,
            'ws_url' => $session['ws_url'],
            'http_console_url' => $session['http_console_url'],
            'vnc_password' => $session['vnc_password'],
            'vnc_username' => $session['vnc_username'],
        ), 200);
    }

    public function client_service_action($service_id, $action)
    {
        $user = $this->ensure_client(); if (!$user) { return; }
        $service = $this->Service_model->find_for_user((int) $service_id, $user);
        if (!$service) { show_404(); return; }

        $action = strtolower(trim((string) $action));
        $local_status_map = array('start' => 'running', 'stop' => 'stopped', 'restart' => 'running');
        if (!isset($local_status_map[$action])) {
            $this->session->set_flashdata('client_service_error', 'Unsupported action.');
            redirect('client/services/'.(int) $service_id);
            return;
        }

        $provider_server_id = (int) (isset($service['provider_server_id']) ? $service['provider_server_id'] : 0);
        if ($provider_server_id > 0) {
            $this->load->library('Solusvm_client');
            if ($this->solusvm_client->is_configured()) {
                $result = array('ok' => false, 'error' => 'Unknown error');
                if ($action === 'start') {
                    $result = $this->solusvm_client->start_server($provider_server_id);
                } elseif ($action === 'stop') {
                    $result = $this->solusvm_client->stop_server($provider_server_id);
                } elseif ($action === 'restart') {
                    $result = $this->solusvm_client->restart_server($provider_server_id);
                }
                if (!$result['ok']) {
                    $this->session->set_flashdata('client_service_error', 'Provider action failed: '.(string) $result['error']);
                    redirect('client/services/'.(int) $service_id);
                    return;
                }
            }
        }

        $this->Service_model->set_status((int) $service_id, $local_status_map[$action]);
        $this->session->set_flashdata('client_service_success', 'Action "'.$action.'" sent successfully.');
        redirect('client/services/'.(int) $service_id);
    }

    public function client_service_reinstall($service_id)
    {
        $user = $this->ensure_client(); if (!$user) { return; }
        $service = $this->Service_model->find_for_user((int) $service_id, $user);
        if (!$service) { show_404(); return; }

        $provider_server_id = (int) (isset($service['provider_server_id']) ? $service['provider_server_id'] : 0);
        if ($provider_server_id <= 0) {
            $this->session->set_flashdata('client_service_error', 'Service is not linked to provider.');
            redirect('client/services/'.(int) $service_id);
            return;
        }

        $os = (int) $this->input->post('os', true);
        $application = (int) $this->input->post('application', true);
        $password = trim((string) $this->input->post('password', true));
        $application_data_raw = trim((string) $this->input->post('application_data', true));
        $application_data = NULL;
        if ($application_data_raw !== '') {
            $decoded = json_decode($application_data_raw, true);
            if (is_array($decoded)) {
                $application_data = $decoded;
            }
        }

        $payload = array();
        if ($os > 0) { $payload['os'] = $os; }
        if ($application > 0) { $payload['application'] = $application; }
        if ($password !== '') { $payload['password'] = $password; }
        if (is_array($application_data)) { $payload['application_data'] = $application_data; }

        if (empty($payload)) {
            $this->session->set_flashdata('client_service_error', 'Please select at least OS or Application.');
            redirect('client/services/'.(int) $service_id);
            return;
        }

        $this->load->library('Solusvm_client');
        $result = $this->solusvm_client->reinstall_server($provider_server_id, $payload);
        if (!$result['ok']) {
            $this->session->set_flashdata('client_service_error', 'Reinstall failed: '.(string) $result['error']);
            redirect('client/services/'.(int) $service_id);
            return;
        }

        if ($password !== '') {
            $this->Service_model->set_root_password((int) $service_id, $password);
        }
        $this->Service_model->set_status((int) $service_id, 'reinstalling');
        $this->session->set_flashdata('client_service_success', 'Reinstall/Install request accepted by SolusVM.');
        redirect('client/services/'.(int) $service_id);
    }

    public function client_service_change_password($service_id)
    {
        $user = $this->ensure_client(); if (!$user) { return; }
        $service = $this->Service_model->find_for_user((int) $service_id, $user);
        if (!$service) { show_404(); return; }

        $password = trim((string) $this->input->post('password', true));
        if ($password === '' || strlen($password) < 8) {
            $this->session->set_flashdata('client_service_error', 'Password must be at least 8 characters.');
            redirect('client/services/'.(int) $service_id);
            return;
        }

        $provider_server_id = (int) (isset($service['provider_server_id']) ? $service['provider_server_id'] : 0);
        if ($provider_server_id > 0) {
            $this->load->library('Solusvm_client');
            $result = $this->solusvm_client->change_root_password($provider_server_id, $password);
            if (!$result['ok']) {
                $this->session->set_flashdata('client_service_error', 'Provider password change failed: '.(string) $result['error']);
                redirect('client/services/'.(int) $service_id);
                return;
            }
        }

        $this->Service_model->set_root_password((int) $service_id, $password);
        $this->session->set_flashdata('client_service_success', 'Root password updated successfully.');
        redirect('client/services/'.(int) $service_id);
    }

    public function client_service_provision($service_id)
    {
        $user = $this->ensure_client(); if (!$user) { return; }
        $service = $this->Service_model->find_for_user((int) $service_id, $user);
        if (!$service) { show_404(); return; }

        if ((int) (isset($service['provider_server_id']) ? $service['provider_server_id'] : 0) > 0) {
            $this->session->set_flashdata('client_service_success', 'Service is already linked with provider.');
            redirect('client/services/'.(int) $service_id);
            return;
        }

        $provision = $this->provision_service_on_provider($service);
        if (!empty($provision['ok']) && (int) $provision['remote_id'] > 0) {
            $this->Service_model->set_provider_server_id((int) $service_id, (int) $provision['remote_id']);
            if (!empty($provision['password'])) {
                $this->Service_model->set_root_password((int) $service_id, $provision['password']);
            }
            $this->session->set_flashdata('client_service_success', 'Provider linked successfully: '.$provision['remote_id']);
        } else {
            $this->session->set_flashdata('client_service_error', isset($provision['message']) ? $provision['message'] : 'Provider provisioning failed.');
        }
        redirect('client/services/'.(int) $service_id);
    }

    public function plans()
    {
        if (!$this->require_login_web()) { return; }
        $user = $this->current_user();
        if ($this->is_admin($user)) {
            $this->admin_plans();
            return;
        }
        $this->render_client('portal/client_store', array('title' => 'Store', 'user' => $user, 'plans' => $this->Service_model->list_plans()));
    }

    public function client_checkout()
    {
        $user = $this->ensure_client(); if (!$user) { return; }
        $plan_id = (int) $this->input->get('plan', true);
        $plan = NULL;
        foreach ($this->Service_model->list_plans() as $p) {
            if ((int) $p['id'] === $plan_id) {
                $plan = $p;
                break;
            }
        }
        $this->render_client('portal/client_checkout', array('title' => 'Checkout', 'user' => $user, 'plan' => $plan));
    }

    public function create_order()
    {
        $user = $this->ensure_client(); if (!$user) { return; }
        if (strtoupper((string) $this->input->method()) !== 'POST') {
            redirect('store');
            return;
        }

        $plan_id = (int) $this->input->post('plan_id', true);
        $hostname = trim((string) $this->input->post('hostname', true));
        $location_id = (int) $this->input->post('location_id', true);
        $os_id = (int) $this->input->post('os_id', true);

        $location_map = array(1 => 'USA - New York', 2 => 'USA - Los Angeles', 3 => 'Europe - Amsterdam', 4 => 'Asia - Singapore');
        $os_map = array(1 => 'Ubuntu 22.04 LTS', 2 => 'Ubuntu 20.04 LTS', 3 => 'CentOS 8', 4 => 'Debian 11');

        if ($plan_id <= 0 || $hostname === '') {
            $this->session->set_flashdata('error', 'Please select a plan and hostname.');
            redirect('store');
            return;
        }

        $res = $this->Service_model->create_checkout_order(
            (int) $user['id'],
            $plan_id,
            $hostname,
            isset($location_map[$location_id]) ? $location_map[$location_id] : 'Auto Location',
            isset($os_map[$os_id]) ? $os_map[$os_id] : 'Auto OS'
        );

        if (!$res || empty($res['invoice_id'])) {
            $this->session->set_flashdata('error', 'Unable to create order right now.');
            redirect('store');
            return;
        }

        $this->session->set_flashdata('success', 'Order created. Please pay invoice #'.(int) $res['invoice_id']);
        redirect('client/invoices');
    }

    public function orders()
    {
        if (!$this->require_login_web()) { return; }
        $user = $this->current_user();
        if ($this->is_admin($user)) {
            $this->admin_orders();
            return;
        }
        $this->render_client('portal/client_orders', array('title' => 'Orders', 'user' => $user, 'rows' => $this->Service_model->list_orders_for_user($user)));
    }

    public function invoices()
    {
        if (!$this->require_login_web()) { return; }
        $user = $this->current_user();
        if ($this->is_admin($user)) {
            $this->admin_invoices();
            return;
        }
        $this->render_client('portal/client_invoices', array('title' => 'Invoices', 'user' => $user, 'rows' => $this->Service_model->list_invoices_for_user($user)));
    }

    public function client_invoice_pay($invoice_id)
    {
        $user = $this->ensure_client(); if (!$user) { return; }
        $invoice_id = (int) $invoice_id;
        $result = $this->Service_model->pay_invoice_demo($invoice_id, $user);
        if (empty($result['ok'])) {
            $this->session->set_flashdata('error', isset($result['message']) ? $result['message'] : 'Unable to process payment.');
            redirect('client/invoices');
            return;
        }

        $service_id = (int) (isset($result['service_id']) ? $result['service_id'] : 0);
        $msg = !empty($result['already_paid']) ? 'Invoice was already paid.' : 'Invoice paid successfully (demo).';

        $service = $this->Service_model->find_for_user($service_id, $user);
        if ($service && (int) (isset($service['provider_server_id']) ? $service['provider_server_id'] : 0) <= 0) {
            $provision = $this->provision_service_on_provider($service);
            if (!empty($provision['ok']) && (int) $provision['remote_id'] > 0) {
                $this->Service_model->set_provider_server_id($service_id, (int) $provision['remote_id']);
                if (!empty($provision['password'])) {
                    $this->Service_model->set_root_password($service_id, $provision['password']);
                }
                $msg .= ' | Provider linked: '.$provision['remote_id'];
            } else {
                $msg .= ' | Note: Provider not linked yet. Use "Provision on Provider" from service page.';
            }
        }

        $this->audit_event($user, 'invoice.paid_demo', array(
            'invoice_id' => $invoice_id,
            'service_id' => $service_id,
            'transaction_id' => isset($result['transaction_id']) ? $result['transaction_id'] : NULL,
        ));
        $this->session->set_flashdata('success', $msg);
        redirect('client/services');
    }

    public function client_tickets()
    {
        $user = $this->ensure_client(); if (!$user) { return; }
        $this->render_client('portal/client_tickets', array(
            'title' => 'Tickets',
            'user' => $user,
            'rows' => $this->Service_model->list_table_for_user('tickets', $user),
            'flash_success' => $this->session->flashdata('success'),
            'flash_error' => $this->session->flashdata('error'),
        ));
    }

    public function client_ticket_create()
    {
        $user = $this->ensure_client(); if (!$user) { return; }
        $subject = trim((string) $this->input->post('subject', true));
        if ($subject === '' || strlen($subject) < 8) {
            $this->session->set_flashdata('error', 'Please provide a meaningful ticket subject (min 8 chars).');
            redirect('client/tickets');
            return;
        }
        $id = $this->Service_model->create_ticket((int) $user['id'], $subject);
        if ($id) {
            $this->audit_event($user, 'ticket.create', array('ticket_id' => $id));
            $this->session->set_flashdata('success', 'Ticket created successfully.');
        } else {
            $this->session->set_flashdata('error', 'Unable to create ticket.');
        }
        redirect('client/tickets');
    }

    public function clients()
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $this->render_admin('portal/admin_clients', array('title' => 'Clients', 'user' => $user, 'rows' => $this->User_model->list_clients()));
    }

    public function admin_invoices()
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $this->render_admin('portal/admin_invoices', array('title' => 'Invoices', 'user' => $user, 'rows' => $this->Service_model->admin_list('invoices')));
    }

    public function admin_orders()
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $this->render_admin('portal/admin_orders', array('title' => 'Orders', 'user' => $user, 'rows' => $this->Service_model->admin_list('orders')));
    }

    public function domains()
    {
        if (!$this->require_login_web()) { return; }
        $user = $this->current_user();
        if ($this->is_admin($user)) {
            $this->render_admin('portal/admin_domains', array('title' => 'Domains', 'user' => $user, 'rows' => $this->Service_model->admin_list('domains')));
            return;
        }
        redirect('client');
    }

    public function tickets()
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $this->render_admin('portal/admin_tickets', array('title' => 'Tickets', 'user' => $user, 'rows' => $this->Service_model->admin_list('tickets')));
    }

    public function admin_audit_logs()
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $this->render_admin('portal/admin_audit_logs', array('title' => 'Audit Logs', 'user' => $user, 'rows' => $this->Service_model->admin_list('audit_logs')));
    }

    public function admin_locations()
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $this->render_admin('portal/admin_locations', array('title' => 'Locations', 'user' => $user, 'rows' => $this->seed_rows('locations')));
    }

    public function admin_backups()
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $this->render_admin('portal/admin_backups', array('title' => 'Backups', 'user' => $user, 'rows' => $this->seed_rows('backups')));
    }

    public function admin_ip_blocks()
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $this->render_admin('portal/admin_ip_blocks', array('title' => 'IP Blocks', 'user' => $user, 'rows' => $this->seed_rows('ip_blocks')));
    }

    public function admin_compute_resources()
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $this->render_admin('portal/admin_compute_resources', array('title' => 'Compute Resources', 'user' => $user, 'rows' => $this->seed_rows('compute_resources')));
    }
}
