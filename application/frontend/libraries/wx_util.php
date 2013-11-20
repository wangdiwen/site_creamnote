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
/**********************   统计发放的用户奖励金额  ******************************/
    public function statistic_award_money($type = '0', $money = 0.00) {
        $statistic_award_file = 'application/site_data/award_statistic.data';
        if (file_exists($statistic_award_file)) {
            $json_text = file_get_contents($statistic_award_file);
            if ($json_text) {
                $json_data = json_decode($json_text, true);
                if ($type == '0') {  // 新注册用户，奖励
                    $json_data['new-user'] = number_format($json_data['new-user'] + $money, 2, '.', '');
                    $json_data['total'] = number_format($json_data['total'] + $money, 2, '.', '');
                }
                elseif ($type == '1') {  // 优质笔记资料，奖励
                    $json_data['good-note'] = number_format($json_data['good-note'] + $money, 2, '.', '');
                    $json_data['total'] = number_format($json_data['total'] + $money, 2, '.', '');
                }
                elseif ($type == '2') {  // 邀请好友加入醍醐，奖励
                    $json_data['user-invite'] = number_format($json_data['user-invite'] + $money, 2, '.', '');
                    $json_data['total'] = number_format($json_data['total'] + $money, 2, '.', '');
                }
                $ret = file_put_contents($statistic_award_file, json_encode($json_data));
                if ($ret) {
                    return true;
                }
            }
            else {
                $award_data = array(
                    'new-user' => '0.00',
                    'good-note' => '0.00',
                    'user-invite' => '0.00',
                    'total' => '0.00',
                    );
                $ret = file_put_contents($statistic_award_file, json_encode($award_data));
                if ($ret) {
                    return true;
                }
            }
        }
        return false;
    }
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
