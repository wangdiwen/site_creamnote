<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{
/*****************************************************************************/
    public function __construct()
    {
        parent::__construct();
        $this->load->model('wxm_admin_user');

        $this->load->library('wx_util');
    }
/*****************************************************************************/
    public function check_login()
    {
        $user_email = $this->input->post('admin_email');
        $user_password = $this->input->post('admin_password');
        $auth_code = $this->input->post('admin_auth_code');
        // $user_email = 'wangdiwen@creamnote.com';
        // $user_password = 'wangdiwen123!@#@creamnote';

        $auth_ret = $this->wx_util->check_auth_code($auth_code);
        if (! $auth_ret) {
            echo 'login-authcode-wrong';
            return;
        }

        if ($user_email && $user_password) {
            $auth = $this->wxm_admin_user->check_admin_user($user_email, $user_password);
            if ($auth == '0') {
                echo 'login-success';
                return;
            }
            elseif ($auth == '1') {
                echo 'login-no-user';
                return;
            }
            elseif ($auth == '2') {
                echo 'login-password-wrong';
                return;
            }
            elseif ($auth == '3') {
                echo 'login-no-permission';
                return;
            }
        }
        echo 'login-failed';
    }
/*****************************************************************************/
    public function login_page()
    {
        // auth code
        $data = array();
        $auth_code = $this->wx_util->get_auth_code();
        $data['admin_auth_code'] = $auth_code; // auth code string

        $this->load->view('wxv_login', $data);
    }
/*****************************************************************************/
	public function login()
	{
        $user_email = $this->input->post('admin_email');
        $user_password = $this->input->post('admin_password');
        $auth_code = $this->input->post('admin_auth_code');

        $auth_ret = $this->wx_util->check_auth_code($auth_code);
        // unset 'admin_auth_code'
        $this->session->unset_userdata('admin_auth_code');

        if ($auth_ret && $user_email && $user_password) {
            $data = array();
            // get some admin user info
            $auth = $this->wxm_admin_user->check_admin_user($user_email, $user_password);
            if ($auth == '0') {
                $user_info = $this->wxm_admin_user->get_by_email($user_email);
                if ($user_info) {
                    $_SESSION['admin_user_id'] = $user_info['user_id'];
                    $_SESSION['admin_user_name'] = $user_info['user_name'];
                    $_SESSION['admin_user_email'] = $user_info['user_email'];
                    $_SESSION['admin_user_status'] = $user_info['user_status'];
                    $_SESSION['admin_user_type'] = $user_info['user_type'];
                    $_SESSION['admin_user_token'] = $user_info['user_token'];
                }
                redirect('cnadmin/home/index');
            }
        }
        else {
            redirect('cnadmin/home/login_page');
        }
	}
/*****************************************************************************/
    public function index()
    {
        $user_name = isset($_SESSION['admin_user_name']) ? $_SESSION['admin_user_name'] : '';
        $user_email = isset($_SESSION['admin_user_email']) ? $_SESSION['admin_user_email'] : '';
        // $data['admin_user_name'] = $user_name;
        // $data['admin_user_email'] = $user_email;
        if ($user_name && $user_email) {
            redirect('cnadmin/user/user_index');
        }
        else {
            redirect('cnadmin/home/login_page');
        }
    }
/*****************************************************************************/
    public function logout()
    {
        // destory all php session and CI session
        session_destroy();
        $this->session->sess_destroy();
        redirect('cnadmin/home/login_page');
    }
/*****************************************************************************/

/*****************************************************************************/
/*****************************************************************************/
	public function test()
	{
        $month = wx_month();
        $last_month = wx_last_month();
        $before_last_month = wx_before_last_month();
        echo 'Month: '.$month.'<br />';
        echo 'Last Month: '.$last_month.'<br />';
        echo 'Before last month: '.$before_last_month;
	}
/*****************************************************************************/
}

/* End of file home.php */
/* Location: /application/backend/controllers/home.php */
