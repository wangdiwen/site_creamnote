<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Follow extends CI_Model
{
    var $wx_table = 'wx_follow';
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
            $follow_user_id = $info['follow_user_id'];
            $follow_followed_user_id = $info['follow_followed_user_id'];
            if ($follow_user_id > 0
                && $follow_followed_user_id > 0)
            {
                $data = array(
                    'follow_user_id' => $follow_user_id,
                    'follow_followed_user_id' => $follow_followed_user_id
                    );
                $table = $this->wx_table;
                $this->db->insert($table, $data);
            }
        }
    }
/*****************************************************************************/
    public function has_followed($follow_user_id = 0, $followed_user_id = 0)
    {
        if ($follow_user_id > 0
            && $followed_user_id > 0)
        {
            $table = $this->wx_table;
            $where = array(
                'follow_user_id' => $follow_user_id,
                'follow_followed_user_id' => $followed_user_id
                );
            $this->db->select('follow_user_id')->from($table)->where($where)->limit(1);
            $query = $this->db->get();
            $num = $query->num_rows();
            if ($num > 0)
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
    public function you_like_who($user_id = 0)
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $join_table = 'wx_user';
            $join_where = $join_table.'.user_id = '.$table.'.follow_followed_user_id';

            $this->db->select('wx_follow.follow_followed_user_id, user_name')->from($table)->join($join_table, $join_where, 'left')->where('follow_user_id', $user_id);
            $query = $this->db->get();
            return $query->result_array();
        }
    }
/*****************************************************************************/
    public function who_like_you($user_id = 0)
    {
        if ($user_id > 0)
        {
            // 查找谁关注了你
            $table = $this->wx_table;
            $join_table = 'wx_user';
            $join_where = $join_table.'.user_id = '.$table.'.follow_user_id';

            $this->db->select('wx_follow.follow_user_id, user_name')->from($table)->join($join_table, $join_where, 'left')->where('follow_followed_user_id', $user_id);
            $query = $this->db->get();
            $who_like_you = $query->result_array();

            // 查找你关注的人
            $join_where = $join_table.'.user_id = '.$table.'.follow_followed_user_id';

            $this->db->select('wx_follow.follow_followed_user_id, user_name')->from($table)->join($join_table, $join_where, 'left')->where('follow_user_id', $user_id);
            $query = $this->db->get();
            $you_like_who = $query->result_array();

            $data = array();
            foreach ($who_like_you as $who)
            {
                $who['has_followed'] = 'false';
                foreach ($you_like_who as $you)
                {
                    if ($you['follow_followed_user_id'] == $who['follow_user_id'])
                    {
                        $who['has_followed'] = 'true';
                    }
                }
                array_push($data, $who);
            }
            return $data;
        }
    }
/*****************************************************************************/
    public function you_like_who_count($user_id = 0)
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $join_table = 'wx_user';
            $join_where = $join_table.'.user_id = '.$table.'.follow_followed_user_id';

            $this->db->select('wx_follow.follow_followed_user_id, user_name')->from($table)->join($join_table, $join_where, 'left')->where('follow_user_id', $user_id);
            $query = $this->db->get();
            return $query->num_rows();
        }
    }
/*****************************************************************************/
    public function who_like_you_count($user_id = 0)
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $join_table = 'wx_user';
            $join_where = $join_table.'.user_id = '.$table.'.follow_user_id';

            $this->db->select('wx_follow.follow_user_id, user_name')->from($table)->join($join_table, $join_where, 'left')->where('follow_followed_user_id', $user_id);
            $query = $this->db->get();
            return $query->num_rows();
        }
    }
/*****************************************************************************/
    public function delete($info)
    {
        $follow_user_id = $info['follow_user_id'];
        $follow_followed_user_id = $info['follow_followed_user_id'];
        if ($follow_user_id > 0
            && $follow_followed_user_id > 0)
        {
            $table = $this->wx_table;
            $where = array(
                'follow_user_id' => $follow_user_id,
                'follow_followed_user_id' => $follow_followed_user_id
                );
            $this->db->where($where);
            $this->db->delete($table);
        }
    }
/*****************************************************************************/

/*****************************************************************************/
}

/* End of file wxm_follow.php */
/* Location: ./application/models/core/wxm_follow.php */

