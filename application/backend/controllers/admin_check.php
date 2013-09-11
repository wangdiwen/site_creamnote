<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Check extends CI_Controller
{
/*****************************************************************************/
    public function __construct()
    {
        parent::__construct();
        $this->load->model('wxm_admin_user');

        $this->load->library('wx_util');
    }
/*****************************************************************************/

/*****************************************************************************/
    public function check_admin_user()
    {
        $user_email = $this->input->post('admin_email');
        // $user_email = 'wangdiwen@creamnote.com';

        if ($user_email) {
            $has_user = $this->wxm_admin_user->has_admin_user($user_email);
            if ($has_user) {
                echo 'true';
                return;
            }
        }
        echo 'false';
    }
/*****************************************************************************/

/*****************************************************************************/
    public function check_admin_auth_code($auth_code = '')
    {
        $ret = $this->wx_util->check_auth_code($auth_code);
        if ($ret)
            return true;
        else
            return false;
    }
/*****************************************************************************/
    public function test()
    {
        echo 'admin_check -> test function';
    }
/*****************************************************************************/
}

/* End of file admin_check.php */
/* Location: /application/backend/controllers/admin_check.php */
