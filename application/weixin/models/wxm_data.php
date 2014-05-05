<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Data extends CI_Model
{
    var $wx_table = 'wx_data';
/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/

/*****************************************************************************/

/*****************************************************************************/
    public function search_by_name_like($name = '') {
        if ($name) {
            $table = $this->wx_table;
            $this->db->select('data_id, data_name, data_summary, data_price, data_keyword, data_uploadtime')
                    ->from($table)->like('data_name', $name, 'both')
                    ->where('data_status', '3')->limit(10)->order_by('data_uploadtime', 'desc');
            $query = $this->db->get();
            return $query->result_array();
        }
        return false;
    }
/*****************************************************************************/
    public function search_by_name_like_list($name_list = array()) {
        if ($name_list) {
            $table = $this->wx_table;
            $this->db->select('data_id, data_name, data_type, data_pagecount, data_price, data_uploadtime, data_tag');  # data_tag use ',' split
            $len = count($name_list);
            if ($len == 1) {
                $this->db->like('data_name', $name_list[0], 'both');
            }
            else {
                $this->db->like('data_name', $name_list[0], 'both');
                for ($i = 1; $i < $len; $i++) {
                    $this->db->or_like('data_name', $name_list[$i], 'both');
                }
            }
            $this->db->from($table)->where('data_status', '3')->limit(10)->order_by('data_uploadtime', 'desc');
            $query = $this->db->get();
            return $query->result_array();
        }
        return false;
    }
/*****************************************************************************/
}

/* End of file wxm_data.php */
/* Location: /application/weixin/models/wxm_data.php */
