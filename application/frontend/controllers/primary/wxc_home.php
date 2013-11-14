<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXC_Home extends CI_Controller
{
/*****************************************************************/
    public function __construct()
    {
        parent::__construct();

        // Encrypt lib
        $this->load->library('encrypt');        // Load md5 encrypt lib iface
        $this->load->library('wx_email');       // Load the WX_Email library of ourself

        $this->load->model('core/wxm_user');
        $this->load->model('core/wxm_data');
        $this->load->model('share/wxm_data2carea');
        $this->load->model('share/wxm_data2cnature');
        $this->load->model('share/wxm_data_activity');
        $this->load->model('core/wxm_user_activity');
        $this->load->model('share/wxm_category_nature');
        $this->load->model('share/wxm_category_area');
        $this->load->model('core/wxm_user2carea');
        $this->load->model('core/wxm_week_article');
        $this->load->model('core/wxm_notice');

        // below is test lib iface
        $this->load->library('wx_tcpdfapi');
        // $this->load->library('wx_imageapi');
        // $this->load->library('wx_aliossapi');
        $this->load->library('wx_general');
        $this->load->library('wx_util');
        // $this->load->library('wx_alipay_direct_api');
        // $this->load->library('wx_alipay_refund_api');
        // $this->load->library('wx_weibo_api');
        $this->load->library('wx_site_manager');
        // $this->load->library('wx_weibo_renren_api');  // test
    }
/*****************************************************************/
    // Home page entry
    public function index()
    {
        // get recommend notes info
        $recommend_notes = $this->get_recommend_notes();
        // get super user
        $super_users = $this->get_super_users();
        $week_article = $this->get_week_article_four();
        $site_notice = $this->get_site_notice();

        $data = array();
        $data['recommend_notes'] = $recommend_notes;
        $data['super_users'] = $super_users;
        $data['week_article'] = $week_article;
        $data['site_notice'] = $site_notice;

        // check auto login or not
        $cur_user_info = $this->wx_util->get_user_session_info();
        $cur_user_id = $cur_user_info['user_id'];

        if (! $cur_user_id > 0) {  // not login yet
            // check auto login or not
            $cookie_user_id = $this->session->userdata('user_id');
            $cookie_session_id = $this->session->userdata('rem_session_id');
            if ($cookie_user_id && $cookie_session_id) {
                $ret = $this->auto_login($cookie_user_id, $cookie_session_id);
            }
        }

        $this->load->view('entry/wxv_home', $data);  // Home page
    }
/*****************************************************************************/
    public function get_site_notice() {
        $site_notice = $this->wxm_notice->get_site_notice();
        return $site_notice;
    }
/*****************************************************************************/
    public function get_week_article_four()
    {
        $four_article = $this->wxm_week_article->get_article_four();
        return $four_article;
    }
/*****************************************************************************/
    public function get_super_users()
    {
        // $super_users = $this->wxm_user_activity->get_super_users();
        $super_users = $this->wxm_user->get_super_users_by_new_user();
        if ($super_users) {
            foreach ($super_users as $key => $value) {
                $user_id = $value['user_id'];
                $user_email = $value['user_email'];
                $user_register_time = substr($value['user_register_time'], 0, 10);
                $super_users[$key]['user_register_time'] = $user_register_time;
                $super_users[$key]['user_head_url'] = wx_get_gravatar_image($user_email, 32);
                $school_info = $this->wx_general->add_user_school_major($user_id);
                if ($school_info) {
                    $super_users[$key]['user_school'] = $school_info['user_school'];
                    $super_users[$key]['user_major'] = $school_info['user_major'];
                }
            }
        }

        // wx_echoxml($super_users);
        return $super_users;
    }
/*****************************************************************************/
    public function get_recommend_notes()
    {
        $recommend_notes = $this->wxm_data->latest_top_ten();
        $cur_time = date('Y-m-d H:i:s');
        $yesterday_time = wx_get_yesterday_time();

        // get user's collect data info
        $collect_list = $this->wx_general->get_user_collect_list();

        // find user_id -> user_name and other info
        if ($recommend_notes) {
            foreach ($recommend_notes as $key => $obj) {
                $data_id = $obj['data_id'];
                $user_id = $obj['user_id'];
                $data_upload_time = $obj['data_uploadtime'];
                $data_point = $obj['data_point'];

                // check price
                $data_price = $obj['data_price'];
                if ($data_price == '0.00') {
                    $recommend_notes[$key]['data_price'] = '免费';
                }
                else {
                    $recommend_notes[$key]['data_price'] = '￥'.$data_price;
                }

                // user nice name info
                $this->db->select('user_name')->from('wx_user')->where('user_id', $user_id)->limit(1);
                $tmp_query = $this->db->get();
                $tmp_ret = $tmp_query->row_array();
                if ($tmp_ret) {
                    $recommend_notes[$key]['user_name'] = $tmp_ret['user_name'];
                }
                else {
                    $recommend_notes[$key]['user_name'] = 'unknown user';
                }

                // collect data info
                if ($collect_list && in_array($obj['data_id'], $collect_list)) {
                    $recommend_notes[$key]['collect'] = 'true';
                }
                else {
                    $recommend_notes[$key]['collect'] = 'false';
                }

                // note download and view count info
                $this->db->select('dactivity_download_count, dactivity_view_count')->from('wx_data_activity')
                         ->where('data_id', $data_id)->limit(1);
                $tmp_query = $this->db->get();
                $tmp_ret = $tmp_query->row_array();
                if ($tmp_ret) {
                    $recommend_notes[$key]['dactivity_download_count'] = $tmp_ret['dactivity_download_count'];
                    $recommend_notes[$key]['dactivity_view_count'] = $tmp_ret['dactivity_view_count'];
                }
                else {
                    $recommend_notes[$key]['dactivity_download_count'] = 0;
                    $recommend_notes[$key]['dactivity_view_count'] = 0;
                }

                // 查找对应的area学校,专业的名称
                $recommend_notes[$key]['data_area_id_school'] = 0;
                $recommend_notes[$key]['data_area_name_school'] = '';
                $recommend_notes[$key]['data_area_id_major'] = 0;
                $recommend_notes[$key]['data_area_name_major'] = '';

                $area_info = $this->wxm_data2carea->get_area_id($data_id);
                if ($area_info) {
                    // school info
                    $category_area_info = $this->wxm_category_area->get_all_info($area_info->carea_id_school);
                    if ($category_area_info) {
                        $recommend_notes[$key]['data_area_id_school'] = $category_area_info->carea_id;
                        $recommend_notes[$key]['data_area_name_school'] = $category_area_info->carea_name;
                    }
                    // major info
                    $category_area_info = $this->wxm_category_area->get_all_info($area_info->carea_id_major);
                    if ($category_area_info) {
                        $recommend_notes[$key]['data_area_id_major'] = $category_area_info->carea_id;
                        $recommend_notes[$key]['data_area_name_major'] = $category_area_info->carea_name;
                    }
                }

                // 查找对应的nature分类
                $nature_info = $this->wxm_data2cnature->get_nature_id($data_id);
                if ($nature_info) {
                    $category_nature_info = $this->wxm_category_nature->get_all_info($nature_info->cnature_id);
                    if ($category_nature_info) {
                        $recommend_notes[$key]['data_nature_id'] = $category_nature_info->cnature_id;
                        $recommend_notes[$key]['data_nature_name'] = $category_nature_info->cnature_name;
                    }
                }
                else {
                    $recommend_notes[$key]['data_nature_id'] = 0;
                    $recommend_notes[$key]['data_nature_name'] = '';
                }

                // filter the upload time info
                if ($data_upload_time >= $yesterday_time) {
                    // show hours
                    $diff = strtotime($cur_time) - strtotime($data_upload_time);
                    $diff_time = floor($diff/(60*60));
                    if ($diff_time > 0) {
                        $recommend_notes[$key]['data_uploadtime'] = $diff_time.'小时之前';
                    }
                    else {
                        $recommend_notes[$key]['data_uploadtime'] = '1小时内';
                    }
                }
                else {
                    // show year/mouth/day
                    $time_list = explode(' ', $data_upload_time);
                    $recommend_notes[$key]['data_uploadtime'] = $time_list[0];
                }

                // filter the data point, output stars
                if ($data_point < 20) {
                    $recommend_notes[$key]['data_point'] = 1;
                }
                else if ($data_point < 40) {
                    $recommend_notes[$key]['data_point'] = 2;
                }
                else if ($data_point < 60) {
                    $recommend_notes[$key]['data_point'] = 3;
                }
                else if ($data_point < 80) {
                    $recommend_notes[$key]['data_point'] = 4;
                }
                else {
                    $recommend_notes[$key]['data_point'] = 5;
                }
            }
        }
        // echoxml($recommend_notes);
        return $recommend_notes;
    }
/*****************************************************************************/
    public function get_server_ip()
    {
        $server_addr = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '121.199.4.71';
        return $server_addr;
    }
/*****************************************************************/
    public function page_404()  // Default 404 page of ourself
    {
        $this->load->view('share/page_404');
    }
/*****************************************************************/
    // Go to register page
    public function register_page()
    {
        $data = array();

        // add auth code
        $auth_code = $this->wx_util->get_auth_code();
        $data['auth_code'] = $auth_code;

        $this->load->view('entry/wxv_register',$data);
    }
/*****************************************************************/
    public function check_email()
    {
        $email = $this->input->post('wx_email');
        $has_email = $this->wxm_user->has_user($email);
        if ($has_email)
        {
            echo 'failed';
            return;
        }
        else
        {
            echo 'success';
            return;
        }
    }
/*****************************************************************/
    public function data_upload_page()
    {
        // 获得资料属性分类中的一级分类，@1：考研资料，@2：考试，@3：学习笔记
        $data = array();
        $data['first_nature'] = $this->wxm_category_nature->get_first_nature();
        $base_info = $this->wx_general->get_user_base_info();
        $data['base_user_info'] = $base_info;
        $this->load->view('data/wxv_uploadfile', $data);
    }
/*****************************************************************/
    public function image_upload_page()
    {
        // user info
        $data = array();
        $user_info = $this->wx_general->get_user_base_info();
        if ($user_info) {
            $data['base_user_info'] = $user_info;
        }
    	$this->load->view('data/wxv_uploadimage', $data);
    }
/*****************************************************************/
    public function set_browser_cookie($user_id = 0, $session_id = '')
    {
        //CI session
        $newdata = array(
               'user_id'  => $user_id,
               'rem_session_id' => $session_id,
           );
        $this->session->set_userdata($newdata);
    }
/*****************************************************************/
    // User's login, to check the form's data, e-mail and passwd
    public function login()
    {
        // Get the post data
        $email = $this->input->post('wx_email');
        $passwd = $this->input->post('wx_password');
        $if_auto_login = $this->input->post('if_auto_login');

        // filter space char
        $email = trim($email);
        $passwd = trim($passwd);

        $data['result'] = '';
        $ret = $this->wxm_user->login($email, $passwd);
        if ($ret == '2') {
            echo 'database-wrong';
        }
        elseif ($ret == '0') {
            echo 'passwd-wrong';
        }
        elseif ($ret == '1') {
            echo 'no-user';
        }
        elseif ($ret == '3') {
            echo 'user-close';
        }
        else {  // 正常登录
            // Record the CI session
            $user_info = $this->wxm_user->get_id_name($email);
            $user_id = $user_info->user_id;
            $user_name = $user_info->user_name;

            // 记录登录PHP SESSION用户数据
            $_SESSION['wx_user_id'] = $user_id;
            $_SESSION['wx_user_name'] = $user_name;
            $_SESSION['wx_user_email'] = $email;
            $session_id = session_id();

            // 记录登录信息到wx_user_activity关联表
            $ip = $this->get_login_ip();
            if($if_auto_login == "false") {   //不自动登录
                $session_id = '';
            }
            $login_time = date('Y-m-d H:i:s');
            $reset_day_downcount = false;
            $login_count = 0;
            $login_info = $this->wxm_user_activity->get_last_login_time($user_id);
            if ($login_info) {
                $last_login_time = $login_info['uactivity_logintime'];
                $login_count = $login_info['uactivity_logincount'];

                if (strncmp($login_time, $last_login_time, 10) > 0) {
                    $reset_day_downcount = true;
                }
            }

            $info = array(
                'user_id' => $user_id,
                'uactivity_loginip' => $ip,
                'uactivity_logintime' => $login_time,
                'uactivity_logincount' => $login_count + 1,  // 登录次数+1
                'session_id' => $session_id,
                'reset_day_downcount' => $reset_day_downcount,
                );
            $this->set_browser_cookie($user_id, $session_id);
            $this->wxm_user_activity->update_login($info);

            // Login successed
            $output = 'success';
            echo $output;

            // 后台处理
            fastcgi_finish_request();
            // 计算用户的等级，反应头像的权限
            $this->compute_user_level();
            // 计算用户的所有资料的data_point值
            $user_data_id = $this->wxm_data->get_data_id_list($user_id);
            if ($user_data_id) {
                foreach ($user_data_id as $data_id_info) {
                    $data_id = $data_id_info['data_id'];
                    $this->update_data_point($data_id);
                }
            }
        }
        return true;
    }
/*****************************************************************************/
    public function quick_login()
    {
        // 快速登录，支持QQ、微博、人人，目前只实现QQ接口

        $user_email = $this->input->post('user_email');
        $user_passwd = $this->input->post('user_passwd');

        $ajax = 'success';
        $ret = $this->wxm_user->login($user_email, $user_passwd);
        if ($ret == '0') {
            $ajax = 'passwd-wrong';
        }
        elseif ($ret == '1') {
            $ajax = 'no-user';
        }
        elseif ($ret == '2') {
            $ajax = 'database-wrong';
        }
        elseif ($ret == '3') {
            $ajax = 'user-close';
        }

        echo $ajax;  // ajax
        fastcgi_finish_request();

        if ($ajax == 'success') {
            // Record the CI session
            $user_info = $this->wxm_user->get_id_name($user_email);
            $user_id = $user_info->user_id;
            $user_name = $user_info->user_name;
            // get login count info
            $login_count = 0;
            $login_count_info = $this->wxm_user_activity->get_login_count($user_id);
            if ($login_count_info) {
                $login_count = $login_count_info['uactivity_logincount'];
            }

            // 记录登录PHP SESSION用户数据
            $_SESSION['wx_user_id'] = $user_id;
            $_SESSION['wx_user_name'] = $user_name;
            $_SESSION['wx_user_email'] = $user_email;

            // 记录登录信息到wx_user_activity关联表
            $ip = $this->get_login_ip();
            $info = array(
                'user_id' => $user_id,
                'uactivity_loginip' => $ip,
                'uactivity_logintime' => date('Y-m-d H:i:s'),
                'uactivity_logincount' => $login_count + 1,
                'session_id' => ''
                );
            $this->wxm_user_activity->update_login($info);

            // record qq bind account
            $qq_open_id = isset($_SESSION['qq_open_id']) ? $_SESSION['qq_open_id'] : '';
            $qq_nice_name = isset($_SESSION['qq_nice_name']) ? $_SESSION['qq_nice_name'] : '';
            if ($qq_open_id && $qq_nice_name) {
                $this->wxm_user->record_qq_account($user_id, $qq_open_id, $qq_nice_name);
                session_unregister('qq_open_id');
                session_unregister('qq_nice_name');
            }
            // record weibo bind account
            $weibo_open_id = isset($_SESSION['weibo_open_id']) ? $_SESSION['weibo_open_id'] : '';
            $weibo_nice_name = isset($_SESSION['weibo_nice_name']) ? $_SESSION['weibo_nice_name'] : '';
            if ($weibo_open_id && $weibo_nice_name) {
                $this->wxm_user->record_weibo_account($user_id, $weibo_open_id, $weibo_nice_name);
                session_unregister('weibo_open_id');
                session_unregister('weibo_nice_name');
            }
            // record renren bind account
            $renren_open_id = isset($_SESSION['renren_open_id']) ? $_SESSION['renren_open_id'] : '';
            $renren_nice_name = isset($_SESSION['renren_nice_name']) ? $_SESSION['renren_nice_name'] : '';
            if ($renren_open_id && $renren_nice_name) {
                $this->wxm_user->record_renren_account($user_id, $renren_open_id, $renren_nice_name);
                session_unregister('renren_open_id');
                session_unregister('renren_nice_name');
            }

            // 计算用户的等级，反应头像的权限
            $this->compute_user_level();
            // 计算用户的所有资料的data_point值
            $user_data_id = $this->wxm_data->get_data_id_list($user_id);
            if ($user_data_id) {
                foreach ($user_data_id as $data_id_info) {
                    $data_id = $data_id_info['data_id'];
                    $this->update_data_point($data_id);
                }
            }
        }
    }
/*****************************************************************/
    // User's login, to check the form's data, e-mail and passwd
    public function auto_login($cookie_user_id = 0, $cookie_session_id = '') {
        if (! $cookie_user_id || ! $cookie_session_id) {
            // echo 'failed';
            return false;
        }

        $user_session_info = $this->wxm_user_activity->check_auto_login($cookie_user_id);
        if ($user_session_info) {
            $user_session_id = $user_session_info['uactivity_session_id'];
            $login_count = $user_session_info['uactivity_logincount'];

            if ($cookie_session_id == $user_session_id) {  // 匹配成功
                $user_info = $this->wxm_user->get_id_name_by_user_id($cookie_user_id);
                if ($user_info) {
                    $user_id = $user_info['user_id'];
                    $user_name = $user_info['user_name'];
                    $user_email = $user_info['user_email'];

                    // record the session data
                    // 记录登录PHP SESSION用户数据
                    $_SESSION['wx_user_id'] = $user_id;
                    $_SESSION['wx_user_name'] = $user_name;
                    $_SESSION['wx_user_email'] = $user_email;
                    $session_id = session_id();

                    // 记录登录信息到wx_user_activity关联表
                    $ip = $this->get_login_ip();
                    $info = array(
                        'user_id' => $user_id,
                        'uactivity_loginip' => $ip,
                        'uactivity_logintime' => date('Y-m-d H:i:s'),
                        'uactivity_logincount' => $login_count + 1,
                        'session_id' => $session_id,
                        );
                    $this->wxm_user_activity->update_login($info);
                    $this->set_browser_cookie($user_id, $session_id);

                    // 计算用户的等级，反应头像的权限
                    $this->compute_user_level();
                    // 计算用户的所有资料的data_point值
                    $user_data_id = $this->wxm_data->get_data_id_list($user_id);
                    if ($user_data_id) {
                        foreach ($user_data_id as $data_id_info) {
                            $data_id = $data_id_info['data_id'];
                            $this->update_data_point($data_id);
                        }
                    }
                    return true;
                }
            }
        }
        return false;
    }
/*****************************************************************************/
/*****************************************************************************/
    public function update_data_point($data_id = 0)
    {
        // 算法：data_point的值
        // 1. 审核 = 满分50分（暂定）
        // 2. 资料打分，根据grade表3个字段算法得出，满分10分
        // 3. 评论，每条1分，>=10条，满分10分
        // 4. 下载数，每2次1分，>=20次，满分10分
        // 5. 浏览数，每5次1分，>=50次，满分10分
        // 6. 购买数，每1次2分，>=5次，满分10分
        if ($data_id > 0)
        {
            $activity_data = $this->wxm_data_activity->get_by_data_id($data_id);
            if ($activity_data)
            {
                $comment_count = $activity_data['dactivity_comment_count'];
                $download_count = $activity_data['dactivity_download_count'];
                $view_count = $activity_data['dactivity_view_count'];
                $buy_count = $activity_data['dactivity_buy_count'];
                $point_count = $activity_data['dactivity_point_count'];
                $examine_count = $activity_data['dactivity_examine_count'];

                $comment_count = $comment_count < 10 ? $comment_count : 10;
                $download_count = floor($download_count*0.5) < 10 ? floor($download_count*0.5) : 10;
                $view_count = floor($view_count*0.2) < 10 ? floor($view_count*0.2) : 10;
                $buy_count = floor($buy_count*2) < 10 ? floor($buy_count*2) : 10;

                $data_point = $examine_count + $point_count + $comment_count + $download_count + $view_count + $buy_count;
                // echo $data_point;
                // 更新到 wx_data表
                $data = array(
                    'data_id' => $data_id,
                    'data_point' => $data_point
                    );
                $this->wxm_data->update_point($data);
            }
        }
    }
/*****************************************************************/
    public function compute_user_level()
    {
        // 注意：此接口放在用户登录的时候自动触发
        // 等级的划分：共5个等级，加载5个不同的头像，分数100分
        // level-1: 0<= xxx < 10;
        // level-2: 10<= xxx < 40;
        // level-3: 40<= xxx < 80;
        // level-4: 80<= xxx < 100;
        // level-5: 100 <= xxx
        // 等级取决的因素：
        // 1. 资料数；每1份资料得1分，25份资料后的满分；
        // 2. 下载量；每下载2次得1分，下载超过50次得满分；
        // 3. 登录次数，每登录40次得1分，超过1000次得满分；
        // 4. 账户余额；每余额2元人民币得1分，超过50块的满分；
        $user_level = 0;
        $data_point = 0;
        $download_point = 0;
        $login_point = 0;
        $money_point = 0;

        $data_count = 0;
        $download_count = 0;
        $login_count = 0;
        $user_money = 0;

        // 从数据表中取得数据
        $user_id = 0;
        if (isset($_SESSION['wx_user_id']) && $_SESSION['wx_user_id'])
        {
            $user_id = $_SESSION['wx_user_id'];
            if ($user_id > 0)
            {
                $activity_info = $this->wxm_user_activity->get_all_by_user_id($user_id);
                if ($activity_info)
                {
                    $data_count = $activity_info['uactivity_datacount'];
                    $download_count = $activity_info['uactivity_downloadcount'];
                    $login_count = $activity_info['uactivity_logincount'];
                }
                $account_info = $this->wxm_user->get_user_account($user_id);
                if ($account_info)
                {
                    $user_money = $account_info['user_account_money'];
                }

                // 根据资料数得到分数
                if ($data_count > 25)
                {
                    $data_point = 25;
                }
                else
                {
                    $data_point = $data_count;
                }
                // 根据下载量得到分数
                if ($download_count > 50)
                {
                    $download_point = 25;
                }
                else
                {
                    $download_point = floor($download_count/2);
                }
                // 根据登录次数得到分数
                if ($login_count > 1000)
                {
                    $login_point = 25;
                }
                else
                {
                    $login_point = floor($login_count/40);
                }
                // 根据用户账户余额得到分数
                if ($user_money > 50)
                {
                    $money_point = 25;
                }
                else
                {
                    $money_point = floor($user_money/2);
                }
                // 计算用户等级分数
                $total = $data_point + $download_point + $login_point + $money_point;
                if ($total < 10)
                {
                    $user_level = 1;
                }
                elseif ($total >= 10 && $total < 40)
                {
                    $user_level = 2;
                }
                elseif ($total >= 40 && $total < 80)
                {
                    $user_level = 3;
                }
                elseif ($total >= 80 && $total < 100)
                {
                    $user_level = 4;
                }
                elseif ($total >= 100)
                {
                    $user_level = 5;
                }

                // 更新当前用户的等级
                if ($user_id > 0)
                {
                    $this->wxm_user_activity->update_level($user_id, $user_level);
                }
            }
        }
    }
/*****************************************************************/
    public function get_login_ip()
    {
        $ip = $_SERVER["REMOTE_ADDR"] ? $_SERVER["REMOTE_ADDR"] : 'unknow';
        // echoxml($_SERVER);  // 测试所有的环境变量
        return $ip;
    }
/*****************************************************************/
    // 注销登录
    public function logout()
    {
        // 记录注销的信息
        $user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        $logout_time = date('Y-m-d H:i:s');
        $info = array(
            'user_id' => $user_id,
            'uactivity_logouttime' => $logout_time
            );
        $this->wxm_user_activity->update_logout($info);

        // Clean the PHP session
        session_unregister("wx_user_id");
        session_unregister("wx_user_name");
        session_unregister('wx_user_email');
        session_unregister('area_id_major');
        session_unregister('area_id_school');
        session_destroy();
        //删除CI session
        $ci_session = array(
               'user_id'  => '',
               'rem_session_id' => '',
           );
        $this->session->unset_userdata($ci_session);
        // $this->session->sess_destroy();

        // For front ajax
        echo 'success';
    }
/*****************************************************************/
    // 注销登录页面url
    public function logout_page()
    {
        // And here, clean the user's e-mail data of session
        session_unregister("wx_user_id");
        session_unregister("wx_user_name");
        session_unregister('wx_user_email');
        session_unregister('area_id_major');
        session_unregister('area_id_school');
        session_destroy();
        // del ci session
        $ci_session = array(
               'user_id'  => '',
               'rem_session_id' => '',
           );
        $this->session->unset_userdata($ci_session);
        // $this->session->sess_destroy();
    }
/*****************************************************************/
    // 注册新用户
    public function register()
    {
        // 取得注册页面提交的数据
        $name = $this->input->post('wx_name');
        $email = $this->input->post('wx_email');
        $passwd = $this->input->post('wx_password');
        $area_id_major = $this->input->post('wx_area_id_major');
        $area_id_school = $this->input->post('wx_area_id_school');

        if (!$name || !$email || !$passwd || !$area_id_major || !$area_id_school)
        {
            redirect('home/page_404');
            return;
        }

        // 邮箱验证和激活功能
        // 激活链接为：1）随机码；2）昵称md5；3）用户邮箱md5；
        $random_code = rand(1024, 2048);
        $name_md5 = $this->encrypt->encode($name);
        $email_md5 = $this->encrypt->encode($email);
        $passwd_md5 = $this->encrypt->encode($passwd);

        // Active link like:
        // http://www.xxx.com/user_active?id=xxx&user_name=xxx&user_email=xxx&user_passwd=xxx
        $url = 'http://www.creamnote.com/user_active';
        $active_link = $url.'?id='.$random_code.'&user_name='.$name_md5.'&user_email='.$email_md5.'&user_passwd='.$passwd_md5;

        // 保存激活链接的3个字段到本地的session
        $_SESSION['random_code'] = $random_code;
        $_SESSION['active_name'] = $name;
        $_SESSION['active_email'] = $email;
        $_SESSION['active_passwd'] = $passwd;
        $_SESSION['area_id_major'] = $area_id_major;
        $_SESSION['area_id_school'] = $area_id_school;

        $ret = $this->_send_activelink($email, $active_link);
        if ($ret)
        {
            echo '发送注册邮箱的激活链接，成功！';
        }
        else
        {
            echo '发送注册激活链接，失败！';
        }
    }
/*****************************************************************/
    // 验证从邮箱链接得到的数据
    public function check_active()
    {
        // 从激活链接中得到3个字段：1）随机码；2）昵称md5；3）用户邮箱md5；
        $random_code = $this->input->get('id');
        $name_md5 = rawurldecode(URLencode($this->input->get('user_name')));
        $email_md5 = rawurldecode(URLencode($this->input->get('user_email')));
        $passwd_md5 = rawurldecode(URLencode($this->input->get('user_passwd')));

        // 取得第一次注册生成的激活字段
        $session_randomcode = isset($_SESSION['random_code']) ? $_SESSION['random_code'] : '';
        $session_name = isset($_SESSION['active_name']) ? $_SESSION['active_name'] : '';
        $session_email = isset($_SESSION['active_email']) ? $_SESSION['active_email'] : '';
        $session_passwd = isset($_SESSION['active_passwd']) ? $_SESSION['active_passwd'] : '';
        $session_area_id_major = isset($_SESSION['area_id_major']) ? $_SESSION['area_id_major'] : '';
        $session_area_id_school = isset($_SESSION['area_id_school']) ? $_SESSION['area_id_school'] : '';

        // third party social account
        $qq_open_id = isset($_SESSION['qq_open_id']) ? $_SESSION['qq_open_id'] : '';
        $qq_nice_name = isset($_SESSION['qq_nice_name']) ? $_SESSION['qq_nice_name'] : '';
        $weibo_open_id = isset($_SESSION['weibo_open_id']) ? $_SESSION['weibo_open_id'] : '';
        $weibo_nice_name = isset($_SESSION['weibo_nice_name']) ? $_SESSION['weibo_nice_name'] : '';
        $renren_open_id = isset($_SESSION['renren_open_id']) ? $_SESSION['renren_open_id'] : '';
        $renren_nice_name = isset($_SESSION['renren_nice_name']) ? $_SESSION['renren_nice_name'] : '';


        if ($session_randomcode)
        {
            // 比较URL字段中的Get数据
            $decode_name = $this->encrypt->decode($name_md5);
            $decode_email = $this->encrypt->decode($email_md5);
            $decode_passwd = $this->encrypt->decode($passwd_md5);

            if ($random_code == $session_randomcode
                && $decode_name == $session_name
                && $decode_email == $session_email)
            {
                // 说明，激活字段和本地session激活字段符合
                // 正式将用户注册信息写入数据表
                $ret = $this->wxm_user->register($session_name, $session_email, $decode_passwd);
                if ($ret == '0')        // Registe successed
                {
                    // $output = 'success';
                    // echo $output;
                    // 记录登录PHP SESSION用户数据
                    $_SESSION['wx_user_name'] = $session_name;
                    $_SESSION['wx_user_email'] = $session_email;

                    // 查找刚才注册的email的用户的id，然后插入user2area表
                    $data = $this->wxm_user->get_id_name($session_email);
                    if ($data)
                    {
                        $user_id = $data->user_id;
                        $_SESSION['wx_user_id'] = $user_id;
                        $info = array(
                            'carea_id_major' => $session_area_id_major,
                            'carea_id_school' => $session_area_id_school,
                            'user_id' => $user_id
                            );
                        $this->wxm_user2carea->insert($info);

                        // 同步插入 wx_user_activity表
                        $user_active = array(
                            'user_id' => $user_id,
                            'uactivity_datacount' => 0,
                            'uactivity_downloadcount' => 0,
                            'uactivity_loginip' => '',
                            'uactivity_logintime' => '',
                            'uactivity_logouttime' => '',
                            'uactivity_status' => 'false',
                            'uactivity_recent_view' => '',
                            'uactivity_logincount' => 0,
                            'uactivity_level' => 1
                            );
                        $this->wxm_user_activity->insert($user_active);

                        // record third party social account
                        if ($qq_open_id && $qq_nice_name) {
                            $this->wxm_user->record_qq_account($user_id, $qq_open_id, $qq_nice_name);
                        }
                        if ($weibo_open_id && $weibo_nice_name) {
                            $this->wxm_user->record_weibo_account($user_id, $weibo_open_id, $weibo_nice_name);
                        }
                        if ($renren_open_id && $renren_nice_name) {
                            $this->wxm_user->record_renren_account($user_id, $renren_open_id, $renren_nice_name);
                        }

                        // add new register user count, site manager db table
                        $this->wx_site_manager->add_new_register_user();
                    }
                }
                elseif($ret == '1')     // Has e-mail
                {
                    $output = 'has email';
                    echo $output;
                }
                elseif ($ret == '2')    // Has the nice name
                {
                    $output = 'has nice name';
                    echo $output;
                }

                // 删除用户激活字段
                session_unregister('random_code');
                session_unregister('active_name');
                session_unregister('active_email');
                session_unregister('active_passwd');
                session_unregister('area_id_major');
                session_unregister('area_id_school');
                // social account
                session_unregister('qq_open_id');
                session_unregister('qq_nice_name');
                session_unregister('weibo_open_id');
                session_unregister('weibo_nice_name');
                session_unregister('renren_open_id');
                session_unregister('renren_nice_name');

                redirect('/home/index');
            }
        }
        else
        {
            echo '您的激活链接已经失效，请重新注册，然后激活！';
        }
    }
/*****************************************************************/
    // 发送激活链接，私有方法
    public function _send_activelink($to_email = '', $link = '')
    {
        $content = '<html><head></head><body><p><b>亲爱的用户，欢迎您加入醍醐笔记网！</b></p><p></p><p>请点击下面的链接，激活您刚刚注册的醍醐账户</p><p><b><a href="'.$link.'">点击激活</a></b></p><p></p><p>友情提示 ^_^：如果上面的激活链接无法点击，可能是由于邮件服务提供商解析规则变动导致，请您复制完整的链接到浏览器地址栏，Enter键完成激活 ~~</p><p></p><p>激活链接：'.$link.'</p><p></p><p>Creamnote 醍醐笔记团队</p></body></html>';
        $this->wx_email->clear();

        $this->wx_email->set_from_user('no-reply@creamnote.com', '醍醐笔记');
        $this->wx_email->set_to_user($to_email);
        $this->wx_email->set_subject('醍醐注册激活链接');
        $this->wx_email->set_message($content);

        return $this->wx_email->send_email();
    }
/*****************************************************************/
    public function _database_info()
    {
        $info = $this->wxm_user->database_info();

        $data['platform'] = $info['platform'];
        $data['version'] = $info['version'];

        $this->load->view('share/test.php', $data);
    }
/*****************************************************************/
    public function skip_page()
    {
    	$mail_suffix = rawurldecode(URLencode($this->input->get('email')));
    	$data = array();
    	$data['url'] = $mail_suffix;
    	$this->load->view('entry/wxv_register_skip_page',$data);
    }
/*****************************************************************/
    // 密码找回功能，通过填写邮箱和验证码方式
/*****************************************************************/
    public function find_password_page()
    {
        $this->load->view('entry/wxv_find_password');
    }
/*****************************************************************/
    public function find_password_first_step()
    {
        $user_email = $this->input->post('user_email');

        // check the email
        if (! $user_email)
        {
            echo 'email-empty';
            return;
        }
        $has_email = $this->wxm_user->has_user($user_email);
        if (! $has_email)
        {
            echo 'has-no-email';
            return;
        }
        else
        {
            $auth_code = rand(102400, 204800); // 随机的验证码
            $_SESSION['user_email'] = $user_email;
            $_SESSION['auth_code'] = $auth_code;

            // ajax value
            echo 'success';
            // 后台处理
            fastcgi_finish_request();
            // 发送邮件
            $content = '您的验证码为：'.$auth_code;
            $ret = $this->_send_auth_code($user_email, $content);
            // loginfo($ret);
        }
    }
/*****************************************************************/
    public function find_password_sec_step()
    {
        $auth_code = $this->input->post('auth_code');

        if (isset($_SESSION['user_email']) && isset($_SESSION['auth_code']))
        {
            $user_email = $_SESSION['user_email'];
            $session_auth_code = $_SESSION['auth_code'];
            if ($user_email && $auth_code == $session_auth_code)
            {
                echo 'success';
                return;
            }
        }

        echo 'failed';
        return;
    }
/*****************************************************************/
    public function find_password_third_step()
    {
        $new_password = $this->input->post('new_password');
        $new_password = trim($new_password);

        if (isset($_SESSION['user_email']) && isset($_SESSION['auth_code']))
        {
            $user_email = $_SESSION['user_email'];
            $session_auth_code = $_SESSION['auth_code'];
            if ($user_email && $session_auth_code && $new_password)
            {
                $ret = $this->wxm_user->update_passwd_by_email($user_email, $new_password);
                if ($ret)
                {
                    session_unregister('user_email');
                    session_unregister('auth_code');

                    // Record the CI session
                    $user_info = $this->wxm_user->get_id_name($user_email);
                    $user_id = $user_info->user_id;
                    $user_name = $user_info->user_name;

                    // 记录登录信息到wx_user_activity关联表
                    $ip = $this->get_login_ip();
                    $info = array(
                        'user_id' => $user_id,
                        'uactivity_loginip' => $ip,
                        'uactivity_logintime' => date('Y-m-d H:i:s'),
                        'session_id' => ''
                        );
                    $this->wxm_user_activity->update_login($info);

                    // 记录登录PHP SESSION用户数据
                    $_SESSION['wx_user_id'] = $user_id;
                    $_SESSION['wx_user_name'] = $user_name;
                    $_SESSION['wx_user_email'] = $user_email;

                    echo 'success';

                    // 后台处理
                    fastcgi_finish_request();
                    $this->compute_user_level();
                }
            }
            else
            {
                echo 'failed';
            }
        }
        else
        {
            echo 'failed';
        }
    }
/*****************************************************************/
    public function _send_auth_code($to_email = '', $content = '')
    {
        if ($to_email && $content)
        {
            $this->wx_email->clear();

            $this->wx_email->set_from_user('no-reply@creamnote.com', '醍醐笔记');
            $this->wx_email->set_to_user($to_email);
            $this->wx_email->set_subject('找回密码的验证码');
            $this->wx_email->set_message($content);

            return $this->wx_email->send_email();
        }
        return false;
    }
/*****************************************************************/
    public function load_header()
    {
        $this->load->view('share/header');
    }
/*****************************************************************************/
    public function get_login_name()
    {
        $user_nice_name = isset($_SESSION['wx_user_name']) ? $_SESSION['wx_user_name'] : '';
        return $user_nice_name;
    }
/*****************************************************************************/
public function wx_substr_by_length_test($str = '', $sub_length = 0, $indent = 8) {
        if ($str && $sub_length > 0) {
            $sub_str_list = array();
            $str_len = mb_strlen($str, 'UTF-8');
            $sub_count = floor($str_len / $sub_length);
            $indent_count = floor($indent/2);
            if ($indent_count) {
                for ($i = 0; $i < $indent_count; $i++) {
                    $str = '  '.$str;
                }
            }

            if ($sub_count > 0) {
                for ($i = 0, $j = 0; $i < $sub_count; $i++) {
                    if ($indent_count && $i == 0) {
                        $tmp_str = mb_substr($str, $j, $sub_length + $indent_count, 'UTF-8');
                        $sub_str_list[] = '['.$tmp_str.']';
                        $j += $sub_length + $indent_count;
                    }
                    else {
                        $tmp_str = mb_substr($str, $j, $sub_length, 'UTF-8');
                        $sub_str_list[] = '['.$tmp_str.']';
                        $j += $sub_length;
                    }

                }
                $sub_total_len = $sub_length * $sub_count;
                if ($sub_total_len < $str_len) {
                    $end_str = mb_substr($str, $sub_total_len, $sub_length, 'UTF-8');
                    $sub_str_list[] = '['.$end_str.']';
                }
            }
            else {
                $sub_str_list[] = '['.$str.']';
            }
            return $sub_str_list;
        }
    }
/*****************************************************************/
    public function test()
    {

        // $pdf = 'upload/tmp/2013100821433354.pdf';
        // $data = file_get_contents($pdf);
        // $this->output->set_header("Content-type: application/pdf");
        // $this->output->set_output($data);

        // $summary = '嵌入式安全监控系统的嵌入式安全监控系统的嵌入式安全监控系统的嵌入式安全监控系统的'; // 40
        // // $summary = '        '.$summary;
        // $str_len = mb_strlen($summary, 'UTF-8');
        // wx_echoxml($str_len);
        // $str_list = $this->wx_substr_by_length($summary, 20);
        // wx_echoxml($str_list);

        // $ret = $this->wx_tcpdfapi->test();

        // $email = 'dw_wang126@126.com';
        // $link = 'http://www.creamnote.com/user_active';
        // $ret = $this->_send_activelink($email, $link);

        // $set_time = '00:01:00';
        // $after_tomorrow = date('Y-m-d', strtotime('+2 day'));
        // $after_tomorrow_time = $after_tomorrow.' '.$set_time;
        // echo $after_tomorrow_time;

    }
/*****************************************************************/
}

/* End of file wxc_home.php */
/* Location: ./application/controllers/primary/wxc_home.php */
