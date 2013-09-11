<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WXC_personal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('core/wxm_user');  // 加载用户表模型
        $this->load->model('core/wxm_user_activity');
        $this->load->model('core/wxm_user2carea');
        $this->load->model('core/wxm_data');  // 加载资料表
        $this->load->model('share/wxm_data2carea');
        $this->load->model('share/wxm_data2cnature');
        $this->load->model('share/wxm_data_activity');
        $this->load->model('core/wxm_message');
        $this->load->model('core/wxm_notify');
        $this->load->model('core/wxm_feedback');
        $this->load->model('core/wxm_comment');
        $this->load->model('core/wxm_follow');
        $this->load->model('core/wxm_message');
        $this->load->model('share/wxm_grade');
        $this->load->model('share/wxm_category_area');
        $this->load->model('share/wxm_category_nature');

        $this->load->library('wx_data');
    }
/*****************************************************************************/
    public function personal_base_tips()
    {
        $user_id = $this->input->post('user_id');  // ajax post require
        // echo $user_id.'<br />';

        if ($user_id > 0) {
            // get header-image, email, data-count, school, major etc.
            $data = array();

            $user_info = $this->wxm_user->get_email_by_id($user_id);
            if ($user_info) {
                $data['user_email'] = $user_info['user_email'];
                $header = wx_get_gravatar_image($user_info['user_email'], 50);
                $data['user_header'] = $header;
            }
            $user_activity = $this->wxm_user_activity->get_data_count($user_id);
            if ($user_activity) {
                $data['user_datacount'] = $user_activity['uactivity_datacount'];
                $data['user_download'] = $user_activity['uactivity_downloadcount'];
                $data['user_downloaded'] = $user_activity['uactivity_downloaded_count'];
            }
            $user_area = $this->wxm_user2carea->get_by_user_id($user_id);
            if ($user_area) {
                $major_id = $user_area['carea_id_major'];
                $school_id = $user_area['carea_id_school'];
                $major_info = $this->wxm_category_area->get_name_by_id($major_id);
                if ($major_info) {
                    $data['user_major'] = $major_info['carea_name'];
                }
                $school_info = $this->wxm_category_area->get_name_by_id($school_id);
                if ($school_info) {
                    $data['user_school'] = $school_info['carea_name'];
                }
            }

            // echoxml($data);
            echo json_encode($data);
        }
    }
/*****************************************************************************/
    // Personal centry page
    public function personal()
    {
        $data_info = $this->user_data_info();
        $user_info = $this->user_info();
        $user_level = $this->get_user_level();
        $user_notify = $this->get_notify();
        $like_count = $this->you_like_who_count();
        $liked_count = $this->who_like_you_count();

        // 更新一次用户的资料数量
        $user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        $user_email = isset($_SESSION['wx_user_email']) ? $_SESSION['wx_user_email'] : '';
        $user_data_count_old = $this->wxm_user_activity->get_data_count($user_id);
        $user_data_count_new = count($data_info['data_ok']);
        if ($user_data_count_new != $user_data_count_old)
        {
            $info = array(
                'user_id' => $user_id,
                'uactivity_datacount' => $user_data_count_new
                );
            $this->wxm_user_activity->update_data_count($info);
        }
        $head_url = wx_get_gravatar_image($user_email, 140);

        $data['data_info'] = $data_info;
        $data['user_info'] = $user_info;
        $data['user_level'] = $user_level;
        $data['user_notify'] = $user_notify;
        $data['like_count'] = $like_count;
        $data['liked_count'] = $liked_count;
        $data['head_url'] = $head_url;
        $this->load->view('personal/wxv_personal', $data);
    }
/*****************************************************************************/
    public function you_like_who_count()
    {
        $count = 0;
        $cur_user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        if ($cur_user_id > 0)
        {
            $count = $this->wxm_follow->you_like_who_count($cur_user_id);
        }
        // echoxml($count);
        return $count;
    }
