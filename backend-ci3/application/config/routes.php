<?php
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
$route['default_controller'] = 'health';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['health'] = 'health/index';

$route['api/auth/login'] = 'auth/login';
$route['api/auth/register'] = 'auth/register';

$route['api/automation/v1/hooks/solusvm'] = 'hooks/solusvm';

$route['api/automation/v1/client/plans'] = 'client/plans';
$route['api/automation/v1/client/profile'] = 'client/profile';
$route['api/automation/v1/client/services'] = 'client/services';
$route['api/automation/v1/client/services/(:num)'] = 'client/services/$1';
$route['api/automation/v1/client/services/(:num)/(:any)'] = 'client/services/$1/$2';
$route['api/automation/v1/client/orders'] = 'client/orders';
$route['api/automation/v1/client/orders/(:num)'] = 'client/orders/$1';
$route['api/automation/v1/client/invoices'] = 'client/invoices';

$route['api/automation/v1/clients'] = 'admin/clients';
$route['api/automation/v1/clients/(:num)'] = 'admin/clients/$1';
$route['api/automation/v1/invoices'] = 'admin/invoices';
$route['api/automation/v1/invoices/(:num)'] = 'admin/invoices/$1';
$route['api/automation/v1/orders'] = 'admin/orders';
$route['api/automation/v1/orders/(:num)'] = 'admin/orders/$1';
$route['api/automation/v1/domains'] = 'admin/domains';
$route['api/automation/v1/domains/(:num)'] = 'admin/domains/$1';
$route['api/automation/v1/support/tickets'] = 'admin/support_tickets';
$route['api/automation/v1/support/tickets/(:num)'] = 'admin/support_tickets/$1';

$route['api/v1/(:any)/(:any)/(:any)'] = 'mockapi/$1/$2/$3';
$route['api/v1/(:any)/(:any)'] = 'mockapi/$1/$2';
$route['api/v1/(:any)'] = 'mockapi/$1';
