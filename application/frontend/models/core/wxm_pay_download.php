<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Pay_Download extends CI_Model
{
    var $wx_table = 'wx_pay_download';
/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function create_order($info = array()) {
        if ($info) {
            $table = $this->wx_table;
            $data = array(
                'pay_user_id' => $info['pay_user_id'],
                'pay_total_fee' => $info['pay_total_fee'],
                'pay_status' => $info['pay_status'],
                'pay_way' => $info['pay_way'],
                'pay_trade_no' => $info['pay_trade_no'],
                'pay_alipay_no' => $info['pay_alipay_no'],
                'pay_subject' => $info['pay_subject'],
                'pay_body' => $info['pay_body'],
                'pay_show_url' => $info['pay_show_url'],
                'pay_timestamp' => $info['pay_timestamp'],
                );
            $this->db->insert($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function get_order_by_trade_no($trade_no = '') {
        if ($trade_no) {
            $table = $this->wx_table;
            $this->db->select('pay_id, pay_user_id, pay_show_url')->from($table)->where('pay_trade_no', $trade_no)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
        return false;
    }
/*****************************************************************************/
    public function change_order_status_invalid($trade_no = '', $alipay_trade_no = '') {
        if ($trade_no && $alipay_trade_no) {
            $table = $this->wx_table;
            $data = array(
                'pay_status' => 'true',
                'pay_alipay_no' => $alipay_trade_no,
                );
            $this->db->where('pay_trade_no', $trade_no);
            $this->db->update($table, $data);

            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function check_by_userid_and_showurl($pay_user_id = 0, $pay_show_url = '') {
        if ($pay_user_id && $pay_show_url) {
            $table = $this->wx_table;
            $where = array(
                'pay_user_id' => $pay_user_id,
                'pay_show_url' => $pay_show_url,
                );
            $this->db->select('pay_id, pay_status')->from($table)->where($where)->order_by('pay_trade_no', 'desc');
            $query = $this->db->get();
            $result = $query->result_array();
            if ($result) {
                // get the first item
                $first_order = $result[0];
                $pay_status = $first_order['pay_status'];
                if ($pay_status == 'true') {
                    return true;
                }
            }
        }
        return false;
    }
/*****************************************************************************/
    // 查看用户付费订单历史
    public function get_all_history_by_userid($pay_user_id = 0) {
        if ($pay_user_id > 0) {
            $table = $this->wx_table;
            $this->db->select('pay_id, pay_total_fee, pay_status, pay_way, pay_trade_no, pay_subject, pay_body, pay_show_url, pay_timestamp')
                    ->from($table)->where('pay_user_id', $pay_user_id)->limit(20)->order_by('pay_timestamp', 'desc');
            $query = $this->db->get();
            return $query->result_array();
        }
        return false;
    }
/*****************************************************************************/
}

/* End of file wxm_pay_download.php */
/* Location: /application/frontend/models/core/wxm_pay_download.php */
