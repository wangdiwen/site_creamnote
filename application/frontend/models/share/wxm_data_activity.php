<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Data_activity extends CI_Model
{
    var $wx_table = 'wx_data_activity';
/*****************************************************************************/
    public function __construct()
    {
        $this->load->database();
    }
/*****************************************************************************/
    public function insert($info)
    {
        $data_id = $info['data_id'];
        $dactivity_comment_count = $info['dactivity_comment_count'];
        $dactivity_point_count = $info['dactivity_point_count'];
        $dactivity_download_count = $info['dactivity_download_count'];
        $dactivity_view_count = $info['dactivity_view_count'];
        $dactivity_examine_count = $info['dactivity_examine_count'];
        $dactivity_buy_count = $info['dactivity_buy_count'];

        if ($data_id > 0)
        {
            $data = array(
                'data_id' => $data_id,
                'dactivity_comment_count' => $dactivity_comment_count,
                'dactivity_point_count' => $dactivity_point_count,
                'dactivity_download_count' => $dactivity_download_count,
                'dactivity_view_count' => $dactivity_view_count,
                'dactivity_examine_count' => $dactivity_examine_count,
                'dactivity_buy_count' => $dactivity_buy_count
                );
            $table = $this->wx_table;
            $this->db->insert($table, $data);
        }
    }
/*****************************************************************************/
    public function update_comment($info)
    {
        $data_id = $info['data_id'];
        $dactivity_comment_count = $info['dactivity_comment_count'];
        if ($data_id > 0)
        {
            $data = array(
                'dactivity_comment_count' => $dactivity_comment_count
                );
            $table = $this->wx_table;
            $this->db->where('data_id', $data_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function update_point($info)
    {
        $data_id = $info['data_id'];
        $dactivity_point_count = $info['dactivity_point_count'];
        if ($data_id > 0)
        {
            $data = array(
                'dactivity_point_count' => $dactivity_point_count
                );
            $table = $this->wx_table;
            $this->db->where('data_id', $data_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function update_data_lifetime($data_id = 0, $data_lifetime = '') {
        if ($data_id > 0 && $data_lifetime) {
            $data = array(
                'dactivity_lifetime' => $data_lifetime,
                );
            $table = $this->wx_table;
            $this->db->where('data_id', $data_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function update_download($info)
    {
        $data_id = $info['data_id'];
        $dactivity_download_count = $info['dactivity_download_count'];
        if ($data_id > 0)
        {
            $data = array(
                'dactivity_download_count' => $dactivity_download_count
                );
            $table = $this->wx_table;
            $this->db->where('data_id', $data_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function update_view($info)
    {
        $data_id = $info['data_id'];
        $dactivity_view_count = $info['dactivity_view_count'];
        if ($data_id > 0)
        {
            $data = array(
                'dactivity_view_count' => $dactivity_view_count
                );
            $table = $this->wx_table;
            $this->db->where('data_id', $data_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function update_examine($info)
    {
        $data_id = $info['data_id'];
        $dactivity_examine_count = $info['dactivity_examine_count'];
        if ($data_id > 0)
        {
            $data = array(
                'dactivity_examine_count' => $dactivity_examine_count
                );
            $table = $this->wx_table;
            $this->db->where('data_id', $data_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function update_buy($info)
    {
        $data_id = $info['data_id'];
        $dactivity_buy_count = $info['dactivity_buy_count'];
        if ($data_id > 0)
        {
            $data = array(
                'dactivity_buy_count' => $dactivity_buy_count
                );
            $table = $this->wx_table;
            $this->db->where('data_id', $data_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function get_by_data_id($data_id = 0)
    {
        if ($data_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('dactivity_id, dactivity_comment_count, dactivity_download_count,
                dactivity_view_count, dactivity_buy_count, dactivity_point_count, dactivity_examine_count')
                ->from($table)->where('data_id', $data_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function get_download_view_count($data_id = 0)
    {
        if ($data_id > 0) {
            $table = $this->wx_table;
            $this->db->select('dactivity_id, dactivity_download_count, dactivity_view_count')
                    ->from($table)->where('data_id', $data_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function get_comment_count($data_id = 0) {
        if ($data_id > 0) {
            $table = $this->wx_table;
            $this->db->select('dactivity_id, dactivity_comment_count')
                    ->from($table)->where('data_id', $data_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
        return false;
    }
/*****************************************************************************/
    public function get_download_info($data_id = 0) {
        if ($data_id > 0) {
            $table = $this->wx_table;
            $this->db->select('dactivity_id, dactivity_download_count')->from($table)->where('data_id', $data_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function delete_by_data_id($data_id = 0)
    {
        if ($data_id > 0)
        {
            if ($data_id > 0)
            {
                $table = $this->wx_table;
                $where = array(
                    'data_id' => $data_id
                    );
                $this->db->where($where);
                $this->db->delete($table);
            }
        }
    }
/*****************************************************************************/
}

/* End of file wxm_data_activity.php */
/* Location: ./application/models/share/wxm_data_activity.php */
