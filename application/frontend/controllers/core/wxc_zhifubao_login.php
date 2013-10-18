<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WXC_Zhifubao_Login extends CI_Controller {
/*****************************************************************************/
    public function __construct() {
        parent::__construct();

		$this->load->model('core/wxm_user');

        $this->load->library('wx_util');
        $this->load->library('wx_zhifubao_login_api');
    }
/*****************************************************************************/
/*****************************************************************************/
    public function zhifubao_return_url() {
		// get result: is_success, notify_id, real_name, token, user_id, sign, sign_type
		$is_success = $this->input->get('is_success');  // 'T'->true,success
        $user_id = $this->input->get('user_id');
		$real_name= $this->input->get('real_name');

		$cur_user_info = $this->wx_util->get_user_session_info();
		$cur_user_id = $cur_user_info['user_id'];

        if ($cur_user_id && $is_success == 'T' && $user_id && $real_name) {
			// record real name as signature name to user's account
			$ret = $this->wxm_user->update_account_realname($cur_user_id, $real_name);
			if ($ret) {
				// echo 'success,'.$real_name;
                redirect('http://www.creamnote.com/primary/wxc_personal/update_userinfo_page/'.urlencode($real_name));
			}
        }
        else {
            // echo 'failed';
            $this->load->view('share/wxv_activate_fail');
        }
    }
/*****************************************************************************/
    public function zhifubao_submit() {
        // http://www.creamnote.com/core/wxc_zhifubao_login/zhifubao_submit
        // 激活支付宝接口，检查用户是否填写了支付宝账户
        $ali_account = $this->input->post('ali_account');

		$html_text = $this->wx_zhifubao_login_api->alipay_submit();
		// wx_loginfo($html_text);
        echo $html_text;
    }
/*****************************************************************************/
    public function test() {
		// echo 'fuck';
        // $this->load->view('share/wxv_activate_fail');
    }
/*****************************************************************************/
}

/* End of file wxc_zhifubao_login.php */
/* Location: /application/frontend/controllers/wxc_zhifubao_login.php */
