<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Category_nature extends CI_Model
{
    var $wx_table = 'wx_category_nature';
/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function get_nature_tag($nature_id = 0) {
        if ($nature_id > 0) {
            $table = $this->wx_table;
            $this->db->select('cnature_id, cnature_tag')
                    ->from($table)->where('cnature_id', $nature_id);
            $query = $this->db->get();
            return $query->row_array();
        }
        return false;
    }
/*****************************************************************************/
}

/* End of file wxm_category_nature.php */
/* Location: ./application/frontend/models/openapi/wxm_category_nature.php */
