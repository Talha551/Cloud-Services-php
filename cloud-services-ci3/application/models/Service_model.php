<?php
/**
 * ============================================================================
 * Service Data Model
 * ============================================================================
 * 
 * PURPOSE: Database operations for VPS services, plans, orders, invoices, domains, tickets
 * STATUS: 100% Complete (basic CRUD working with SQLite)
 * DATABASE: SQLite (local, in-memory or file-based)
 * 
 * TABLES MANAGED:
 * 
 *   1. plans - VPS pricing plans
 *      Columns: id, name, vcpu, memory, disk, bandwidth, price
 *      Sample: "Starter VPS" - 2 vCPU, 2GB RAM, 30GB disk, $9.99/mo
 *   
 *   2. services - Active VPS instances
 *      Columns: id, user_id, plan_id, name, hostname, status, os, location, ip_address, created_at
 *      Statuses: running, stopped, active, restarting, reinstalling
 *   
 *   3. orders - Purchase orders for VPS provisioning
 *      Columns: id, user_id, plan_id, total, status, created_at
 *      Statuses: pending, active, cancelled
 *   
 *   4. invoices - Billing invoices
 *      Columns: id, user_id, total, status, created_at
 *      Statuses: paid, unpaid
 *   
 *   5. domains - Domain registrations/DNS records
 *      Columns: id, user_id, domain, status, created_at
 *      Statuses: active, inactive, expired
 *   
 *   6. tickets - Support tickets
 *      Columns: id, user_id, subject, status, created_at
 *      Statuses: open, in_progress, resolved, closed
 * 
 * KEY METHODS:
 *   - list_plans()                   - Get all available plans
 *   - list_for_user($user)          - Get services for user (admin sees all)
 *   - find_for_user($id, $user)     - Get single service with ACL
 *   - set_status($id, $status)      - Update service status (mock action)
 *   - create_order()                - Create VPS order & service & invoice
 *   - admin_list($table)            - List records from any table
 *   - find_in_table($table, $id)    - Get record by ID
 * 
 * DATA GENERATION:
 *   - Auto-creates sample plans on first run
 *   - Auto-seeds sample services for demo users
 *   - Auto-creates sample invoices, domains, tickets
 *   - All data is MOCK (not connected to real SolusVM)
 * 
 * ARCHITECTURE NOTES:
 * ✓ User isolation: Non-admin users only see their own data
 * ✓ ACL enforcement: Checks user_id before returning data
 * ✓ Schema migrations: Auto-creates tables if missing
 * ✓ Column backfill: Adds missing columns to existing schemas
 * ✓ Generic operations: admin_list/find_in_table for DRY code
 * 
 * MISSING FEATURES vs SolusVM 2.0:
 * ❌ No backups table
 * ❌ No snapshots table
 * ❌ No SSH keys table
 * ❌ No IP management tables
 * ❌ No compute resources/nodes tracking
 * ❌ No resource limits/quotas
 * ❌ No activity logging
 * ❌ No audit trail
 * 
 * DATABASE CHOICE:
 * SQLite was chosen for quick local development/testing.
 * For production:
 *   - Migrate to MySQL/PostgreSQL
 *   - Add proper migrations framework
 *   - Implement connection pooling
 *   - Add query logging/monitoring
 * ============================================================================
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->bootstrap();
    }

    private function bootstrap()
    {
        $this->db->query('CREATE TABLE IF NOT EXISTS plans (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            vcpu INTEGER NOT NULL,
            memory INTEGER NOT NULL,
            disk INTEGER NOT NULL,
            bandwidth INTEGER NOT NULL,
            price REAL NOT NULL
        )');

        $this->db->query('CREATE TABLE IF NOT EXISTS services (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            plan_id INTEGER,
            provider_server_id INTEGER,
            name TEXT NOT NULL,
            hostname TEXT,
            status TEXT NOT NULL DEFAULT "stopped",
            os TEXT,
            location TEXT,
            ip_address TEXT,
            created_at TEXT NOT NULL
        )');

        // Backfill columns for existing SQLite databases created before parity updates.
        $columns = $this->db->query('PRAGMA table_info(services)')->result_array();
        $existing = array();
        foreach ($columns as $column) {
            if (isset($column['name'])) {
                $existing[$column['name']] = true;
            }
        }
        if (!isset($existing['plan_id'])) {
            $this->db->query('ALTER TABLE services ADD COLUMN plan_id INTEGER');
        }
        if (!isset($existing['provider_server_id'])) {
            $this->db->query('ALTER TABLE services ADD COLUMN provider_server_id INTEGER');
        }
        if (!isset($existing['hostname'])) {
            $this->db->query('ALTER TABLE services ADD COLUMN hostname TEXT');
        }
        if (!isset($existing['os'])) {
            $this->db->query('ALTER TABLE services ADD COLUMN os TEXT');
        }
        if (!isset($existing['location'])) {
            $this->db->query('ALTER TABLE services ADD COLUMN location TEXT');
        }
            if (!isset($existing['root_password'])) {
                $this->db->query('ALTER TABLE services ADD COLUMN root_password TEXT');
            }

        $this->db->query('CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            plan_id INTEGER NOT NULL,
            hostname TEXT,
            location TEXT,
            os TEXT,
            invoice_id INTEGER,
            service_id INTEGER,
            provider_server_id INTEGER,
            total REAL NOT NULL,
            status TEXT NOT NULL,
            created_at TEXT NOT NULL,
            updated_at TEXT
        )');

        $order_columns = $this->db->query('PRAGMA table_info(orders)')->result_array();
        $order_existing = array();
        foreach ($order_columns as $column) {
            if (isset($column['name'])) {
                $order_existing[$column['name']] = true;
            }
        }
        if (!isset($order_existing['hostname'])) {
            $this->db->query('ALTER TABLE orders ADD COLUMN hostname TEXT');
        }
        if (!isset($order_existing['location'])) {
            $this->db->query('ALTER TABLE orders ADD COLUMN location TEXT');
        }
        if (!isset($order_existing['os'])) {
            $this->db->query('ALTER TABLE orders ADD COLUMN os TEXT');
        }
        if (!isset($order_existing['invoice_id'])) {
            $this->db->query('ALTER TABLE orders ADD COLUMN invoice_id INTEGER');
        }
        if (!isset($order_existing['service_id'])) {
            $this->db->query('ALTER TABLE orders ADD COLUMN service_id INTEGER');
        }
        if (!isset($order_existing['provider_server_id'])) {
            $this->db->query('ALTER TABLE orders ADD COLUMN provider_server_id INTEGER');
        }
        if (!isset($order_existing['updated_at'])) {
            $this->db->query('ALTER TABLE orders ADD COLUMN updated_at TEXT');
        }

        $this->db->query('CREATE TABLE IF NOT EXISTS invoices (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            order_id INTEGER,
            plan_id INTEGER,
            hostname TEXT,
            location TEXT,
            os TEXT,
            service_id INTEGER,
            total REAL NOT NULL,
            status TEXT NOT NULL,
            provisioning_status TEXT,
            payment_method TEXT,
            transaction_id TEXT,
            paid_at TEXT,
            created_at TEXT NOT NULL
        )');

        $invoice_columns = $this->db->query('PRAGMA table_info(invoices)')->result_array();
        $invoice_existing = array();
        foreach ($invoice_columns as $column) {
            if (isset($column['name'])) {
                $invoice_existing[$column['name']] = true;
            }
        }
        if (!isset($invoice_existing['order_id'])) {
            $this->db->query('ALTER TABLE invoices ADD COLUMN order_id INTEGER');
        }
        if (!isset($invoice_existing['plan_id'])) {
            $this->db->query('ALTER TABLE invoices ADD COLUMN plan_id INTEGER');
        }
        if (!isset($invoice_existing['hostname'])) {
            $this->db->query('ALTER TABLE invoices ADD COLUMN hostname TEXT');
        }
        if (!isset($invoice_existing['location'])) {
            $this->db->query('ALTER TABLE invoices ADD COLUMN location TEXT');
        }
        if (!isset($invoice_existing['os'])) {
            $this->db->query('ALTER TABLE invoices ADD COLUMN os TEXT');
        }
        if (!isset($invoice_existing['service_id'])) {
            $this->db->query('ALTER TABLE invoices ADD COLUMN service_id INTEGER');
        }
        if (!isset($invoice_existing['provisioning_status'])) {
            $this->db->query('ALTER TABLE invoices ADD COLUMN provisioning_status TEXT');
        }
        if (!isset($invoice_existing['payment_method'])) {
            $this->db->query('ALTER TABLE invoices ADD COLUMN payment_method TEXT');
        }
        if (!isset($invoice_existing['transaction_id'])) {
            $this->db->query('ALTER TABLE invoices ADD COLUMN transaction_id TEXT');
        }
        if (!isset($invoice_existing['paid_at'])) {
            $this->db->query('ALTER TABLE invoices ADD COLUMN paid_at TEXT');
        }

        $this->db->query('CREATE TABLE IF NOT EXISTS domains (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            domain TEXT NOT NULL,
            status TEXT NOT NULL,
            created_at TEXT NOT NULL
        )');

        $this->db->query('CREATE TABLE IF NOT EXISTS tickets (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            subject TEXT NOT NULL,
            status TEXT NOT NULL,
            created_at TEXT NOT NULL
        )');

        $this->db->query('CREATE TABLE IF NOT EXISTS audit_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER,
            action TEXT NOT NULL,
            details TEXT,
            ip_address TEXT,
            created_at TEXT NOT NULL
        )');

        $plan_count = (int) $this->db->count_all('plans');
        if ($plan_count === 0) {
            $plans = array(
                array('name' => 'Starter VPS', 'vcpu' => 2, 'memory' => 2048, 'disk' => 30, 'bandwidth' => 1000, 'price' => 9.99),
                array('name' => 'Business VPS', 'vcpu' => 4, 'memory' => 4096, 'disk' => 80, 'bandwidth' => 3000, 'price' => 19.99),
                array('name' => 'Pro VPS', 'vcpu' => 8, 'memory' => 8192, 'disk' => 160, 'bandwidth' => 6000, 'price' => 39.99)
            );
            foreach ($plans as $plan) {
                $this->db->insert('plans', $plan);
            }
        }

        $count = (int) $this->db->count_all('services');
        if ($count === 0) {
            $seed = array(
                array('user_id' => 1, 'plan_id' => 2, 'name' => 'Admin VPS 1', 'hostname' => 'admin-vps-1', 'status' => 'running', 'os' => 'Ubuntu 22.04', 'location' => 'Germany', 'ip_address' => '10.0.0.11', 'created_at' => date('c')),
                array('user_id' => 2, 'plan_id' => 1, 'name' => 'Client VPS 1', 'hostname' => 'client-vps-1', 'status' => 'running', 'os' => 'Debian 12', 'location' => 'Netherlands', 'ip_address' => '10.0.0.21', 'created_at' => date('c')),
                array('user_id' => 2, 'plan_id' => 1, 'name' => 'Client VPS 2', 'hostname' => 'client-vps-2', 'status' => 'stopped', 'os' => 'AlmaLinux 9', 'location' => 'USA', 'ip_address' => '10.0.0.22', 'created_at' => date('c'))
            );
            foreach ($seed as $row) {
                $this->db->insert('services', $row);
            }
        }

        $invoice_count = (int) $this->db->count_all('invoices');
        if ($invoice_count === 0) {
            $this->db->insert('invoices', array('user_id' => 2, 'total' => 19.99, 'status' => 'paid', 'created_at' => date('c')));
            $this->db->insert('invoices', array('user_id' => 2, 'total' => 9.99, 'status' => 'unpaid', 'created_at' => date('c')));
        }

        $domain_count = (int) $this->db->count_all('domains');
        if ($domain_count === 0) {
            $this->db->insert('domains', array('user_id' => 2, 'domain' => 'example-client.com', 'status' => 'active', 'created_at' => date('c')));
        }

        $ticket_count = (int) $this->db->count_all('tickets');
        if ($ticket_count === 0) {
            $this->db->insert('tickets', array('user_id' => 2, 'subject' => 'Need help with reboot', 'status' => 'open', 'created_at' => date('c')));
        }
    }

    public function list_plans()
    {
        return $this->db->order_by('price', 'asc')->get('plans')->result_array();
    }

    public function list_for_user($user)
    {
        $query = 'SELECT s.*, p.vcpu, p.memory, p.disk, p.bandwidth, p.price, p.name as plan_name FROM services s LEFT JOIN plans p ON s.plan_id = p.id';
        if ($user['role'] === 'admin') {
            return $this->db->query($query.' ORDER BY s.id DESC')->result_array();
        }
        return $this->db->query($query.' WHERE s.user_id = ? ORDER BY s.id DESC', array((int) $user['id']))->result_array();
    }

    public function find_for_user($service_id, $user)
    {
        $service_id = (int) $service_id;
        $query = 'SELECT s.*, p.vcpu, p.memory, p.disk, p.bandwidth, p.price, p.name as plan_name FROM services s LEFT JOIN plans p ON s.plan_id = p.id WHERE s.id = ?';
        if ($user['role'] === 'admin') {
            return $this->db->query($query, array($service_id))->row_array();
        }
        return $this->db->query($query.' AND s.user_id = ?', array($service_id, (int) $user['id']))->row_array();
    }

    public function set_status($service_id, $status)
    {
        $this->db->where('id', (int) $service_id)->update('services', array('status' => $status));
    }

    public function create_order($user_id, $plan_id, $hostname, $location, $os, $provider_server_id = NULL)
    {
        $plan = $this->db->get_where('plans', array('id' => (int) $plan_id))->row_array();
        if (!$plan) {
            return NULL;
        }

        $this->db->insert('orders', array(
            'user_id' => (int) $user_id,
            'plan_id' => (int) $plan_id,
            'total' => (float) $plan['price'],
            'status' => 'pending',
            'created_at' => date('c')
        ));

        $this->db->insert('services', array(
            'user_id' => (int) $user_id,
            'plan_id' => (int) $plan_id,
            'provider_server_id' => $provider_server_id !== NULL ? (int) $provider_server_id : NULL,
            'name' => $hostname ? $hostname : ('Service #'.((int) $this->db->insert_id())),
            'hostname' => $hostname,
            'status' => 'active',
            'os' => $os ? $os : 'OS',
            'location' => $location ? $location : 'Location',
            'ip_address' => '192.168.1.'.mt_rand(2, 254),
            'created_at' => date('c')
        ));

        $service_id = (int) $this->db->insert_id();

        $this->db->insert('invoices', array(
            'user_id' => (int) $user_id,
            'total' => (float) $plan['price'],
            'status' => 'unpaid',
            'created_at' => date('c')
        ));

        return $service_id;
    }

    public function create_checkout_order($user_id, $plan_id, $hostname, $location, $os)
    {
        $plan = $this->db->get_where('plans', array('id' => (int) $plan_id))->row_array();
        if (!$plan) {
            return NULL;
        }

        $now = date('c');
        $this->db->insert('orders', array(
            'user_id' => (int) $user_id,
            'plan_id' => (int) $plan_id,
            'hostname' => $hostname,
            'location' => $location,
            'os' => $os,
            'total' => (float) $plan['price'],
            'status' => 'pending',
            'created_at' => $now,
            'updated_at' => $now,
        ));
        $order_id = (int) $this->db->insert_id();

        $this->db->insert('invoices', array(
            'user_id' => (int) $user_id,
            'order_id' => $order_id,
            'plan_id' => (int) $plan_id,
            'hostname' => $hostname,
            'location' => $location,
            'os' => $os,
            'total' => (float) $plan['price'],
            'status' => 'unpaid',
            'provisioning_status' => 'awaiting_payment',
            'created_at' => $now,
        ));
        $invoice_id = (int) $this->db->insert_id();

        $this->db->where('id', $order_id)->update('orders', array('invoice_id' => $invoice_id, 'updated_at' => $now));

        return array('order_id' => $order_id, 'invoice_id' => $invoice_id);
    }

    public function pay_invoice_and_provision($invoice_id, $user, $payment_meta = array())
    {
        $invoice = $this->find_invoice_for_user((int) $invoice_id, $user);
        if (!$invoice) {
            return array('ok' => false, 'message' => 'Invoice not found');
        }

        if (isset($invoice['status']) && strtolower((string) $invoice['status']) === 'paid' && !empty($invoice['service_id'])) {
            return array('ok' => true, 'service_id' => (int) $invoice['service_id'], 'already_paid' => true);
        }

        $plan_id = isset($invoice['plan_id']) ? (int) $invoice['plan_id'] : 0;
        if ($plan_id <= 0 && isset($invoice['order_id'])) {
            $order = $this->db->get_where('orders', array('id' => (int) $invoice['order_id']))->row_array();
            if ($order && isset($order['plan_id'])) {
                $plan_id = (int) $order['plan_id'];
            }
        }
        if ($plan_id <= 0) {
            return array('ok' => false, 'message' => 'Invoice is missing plan details.');
        }

        $plan = $this->db->get_where('plans', array('id' => $plan_id))->row_array();
        if (!$plan) {
            return array('ok' => false, 'message' => 'Selected plan not found.');
        }

        $hostname = isset($invoice['hostname']) ? (string) $invoice['hostname'] : '';
        if ($hostname === '') {
            $hostname = 'service-'.$invoice['id'];
        }
        $location = isset($invoice['location']) ? (string) $invoice['location'] : 'Auto Location';
        $os = isset($invoice['os']) ? (string) $invoice['os'] : 'Auto OS';

        $this->db->trans_begin();
        $this->db->insert('services', array(
            'user_id' => (int) $user['id'],
            'plan_id' => $plan_id,
            'name' => $hostname,
            'hostname' => $hostname,
            'status' => 'active',
            'os' => $os,
            'location' => $location,
            'ip_address' => '192.168.1.'.mt_rand(2, 254),
            'created_at' => date('c'),
        ));
        $service_id = (int) $this->db->insert_id();

        $now = date('c');
        $update_payload = array(
            'status' => 'paid',
            'paid_at' => $now,
            'service_id' => $service_id,
            'provisioning_status' => 'completed',
        );
        if (isset($payment_meta['method'])) {
            $update_payload['payment_method'] = (string) $payment_meta['method'];
        }
        if (isset($payment_meta['transaction_id'])) {
            $update_payload['transaction_id'] = (string) $payment_meta['transaction_id'];
        }

        $this->db->where('id', (int) $invoice['id'])->update('invoices', $update_payload);

        if (!empty($invoice['order_id'])) {
            $this->db->where('id', (int) $invoice['order_id'])->update('orders', array(
                'status' => 'active',
                'service_id' => $service_id,
                'updated_at' => $now,
            ));
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return array('ok' => false, 'message' => 'Payment captured but provisioning failed.');
        }

        $this->db->trans_commit();
        return array(
            'ok' => true,
            'service_id' => $service_id,
            'already_paid' => false,
            'transaction_id' => isset($update_payload['transaction_id']) ? $update_payload['transaction_id'] : NULL,
            'payment_method' => isset($update_payload['payment_method']) ? $update_payload['payment_method'] : NULL,
        );
    }

    public function pay_invoice_demo($invoice_id, $user)
    {
        $transaction_id = 'DEMO-TXN-'.strtoupper(substr(md5(uniqid((string) $invoice_id, true)), 0, 12));
        return $this->pay_invoice_and_provision((int) $invoice_id, $user, array(
            'method' => 'demo_gateway',
            'transaction_id' => $transaction_id,
        ));
    }

    public function set_provider_server_id($service_id, $provider_server_id)
    {
        $this->db->where('id', (int) $service_id)->update('services', array(
            'provider_server_id' => $provider_server_id !== NULL ? (int) $provider_server_id : NULL,
        ));
    }

        public function set_root_password($service_id, $password)
        {
            $this->db->where('id', (int) $service_id)->update('services', array(
                'root_password' => ($password !== NULL && $password !== '') ? (string) $password : NULL,
            ));
        }

    public function list_orders_for_user($user)
    {
        $sql = 'SELECT o.*, p.name as product_name, p.price, i.status as invoice_status FROM orders o LEFT JOIN plans p ON o.plan_id = p.id LEFT JOIN invoices i ON o.invoice_id = i.id';
        if ($user['role'] === 'admin') {
            return $this->db->query($sql.' ORDER BY o.created_at DESC')->result_array();
        }
        return $this->db->query($sql.' WHERE o.user_id = ? ORDER BY o.created_at DESC', array((int) $user['id']))->result_array();
    }

    public function find_order_for_user($id, $user)
    {
        $id = (int) $id;
        if ($user['role'] === 'admin') {
            return $this->db->get_where('orders', array('id' => $id))->row_array();
        }
        return $this->db->get_where('orders', array('id' => $id, 'user_id' => (int) $user['id']))->row_array();
    }

    public function list_invoices_for_user($user)
    {
        if ($user['role'] === 'admin') {
            return $this->db->order_by('created_at', 'desc')->get('invoices')->result_array();
        }
        return $this->db->order_by('created_at', 'desc')->get_where('invoices', array('user_id' => (int) $user['id']))->result_array();
    }

    public function find_invoice_for_user($id, $user)
    {
        $id = (int) $id;
        if ($user['role'] === 'admin') {
            return $this->db->get_where('invoices', array('id' => $id))->row_array();
        }
        return $this->db->get_where('invoices', array('id' => $id, 'user_id' => (int) $user['id']))->row_array();
    }

    public function admin_list($table)
    {
        $allowed = array('orders', 'invoices', 'domains', 'tickets', 'audit_logs');
        if (!in_array($table, $allowed, true)) {
            return array();
        }

        return $this->db->order_by('id', 'desc')->get($table)->result_array();
    }

    public function find_in_table($table, $id)
    {
        $allowed = array('orders', 'invoices', 'domains', 'tickets');
        if (!in_array($table, $allowed, true)) {
            return NULL;
        }

        return $this->db->get_where($table, array('id' => (int) $id))->row_array();
    }

    public function list_table_for_user($table, $user)
    {
        $allowed = array('orders', 'invoices', 'domains', 'tickets');
        if (!in_array($table, $allowed, true)) {
            return array();
        }

        if ($table === 'orders') {
            return $this->list_orders_for_user($user);
        }

        if ($table === 'invoices') {
            return $this->list_invoices_for_user($user);
        }

        if ($user['role'] === 'admin') {
            return $this->db->order_by('id', 'desc')->get($table)->result_array();
        }

        return $this->db->where('user_id', (int) $user['id'])->order_by('id', 'desc')->get($table)->result_array();
    }

    public function create_ticket($user_id, $subject)
    {
        $subject = trim((string) $subject);
        if ($subject === '') {
            return NULL;
        }

        $this->db->insert('tickets', array(
            'user_id' => (int) $user_id,
            'subject' => $subject,
            'status' => 'open',
            'created_at' => date('c')
        ));

        return (int) $this->db->insert_id();
    }

    public function add_audit_log($user_id, $action, $details = array(), $ip_address = '')
    {
        $payload = NULL;
        if (is_array($details)) {
            $payload = json_encode($details);
        } elseif ($details !== NULL && $details !== '') {
            $payload = (string) $details;
        }

        $this->db->insert('audit_logs', array(
            'user_id' => $user_id !== NULL ? (int) $user_id : NULL,
            'action' => (string) $action,
            'details' => $payload,
            'ip_address' => (string) $ip_address,
            'created_at' => date('c')
        ));
    }

    public function run_billing_automation($cycle_days = 30, $grace_days = 3)
    {
        $cycle_days = max(1, (int) $cycle_days);
        $grace_days = max(0, (int) $grace_days);
        $now = time();

        $generated = 0;
        $suspended = 0;

        $services = $this->db
            ->where_in('status', array('active', 'running'))
            ->get('services')
            ->result_array();

        foreach ($services as $service) {
            $service_id = (int) (isset($service['id']) ? $service['id'] : 0);
            if ($service_id <= 0) {
                continue;
            }

            $has_unpaid = (bool) $this->db
                ->where('service_id', $service_id)
                ->where('status', 'unpaid')
                ->count_all_results('invoices');
            if ($has_unpaid) {
                continue;
            }

            $last_paid = $this->db
                ->where('service_id', $service_id)
                ->where('status', 'paid')
                ->order_by('id', 'desc')
                ->limit(1)
                ->get('invoices')
                ->row_array();

            $anchor = isset($service['created_at']) ? strtotime((string) $service['created_at']) : false;
            if ($last_paid) {
                if (!empty($last_paid['paid_at'])) {
                    $anchor = strtotime((string) $last_paid['paid_at']);
                } elseif (!empty($last_paid['created_at'])) {
                    $anchor = strtotime((string) $last_paid['created_at']);
                }
            }
            if ($anchor === false) {
                $anchor = $now;
            }

            $due_at = strtotime('+'.$cycle_days.' days', $anchor);
            if ($due_at !== false && $due_at > $now) {
                continue;
            }

            $plan_id = isset($service['plan_id']) ? (int) $service['plan_id'] : 0;
            $plan = $plan_id > 0 ? $this->db->get_where('plans', array('id' => $plan_id))->row_array() : NULL;
            $total = $plan ? (float) $plan['price'] : 0.00;

            $this->db->insert('invoices', array(
                'user_id' => (int) $service['user_id'],
                'plan_id' => $plan_id > 0 ? $plan_id : NULL,
                'hostname' => isset($service['hostname']) ? $service['hostname'] : '',
                'location' => isset($service['location']) ? $service['location'] : '',
                'os' => isset($service['os']) ? $service['os'] : '',
                'service_id' => $service_id,
                'total' => $total,
                'status' => 'unpaid',
                'provisioning_status' => 'renewal_pending',
                'created_at' => date('c')
            ));
            $generated++;
        }

        $unpaid = $this->db
            ->where('status', 'unpaid')
            ->where('service_id IS NOT NULL', NULL, false)
            ->get('invoices')
            ->result_array();

        foreach ($unpaid as $invoice) {
            $created_ts = !empty($invoice['created_at']) ? strtotime((string) $invoice['created_at']) : false;
            if ($created_ts === false) {
                continue;
            }
            $overdue_after = strtotime('+'.$grace_days.' days', $created_ts);
            if ($overdue_after === false || $overdue_after > $now) {
                continue;
            }

            $service_id = (int) (isset($invoice['service_id']) ? $invoice['service_id'] : 0);
            if ($service_id <= 0) {
                continue;
            }

            $service = $this->db->get_where('services', array('id' => $service_id))->row_array();
            if (!$service) {
                continue;
            }

            $status = strtolower((string) (isset($service['status']) ? $service['status'] : ''));
            if ($status !== 'suspended') {
                $this->db->where('id', $service_id)->update('services', array('status' => 'suspended'));
                $suspended++;
            }

            $this->db->where('id', (int) $invoice['id'])->update('invoices', array('provisioning_status' => 'suspended'));
        }

        return array(
            'generated_invoices' => $generated,
            'suspended_services' => $suspended,
            'cycle_days' => $cycle_days,
            'grace_days' => $grace_days,
            'run_at' => date('c')
        );
    }
}
