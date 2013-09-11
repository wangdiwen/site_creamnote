<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_User2carea extends CI_Model
{
    var $wx_table = 'wx_user2carea';

/*****************************************************************************/
    public function __construct()
    {
        $this->load->database();
    }
/*****************************************************************************/
    public function get_user_detail($user_id = 0)
    {
        if ($user_id > 0) {
            $table = $this->wx_table;
            $this->db->select('carea_id_major, carea_id_school')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
}

/* End of file wxm_admin_user.php */
/* Location: /application/backend/models/wxm_admin_user.php */
