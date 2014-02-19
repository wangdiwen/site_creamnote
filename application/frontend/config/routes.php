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

$route['default_controller'] = 'primary/wxc_home/index';
$route['404_override'] = 'primary/wxc_home/page_404';

$route['home/personal'] = 'primary/wxc_personal/personal';
$route['home/(:any)'] = 'primary/wxc_home/$1';
$route['user_active'] = 'primary/wxc_home/check_active';
$route['core/wxc_(alioss|alipay|content|data_statistic|download_note|user_manager|util|zhifubao_login|user_account){1,1}/(:any)'] = 'core/wxc_$1/$2';
$route['data/wxc_(data|image){1,1}/(:any)'] = 'data/wxc_$1/$2';
$route['primary/wxc_(feedback|home|message|personal|search){1,1}/(:any)'] = 'primary/wxc_$1/$2';
$route['static/wxc_(about|help|direct|cooperation){1,1}/(:any)'] = 'static/wxc_$1/$2';
$route['experiment/wxc_(home){1,1}/(:any)'] = 'experiment/wxc_$1/$2';
$route['openapi/(:any)'] = 'api/$1';
$route['^(:any)$'] = 'primary/wxc_home/page_404';


/* End of file routes.php */
/* Location: ./application/config/routes.php */
