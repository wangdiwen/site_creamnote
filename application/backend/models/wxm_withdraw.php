<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Withdraw extends CI_Model {
    var $wx_table = 'wx_withdraw';
/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function get_page_withdraw_order($per_page_limit = '5', $offset = '10') {
        $table = $this->wx_table;
        $this->db->select('draw_id, draw_no, draw_user_id, draw_ali_account, draw_money,
                            draw_timestamp, draw_status, draw_admin, draw_admin_time')
                    ->where('draw_status', 'false')->order_by('draw_timestamp', 'desc');
        $query = $this->db->get($table, $per_page_limit, $offset);
        return $query->result_array();
    }
/*****************************************************************************/
    public function withdraw_count() {
        $table = $this->wx_table;
        $this->db->select('draw_id')->from($table)->where('draw_status', 'false');
        $query = $this->db->get();
        return $query->num_rows();
    }
/*****************************************************************************/
    public function get_draw_user_info($draw_no = '') {
        if ($draw_no) {
            $table = $this->wx_table;
            $this->db->select('draw_id, draw_user_id, draw_ali_account, draw_money, draw_timestamp')
                        ->from($table)->where('draw_no', $draw_no)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
        return false;
    }
/*****************************************************************************/
    public function check_withdraw_only_by_user_id($draw_user_id = 0) {
        if ($draw_user_id > 0) {
            $table = $this->wx_table;
            $where = array(
                'draw_status' => 'false',
                'draw_user_id' => $draw_user_id,
                );
            $this->db->select('draw_id')->from($table)->where($where);
            $query = $this->db->get();
            return $query->num_rows();
        }
        return 2;  // 非法，返回2，表示此用户的未处理提现订单，不是唯一
    }
/*****************************************************************************/
    public function change_order_status_done($draw_no = '', $admin_name = '', $admin_time = '') {
        if ($draw_no) {
            $table = $this->wx_table;
            $data = array(
                'draw_status' => 'true',
                'draw_admin' => $admin_name,
                'draw_admin_time' => $admin_time,
                );
            $this->db->where('draw_no', $draw_no);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
}

/* End of file wxm_withdraw.php */
/* Location: /application/backend/models/wxm_withdraw.php */
