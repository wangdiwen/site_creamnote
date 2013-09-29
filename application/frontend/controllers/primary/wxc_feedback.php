<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXC_Feedback extends CI_Controller
{
/*****************************************************************/
    public function __construct()
    {
        parent::__construct();
        $this->load->model('core/wxm_feedback');
        $this->load->model('core/wxm_notify');
        $this->load->model('core/wxm_data');
        $this->load->model('core/wxm_report');
        $this->load->model('core/wxm_admin_user');

        $this->load->library('wx_util');
        $this->load->library('pagination');
    }
/*****************************************************************/
    public function feedback_page($offset = 0)
    {
        $topic_count = $this->wxm_feedback->get_feedback_topic_count();
        $config = array(
            'base_url' => base_url().'primary/wxc_feedback/feedback_page',
            'total_rows' => $topic_count,
            'per_page' => 10,
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

        // get creamnote admin some base info,
        // like id, name, email
        $admin_base_info = $this->wxm_admin_user->get_all_admin_base_info();
        $admin_map = array();
        foreach ($admin_base_info as $key => $value) {
            $admin_user_id = $value['user_id'];
            $admin_map[$admin_user_id] = array(
                'user_name' => $value['user_name'],
                'user_email' => $value['user_email'],
                );
        }
        // wx_echoxml($admin_map);

        $topic_page = $this->wxm_feedback->get_feedback_info($config['per_page'], $offset);
        // add gaverter header
        foreach ($topic_page as $key => $value) {
            foreach ($value as $key_2 => $obj) {
                $user_type = $obj['feedback_user_type'];
                if ($user_type == '1') {  // is admin reply
                    $obj['user_email'] = isset($admin_map[$obj['user_id']]['user_email']) ? $admin_map[$obj['user_id']]['user_email'] : '';
                    $topic_page[$key][$key_2]['user_name'] = isset($admin_map[$obj['user_id']]['user_name']) ? '<span style="color:red;">Creamnote_'.$admin_map[$obj['user_id']]['user_name'].'</span>' : '0';
                    $topic_page[$key][$key_2]['user_email'] = isset($admin_map[$obj['user_id']]['user_email']) ? $admin_map[$obj['user_id']]['user_email'] : '';
                }

                $user_email = $obj['user_email'];
                if ($user_email) {
                    $topic_page[$key][$key_2]['head_url'] = wx_get_gravatar_image($user_email, 48);
                }
                else {  // admin header
                    $my_email = 'wangdiwen@creamnote.com';
                    $topic_page[$key][$key_2]['user_name'] = '<span style="color:red;">Creamnote_Admin</span>';
                    $topic_page[$key][$key_2]['head_url'] = wx_get_gravatar_image($my_email, 48);
                }
            }
        }
        // wx_echoxml($topic_page);

        $data = array(
            'feedback_topic' => $topic_page,
            // 'feedback_offset' => $offset,
            );
        $this->load->view('share/wxv_feedback', $data);
    }
/*****************************************************************/
    public function create_feedback()
    {
        $ajax = '';

        $feedback_content = $this->input->post('feedback_content');
        $feedback_time = date('Y-m-d H:i:s');
        $feedback_startup = 'true';
        $feedback_followed_id = '';
        $feedback_user_type = '2';          // 1->admin, 2->normal user
        $user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;

        if (! $user_id > 0)
        {
            // 在这里进行弹框，提示登录、或者注册
            $ajax = 'nologin';
            // redirect('home/register_page');
        }
        else
        {
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
                    'feedback_newjoin' => 'true',
                    );
                $this->wxm_feedback->insert($data);

                $feedback_info = $this->wxm_feedback->get_id_by_time($feedback_time);
                $feedback_id = $feedback_info->feedback_id;
                $user_nice_name = isset($_SESSION['wx_user_name']) ? $_SESSION['wx_user_name'] : '';
                $ajax = $feedback_id.','.$user_nice_name;
                // echo 'SUCCESS';
            }
            else
            {
                $ajax = '创建一条意见反馈的信息不全'.'<br />';
            }
        }

        echo $ajax;
    }
