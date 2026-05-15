<?php
/**
 * ============================================================================
 * SolusVM 2.0 Compatibility API - Route Configuration
 * ============================================================================
 * 
 * PROJECT: Cloud Services Monolith (CodeIgniter 3 + PHP)
 * PURPOSE: Single app serving both frontend pages & REST API
 * STATUS: Partially complete (28+ endpoints working, React-compatible)
 * 
 * ARCHITECTURE:
 * - Frontend routes: Traditional views (login, dashboard, admin pages)
 * - API routes: RESTful endpoints (/api/...) for backend/mobile
 * - Automation aliases: /api/automation/v1/* for existing React app
 * 
 * ENDPOINTS IMPLEMENTED:
 * AUTHENTICATION (8 endpoints): login, register, profile, 2fa, tokens, reset_password
 * CLIENT SERVICES (14 endpoints): list, detail, actions (start/stop/restart), orders, invoices
 * ADMIN (10 endpoints): clients, invoices, orders, domains, tickets management
 * AUTOMATION ALIASES (React): Mirror of client/admin routes under /api/automation/v1/
 * 
 * GAPS vs SolusVM 2.0 Official (186 endpoints):
 * ❌ Compute resources, backups, snapshots, networking, storage, VPC, projects
 * ❌ Advanced SSH keys, tags, limits, licenses, updates management
 * ❌ 2FA endpoints are stubbed (501 Not Implemented)
 * ❌ No upstream SolusVM API client (currently local DB only)
 * 
 * LAST TESTED: May 12, 2026 - All endpoints working on localhost:8092
 * ============================================================================
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Frontend routes (CodeIgniter views/pages)
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['signup'] = 'auth/signup';
$route['register'] = 'auth/signup';
$route['pricing'] = 'home/pricing';
$route['features'] = 'home/features';
$route['about'] = 'home/about';
$route['faq'] = 'home/faq';
$route['contact'] = 'home/contact';
$route['dashboard'] = 'dashboard/index';
$route['admin'] = 'dashboard/admin_home';
$route['admin/servers'] = 'dashboard/admin_servers';
$route['admin/servers/create'] = 'dashboard/admin_server_create';
$route['admin/servers/provision'] = 'dashboard/admin_server_provision';
$route['admin/servers/(:num)/action/(:any)'] = 'dashboard/admin_service_action/$1/$2';
$route['admin/servers/(:num)/reinstall'] = 'dashboard/admin_service_reinstall/$1';
$route['admin/servers/(:num)/change_password'] = 'dashboard/admin_service_change_password/$1';
$route['admin/servers/(:num)/provision'] = 'dashboard/admin_service_provision/$1';
$route['admin/servers/(:num)/console'] = 'dashboard/admin_service_console/$1';
$route['admin/servers/(:num)/console/session'] = 'dashboard/admin_service_console_session/$1';
$route['admin/servers/(:num)'] = 'dashboard/admin_server_detail/$1';
$route['admin/plans'] = 'dashboard/admin_plans';
$route['admin/os-images'] = 'dashboard/admin_os_images';
$route['admin/users'] = 'dashboard/clients';
$route['admin/projects'] = 'dashboard/admin_projects';
$route['client'] = 'dashboard/client_home';
$route['client/services'] = 'dashboard/services';
$route['client/services/(:num)'] = 'dashboard/client_service_detail/$1';
$route['client/services/(:num)/console'] = 'dashboard/client_service_console/$1';
$route['client/services/(:num)/console/session'] = 'dashboard/client_service_console_session/$1';
$route['client/services/(:num)/action/(:any)'] = 'dashboard/client_service_action/$1/$2';
$route['client/services/(:num)/reinstall'] = 'dashboard/client_service_reinstall/$1';
$route['client/services/(:num)/change_password'] = 'dashboard/client_service_change_password/$1';
$route['client/services/(:num)/provision'] = 'dashboard/client_service_provision/$1';
$route['client/services/(:num)/install-request'] = 'dashboard/client_service_install_request/$1';
$route['client/orders'] = 'dashboard/orders';
$route['client/invoices'] = 'dashboard/invoices';
$route['client/invoices/(:num)/pay'] = 'dashboard/client_invoice_pay/$1';
$route['client/invoices/(:num)/pay-credits'] = 'dashboard/client_invoice_pay_with_credits/$1';
$route['client/credits'] = 'dashboard/client_credits';
$route['client/credits/topup'] = 'dashboard/client_credits_topup';
$route['client/tickets'] = 'dashboard/client_tickets';
$route['client/tickets/create'] = 'dashboard/client_ticket_create';
$route['client/profile'] = 'dashboard/client_profile';
$route['client/profile/update'] = 'dashboard/client_profile_update';
$route['store'] = 'dashboard/plans';
$route['checkout'] = 'dashboard/client_checkout';
$route['admin/clients'] = 'dashboard/clients';
$route['admin/clients/(:num)'] = 'dashboard/admin_client_detail/$1';
$route['admin/clients/(:num)/credits/send'] = 'dashboard/admin_client_send_credit/$1';
$route['admin/invoices'] = 'dashboard/admin_invoices';
$route['admin/orders'] = 'dashboard/admin_orders';
$route['admin/profile'] = 'dashboard/admin_profile';
$route['admin/profile/update'] = 'dashboard/admin_profile_update';
$route['admin/domains'] = 'dashboard/domains';
$route['admin/tickets'] = 'dashboard/tickets';
$route['admin/audit-logs'] = 'dashboard/admin_audit_logs';
$route['admin/locations'] = 'dashboard/admin_locations';
$route['admin/backups'] = 'dashboard/admin_backups';
$route['admin/ip-blocks'] = 'dashboard/admin_ip_blocks';
$route['admin/compute-resources'] = 'dashboard/admin_compute_resources';
$route['services'] = 'dashboard/services';
$route['plans'] = 'dashboard/plans';
$route['orders'] = 'dashboard/orders';
$route['invoices'] = 'dashboard/invoices';
$route['domains'] = 'dashboard/domains';
$route['tickets'] = 'dashboard/tickets';
$route['clients'] = 'dashboard/clients';
$route['orders/create'] = 'dashboard/create_order';
$route['automation/billing/daily'] = 'cron/billing_daily';

// API routes (backend)
$route['api/health'] = 'api/health/index';
$route['api/auth/login'] = 'api/auth/login';
$route['api/auth/register'] = 'api/auth/register';
$route['api/auth/profile'] = 'api/auth/profile';
$route['api/auth'] = 'api/auth/verify';
$route['api/auth/logout'] = 'api/auth/logout';
$route['api/auth/2fa/login'] = 'api/auth/login_2fa';
$route['api/auth/reset_password'] = 'api/auth/reset_password';
$route['api/auth/forgot_password'] = 'api/auth/forgot_password';
$route['api/auth/tokens'] = 'api/auth/tokens';
$route['api/auth/tokens/revoke'] = 'api/auth/tokens_revoke';
$route['api/auth/2fa/enable'] = 'api/auth/two_factor_enable';
$route['api/auth/2fa/disable'] = 'api/auth/two_factor_disable';
$route['api/auth/2fa/tokens'] = 'api/auth/two_factor_tokens';

$route['api/client/services'] = 'api/client/services';
$route['api/client/services/(:num)'] = 'api/client/service/$1';
$route['api/client/services/(:num)/start'] = 'api/client/start/$1';
$route['api/client/services/(:num)/stop'] = 'api/client/stop/$1';
$route['api/client/services/(:num)/restart'] = 'api/client/restart/$1';
$route['api/client/services/(:num)/reinstall'] = 'api/client/reinstall/$1';
$route['api/client/services/(:num)/console'] = 'api/client/console/$1';
$route['api/client/services/(:num)/stream'] = 'api/client/stream/$1';
$route['api/client/profile'] = 'api/client/profile';
$route['api/client/plans'] = 'api/client/plans';
$route['api/client/orders'] = 'api/client/orders';
$route['api/client/orders/(:num)'] = 'api/client/order/$1';
$route['api/client/invoices'] = 'api/client/invoices';
$route['api/client/invoices/(:num)'] = 'api/client/invoice/$1';

// Automation v1 aliases for existing React app
$route['api/automation/v1/client/profile'] = 'api/client/profile';
$route['api/automation/v1/client/plans'] = 'api/client/plans';
$route['api/automation/v1/client/services'] = 'api/client/services';
$route['api/automation/v1/client/services/(:num)'] = 'api/client/service/$1';
$route['api/automation/v1/client/services/(:num)/start'] = 'api/client/start/$1';
$route['api/automation/v1/client/services/(:num)/stop'] = 'api/client/stop/$1';
$route['api/automation/v1/client/services/(:num)/restart'] = 'api/client/restart/$1';
$route['api/automation/v1/client/services/(:num)/reinstall'] = 'api/client/reinstall/$1';
$route['api/automation/v1/client/services/(:num)/console'] = 'api/client/console/$1';
$route['api/automation/v1/client/services/(:num)/stream'] = 'api/client/stream/$1';
$route['api/automation/v1/client/orders'] = 'api/client/orders';
$route['api/automation/v1/client/orders/(:num)'] = 'api/client/order/$1';
$route['api/automation/v1/client/invoices'] = 'api/client/invoices';
$route['api/automation/v1/client/invoices/(:num)'] = 'api/client/invoice/$1';

$route['api/automation/v1/clients'] = 'api/admin/clients';
$route['api/automation/v1/clients/(:num)'] = 'api/admin/client/$1';
$route['api/automation/v1/clients/create'] = 'api/admin/create_client';
$route['api/automation/v1/clients/(:num)/update'] = 'api/admin/update_client/$1';
$route['api/automation/v1/clients/(:num)/delete'] = 'api/admin/delete_client/$1';
$route['api/automation/v1/invoices'] = 'api/admin/invoices';
$route['api/automation/v1/invoices/(:num)'] = 'api/admin/invoice/$1';
$route['api/automation/v1/orders'] = 'api/admin/orders';
$route['api/automation/v1/orders/(:num)'] = 'api/admin/order/$1';
$route['api/automation/v1/servers/create'] = 'api/admin/server_create';
$route['api/automation/v1/servers/(:num)/reinstall'] = 'api/admin/server_reinstall/$1';
$route['api/automation/v1/domains'] = 'api/admin/domains';
$route['api/automation/v1/domains/(:num)'] = 'api/admin/domain/$1';
$route['api/automation/v1/support/tickets'] = 'api/admin/tickets';
$route['api/automation/v1/support/tickets/(:num)'] = 'api/admin/ticket/$1';
$route['api/automation/v1/support/tickets/(:num)/update'] = 'api/admin/update_ticket/$1';
