<?php
/**
 * ============================================================================
 * API Client Endpoints Controller
 * ============================================================================
 * 
 * PURPOSE: Manage client-accessible VPS services, orders, invoices, plans
 * STATUS: 85% Complete (core operations working, advanced features missing)
 * AUTHENTICATION: Required - Bearer token or session
 * 
 * DATA ENDPOINTS (14 routes):
 *   GET  /api/client/profile          - User account details
 *   GET  /api/client/plans            - Available service plans
 *   GET  /api/client/services         - List user's VPS services
 *   GET  /api/client/services/:id     - Single service details
 *   GET  /api/client/orders           - User's purchase orders
 *   GET  /api/client/orders/:id       - Single order details
 *   GET  /api/client/invoices         - User's billing invoices
 *   GET  /api/client/invoices/:id     - Single invoice details
 *   POST /api/client/orders           - Create new VPS order
 * 
 * ACTION ENDPOINTS (VPS Management):
 *   GET  /api/client/services/:id/start      - Start VPS (status=running)
 *   GET  /api/client/services/:id/stop       - Stop VPS (status=stopped)
 *   GET  /api/client/services/:id/restart    - Restart VPS (status=restarting)
 *   GET  /api/client/services/:id/reinstall  - Reinstall OS (status=reinstalling)
 *   GET  /api/client/services/:id/console    - VNC console URL
 *   GET  /api/client/services/:id/stream     - Server logs stream (SSE)
 * 
 * DATA SOURCE: SQLite local database (NOT connected to upstream SolusVM)
 * ARCHITECTURE: All data is mocked/simulated for demo purposes
 * 
 * GAPS vs SolusVM 2.0:
 * ❌ No resize/upgrade operations
 * ❌ No snapshot/backup management
 * ❌ No additional IP provisioning
 * ❌ No bandwidth/resource limit management
 * ❌ No guest tools installation
 * ❌ No VNC/console direct access (stub URL only)
 * ❌ No real upstream API calls to actual hypervisor
 * 
 * INTEGRATION POINTS:
 *   - User_model: User lookups, access control
 *   - Service_model: VPS data, status updates, order creation
 *   - MY_Controller: JWT auth, session management
 * ============================================================================
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends MY_Controller
{
    public function profile()
    {
        $user = $this->require_login_json();
        if (!$user) return;
        $this->json(array('data' => $this->User_model->shape_user($user)), 200);
    }

    public function plans()
    {
        $user = $this->require_login_json();
        if (!$user) return;
        $items = $this->Service_model->list_plans();
        $this->json(array('data' => $items, 'meta' => array('total' => count($items))), 200);
    }

    public function services()
    {
        $user = $this->require_login_json();
        if (!$user) {
            return;
        }

        $services = $this->Service_model->list_for_user($user);
        $this->json(array('data' => $services, 'meta' => array('total' => count($services))), 200);
    }

    public function service($id)
    {
        $user = $this->require_login_json();
        if (!$user) return;

        $service = $this->Service_model->find_for_user((int) $id, $user);
        if (!$service) {
            $this->json(array('message' => 'Service not found'), 404);
            return;
        }

        $this->json(array('data' => $service), 200);
    }

    public function orders()
    {
        $user = $this->require_login_json();
        if (!$user) return;

        if ($this->input->method(TRUE) === 'POST') {
            $plan_id = (int) $this->input_data('plan_id', 0);
            $hostname = (string) $this->input_data('hostname', '');
            $location_id = (string) $this->input_data('location_id', '');
            $os_id = (string) $this->input_data('os_id', '');

            $service_id = $this->Service_model->create_order((int) $user['id'], $plan_id, $hostname, 'Location '.$location_id, 'OS '.$os_id);
            if (!$service_id) {
                $this->json(array('message' => 'Plan not found'), 404);
                return;
            }

            $this->json(array('data' => array('id' => (int) $service_id, 'status' => 'success')), 201);
            return;
        }

        $items = $this->Service_model->list_orders_for_user($user);
        $this->json(array('data' => $items, 'meta' => array('total' => count($items))), 200);
    }

    public function order($id)
    {
        $user = $this->require_login_json();
        if (!$user) return;

        $item = $this->Service_model->find_order_for_user((int) $id, $user);
        if (!$item) {
            $this->json(array('message' => 'Order not found'), 404);
            return;
        }

        $this->json(array('data' => $item), 200);
    }

    public function invoices()
    {
        $user = $this->require_login_json();
        if (!$user) return;

        $items = $this->Service_model->list_invoices_for_user($user);
        $this->json(array('data' => $items, 'meta' => array('total' => count($items))), 200);
    }

    public function invoice($id)
    {
        $user = $this->require_login_json();
        if (!$user) return;

        $item = $this->Service_model->find_invoice_for_user((int) $id, $user);
        if (!$item) {
            $this->json(array('message' => 'Invoice not found'), 404);
            return;
        }

        $this->json(array('data' => $item), 200);
    }

    public function start($service_id)
    {
        $this->remote_action($service_id, 'start');
    }

    public function stop($service_id)
    {
        $this->remote_action($service_id, 'stop');
    }

    public function restart($service_id)
    {
        $this->remote_action($service_id, 'restart');
    }

    public function reinstall($service_id)
    {
        $user = $this->require_login_json();
        if (!$user) {
            return;
        }

        $service = $this->Service_model->find_for_user((int) $service_id, $user);
        if (!$service) {
            $this->json(array('success' => false, 'message' => 'Service not found'), 404);
            return;
        }

        $provider_server_id = $this->resolve_provider_server_id($service);
        if ($provider_server_id <= 0) {
            $this->json(array('success' => false, 'message' => 'Provider server mapping not found'), 422);
            return;
        }

        $payload = array();
        $os_id = (int) $this->input_data('os', 0);
        $application_id = (int) $this->input_data('application', 0);

        if ($os_id > 0 && $application_id > 0) {
            $this->json(array('success' => false, 'message' => 'Provide only one parameter: os or application.'), 422);
            return;
        }

        if ($os_id > 0) {
            $payload['os'] = $os_id;
        }
        if ($application_id > 0) {
            $payload['application'] = $application_id;
        }

        $application_data = $this->input_data('application_data', null);
        if (is_array($application_data) && $application_id <= 0) {
            $this->json(array('success' => false, 'message' => 'application_data requires application id.'), 422);
            return;
        }
        if (is_array($application_data)) {
            $payload['application_data'] = $application_data;
        }

        if (empty($payload)) {
            $payload['os'] = 1;
        }

        $this->load->library('Solusvm_client');
        $result = $this->solusvm_client->reinstall_server($provider_server_id, $payload);
        if (!$result['ok']) {
            $status = $result['status'] > 0 ? $result['status'] : 502;
            $this->json(array(
                'success' => false,
                'message' => 'SolusVM reinstall failed',
                'error' => $result['error'],
                'provider_response' => $result['data']
            ), $status);
            return;
        }

        $this->Service_model->set_status((int) $service_id, 'reinstalling');
        $this->json(array(
            'success' => true,
            'data' => array('status' => 'reinstalling', 'instance_id' => (int) $service_id),
            'provider_response' => $result['data']
        ), 200);
    }

    public function console($service_id)
    {
        $user = $this->require_login_json();
        if (!$user) return;

        $service = $this->Service_model->find_for_user((int) $service_id, $user);
        if (!$service) {
            $this->json(array('message' => 'Service not found'), 404);
            return;
        }

        $provider_server_id = $this->resolve_provider_server_id($service);
        if ($provider_server_id <= 0) {
            $this->json(array('success' => false, 'message' => 'Provider server mapping not found'), 422);
            return;
        }

        $this->load->library('Solusvm_client');
        $result = $this->solusvm_client->vnc_up($provider_server_id);
        if (!$result['ok']) {
            $status = $result['status'] > 0 ? $result['status'] : 502;
            $this->json(array(
                'success' => false,
                'message' => 'SolusVM console failed',
                'error' => $result['error'],
                'provider_response' => $result['data']
            ), $status);
            return;
        }

        $host = isset($result['data']['host']) ? (string) $result['data']['host'] : '';
        $port = isset($result['data']['port']) ? (int) $result['data']['port'] : 0;
        $console_url = '';
        if (isset($result['data']['vnc_proxy_url']) && isset($result['data']['url'])) {
            $console_url = (string) $result['data']['vnc_proxy_url'].(string) $result['data']['url'];
        }

        $this->json(array(
            'success' => true,
            'data' => array(
                'host' => $host,
                'port' => $port,
                'console_url' => $console_url,
                'instance_id' => (int) $service['id'],
                'provider_server_id' => $provider_server_id,
            ),
            'provider_response' => $result['data']
        ), 200);
    }

    public function stream($service_id)
    {
        $user = $this->require_login_json();
        if (!$user) return;

        $service = $this->Service_model->find_for_user((int) $service_id, $user);
        if (!$service) {
            $this->json(array('message' => 'Service not found'), 404);
            return;
        }

        $this->output->set_content_type('text/event-stream');
        $this->output->set_header('Cache-Control: no-cache, no-transform');
        $this->output->set_header('Connection: keep-alive');
        $this->output->set_output("event: stream.ready\n".
            'data: '.json_encode(array('service_id' => (int) $service['id'], 'at' => date('c')))."\n\n");
    }

    private function update_status($service_id, $status)
    {
        $user = $this->require_login_json();
        if (!$user) {
            return;
        }

        $service = $this->Service_model->find_for_user($service_id, $user);
        if (!$service) {
            $this->json(array('success' => false, 'message' => 'Service not found'), 404);
            return;
        }

        $this->Service_model->set_status($service_id, $status);
        $updated = $this->Service_model->find_for_user($service_id, $user);

        $this->json(array('data' => array('status' => $updated['status'], 'instance_id' => (int) $updated['id'])), 200);
    }

    private function resolve_provider_server_id($service)
    {
        if (isset($service['provider_server_id']) && (int) $service['provider_server_id'] > 0) {
            return (int) $service['provider_server_id'];
        }

        $hostname = strtolower((string) (isset($service['hostname']) ? $service['hostname'] : ''));
        $name = strtolower((string) (isset($service['name']) ? $service['name'] : ''));
        if ($hostname === '' && $name === '') {
            return 0;
        }

        $this->load->library('Solusvm_client');
        $servers_result = $this->solusvm_client->list_servers();
        if (!$servers_result['ok'] || !isset($servers_result['data']['data']) || !is_array($servers_result['data']['data'])) {
            return 0;
        }

        foreach ($servers_result['data']['data'] as $row) {
            $remote_name = strtolower((string) (isset($row['name']) ? $row['name'] : ''));
            if ($remote_name !== '' && ($remote_name === $hostname || $remote_name === $name)) {
                $provider_server_id = isset($row['id']) ? (int) $row['id'] : 0;
                if ($provider_server_id > 0 && isset($service['id'])) {
                    $this->Service_model->set_provider_server_id((int) $service['id'], $provider_server_id);
                }
                return $provider_server_id;
            }
        }

        return 0;
    }

    private function remote_action($service_id, $action)
    {
        $user = $this->require_login_json();
        if (!$user) {
            return;
        }

        $service = $this->Service_model->find_for_user((int) $service_id, $user);
        if (!$service) {
            $this->json(array('success' => false, 'message' => 'Service not found'), 404);
            return;
        }

        $provider_server_id = $this->resolve_provider_server_id($service);
        if ($provider_server_id <= 0) {
            $this->json(array('success' => false, 'message' => 'Provider server mapping not found'), 422);
            return;
        }

        $this->load->library('Solusvm_client');
        $result = null;
        if ($action === 'start') {
            $result = $this->solusvm_client->start_server($provider_server_id);
        } elseif ($action === 'stop') {
            $result = $this->solusvm_client->stop_server($provider_server_id);
        } elseif ($action === 'restart') {
            $result = $this->solusvm_client->restart_server($provider_server_id);
        } else {
            $this->json(array('success' => false, 'message' => 'Unsupported action'), 422);
            return;
        }

        if (!$result['ok']) {
            $status = $result['status'] > 0 ? $result['status'] : 502;
            $this->json(array(
                'success' => false,
                'message' => 'SolusVM action failed',
                'error' => $result['error'],
                'provider_response' => $result['data']
            ), $status);
            return;
        }

        $status_map = array('start' => 'running', 'stop' => 'stopped', 'restart' => 'restarting');
        if (isset($status_map[$action])) {
            $this->Service_model->set_status((int) $service_id, $status_map[$action]);
        }

        $updated = $this->Service_model->find_for_user((int) $service_id, $user);
        $this->json(array(
            'success' => true,
            'data' => array(
                'status' => isset($updated['status']) ? $updated['status'] : $status_map[$action],
                'instance_id' => (int) $service_id,
                'provider_server_id' => $provider_server_id,
            ),
            'provider_response' => $result['data']
        ), 200);
    }
}