/*****************************************************************************/
    public function who_like_you_count()
    {
        $count = 0;
        $cur_user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        if ($cur_user_id > 0)
        {
            $count = $this->wxm_follow->who_like_you_count($cur_user_id);
        }
        // echoxml($count);
        return $count;
    }
/*****************************************************************************/
    public function space()  // Test iface
    {
        $this->load->view('personal/wxv_personal');
    }
/*****************************************************************************/
    // 呈现个人资料的所有信息
    public function user_info()
    {
        $user_id = 0;
        if (isset($_SESSION['wx_user_id']) && $_SESSION['wx_user_id'] != '')
        {
            $user_id = $_SESSION['wx_user_id'];
        }
        $user_info = $this->wxm_user->user_info($user_id);
        $area_info = $this->wxm_user2carea->get_by_user_id($user_id);
        if ($area_info) {
            $school_id = $area_info['carea_id_school'];
            $major_id = $area_info['carea_id_major'];
            $school_info = $this->wxm_category_area->get_all_info($school_id);
            if ($school_info) {
                $user_info->user_school = $school_info->carea_name;
            }
            $major_info = $this->wxm_category_area->get_all_info($major_id);
            if ($major_info) {
                $user_info->user_major = $major_info->carea_name;
            }
        }
        // echoxml($user_info);
        return $user_info;
    }
