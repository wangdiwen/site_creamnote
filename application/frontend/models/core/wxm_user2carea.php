<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_User2carea extends CI_Model
{
    var $wx_table = 'wx_user2carea';

/*****************************************************************************/
    public function __construct()
    {
        $this->load->database();
    }
/*****************************************************************************/
    public function insert($info)
    {
        if ($info)
        {
            $carea_id_major = $info['carea_id_major'];
            $carea_id_school = $info['carea_id_school'];
            $user_id = $info['user_id'];

            if ($user_id > 0)
            {
                $data = array(
                    'carea_id_major' => $carea_id_major,
                    'carea_id_school' => $carea_id_school,
                    'user_id' => $user_id
                    );
                $table = $this->wx_table;
                $this->db->insert($table, $data);
            }

        }
    }
/*****************************************************************************/
    public function delete_by_user_id($user_id = 0)
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $this->db->where('user_id', $user_id);
            $this->db->delete($table);
        }
    }
/*****************************************************************************/
    public function has_user_area_info($user_id = 0) {
        $table = $this->wx_table;
        $this->db->select('carea_id_school')->from($table)->where('user_id', $user_id)->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function get_by_user_id($user_id = 0)
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('carea_id_major, carea_id_school')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function update_school_major($info)
    {
        if ($info)
        {
            $user_id = $info['user_id'];
            $carea_id_major = $info['carea_id_major'];
            $carea_id_school = $info['carea_id_school'];
            if ($user_id > 0)
            {
                $data = array(
                    'carea_id_major' => $carea_id_major,
                    'carea_id_school' => $carea_id_school
                    );
                $table = $this->wx_table;
                $this->db->where('user_id', $user_id);
                $this->db->update($table, $data);
            }
        }
    }
/*****************************************************************************/
    public function get_user_id_list_by_school_id_list($school_id_list = array()) {
        if ($school_id_list) {
            $table = $this->wx_table;
            $this->db->select('user_id')->from($table)->where_in('carea_id_school', $school_id_list)->limit(10);
            $query = $this->db->get();
            return $query->result_array();
        }
        return false;
    }
/*****************************************************************************/
}

/* End of file wxm_user2carea.php */
/* Location: ./application/models/core/wxm_user2area.php */
