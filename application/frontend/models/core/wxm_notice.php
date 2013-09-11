<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Notice extends CI_Model
{
    var $wx_table = 'wx_notice';

/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function get_site_notice() {
        $table = $this->wx_table;
        $this->db->select('notice_id, notice_title, notice_time')->from($table)->where('notice_status', 'true')->limit(4)->order_by('notice_time', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }
/*****************************************************************************/
    public function get_by_id($notice_id = 0) {
        $has_notice = $this->has_such_notice($notice_id);
        if ($has_notice) {
            $table = $this->wx_table;
            $this->db->select('notice_id, notice_title, notice_content_url, notice_time')->from($table)->where('notice_id', $notice_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function notice_count() {
        $table = $this->wx_table;
        $this->db->select('notice_id')->from($table)->where('notice_status', 'true');
        $query = $this->db->get();
        $count = $query->num_rows();
        return $count;
    }
/*****************************************************************************/
    public function get_page_notice($per_page_limit = 5, $offset = 10) {
        $table = $this->wx_table;
        $this->db->select('notice_id, notice_title, notice_time')->where('notice_status', 'true')->order_by('notice_time', 'desc');
        $query = $this->db->get($table, $per_page_limit, $offset);
        return $query->result_array();
    }
/*****************************************************************************/
/*****************************************************************************/
    public function has_such_notice($notice_id = 0) {
        if ($notice_id > 0) {
            $table = $this->wx_table;
            $this->db->select('notice_id')->from($table)->where('notice_id', $notice_id)->limit(1);
            $query = $this->db->get();
            $count = $query->num_rows();
            if ($count)
                return true;
        }
        return false;
    }
/*****************************************************************************/
}

/* End of file wxm_notice.php */
/* Location: /application/frontend/models/wxm_notice.php */