/*****************************************************************************/
    // 呈现用户个人的所有资料信息
    public function user_data_info()
    {
        $cur_time = date('Y-m-d H:i:s');
        $yesterday_time = wx_get_yesterday_time();

        $user_id = 0;
        if (isset($_SESSION['wx_user_id']) && $_SESSION['wx_user_id'] != '')
        {
            $user_id = $_SESSION['wx_user_id'];
            $user_name = $_SESSION['wx_user_name'];
            $data_info = $this->wxm_data->user_data_info($user_id);

            foreach ($data_info as $key => $obj) {
                $data_id = $obj['data_id'];
                $data_upload_time = $obj['data_uploadtime'];
                $data_point = $obj['data_point'];

                // check data price
                $data_price = $obj['data_price'];
                if ($data_price == '0.00') {
                    $data_info[$key]['data_price'] = '免费';
                }
                else {
                    $data_info[$key]['data_price'] = '￥'.$data_price;
                }

                // add nice name
                $data_info[$key]['user_name'] = $user_name;
                // note download and view count info
                $this->db->select('dactivity_download_count, dactivity_view_count')->from('wx_data_activity')
                         ->where('data_id', $data_id)->limit(1);
                         $tmp_query = $this->db->get();
                $tmp_ret = $tmp_query->row_array();
                if ($tmp_ret) {
                    $data_info[$key]['dactivity_download_count'] = $tmp_ret['dactivity_download_count'];
                    $data_info[$key]['dactivity_view_count'] = $tmp_ret['dactivity_view_count'];
                }
                else {
                    $data_info[$key]['dactivity_download_count'] = 0;
                    $data_info[$key]['dactivity_view_count'] = 0;
                }
                // 查找对应的area学校,专业的名称
                $data_info[$key]['data_area_id_school'] = 0;
                $data_info[$key]['data_area_name_school'] = '';
                $data_info[$key]['data_area_id_major'] = 0;
                $data_info[$key]['data_area_name_major'] = '';

                $area_info = $this->wxm_data2carea->get_area_id($data_id);
                if ($area_info) {
                    // school info
                    $category_area_info = $this->wxm_category_area->get_all_info($area_info->carea_id_school);
                    if ($category_area_info) {
                        $data_info[$key]['data_area_id_school'] = $category_area_info->carea_id;
                        $data_info[$key]['data_area_name_school'] = $category_area_info->carea_name;
                    }
                    // major info
                    $category_area_info = $this->wxm_category_area->get_all_info($area_info->carea_id_major);
                    if ($category_area_info) {
                        $data_info[$key]['data_area_id_major'] = $category_area_info->carea_id;
                        $data_info[$key]['data_area_name_major'] = $category_area_info->carea_name;
                    }
                }

                // 查找对应的nature分类
                $nature_info = $this->wxm_data2cnature->get_nature_id($data_id);
                if ($nature_info) {
                    $category_nature_info = $this->wxm_category_nature->get_all_info($nature_info->cnature_id);
                    if ($category_nature_info) {
                        $data_info[$key]['data_nature_id'] = $category_nature_info->cnature_id;
                        $data_info[$key]['data_nature_name'] = $category_nature_info->cnature_name;
                    }
                }
                else {
                    $data_info[$key]['data_nature_id'] = 0;
                    $data_info[$key]['data_nature_name'] = '';
                }
                // filter the upload time info
                if ($data_upload_time >= $yesterday_time) {
                    // show hours
                    $diff = strtotime($cur_time) - strtotime($data_upload_time);
                    $diff_time = floor($diff/(60*60));
                    if ($diff_time > 0) {
                        $data_info[$key]['data_uploadtime'] = $diff_time.'小时之前';
                    }
                    else {
                        $data_info[$key]['data_uploadtime'] = '1小时内';
                    }
                }
                else {
                    // show year/mouth/day
                    $time_list = explode(' ', $data_upload_time);
                    $data_info[$key]['data_uploadtime'] = $time_list[0];
                }
                // filter the data point, output stars
                if ($data_point < 20) {
                    $data_info[$key]['data_point'] = 1;
                }
                else if ($data_point < 40) {
                    $data_info[$key]['data_point'] = 2;
                }
                else if ($data_point < 60) {
                    $data_info[$key]['data_point'] = 3;
                }
                else if ($data_point < 80) {
                    $data_info[$key]['data_point'] = 4;
                }
                else {
                    $data_info[$key]['data_point'] = 5;
                }
            }

            // 添加过滤，按照未完善和完善分成2部分
            $data = array();
            $data_ok = array();
            $data_waiting = array();
            $data_undefine = array();
            $data_unpass = array();
            foreach ($data_info as $row)
            {
                $status = $row['data_status'];
                if ($status == $this->wx_data->PUBLIC_FULL_VERIFY)  // 完善
                {
                    array_push($data_ok, $row);
                }
                elseif ($status == $this->wx_data->PUBLIC_UNFULL) // 未完善
                {
                    array_push($data_undefine, $row);
                }
                elseif ($status == $this->wx_data->PUBLIC_WAITING)   // 等待审核
                {
                    array_push($data_waiting, $row);
                }
                elseif ($status == $this->wx_data->PUBLIC_FULL_UNVERIFY) {
                    // 审核未通过
                    array_push($data_unpass, $row);
                }
            }

            $data['data_ok'] = $data_ok;
            $data['data_waiting'] = $data_waiting;
            $data['data_unpass'] = $data_unpass;
            $data['data_undefine'] = $data_undefine;
            // echoxml($data);
            return $data;
        }
    }
/*****************************************************************************/
/*****************************************************************************/
    // Delete the user account by e-mail
    public function delete_user()
    {
        $email = '';  // Test data
        if (isset($_SESSION['wx_user_email']) && $_SESSION['wx_user_email'] != '')
        {
            $email = $_SESSION['wx_user_email'];
            $data = '';
            $ret = $this->wxm_user->delete_user($email);
            if ($ret)
            {
                $data = 'Delete the user: ' . $email . ' success';
            }
            else
            {
                $data = 'Delete the user: ' . $email . ' failed';
            }

            echo $email.'<br />';
            echo $data;
        }
    }
