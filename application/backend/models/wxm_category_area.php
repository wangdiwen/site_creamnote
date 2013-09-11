<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Category_area extends CI_Model
{
    var $wx_table = 'wx_category_area';

/*****************************************************************************/
    public function __construct()
    {
        $this->load->database();
    }
/*****************************************************************************/
    public function get_name_by_id($carea_id = 0)
    {
        if ($carea_id > 0) {
            $table = $this->wx_table;
            $this->db->select('carea_name')->from($table)->where('carea_id', $carea_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
}

/* End of file wxm_admin_user.php */
/* Location: /application/backend/models/wxm_admin_user.php */
