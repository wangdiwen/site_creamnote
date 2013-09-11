<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Comment extends CI_Model
{
    var $wx_table = 'wx_comment';
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
            $data_id = $info['data_id'];
            $user_id = $info['user_id'];
            $comment_content = $info['comment_content'];
            $comment_time = $info['comment_time'];
            $comment_status = $info['comment_status'];
            if ($data_id > 0 && $user_id > 0
                && $comment_content
                && $comment_time
                && $comment_status)
            {
                $data = array(
                    'data_id' => $data_id,
                    'user_id' => $user_id,
                    'comment_content' => $comment_content,
                    'comment_time' => $comment_time,
                    'comment_status' => $comment_status
                    );
                $table = $this->wx_table;
                $this->db->insert($table, $data);
            }
        }
    }
/*****************************************************************************/
    public function get_by_data_id($data_id = 0)
    {
        if ($data_id > 0)
        {
            $table = $this->wx_table;
            $join_table = 'wx_user';
            $join_where = $join_table.'.user_id = '.$table.'.user_id';

            $this->db->select('comment_id, wx_comment.data_id, comment_content, comment_time,
                    comment_status, wx_comment.user_id, user_name, user_email')->from($table)
                    ->join($join_table, $join_where, 'left')->where('data_id', $data_id)
                    ->order_by('comment_time', 'desc');
            $query = $this->db->get();
            return $query->result_array();
        }
    }
/*****************************************************************************/
    public function get_by_user_id($user_id = 0)
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $join_table = 'wx_user';
            $join_where = $join_table.'.user_id = '.$table.'.user_id';

            $this->db->select('comment_id, wx_comment.user_id, comment_content, comment_time, comment_status, user_name')->from($table)->join($join_table, $join_where, 'left')->where('user_id', $user_id)->order_by('comment_time', 'desc');
            $query = $this->db->get();
            return $query->result_array();
        }
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

/* End of file wxm_comment.php */
/* Location: ./application/models/core/wxm_comment.php */
