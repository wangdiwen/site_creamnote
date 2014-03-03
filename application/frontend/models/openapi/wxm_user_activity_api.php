<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_User_activity_api extends CI_Model
{
    var $wx_table = 'wx_user_activity';
/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function update_user_ci_session($user_id = 0, $ci_session = '') {
        if ($user_id > 0 && $ci_session) {
            $table = $this->wx_table;
            $data = array(
                'uactivity_ci_session' => $ci_session,
                );
            $this->db->where('user_id', $user_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function get_ci_session($user_id = 0) {
        if ($user_id > 0) {
            $table = $this->wx_table;
            $this->db->select('uactivity_ci_session')->from($table)->where('user_id', $user_id);
            $query = $this->db->get();
            return $query->row_array();
        }
        return false;
    }
/*****************************************************************************/
/*****************************************************************************/
}

/* End of file wxm_user_activity_api.php */
/* Location: /application/frontend/models/openapi/wxm_user_activity_api.php */
