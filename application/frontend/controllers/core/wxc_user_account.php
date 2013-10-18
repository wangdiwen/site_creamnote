<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WXC_User_Account extends CI_Controller {
/*****************************************************************************/
    public function __construct() {
        parent::__construct();

		$this->load->model('core/wxm_user');
		$this->load->model('core/wxm_withdraw');

		$this->load->library('wx_email');
        $this->load->library('wx_util');
    }
/*****************************************************************************/
	public function account_active() {
		// 1, check has actived or not
		// 2, record the zhifubao account
		// 3, redirect to zhifubao login page
		$account_name = $this->input->post('account_email');

		// test ...
		// $account_name = 'dw_wang126@163.com';

		$cur_user_info = $this->wx_util->get_user_session_info();
		$user_id = $cur_user_info['user_id'];
		$account_status_info = $this->wxm_user->get_account_status($user_id);
		if ($account_status_info) {
			$has_actived = $account_status_info['user_account_active'];
			if ($has_actived == 'true') {
				echo 'has-actived';
				return false;
			}

			// has no actived
			if ($account_name) {
				$ret = $this->wxm_user->update_account_name($user_id, trim($account_name));
				if ($ret) {
					echo 'success';
					return true;
				}
			}
		}
		echo 'failed';
		return false;
	}
/*****************************************************************************/
	public function redirect_to_alipay() {
		// redirect to zhifubao login page
		redirect('http://www.creamnote.com/core/wxc_zhifubao_login/zhifubao_submit');
	}
/*****************************************************************************/
	public function change_ali_account() {
		$account_name = $this->input->post('account_email');

		$cur_user_info = $this->wx_util->get_user_session_info();
		$user_id = $cur_user_info['user_id'];
		$account_status_info = $this->wxm_user->get_account_status($user_id);
		if ($account_status_info) {
			$has_actived = $account_status_info['user_account_active'];
			$has_withdraw = $account_status_info['user_account_status'];
			if ($has_withdraw) {
				echo 'has-withdraw';
				return false;
			}
			if ($has_actived == 'false') {
				echo 'no-actived';
				return false;
			}

			// has no actived
			if ($account_name) {
				$ret = $this->wxm_user->update_account_name($user_id, trim($account_name));
				if ($ret) {
					echo 'success';
					return true;
				}
			}
		}
		echo 'failed';
		return false;
	}
/*****************************************************************************/
	public function require_withdraw_token() {
		$token = $this->input->post('withdraw_token');
		$token = trim($token);  // revise param

		// check param valid
		if (! is_numeric($token) || ! strlen($token)) {
			echo 'invalid-token';
			return false;
		}

		// 发送一个6位数的随机码，到用户的注册邮箱
		$cur_user_info = $this->wx_util->get_user_session_info();
		// $cur_user_id = $cur_user_info['user_id'];
		$cur_user_email = $cur_user_info['user_email'];
		$cur_user_name = $cur_user_info['user_name'];

		$rand_code_six = mt_rand(100000, 999999);
		$content = '<html><head></head><body><p><b>亲爱的'.$cur_user_name.'：</b></p>您的提现口令验证码为：'.$rand_code_six.'<p></body></html>';

		$this->wx_email->clear();

        $this->wx_email->set_from_user('no-reply@creamnote.com', '醍醐笔记');
        $this->wx_email->set_to_user($cur_user_email);
        $this->wx_email->set_subject('申请醍醐的提现口令');
        $this->wx_email->set_message($content);

        $ret = $this->wx_email->send_email();
		if ($ret) {
			// 记录 6 位的随机码到 Cookie
			$data = array(
				'withdraw_token' => $token,
				'withdraw_token_authcode' => $rand_code_six,
				);
			$this->session->set_userdata($data);
			echo 'success';
			return true;
		}
		echo 'failed';
		return false;
	}
/*****************************************************************************/
	public function record_withdraw_token() {
		$auth_code = $this->input->post('auth_code_token');

		$cur_user_info = $this->wx_util->get_user_session_info();
		$cur_user_id = $cur_user_info['user_id'];

		// 获取记录的 Cookie 数据
		$token = $this->session->userdata('withdraw_token');
		$cookie_withdraw_token_authcode = $this->session->userdata('withdraw_token_authcode');
		$withdraw_token_authcode = trim($auth_code);
		if ($cookie_withdraw_token_authcode && $withdraw_token_authcode
			&& $cookie_withdraw_token_authcode == $withdraw_token_authcode) {
			$ret = $this->wxm_user->update_account_token($cur_user_id, $token);
			if ($ret) {
				$data = array(
					'withdraw_token' => '',
					'withdraw_token_authcode' => '',
					);
				$this->session->unset_userdata($data);
				echo 'success';
				return true;
			}
		}
		echo 'failed';
		return false;
	}
/*****************************************************************************/
	public function require_withdraw_order() {
		// 1, check withdraw token has existed or not
		// 2, check user account's status, has actived ? has require withdraw ?
		// 3, check user's money, is > 10.00 RMB ?
		// 4, check user's withdraw status, has withdrawed ? or not ?
		// 5, record user withdraw order
		// 6, change user's account withdraw status to '1', '0'-> no withdraw
		// 7, update user's account balance money

		$withdraw_money = $this->input->post('withdraw_money');  // 前端为整数：10,20,30...
		$withdraw_token = $this->input->post('withdraw_token');
		$withdraw_money = trim($withdraw_money);
		$withdraw_token = trim($withdraw_token);

		// test ...
		// $withdraw_money = '20';
		// $withdraw_token = '654321';

		$cur_user_info = $this->wx_util->get_user_session_info();
		$cur_user_id = $cur_user_info['user_id'];

		$user_withdraw_info = $this->wxm_user->get_user_account($cur_user_id);
		if ($user_withdraw_info) {
			$ali_account = $user_withdraw_info['user_account_name'];
			$account_token = $user_withdraw_info['user_account_token'];
			$active_status = $user_withdraw_info['user_account_active'];
			$account_money = $user_withdraw_info['user_account_money'];
			$withdraw_status = $user_withdraw_info['user_account_status'];

			if (! $account_token) {
				echo 'no-token';
				return false;
			}
			if ($account_token != $withdraw_token) {
				echo 'wrong-token';
				return false;
			}
			if ($active_status != 'true') {
				echo 'no-actived';
				return false;
			}
			if ($account_money < 10.00) {
				echo 'no-money';
				return false;
			}
			if ($withdraw_status == '1') {
				echo 'has-withdraw';
				return false;
			}

			// todo 5,6
			$draw_no = date('YmdHis').mt_rand(100000, 999999);
			$draw_timestamp = date('Y-m-d H:i:s');
			$draw_status = 'false';
			$draw_admin = '';
			$draw_admin_time = '0000-00-00 00:00:00';
			// 计算提现的金额，目前策略为只能提现 10.00 的整数倍，例如：22.20，提现20.00，剩余2.20
			$money_list = wx_withdraw_money_list($account_money);
			if (! in_array((int)$withdraw_money, $money_list)) {
				echo 'wrong-money';
				return false;
			}

			$order_money = number_format($withdraw_money, 2, '.', '');
			$balance_money = (float)$account_money - (int)$withdraw_money;
			// wx_echoxml((string)$balance_money);
			// return;

			$data = array(
                'draw_no' => $draw_no,
                'draw_user_id' => $cur_user_id,
                'draw_ali_account' => trim($ali_account),
                'draw_money' => $order_money,
                'draw_timestamp' => $draw_timestamp,
                'draw_status' => $draw_status,
                'draw_admin' => $draw_admin,
                'draw_admin_time' => $draw_admin_time,
                );
			$ret_withdraw = $this->wxm_withdraw->create_order($data);
			$ret_change_withdraw_status = $this->wxm_user->update_account_money_status($cur_user_id, '1', $balance_money);  // 0->未提现，1->进入提现受理阶段
			if ($ret_withdraw && $ret_change_withdraw_status) {
				$balance_str = number_format($balance_money, 2, '.', '');
				echo $balance_str;	// success，返回余额
				return true;
			}
		}
		echo 'failed';
		return false;
	}
/*****************************************************************************/
	public function test() {
	}
/*****************************************************************************/
}

/* End of file wxc_user_account.php */
/* Location: /application/frontend/controllers/wxc_user_account.php */
