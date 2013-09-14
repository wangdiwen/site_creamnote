<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Data2cnature extends CI_Model
{
    var $wx_table = 'wx_data2cnature';

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
            $cnature_id = $info['cnature_id'];
            $data_id = $info['data_id'];

            $data = array(
                'cnature_id' => $cnature_id,
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
    public function get_data_id($nature_id = 0)
    {
        if ($nature_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('data_id')->from($table)->where('cnature_id', $nature_id)->limit(20);
            $query = $this->db->get();
            return $query->result();  // Object array
        }

        return false;
    }
/*****************************************************************************/
    public function get_nature_id($data_id = 0)
    {
        if ($data_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('cnature_id')->from($table)->where('data_id', $data_id)->limit(1);
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
            $nature_id = $info['nature_id'];
            if ($data_id > 0)
            {
                $table = $this->wx_table;
                $data = array(
                    'cnature_id' => $nature_id
                    );
                $this->db->where('data_id', $data_id);
                $this->db->update($table, $data);
            }
        }
    }
/*****************************************************************************/
}

/* End of file wxm_data2cnature.php */
/* Location: ./application/models/share/wxm_data2carea.php */
