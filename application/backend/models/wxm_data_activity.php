<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Data_Activity extends CI_Model
{
    var $wx_table = 'wx_data_activity';
/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function update_examine_point($data_id = 0, $examine_point = 50) {
        if ($data_id > 0) {
            $data = array(
                'dactivity_examine_count' => $examine_point,
                );
            $table = $this->wx_table;
            $this->db->where('data_id', $data_id);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/

/*****************************************************************************/
}

/* End of file wxm_data_activity.php */
/* Location: /application/backend/models/wxm_data_activity.php */
