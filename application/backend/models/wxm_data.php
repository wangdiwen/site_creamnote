<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Data extends CI_Model
{
    var $wx_table = 'wx_data';
/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function note_count() {
        $table = $this->wx_table;
        $this->db->select('data_id')->from($table)->where('data_status', '1')->order_by('data_uploadtime', 'desc');
        $query = $this->db->get();
        $count = $query->num_rows();
        return $count;
    }
/*****************************************************************************/
    public function get_note_page($per_page_limit = 5, $offset = 10) {
        $table = $this->wx_table;
        $this->db->select('data_id, data_name, data_type, data_pagecount, data_price, user_id, data_uploadtime, data_osspath, data_vpspath')->where('data_status', '1')->order_by('data_uploadtime', 'desc');
        $query = $this->db->get($table, $per_page_limit, $offset);
        return $query->result_array();
    }
/*****************************************************************************/
    public function pass_audit($data_id = 0) {
        if ($data_id > 0) {
            $table = $this->wx_table;
            $data = array(
                'data_status' => '3'
                );
            $this->db->where('data_id', $data_id);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function unpass_audit($data_id = 0) {
        if ($data_id > 0) {
            $table = $this->wx_table;
            $data = array(
                'data_status' => '2'
                );
            $this->db->where('data_id', $data_id);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function get_storage_info($data_id = 0) {
        if ($data_id > 0) {
            $table = $this->wx_table;
            $this->db->select('data_id, data_objectname, data_osspath, data_vpspath')
                    ->from($table)->where('data_id', $data_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function query_good_data_any_time($start_time = '', $end_time = '') {
        if ($start_time && $end_time) {
            $table = $this->wx_table;
            $where = array(
                'data_uploadtime >=' => $start_time,
                'data_uploadtime <=' => $end_time,
                'data_isgood' => 'true'
                );
            $this->db->select('data_id, data_name, data_type, data_uploadtime')->from($table)->where($where)->order_by('data_uploadtime', 'desc');
            $query = $this->db->get();
            return $query->result_array();
        }
    }
/*****************************************************************************/
    public function mark_good($data_id = 0) {
        if ($data_id > 0) {
            $table = $this->wx_table;
            $data = array(
                'data_isgood' => 'true'
                );
            $this->db->where('data_id', $data_id);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function filter_note_by_id_list($data_id_list = array()) {
        if ($data_id_list) {
            $table = $this->wx_table;
            $this->db->select('data_id, data_name, data_type, data_pagecount, data_price, data_uploadtime, data_keyword')->from($table)->where('data_status', '3');
            $this->db->where_in('data_id', $data_id_list);
            $query = $this->db->get();
            return $query->result_array();
        }
    }
/*****************************************************************************/
    public function query_data_detail($data_id = 0) {
        if ($data_id > 0) {
            $table = $this->wx_table;
            $this->db->select('data_id, data_name, data_type, data_uploadtime')->from($table)->where('data_id', $data_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function get_data_user_id($data_id = 0) {
        if ($data_id > 0) {
            $table = $this->wx_table;
            $this->db->select('data_id, user_id')->from($table)->where('data_id', $data_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function get_download_info($data_id = 0) {
        if ($data_id > 0) {
            $table = $this->wx_table;
            $this->db->select('data_id, data_name, data_objectname, data_type, data_price, user_id, data_osspath, data_vpspath')->from($table)->where('data_id', $data_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function check_not_pass($data_id = 0) {
        if ($data_id > 0) {
            $table = $this->wx_table;
            $data = array(
                'data_status' => '2',
                );
            $this->db->where('data_id', $data_id);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
}

/* End of file wxm_data.php */
/* Location: /application/backend/models/wxm_data.php */
