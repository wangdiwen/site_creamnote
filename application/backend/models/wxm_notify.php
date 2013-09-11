<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Notify extends CI_Model
{
    var $wx_table = 'wx_notify';
/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function send_system_notify($user_id = 0, $title = '', $content = '') {
        if ($user_id > 0 && $title && $content) {
            $data = array(
                'notify_type' => '4',
                'notify_title' => $title,
                'notify_content' => $content,
                'user_id' => $user_id,
                'notify_params' => '',
                'notify_time' => date('Y-m-d H:i:s'),
                );
            $table = $this->wx_table;
            $this->db->insert($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function insert($info = array()) {
        $notify_type = $info['notify_type'];
        $notify_content = $info['notify_content'];
        $user_id = $info['user_id'];
        $notify_params = $info['notify_params'];
        $notify_time = $info['notify_time'];

        if ($notify_type && $notify_content && $user_id && $notify_params)
        {
            $data = array(
                'notify_type' => $notify_type,
                'notify_content' => $notify_content,
                'user_id' => $user_id,
                'notify_params' => $notify_params,
                'notify_time' => $notify_time,
                );
            $table = $this->wx_table;
            $this->db->insert($table, $data);
        }
    }
/*****************************************************************************/
    public function has_feedback_topic($user_id = 0, $feedback_id = 0) {
        if ($user_id > 0 && $feedback_id > 0) {
            $table = $this->wx_table;
            $where = array(
                'notify_type' => '1',
                'user_id' => $user_id,
                'notify_params' => $feedback_id
                );
            $this->db->select('notify_id')->from($table)->where($where);
            $query = $this->db->get();
            $rows_num = $query->num_rows();
            if ($rows_num) {
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
}

/* End of file wxm_notify.php */
/* Location: /application/backend/models/wxm_notify.php */