/*****************************************************************/
    public function follow_feedback()
    {
        $ajax = '';

        $feedback_topic = $this->input->post('feedback_topic');
        $feedback_content = $this->input->post('feedback_content');
        $feedback_followed_id = $this->input->post('feedback_id');       // 指向关注某个意见的primary id
        $feedback_user_id_list = $this->input->post('user_id_list');
        $feedback_time = date('Y-m-d H:i:s');
        $feedback_startup = 'false';
        $feedback_user_type = '2';      // 1->admin, 2->normal user
        $user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : '';

        if (! $user_id)
        {
            // 在这里进行弹框，提示登录、或者注册
            $ajax = 'nologin';
            echo $ajax;
            // redirect('home/register_page');
        }
        else
        {
            if ($feedback_content && $feedback_time && $feedback_startup
            && $feedback_followed_id && $feedback_user_type && $user_id && $feedback_user_id_list)
            {
                // update topic feedback status, new join
                $this->wxm_feedback->feedback_topic_new_join($feedback_followed_id);

                $data = array(
                    'feedback_content' => $feedback_content,
                    'feedback_time' => $feedback_time,
                    'feedback_startup' => $feedback_startup,
                    'feedback_followed_id' => $feedback_followed_id,
                    'feedback_user_type' => $feedback_user_type,
                    'user_id' => $user_id,
                    'feedback_newjoin' => '',
                    );
                $this->wxm_feedback->insert($data);

                // $feedback_info = $this->wxm_feedback->get_id_by_time($feedback_time);
                // $feedback_id = $feedback_info->feedback_id;

                $cur_user_info = $this->wx_util->get_user_session_info();
                $cur_user_name = $cur_user_info['user_name'];
                $cur_user_email = $cur_user_info['user_email'];
                $head_url = wx_get_gravatar_image($cur_user_email, 40);

                echo $cur_user_name.','.$head_url;
                // $ajax = $feedback_id.','.$cur_user_name.','.$cur_user_email;
                // echo $ajax;

                // 后台记录通知
                fastcgi_finish_request();

                $user_id_list = explode('&', $feedback_user_id_list);
                // 过滤掉数组中为空的项，以及重复的项
                $notify_user_id_list = array_unique(array_filter($user_id_list));
                // 过滤掉数组中与当前用户的user id相同的项
                foreach ($notify_user_id_list as $key => $value)
                {
                    if (! is_numeric($value)        // filter 'null' string
                        || $value == $user_id)
                    {
                        array_splice($notify_user_id_list, $key, 1);
                    }
                }
                foreach ($notify_user_id_list as $notify_user_id)
                {
                    $notify = array();
                    $notify['notify_type'] = '1';
                    $notify['notify_content'] = '您有一条意见反馈信息：'.substr($feedback_topic, 0, 30).'...';
                    $notify['user_id'] = $notify_user_id;
                    $notify['notify_params'] = $feedback_followed_id;
                    $notify['notify_time'] = $feedback_time;

                    // 检查user id中是否已经存在反馈话题的id的通知了？
                    // 如果存在，则不写入新的通知，反正则否
                    if ($notify['user_id'] && $notify['user_id'] > 0) {  // 排除管理员的回复
                        $has_notify = $this->wxm_notify->has_feedback_topic($notify['user_id'], $feedback_followed_id);
                        if (! $has_notify)
                        {
                            $this->wxm_notify->insert($notify);
                        }
                    }
                }
            }
            else
            {
                $ajax = 'failed';
                echo $ajax;
            }
        }
    }
/*****************************************************************************/
/**************************** 投诉 & 举报 ************************************/
/*****************************************************************************/
    public function report_page() {
        $data_id = $this->input->post('data_id');

        $data = array(
            'com_title' => '投诉&举报',
            'com_link' => '',
            'com_note_name' => '',
            'com_user_email' => '',
            'if_login' => 'false',
            );
        $user_info = $this->wx_util->get_user_session_info();
        if (isset($user_info['user_email']) && $user_info['user_email']) {
            $data['if_login'] = 'true';
        }

        if (is_numeric($data_id) && $data_id > 0) {
            $data['com_user_email'] = $user_info['user_email'];
            $data['com_link'] = 'http://www.creamnote.com/data/wxc_data/data_view/'.$data_id;
            $simple_info = $this->wxm_data->get_simple_info_by_id($data_id);
            if ($simple_info) {
                $data['com_note_name'] = $simple_info['data_name'];
            }
        }

        $this->load->view('share/wxv_complaint', $data);
    }
/*****************************************************************************/
    public function report_commit() {
        $com_title = $this->input->post('com_title');
        $com_link = $this->input->post('com_link');
        $com_note_name = $this->input->post('com_note_name');
        $com_user_email = $this->input->post('com_user_email');
        $com_user_phone = $this->input->post('com_user_phone');
        $com_describe = $this->input->post('com_describe');

        $com_time = date('Y-m-d H:i:s');
        if ($com_title && $com_link && $com_user_email && $com_describe) {
            $data = array(
                'com_title' => $com_title,
                'com_link' => $com_link,
                'com_note_name' => $com_note_name,
                'com_user_email' => $com_user_email,
                'com_user_phone' => $com_user_phone,
                'com_describe' => $com_describe,
                'com_time' => $com_time,
                );
            $ret = $this->wxm_report->commit_new_report($data);
            if ($ret) {
                echo 'success';
                return true;
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function test() {
        echo 'here';
    }
/*****************************************************************************/
}

/* End of file wxc_feedback.php */
/* Location: ./application/controllers/primary/wxc_feedback.php */
