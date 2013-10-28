<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Withdraw extends CI_Controller
{
/*****************************************************************************/
    public function __construct()
    {
        parent::__construct();

        $this->load->model('wxm_withdraw');
        $this->load->model('wxm_user');
        $this->load->model('wxm_notify');

        $this->load->library('pagination');
        $this->load->library('wx_email');
        $this->load->library('wx_util');
    }
/*****************************************************************************/
    public function withdraw_orders($offset = 0) {
        $orders_count = $this->wxm_withdraw->withdraw_count();

        $config = array(
            'base_url' => base_url().'cnadmin/withdraw/withdraw_orders',
            'total_rows' => $orders_count,
            'per_page' => 5,
            'num_links' => 3,
            'uri_segment' => 4,
            'full_tag_open' => '<p>',
            'full_tag_close' => '</p>',
            'first_link' => '首页',
            'first_tag_open' => '<span>',
            'first_tag_close' => '</span>',
            'last_link' => '尾页',
            'last_tag_open' => '<span>',
            'last_tag_close' => '</span>',
            'next_link' => '下一页',
            'next_tag_open' => '<span>',
            'next_tag_close' => '</span>',
            'prev_link' => '上一页',
            'prev_tag_open' => '<span>',
            'prev_tag_close' => '</span>',
            'cur_tag_open' => '<span><a class="number current">',
            'cur_tag_close' => '</a></span>'
            );
        $this->pagination->initialize($config);

        $withdraw_orders = $this->wxm_withdraw->get_page_withdraw_order($config['per_page'], $offset);
        $data = array(
            'withdraw_order' => $withdraw_orders,
            'withdraw_offset' => $offset
            );

        // wx_echoxml($data);
        $this->load->view('f_account/wxv_withdraw', $data);
    }
/*****************************************************************************/
    public function check_order_valid() {
        $draw_no = $this->input->post('draw_no');
        $draw_user_id = $this->input->post('draw_user_id');
        $draw_money = $this->input->post('draw_money');

        // wx_loginfo($draw_no);
        // wx_loginfo($draw_user_id);
        // wx_loginfo($draw_money);

        // test ...
        // $draw_no = '20131017225548169445';
        // $draw_user_id = 1;
        // $draw_money = '10.00';

        // 检测提现申请的合法性
        // 1，当前提现申请的用户id，在提现申请等待处理中，必须唯一
        // 2，当前提现用户的收益账户，是否状态正常
        // 3，当前提现用户的id，对应的收益账户余额，必须大于等于 10.00
        // 4，提现的金额，必须为10的整数倍，并且在收益账户余额的10的倍数范围中
        $result = array(
            'is_only' => 'false',
            'is_account_ok' => 'false',
            'is_enough' => 'false',
            'is_ten_multi' => 'false',
            'ali_account' => '',
            'ali_realname' => '',
            'ali_draw_money' => '',
            );

        $draw_item_count = $this->wxm_withdraw->check_withdraw_only_by_user_id($draw_user_id);
        if ($draw_item_count == 1) {
            $result['is_only'] = 'true';
        }

        $user_account_base_info = $this->wxm_user->get_account_base_info($draw_user_id);
        if ($user_account_base_info) {
            $user_account_name = $user_account_base_info['user_account_name'];  // 支付宝账户
            $user_account_realname = $user_account_base_info['user_account_realname'];  // 支付宝实名签名
            $user_account_active = $user_account_base_info['user_account_active'];  // 支付宝是否激活，'true'
            $user_account_status = $user_account_base_info['user_account_status'];  // 提现中，'1'
            $user_account_money = $user_account_base_info['user_account_money'];  // 余额，大于10.00

            // wx_loginfo($draw_no);
            // wx_loginfo($user_account_name);
            // wx_loginfo($user_account_realname);
            // wx_loginfo($user_account_active);
            // wx_loginfo($user_account_status);

            if ($user_account_name && $user_account_realname
                && $user_account_status == '1'
                && $user_account_active == 'true') {
                $result['is_account_ok'] = 'true';
            }

            // wx_loginfo('is_account_ok:'.$result['is_account_ok']);

            if ($user_account_money >= 0.00) {
                $result['is_enough'] = 'true';
            }

            // $money_rand = wx_withdraw_money_list($user_account_money, true);
            // if (in_array($draw_money, $money_rand)) {
            //     $result['is_ten_multi'] = 'true';
            // }

            // 判断提现金额为10.00的整数倍
            $is_ten_multi = wx_is_ten_multi($draw_money);
            if ($is_ten_multi) {
                $result['is_ten_multi'] = 'true';
            }

            if ($result['is_only'] == 'true'
                && $result['is_account_ok'] == 'true'
                && $result['is_enough'] == 'true'
                && $result['is_ten_multi'] == 'true') {
                $result['ali_account'] = $user_account_name;
                $result['ali_realname'] = $user_account_realname;
                $result['ali_draw_money'] = $draw_money;
            }
        }

        // wx_echoxml($draw_money);
        // wx_echoxml($result);
        $json_data = json_encode($result);
        echo $json_data;
    }
/*****************************************************************************/
    public function reject_withdraw_order() {
        $is_only = $this->input->post('is_only');
        $is_account_ok = $this->input->post('is_account_ok');
        $is_enough = $this->input->post('is_enough');
        $is_ten_multi = $this->input->post('is_ten_multi');
        $draw_no = $this->input->post('draw_no');

        // wx_loginfo($is_only);
        // wx_loginfo($is_account_ok);
        // wx_loginfo($is_enough);
        // wx_loginfo($is_ten_multi);
        // wx_loginfo($draw_no);

        // 目前，如果提现订单在本地没有审核通过的话，会有4种原因，
        // 给用户个人中心发送系统通知
        $draw_user_info = $this->wxm_withdraw->get_draw_user_info($draw_no);
        if ($draw_user_info) {
            $draw_user_id = $draw_user_info['draw_user_id'];
            // $draw_ali_account = $draw_user_info['draw_ali_account'];
            $draw_money = $draw_user_info['draw_money'];
            $draw_timestamp = $draw_user_info['draw_timestamp'];

            // get cur admin info
            $cur_admin_info = $this->wx_util->get_admin_info();
            $cur_admin_name = $cur_admin_info['admin_user_name'];
            $cur_time = date('Y-m-d H:i:s');

            $why = '';
            if ($is_only == 'false') {
                $why = '# 重复提交提现申请；';
            }
            if ($is_account_ok == 'false') {
                $why = $why.'# 收益账户未激活或者提现错误；';
            }
            if ($is_enough == 'false') {
                $why = $why.'# 账户余额不足￥10.00；';
            }
            if ($is_ten_multi == 'false') {
                $why = $why.'# 提现金额必须为10元整数倍';
            }

            $notify_title = '提现申请驳回';
            $notify_content = '您在 '.$draw_timestamp.' 提交的提现申请，未通过系统验证，原因为：【'.$why.'】';

            $ret_send_sys_notify = $this->wxm_notify->send_system_notify($draw_user_id, $notify_title, $notify_content);
            if ($ret_send_sys_notify) {
                // 将提现订单的状态改为'已经受理'，
                // 将用户的账户解冻，返还提现的金额给用户的收益账户
                $ret_order_status = $this->wxm_withdraw->change_order_status_done($draw_no, $cur_admin_name, $cur_time);
                if ($ret_order_status) {
                    if ($is_only == 'false') {  // 不是唯一提现订单，则只返还提现金额，提现状态不改变
                        $ret_user_account_refund = $this->wxm_user->refund_account_withdraw_money($draw_user_id, $draw_money);
                        if ($ret_user_account_refund) {
                            echo 'success';
                            return true;
                        }
                    }
                    else {  // 提现订单唯一，正常状态，则解冻用户收益账户，并且返还提现金额
                        $ret_user_account_status = $this->wxm_user->change_account_withdraw_status_and_refund($draw_user_id, $draw_money);
                        if ($ret_user_account_status) {
                            echo 'success';
                            return true;
                        }
                    }
                }
            }
            echo 'failed';
            return false;
        }
        else {
            echo 'no-record';
            return false;
        }
    }
/*****************************************************************************/
    public function reject_withdraw_order_invalid_sign() {
        $draw_no = $this->input->post('draw_no');

        // 在企业支付宝进行对提现账户进行转换的时候，
        // 如果发现用户的支付宝签名和网站记录的签名不一致，
        // 那么需要驳回用户的提现申请

        // 1，变更提现订单的状态，为'true'
        // 2，将用户的收益账户解冻，返还提现的金额给用户的收益账户
        // 3，发送系统通知，由于支付宝账户认证的实名签名不一致，导致提现申请被驳回

        $draw_base_info = $this->wxm_withdraw->get_draw_user_info($draw_no);
        if ($draw_base_info) {
            $draw_user_id = $draw_base_info['draw_user_id'];
            $draw_ali_account = $draw_base_info['draw_ali_account'];
            $draw_money = $draw_base_info['draw_money'];
            $draw_timestamp = $draw_base_info['draw_timestamp'];

            // get cur admin info
            $cur_admin_info = $this->wx_util->get_admin_info();
            $cur_admin_name = $cur_admin_info['admin_user_name'];
            $cur_time = date('Y-m-d H:i:s');

            $user_account_realname = '';
            $user_account_base_info = $this->wxm_user->get_account_base_info($draw_user_id);
            if ($user_account_base_info) {
                $user_account_realname = $user_account_base_info['user_account_realname'];  // 支付宝实名签名
            }

            $why = '# 您的支付宝账户【实名认证的签名】与醍醐网站记录的签名【'.$user_account_realname.'】不一致；';
            $notify_title = '提现申请驳回';
            $notify_content = '您在 '.$draw_timestamp.' 提交的提现申请被驳回，原因为：【'.$why.'】 请到个人账户中核实您的支付宝实名认证签名，或者重新激活您的支付宝账户！';

            $ret_send_sys_notify = $this->wxm_notify->send_system_notify($draw_user_id, $notify_title, $notify_content);
            if ($ret_send_sys_notify) {
                // 将提现订单的状态改为'已经受理'，
                // 将用户的账户解冻，返还提现的金额给用户的收益账户
                $ret_order_status = $this->wxm_withdraw->change_order_status_done($draw_no, $cur_admin_name, $cur_time);
                if ($ret_order_status) {
                    $ret_user_account_refund = $this->wxm_user->change_account_withdraw_status_and_refund($draw_user_id, $draw_money);
                    if ($ret_user_account_refund) {
                        echo 'success';
                        return true;
                    }
                }
            }
            echo 'failed';
            return false;
        }
        else {
            echo 'no-record';
            return false;
        }
    }
/*****************************************************************************/
    public function accept_withdraw_order() {
        $draw_no = $this->input->post('draw_no');

        // 1，变更提现订单的状态，为'已受理'
        // 2，提现用户的收益账户解冻
        // 3，发送系统通知，成功受理了提现请求
        // 4，发送邮件，成功受理了提现请求

        $draw_base_info = $this->wxm_withdraw->get_draw_user_info($draw_no);
        if ($draw_base_info) {
            $draw_user_id = $draw_base_info['draw_user_id'];
            $draw_ali_account = $draw_base_info['draw_ali_account'];
            $draw_money = $draw_base_info['draw_money'];
            $draw_timestamp = $draw_base_info['draw_timestamp'];

            // get cur admin info
            $cur_admin_info = $this->wx_util->get_admin_info();
            $cur_admin_name = $cur_admin_info['admin_user_name'];
            $cur_time = date('Y-m-d H:i:s');

            // change withdraw order's status
            $ret_order_status = $this->wxm_withdraw->change_order_status_done($draw_no, $cur_admin_name, $cur_time);
            if ($ret_order_status) {
                // unfreeze the user's withdraw status of his account
                $ret_user_account_status = $this->wxm_user->change_account_withdraw_status_ok($draw_user_id);
                if ($ret_user_account_status) {
                    // send the platform system notify
                    $notify_title = '提现申请受理成功';
                    $notify_content = '您在 '.$draw_timestamp.' 提交的提现申请，受理成功。请到您的支付宝账户【'.$draw_ali_account.'】查看来自醍醐笔记平台的转账。';
                    $ret_send_sys_notify = $this->wxm_notify->send_system_notify($draw_user_id, $notify_title, $notify_content);

                    // send the withdraw ok emial to user
                    $draw_user_email_info = $this->wxm_user->get_user_name_email($draw_user_id);
                    if ($draw_user_email_info) {
                        $user_name = $draw_user_email_info['user_name'];
                        $user_email = $draw_user_email_info['user_email'];

                        $email_content = '<html><head></head>你好 <b>'.$user_name.'</b>：<p></p><p></p><p>您的提现申请已经成功受理，提现申请详细如下：</p>';
                        $email_content .= '<p>提现支付宝账户：'.$draw_ali_account.'</p>';
                        $email_content .= '<p>提现金额：'.$draw_money.'</p>';
                        $email_content .= '<p>提现时间：'.$draw_timestamp.'</p><p></p>';
                        $email_content .= '<p>^_^ : 请及时到您的支付宝账户查看来自“醍醐网络科技”的转账信息 ~~</p><p></p>';
                        $email_content .= '<p>醍醐笔记团队</p></html>';

                        $ret_send_withdraw_ok_email = $this->_send_withdraw_require_email($user_email, $email_content);
                    }

                    echo 'success';
                    return true;
                }
            }
            echo 'failed';
            return false;
        }
        else {
            echo 'no-record';
            return false;
        }
    }
/*****************************************************************************/
    public function _send_withdraw_require_email($user_email = '', $content = '') {
        if ($user_email && $content) {
            $config = array(
                'protocol' => 'smtp',
                'smtp_host' => 'smtp.ym.163.com',
                'smtp_port' => 25,
                'smtp_user' => 'no-reply@creamnote.com',
                'smtp_pass' => 'wx@creamnote',
                'mailtype' => 'html',
                'validate' => true,
                'crlf' => '\r\n',
                'charset' => 'utf-8',
                );
            $this->email->initialize($config);

            $this->wx_email->clear();
            $this->wx_email->set_from_user('no-reply@creamnote.com', '醍醐笔记');
            $this->wx_email->set_to_user($user_email);
            $this->wx_email->set_subject('提现申请通知');
            $this->wx_email->set_message($content);

            $ret = $this->wx_email->send_email();
            if ($ret) {
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function query_history_index() {
        $this->load->view('f_account/wxv_withdraw_history');
    }
/*****************************************************************************/
    public function query_history_by_user() {
        $user_email_name = $this->input->post('user_email_name');
        // $user_email_name = 'steven wang';

        // filter space char
        $user_email_name = trim($user_email_name);
        if ($user_email_name) {
            // get user id info
            $draw_user_id = 0;
            $user_id_info = $this->wxm_user->get_id_by_email_or_name($user_email_name);
            if ($user_id_info) {
                $draw_user_id = $user_id_info['user_id'];
            }
            $orders_history = $this->wxm_withdraw->get_history_by_user_id($draw_user_id);
            if ($orders_history) {
                echo json_encode($orders_history);
                return true;
            }
        }
        echo json_encode('');
        return false;
    }
/*****************************************************************************/
    public function test() {
        // echo 'CMS withdraw interface...';
        // $str = '20.11';
        // $money_rand = wx_withdraw_money_list($str, true);
        // wx_echoxml($money_rand);
        // if (in_array('10.00', $money_rand)) {
        //     echo 'like';
        // }
        // wx_echoxml(array('10.00', '20.00'));

        $str = '233224300.00';
        $mod = (float)$str;
        $ret = is_integer($mod);
        if ($ret) {
            echo '是整数。。。';
        }
        if (is_float($mod)) {
            echo '是浮点。。。';
        }
        if (ereg('^[1-9]+[0-9]*0\.00$', $str)) {
            echo '符合。。。';
        }
    }
/*****************************************************************************/
}

/* End of file withdraw.php */
/* Location: /application/backend/controllers/withdraw.php */
