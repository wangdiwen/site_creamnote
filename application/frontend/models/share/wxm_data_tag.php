<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Data_Tag extends CI_Model
{
    var $wx_table = 'wx_data_tag';
/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function fetch_data_tag($tag_keyword = '') {
        if ($tag_keyword) {
            $table = $this->wx_table;
            $this->db->select('tag_id, tag_name')->from($table)->like('tag_name', $tag_keyword)->limit(5);
            $query = $this->db->get();
            return $query->result_array();
        }
        return false;
    }
/*****************************************************************************/
    public function has_such_tag($tag_name = '') {
        if ($tag_name) {
            $table = $this->wx_table;
            $this->db->select('tag_id')->from($table)->where('tag_name', $tag_name)->limit(1);
            $query = $this->db->get();
            $count = $query->num_rows();
            if ($count > 0) {
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function add_new_tag($tag_name = '') {
        if ($tag_name) {
            $data = array(
                'tag_name' => $tag_name,
                );
            $table = $this->wx_table;
            $this->db->insert($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
/*****************************************************************************/
/*****************************************************************************/
}

/* End of file wxm_data_tag.php */
/* Location: ./application/frontend/models/share/wxm_data_tag.php */
