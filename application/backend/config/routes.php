<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home/login_page";
$route['404_override'] = 'share/page_404';

$route['cnadmin'] = 'home/login_page';
$route['cnadmin/home/(:any)'] = 'home/$1';
$route['cnadmin/check/(:any)'] = 'admin_check/$1';

$route['cnadmin/user/(:any)'] = 'user_manager/$1';
$route['cnadmin/audit/(:any)'] = 'audit/$1';
$route['cnadmin/article/(:any)'] = 'article/$1';
$route['cnadmin/content/(:any)'] = 'content/$1';
$route['cnadmin/general/(:any)'] = 'general/$1';
$route['cnadmin/withdraw/(:any)'] = 'withdraw/$1';
$route['cnadmin/feedback/(:any)'] = 'feedback/$1';
$route['cnadmin/report/(:any)'] = 'report/$1';
$route['cnadmin/storage/(:any)'] = 'storage/$1';
$route['cnadmin/database/(:any)'] = 'database/$1';
$route['cnadmin/alipay/(:any)'] = 'alipay/$1';
$route['cnadmin/share/(:any)'] = 'share/$1';
$route['cnadmin/offical/(:any)'] = 'offical_email/$1';
$route['cnadmin/(:any)'] = 'share/page_404';


/* End of file routes.php */
/* Location: /application/backend/config/routes.php */
