<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Notify extends CI_Model
{
    var $wx_table = 'wx_notify';
/*****************************************************************************/
    public function __construct()
    {
        $this->load->database();
    }
/*****************************************************************************/
    public function insert($info)
    {
        $notify_type = $info['notify_type'];
        $notify_content = $info['notify_content'];
        $user_id = $info['user_id'];
        $notify_params = $info['notify_params'];
        $notify_time = $info['notify_time'];

        if ($notify_type && $notify_content
            && $user_id && $notify_params)
        {
            $data = array(
                'notify_type' => $notify_type,
                'notify_content' => $notify_content,
                'user_id' => $user_id,
                'notify_params' => $notify_params,
                'notify_time' => $notify_time
                );
            $table = $this->wx_table;
            $this->db->insert($table, $data);
        }
    }
/*****************************************************************************/
    public function has_feedback_topic($user_id = 0, $notify_params = 0)
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $where = array(
                'notify_type' => '1',
                'user_id' => $user_id,
                'notify_params' => $notify_params
                );
            $this->db->select('notify_id')->from($table)->where($where);
            $query = $this->db->get();
            $rows_num = $query->num_rows();
            if ($rows_num > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        return true;
    }
/*****************************************************************************/
    public function get_by_user_id($user_id = 0)
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $where = array(
                'user_id' => $user_id
                );
            $this->db->select('notify_id, notify_type, notify_title, notify_content, notify_params')->from($table)->where($where)->order_by('notify_time', 'desc');
            $query = $this->db->get();
            return $query->result_array();
        }
    }
/*****************************************************************************/
    public function get_sysinfo_by_id($notify_id = 0)
    {
        if ($notify_id > 0) {
            $table = $this->wx_table;
            $where = array(
                'notify_id' => $notify_id
                );
            $this->db->select('notify_title, notify_content, notify_time')->from($table)->where($where)->limit(1);
            $query = $this->db->get();
            return $query->result_array();
        }
    }
/*****************************************************************************/
    public function delete_by_id($notify_id = 0)
    {
        if ($notify_id > 0)
        {
            $table = $this->wx_table;
            $this->db->where('notify_id', $notify_id);
            $this->db->delete($table);
        }
    }
/*****************************************************************************/
    public function has_comment_notify($user_id = 0, $notify_params = 0)
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $where = array(
                'notify_type' => '3',
                'user_id' => $user_id,
                'notify_params' => $notify_params
                );
            $this->db->select('notify_id')->from($table)->where($where);
            $query = $this->db->get();
            $rows_num = $query->num_rows();
            if ($rows_num > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        return true;
    }
/*****************************************************************************/
    public function has_message_notify($user_id = 0, $notify_params = 0)
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $where = array(
                'notify_type' => '2',
                'user_id' => $user_id,
                'notify_params' => $notify_params
                );
            $this->db->select('notify_id')->from($table)->where($where);
            $query = $this->db->get();
            $rows_num = $query->num_rows();
            if ($rows_num > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        return true;
    }
/*****************************************************************************/
}

/* End of file wxm_notify.php */
/* Location: ./application/models/core/wxm_notify.php */
