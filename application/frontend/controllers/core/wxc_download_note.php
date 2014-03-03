<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXC_Download_Note extends CI_Controller {
/*****************************************************************************/
    public function __construct() {
        parent::__construct();
        $this->load->helper('download');
        $this->load->library('user_agent');

        $this->load->model('core/wxm_data');
        $this->load->model('core/wxm_user');
        $this->load->model('core/wxm_user_activity');
        $this->load->model('share/wxm_data_activity');
        $this->load->model('core/wxm_pay_download');
        $this->load->model('core/wxm_pay_platform');

        // $this->load->library('wx_general');
        $this->load->library('wx_util');
        $this->load->library('wx_aliossapi');
        $this->load->library('wx_site_manager');
        $this->load->library('wx_alipay_direct_api');       // 调用自定义的支付宝即时到帐类
    }
/*****************************************************************************/
    public function download_file($data_id = 0) {
        $cur_user_info = $this->wx_util->get_user_session_info();
        $cur_user_id = $cur_user_info['user_id'];

        if (! $cur_user_id > 0
            || ! is_numeric($data_id)
            || ! $data_id > 0 ) {
            die('Not login or system data error');
            return;
        }

        $data_info = $this->wxm_data->get_download_info($data_id);
        if ($data_info) {
            // get data base info
            $data_id = $data_info['data_id'];
            $data_name = $data_info['data_name'];
            $data_objectname = $data_info['data_objectname'];
            $data_type = $data_info['data_type'];
            $data_price = $data_info['data_price'];
            $data_own_user_id = $data_info['user_id'];
            $data_osspath = $data_info['data_osspath'];
            $data_vpspath = $data_info['data_vpspath'];

            // 如果笔记是自己的，那么可以直接下载
            if ($data_own_user_id == $cur_user_id) {
                // update note download count of note itself
                if ($data_id > 0) {
                    $activity_info = $this->wxm_data_activity->get_download_info($data_id);
                    if ($activity_info) {
                        $download_count = $activity_info['dactivity_download_count'];
                        $download = array(
                            'data_id' => $data_id,
                            'dactivity_download_count' => $download_count + 1,
                            );
                        $this->wxm_data_activity->update_download($download);
                    }
                    // add data owner been downloaded count
                    if ($data_own_user_id > 0) {
                        $this->wxm_user_activity->add_been_download_count($data_own_user_id);
                    }
                }

                // download request process
                $this->_require_download($data_id, $data_name, $data_objectname, $data_type, $data_osspath, $data_vpspath);
                return true;
            }
            else {
                // check note, free download ? or pay download ?
                // and record site download target
                if ($data_price == '0.00') {  // free
                    // and then, update cur user some record
                    if ($data_id > 0 && $cur_user_id > 0) {
                        $download_info = $this->wxm_user_activity->get_download_info($cur_user_id);
                        if ($download_info) {
                            $download_count = $download_info['uactivity_downloadcount'];
                            $download_history = $download_info['uactivity_download'];
                            $day_down_count = $download_info['uactivity_day_downcount'];

                            // check and limit cur user just
                            // can download free note 5 count everyday
                            // if this note is user owner, then don't need check
                            if ($data_own_user_id != $cur_user_id
                                && is_numeric($day_down_count)
                                && $day_down_count >= 5) {
                                redirect('static/wxc_direct/free_download_over');
                                return false;
                            }

                            $his_data_str = '';
                            if ($download_history) {
                                $history_data_list = explode(',', $download_history);
                                if (! in_array($data_id, $history_data_list)) {
                                    $len = count($history_data_list);
                                    if ($len >= 7) {
                                        array_splice($history_data_list, 0, 1);
                                        $history_data_list[] = $data_id;
                                    }
                                    else {
                                        $history_data_list[] = $data_id;
                                    }
                                }
                                $his_data_str = implode(',', $history_data_list);
                            }
                            else {
                                $his_data_str = $data_id;
                            }
                            // update data to db
                            $data = array(
                                'user_id' => $cur_user_id,
                                'uactivity_downloadcount' => $download_count + 1,
                                'uactivity_download' => $his_data_str,
                                );
                            if ($data_own_user_id != $cur_user_id) {
                                $data['uactivity_day_downcount'] = $day_down_count + 1;
                            }
                            $this->wxm_user_activity->update_download_count($data);
                        }
                    }

                    // update note download count of itself
                    if ($data_id > 0) {
                        $activity_info = $this->wxm_data_activity->get_download_info($data_id);
                        if ($activity_info)
                        {
                            $download_count = $activity_info['dactivity_download_count'];
                            $download = array(
                                'data_id' => $data_id,
                                'dactivity_download_count' => $download_count + 1,
                                );
                            $this->wxm_data_activity->update_download($download);
                        }
                        // add data owner been downloaded count
                        if ($data_own_user_id > 0) {
                            $this->wxm_user_activity->add_been_download_count($data_own_user_id);
                        }
                    }

                    // record free download count
                    $this->wx_site_manager->add_free_download_count();

                    // free download file by browser
                    $file_url = '';
                    if ($data_vpspath && file_exists($data_vpspath.$data_objectname)) {
                        $file_url = $data_vpspath.$data_objectname;
                    }
                    elseif ($data_osspath) {
                        // $file_url = 'http://'.$data_osspath.'.oss-internal.aliyuncs.com/'.$data_objectname;
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
                        // $file_data = file_get_contents($file_url);
                        // $file_len = strlen($file_data)/1024;
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
                else {  // pay
                    // redirect to shou-yin-tai page
                    $user_account_money = 0.00;
                    $user_account_status = '0';
                    $user_account_money_info = $this->wxm_user->get_user_account_money($cur_user_id);
                    if ($user_account_money_info) {
                        $user_account_money = $user_account_money_info['user_account_money'];
                        $user_account_status = $user_account_money_info['user_account_status'];
                    }

                    $diff_money = 0.00;  // 计算本次支付宝支付的差额
                    // 如果当前用户的收益账户处于提现状态，则使用支付宝全额支付
                    if ($user_account_status == '1') {  // 提现受理中，收益账户冻结
                        $diff_money = $data_price;
                    }
                    else {                              // 未提现申请，收益账户余额可用
                        if ($data_price > $user_account_money
                            && $user_account_money >= 0.00) {
                            $diff_money = number_format($data_price - $user_account_money, 2, '.', '');
                        }
                    }

                    $pay_data = array(
                        'note_id' => $data_id,
                        'note_own_user_id' => $data_own_user_id,
                        'note_name' => $data_name.'.'.$data_type,
                        'note_price' => $data_price,
                        'user_account_money' => $user_account_money,
                        'diff_money' => $diff_money,  // 差额：当价格大于余额的时候，价格减去用户收益余额
                        );
                    // wx_echoxml($pay_data);
                    $this->load->view('trade/wxv_accountselect_page', $pay_data);
                }
            }
        }
    }
/*****************************************************************************/
    public function pay_download_file() {
        $note_id = $this->input->post('note_id');
        $note_own_user_id = $this->input->post('note_own_user_id');
        $note_name = $this->input->post('note_name');
        $note_price = $this->input->post('note_price');
        // $user_account_money = $this->input->post('user_account_money');
        // $diff_money = $this->input->post('diff_money');

        // test ...
        // wx_echoxml($note_id);
        // wx_echoxml($note_own_user_id);
        // wx_echoxml($note_name);
        // wx_echoxml($note_price);
        // return false;

        // test ...
        // $note_id = 2;
        // $note_own_user_id = 1;
        // $note_name = '支付宝付费下载接口';
        // $note_price = '1.99';
        // $user_account_money = '3.01';
        // $diff_money = '0.01';

        // 生成付费订单，数据表2个：wx_pay_download, wx_pay_platform
        $cur_user_info = $this->wx_util->get_user_session_info();

        $pay_user_id = $cur_user_info['user_id'];
        if ($pay_user_id == 0) {
            echo 'disconnected';  // lost connection
            return false;
        }

        $user_account_money = 0.00;
        $user_account_status = '0';
        $user_account_money_info = $this->wxm_user->get_user_account_money($pay_user_id);
        if ($user_account_money_info) {
            $user_account_money = $user_account_money_info['user_account_money'];
            $user_account_status = $user_account_money_info['user_account_status'];
        }
        if ($user_account_money < 0.00) {
            // 发现账户余额为负数，则说明程序出现问题，正常余额大于等于0，对账户进行归零操作
            $set_user_accout_zero = array(
                'user_id' => $pay_user_id,
                'user_account_money' => number_format(0.00, 2, '.', ''),
                );
            $set_user_account_zero_ret = $this->wxm_user->update_account_balance($set_user_accout_zero);
            echo 'not-enough-money';
            return false;
        }

        $diff_money = 0.00;
        // 如果当前用户的收益账户处于提现状态，则使用支付宝全额支付
        if ($user_account_status == '1') {  // 提现受理中，收益账户冻结
            $diff_money = $note_price;
        }
        else {                              // 未提现申请，收益账户余额可用
            if ($note_price > $user_account_money
                && $user_account_money >= 0.00) {
                $diff_money = $note_price - $user_account_money;
            }
        }

        $pay_total_fee = $note_price;
        $pay_status = 'false';
        $pay_way = '0';             // 0：余额支付，1：支付宝，2：混合方式
        $pay_trade_no = date('YmdHis').mt_rand(100000, 999999);
        $pay_alipay_no = '';
        $pay_subject = '购买醍醐笔记';
        $pay_body = mb_substr(trim($note_name), 0, 19, 'utf-8');
        $pay_show_url = 'http://www.creamnote.com/data/wxc_data/data_view/'.$note_id;
        $pay_timestamp = date('Y-m-d H:i:s');

        if ($diff_money == 0.00) {  // 余额足够，使用余额支付
            $pay_way = '0';
        }
        elseif ($diff_money == $note_price) {
            $pay_way = '1';
        }
        elseif ($diff_money < $note_price) {
            $pay_way = '2';
        }

        // wx_pay_download 订单表，
        $order_pay = array(
            'pay_user_id' => $pay_user_id,
            'pay_total_fee' => $pay_total_fee,
            'pay_status' => $pay_status,
            'pay_way' => $pay_way,
            'pay_trade_no' => $pay_trade_no,
            'pay_alipay_no' => $pay_alipay_no,
            'pay_subject' => $pay_subject,
            'pay_body' => $pay_body,
            'pay_show_url' => $pay_show_url,
            'pay_timestamp' => $pay_timestamp,
            );

        // wx_pay_platform 订单关联表，资金的分配
        // 醍醐平台 20%，支付宝费率 1.2%，剩余 78.8% 笔记所有者收益
        $money_distribute = wx_money_distribute($pay_total_fee);
        $creamnote_money = $money_distribute['creamnote'];
        $alipay_money = $money_distribute['alipay'];
        $owner_money = $money_distribute['owner'];

        $order_platform = array(
            'plat_trade_no' => $pay_trade_no,
            'plat_total_fee' => $pay_total_fee,
            'plat_creamnote' => $creamnote_money,
            'plat_zhifubao' => $alipay_money,
            'plat_owner' => $owner_money,
            );

        // record pay download count
        $this->wx_site_manager->add_pay_download_count();

        if ($pay_way == '0') {      // 直接下载，从收益账户余额账户扣除笔记价格

            // test ...
            // wx_echoxml($order_pay);
            // wx_echoxml($order_platform);

            // 1，生成本次余额支付订单，修正订单状态为‘true’
            // 2，生成本次资金分配支付订单
            // 3，从付费用户的收益账户，扣除笔记价格，更新余额
            // 4，向笔记所有者的收益账户，打入本次的付费收益，更新余额

            $pay_user_balance = number_format((float)$user_account_money - (float)$pay_total_fee, 2, '.', '');
            $pay_user_account_info = array(
                'user_id' => $pay_user_id,
                'user_account_money' => $pay_user_balance,
                );

            $owner_user_balance_info = array(
                'user_id' => $note_own_user_id,
                'user_account_money_add' => $owner_money,
                );

            // test ...
            // wx_echoxml($pay_user_account_info);
            // wx_echoxml($owner_user_balance_info);
            // return false;        // test ...

            $order_pay['pay_status'] = 'true';      // 余额支付的话，订单状态为：交易完成true
            $pay_ret = $this->wxm_pay_download->create_order($order_pay);
            if ($pay_ret) {
                $platform_ret = $this->wxm_pay_platform->create_order($order_platform);

                $pay_user_account_ret = $this->wxm_user->update_account_balance($pay_user_account_info);
                $owner_user_balance_ret = $this->wxm_user->add_account_balance($owner_user_balance_info);

                // 添加网站的营收总量
                $this->wx_site_manager->add_site_income($creamnote_money);

                if ($pay_user_account_ret && $owner_user_balance_ret) {
                    // echo 'success';
                    // return true;

                    // 订单及用户收益账户，数据记录成功后，进入下载进程
                    // $ret_loading = $this->_direct_download_via_balance($note_id, $pay_user_id);
                    // return true;

                    // 在本地记录一次支付成功后的笔记资料的 id，
                    // 和 this->check_pay_ok() 函数中的记录相同，cookie名称为'pay_ok_note'
                    $pay_ok_note_cookie = array(
                        'pay_ok_note' => $note_id,
                        );
                    $this->session->set_userdata($pay_ok_note_cookie);

                    redirect('core/wxc_download_note/downloading_ok');
                    return true;
                }
            }
        }
        elseif ($pay_way == '1' || $pay_way == '2') {  // 跳转到支付宝付费页面，支付方式为：1-全额支付，2-混合支付，从收益账户中使用全部余额，差额采用支付宝支付
            // 1，首先，生成付费订单，订单状态为‘未交易完成’false
            // 2，生成订单的关联资金分配订单
            // 3，根据支付的差额diff-money进入支付宝的即时到帐支付页面，等待购买用户完成支付
            // 4，用户完成支付后，在同步通知接口，根据订单号获取订单数据，如果存在此订单，则完成一系列的数据处理工作
            //     @1，从订单表，获取付费用户的id，以及笔记的id信息
            //     @2，订单状态改为：true
            //     @3，从订单资金分配关联表，获取总资金，以及3方的各自分配信息
            //     @4，根据笔记的id，获取笔记的一些数据信息，比如：所有者的id
            //     @5，更新付费用户的数据，如：收益账户扣除余额，记录历史等
            //     @6，更新笔记的数据
            //     @7，更新笔记所有者的信息，如：收益账户增加收入
            //     @8，进行下载进程
            //     @9，如果@1的状态为false，那么则提示支付错误信息
            // 5，支付宝的异步通知接口，我们暂时不做处理，返回'success'

            // test ...
            // wx_echoxml($order_pay);
            // wx_echoxml($order_platform);
            // return false;

            $pay_ret = $this->wxm_pay_download->create_order($order_pay);   // step 1
            if ($pay_ret) {
                $platform_ret = $this->wxm_pay_platform->create_order($order_platform);     // step 2
                if ($platform_ret) {
                    echo '您的订单已生效，即将进入【支付宝】官方收银台，请不要走开 。。。';
                    // 记录一下此订单，CI session Cookie
                    $pay_order = array(
                        'pay_trade_no' => $pay_trade_no,
                        'pay_subject' => $pay_subject,
                        'pay_body' => $pay_body,
                        'pay_show_url' => $pay_show_url,
                        'pay_diff_money' => $diff_money,
                        );
                    $this->session->set_userdata($pay_order);

                    $this->_alipay_submit($pay_trade_no, $pay_subject, $diff_money, $pay_body, $pay_show_url);
                    return true;
                }
            }
        }
        else {
            echo 'Request Pay Downloading Error !';
            return false;
        }
    }
/*****************************************************************************/
    // 余额支付，下载
    public function _direct_download_via_balance(   $data_id = 0,
                                                    $cur_user_id = 0) {
        // 1，根据id获取笔记信息
        // 2，记录下载用户的付费笔记历史，同时用户下载的次数+1
        // 3，增加笔记被下载的次数，+1
        // 4，增加笔记所有者的下载次数，+1
        // 5，浏览器下载笔记资料，判断是否存在VPS数据，没有的话，从OSS下载，然后更新VPS路径信息

        if ($data_id > 0 && $cur_user_id > 0) {
            $data_info = $this->wxm_data->get_download_info($data_id);
            if ($data_info) {
                // setp 1, get data base info
                $data_name = $data_info['data_name'];
                $data_objectname = $data_info['data_objectname'];
                $data_type = $data_info['data_type'];
                $data_own_user_id = $data_info['user_id'];
                $data_osspath = $data_info['data_osspath'];
                $data_vpspath = $data_info['data_vpspath'];

                // setp 1, get cur user's doanload info
                $pay_download_info = $this->wxm_user_activity->get_pay_download_info($cur_user_id);
                if ($pay_download_info) {
                    $cur_user_download_count = $pay_download_info['uactivity_downloadcount'];
                    $cur_user_download_history = $pay_download_info['uactivity_pay_download'];

                    $cur_download_data = array(
                        'user_id' => $cur_user_id,
                        'uactivity_downloadcount' => $cur_user_download_count + 1,
                        'uactivity_pay_download' => '',
                        );

                    // update the download history
                    if ($cur_user_download_history) {
                        $history_data_list = explode(',', $cur_user_download_history);
                        if (! in_array($data_id, $history_data_list)) {
                            $history_data_list[] = $data_id;
                            $new_history_str = implode(',', $history_data_list);
                            $cur_download_data['uactivity_pay_download'] = $new_history_str;
                        }
                        else {
                            $cur_download_data['uactivity_pay_download'] = $cur_user_download_history;
                        }
                    }
                    else {
                        $cur_download_data['uactivity_pay_download'] = $data_id;
                    }
                    // setp 2, record the cur user's download new info
                    $ret_cur_pay = $this->wxm_user_activity->update_pay_download_info($cur_download_data);

                    // setp 3, add cur note download count +1
                    if ($data_id > 0) {
                        $activity_info = $this->wxm_data_activity->get_pay_download_info($data_id);
                        if ($activity_info) {
                            $download_count = $activity_info['dactivity_download_count'];
                            $pay_count = $activity_info['dactivity_buy_count'];
                            $download = array(
                                'data_id' => $data_id,
                                'dactivity_download_count' => $download_count + 1,
                                'dactivity_buy_count' => $pay_count + 1,
                                );
                            $this->wxm_data_activity->update_pay_download($download);
                        }
                        // setp 4, add data owner been downloaded count
                        if ($data_own_user_id > 0) {
                            $this->wxm_user_activity->add_been_download_count($data_own_user_id);
                        }
                    }

                    // step 5, prepare to load vps or oss data, download via web browser
                    $this->_require_download($data_id, $data_name, $data_objectname, $data_type, $data_osspath, $data_vpspath);
                    return true;
                }
            }
        }
        else {
            echo 'Download Error !';
            return false;
        }
    }
/*****************************************************************************/
    public function _require_download(  $data_id = 0,
                                        $data_name = '', $data_objectname = '',
                                        $data_type = 'doc', $data_osspath = '',
                                        $data_vpspath = '') {
        if (! $data_id > 0 || ! $data_name || ! $data_objectname) {
            echo 'Request Download Error !';
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
        else {
            echo 'Download Note Request Error !';
            return false;
        }
    }
/*****************************************************************************/
    public function _alipay_submit( $out_trade_no = '',
                                    $subject = '购买醍醐笔记',
                                    $total_fee = 0.01,
                                    $body = '醍醐笔记',
                                    $show_url = '') {
        // out_trade_no: 商户订单号, 商户网站订单系统中唯一订单号，必填
        // subject: 订单名称
                                    // total_fee: 付款金额
        // body: 订单描述
        // show_url: 商品展示地址, 需以http://开头的完整路径
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

        // echo '系统正在进入支付宝官方认证页面，请不要走开 。。。';
        $html_content = $this->wx_alipay_direct_api->alipay_submit($out_trade_no, $subject, $total_fee, $body, $show_url);
        echo $html_content;
    }
/*****************************************************************************/
    public function downloading_ok() {
        $this->load->view('share/wxv_loading_over');
    }
/*****************************************************************************/
    // 个人中心，查看付费下载订单的历史记录
    public function pay_order_history() {
        $cur_user_info = $this->wx_util->get_user_session_info();
        $cur_user_id = $cur_user_info['user_id'];

        if (! $cur_user_id) {
            echo json_encode('disconnected');
            return false;
        }

        $pay_download_history_info = $this->wxm_pay_download->get_all_history_by_userid($cur_user_id);
        if ($pay_download_history_info) {
            // wx_echoxml($pay_download_history_info);    // test ...
            $json_data = json_encode($pay_download_history_info);
            echo $json_data;
            return true;
        }
        else {
            // wx_loginfo(json_encode('no-record'));
            echo json_encode('no-record');
            return false;
        }
    }
/*****************************************************************************/
    // 个人中心，查看付费下载笔记的历史记录
    public function pay_download_history() {
        $cur_user_info = $this->wx_util->get_user_session_info();
        $cur_user_id = $cur_user_info['user_id'];

        if (! $cur_user_id) {
            echo json_encode('disconnected');
            return false;
        }

        $pay_download_note_info = $this->wxm_user_activity->get_payed_note_record($cur_user_id);
        if ($pay_download_note_info) {
            $pay_download_note_list = explode(',', $pay_download_note_info['uactivity_pay_download']);
            $behind_twenty = array();

            if (count($pay_download_note_list) > 20) {
                $behind_twenty = array_slice($pay_download_note_list, -20, 20);
            }
            else {
                $behind_twenty = $pay_download_note_list;
            }

            $note_list_result = $this->wxm_data->get_simple_info_by_id_list(array_unique(array_filter($behind_twenty)));
            if ($note_list_result) {
                // wx_echoxml($note_list_result);
                // process time stamp
                foreach ($note_list_result as $key => $value) {
                    $note_list_result[$key]['data_uploadtime'] = substr($value['data_uploadtime'], 0, 10);
                }
                $json_data = json_encode($note_list_result);
                echo $json_data;
                return true;
            }
        }
        echo json_encode('no-record');
        return false;
    }
/*****************************************************************************/
    // 个人中心，查看免费下载笔记的历史记录
    public function free_download_history() {
        $cur_user_info = $this->wx_util->get_user_session_info();
        $cur_user_id = $cur_user_info['user_id'];

        if (! $cur_user_id) {
            echo json_encode('disconnected');
            return false;
        }

        $free_download_note_info = $this->wxm_user_activity->get_free_note_record($cur_user_id);
        if ($free_download_note_info) {
            $free_download_note_list = explode(',', $free_download_note_info['uactivity_download']);
            $behind_twenty = array();

            if (count($free_download_note_list) > 20) {
                $behind_twenty = array_slice($free_download_note_list, -20, 20);
            }
            else {
                $behind_twenty = $free_download_note_list;
            }

            $note_list_result = $this->wxm_data->get_simple_info_by_id_list(array_filter($behind_twenty));
            if ($note_list_result) {
                // wx_echoxml($note_list_result);
                // process time stamp
                foreach ($note_list_result as $key => $value) {
                    $note_list_result[$key]['data_uploadtime'] = substr($value['data_uploadtime'], 0, 10);
                }
                $json_data = json_encode($note_list_result);
                echo $json_data;
                return true;
            }
        }

        echo json_encode('no-record');
        return false;
    }
/*****************************************************************************/
    // 个人中心，对已付费的笔记，永久免费下载
    public function download_have_payed_note() {
        $note_id = $this->input->get('note_id');

        // test ...
        // $note_id = 13;

        $cur_user_info = $this->wx_util->get_user_session_info();
        $cur_user_id = $cur_user_info['user_id'];

        if (! $cur_user_id) {
            echo 'disconnected';
            return false;
        }

        $have_payed_note_info = $this->wxm_user_activity->get_payed_note_record($cur_user_id);
        if ($have_payed_note_info) {
            $have_payed_note_list = explode(',', $have_payed_note_info['uactivity_pay_download']);
            // wx_echoxml($have_payed_note_list);
            if (in_array($note_id, $have_payed_note_list)) {
                // direct download this note
                $data_info = $this->wxm_data->get_download_info($note_id);
                if ($data_info) {
                    // get data base info
                    $data_id = $data_info['data_id'];
                    $data_name = $data_info['data_name'];
                    $data_objectname = $data_info['data_objectname'];
                    $data_type = $data_info['data_type'];
                    $data_price = $data_info['data_price'];
                    $data_own_user_id = $data_info['user_id'];
                    $data_osspath = $data_info['data_osspath'];
                    $data_vpspath = $data_info['data_vpspath'];

                    // update note download count of note itself
                    if ($data_id > 0) {
                        $activity_info = $this->wxm_data_activity->get_download_info($data_id);
                        if ($activity_info) {
                            $download_count = $activity_info['dactivity_download_count'];
                            $download = array(
                                'data_id' => $data_id,
                                'dactivity_download_count' => $download_count + 1,
                                );
                            $this->wxm_data_activity->update_download($download);
                        }
                        // add data owner been downloaded count
                        if ($data_own_user_id > 0) {
                            $this->wxm_user_activity->add_been_download_count($data_own_user_id);
                        }
                        if ($data_price == 0.00) {
                            // record free download count
                            $this->wx_site_manager->add_free_download_count();
                        }
                        else {
                            // record pay download count
                            $this->wx_site_manager->add_pay_download_count();
                        }
                    }

                    // download request process
                    $this->_require_download($data_id, $data_name, $data_objectname, $data_type, $data_osspath, $data_vpspath);
                    return true;
                }
            }
        }
        else {
            // echo 'no-record';  // 该笔记没有记录，或者不在已付费下载记录中，不可以直接永久下载
            $this->load->view('share/wxv_system_error');  // 进入系统错误页面
            return false;
        }
    }
/*****************************************************************************/
    public function test() {
        // $note_price = 3.99;
        // $creamnote_money = number_format($note_price * 0.2, 2, '.', '');
        // $this->wx_site_manager->add_site_income($creamnote_money);
        // wx_echoxml(wx_money_distribute($note_price));
    }
/*****************************************************************************/
}

/* End of file wxc_download_note.php */
/* Location: /application/frontend/controllers/wxc_download_note.php */
