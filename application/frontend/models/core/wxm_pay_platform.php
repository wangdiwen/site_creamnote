<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Pay_Platform extends CI_Model
{
    var $wx_table = 'wx_pay_platform';
/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function create_order($info = array()) {
        if ($info) {
            $table = $this->wx_table;
            $data = array(
                'plat_trade_no' => $info['plat_trade_no'],
                'plat_total_fee' => $info['plat_total_fee'],
                'plat_creamnote' => $info['plat_creamnote'],
                'plat_zhifubao' => $info['plat_zhifubao'],
                'plat_owner' => $info['plat_owner'],
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
            $this->db->select('plat_id, plat_total_fee, plat_creamnote, plat_zhifubao, plat_owner')->from($table)->where('plat_trade_no', $trade_no)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
        return false;
    }
/*****************************************************************************/
}

/* End of file wxm_pay_platform.php */
/* Location: /application/frontend/models/core/wxm_pay_platform.php */
