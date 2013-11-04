<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WXC_Alipay extends CI_Controller {
/*****************************************************************************/
    public function __construct() {
        parent::__construct();

        $this->load->helper('download');

        $this->load->model('core/wxm_data');
        $this->load->model('core/wxm_user');
        $this->load->model('core/wxm_user_activity');
        $this->load->model('share/wxm_data_activity');
        $this->load->model('core/wxm_pay_download');
        $this->load->model('core/wxm_pay_platform');

        $this->load->library('user_agent');
        // $this->load->library('wx_general');
        $this->load->library('wx_util');
        $this->load->library('wx_aliossapi');
        $this->load->library('wx_site_manager');

        $this->load->library('wx_alipay_direct_api');       // 调用自定义的支付宝即时到帐类
    }
/*****************************************************************************/
    public function _alipay_submit( $out_trade_no = '',          // 商户订单号, 商户网站订单系统中唯一订单号，必填
                                    $subject = '购买醍醐笔记',    // 订单名称
                                    $total_fee = 0.01,           // 付款金额
                                    $body = '醍醐笔记',           // 订单描述
                                    $show_url = '') {            // 商品展示地址, 需以http://开头的完整路径
        // 测试数据，
        // 接口链接为：www.creamnote.com/core/wxc_alipay/alipay_submit
        // $out_trade_no = '201310122249123456';
        // $subject = '购买学习笔记';
        // $total_fee = '0.01';
        // $body = '通信工程专业学习笔记';
        // $show_url = 'http://www.creamnote.com/data/wxc_data/data_view/11';
        if (! $out_trade_no || ! $subject || ! $total_fee) {
            echo 'Request Error !';
            return false;
        }

        $html_content = $this->wx_alipay_direct_api->alipay_submit($out_trade_no, $subject, $total_fee, $body, $show_url);
        echo $html_content;
    }
/*****************************************************************************/
    public function return_url() {                                  // 支付宝同步通知跳转url
        // test ...
        // wx_echoxml($_GET);

        // 此官方的验证返回结果正确与否，接口好像不能正常工作，暂时不使用此接口判断返回
        // $verify_result = $this->wx_alipay_direct_api->get_notify();

        $out_trade_no = $this->input->get('out_trade_no');      // 商户订单号
        $trade_no = $this->input->get('trade_no');              // 支付宝交易号
        $trade_status = $this->input->get('trade_status');      // 交易状态
        $total_fee = $this->input->get('total_fee');            // 支付宝的付费金额，差额

        if ($trade_status == 'TRADE_SUCCESS' || $trade_status == 'TRADE_FINISHED') {
            // 支付宝返回的支付成功状态
            // 可以再次加入平台的订单处理逻辑代码，如记录数据库，提供用户一下必要的服务信息等
            // 注意：如果是异步通知的url，还可以根据订单号对数据库中的订单进行查询，判断是否已经处理了？

            // tips ...
            echo '系统正在进行订单资料审核，请稍等 ...'."<br />";

            // @1，从订单表，获取付费用户的id，以及笔记的id信息
            $order_info = $this->wxm_pay_download->get_order_by_trade_no($out_trade_no);
            if ($order_info) {      // 存在此笔订单
                $pay_user_id = $order_info['pay_user_id'];          // 付费用户id
                $pay_show_url = $order_info['pay_show_url'];

                $note_price = 0.00;                                 // 笔记总价格
                $pay_owner_fee = 0.00;                              // 笔记所有者的收益 78.8%

                $str_list = explode('/', $pay_show_url);
                $note_id = isset($str_list[6]) ? $str_list[6] : 0;  // 笔记资料id
                $note_own_user_id = 0;                              // 笔记所有者id

                if ($note_id == 0 || ! is_numeric($note_id)) {
                    echo '订单系统中笔记资料信息异常，交易请求中断！'."<br />";
                    die('Not find invalid note id, request disconnnect !');
                }

                // @1，订单状态改为：true，同时记录支付宝的交易流水号
                $ret_status = $this->wxm_pay_download->change_order_status_invalid($out_trade_no, $trade_no);

                // @3，从订单资金分配关联表，获取总资金，以及3方的各自分配信息
                $platform_order_info = $this->wxm_pay_platform->get_order_by_trade_no($out_trade_no);
                if ($platform_order_info) {
                    $note_price = $platform_order_info['plat_total_fee'];
                    $pay_owner_fee = $platform_order_info['plat_owner'];
                }

                // @4，根据笔记的id，获取笔记的所有者的id
                $note_info = $this->wxm_data->get_owner_user_id($note_id);
                if ($note_info) {
                    $note_own_user_id = $note_info['user_id'];
                }

                // @5，更新付费用户的数据，如：收益账户扣除余额，记录历史等
                // 更新付费用户的收益账户，扣除笔记的价格
                // 此时，不论支付宝的金额是否和和笔记价格相等？付费用户的收益账户都清零
                $pay_user_account_data = array(
                    'user_id' => $pay_user_id,
                    'user_account_money' => 0.00,
                    );
                $ret_pay_user_account = $this->wxm_user->update_account_balance($pay_user_account_data);

                // @7，更新笔记所有者的信息，如：收益账户增加收入
                // 更新笔记所有者的收益账户，增加获取的收益
                $owner_user_balance_data = array(
                    'user_id' => $note_own_user_id,
                    'user_account_money_add' => $pay_owner_fee,
                    );
                $ret_owner_user_balance_data = $this->wxm_user->add_account_balance($owner_user_balance_data);
                // 更新笔记所有者的下载次数记录
                $this->wxm_user_activity->add_been_download_count($note_own_user_id);

                // @8，记录付费用户的付费下载历史信息
                $pay_download_info = $this->wxm_user_activity->get_pay_download_info($pay_user_id);
                if ($pay_download_info) {
                    $pay_user_download_count = $pay_download_info['uactivity_downloadcount'];
                    $pay_user_download_history = $pay_download_info['uactivity_pay_download'];

                    $pay_download_data = array(
                        'user_id' => $pay_user_id,
                        'uactivity_downloadcount' => $pay_user_download_count + 1,
                        'uactivity_pay_download' => '',
                        );

                    // update the pay user's download history
                    if ($pay_user_download_history) {
                        $history_data_list = explode(',', $pay_user_download_history);
                        if (! in_array($note_id, $history_data_list)) {
                            $history_data_list[] = $note_id;
                            $new_history_str = implode(',', $history_data_list);
                            $pay_download_data['uactivity_pay_download'] = $new_history_str;
                        }
                        else {
                            $pay_download_data['uactivity_pay_download'] = $pay_user_download_history;
                        }
                    }
                    else {
                        $pay_download_data['uactivity_pay_download'] = $note_id;
                    }
                    $ret_pay_download = $this->wxm_user_activity->update_pay_download_info($pay_download_data);
                }

                // @9，更新笔记自己的下载和付费次数
                $data_activity_download_info = $this->wxm_data_activity->get_pay_download_info($note_id);
                if ($data_activity_download_info) {
                    $data_download_count = $data_activity_download_info['dactivity_download_count'];
                    $data_pay_count = $data_activity_download_info['dactivity_buy_count'];

                    $data_download_data = array(
                        'data_id' => $note_id,
                        'dactivity_download_count' => $data_download_count + 1,
                        'dactivity_buy_count' => $data_pay_count + 1,
                        );
                    $this->wxm_data_activity->update_pay_download($data_download_data);
                }

                // 添加网站的营收总量
                $creamnote_money = number_format($note_price * 0.2, 2, '.', '');  // 20% ratio for creamnote
                $this->wx_site_manager->add_site_income($creamnote_money);

                // @10，进入下载进程
                echo '正在加载您需要的笔记资料 ...'."<br />";

                // 销毁本次支付请求的CI Session Cookie
                $pay_order = array(
                    'pay_trade_no' => '',
                    'pay_subject' => '',
                    'pay_body' => '',
                    'pay_show_url' => '',
                    'pay_diff_money' => '',
                    );
                $this->session->unset_userdata($pay_order);
                // 在本地记录一次支付成功后的笔记资料的 id，
                // 和 this->check_pay_ok() 函数中的记录相同，cookie名称为'pay_ok_note'
                $pay_ok_note_cookie = array(
                    'pay_ok_note' => $note_id,
                    );
                $this->session->set_userdata($pay_ok_note_cookie);

                redirect('core/wxc_download_note/downloading_ok');
                return true;
            }
            else {                  // 数据库中不存在此笔订单
                echo '订单系统未找到交易订单信息，交易请求中断！';
                die('Not find such order information, request disconnnect !');
            }
        }
        else {
            echo '支付宝同步通知: 非法状态返回 '.$trade_status;
            die('alipay return url failed, invalid status '.$trade_status);
        }
    }
/*****************************************************************************/
    public function notify_url() {                                  // 支付宝异步通知url
        // test ...
        // $post_data = json_encode($_POST);
        // wx_loginfo($post_data);

        // 此官方的验证返回结果正确与否，接口好像不能正常工作，暂时不使用此接口判断返回
        // $verify_result = $this->wx_alipay_direct_api->get_notify();

        $out_trade_no = $this->input->post('out_trade_no');      // 商户订单号
        $trade_no = $this->input->post('trade_no');              // 支付宝交易号
        $trade_status = $this->input->post('trade_status');      // 交易状态

        if($trade_status == 'TRADE_SUCCESS' || $trade_status == 'TRADE_FINISHED') {
            // TRADE_FINISHED 交易状态只在两种情况下出现
            // 1、开通了普通即时到账，买家付款成功后。
            // 2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。

            // TRADE_SUCCESS 交易状态只在一种情况下出现——开通了高级即时到账，买家付款成功后。
            // 说明：状态的逻辑按照我们平台开通的服务决定？

            // Todo：网站平台一般在此做一些逻辑处理，比如：判断订单数据库中是否已经对改订单号进行了处理了？
            // 因为在同步通知url中会提前做业务处理，使用在此做判断，是为了保险起见。

            // Todo...
        }

        echo "success";     // 请不要修改或删除, 必须返回succes字符串给支付宝后台！！！
    }
/*****************************************************************************/
    public function check_pay_ok() {
        // 在收银台页面的”完成支付“按钮接口
        // 根据笔记id 检测用户的订单，是否成功了？如果成功，则进入下载进程
        // 如果没有完成支付，则跳转到重新支付页面
        $note_id = $this->input->post('note_id');

        // test ...
        // $note_id = 13;

        $cur_user_info = $this->wx_util->get_user_session_info();
        $pay_user_id = $cur_user_info['user_id'];
        $pay_show_url = 'http://www.creamnote.com/data/wxc_data/data_view/'.$note_id;

        $has_pay_ok = $this->wxm_pay_download->check_by_userid_and_showurl($pay_user_id, $pay_show_url);
        if ($has_pay_ok) {
            // 如果支付成功，则将 note_id 记录到 CI Session Cookie中
            $pay_ok_note = $this->session->userdata('pay_ok_note');
            if (! $pay_ok_note) {
                echo 'has-downloaded';  // 支付后的快捷下载只能使用一次
                return false;
            }

            echo 'pay-over';  // use: this->require_download_direct() func
        }
        else {
            echo 'not-pay-over';  // use: this->alipay_submit_again() func
        }
    }
/*****************************************************************************/
    public function fast_pay_download_fail() {
        $this->load->view('share/wxv_download_failed');
    }
/*****************************************************************************/
    public function require_download_direct() {     // 此接口，可以在支付成功后，直接请求下载，一般情况下，只可使用一次
        $data_id = $this->session->userdata('pay_ok_note');

        if (! $data_id) {
            // echo 'Not Find Such Note ID, Request Error !'."<br />";
            $this->load->view('share/wxv_download_failed');
            return false;
        }

        $data_name = '';
        $data_objectname = '';
        $data_type = 'doc';
        $data_osspath = '';
        $data_vpspath = '';

        $data_info = $this->wxm_data->get_download_info($data_id);
        if ($data_info) {
            $data_name = $data_info['data_name'];
            $data_objectname = $data_info['data_objectname'];
            $data_type = $data_info['data_type'];
            // $data_own_user_id = $data_info['user_id'];
            $data_osspath = $data_info['data_osspath'];
            $data_vpspath = $data_info['data_vpspath'];
        }

        // 清除本地存储的 'pay_ok_note'，即支付宝支付成功的笔记id
        $pay_ok_note_cookie = array(
                'pay_ok_note' => '',
                );
        $this->session->unset_userdata($pay_ok_note_cookie);

        if (! $data_name || ! $data_objectname) {
            // echo 'Not Find Such Note, Request Error !';
            $this->load->view('share/wxv_download_failed');
            return false;
        }

        // free download file by browser
        $file_url = '';
        if ($data_vpspath && file_exists($data_vpspath.$data_objectname)) {
            $file_url = $data_vpspath.$data_objectname;
        }
        elseif ($data_osspath) {
            // like, oss file =>  'http://'.$data_osspath.'.oss-internal.aliyuncs.com/'.$data_objectname;
            $bucket = $data_osspath;
            $object = $data_objectname;
            $save_file = '';
            $local_dir = '/alidata/www/creamnote/upload/'.substr($data_osspath, 3).'/';

            if (is_dir($local_dir)) {
                $save_file = $local_dir.'/'.$data_objectname;
                $oss_ret = $this->wx_aliossapi->get_object($bucket, $data_objectname, $save_file);
                if ($oss_ret) {
                    // update to db, data_vpspath
                    $this->wxm_data->update_vpspath($data_id, $local_dir);
                    // record this data lifetime
                    $now_timestamp = date('Y-m-d H:i:s');
                    $this->wxm_data_activity->update_data_lifetime($data_id, $now_timestamp);
                }
                $file_url = $save_file;
            }
        }

        // prepare to download note

        $file_name = $data_name.'.'.$data_type;
        if ($file_name && $file_url) {
            // $file_data = $file_url ? file_get_contents($file_url) : '';
            // $file_len = strlen($file_data) / 1024;
            $file_len = filesize($file_url);

            $this->output->set_header("Content-type: application/octet-stream");
            $this->output->set_header("Accept-Ranges: bytes");
            $this->output->set_header("Content-type: application/force-download; charset=utf-8");
            $this->output->set_header("Content-Length: ".$file_len);

            // check user browser
            $agent_info = $this->agent->agent_string();
            if (strpos($agent_info, 'MSIE')) {   // solve chinese mess word
                force_download(urlencode($file_name), file_get_contents($file_url));
            }
            else {
                force_download($file_name, file_get_contents($file_url));
            }
        }
    }
/*****************************************************************************/
    public function alipay_submit_again() {
        $pay_trade_no = $this->session->userdata('pay_trade_no');
        $pay_subject = $this->session->userdata('pay_subject');
        $pay_body = $this->session->userdata('pay_body');
        $pay_show_url = $this->session->userdata('pay_show_url');
        $pay_diff_money = $this->session->userdata('pay_diff_money');

        if (! $pay_trade_no || ! $pay_subject || ! $pay_diff_money) {
            echo 'Pay Request Error !';
            return false;
        }

        echo '您的订单已生效，即将进入 支付宝 官方收银台，请稍等 ...';
        $html_content = $this->wx_alipay_direct_api->alipay_submit($pay_trade_no, $pay_subject, $pay_diff_money, $pay_body, $pay_show_url);
        echo $html_content;
    }
/*****************************************************************************/
    public function test() {
        echo "hi, alipay controller function..."."<br />";
        $this->load->view('share/wxv_download_failed');
        // $this->wx_alipay_direct_api->test();
    }
/*****************************************************************************/
}

/* End of file alipay.php */
/* Location: /application/frontend/controllers/wxc_alipay.php */
