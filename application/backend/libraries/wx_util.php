<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WX_Util
{
/*****************************************************************************/
    var $CI;  // Get the CI super object

/*****************************************************************************/
    public function __construct()
    {
        $this->CI =& get_instance();
    }
/*****************************************************************************/
    public function get_admin_info() {
        $id = isset($_SESSION['admin_user_id']) ? $_SESSION['admin_user_id'] : 0;
        $name = isset($_SESSION['admin_user_name']) ? $_SESSION['admin_user_name'] : '';
        $email = isset($_SESSION['admin_user_email']) ? $_SESSION['admin_user_email'] : '';
        $status = isset($_SESSION['admin_user_status']) ? $_SESSION['admin_user_status'] : 'false';
        $type = isset($_SESSION['admin_user_type']) ? $_SESSION['admin_user_type'] : '';
        $token = isset($_SESSION['admin_user_token']) ? $_SESSION['admin_user_token'] : '';
        $admin_info = array(
            'admin_user_id' => $id,
            'admin_user_name' => $name,
            'admin_user_email' => $email,
            'admin_user_status' => $status,
            'admin_user_type' => $type,
            'admin_user_token' => $token,
            );
        return $admin_info;
    }
/*****************************************************************************/
    public function check_admin_permission($user_type = 'super', $other_type = '')
    {
        $cur_user_type = isset($_SESSION['admin_user_type']) ? $_SESSION['admin_user_type'] : '';
        if ($cur_user_type) {
            if ($user_type && $user_type == $cur_user_type) {
                return true;
            }
            if ($other_type && $other_type == $cur_user_type) {
                return true;
            }
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
            'admin_auth_code' => $cur_result
            );
        $this->CI->session->set_userdata($session_data);

        return $cur_auth_code;
    }
/*****************************************************************************/
    public function check_auth_code($result = '')
    {
        $auth_code_ret = $this->CI->session->userdata('admin_auth_code');
        if ($result == $auth_code_ret) {
            return true;
        }
        return false;
    }
/*****************************************************************************/

/*****************************************************************************/
}

/* End of file WX_General.php */
/* Location: ./application/libraries/wx_general.php */
