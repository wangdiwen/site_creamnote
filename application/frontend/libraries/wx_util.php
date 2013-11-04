<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WX_Util
{
/*****************************************************************************/
    var $CI;  // Get the CI super object

/*****************************************************************************/
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('session');
    }
/*****************************************************************************/
/*****************************************************************************/
    public function get_login_addr() {
        $ip = $_SERVER["REMOTE_ADDR"] ? $_SERVER["REMOTE_ADDR"] : 'unknow';
        return $ip;
    }
/*****************************************************************************/
    public function get_user_session_info() {
        $user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        $user_name = isset($_SESSION['wx_user_name']) ? $_SESSION['wx_user_name'] : '';
        $user_email = isset($_SESSION['wx_user_email']) ? $_SESSION['wx_user_email'] : '';
        $data = array(
            'user_id' => $user_id,
            'user_name' => $user_name,
            'user_email' => $user_email,
            );
        return $data;
    }
/*****************************************************************************/
    public function check_has_login() {
        $user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        if ($user_id && is_numeric($user_id) && $user_id > 0) {
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function get_auth_code()
    {
        $random = rand(0, 29);
        $auth_code_file = 'application/frontend/license/auth_code.json';
        $json_context = file_get_contents($auth_code_file);
        $json = json_decode($json_context);

        $cur_auth_code = $json[$random]->math;
        $cur_result = $json[$random]->result;
        $session_data = array(
            'auth_code' => $cur_result
            );
        $this->CI->session->set_userdata($session_data);

        return $cur_auth_code;
    }
/*****************************************************************************/
    public function check_auth_code($result = '')
    {
        $auth_code_ret = $this->CI->session->userdata('auth_code');
        if ($result == $auth_code_ret) {
            return true;
        }
        return false;
    }
/*****************************************************************************/
}

/* End of file WX_General.php */
/* Location: ./application/libraries/wx_general.php */
