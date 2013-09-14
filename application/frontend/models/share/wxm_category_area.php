<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Category_area extends CI_Model
{
    var $wx_table = 'wx_category_area';

    public function __construct()
    {
        $this->load->database();
    }
/*****************************************************************************/
    // 通过学校名称，获得所有的院系信息
    public function get_depart_by_school($school = '')
    {
        $table = 'wx_category_area';
        // First, get the flag of school name
        $this->db->select('carea_flag')->from($table)->where('carea_name', $school);
        $query = $this->db->get();
        $row = $query->row();
        if (!$row)
        {
            return array();
        }
        $carea_flag = $row->carea_flag;

        // Second, get the carea_name where the flag=carea_flag
        $flag_min = $carea_flag.'000';
        $flag_max = $carea_flag.'999';
        $where = array(
            'carea_flag >' =>$flag_min,
            'carea_flag <' =>$flag_max
            );
        $this->db->select('carea_id, carea_name')->from($table)->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }
/*****************************************************************************/
    // 根据名称查找对应的id
    public function get_id_by_name($name = '')
    {
        $table = $this->wx_table;
        $this->db->select('carea_id')->from($table)->where('carea_name', $name)->limit(1);
        $query = $this->db->get();
        return $query->row()->carea_id;
    }
/*****************************************************************************/
    public function merge()         // 合并数据表的测试接口
    {
        // Test
        // $start = date("Y-m-d H-i-s", time());
        // loginfo('start time: '.$start);

        $provinces = 'provinces';
        $univs = 'univs';
        $schools = 'schools';
        $wx_category_area = 'wx_category_area';

        $this->db->select('id, name')->from($schools);
        $query = $this->db->get();
        foreach ($query->result() as $row)
        {
            $data = array(
                'carea_name' => $row->name,
                'carea_flag' => $row->id,
                'carea_grade' => '3'
                );
            $this->db->insert($wx_category_area, $data);
        }

        // Test
        // $stop = date("Y-m-d H-i-s", time());
        // loginfo('stop time: '.$stop);
    }
/*****************************************************************************/
    public function get_all_info($carea_id = 0)
    {
        if ($carea_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('carea_id, carea_name, carea_grade, carea_flag')
                     ->from($table)->where('carea_id', $carea_id)->limit(1);
            $query = $this->db->get();
            $row = $query->row();
            return $row;
        }

        return false;
    }
/*****************************************************************************/
    public function get_name_by_id($carea_id = 0)
    {
        if ($carea_id > 0) {
            $table = $this->wx_table;
            $this->db->select('carea_id, carea_name')->from($table)->where('carea_id', $carea_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function search_area_name_like($area_name = '')
    {
        $table = $this->wx_table;
        $this->db->select('carea_id, carea_name, carea_grade, carea_flag')
                 ->from($table)->like('carea_name', $area_name, 'both')->limit(10);
        $query = $this->db->get();
        return $query->result();
    }
/*****************************************************************************/
    public function get_by_flag($area_flag = '')
    {
        if ($area_flag)
        {
            $table = $this->wx_table;
            $this->db->select('carea_id, carea_name, carea_grade, carea_flag')
                     ->from($table)->where('carea_flag', $area_flag)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function get_all_school_by_region($region = '') {  // like: 江苏
        if ($region) {
            // first, get area id of region
            $table = $this->wx_table;
            $this->db->select('carea_flag')
                     ->from($table)->where('carea_name', $region)->limit(1);
            $query = $this->db->get();
            $region_info = $query->row_array();
            if ($region_info) {
                $region_area_flag = $region_info['carea_flag'];

                $flag_min = $region_area_flag.'000';
                $flag_max = $region_area_flag.'999';
                $where = array(
                    'carea_flag >' =>$flag_min,
                    'carea_flag <' =>$flag_max
                    );
                $this->db->select('carea_id, carea_name')->from($table)->where($where);
                $query = $this->db->get();
                return $query->result_array();
            }
        }
    }
/*****************************************************************************/
}

/* End of file wxm_category_area.php */
/* Location: ./application/models/share/wxm_category_area.php */
