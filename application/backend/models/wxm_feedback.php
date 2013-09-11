<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Feedback extends CI_Model
{
    var $wx_table = 'wx_feedback';
/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function get_feedback_topic_count() {
        $table = $this->wx_table;
        $where = array(
            'feedback_startup' => 'true',
            'feedback_newjoin' => 'true',
            );
        $this->db->select('feedback_id')->from($table)->where($where);
        $query = $this->db->get();
        return $query->num_rows();
    }
/*****************************************************************************/
    public function get_topic_page_info($per_page_limit = 5, $offset = 10) {
        $table = $this->wx_table;
        $where = array(
            'feedback_startup' => 'true',
            'feedback_newjoin' => 'true',
            );
        $this->db->select('feedback_id, feedback_content, feedback_time')->where($where)->order_by('feedback_time', 'desc');
        $query = $this->db->get($table, $per_page_limit, $offset);
        return $query->result_array();
    }
/*****************************************************************************/
    public function admin_reply($feedback_id = 0, $feedback_content = '', $feedback_time = '') {
        if ($feedback_id > 0 && $feedback_content && $feedback_time) {
            $table = $this->wx_table;
            $data = array(
                    'feedback_content' => $feedback_content,
                    'feedback_time' => $feedback_time,
                    'feedback_startup' => 'false',
                    'feedback_followed_id' => $feedback_id,
                    'feedback_user_type' => '1',
                    'feedback_newjoin' => '',
                    );
            $this->db->insert($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function get_single_feedback_topic($feedback_followed_id = 0) {
        $data = array();
        if ($feedback_followed_id > 0) {
            $table = $this->wx_table;
            $join_table = 'wx_user';
            $join_where = $join_table.'.user_id = '.$table.'.user_id';

            $this->db->select('feedback_id, feedback_content, feedback_time, feedback_startup, feedback_user_type, wx_feedback.user_id, user_name')->from($table)->join($join_table, $join_where, 'left')->where('feedback_id', $feedback_followed_id)->limit(1);
            $query = $this->db->get();
            $topic = $query->row_array();
            array_push($data, $topic);

            $this->db->select('feedback_id, feedback_content, feedback_time, feedback_startup, feedback_user_type, wx_feedback.user_id, user_name')->from($table)->join($join_table, $join_where, 'left')->where('feedback_followed_id', $feedback_followed_id)->order_by('feedback_time', 'asc');
            $query = $this->db->get();
            $followed_data = $query->result_array();

            // filter admin notice, add admin name '管理员'
            foreach ($followed_data as $key => $value) {
                $user_name = $value['user_name'];
                if (! $user_name) {
                    $followed_data[$key]['user_name'] = 'Creamnote管理员';
                }
            }

            if ($followed_data) {
                $data = array_merge($data, $followed_data);
            }
        }
        return $data;
    }
/*****************************************************************************/
    public function pass_feedback_topic($feedback_id = 0) {  // topic id
        if ($feedback_id > 0) {
            $table = $this->wx_table;
            $data = array(
                'feedback_newjoin' => 'false',
                );
            $this->db->where('feedback_id', $feedback_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
}

/* End of file wxm_feedback.php */
/* Location: /application/backend/models/wxm_feedback.php */