/*****************************************************************************/
    // Update the user passwd
    public function update_passwd()
    {
        $old_password = $this->input->post('wx_password_old');
        $new_password = $this->input->post('wx_password_new');

        if (! $old_password || ! $new_password)
        {
            echo 'password-is-empty';
            return;
        }

        if (isset($_SESSION['wx_user_id']) && $_SESSION['wx_user_id'])
        {
            // check the old password is right or not
            $user_id = $_SESSION['wx_user_id'];
            $old_password_is_right = $this->wxm_user->check_password($user_id, $old_password);
            if (! $old_password_is_right)
            {
                echo 'old-password-wrong';
                return;
            }

            // update the new password
            $ret = $this->wxm_user->update_passwd($user_id, $new_password);
            if ($ret)
            {
                echo 'success';
                return;
            }
        }

        echo 'failed';
    }
/*****************************************************************************/
/*****************************************************************************/
    public function get_notify()
    {
        $notify = array(
            'feedback' => array(),      // 反馈通知,type = 1
            'message' => array(),       // 留言通知,type = 2
            'comment' => array(),       // 评论通知,type = 3
            'system' => array()         // 系统通知,type = 4
            );

        $user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : '';
        if ($user_id)
        {
            $notify_info = $this->wxm_notify->get_by_user_id($user_id);
            if ($notify_info)
            {
                foreach ($notify_info as $info)
                {
                    if ($info['notify_type'] == '1')
                    {
                        array_push($notify['feedback'], $info);
                    }
                    elseif ($info['notify_type'] == '2')
                    {
                        array_push($notify['message'], $info);
                    }
                    elseif ($info['notify_type'] == '3')
                    {
                        array_push($notify['comment'], $info);
                    }
                    elseif ($info['notify_type'] == '4')
                    {
                        array_push($notify['system'], $info);
                    }
                }
            }
        }

        // echoxml($notify);
        return $notify;
    }
/*****************************************************************************/
    public function get_single_notify()
    {
        $type = $this->input->post('notify_type');
        $params = $this->input->post('notify_params');
        $notify_id = $this->input->post('notify_id');

        $data = array();
        if ($type > 0 && $params)
        {
            switch ($type)
            {
                case '1':       // 反馈通知
                    $data = $this->wxm_feedback->get_single_feedback_topic($params);
                    // add gavatar head url
                    if ($data) {
                        foreach ($data as $key => $value) {
                            if ($value['user_email']) {
                                $data[$key]['head_url'] = wx_get_gravatar_image($value['user_email'], 40);
                            }
                            else {
                                $my_email = 'dw_wang126@126.com';
                                $data[$key]['head_url'] = wx_get_gravatar_image($my_email, 40);
                            }
                        }
                    }
                    break;
                case '2':       // 留言通知
                    $cur_user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
                    $data = $this->wxm_message->get_message($params, $cur_user_id);
                    // add gavatar head url
                    if ($data) {
                        foreach ($data as $key => $value) {
                            if ($value['user_email']) {
                                $data[$key]['head_url'] = wx_get_gravatar_image($value['user_email'], 40);
                            }
                            else {
                                $my_email = 'dw_wang126@126.com';
                                $data[$key]['head_url'] = wx_get_gravatar_image($my_email, 40);
                            }
                        }
                    }
                    break;
                case '3':       // 评论通知
                    $data_info = $this->wxm_data->get_data_info($params);
                    $first = array(
                        'data_id' => $params,
                        'data_name' => $data_info->data_name
                        );
                    array_push($data, $first);
                    $data_comment = $this->wxm_comment->get_by_data_id($params);
                    if ($data_comment) {
                        foreach ($data_comment as $key => $value) {
                            if ($value['user_email']) {
                                $data_comment[$key]['head_url'] = wx_get_gravatar_image($value['user_email'], 40);
                            }
                            else {
                                $my_email = 'dw_wang126@126.com';
                                $data_comment[$key]['head_url'] = wx_get_gravatar_image($my_email, 40);
                            }
                        }
                    }
                    $data = array_merge($data, $data_comment);
                    break;
                case '4':       // 系统通知
                    # 系统通知，目前只需要标题、正文
                    $data = $this->wxm_notify->get_sysinfo_by_id($notify_id);
                    break;
                default:
                    break;
            }
        }

        // echoxml($data);
        $json_data = json_encode($data);
        echo $json_data;

        // 后台删除通知数据
        fastcgi_finish_request();

        if ($type == '2')       // 留言的时候，要把看过的留言标记为已看过
        {
            // 标记留言已经被读过
            if ($data)
            {
                foreach ($data as $message)
                {
                    $message_id = $message['message_id'];
                    $this->wxm_message->update_message_view($message_id);
                }
            }
        }
        $this->_delete_notify($notify_id);
    }
