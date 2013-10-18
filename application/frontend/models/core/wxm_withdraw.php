<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Withdraw extends CI_Model
{
    var $wx_table = 'wx_withdraw';
/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function create_order($info = array()) {
        $draw_no = $info['draw_no'];
        $draw_user_id = $info['draw_user_id'];
        $draw_ali_account = $info['draw_ali_account'];
        $draw_money = $info['draw_money'];
        $draw_timestamp = $info['draw_timestamp'];
        $draw_status = $info['draw_status'];
        $draw_admin = $info['draw_admin'];
        $draw_admin_time = $info['draw_admin_time'];
        if ($draw_no && $draw_user_id > 0 && $draw_ali_account && $draw_money) {
            $data = array(
                'draw_no' => $draw_no,
                'draw_user_id' => $draw_user_id,
                'draw_ali_account' => $draw_ali_account,
                'draw_money' => $draw_money,
                'draw_timestamp' => $draw_timestamp,
                'draw_status' => $draw_status,
                'draw_admin' => $draw_admin,
                'draw_admin_time' => $draw_admin_time,
                );
            $table = $this->wx_table;
            $this->db->insert($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/

/*****************************************************************************/
}

/* End of file wxm_withdraw.php */
/* Location: /application/frontend/models/core/wxm_withdraw.php */
