<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Site_Manager extends CI_Model {
    var $wx_table = 'wx_site_manager';

/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function insert_new($info = array()) {
        if ($info) {
            $table = $this->wx_table;
            $data = array(
                'site_date' => $info['site_date'],
                'site_users' => $info['site_users'],
                'site_note_count' => $info['site_note_count'],
                'site_upload_count' => $info['site_upload_count'],
                'site_imagenote_count' => $info['site_imagenote_count'],
                'site_freedown_count' => $info['site_freedown_count'],
                'site_paydown_count' => $info['site_paydown_count'],
                'site_download_count' => $info['site_download_count'],
                'site_total_income' => $info['site_total_income']
                );
            $this->db->insert($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function get_by_date($year_month = '') {
        if ($year_month) {
            $table = $this->wx_table;
            $this->db->select('site_id, site_date, site_users, site_note_count, site_upload_count, site_imagenote_count, site_freedown_count, site_paydown_count, site_download_count, site_total_income')->from($table)->where('site_date', $year_month)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function has_by_date($year_month = '') {
        if ($year_month) {
            $table = $this->wx_table;
            $this->db->select('site_id')->from($table)->where('site_date', $year_month)->limit(1);
            $query = $this->db->get();
            $count = $query->num_rows();
            if ($count)
                return true;
        }
        return false;
    }
/*****************************************************************************/
    public function get_page_info($per_page_limit = 5, $offset = 10) {
        $table = $this->wx_table;
        $this->db->select('site_id, site_date, site_users, site_note_count, site_upload_count, site_imagenote_count, site_freedown_count, site_paydown_count, site_download_count, site_total_income')->order_by('site_date', 'desc');
        $query = $this->db->get($table, $per_page_limit, $offset);
        return $query->result_array();
    }
/*****************************************************************************/
    public function site_count() {
        $table = $this->wx_table;
        $count = $this->db->count_all($table);
        return $count;
    }
/*****************************************************************************/
    public function query_any_month($start_month ='', $end_month = '') {
        $table = $this->wx_table;
        $where = array(
            'site_date >=' => $start_month,
            'site_date <=' => $end_month
            );
        $this->db->select('site_id, site_date, site_users, site_note_count, site_upload_count, site_imagenote_count, site_freedown_count, site_paydown_count, site_download_count, site_total_income')->from($table)->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }
/*****************************************************************************/
/*****************************************************************************/
/*****************************************************************************/
/*****************************************************************************/
}

/* End of file wxm_site_manager.php */
/* Location: /application/backend/models/wxm_site_manager.php */