/*****************************************************************************/
    public function _delete_notify($notify_id = 0)
    {
        if ($notify_id > 0)
        {
            $this->wxm_notify->delete_by_id($notify_id);
        }
    }
/*****************************************************************************/
    public function update_userinfo_page()
    {
        $base_info = $this->get_base_info();
        $data = array();
        $data['base_info'] = $base_info;
    	$this->load->view('personal/wxv_userInfo', $data);
    }
/*****************************************************************************/
    public function user_account_info()
    {
        if (isset($_SESSION['wx_user_id']) && $_SESSION['wx_user_id'])
        {
            $user_id = $_SESSION['wx_user_id'];
            $info = $this->wxm_user->get_user_account($user_id);
            // echoxml($info);
            echo json_encode($info);
        }
    }
/*****************************************************************************/
    public function update_user_account()
    {
        $user_account_name = $this->input->post('user_account_name');

        if (isset($_SESSION['wx_user_id']) && $_SESSION['wx_user_id'])
        {
            $user_id = $_SESSION['wx_user_id'];
            if ($user_id > 0 && $user_account_name)
            {
                $this->wxm_user->update_account_name($user_id, $user_account_name);
                echo 'success';
                return;
            }
        }
        echo 'failed';
    }
/*****************************************************************************/
    public function get_user_level()
    {
        if (isset($_SESSION['wx_user_id']) && $_SESSION['wx_user_id'])
        {
            $user_id = $_SESSION['wx_user_id'];

            // Todo...
            $level_info = $this->wxm_user_activity->get_user_level($user_id);
            if ($level_info)
            {
                return $level_info['uactivity_level'];
            }
        }
        return '1';
    }
/*****************************************************************************/
    public function get_base_info()
    {
        if (isset($_SESSION['wx_user_id']) && $_SESSION['wx_user_id'])
        {
            $user_id = $_SESSION['wx_user_id'];
            if ($user_id > 0)
            {
                $info = array(
                    'user_name' => '',
                    'user_hobby' => '',
                    'user_email' => '',
                    'user_period' => '',
                    'user_phone' => '',
                    'user_school' => '',
                    'user_school_id' => 0,
                    'user_major' => '',
                    'user_major_id' => 0
                    );
                $base_info = $this->wxm_user->get_base_info($user_id);
                $school_info = $this->wxm_user2carea->get_by_user_id($user_id);
                if ($base_info)
                {
                    $info['user_name'] = $base_info['user_name'];
                    $info['user_email'] = $base_info['user_email'];
                    $info['user_hobby'] = $base_info['user_hobby'];
                    $info['user_period'] = $base_info['user_period'];
                    $info['user_phone'] = $base_info['user_phone'];
                }
                if ($school_info)
                {
                    $area_info_school = $this->wxm_category_area->get_all_info($school_info['carea_id_school']);
                    if ($area_info_school)
                    {
                        $info['user_school'] = $area_info_school->carea_name;
                        $info['user_school_id'] = $area_info_school->carea_id;
                    }

                    $area_info_major = $this->wxm_category_area->get_all_info($school_info['carea_id_major']);
                    if ($area_info_major)
                    {
                        $info['user_major'] = $area_info_major->carea_name;
                        $info['user_major_id'] = $area_info_major->carea_id;
                    }
                }

                // echoxml($info);
                return $info;
            }
        }
    }
