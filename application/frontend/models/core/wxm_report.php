<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Report extends CI_Model
{
    var $wx_table = 'wx_report';
/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function commit_new_report($info = array()) {
        $com_title = $info['com_title'];
        $com_link = $info['com_link'];
        $com_note_name = $info['com_note_name'];
        $com_user_email = $info['com_user_email'];
        $com_user_phone = $info['com_user_phone'];
        $com_describe = $info['com_describe'];
        $com_time = $info['com_time'];

        if ($com_title && $com_link && $com_user_email && $com_describe) {
            $table = $this->wx_table;
            $data = array(
                'com_title' => $com_title,
                'com_link' => $com_link,
                'com_note_name' => $com_note_name,
                'com_user_email' => $com_user_email,
                'com_user_phone' => $com_user_phone,
                'com_time' => $com_time,
                'com_status' => 'false',
                'com_admin_user' => '',
                'com_step' => '1',
                'com_result' => '',
                'com_describe' => $com_describe,
                );
            $this->db->insert($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
/*****************************************************************************/
/*****************************************************************************/
/*****************************************************************************/
/*****************************************************************************/
}

/* End of file wxm_report.php */
/* Location: /application/frontend/models/core/wxm_report.php */
