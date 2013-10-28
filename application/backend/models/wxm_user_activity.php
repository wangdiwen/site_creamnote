<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_User_Activity extends CI_Model
{
    var $wx_table = 'wx_user_activity';

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
            $this->db->select('uactivity_datacount, uactivity_downloadcount, uactivity_downloaded_count,
                                uactivity_loginip, uactivity_logintime, uactivity_logouttime, uactivity_status,
                                uactivity_logincount, uactivity_level')
                        ->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
}

/* End of file wxm_admin_user.php */
/* Location: /application/backend/models/wxm_admin_user.php */
