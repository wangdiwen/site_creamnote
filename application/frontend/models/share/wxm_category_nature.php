<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Category_nature extends CI_Model
{
    var $wx_table = 'wx_category_nature';

    public function __construct()
    {
        $this->load->database();
    }
/*****************************************************************************/
    public function get_all_info($cnature_id = 0)
    {
        if ($cnature_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('cnature_id, cnature_name, cnature_grade, cnature_flag')
                     ->from($table)->where('cnature_id', $cnature_id)->limit(1);
            $query = $this->db->get();
            $row = $query->row();
            if ($row)
            {
                return $row;
            }
        }

        return false;
    }
/*****************************************************************************/
    public function get_name_by_id($cnature_id = 0)
    {
        if ($cnature_id > 0) {
            $table = $this->wx_table;
            $this->db->select('cnature_id, cnature_name')->from($table)->where('cnature_id', $cnature_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    // 根据id，查找到它的一级分类名称
    public function get_first_by_id($cnature_id = 0)
    {
        if ($cnature_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('cnature_flag')->from($table)->where('cnature_id', $cnature_id);
            $query = $this->db->get();
            $row = $query->row();
            if ($row)
            {
                $flag = $row->cnature_flag;
                // $flag[0] = 1, ===>考研资料
                // $flag[0] = 2, ===>考试
                // $flag[0] = 3, ===>学习笔记
                $first_id = $flag[0];
                return $first_id;
            }
            else
                echo '0';
        }
    }
/*****************************************************************************/
    // 获得natrue分类的一级分类
    public function get_first_nature()
    {
        $table = 'wx_category_nature';
        $this->db->select('cnature_id, cnature_name')->from($table)->where('cnature_grade', '1');
        $query = $this->db->get();
        return $query->result();    // a object array
    }
/*****************************************************************************/
    // 获得natrue分类的二级分类
    public function get_second_nature($cnature_id = 0)
    {
        $table = 'wx_category_nature';
        // Get flag info
        $cnature_flag = '';
        $this->db->select('cnature_flag')->from($table)->where('cnature_id', $cnature_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            $cnature_flag = $row->cnature_flag;
        }

        // Get second natrue of this flag
        if ($cnature_flag != '')
        {
            $second_flag_min = $cnature_flag.'00';
            $second_flag_max = $cnature_flag.'99';
            $where = array(
                'cnature_flag >' => $second_flag_min,
                'cnature_flag <' => $second_flag_max
                );
            $this->db->select('cnature_id, cnature_name')->from($table)->where($where);
            $query = $this->db->get();
            $rows = $query->num_rows();
            if ($rows > 0)
            {
                return $query->result();  // result() is a object array
            }
            else
            {
                return array();
            }
        }
    }
/*****************************************************************************/
    // 获得natrue分类的三级分类
    public function get_third_nature($cnature_id = 0)
    {
        $table = 'wx_category_nature';
        // Get flag info
        $cnature_flag = '';
        $this->db->select('cnature_flag')->from($table)->where('cnature_id', $cnature_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            $cnature_flag = $row->cnature_flag;
        }

        // Get third natrue of this flag
        if ($cnature_flag != '')
        {
            $second_flag_min = $cnature_flag.'00';
            $second_flag_max = $cnature_flag.'99';
            $where = array(
                'cnature_flag >' => $second_flag_min,
                'cnature_flag <' => $second_flag_max
                );
            $this->db->select('cnature_id, cnature_name')->from($table)->where($where);
            $query = $this->db->get();
            $rows = $query->num_rows();
            if ($rows > 0)
            {
                return $query->result();  // result() is a object array
            }
            else
            {
                return array();
            }
        }
    }
/*****************************************************************************/
    public function search_by_name_like($name = '')
    {
        $table = $this->wx_table;
        $this->db->select('cnature_id, cnature_name, cnature_grade, cnature_flag')
                 ->from($table)->like('cnature_name', $name, 'both');
        $query = $this->db->get();
        return $query->result();
    }
/*****************************************************************************/
    public function get_by_flag($nature_flag = '')
    {
        if ($nature_flag)
        {
            $table = $this->wx_table;
            $this->db->select('cnature_id, cnature_name, cnature_grade, cnature_flag')
                     ->from($table)->where('cnature_flag', $nature_flag)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
}

/* End of file wxm_category_nature.php */
/* Location: ./application/models/share/wxm_category_nature.php */
