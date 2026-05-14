<?php
/**
 * ============================================================================
 * API Admin Management Controller
 * ============================================================================
 * 
 * PURPOSE: Administrative endpoints for system management (clients, orders, billing, domains, tickets)
 * STATUS: 70% Complete (list/detail operations working, advanced actions missing)
 * AUTHENTICATION: Required - Bearer token or session, role='admin' enforced
 * AUTHORIZATION: Admin role check on all methods
 * 
 * IMPLEMENTED ENDPOINTS (10 routes):
 *   GET  /api/automation/v1/clients            - List all client users
 *   GET  /api/automation/v1/clients/:id        - Single client details + stats
 *   GET  /api/automation/v1/invoices           - List all invoices (billing)
 *   GET  /api/automation/v1/invoices/:id       - Single invoice details
 *   GET  /api/automation/v1/orders             - List all orders (VPS purchases)
 *   GET  /api/automation/v1/orders/:id         - Single order details
 *   GET  /api/automation/v1/domains            - List all domains
 *   GET  /api/automation/v1/domains/:id        - Single domain details
 *   GET  /api/automation/v1/support/tickets    - Support tickets list
 *   GET  /api/automation/v1/support/tickets/:id - Single ticket details
 * 
 * ARCHITECTURE:
 *   - All routes prefixed with /api/automation/v1/ for React app compatibility
 *   - Uses generic admin_list() and admin_item() methods for DRY code
 *   - Database-driven (no upstream SolusVM connection)
 * 
 * FEATURES:
 *   - Admin-only access check on every endpoint
 *   - Metadata pagination (current_page, last_page, total)
 *   - 404 error handling for missing records
 *   - Proper HTTP status codes (403 for auth failures)
 * 
 * MISSING ADMIN FEATURES (vs SolusVM 2.0):
 * ❌ Create/update/delete operations (read-only currently)
 * ❌ Client account suspension/locking
 * ❌ Bulk actions (mass delete, status updates)
 * ❌ Client resource limits management
 * ❌ Support ticket status/reply management
 * ❌ Financial reconciliation/reporting
 * ❌ System settings/configuration endpoints
 * ❌ User role/permission management
 * ❌ Activity logging/audit trails
 * 
 * INTEGRATION:
 *   - User_model: User lookups, access control
 *   - Service_model: Generic table access layer
 *   - MY_Controller: Auth, admin role validation
 * 
 * NOTES:
 *   Routes are mapped under /api/automation/v1/ namespace to support
 *   existing React frontend that expects this endpoint structure.
 * ============================================================================
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller
{
    public function clients()
    {
        $user = $this->require_login_json();
        if (!$user) return;
        if (!$this->is_admin($user)) {
            $this->json(array('message' => 'Admin access required'), 403);
            return;
        }

        $items = $this->User_model->list_clients();
        $this->json(array('data' => $items, 'meta' => array('total' => count($items), 'current_page' => 1, 'last_page' => 1)), 200);
    }

    public function client($id)
    {
        $user = $this->require_login_json();
        if (!$user) return;
        if (!$this->is_admin($user)) {
            $this->json(array('message' => 'Admin access required'), 403);
            return;
        }

        $item = $this->User_model->find_by_id((int) $id);
        if (!$item) {
            $this->json(array('message' => 'Client not found'), 404);
            return;
        }
        $this->json(array('data' => $this->User_model->shape_user($item)), 200);
    }

    public function invoices()
    {
        $this->admin_list('invoices');
    }

    public function invoice($id)
    {
        $this->admin_item('invoices', $id, 'Invoice not found');
    }

    public function orders()
    {
        $this->admin_list('orders');
    }

    public function order($id)
    {
        $this->admin_item('orders', $id, 'Order not found');
    }

    public function domains()
    {
        $this->admin_list('domains');
    }

    public function domain($id)
    {
        $this->admin_item('domains', $id, 'Domain not found');
    }

    public function tickets()
    {
        $this->admin_list('tickets');
    }

    public function ticket($id)
    {
        $this->admin_item('tickets', $id, 'Ticket not found');
    }

    private function admin_list($table)
    {
        $user = $this->require_login_json();
        if (!$user) return;
        if (!$this->is_admin($user)) {
            $this->json(array('message' => 'Admin access required'), 403);
            return;
        }

        $items = $this->Service_model->admin_list($table);
        $this->json(array('data' => $items, 'meta' => array('total' => count($items), 'current_page' => 1, 'last_page' => 1)), 200);
    }

    private function admin_item($table, $id, $not_found_message)
    {
        $user = $this->require_login_json();
        if (!$user) return;
        if (!$this->is_admin($user)) {
            $this->json(array('message' => 'Admin access required'), 403);
            return;
        }

        $item = $this->Service_model->find_in_table($table, (int) $id);
        if (!$item) {
            $this->json(array('message' => $not_found_message), 404);
            return;
        }

        $this->json(array('data' => $item), 200);
    }

    /**
     * CREATE operations for admin management
     */
    public function create_client()
    {
        $user = $this->require_login_json();
        if (!$user) return;
        if (!$this->is_admin($user)) {
            $this->json(array('message' => 'Admin access required'), 403);
            return;
        }

        $full_name = trim((string) $this->input_data('full_name', ''));
        $email = trim((string) $this->input_data('email', ''));
        $password = (string) $this->input_data('password', '');
        $role = (string) $this->input_data('role', 'client');

        if ($full_name === '' || $email === '' || strlen($password) < 6) {
            $this->json(array('success' => false, 'message' => 'Invalid input'), 422);
            return;
        }

        if ($this->User_model->find_by_email($email)) {
            $this->json(array('success' => false, 'message' => 'Email already exists'), 409);
            return;
        }

        $new_user = $this->User_model->create(array(
            'full_name' => $full_name,
            'email' => $email,
            'password' => $password,
            'role' => $role
        ));

        $this->json(array('data' => $this->User_model->shape_user($new_user)), 201);
    }

    /**
     * UPDATE operations for admin management
     */
    public function update_client($id)
    {
        $user = $this->require_login_json();
        if (!$user) return;
        if (!$this->is_admin($user)) {
            $this->json(array('message' => 'Admin access required'), 403);
            return;
        }

        $target_user = $this->User_model->find_by_id((int) $id);
        if (!$target_user) {
            $this->json(array('message' => 'Client not found'), 404);
            return;
        }

        $update_data = array();
        
        if ($this->input_data('full_name')) {
            $update_data['full_name'] = $this->input_data('full_name');
        }
        
        if ($this->input_data('email')) {
            $new_email = $this->input_data('email');
            if ($new_email !== $target_user['email']) {
                if ($this->User_model->find_by_email($new_email)) {
                    $this->json(array('success' => false, 'message' => 'Email already exists'), 409);
                    return;
                }
            }
            $update_data['email'] = $new_email;
        }

        if ($this->input_data('role')) {
            $update_data['role'] = $this->input_data('role');
        }

        if (!empty($update_data)) {
            $this->db->where('id', (int) $id)->update('users', $update_data);
        }

        $updated_user = $this->User_model->find_by_id((int) $id);
        $this->json(array('data' => $this->User_model->shape_user($updated_user)), 200);
    }

    /**
     * DELETE operations for admin management
     */
    public function delete_client($id)
    {
        $user = $this->require_login_json();
        if (!$user) return;
        if (!$this->is_admin($user)) {
            $this->json(array('message' => 'Admin access required'), 403);
            return;
        }

        if ($user['id'] === (int) $id) {
            $this->json(array('success' => false, 'message' => 'Cannot delete yourself'), 422);
            return;
        }

        $target_user = $this->User_model->find_by_id((int) $id);
        if (!$target_user) {
            $this->json(array('message' => 'Client not found'), 404);
            return;
        }

        $this->User_model->delete_user((int) $id);
        $this->json(array('success' => true, 'message' => 'Client deleted'), 200);
    }

    /**
     * Update ticket status
     */
    public function update_ticket($id)
    {
        $user = $this->require_login_json();
        if (!$user) return;
        if (!$this->is_admin($user)) {
            $this->json(array('message' => 'Admin access required'), 403);
            return;
        }

        $ticket = $this->Service_model->find_in_table('tickets', (int) $id);
        if (!$ticket) {
            $this->json(array('message' => 'Ticket not found'), 404);
            return;
        }

        $status = (string) $this->input_data('status', '');
        $allowed_statuses = array('open', 'in_progress', 'resolved', 'closed');

        if ($status === '' || !in_array($status, $allowed_statuses)) {
            $this->json(array('success' => false, 'message' => 'Invalid status'), 422);
            return;
        }

        $this->db->where('id', (int) $id)->update('tickets', array('status' => $status));
        
        $updated_ticket = $this->Service_model->find_in_table('tickets', (int) $id);
        $this->json(array('data' => $updated_ticket), 200);
    }

    public function server_create()
    {
        $user = $this->require_login_json();
        if (!$user) return;
        if (!$this->is_admin($user)) {
            $this->json(array('message' => 'Admin access required'), 403);
            return;
        }

        $hostname = trim((string) $this->input_data('name', ''));
        $plan_id = (int) $this->input_data('plan', 0);
        $location_id = (int) $this->input_data('location', 0);
        $os_id = (int) $this->input_data('os', 0);
        $local_plan_id = (int) $this->input_data('local_plan_id', 1);

        if ($hostname === '' || $plan_id <= 0 || $location_id <= 0 || $os_id <= 0) {
            $this->json(array(
                'success' => false,
                'message' => 'Missing required fields: name, plan, location, os'
            ), 422);
            return;
        }

        $this->load->library('Solusvm_client');
        $result = $this->solusvm_client->create_server(array(
            'name' => $hostname,
            'plan' => $plan_id,
            'location' => $location_id,
            'os' => $os_id,
        ));

        if (!$result['ok']) {
            $status = $result['status'] > 0 ? $result['status'] : 502;
            $this->json(array(
                'success' => false,
                'message' => 'SolusVM create server failed',
                'error' => $result['error'],
                'provider_response' => $result['data']
            ), $status);
            return;
        }

        $local_service_id = $this->Service_model->create_order(
            (int) $user['id'],
            $local_plan_id,
            $hostname,
            'Location #'.$location_id,
            'OS #'.$os_id,
            isset($result['data']['data']['id']) ? (int) $result['data']['data']['id'] : NULL
        );

        $this->json(array(
            'success' => true,
            'message' => 'Server creation request accepted by SolusVM',
            'local_service_id' => (int) $local_service_id,
            'provider_response' => $result['data']
        ), 201);
    }

    public function server_reinstall($id)
    {
        $user = $this->require_login_json();
        if (!$user) return;
        if (!$this->is_admin($user)) {
            $this->json(array('message' => 'Admin access required'), 403);
            return;
        }

        $server_id = (int) $id;
        $os_id = (int) $this->input_data('os', 0);
        if ($server_id <= 0 || $os_id <= 0) {
            $this->json(array('success' => false, 'message' => 'Missing required fields: server id and os'), 422);
            return;
        }

        $this->load->library('Solusvm_client');
        $result = $this->solusvm_client->reinstall_server($server_id, array('os' => $os_id));
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

        $this->json(array(
            'success' => true,
            'message' => 'OS installation initiated',
            'provider_response' => $result['data']
        ), 200);
    }
}
