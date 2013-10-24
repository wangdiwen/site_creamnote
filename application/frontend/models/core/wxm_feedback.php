<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Feedback extends CI_Model
{
    var $wx_table = 'wx_feedback';
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
            $feedback_content = $info['feedback_content'];
            $feedback_time = $info['feedback_time'];
            $feedback_startup = $info['feedback_startup'];
            $feedback_followed_id = $info['feedback_followed_id'];
            $feedback_user_type = $info['feedback_user_type'];
            $user_id = $info['user_id'];
            $feedback_newjoin = $info['feedback_newjoin'];

            if ($feedback_content && $feedback_time && $feedback_startup
                && $feedback_user_type && $user_id)
            {
                $data = array(
                    'feedback_content' => $feedback_content,
                    'feedback_time' => $feedback_time,
                    'feedback_startup' => $feedback_startup,
                    'feedback_followed_id' => $feedback_followed_id,
                    'feedback_user_type' => $feedback_user_type,
                    'user_id' => $user_id,
                    'feedback_newjoin' => $feedback_newjoin,
                    );
                $table = $this->wx_table;
                $this->db->insert($table, $data);
            }
        }
    }
/*****************************************************************************/
    public function get_feedback_info($per_page_limit = 5, $offset = 10)
    {
        $data = array();

        $table = $this->wx_table;
        $join_table = 'wx_user';
        $join_where = $join_table.'.user_id = '.$table.'.user_id';

        $this->db->select('feedback_id, feedback_content, feedback_time, feedback_startup, feedback_user_type, wx_feedback.user_id , user_name, user_email')->join($join_table, $join_where, 'left')->where('feedback_startup', 'true')->order_by('feedback_time', 'desc');
        $query = $this->db->get($table, $per_page_limit, $offset);

        $topics = $query->result_array();
        if ($topics)
        {
            foreach ($topics as $topic)
            {
                $topic_data = array();
                array_push($topic_data, $topic);

                $feedback_followed_id = $topic['feedback_id'];
                $this->db->select('feedback_id, feedback_content, feedback_time, feedback_startup, feedback_user_type, wx_feedback.user_id, user_name, user_email')->from($table)->join($join_table, $join_where, 'left')->where('feedback_followed_id', $feedback_followed_id)->order_by('feedback_time', 'asc');
                $query = $this->db->get();
                $followed_data = $query->result_array();
                if ($followed_data)
                {
                    $tmp_data = array_merge($topic_data, $followed_data);
                    array_push($data, $tmp_data);
                }
                else
                {
                    array_push($data, $topic_data);
                }
            }
        }
        return $data;
    }
/*****************************************************************************/
    public function get_id_by_time($feedback_time = '')
    {
        if ($feedback_time)
        {
            $table = $this->wx_table;
            $this->db->select('feedback_id')->from($table)->where('feedback_time', $feedback_time)->limit(1);
            $query = $this->db->get();
            return $query->row();
        }
    }
/*****************************************************************************/
    public function get_single_feedback_topic($feedback_followed_id = 0)
    {
        $data = array();

        if ($feedback_followed_id > 0) {
            $table = $this->wx_table;

            // get the feedback topic
            $this->db->select('feedback_id, feedback_content, feedback_time, feedback_startup,
                                feedback_user_type, user_id')
                        ->from($table)->where('feedback_id', $feedback_followed_id)->limit(1);
            $query = $this->db->get();
            $topic = $query->row_array();

            // get the follow feedback topic
            $this->db->select('feedback_id, feedback_content, feedback_time, feedback_startup,
                                feedback_user_type, user_id')
                        ->from($table)->where('feedback_followed_id', $feedback_followed_id)->order_by('feedback_time', 'asc');
            $query = $this->db->get();
            $followed_data = $query->result_array();

            // merge the topic and follow feedback info
            $data[] = $topic;
            if ($followed_data) {
                $data = array_merge($data, $followed_data);
            }
        }

        return $data;
    }
/*****************************************************************************/
    public function get_common_user_id_info_by_id($feedback_followed_id = 0) {
        if ($feedback_id > 0) {
            $table = $this->wx_table;
            $where = array(
                'feedback_followed_id' => $feedback_followed_id,
                'feedback_startup' => 'false',
                'feedback_user_type' => '2',
                );
            $this->db->select('user_id')->from($table)->where($where);
            $query = $this->db->get();
            return $query->result_array();
        }
        return false;
    }
/*****************************************************************************/
    public function get_feedback_topic_count() {
        $table = $this->wx_table;
        $this->db->select('feedback_id')->from($table)->where('feedback_startup', 'true');
        $query = $this->db->get();
        return $query->num_rows();
    }
/*****************************************************************************/
    public function feedback_topic_new_join($feedback_id = 0) {
        if ($feedback_id > 0) {
            $table = $this->wx_table;
            $data = array(
                'feedback_newjoin' => 'true',
                );
            $this->db->where('feedback_id', $feedback_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
}

/* End of file wxm_feedback.php */
/* Location: ./application/models/core/wxm_feedback.php */
