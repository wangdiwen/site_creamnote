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
    public function admin_reply($feedback_id = 0, $feedback_content = '', $feedback_time = '', $admin_user_id = 0) {
        if ($feedback_id > 0 && $feedback_content && $feedback_time) {
            $table = $this->wx_table;
            $data = array(
                    'feedback_content' => $feedback_content,
                    'feedback_time' => $feedback_time,
                    'feedback_startup' => 'false',
                    'feedback_followed_id' => $feedback_id,
                    'feedback_user_type' => '1',
                    'user_id' => $admin_user_id,
                    'feedback_newjoin' => 'false',
                    );
            $this->db->insert($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function delete_topic_feedback($feedback_id = 0) {
        if ($feedback_id > 0) {
            // first, del the topic data
            $del_ret = $this->delete_one_feedback($feedback_id);
            if ($del_ret) {
                // second, del the followed data
                $table = $this->wx_table;
                $where = array(
                    'feedback_followed_id' => $feedback_id,
                    );
                $this->db->where($where);
                $this->db->delete($table);
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function delete_one_feedback($feedback_id = 0) {
        if ($feedback_id > 0) {
            $table = $this->wx_table;
            $where = array(
                'feedback_id' => $feedback_id,
                );
            $this->db->where($where);
            $this->db->delete($table);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function is_topic_feedback($feedback_id = 0) {
        if ($feedback_id > 0) {
            $table = $this->wx_table;
            $this->db->select('feedback_startup')->from($table)->where('feedback_id', $feedback_id)->limit(1);
            $query = $this->db->get();
            $info = $query->row_array();
            if ($info && $info['feedback_startup'] == 'true') {
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function get_single_feedback_topic($feedback_followed_id = 0) {
        $data = array();
        if ($feedback_followed_id > 0) {
            $table = $this->wx_table;
            $user_table = 'wx_user';
            $admin_table = 'wx_admin_user';

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

            $admin_user_id_list = array();
            $common_user_id_list = array();
            if ($data) {
                foreach ($data as $key => $value) {
                    if ($value['feedback_user_type'] == '2') {
                        $common_user_id_list[] = $value['user_id'];
                    }
                    else {
                        $admin_user_id_list[] = $value['user_id'];
                    }
                }
            }

            // get common user info
            $common_user_info = array();
            if ($common_user_id_list) {
                $this->db->select('user_id, user_name')->from($user_table)->where_in('user_id', $common_user_id_list);
                $query = $this->db->get();
                $common_user_info = $query->result_array();
            }

            $common_user_map = array();
            if ($common_user_info) {
                foreach ($common_user_info as $key => $value) {
                    $common_user_map[$value['user_id']] = array();
                    $common_user_map[$value['user_id']]['user_name'] = $value['user_name'];
                }
            }

            // get admin user info
            $admin_user_name = '';
            $admin_user_info = array();
            if ($admin_user_id_list) {
                $this->db->select('user_name')->from($admin_table)->where('user_id', $admin_user_id_list[0]['user_id'])->limit(1);
                $query = $this->db->get();
                $admin_user_info = $query->row_array();
                if ($admin_user_info) {
                    $admin_user_name = $admin_user_info['user_name'];
                }
            }

            // process the data result
            foreach ($data as $key => $value) {
                if ($value['feedback_user_type'] == '2') {
                    $common_user_id = $value['user_id'];
                    $data[$key]['user_name'] = $common_user_map[$common_user_id]['user_name'];
                }
                elseif ($value['feedback_user_type'] == '1') {
                    $admin_user_id = $value['user_id'];
                    $data[$key]['user_name'] = '管理员'.$admin_user_name;
                }
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
    public function get_common_user_id_info_by_id($feedback_followed_id = 0) {
        if ($feedback_followed_id > 0) {
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
}

/* End of file wxm_feedback.php */
/* Location: /application/backend/models/wxm_feedback.php */
