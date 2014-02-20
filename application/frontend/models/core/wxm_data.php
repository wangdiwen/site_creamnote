<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Data extends CI_Model
{
    var $wx_table = 'wx_data';
/*****************************************************************************/
    public function __construct()
    {
        $this->load->database();
    }
/*****************************************************************************/
    public function get_data_by_id_list($data_id_list = array())
    {
        if ($data_id_list)
        {
            $table = $this->wx_table;
            $this->db->select('data_id, data_name, data_type')->from('wx_data')->where_in('data_id', $data_id_list)->order_by('data_uploadtime', 'desc');
            $query = $this->db->get();
            return $query->result_array();
        }
    }
/*****************************************************************************/
    public function get_data_id_list($user_id = 0)
    {
        if ($user_id > 0)
        {
            $this->db->select('data_id, data_name')->from('wx_data')->where('user_id', $user_id);
            $query = $this->db->get();
            return $query->result_array();
        }
    }
/*****************************************************************************/
    public function user_data_info($user_id = 0)
    {
        if ($user_id == 0)
        {
            return array();
        }
        else
        {
            $this->db->select('data_id, data_name, data_type, data_pagecount, data_summary, data_price,
                                data_status, user_id, data_uploadtime, data_point, data_keyword')
                     ->from('wx_data')->where('user_id', $user_id)->order_by('data_uploadtime', 'desc')->limit(40);
            $query = $this->db->get();
            return $query->result_array();  // Object array
        }
    }
/*****************************************************************************/
    public function search_user_data_info($user_id = 0)
    {
        if ($user_id == 0)
        {
            return array();
        }
        else
        {
            $where = array(
                'user_id' => $user_id,
                'data_status' => '3'
                );
            $this->db->select('data_id, data_name, data_type, data_pagecount, user_id, data_uploadtime')
                     ->from('wx_data')->where($where)->order_by('data_uploadtime', 'desc');
            $query = $this->db->get();
            return $query->result();  // Object array
        }
    }
/*****************************************************************************/
    public function delete_data_by_id($data_id = 0)
    {
        if ($data_id > 0)
        {
            $this->db->where('data_id', $data_id);
            $this->db->delete('wx_data');
        }
    }
/*****************************************************************************/
    public function get_storage_path($data_id = 0)
    {
        if ($data_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('data_id, data_objectname, user_id, data_osspath, data_vpspath, data_preview')->from($table)->where('data_id', $data_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
        return false;
    }
/*****************************************************************************/
    public function get_oss_info($data_id = 0)
    {
        if ($data_id > 0) {
            $table = $this->wx_table;
            $this->db->select('data_id, user_id, data_objectname, data_osspath, data_vpspath')->from($table)->where('data_id', $data_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function get_data_info($data_id = 0)
    {
        $data = array();
        // Query the db
        $ret = $this->db->table_exists('wx_data');
        if ($ret)
        {
            if (! ($data_id > 0))
            {
                return false;
            }
            $this->db->select('data_id, data_name, data_objectname, data_type, data_pagecount, data_summary,
                data_price, data_point, data_status, user_id, data_uploadtime,
                data_osspath, data_vpspath, data_preview, data_keyword')
                ->from('wx_data')->where('data_id', $data_id);
            $query = $this->db->get();
            $rows = $query->num_rows();
            if ($rows > 0)
            {
                $data = $query->row();  // $data is a obj
                return $data;
            }
        }
    }
/*****************************************************************************/
    public function get_simple_info_by_id($data_id = 0) {
        if ($data_id > 0) {
            $table = $this->wx_table;
            $this->db->select('data_id, data_name, data_objectname, data_type')->from($table)->where('data_id', $data_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function get_download_info($data_id = 0) {
        if ($data_id > 0) {
            $table = $this->wx_table;
            $this->db->select('data_id, data_name, data_objectname, data_type,
                data_price, user_id, data_osspath, data_vpspath')
                ->from($table)->where('data_id', $data_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
        return false;
    }
/*****************************************************************************/
    public function get_simple_info_by_id_list($data_id_list = array()) {
        if ($data_id_list) {
            $table = $this->wx_table;
            $this->db->select('data_id, data_name, data_type, data_pagecount, data_price, data_uploadtime')
                    ->from($table)->where_in('data_id', $data_id_list)->order_by('data_uploadtime', 'desc');
            $query = $this->db->get();
            return $query->result_array();
        }
        return false;
    }
/*****************************************************************************/
    public function get_owner_user_id($data_id = 0) {
        if ($data_id > 0) {
            $table = $this->wx_table;
            $this->db->select('user_id')
                ->from($table)->where('data_id', $data_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
        return false;
    }
/*****************************************************************************/
    public function search_get_baseinfo_by_id_list($data_id_list = array()) {
        if ($data_id_list) {
            $where = array(
                'data_status' => '3'
                );
            $this->db->select('data_id, data_name, data_type, data_pagecount, data_price, user_id, data_uploadtime, data_point, data_keyword')
                    ->from('wx_data')->where($where)->where_in('data_id', $data_id_list)->order_by('data_uploadtime');
            $query = $this->db->get();
            return $query->result_array();
        }
    }
/*****************************************************************************/
    public function search_get_data_info($data_id = 0)
    {
        $data = array();
        // Query the db
        $ret = $this->db->table_exists('wx_data');
        if ($ret)
        {
            if (! ($data_id > 0))
            {
                return false;
            }

            $where = array(
                'data_id' => $data_id,
                'data_status' => '3'
                );
            $this->db->select('data_id, data_name, data_type, data_pagecount, data_price, user_id, data_uploadtime, data_point, data_keyword')->from('wx_data')->where($where);
            $query = $this->db->get();

            $rows = $query->num_rows();
            if ($rows > 0)
            {
                return $query->row_array();
            }
        }
    }
/*****************************************************************************/
    public function get_id_by_objectname($data_objectname = '')
    {
        if ($data_objectname != '')
        {
            $this->db->select('data_id')->from('wx_data')->where('data_objectname', $data_objectname)->limit(1);
            $query = $this->db->get();
            $row = $query->row_array();
            if ($row) {
                return $row['data_id'];
            }
        }
    }
/*****************************************************************************/
    public function set_data_info($data)
    {
        if ($data)
        {
            $this->db->insert('wx_data', $data);
        }
    }
/*****************************************************************************/
    public function update_data_info($data)
    {
        if ($data)
        {
            $data_id = $data['data_id'];
            $new_data = array(
                            'data_name' => $data['data_name'],
                            'data_status' => $data['data_status'],
                            // 'data_summary' => $data['data_summary'],
                            'data_price' => $data['data_price'],
                            'data_point' => $data['data_point'],
                            // 'data_keyword' => $data['data_keyword'],
                            'data_tag' => $data['data_tag'],            // new added
                            'data_preview' => $data['data_preview'],
                            'data_uploadtime' => $data['data_uploadtime'],
                            'data_vpspath' => $data['data_vpspath'],
                            );
            $this->db->where('data_id', $data_id);
            $this->db->update('wx_data', $new_data);
        }
    }
/*****************************************************************************/
    public function search_by_name_like($name_list, $data_id_list_area, $data_id_list_nature)
    {
        if (! $name_list)
        {
            return array();
        }
        $table = $this->wx_table;
        $this->db->select('data_id');
        if ($name_list)
        {
            $len = count($name_list);
            if ($len == 1)
            {
                $this->db->like('data_name', $name_list[0], 'both');
            }
            else
            {
                $this->db->like('data_name', $name_list[0], 'both');
                for ($i = 1; $i < $len; $i++)
                {
                    $this->db->or_like('data_name', $name_list[$i], 'both');
                }
            }
        }

        // $data_id_list = array_merge($data_id_list_area, $data_id_list_nature);
        // if ($data_id_list) {
        //     $data_id_list = array_unique($data_id_list);
        //     $this->db->where_in('data_id', $data_id_list);
        // }

        $this->db->from($table)->where('data_status', '3')->limit(10);
        $query = $this->db->get();
        return $query->result_array();
    }
/*****************************************************************************/
    public function search_by_keyword_like($keyword_list, $data_id_list_area, $data_id_list_nature)
    {
        if (! $keyword_list)
        {
            return array();
        }
        $table = $this->wx_table;
        $this->db->select('data_id');
        if ($keyword_list)
        {
            $len = count($keyword_list);
            if ($len == 1)
            {
                $this->db->like('data_keyword', $keyword_list[0], 'both');
            }
            else
            {
                $this->db->like('data_keyword', $keyword_list[0], 'both');
                for ($i = 1; $i < $len; $i++)
                {
                    $this->db->or_like('data_keyword', $keyword_list[$i], 'both');
                }
            }
        }

        // $data_id_list = array_merge($data_id_list_area, $data_id_list_nature);
        // if ($data_id_list) {
        //     $data_id_list = array_unique($data_id_list);
        //     $this->db->where_in('data_id', $data_id_list);
        // }

        $this->db->from($table)->where('data_status', '3')->limit(10);
        $query = $this->db->get();
        return $query->result_array();
    }
/*****************************************************************************/
    public function get_base_info_by_user_id($user_id = 0) {
        if ($user_id > 0) {
            $where = array(
                'user_id' => $user_id,
                'data_status' => '3',
                );
            $this->db->select('data_id, data_name, data_type, data_pagecount, data_price, user_id, data_uploadtime, data_point')
                 ->from('wx_data')->where($where)->order_by('data_point', 'desc');
            $query = $this->db->get();
            return $query->result_array();
        }
        return false;
    }
/*****************************************************************************/
    public function latest_top_ten()
    {
        $this->db->select('data_id, data_name, data_type, data_pagecount, data_price, user_id, data_uploadtime, data_point')
                 ->from('wx_data')->where('data_status', '3')->limit(10)->order_by('data_uploadtime', 'desc');
        $query = $this->db->get();
        $data_info = $query->result_array();
        return $data_info;
    }
/*****************************************************************************/
    public function perfect_top_ten() {
        $this->db->select('data_id, data_name, data_type, data_pagecount, data_price, user_id, data_uploadtime, data_point')
                 ->from('wx_data')->where('data_status', '3')->limit(10)->order_by('data_point', 'desc');
        $query = $this->db->get();
        $data_info = $query->result_array();
        return $data_info;
    }
/*****************************************************************************/
    public function hot_top_100() {
        $this->db->select('data_id, data_name, data_type, data_pagecount, data_price, user_id, data_uploadtime, data_point, data_keyword')
                 ->from('wx_data')->where('data_status', '3')->limit(100)->order_by('data_point', 'desc');
        $query = $this->db->get();
        $data_info = $query->result_array();
        return $data_info;
    }
/*****************************************************************************/
    public function latest_upload()
    {
        $this->db->select('data_id, data_name, data_type, data_pagecount, data_price, user_id, data_uploadtime, data_point, data_keyword')
                 ->from('wx_data')->where('data_status', '3')->limit(10)->order_by('data_uploadtime', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }
/*****************************************************************************/
    public function update_point($info)
    {
        $data_id = $info['data_id'];
        $data_point = $info['data_point'];
        if ($data_id > 0)
        {
            $data = array(
                'data_point' => $data_point
                );
            $table = $this->wx_table;
            $this->db->where('data_id', $data_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function complete_data_info($info)
    {
        if ($info)
        {
            $data_id = $info['data_id'];
            $data_name = $info['data_name'];
            $data_status = $info['data_status'];
            $data_summary = $info['data_summary'];
            $data_price = $info['data_price'];
            $data_preview = $info['data_preview'];
            $data_keyword = $info['data_keyword'];
            $data_vpspath = $info['data_vpspath'];
            $data_osspath = $info['data_osspath'];
            $data = array(
                'data_name' => $data_name,
                'data_status' => $data_status,
                'data_summary' => $data_summary,
                'data_price' => $data_price,
                'data_preview' => $data_preview,
                'data_keyword' => $data_keyword,
                'data_osspath' => $data_osspath,
                'data_vpspath' => $data_vpspath
                );
            if ($data_id > 0)
            {
                $table = $this->wx_table;
                $this->db->where('data_id', $data_id);
                $this->db->update($table, $data);
            }
        }
    }
/*****************************************************************************/
    public function get_data_all_info($data_id = 0)
    {
        if ($data_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('data_id, data_name, data_objectname, data_type, data_pagecount, data_summary, data_price, data_point, data_status, user_id, data_uploadtime, data_osspath, data_vpspath, data_preview, data_keyword')->from($table)->where('data_id', $data_id)->limit(1);
            $query = $this->db->get();
            $data_info = $query->row_array();
            return $data_info;
        }
    }
/*****************************************************************************/
    public function update_data_pagecount($data_id = 0, $page_count = 0)
    {
        if ($data_id > 0) {
            $table = $this->wx_table;
            $data = array(
                'data_pagecount' => $page_count
                );
            $this->db->where('data_id', $data_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function update_vpspath($data_id = 0, $data_vpspath = '') {
        if ($data_id && $data_vpspath) {
            $table = $this->wx_table;
            $data = array(
                'data_vpspath' => $data_vpspath
                );
            $this->db->where('data_id', $data_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
}

/* End of file wxm_data.php */
/* Location: ./application/models/core/wxm_data.php */
