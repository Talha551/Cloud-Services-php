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
                array('id' => 1, 'name' => 'Ubuntu 24.04 LTS', 'label' => 'Ubuntu 24.04 LTS', 'type' => 'linux', 'status' => 'available'),
                array('id' => 2, 'name' => 'Ubuntu 22.04 LTS', 'label' => 'Ubuntu 22.04 LTS', 'type' => 'linux', 'status' => 'available'),
                array('id' => 3, 'name' => 'Ubuntu 20.04 LTS', 'label' => 'Ubuntu 20.04 LTS', 'type' => 'linux', 'status' => 'available'),
                array('id' => 4, 'name' => 'Debian 12', 'label' => 'Debian 12', 'type' => 'linux', 'status' => 'available'),
                array('id' => 5, 'name' => 'Debian 11', 'label' => 'Debian 11', 'type' => 'linux', 'status' => 'available'),
                array('id' => 6, 'name' => 'AlmaLinux 9', 'label' => 'AlmaLinux 9', 'type' => 'linux', 'status' => 'available'),
                array('id' => 7, 'name' => 'AlmaLinux 8', 'label' => 'AlmaLinux 8', 'type' => 'linux', 'status' => 'available'),
                array('id' => 8, 'name' => 'Rocky Linux 9', 'label' => 'Rocky Linux 9', 'type' => 'linux', 'status' => 'available'),
                array('id' => 9, 'name' => 'Rocky Linux 8', 'label' => 'Rocky Linux 8', 'type' => 'linux', 'status' => 'available'),
                array('id' => 10, 'name' => 'CentOS Stream 9', 'label' => 'CentOS Stream 9', 'type' => 'linux', 'status' => 'available'),
                array('id' => 11, 'name' => 'CentOS 8', 'label' => 'CentOS 8', 'type' => 'linux', 'status' => 'available'),
                array('id' => 12, 'name' => 'Fedora 40', 'label' => 'Fedora 40', 'type' => 'linux', 'status' => 'available'),
                array('id' => 13, 'name' => 'Fedora 39', 'label' => 'Fedora 39', 'type' => 'linux', 'status' => 'available'),
                array('id' => 14, 'name' => 'openSUSE Leap 15.6', 'label' => 'openSUSE Leap 15.6', 'type' => 'linux', 'status' => 'available'),
                array('id' => 15, 'name' => 'Oracle Linux 9', 'label' => 'Oracle Linux 9', 'type' => 'linux', 'status' => 'available'),
                array('id' => 16, 'name' => 'Oracle Linux 8', 'label' => 'Oracle Linux 8', 'type' => 'linux', 'status' => 'available'),
                array('id' => 17, 'name' => 'Arch Linux', 'label' => 'Arch Linux', 'type' => 'linux', 'status' => 'available'),
                array('id' => 18, 'name' => 'Ubuntu 22.04 + Docker', 'label' => 'Ubuntu 22.04 + Docker', 'type' => 'template', 'status' => 'available'),
                array('id' => 19, 'name' => 'Debian 12 + Docker', 'label' => 'Debian 12 + Docker', 'type' => 'template', 'status' => 'available'),
                array('id' => 20, 'name' => 'Windows Server 2022', 'label' => 'Windows Server 2022', 'type' => 'windows', 'status' => 'available')
            );
        }
        if ($type === 'applications') {
            return array(
                array('id' => 1, 'name' => 'WordPress', 'label' => 'WordPress', 'type' => 'cms', 'status' => 'available'),
                array('id' => 2, 'name' => 'WooCommerce', 'label' => 'WooCommerce', 'type' => 'ecommerce', 'status' => 'available'),
                array('id' => 3, 'name' => 'Magento', 'label' => 'Magento', 'type' => 'ecommerce', 'status' => 'available'),
                array('id' => 4, 'name' => 'OpenCart', 'label' => 'OpenCart', 'type' => 'ecommerce', 'status' => 'available'),
                array('id' => 5, 'name' => 'PrestaShop', 'label' => 'PrestaShop', 'type' => 'ecommerce', 'status' => 'available'),
                array('id' => 6, 'name' => 'Joomla', 'label' => 'Joomla', 'type' => 'cms', 'status' => 'available'),
                array('id' => 7, 'name' => 'Drupal', 'label' => 'Drupal', 'type' => 'cms', 'status' => 'available'),
                array('id' => 8, 'name' => 'Ghost', 'label' => 'Ghost', 'type' => 'blog', 'status' => 'available'),
                array('id' => 9, 'name' => 'Nextcloud', 'label' => 'Nextcloud', 'type' => 'cloud', 'status' => 'available'),
                array('id' => 10, 'name' => 'ownCloud', 'label' => 'ownCloud', 'type' => 'cloud', 'status' => 'available'),
                array('id' => 11, 'name' => 'Moodle', 'label' => 'Moodle', 'type' => 'education', 'status' => 'available'),
                array('id' => 12, 'name' => 'phpMyAdmin', 'label' => 'phpMyAdmin', 'type' => 'database', 'status' => 'available'),
                array('id' => 13, 'name' => 'Node.js Runtime', 'label' => 'Node.js Runtime', 'type' => 'runtime', 'status' => 'available'),
                array('id' => 14, 'name' => 'Python Runtime', 'label' => 'Python Runtime', 'type' => 'runtime', 'status' => 'available'),
                array('id' => 15, 'name' => 'Docker CE', 'label' => 'Docker CE', 'type' => 'container', 'status' => 'available'),
                array('id' => 16, 'name' => 'LAMP Stack', 'label' => 'LAMP Stack', 'type' => 'stack', 'status' => 'available'),
                array('id' => 17, 'name' => 'LEMP Stack', 'label' => 'LEMP Stack', 'type' => 'stack', 'status' => 'available'),
                array('id' => 18, 'name' => 'DirectAdmin', 'label' => 'DirectAdmin', 'type' => 'panel', 'status' => 'available'),
                array('id' => 19, 'name' => 'cPanel/WHM', 'label' => 'cPanel/WHM', 'type' => 'panel', 'status' => 'available'),
                array('id' => 20, 'name' => 'Plesk', 'label' => 'Plesk', 'type' => 'panel', 'status' => 'available'),
                array('id' => 21, 'name' => 'Webmin', 'label' => 'Webmin', 'type' => 'panel', 'status' => 'available'),
                array('id' => 22, 'name' => 'Virtualmin', 'label' => 'Virtualmin', 'type' => 'panel', 'status' => 'available'),
                array('id' => 23, 'name' => 'HestiaCP', 'label' => 'HestiaCP', 'type' => 'panel', 'status' => 'available'),
                array('id' => 24, 'name' => 'OpenVPN Access Server', 'label' => 'OpenVPN Access Server', 'type' => 'vpn', 'status' => 'available'),
                array('id' => 25, 'name' => 'GitLab CE', 'label' => 'GitLab CE', 'type' => 'devops', 'status' => 'available')
            );
        }
        return array();
    }

    private function location_options()
    {
        return array(
            1 => 'USA - New York',
            2 => 'USA - Los Angeles',
            3 => 'USA - Dallas',
            4 => 'Canada - Toronto',
            5 => 'Europe - Amsterdam',
            6 => 'Europe - Frankfurt',
            7 => 'Europe - London',
            8 => 'Asia - Singapore',
            9 => 'Asia - Tokyo',
            10 => 'Asia - Mumbai',
            11 => 'Middle East - Dubai',
            12 => 'Australia - Sydney'
        );
    }

    private function os_label_map()
    {
        $map = array();
        $rows = $this->seed_rows('os_images');
        foreach ($rows as $row) {
            $id = isset($row['id']) ? (int) $row['id'] : 0;
            if ($id <= 0) {
                continue;
            }
            $map[$id] = isset($row['label']) ? (string) $row['label'] : (isset($row['name']) ? (string) $row['name'] : ('OS '.$id));
        }
        return $map;
    }

    private function application_label_map()
    {
        $map = array();
        $rows = $this->seed_rows('applications');
        foreach ($rows as $row) {
            $id = isset($row['id']) ? (int) $row['id'] : 0;
            if ($id <= 0) {
                continue;
            }
            $map[$id] = isset($row['label']) ? (string) $row['label'] : (isset($row['name']) ? (string) $row['name'] : ('Application '.$id));
        }
        return $map;
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

    private function extract_provider_rows($result)
    {
        if (!is_array($result) || empty($result['ok']) || !isset($result['data']) || !is_array($result['data'])) {
            return array();
        }

        $data = $result['data'];
        if (isset($data['data']) && is_array($data['data'])) {
            $nested = $data['data'];
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
            if (isset($data[$k]) && is_array($data[$k]) && array_keys($data[$k]) === range(0, count($data[$k]) - 1)) {
                return $data[$k];
            }
        }

        if (array_keys($data) === range(0, count($data) - 1)) {
            return $data;
        }

        return array();
    }

    private function extract_provider_object($result)
    {
        if (!is_array($result) || empty($result['ok']) || !isset($result['data']) || !is_array($result['data'])) {
            return array();
        }

        $data = $result['data'];
        if (isset($data['data']) && is_array($data['data']) && array_keys($data['data']) !== range(0, count($data['data']) - 1)) {
            return $data['data'];
        }

        return $data;
    }

    private function pick_provider_remote_id($result)
    {
        $obj = $this->extract_provider_object($result);
        if (isset($obj['id']) && (int) $obj['id'] > 0) {
            return (int) $obj['id'];
        }
        if (isset($obj['server_id']) && (int) $obj['server_id'] > 0) {
            return (int) $obj['server_id'];
        }
        return 0;
    }

    private function pick_provider_password($result)
    {
        $obj = $this->extract_provider_object($result);
        foreach (array('password', 'root_password', 'vnc_password') as $key) {
            if (isset($obj[$key]) && is_scalar($obj[$key]) && trim((string) $obj[$key]) !== '') {
                return (string) $obj[$key];
            }
        }
        return null;
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
            $plan_rows = $this->extract_provider_rows($plans_result);
            if (!empty($plan_rows)) {
                $solus_plan_id = $this->first_provider_id_from_rows($plan_rows);
            }
        }
        if ($solus_location_id <= 0) {
            $locations_result = $this->solusvm_client->list_locations();
            $location_rows = $this->extract_provider_rows($locations_result);
            if (!empty($location_rows)) {
                $solus_location_id = $this->first_provider_id_from_rows($location_rows);
            }
        }
        if ($solus_os_id <= 0) {
            $os_result = $this->solusvm_client->list_os_images();
            $os_rows = $this->extract_provider_rows($os_result);
            if (!empty($os_rows)) {
                $solus_os_id = $this->first_provider_id_from_rows($os_rows);
            }
        }

        if ($solus_plan_id <= 0 || $solus_location_id <= 0 || $solus_os_id <= 0) {
            return array(
                'ok' => false,
                'message' => 'Provider provisioning skipped: unable to resolve valid plan/location/os IDs from provider.',
                'remote_id' => 0,
            );
        }

        $payload = array(
            'plan' => $solus_plan_id,
            'location' => $solus_location_id,
            'os' => $solus_os_id,
            'hostname' => $hostname,
            'name' => $hostname,
            'host_name' => $hostname,
            'fqdn' => $hostname,
        );
        $service_root_password = isset($service['root_password']) ? trim((string) $service['root_password']) : '';
        if ($service_root_password !== '') {
            $payload['password'] = $service_root_password;
        }
        $result = $this->solusvm_client->create_server($payload);

        if (!$result['ok']) {
            $provider_msg = isset($result['data']['message']) ? strtolower((string) $result['data']['message']) : '';
            $needs_location_retry = strpos($provider_msg, 'location') !== false && strpos($provider_msg, 'not exists') !== false;
            $needs_os_retry = strpos($provider_msg, 'os') !== false && strpos($provider_msg, 'not exists') !== false;
            $needs_plan_retry = strpos($provider_msg, 'plan') !== false && strpos($provider_msg, 'not exists') !== false;

            if ($needs_location_retry || $needs_os_retry || $needs_plan_retry) {
                if ($needs_plan_retry) {
                    $plans_result = $this->solusvm_client->list_plans();
                    $plan_rows = $this->extract_provider_rows($plans_result);
                    if (!empty($plan_rows)) {
                        $solus_plan_id = $this->first_provider_id_from_rows($plan_rows);
                    }
                }
                if ($needs_location_retry) {
                    $locations_result = $this->solusvm_client->list_locations();
                    $location_rows = $this->extract_provider_rows($locations_result);
                    if (!empty($location_rows)) {
                        $solus_location_id = $this->first_provider_id_from_rows($location_rows);
                    }
                }
                if ($needs_os_retry) {
                    $os_result = $this->solusvm_client->list_os_images();
                    $os_rows = $this->extract_provider_rows($os_result);
                    if (!empty($os_rows)) {
                        $solus_os_id = $this->first_provider_id_from_rows($os_rows);
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

        $remote_id = $this->pick_provider_remote_id($result);
        $created_password = $this->pick_provider_password($result);

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
                'portal/admin_users' => 'clients',
                'portal/admin_projects' => 'projects',
                'portal/admin_clients' => 'clients',
                'portal/admin_client_detail' => 'clients',
                'portal/admin_invoices' => 'invoices',
                'portal/admin_orders' => 'orders',
                'portal/admin_domains' => 'domains',
                'portal/admin_tickets' => 'tickets',
                'portal/admin_audit_logs' => 'audit-logs',
                'portal/admin_locations' => 'locations',
                'portal/admin_backups' => 'backups',
                'portal/admin_ip_blocks' => 'ip-blocks',
                'portal/account_profile' => 'profile',
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
                'portal/client_credits' => 'credits',
                'portal/client_tickets' => 'tickets',
                'portal/client_store' => 'store',
                'portal/client_checkout' => 'store',
                'portal/account_profile' => 'profile',
            );
            $data['active_nav'] = isset($client_nav_map[$view]) ? $client_nav_map[$view] : 'dashboard';
        }
        if (!isset($data['credit_balance']) && isset($data['user']['id'])) {
            $data['credit_balance'] = $this->Service_model->get_user_credit_balance((int) $data['user']['id']);
        }
        $this->load->view('portal/client_header', $data);
        $this->load->view($view, $data);
        $this->load->view('portal/client_footer');
    }

    private function handle_profile_update($user, $redirect_path)
    {
        if (strtoupper((string) $this->input->method()) !== 'POST') {
            redirect($redirect_path);
            return;
        }

        $full_name = trim((string) $this->input->post('full_name', true));
        $email = strtolower(trim((string) $this->input->post('email', true)));
        $current_password = (string) $this->input->post('current_password', true);
        $new_password = (string) $this->input->post('new_password', true);
        $confirm_password = (string) $this->input->post('confirm_password', true);

        if ($full_name === '' || strlen($full_name) < 2) {
            $this->session->set_flashdata('error', 'Name is required (minimum 2 characters).');
            redirect($redirect_path);
            return;
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('error', 'Please provide a valid email address.');
            redirect($redirect_path);
            return;
        }

        $existing = $this->User_model->find_by_email($email);
        if ($existing && (int) $existing['id'] !== (int) $user['id']) {
            $this->session->set_flashdata('error', 'Email is already in use by another account.');
            redirect($redirect_path);
            return;
        }

        $password_requested = trim($new_password) !== '' || trim($confirm_password) !== '' || trim($current_password) !== '';
        if ($password_requested) {
            if (strlen($new_password) < 8) {
                $this->session->set_flashdata('error', 'New password must be at least 8 characters.');
                redirect($redirect_path);
                return;
            }
            if ($new_password !== $confirm_password) {
                $this->session->set_flashdata('error', 'New password and confirm password do not match.');
                redirect($redirect_path);
                return;
            }

            $latest_user = $this->User_model->find_by_id((int) $user['id']);
            if (!$latest_user || !$this->User_model->verify_password($latest_user, $current_password)) {
                $this->session->set_flashdata('error', 'Current password is incorrect.');
                redirect($redirect_path);
                return;
            }
        }

        $this->User_model->update_profile((int) $user['id'], array(
            'full_name' => $full_name,
            'email' => $email,
        ));

        if ($password_requested) {
            $this->User_model->update_password((int) $user['id'], $new_password);
        }

        $this->session->set_userdata('user_name', $full_name);
        $this->audit_event($user, 'account.profile_update', array('email' => $email, 'password_changed' => $password_requested));
        $this->session->set_flashdata('success', $password_requested ? 'Profile and password updated successfully.' : 'Profile updated successfully.');
        redirect($redirect_path);
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

        if (($http_console_url === '' && $ws_url === '') && $token !== '') {
            if (preg_match('#^https?://#i', $token)) {
                $http_console_url = $token;
            } elseif (preg_match('#^wss?://#i', $token)) {
                $ws_url = $token;
            } else {
                $cfg = $this->get_solus_config();
                $base_url = isset($cfg['base_url']) ? trim((string) $cfg['base_url']) : '';
                if ($base_url !== '') {
                    $http_console_url = rtrim($base_url, '/').'/'.ltrim($token, '/');
                }
            }
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
        $server_data = $this->extract_provider_object($server_result);
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
        $os_options = $this->os_label_map();
        $location_options = $this->location_options();
        $application_options = $this->application_label_map();
        $this->render_admin('portal/admin_server_create', array(
            'title' => 'Create Server',
            'user' => $user,
            'plans' => $this->Service_model->list_plans(),
            'os_options' => $os_options,
            'location_options' => $location_options,
            'application_options' => $application_options,
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
        $location_id = (int) $this->input->post('location_id', true);
        $os_id = (int) $this->input->post('os_id', true);
        $application_id = (int) $this->input->post('application_id', true);
        $location_label = trim((string) $this->input->post('location_label', true));
        $os_label = trim((string) $this->input->post('os_label', true));
        $vps_password = trim((string) $this->input->post('vps_password', true));

        $location_options = $this->location_options();
        $os_options = $this->os_label_map();
        $application_options = $this->application_label_map();

        if ($location_label === '' && isset($location_options[$location_id])) {
            $location_label = (string) $location_options[$location_id];
        }
        if ($os_label === '' && isset($os_options[$os_id])) {
            $os_label = (string) $os_options[$os_id];
        }
        $application_label = isset($application_options[$application_id]) ? (string) $application_options[$application_id] : '';

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

        $location_key = $location_id > 0 ? $location_id : 0;
        $os_key = $os_id > 0 ? $os_id : 0;

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
            'os' => $application_label !== '' ? ($os_label.' | App: '.$application_label) : $os_label,
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
        $local_status_map = array('start' => 'running', 'stop' => 'stopped', 'restart' => 'running', 'delete' => 'terminated', 'terminate' => 'terminated');
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
                } elseif ($action === 'delete' || $action === 'terminate') {
                    $result = $this->solusvm_client->delete_server($provider_server_id);
                }
                if (!$result['ok']) {
                    $this->session->set_flashdata('admin_service_error', 'Provider action failed: '.(string) $result['error']);
                    redirect('admin/servers/'.(int) $service_id);
                    return;
                }
            }
        }

        $this->Service_model->set_status((int) $service_id, $local_status_map[$action]);
        if ($action === 'delete' || $action === 'terminate') {
            $this->Service_model->set_provider_server_id((int) $service_id, NULL);
        }
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
        $app_rows = $this->seed_rows('applications');

        $cfg = $this->get_solus_config();
        $this->load->library('Solusvm_client');
        if (!empty($cfg['base_url']) && !empty($cfg['api_token']) && $this->solusvm_client->is_configured()) {
            $os_result = $this->solusvm_client->list_os_images();
            $provider_os_rows = $this->extract_provider_rows($os_result);
            if (!empty($provider_os_rows)) {
                foreach ($provider_os_rows as $provider_row) {
                    if (!is_array($provider_row)) {
                        continue;
                    }
                    $provider_name = '';
                    foreach (array('label', 'name', 'title', 'version_name') as $nkey) {
                        if (!empty($provider_row[$nkey])) {
                            $provider_name = strtolower(trim((string) $provider_row[$nkey]));
                            break;
                        }
                    }
                    if ($provider_name === '') {
                        $rows[] = $provider_row;
                        continue;
                    }

                    $found = false;
                    foreach ($rows as $existing_row) {
                        if (!is_array($existing_row)) {
                            continue;
                        }
                        foreach (array('label', 'name', 'title', 'version_name') as $ekey) {
                            if (!empty($existing_row[$ekey]) && strtolower(trim((string) $existing_row[$ekey])) === $provider_name) {
                                $found = true;
                                break 2;
                            }
                        }
                    }
                    if (!$found) {
                        $rows[] = $provider_row;
                    }
                }
            }

            $app_result = $this->solusvm_client->list_applications();
            $provider_app_rows = $this->extract_provider_rows($app_result);
            if (!empty($provider_app_rows)) {
                foreach ($provider_app_rows as $provider_row) {
                    if (!is_array($provider_row)) {
                        continue;
                    }
                    $provider_name = '';
                    foreach (array('label', 'name', 'title') as $nkey) {
                        if (!empty($provider_row[$nkey])) {
                            $provider_name = strtolower(trim((string) $provider_row[$nkey]));
                            break;
                        }
                    }
                    if ($provider_name === '') {
                        $app_rows[] = $provider_row;
                        continue;
                    }

                    $found = false;
                    foreach ($app_rows as $existing_row) {
                        if (!is_array($existing_row)) {
                            continue;
                        }
                        foreach (array('label', 'name', 'title') as $ekey) {
                            if (!empty($existing_row[$ekey]) && strtolower(trim((string) $existing_row[$ekey])) === $provider_name) {
                                $found = true;
                                break 2;
                            }
                        }
                    }
                    if (!$found) {
                        $app_rows[] = $provider_row;
                    }
                }
            }
        }

        $this->render_admin('portal/admin_os_images', array(
            'title' => 'OS Images',
            'user' => $user,
            'rows' => $rows,
            'app_rows' => $app_rows,
        ));
    }

    public function admin_users()
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        redirect('admin/clients');
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

    public function client_profile()
    {
        $user = $this->ensure_client(); if (!$user) { return; }
        $this->render_client('portal/account_profile', array(
            'title' => 'My Profile',
            'user' => $user,
            'profile_mode' => 'client',
            'submit_url' => site_url('client/profile/update'),
            'flash_success' => $this->session->flashdata('success'),
            'flash_error' => $this->session->flashdata('error'),
        ));
    }

    public function client_profile_update()
    {
        $user = $this->ensure_client(); if (!$user) { return; }
        $this->handle_profile_update($user, 'client/profile');
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
                $provider_server_obj = $this->extract_provider_object($sres);
                if (!empty($provider_server_obj)) {
                    $provider_server = $provider_server_obj;
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
                $os_rows = $this->extract_provider_rows($os_res);
                if (!empty($os_rows)) {
                    $available_os = $os_rows;
                } else {
                    $os_dropdown_note = 'OS list could not be fetched from provider.';
                }
                $app_res = $this->solusvm_client->list_applications();
                $application_rows = $this->extract_provider_rows($app_res);
                if (!empty($application_rows)) {
                    $applications = $application_rows;
                }
            }
        }

        if (empty($available_os)) {
            $available_os = $this->seed_rows('os_images');
        }
        if (empty($applications)) {
            $applications = $this->seed_rows('applications');
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
        $local_status_map = array('start' => 'running', 'stop' => 'stopped', 'restart' => 'running', 'delete' => 'terminated', 'terminate' => 'terminated');
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
                } elseif ($action === 'delete' || $action === 'terminate') {
                    $result = $this->solusvm_client->delete_server($provider_server_id);
                }
                if (!$result['ok']) {
                    $this->session->set_flashdata('client_service_error', 'Provider action failed: '.(string) $result['error']);
                    redirect('client/services/'.(int) $service_id);
                    return;
                }
            }
        }

        $this->Service_model->set_status((int) $service_id, $local_status_map[$action]);
        if ($action === 'delete' || $action === 'terminate') {
            $this->Service_model->set_provider_server_id((int) $service_id, NULL);
        }
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

    public function client_service_install_request($service_id)
    {
        $user = $this->ensure_client(); if (!$user) { return; }
        $service = $this->Service_model->find_for_user((int) $service_id, $user);
        if (!$service) { show_404(); return; }

        $note = trim((string) $this->input->post('note', true));
        $subject = '[Install Request] Service #'.(int) $service['id'].' ('.(string) ($service['hostname'] ? $service['hostname'] : $service['name']).')';
        if ($note !== '') {
            $subject .= ' | '.$note;
        }

        $ticket_id = $this->Service_model->create_ticket((int) $user['id'], $subject);
        if ($ticket_id) {
            $this->audit_event($user, 'service.install_request', array(
                'service_id' => (int) $service['id'],
                'ticket_id' => (int) $ticket_id,
            ));
            $this->session->set_flashdata('client_service_success', 'Install request sent to admin successfully (Ticket #'.(int) $ticket_id.').');
        } else {
            $this->session->set_flashdata('client_service_error', 'Unable to create install request right now.');
        }

        redirect('client/services/'.(int) $service_id);
    }

    public function admin_server_detail($service_id)
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $service = $this->Service_model->find_for_user((int) $service_id, array('role' => 'admin', 'id' => 0));
        if (!$service) { show_404(); return; }

        $provider_server_id = (int) (isset($service['provider_server_id']) ? $service['provider_server_id'] : 0);
        $provider_server = array();
        $provider_os_name = '';
        $provider_app_name = '';
        $provider_ip = '';
        $provider_bandwidth_limit = 0;
        $provider_bandwidth_used = 0;
        $provider_resources = array();
        $provider_is_processing = false;
        $provider_app_login_link = '';
        if ($provider_server_id > 0) {
            $this->load->library('Solusvm_client');
            $result = $this->solusvm_client->get_server($provider_server_id);
            if ($result['ok'] && isset($result['data']['data']) && is_array($result['data']['data'])) {
                $provider_server = $result['data']['data'];
                $provider_os_name = isset($provider_server['os']['name']) ? $provider_server['os']['name'] : '';
                $provider_app_name = isset($provider_server['application']['name']) ? $provider_server['application']['name'] : '';
                $provider_ip = isset($provider_server['ip_address']) ? $provider_server['ip_address'] : '';
                $provider_bandwidth_limit = isset($provider_server['bandwidth_limit']) ? (float) $provider_server['bandwidth_limit'] : 0;
                $provider_bandwidth_used = isset($provider_server['bandwidth_used']) ? (float) $provider_server['bandwidth_used'] : 0;
                // Always use provider values if present, never fallback to local
                $provider_resources = array(
                    'vcpu' => isset($provider_server['vcpu']) ? (int) $provider_server['vcpu'] : null,
                    'memory' => isset($provider_server['memory']) ? (int) $provider_server['memory'] : null,
                    'disk' => isset($provider_server['disk']) ? (int) $provider_server['disk'] : null,
                );
                $provider_is_processing = isset($provider_server['is_processing']) ? (bool) $provider_server['is_processing'] : false;
                $provider_app_login_link = isset($provider_server['application_login_link']) ? $provider_server['application_login_link'] : '';
            }
        }

        $available_os = $this->seed_rows('os_images');
        $applications = $this->seed_rows('applications');
        $os_dropdown_note = '';
        $vps_password = isset($service['root_password']) ? $service['root_password'] : '';

        $this->render_admin('portal/admin_server_detail', array(
            'title' => 'Server Detail',
            'user' => $user,
            'service' => $service,
            'provider_server' => $provider_server,
            'provider_os_name' => $provider_os_name,
            'provider_app_name' => $provider_app_name,
            'provider_ip' => $provider_ip,
            'provider_bandwidth_limit' => $provider_bandwidth_limit,
            'provider_bandwidth_used' => $provider_bandwidth_used,
            'provider_resources' => $provider_resources,
            'provider_is_processing' => $provider_is_processing,
            'provider_app_login_link' => $provider_app_login_link,
            'available_os' => $available_os,
            'applications' => $applications,
            'os_dropdown_note' => $os_dropdown_note,
            'vps_password' => $vps_password,
            'flash_success' => $this->session->flashdata('admin_service_success'),
            'flash_error' => $this->session->flashdata('admin_service_error'),
        ));
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
        $this->render_client('portal/client_checkout', array(
            'title' => 'Checkout',
            'user' => $user,
            'plan' => $plan,
            'location_options' => $this->location_options(),
            'os_options' => $this->os_label_map(),
            'application_options' => $this->application_label_map(),
        ));
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
        $application_id = (int) $this->input->post('application_id', true);
        $root_password = trim((string) $this->input->post('root_password', true));

        $location_map = $this->location_options();
        $os_map = $this->os_label_map();
        $application_map = $this->application_label_map();

        if ($plan_id <= 0 || $hostname === '') {
            $this->session->set_flashdata('error', 'Please select a plan and hostname.');
            redirect('store');
            return;
        }

        if ($root_password !== '' && strlen($root_password) < 8) {
            $this->session->set_flashdata('error', 'Root password must be at least 8 characters, or leave blank to auto-generate.');
            redirect('checkout?plan='.$plan_id);
            return;
        }

        $selected_os = isset($os_map[$os_id]) ? $os_map[$os_id] : 'Auto OS';
        if (isset($application_map[$application_id]) && trim((string) $application_map[$application_id]) !== '') {
            $selected_os .= ' | App: '.(string) $application_map[$application_id];
        }

        $res = $this->Service_model->create_checkout_order(
            (int) $user['id'],
            $plan_id,
            $hostname,
            isset($location_map[$location_id]) ? $location_map[$location_id] : 'Auto Location',
            $selected_os,
            $root_password
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

        if (!empty($result['credits_added'])) {
            $balance = isset($result['balance']) ? (float) $result['balance'] : $this->Service_model->get_user_credit_balance((int) $user['id']);
            $msg = (!empty($result['already_paid']) ? 'Invoice was already paid.' : 'Credits purchased successfully.')
                .' | Wallet balance: $'.number_format($balance, 2);
            $this->audit_event($user, 'invoice.credits_topup_paid_demo', array(
                'invoice_id' => $invoice_id,
                'transaction_id' => isset($result['transaction_id']) ? $result['transaction_id'] : NULL,
                'balance' => $balance,
            ));
            $this->session->set_flashdata('success', $msg);
            redirect('client/credits');
            return;
        }

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
        if ($service_id > 0) {
            redirect('client/services/'.(int) $service_id);
            return;
        }
        redirect('client/services');
    }

    public function client_invoice_pay_with_credits($invoice_id)
    {
        $user = $this->ensure_client(); if (!$user) { return; }
        $invoice_id = (int) $invoice_id;
        $result = $this->Service_model->pay_invoice_with_credits($invoice_id, $user);
        if (empty($result['ok'])) {
            $this->session->set_flashdata('error', isset($result['message']) ? $result['message'] : 'Unable to pay invoice with credits.');
            redirect('client/invoices');
            return;
        }

        $balance = isset($result['balance']) ? (float) $result['balance'] : $this->Service_model->get_user_credit_balance((int) $user['id']);
        $service_id = (int) (isset($result['service_id']) ? $result['service_id'] : 0);

        if (!empty($result['credits_added'])) {
            $msg = (!empty($result['already_paid']) ? 'Invoice was already paid.' : 'Credits purchased from wallet successfully.')
                .' | Wallet balance: $'.number_format($balance, 2);
            $this->session->set_flashdata('success', $msg);
            $this->audit_event($user, 'invoice.credits_topup_paid_wallet', array(
                'invoice_id' => $invoice_id,
                'balance' => $balance,
                'transaction_id' => isset($result['transaction_id']) ? $result['transaction_id'] : NULL,
            ));
            redirect('client/credits');
            return;
        }

        $msg = (!empty($result['already_paid']) ? 'Invoice was already paid.' : 'Invoice paid with wallet credits.')
            .' | Wallet balance: $'.number_format($balance, 2);
        $this->session->set_flashdata('success', $msg);
        $this->audit_event($user, 'invoice.paid_wallet', array(
            'invoice_id' => $invoice_id,
            'service_id' => $service_id,
            'balance' => $balance,
            'transaction_id' => isset($result['transaction_id']) ? $result['transaction_id'] : NULL,
        ));

        if ($service_id > 0) {
            redirect('client/services/'.(int) $service_id);
            return;
        }
        redirect('client/services');
    }

    public function client_credits()
    {
        $user = $this->ensure_client(); if (!$user) { return; }
        $this->render_client('portal/client_credits', array(
            'title' => 'Wallet Credits',
            'user' => $user,
            'balance' => $this->Service_model->get_user_credit_balance((int) $user['id']),
            'rows' => $this->Service_model->list_credit_transactions_for_user($user),
            'flash_success' => $this->session->flashdata('success'),
            'flash_error' => $this->session->flashdata('error'),
        ));
    }

    public function client_credits_topup()
    {
        $user = $this->ensure_client(); if (!$user) { return; }
        if (strtoupper((string) $this->input->method()) !== 'POST') {
            redirect('client/credits');
            return;
        }

        $amount = round((float) $this->input->post('amount', true), 2);
        if ($amount < 1) {
            $this->session->set_flashdata('error', 'Minimum topup amount is $1.00');
            redirect('client/credits');
            return;
        }

        $invoice_id = $this->Service_model->create_credit_topup_invoice((int) $user['id'], $amount);
        if (!$invoice_id) {
            $this->session->set_flashdata('error', 'Unable to create topup invoice right now.');
            redirect('client/credits');
            return;
        }

        $this->audit_event($user, 'credits.topup_invoice_created', array(
            'invoice_id' => (int) $invoice_id,
            'amount' => $amount,
        ));
        $this->session->set_flashdata('success', 'Topup invoice #'.(int) $invoice_id.' created. Pay it from invoices page.');
        redirect('client/invoices');
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
        $this->render_admin('portal/admin_clients', array(
            'title' => 'Users & Clients',
            'user' => $user,
            'rows' => $this->User_model->list_all(),
            'flash_success' => $this->session->flashdata('success'),
            'flash_error' => $this->session->flashdata('error'),
        ));
    }

    public function admin_client_send_credit($client_id)
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        if (strtoupper((string) $this->input->method()) !== 'POST') {
            redirect('admin/clients');
            return;
        }

        $client_id = (int) $client_id;
        $amount = round((float) $this->input->post('amount', true), 2);
        $note = trim((string) $this->input->post('note', true));

        if ($client_id <= 0 || $amount <= 0) {
            $this->session->set_flashdata('error', 'Please enter a valid credit amount.');
            redirect('admin/clients');
            return;
        }

        $client = $this->User_model->find_by_id($client_id);
        if (!$client || (isset($client['role']) && $client['role'] !== 'client')) {
            $this->session->set_flashdata('error', 'Client not found.');
            redirect('admin/clients');
            return;
        }

        $res = $this->Service_model->admin_grant_credit($client_id, (int) $user['id'], $amount, $note);
        if (empty($res['ok'])) {
            $this->session->set_flashdata('error', isset($res['message']) ? $res['message'] : 'Unable to send credits.');
            redirect('admin/clients');
            return;
        }

        $this->audit_event($user, 'admin.credit_sent', array(
            'client_id' => $client_id,
            'amount' => $amount,
            'balance' => isset($res['balance']) ? $res['balance'] : NULL,
        ));
        $this->session->set_flashdata('success', 'Credits sent successfully.');
        redirect('admin/clients');
    }

    public function admin_client_detail($client_id)
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $client_id = (int) $client_id;

        $client = $this->User_model->find_by_id($client_id);
        if (!$client || (isset($client['role']) && $client['role'] !== 'client')) {
            show_404();
            return;
        }

        $client_scope = array('id' => $client_id, 'role' => 'client');
        $services = $this->Service_model->list_for_user($client_scope);
        $orders = $this->Service_model->list_orders_for_user($client_scope);
        $invoices = $this->Service_model->list_invoices_for_user($client_scope);
        $domains = $this->Service_model->list_table_for_user('domains', $client_scope);
        $tickets = $this->Service_model->list_table_for_user('tickets', $client_scope);
        $credit_transactions = $this->Service_model->list_credit_transactions_for_user($client_scope);
        $audit_logs = $this->Service_model->list_audit_logs_for_user($client_id);

        $paid_total = 0.0;
        $unpaid_total = 0.0;
        foreach ($invoices as $invoice) {
            $total = isset($invoice['total']) ? (float) $invoice['total'] : 0.0;
            if (isset($invoice['status']) && strtolower((string) $invoice['status']) === 'paid') {
                $paid_total += $total;
            } else {
                $unpaid_total += $total;
            }
        }

        $this->render_admin('portal/admin_client_detail', array(
            'title' => 'Client Detail',
            'user' => $user,
            'client' => $this->User_model->shape_user($client),
            'client_created_at' => isset($client['created_at']) ? (string) $client['created_at'] : '',
            'stats' => array(
                'services' => count($services),
                'orders' => count($orders),
                'invoices' => count($invoices),
                'tickets' => count($tickets),
                'domains' => count($domains),
                'credits' => $this->Service_model->get_user_credit_balance($client_id),
                'paid_total' => $paid_total,
                'unpaid_total' => $unpaid_total,
            ),
            'services' => $services,
            'orders' => $orders,
            'invoices' => $invoices,
            'domains' => $domains,
            'tickets' => $tickets,
            'credit_transactions' => $credit_transactions,
            'audit_logs' => $audit_logs,
        ));
    }

    public function admin_profile()
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $this->render_admin('portal/account_profile', array(
            'title' => 'Admin Profile',
            'user' => $user,
            'profile_mode' => 'admin',
            'submit_url' => site_url('admin/profile/update'),
            'flash_success' => $this->session->flashdata('success'),
            'flash_error' => $this->session->flashdata('error'),
        ));
    }

    public function admin_profile_update()
    {
        $user = $this->ensure_admin(); if (!$user) { return; }
        $this->handle_profile_update($user, 'admin/profile');
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
