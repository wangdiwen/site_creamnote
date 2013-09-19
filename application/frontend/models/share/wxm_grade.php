<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Grade extends CI_Model
{
    var $wx_table = 'wx_grade';
/*****************************************************************************/
    public function __construct()
    {
        $this->load->database();
    }
/*****************************************************************************/
    public function insert($info)
    {
        $grade_excellent_count = $info['grade_excellent_count'];
        $grade_well_count = $info['grade_well_count'];
        $grade_bad_count = $info['grade_bad_count'];
        $data_id = $info['data_id'];

        if ($data_id > 0)
        {
            $data = array(
                'grade_excellent_count' => $grade_excellent_count,
                'grade_well_count' => $grade_well_count,
                'grade_bad_count' => $grade_bad_count,
                'data_id' => $data_id
                );
            $table = $this->wx_table;
            $this->db->insert($table, $data);
        }
    }
/*****************************************************************************/
    public function update_excellent($info)
    {
        $grade_excellent_count = $info['grade_excellent_count'];
        $data_id = $info['data_id'];
        if ($data_id > 0)
        {
            $data = array(
                'grade_excellent_count' => $grade_excellent_count
                );
            $table = $this->wx_table;
            $this->db->where('data_id', $data_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function update_well($info)
    {
        $grade_well_count = $info['grade_well_count'];
        $data_id = $info['data_id'];
        if ($data_id > 0)
        {
            $data = array(
                'grade_well_count' => $grade_well_count
                );
            $table = $this->wx_table;
            $this->db->where('data_id', $data_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function update_bad($info)
    {
        $grade_bad_count = $info['grade_bad_count'];
        $data_id = $info['data_id'];
        if ($data_id > 0)
        {
            $data = array(
                'grade_bad_count' => $grade_bad_count
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
            $this->db->select('grade_id, grade_excellent_count, grade_well_count, grade_bad_count')->from($table)->where('data_id', $data_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function has_grade_data_record($data_id = 0) {
        if ($data_id > 0) {
            $table = $this->wx_table;
            $this->db->select('grade_id')->from($table)->where('data_id', $data_id)->limit(1);
            $query = $this->db->get();
            $count = $query->num_rows();
            if ($count > 0) {
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function delete_by_data_id($data_id = 0)
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
/*****************************************************************************/

/*****************************************************************************/
}

/* End of file wxm_grade.php */
/* Location: ./application/models/share/wxm_grade.php */