/*****************************************************************************/
    public function update_base_info()
    {
        $nice_name = $this->input->post('nice_name');
        $hobby = $this->input->post('hobby');
        $period = $this->input->post('period');
        $phone = $this->input->post('phone');
        $school_id = $this->input->post('school_id');
        $major_id = $this->input->post('major_id');

        if (isset($_SESSION['wx_user_id']) && $_SESSION['wx_user_id'])
        {
            $user_id = $_SESSION['wx_user_id'];
            if (! $user_id > 0)
            {
                echo 'failed';
                return;
            }

            // update core user info
            $info = array(
                'user_id' => $user_id,
                'user_name' => $nice_name,
                'user_hobby' => $hobby,
                'user_period' => $period,
                'user_phone' => $phone
                );
            $this->wxm_user->update_base_info($info);

            // update the school and major info
            $info = array(
                    'user_id' => $user_id,
                    'carea_id_major' => $major_id,
                    'carea_id_school' => $school_id
                    );
            $this->wxm_user2carea->update_school_major($info);

            echo 'success';
            return;
        }
        else
        {
            echo 'failed';
        }
    }
/*****************************************************************************/
    public function check_nice_name()
    {
        $nice_name = $this->input->post('nice_name');

        if (isset($_SESSION['wx_user_id']) && $_SESSION['wx_user_id'])
        {
            $user_id = $_SESSION['wx_user_id'];
            if ($user_id > 0)
            {
                $has_name = $this->wxm_user->check_nice_name($user_id, $nice_name);
                if (! $has_name)
                {
                    echo 'success';
                    return;
                }
            }
        }
        echo 'failed';
        return;
    }
/*****************************************************************************/
    public function check_phone()
    {
        $phone = $this->input->post('phone');

        if (! $phone)
        {
            echo 'success';
            return;
        }
        if (isset($_SESSION['wx_user_id']) && $_SESSION['wx_user_id'])
        {
            $user_id = $_SESSION['wx_user_id'];
            if ($user_id > 0)
            {
                $has_phone = $this->wxm_user->check_phone($user_id, $phone);
                if (! $has_phone)
                {
                    echo 'success';
                    return;
                }
            }
        }
        echo 'failed';
        return;
    }
/*****************************************************************************/
    // 查看留言的历史记录
    public function history_message()
    {
        if (isset($_SESSION['wx_user_id']) && $_SESSION['wx_user_id'])
        {
            $user_id = $_SESSION['wx_user_id'];
            if ($user_id > 0)
            {
                $his_msg = $this->wxm_message->history_message($user_id);
                // echoxml($his_msg);
                if ($his_msg)
                {
                    $to_user_id_list = array();
                    foreach ($his_msg as $msg)
                    {
                        $to_user_id = $msg['message_to_user_id'];
                        array_push($to_user_id_list, $to_user_id);
                    }
                    $to_user_id_list = array_unique($to_user_id_list);
                    // echoxml($to_user_id_list);
                    $to_user_info = $this->wxm_user->get_by_id_list($to_user_id_list);
                    // echoxml($to_user_info);

                    $user_id_map = array();
                    foreach ($to_user_info as $info)
                    {
                        $user_id_map[$info['user_id']] = $info['user_name'];
                    }
                    // echoxml($user_id_map);

                    for ($i = 0; $i < count($his_msg); $i++)
                    {
                        $to_user_id = $his_msg[$i]['message_to_user_id'];
                        $his_msg[$i]['to_user_name'] = $user_id_map[$to_user_id];
                    }
                    // echoxml($his_msg);
                    echo json_encode($his_msg);
                }
            }
        }
    }
/*****************************************************************************/
    // 查看浏览过的资料历史记录
    public function history_view_data()
    {

    }
/*****************************************************************************/
/*****************************************************************************/
}

/* End of file wxc_personal.php */
/* Location: ./application/controllers/primary/wxc_personal.php */
