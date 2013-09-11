<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feedback extends CI_Controller
{
/*****************************************************************************/
    public function __construct() {
        parent::__construct();
        $this->load->model('wxm_feedback');
        $this->load->model('wxm_notify');
        // $this->load->library('wx_util');
        $this->load->library('pagination');
    }
/*****************************************************************************/
    public function feedback_index($offset = 0) {
        $topic_count = $this->wxm_feedback->get_feedback_topic_count();
        $config = array(
            'base_url' => base_url().'cnadmin/feedback/feedback_index',
            'total_rows' => $topic_count,
            'per_page' => 5,
            'num_links' => 3,
            'uri_segment' => 4,
            'full_tag_open' => '<p>',
            'full_tag_close' => '</p>',
            'first_link' => '首页',
            'first_tag_open' => '<span>',
            'first_tag_close' => '</span>',
            'last_link' => '尾页',
            'last_tag_open' => '<span>',
            'last_tag_close' => '</span>',
            'next_link' => '下一页',
            'next_tag_open' => '<span>',
            'next_tag_close' => '</span>',
            'prev_link' => '上一页',
            'prev_tag_open' => '<span>',
            'prev_tag_close' => '</span>',
            'cur_tag_open' => '<span><a class="number current">',
            'cur_tag_close' => '</a></span>'
            );
        $this->pagination->initialize($config);
        $topic_page = $this->wxm_feedback->get_topic_page_info($config['per_page'], $offset);
        $data = array(
            'feedback_topic' => $topic_page,
            'feedback_offset' => $offset,
            );
        // wx_echoxml($data);
        $this->load->view('f_feedback/wxv_feedback', $data);
    }
/*****************************************************************************/
    public function get_topic_detail() {
        $feedback_id = $this->input->post('feedback_id');  // topic id

        $topic_detail = $this->wxm_feedback->get_single_feedback_topic($feedback_id);
        echo json_encode($topic_detail);
    }
/*****************************************************************************/
    public function admin_feedback_reply() {
        $feedback_id = $this->input->post('feedback_id');
        $feedback_content = $this->input->post('feedback_content');
        $feedback_topic = $this->input->post('feedback_topic');
        $feedback_user_id_list = $this->input->post('user_id_list');
        // $feedback_id = 1;
        // $feedback_content = '测试管理员回复功能';

        if ($feedback_id > 0 && $feedback_content) {
            $cur_time = date('Y-m-d H:i:s');
            $ret = $this->wxm_feedback->admin_reply($feedback_id, $feedback_content, $cur_time);
            // pass this topic
            $this->wxm_feedback->pass_feedback_topic($feedback_id);
            // send email to all join topic user, except admin
            if ($feedback_user_id_list) {
                $user_id_list = explode(',', $feedback_user_id_list);
                if ($user_id_list) {
                    foreach ($user_id_list as $user_id) {
                        if ($user_id && $user_id > 0) {
                            $notify = array(
                                'notify_type' => '1',
                                'notify_content' => '您有一条意见反馈信息：'.substr($feedback_topic, 0, 30).'...',
                                'user_id' => $user_id,
                                'notify_params' => $feedback_id,
                                'notify_time' => date('Y-m-d H:i:s'),
                                );
                            $has_notify = $this->wxm_notify->has_feedback_topic($user_id, $feedback_id);
                            if (! $has_notify) {
                                $this->wxm_notify->insert($notify);
                            }
                        }
                    }
                }
            }
            echo 'success';
            return true;
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function feedback_pass() {  // this topic not reply
        $feedback_id = $this->input->post('feedback_id');

        if ($feedback_id > 0) {
            $this->wxm_feedback->pass_feedback_topic($feedback_id);
            echo 'success';
            return true;
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
/*****************************************************************************/
/*****************************************************************************/
}

/* End of file feedback.php */
/* Location: /application/backend/controllers/feedback.php */
