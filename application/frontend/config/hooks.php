<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['post_controller_constructor'] = array(
    'class'    => 'WX_Preprocess',
    'function' => 'auto_session',
    'filename' => 'wx_preprocess.php',
    'filepath' => 'hooks',
    'params'   => array()
    );

/* End of file hooks.php */
/* Location: /application/frontend/config/hooks.php */
