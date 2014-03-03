<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_CI_Session_api extends CI_Model
{
    var $wx_table = 'ci_sessions';
/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function check_has_session_id($session_id = '') {
        if ($session_id) {
            $table = $this->wx_table;
            $this->db->select('session_id')->from($table)->where('session_id', $session_id);
            $query = $this->db->get();
            $count = $query->num_rows();
            if ($count > 0) {
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
}

/* End of file wxm_ci_session_api.php */
/* Location: /application/frontend/models/openapi/wxm_ci_session_api.php */
