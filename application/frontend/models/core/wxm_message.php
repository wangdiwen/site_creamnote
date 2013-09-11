<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Message extends CI_Model
{
    var $wx_table = 'wx_message';
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
            $message_content = $info['message_content'];
            $message_time = $info['message_time'];
            $message_user_id = $info['message_user_id'];
            $message_to_user_id = $info['message_to_user_id'];

            if ($message_content && $message_to_user_id
                && $message_user_id && $message_to_user_id)
            {
                $data = array(
                    'message_content' => $message_content,
                    'message_time' => $message_time,
                    'message_user_id' => $message_user_id,
                    'message_to_user_id' => $message_to_user_id
                    );
                $table = $this->wx_table;
                $this->db->insert($table, $data);
            }
        }
    }
/*****************************************************************************/
    public function get_message($user_id = 0, $to_user_id = 0)
    {
        if ($user_id > 0 && $to_user_id > 0)
        {
            $table = $this->wx_table;
            $where = array(
                'message_view' => 'false',
                'message_user_id' => $user_id,
                'message_to_user_id' => $to_user_id
                );
            $join_table = 'wx_user';
            $join_where = $join_table.'.user_id = '.$table.'.message_user_id';
            $this->db->select('message_id, message_content, message_time, wx_message.message_user_id,
                user_name, user_email')->from($table)->join($join_table, $join_where, 'left')
                ->where($where)->order_by('message_time', 'asc');
            $query = $this->db->get();
            return $query->result_array();
        }
    }
/*****************************************************************************/
    public function update_message_view($message_id = 0)
    {
        $message_view = 'false';
        if ($message_id > 0)
        {
            $table = $this->wx_table;
            $where = array(
                'message_id' => $message_id,
                'message_view' => $message_view
                );
            $data = array(
                'message_view' => 'true'
                );
            $this->db->where($where);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function history_message($user_id = 0)
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $where = array(
                'message_user_id' => $user_id
                );
            $or_where = array(
                'message_to_user_id' => $user_id
                );
            $join_table = 'wx_user';
            $join_where = $join_table.'.user_id = '.$table.'.message_user_id';
            $this->db->select('message_id, message_content, message_time, message_user_id, user_name, message_to_user_id')->from($table)->join($join_table, $join_where, 'left')->where($where)->or_where($or_where)->order_by('message_time', 'desc')->limit(10);
            $query = $this->db->get();
            return $query->result_array();
        }
    }
/*****************************************************************************/

/*****************************************************************************/

/*****************************************************************************/
}

/* End of file wxm_message.php */
/* Location: ./application/models/core/wxm_message.php */
