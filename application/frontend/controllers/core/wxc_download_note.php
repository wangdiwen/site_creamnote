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

        // $this->load->library('wx_general');
        $this->load->library('wx_util');
        $this->load->library('wx_aliossapi');
        $this->load->library('wx_site_manager');
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
                        // can download free note 3 count everyday
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
                if ($data_vpspath) {
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
                $file_data = file_get_contents($file_url);
                $file_len = strlen($file_data)/1024;
                $file_name = $data_name.'.'.$data_type;
                $this->output->set_header("Content-type: application/octet-stream");
                $this->output->set_header("Accept-Ranges: bytes");
                $this->output->set_header("Content-type: application/force-download; charset=utf-8");
                $this->output->set_header("Content-Length: ".$file_len);
                if ($file_name && $file_data) {
                    // check user browser
                    $agent_info = $this->agent->agent_string();
                    if (strpos($agent_info, 'MSIE')) {   // solve chinese mess word
                        force_download(urlencode($file_name), $file_data);
                    }
                    else {
                        force_download($file_name, $file_data);
                    }
                }
            }
            else {  // pay
                // redirect to shou-yin-tai page
                $user_account_money = 0.00;
                $user_account_money_info = $this->wxm_user->get_user_account_money($cur_user_id);
                if ($user_account_money_info) {
                    $user_account_money = $user_account_money_info['user_account_money'];
                }

                $diff_money = 0.00;
                if ($data_price > $user_account_money) {
                    $diff_money = $data_price - $user_account_money;
                }

                $pay_data = array(
                    'note_id' => $data_id,
                    'note_name' => $data_name.'.'.$data_type,
                    'note_price' => $data_price,
                    'user_account_money' => $user_account_money,
                    'diff_money' => $diff_money,  // 差额：当价格大于余额的时候，价格减去用户收益余额
                    );
                // wx_echoxml($pay_data);
                $this->load->view('trade/wxv_accountselect_page', $pay_data);  // Home page
            }
        }
    }
/*****************************************************************************/
    public function pay_download_file() {
        $note_id = $this->input->post('note_id');
        $note_name = $this->input->post('note_name');
        $note_price = $this->input->post('note_price');
        $diff_money = $this->input->post('diff_money');

        // 生成付费订单，数据表2个：wx_pay_download, wx_pay_platform
        $cur_user_info = $this->wx_util->get_user_session_info();

        $pay_user_id = $cur_user_info['user_id'];
        if ($pay_user_id == 0) {
            echo 'disconnected';  // lost connection
            return false;
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

        if ($pay_way == '0') {      // 直接下载，从收益账户余额账户扣除笔记价格

        }
        else {                      // 跳转到支付宝付费页面

        }


        // record pay download count
        // $this->wx_site_manager->add_pay_download_count();
    }
/*****************************************************************************/
    public function test() {
        $msg = 'http://www.creamnote.com/data/wxc_data/data_view/11';
        $len = strlen($msg);
        echo $len;
        // $price = 2.99;
        // $account = 0.00;
        // $diff = $price - $account;
        // echo $diff;
    }
/*****************************************************************************/
}

/* End of file wxc_download_note.php */
/* Location: /application/frontend/controllers/wxc_download_note.php */
