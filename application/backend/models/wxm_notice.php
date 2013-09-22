<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Notice extends CI_Model
{
    var $wx_table = 'wx_notice';

/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function get_by_id($notice_id = 0) {
        if ($notice_id > 0) {
            $table = $this->wx_table;
            $this->db->select('notice_id, notice_title, notice_content_url, notice_time, notice_status')
                ->from($table)->where('notice_id', $notice_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function update_notice($info = array()) {
        if ($info) {
            $notice_id = $info['notice_id'];
            $data = array(
                'notice_title' => $info['notice_title']
                );
            $table = $this->wx_table;
            $this->db->where('notice_id', $notice_id);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function enable_notice($notice_id = 0) {
        if ($notice_id > 0) {
            $has_notice = $this->has_such_notice($notice_id);
            if ($has_notice) {
                $table = $this->wx_table;
                $data = array(
                    'notice_status' => 'true'
                    );
                $this->db->where('notice_id', $notice_id);
                $this->db->update($table, $data);
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function disable_notice($notice_id = 0) {
        if ($notice_id > 0) {
            $has_notice = $this->has_such_notice($notice_id);
            if ($has_notice) {
                $table = $this->wx_table;
                $data = array(
                    'notice_status' => 'false'
                    );
                $this->db->where('notice_id', $notice_id);
                $this->db->update($table, $data);
                return true;
            }
        }
        return false;
    }
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
    public function delete_notice($notice_id = 0) {
        if ($notice_id > 0) {
            $table = $this->wx_table;
            $this->db->where('notice_id', $notice_id);
            $this->db->delete($table);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function create_notice($info = array()) {
        if ($info) {
            $data = array(
                'notice_title' => $info['notice_title'],
                'notice_content_url' => $info['notice_content_url'],
                'notice_time' => $info['notice_time']
                );
            $table = $this->wx_table;
            $this->db->insert($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function notice_count() {
        $table = $this->wx_table;
        $count = $this->db->count_all($table);
        return $count;
    }
/*****************************************************************************/
    public function get_page_notice($per_page_limit = 5, $offset = 10) {
        $table = $this->wx_table;
        $this->db->select('notice_id, notice_title, notice_time, notice_status')->order_by('notice_time', 'desc');
        $query = $this->db->get($table, $per_page_limit, $offset);
        return $query->result_array();
    }
/*****************************************************************************/
    public function get_content_url($notice_id = 0) {
        if ($notice_id > 0) {
            $table = $this->wx_table;
            $this->db->select('notice_id, notice_content_url')->from($table)->where('notice_id', $notice_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
}

/* End of file wxm_notice.php */
/* Location: /application/backend/models/wxm_notice.php */
