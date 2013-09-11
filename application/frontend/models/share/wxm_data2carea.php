<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Data2carea extends CI_Model
{
    var $wx_table = 'wx_data2carea';

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
            $carea_id_school = $info['carea_id_school'];
            $carea_id_major = $info['carea_id_major'];
            $data_id = $info['data_id'];

            $data = array(
                'carea_id_school' => $carea_id_school,
                'carea_id_major' => $carea_id_major,
                'data_id' => $data_id
                );
            $table = $this->wx_table;
            $this->db->insert($table, $data);
        }
    }
/*****************************************************************************/
    public function delete_by_data_id($data_id = 0)
    {
        if ($data_id > 0)
        {
            $table = $this->wx_table;
            $this->db->where('data_id', $data_id);
            $this->db->delete($table);
        }
    }
/*****************************************************************************/
    public function get_data_id_by_school($area_id_school = 0)
    {
        if ($area_id_school > 0)
        {
            $table = $this->wx_table;
            $where = array(
                'carea_id_school' => $area_id_school,
                'carea_id_major' => 0,  // just filter school, not major
                );
            $this->db->select('data_id')->from($table)->where($where)->limit(10);
            $query = $this->db->get();
            return $query->result();  // Object array
        }

        return false;
    }
/*****************************************************************************/
    public function get_data_id_by_major($area_id_major = 0)
    {
        if ($area_id_major > 0)
        {
            $table = $this->wx_table;
            $this->db->select('data_id')->from($table)->where('carea_id_major', $area_id_major)->limit(10);
            $query = $this->db->get();
            return $query->result();  // Object array
        }

        return false;
    }
/*****************************************************************************/
    public function get_data_id($area_id = 0)
    {
        if ($area_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('data_id')->from($table)->where('carea_id_school', $area_id)->or_where('carea_id_major', $area_id)->limit(10);
            $query = $this->db->get();
            return $query->result();  // Object array
        }

        return false;
    }
/*****************************************************************************/
    public function get_area_id($data_id = 0)
    {
        if ($data_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('carea_id_school, carea_id_major')->from($table)->where('data_id', $data_id)->limit(1);
            $query = $this->db->get();
            return $query->row();  // Object array
        }

        return false;
    }
/*****************************************************************************/
    public function update($info)
    {
        if ($info)
        {
            $data_id = $info['data_id'];
            $carea_id_school = $info['carea_id_school'];
            $carea_id_major = $info['carea_id_major'];
            if ($data_id > 0)
            {
                $table = $this->wx_table;
                $data = array(
                    'carea_id_school' => $carea_id_school,
                    'carea_id_major' => $carea_id_major
                    );
                $this->db->where('data_id', $data_id);
                $this->db->update($table, $data);
            }
        }
    }
/*****************************************************************************/
}

/* End of file wxm_data2carea.php */
/* Location: ./application/models/share/wxm_data2area.php */
