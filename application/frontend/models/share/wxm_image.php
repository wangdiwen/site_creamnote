<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Image extends CI_Model
{
    var $wx_table = 'wx_image';

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
            $image_json = $info['image_json'];
            $user_id = $info['user_id'];
            if ($image_json && $user_id)
            {
                $data = array(
                    'image_json' => $image_json,
                    'user_id' => $user_id
                    );
                $table = $this->wx_table;
                $this->db->insert($table, $data);
            }
        }
    }
/*****************************************************************************/
    public function get_all_by_user_id($user_id = 0)
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('image_id, image_json, user_id')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row();
        }
    }
/*****************************************************************************/
    public function update($info)
    {
        if ($info > 0)
        {
            $image_json = $info['image_json'];
            $user_id = $info['user_id'];
            if ($image_json && $user_id > 0)
            {
                $data = array(
                    'image_json' => $image_json
                    );
                $table = $this->wx_table;
                $this->db->where('user_id', $user_id);
                $this->db->update($table, $data);
            }
        }
    }
/*****************************************************************************/
    public function delete($user_id = 0)       // By user id
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $this->db->where('user_id', $user_id);
            $this->db->delete($table);
        }
    }
/*****************************************************************************/

/*****************************************************************************/
}

/* End of file wxm_image.php */
/* Location: ./application/models/share/wxm_image.php */
