<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Report extends CI_Model
{
    var $wx_table = 'wx_report';
/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function get_step_one_count() {
        $table = $this->wx_table;
        $where = array(
            'com_status' => 'false',
            'com_step' => '1',
            );
        $this->db->select('com_id')->from($table)->where($where);
        $query = $this->db->get();
        return $query->num_rows();
    }
/*****************************************************************************/
    public function get_step_one_page($per_page_limit = 5, $offset = 10) {
        $table = $this->wx_table;
        $where = array(
            'com_status' => 'false',
            'com_step' => '1',
            );
        $this->db->select('com_id, com_title, com_link, com_note_name, com_user_email, com_user_phone, com_time, com_admin_user, com_result, com_describe')->where($where)->order_by('com_time', 'desc');
        $query = $this->db->get($table, $per_page_limit, $offset);
        return $query->result_array();
    }
/*****************************************************************************/
    public function get_step_second_count() {
        $table = $this->wx_table;
        $where = array(
            'com_status' => 'false',
            'com_step' => '2',
            );
        $this->db->select('com_id')->from($table)->where($where);
        $query = $this->db->get();
        return $query->num_rows();
    }
/*****************************************************************************/
    public function get_step_second_page($per_page_limit = 5, $offset = 10) {
        $table = $this->wx_table;
        $where = array(
            'com_status' => 'false',
            'com_step' => '2',
            );
        $this->db->select('com_id, com_title, com_link, com_note_name, com_user_email, com_user_phone, com_time, com_admin_user, com_result, com_describe')->where($where)->order_by('com_time', 'desc');
        $query = $this->db->get($table, $per_page_limit, $offset);
        return $query->result_array();
    }
/*****************************************************************************/
    public function get_step_third_count() {
        $table = $this->wx_table;
        $where = array(
            'com_status' => 'false',
            'com_step' => '3',
            );
        $this->db->select('com_id')->from($table)->where($where);
        $query = $this->db->get();
        return $query->num_rows();
    }
/*****************************************************************************/
    public function get_step_third_page($per_page_limit = 5, $offset = 10) {
        $table = $this->wx_table;
        $where = array(
            'com_status' => 'false',
            'com_step' => '3',
            );
        $this->db->select('com_id, com_title, com_link, com_note_name, com_user_email, com_user_phone, com_time, com_admin_user, com_result, com_describe')->where($where)->order_by('com_time', 'desc');
        $query = $this->db->get($table, $per_page_limit, $offset);
        return $query->result_array();
    }
/*****************************************************************************/
    public function get_disposed_count() {
        $table = $this->wx_table;
        $where = array(
            'com_status' => 'true',
            'com_step' => '3',
            );
        $this->db->select('com_id')->from($table)->where($where);
        return $this->db->count_all_results();
    }
/*****************************************************************************/
    public function get_undisposed_count() {
        $table = $this->wx_table;
        $where = array(
            'com_status' => 'false',
            );
        $this->db->select('com_id')->from($table)->where($where);
        return $this->db->count_all_results();
    }
/*****************************************************************************/
    public function get_total_count() {
        $table = $this->wx_table;
        return $this->db->count_all($table);
    }
/*****************************************************************************/
    public function query_report_result($com_id = 0) {
        if ($com_id > 0) {
            $table = $this->wx_table;
            $where = array(
                'com_id' => $com_id,
                );
            $this->db->select('com_id, com_result')->from($table)->where($where)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
/************************ Process handle *************************************/
/*****************************************************************************/
/*****************************************************************************/
    public function report_pass($com_id = 0, $com_admin_user = '', $com_result = '') {
        if ($com_id > 0 && $com_admin_user && $com_result) {
            $table = $this->wx_table;
            $data = array(
                'com_admin_user' => $com_admin_user,
                'com_result' => $com_result,
                'com_status' => 'true',
                );
            $this->db->where('com_id', $com_id);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function just_pass_report($com_id = 0, $com_admin_user = '') {
        if ($com_id > 0 && $com_admin_user) {
            $table = $this->wx_table;
            $data = array(
                'com_admin_user' => $com_admin_user,
                'com_status' => 'true',
                );
            $this->db->where('com_id', $com_id);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function change_step_two($com_id = 0, $com_admin_user = '', $com_result = '') {
        if ($com_id > 0 && $com_admin_user && $com_result) {
            $table = $this->wx_table;
            $data = array(
                'com_admin_user' => $com_admin_user,
                'com_result' => $com_result,
                'com_step' => '2',
                );
            $this->db->where('com_id', $com_id);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function change_step_three($com_id = 0, $com_admin_user = '', $com_result = '') {
        if ($com_id > 0 && $com_admin_user && $com_result) {
            $table = $this->wx_table;
            $data = array(
                'com_admin_user' => $com_admin_user,
                'com_result' => $com_result,
                'com_step' => '3',
                );
            $this->db->where('com_id', $com_id);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function complete_report($com_id = 0, $com_admin_user = '', $com_result = '') {
        if ($com_id > 0 && $com_admin_user && $com_result) {
            $table = $this->wx_table;
            $data = array(
                'com_admin_user' => $com_admin_user,
                'com_result' => $com_result,
                'com_status' => 'true',
                );
            $this->db->where('com_id', $com_id);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
/*****************************************************************************/
/*****************************************************************************/
}

/* End of file wxm_report.php */
/* Location: /application/backend/models/wxm_report.php */
