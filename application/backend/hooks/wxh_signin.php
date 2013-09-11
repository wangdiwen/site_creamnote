<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXH_Signin
{
    var $CI;  // Get the CI super object
/*****************************************************************************/
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->helper('url');
    }
/*****************************************************************************/
    public function check_signin()
    {
        if (isset($_SESSION['admin_user_email']) && $_SESSION['admin_user_email']) {
            return;
        }

        $cur_url = current_url();
        $admin_base_url = base_url().'cnadmin/';

        $login_page = $admin_base_url;
        $check_login = $admin_base_url.'home/check_login';
        $login_index = $admin_base_url.'home/login';
        $login_url = $admin_base_url.'home/login_page';
        $check_url = $admin_base_url.'check/(.*)';

        $test = $admin_base_url.'home/test';

        if ($cur_url == $admin_base_url
            || $cur_url == $login_page
            || $cur_url == $check_login
            || $cur_url == $login_index
            || $cur_url == $login_url
            || $cur_url == $test
            || ereg($check_url, $cur_url)) {
            return;
        }
        else {
            // wx_loginfo('hooks');
            redirect('cnadmin/home/login_page');
        }
    }
/*****************************************************************************/
}

/* End of wxh_signin.php */
/* Location: /application/backend/hooks/wxh_signin.php */
