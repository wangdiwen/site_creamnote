<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Admin_User extends CI_Model
{
    var $wx_table = 'wx_admin_user';
/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function get_all_admin_base_info() {
        $table = $this->wx_table;
        $this->db->select('user_id, user_name, user_email')->from($table);
        $query = $this->db->get();
        return $query->result_array();
    }
/*****************************************************************************/
    public function get_name_email_by_id($user_id = 0) {
        if ($user_id > 0) {
            $table = $this->wx_table;
            $this->db->select('user_name, user_email')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
        return false;
    }
/*****************************************************************************/
}

/* End of file wxm_admin_user.php */
/* Location: /application/frontend/models/core/wxm_admin_user.php */
