<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WXC_User_manager extends CI_Controller
{
/*****************************************************************/
    public function __construct()
    {
        parent::__construct();
        $this->load->model('core/wxm_follow');
        $this->load->model('core/wxm_user_activity');
        $this->load->model('core/wxm_user');
        // $this->load->model('core/wxm_data');

        $this->load->library('wx_util');
        $this->load->library('wx_general');
        $this->load->library('wx_weibo_renren_api');
    }
/*****************************************************************/
    public function add_follow()
    {
        $followed_user_id = $this->input->post('followed_user_id');
        $cur_user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        if ($followed_user_id > 0
            && $cur_user_id > 0
            && $cur_user_id != $followed_user_id)
        {
            // check has followed or not
            $has_followed = $this->wxm_follow->has_followed($cur_user_id, $followed_user_id);
            if (! $has_followed) {
                $data = array(
                    'follow_user_id' => $cur_user_id,
                    'follow_followed_user_id' => $followed_user_id
                    );
                $this->wxm_follow->insert($data);
            }
            echo 'success';
            return;
        }
        echo 'failed';
    }
/*****************************************************************/
    public function del_follow()
    {
        $followed_user_id = $this->input->post('followed_user_id');
        $cur_user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        if ($followed_user_id > 0
            && $cur_user_id > 0)
        {
            $data = array(
                'follow_user_id' => $cur_user_id,
                'follow_followed_user_id' => $followed_user_id
                );
            $this->wxm_follow->delete($data);
            // ajax
            echo 'success';
            return;
        }
        echo 'failed';
    }
/*****************************************************************/
    public function you_like_who()  // 关注
    {
        $cur_user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        if ($cur_user_id > 0)
        {
            $you_like_who = $this->wxm_follow->you_like_who($cur_user_id);
            if ($you_like_who) {
                $user_id_list = array();
                foreach ($you_like_who as $key => $value) {
                    $user_id_list[] = $value['follow_followed_user_id'];
                }
                $user_info = $this->wxm_user->get_by_id_list($user_id_list);
                if ($user_info) {
                    foreach ($you_like_who as $key => $value) {
                        $followed_user_id = $value['follow_followed_user_id'];
                        foreach ($user_info as $key_0 => $value_0) {
                            if ($followed_user_id == $value_0['user_id']) {
                                $you_like_who[$key]['user_name'] = $value_0['user_name'];
                            }
                        }
                    }
                }
            }
            // wx_echoxml($you_like_who);
            echo json_encode($you_like_who);
        }
    }
/*****************************************************************/
    public function who_like_you()  // 粉丝
    {
        $cur_user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        if ($cur_user_id > 0)
        {
            $who_like_you = $this->wxm_follow->who_like_you($cur_user_id);
            $you_like_who = $this->wxm_follow->you_like_who($cur_user_id);
            $you_like_who_userid_list = array();
            if ($you_like_who) {
                foreach ($you_like_who as $value) {
                    $you_like_who_userid_list[] = $value['follow_followed_user_id'];
                }
            }

            if ($who_like_you) {
                $user_id_list = array();
                foreach ($who_like_you as $key => $value) {
                    $user_id_list[] = $value['follow_user_id'];
                }
                $user_info = $this->wxm_user->get_by_id_list($user_id_list);
                if ($user_info) {
                    foreach ($who_like_you as $key => $value) {
                        $follow_user_id = $value['follow_user_id'];
                        // 查看粉丝中，是否有你已经关注的？
                        if ($you_like_who_userid_list && in_array($follow_user_id, $you_like_who_userid_list)) {
                            $who_like_you[$key]['has_followed'] = 'true';
                        }
                        else {
                            $who_like_you[$key]['has_followed'] = 'false';
                        }

                        // add user name info
                        foreach ($user_info as $key0 => $value_0) {
                            if ($follow_user_id == $value_0['user_id']) {
                                $who_like_you[$key]['user_name'] = $value_0['user_name'];
                            }
                        }
                    }
                }
            }

            // wx_echoxml($who_like_you);
            echo json_encode($who_like_you);
        }
    }
/*****************************************************************/
    public function collect_data()
    {
        $collect_data_id = $this->input->post('collect_data_id');

        // ajax respose: 'success' -> collect ok
        //               'failed'  -> already collected

        $cur_user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        if ($cur_user_id > 0 && $collect_data_id > 0) {
            $collect_str = '';

            $collect_info = $this->wxm_user_activity->get_collect_data($cur_user_id);
            if ($collect_info) {
                $collect_data_str = $collect_info['uactivity_collectdata'];
                if ($collect_data_str) {
                    $collect_list = explode(',', $collect_data_str);
                    if (in_array($collect_data_id, $collect_list)) {
                        echo 'has-collected'; // ajax respose
                        return true;
                    }
                    // collect most count: 20
                    if (count($collect_list) >= 20) {
                        array_splice($collect_list, 0, 1);
                    }
                    $collect_list[] = $collect_data_id;
                    $collect_str = join(',', $collect_list);
                }
                else {
                    $collect_str = $collect_str.$collect_data_id;
                }

                // update to db record
                if ($collect_str) {
                    $this->wxm_user_activity->set_collect_data($cur_user_id, $collect_str);
                }
                echo 'success';
                return true;
            }
        }
        echo 'failed';  // ajax respose
        return false;
    }
/*****************************************************************************/
    public function del_collect_data() {
        $collect_data_id = $this->input->post('collect_data_id');

        $cur_user_info = $this->wx_util->get_user_session_info();
        $user_id = $cur_user_info['user_id'];
        if ($user_id > 0 && $collect_data_id > 0) {
            $collect_info = $this->wxm_user_activity->get_collect_data($user_id);
            if ($collect_info) {
                $data_str = $collect_info['uactivity_collectdata'];
                if ($data_str) {
                    $data_id_list = explode(',', $data_str);
                    foreach ($data_id_list as $key => $value) {  // del collect id
                        if ($value == $collect_data_id) {
                            array_splice($data_id_list, $key, 1);
                        }
                    }
                    $collect_str = implode(',', $data_id_list);
                    if ($collect_str) {
                        $this->wxm_user_activity->set_collect_data($user_id, $collect_str);
                    }
                }
            }
        }
        echo 'success';
        return true;
    }
/*****************************************************************************/
    public function show_collect_data() {
        $cur_user_info = $this->wx_util->get_user_session_info();
        $user_id = $cur_user_info['user_id'];
        $data = array();
        if ($user_id > 0) {
            $collect_info = $this->wxm_user_activity->get_collect_data($user_id);
            if ($collect_info) {
                $data_str = $collect_info['uactivity_collectdata'];
                if ($data_str) {
                    $data_id_list = explode(',', $data_str);
                    $new_id_list = array_filter($data_id_list);
                    if ($new_id_list) {
                        foreach ($new_id_list as $key => $value) {
                            $data_info = $this->wx_general->get_data_card($value);
                            if ($data_info) {
                                $data[] = $data_info;
                            }
                        }
                    }
                }
            }
        }
        // wx_echoxml($data);
        echo json_encode($data);
    }
/*****************************************************************************/
    public function check_user_email()
    {
        $user_email = $this->input->input->post('user_email');
        $has_email = $this->wxm_user->has_user($user_email);
        if ($has_email)
            echo 'true';
        else
            echo 'false';  // ajax
    }
/*****************************************************************************/
    public function check_nice_name()
    {
        $nice_name = $this->input->post('user_nice_name');
        $has_nice_name = $this->wxm_user->has_name($nice_name);
        if ($has_nice_name)
            echo 'true';  // ajax
        else
            echo 'false';  // ajax
    }
/*****************************************************************************/
    public function qq_back_func()  // qq login, backup func
    {
        $qq_code = $this->input->get('code');
        if (! $qq_code) {
            redirect('home/index');
            return;
        }

        $token_url = 'https://graph.qq.com/oauth2.0/token';
        $qq_params = $this->wx_weibo_renren_api->get_qq_params();
        $token_params = array(
            'client_id' => $qq_params['appkey'],
            'client_secret' => $qq_params['appsecret'],
            'grant_type' => 'authorization_code',
            'code' => $qq_code,
            'redirect_uri' => $qq_params['redirect_url']
            );
        $token_data = $this->wx_weibo_renren_api->post_request($token_url, $token_params);
        // echo $token_data;

        // get access token
        $token_list = explode('&', $token_data);
        $tmp_list = explode('=', $token_list[0]);
        $qq_access_token = isset($tmp_list[1]) ? $tmp_list[1] : '';

        $qq_openid_url = 'https://graph.qq.com/oauth2.0/me';
        $params = array(
            'access_token' => $qq_access_token
            );
        $open_id_info = $this->wx_weibo_renren_api->post_request($qq_openid_url, $params);
        // echo $open_id_info;
        $json_str = preg_replace('/callback\( (.*) \);/i', '${1}', $open_id_info);
        // echo $json_str;
        $json_data = json_decode($json_str);
        $user_open_id = isset($json_data->openid) ? $json_data->openid : '';
        // echo $user_open_id;

        $user_info_url = 'https://graph.qq.com/user/get_user_info';
        $user_info_params = array(
            'openid' => $user_open_id,
            'access_token' => $qq_access_token,
            // 'format' => 'json',
            'oauth_consumer_key' => $qq_params['appkey']
            );
        $user_info = $this->wx_weibo_renren_api->get_request($user_info_url, $user_info_params);
        // echo $user_info;
        $user_info_json_data = json_decode($user_info);
        $user_nice_name = isset($user_info_json_data->nickname) ? $user_info_json_data->nickname : '';

        // check qq bind or not
        $has_qq_bind = $this->check_qq_bind($user_open_id, $user_nice_name);
        if ($has_qq_bind == '0') {      // 已经绑定了
            redirect('home/personal');
        }
        elseif ($has_qq_bind == '1') {      // 未绑定QQ，进入绑定提示页面
            $this->third_party_login($user_nice_name);
        }
        else {     // 用户被封号了
            redirect('static/wxc_direct/user_close_page');
        }
    }
/*****************************************************************************/
    public function check_qq_bind($qq_open_id = '', $qq_nice_name = '')  // bind qq or not
    {
        // $qq_open_id = $this->input->post('qq_open_id');
        // $qq_nice_name = $this->input->post('qq_nice_name');

        $has_qq_account = $this->wxm_user->has_qq_account($qq_open_id);
        if ($has_qq_account) {
            // active user session data
            // Record user session
            $user_info = $this->wxm_user->get_by_qq_openid($qq_open_id);
            if ($user_info) {
                $user_status = $user_info['user_status'];
                if ($user_status == 'false') {  // 被封号了
                    return '2';
                }

                // 记录登录PHP SESSION用户数据
                $_SESSION['wx_user_id'] = $user_info['user_id'];
                $_SESSION['wx_user_name'] = $user_info['user_name'];
                $_SESSION['wx_user_email'] = $user_info['user_email'];

                // update login time stamp
                $login_time = date('Y-m-d H:i:s');
                $login_ip = $this->wx_util->get_login_addr();
                $reset_day_downcount = false;

                $login_info = $this->wxm_user_activity->get_last_login_time($user_info['user_id']);
                if ($login_info) {
                    $last_login_time = $login_info['uactivity_logintime'];
                    if (strncmp($login_time, $last_login_time, 10) > 0) {
                        $reset_day_downcount = true;
                    }
                }
                $this->wxm_user_activity->update_login_time_ip($user_info['user_id'], $login_time, $login_ip, $reset_day_downcount);
            }
            // return true;
            return '0';
        }
        else {
            $_SESSION['qq_open_id'] = $qq_open_id;
            $_SESSION['qq_nice_name'] = $qq_nice_name;
            // return false;
            return '1';
        }
    }
/*****************************************************************************/
    public function third_party_login($user_nice_name = '')
    {
        $data = array();
        $data['login_area'] = '';
        $data['login_school'] = '';
        // add auth code
        $auth_code = $this->wx_util->get_auth_code();
        $data['auth_code'] = $auth_code;
        $data['user_nice_name'] = $user_nice_name;

        $this->load->view('entry/wxv_social_login', $data);
    }
/*****************************************************************************/
    public function show_third_party_account()
    {
        $cur_user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        if ($cur_user_id > 0) {
            $data = array();
            $third_info = $this->wxm_user->get_third_party_account($cur_user_id);
            if ($third_info) {
                $qq_open_id = $third_info['user_qq_openid'];
                $weibo_open_id = $third_info['user_weibo_openid'];
                $renren_open_id = $third_info['user_renren_openid'];
                if ($qq_open_id) {
                    $data[] = array(
                        'type' => 'qq',
                        'show_name' => '腾讯QQ',
                        'open_id' => $qq_open_id,
                        'nice_name' => $third_info['user_qq_nicename']
                        );
                }
                if ($weibo_open_id) {
                    $data[] = array(
                        'type' => 'weibo',
                        'show_name' => '新浪微博',
                        'open_id' => $weibo_open_id,
                        'nice_name' => $third_info['user_weibo_nicename']
                        );
                }
                if ($renren_open_id) {
                    $data[] = array(
                        'type' => 'renren',
                        'show_name' => '人人网',
                        'open_id' => $renren_open_id,
                        'nice_name' => $third_info['user_renren_nicename']
                        );
                }
            }
            // echoxml($data);
            echo json_encode($data);
        }
    }
/*****************************************************************************/
    public function del_third_party()
    {
        $cur_user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        $third_type = $this->input->post('third_party_type');

        if ($cur_user_id > 0 && $third_type) {
            $this->wxm_user->del_bind_third_party($cur_user_id, $third_type);
            echo 'success';
        }
        else
            echo 'failed';  // ajax
    }
/*****************************************************************************/
    public function weibo_back_func()
    {
        // get weibo access_token, user id, user nice name
        $weibo_code = $this->input->get('code');
        if (! $weibo_code) {
            redirect('home/index');
            return;
        }

        $url = 'https://api.weibo.com/oauth2/access_token';
        $weibo_params = $this->wx_weibo_renren_api->get_weibo_params();
        $params = array(
            'client_id' => $weibo_params['appkey'],
            'client_secret' => $weibo_params['appsecret'],
            'grant_type' => 'authorization_code',
            'code' => $weibo_code,
            'redirect_uri' => $weibo_params['redirect_url']
            );
        $ret_data = $this->wx_weibo_renren_api->post_request($url, $params);
        // echo $ret_data;

        $json_data = json_decode($ret_data);
        $weibo_access_token = isset($json_data->access_token) ? $json_data->access_token : '';
        $weibo_user_id = isset($json_data->uid) ? $json_data->uid : '';

        // get user info
        $weibo_get_user_info_url = 'https://api.weibo.com/2/users/show.json';
        $params = array(
            'uid' => $weibo_user_id,
            'access_token' => $weibo_access_token
            );
        $weibo_user_info = $this->wx_weibo_renren_api->get_request($weibo_get_user_info_url, $params);
        // echoxml($weibo_user_info);
        $json_user_data = json_decode($weibo_user_info);
        $weibo_user_nice_name = isset($json_user_data->screen_name) ? $json_user_data->screen_name : '';

        // check weibo bind or not
        $bind_weibo = $this->check_weibo_bind($weibo_user_id, $weibo_user_nice_name);
        if ($bind_weibo == '0') {  // 有绑定微博账户
            redirect('home/personal');
        }
        elseif ($bind_weibo == '1') {  // 没有绑定，进入绑定微博账户页面
            $this->third_party_login($weibo_user_nice_name);
        }
        else {  // 结果返回'2'，表示此用户被封号了
            redirect('static/wxc_direct/user_close_page');
        }
    }
/*****************************************************************************/

/*****************************************************************************/
    public function weibo_cancel_back_func()
    {
        // Todo...
    }
/*****************************************************************************/
    public function check_weibo_bind($weibo_open_id = '', $weibo_nice_name = '')
    {
        // $weibo_open_id = $this->input->post('weibo_open_id');
        // $weibo_nice_name = $this->input->post('weibo_nice_name');

        $has_weibo_account = $this->wxm_user->has_weibo_account($weibo_open_id);
        if ($has_weibo_account) {
            $user_info = $this->wxm_user->get_by_weibo_openid($weibo_open_id);
            if ($user_info) {
                $user_status = $user_info['user_status'];
                if ($user_status == 'false') {  // 用户被封号了
                    return '2';
                }

                // 记录登录PHP SESSION用户数据
                $_SESSION['wx_user_id'] = $user_info['user_id'];
                $_SESSION['wx_user_name'] = $user_info['user_name'];
                $_SESSION['wx_user_email'] = $user_info['user_email'];

                // update login time stamp
                $login_time = date('Y-m-d H:i:s');
                $login_ip = $this->wx_util->get_login_addr();
                $reset_day_downcount = false;

                $login_info = $this->wxm_user_activity->get_last_login_time($user_info['user_id']);
                if ($login_info) {
                    $last_login_time = $login_info['uactivity_logintime'];
                    if (strncmp($login_time, $last_login_time, 10) > 0) {
                        $reset_day_downcount = true;
                    }
                }
                $this->wxm_user_activity->update_login_time_ip($user_info['user_id'], $login_time, $login_ip, $reset_day_downcount);
                // return true;
                return '0';
            }
        }
        else {
            $_SESSION['weibo_open_id'] = $weibo_open_id;
            $_SESSION['weibo_nice_name'] = $weibo_nice_name;
            // return false;
            return '1';
        }
    }
/*****************************************************************************/
    public function renren_back_func()
    {
        $renren_code = $this->input->get('code');
        if (! $renren_code) {
            redirect('home/index');
            return;
        }

        $token_url = 'https://graph.renren.com/oauth/token';
        $renren_params = $this->wx_weibo_renren_api->get_renren_params();
        $params = array(
            'client_id' => $renren_params['appkey'],
            'client_secret' => $renren_params['appsecret'],
            'grant_type' => 'authorization_code',
            'code' => $renren_code,
            'redirect_uri' => $renren_params['redirect_url']
            );
        $ret_renren_data = $this->wx_weibo_renren_api->post_request($token_url, $params);
        // echo $ret_renren_data;
        $json_data = json_decode($ret_renren_data);
        $user_id = '';
        $user_name = '';
        if (isset($json_data->user)) {
            $renren_user_info = $json_data->user;
            $user_id = isset($renren_user_info->id) ? $renren_user_info->id : '';
            $user_name = isset($renren_user_info->name) ? $renren_user_info->name : '';
        }

        $has_renren_bind = $this->check_renren_bind($user_id, $user_name);
        if ($has_renren_bind == '0') {  // 已经绑定
            redirect('home/personal');
        }
        elseif ($has_renren_bind == '1') {  // 未绑定，进入绑定提示页面
            $this->third_party_login($user_name);
        }
        else {  // 用户被封号
            redirect('static/wxc_direct/user_close_page');
        }
    }
/*****************************************************************************/
    public function check_renren_bind($renren_open_id = '', $renren_nice_name = '')
    {
        // $renren_open_id = $this->input->post('renren_open_id');
        // $renren_nice_name = $this->input->post('renren_nice_name');

        $has_renren_account = $this->wxm_user->has_renren_account($renren_open_id);
        if ($has_renren_account) {
            $user_info = $this->wxm_user->get_by_renren_openid($renren_open_id);
            if ($user_info) {
                $user_status = $user_info['user_status'];
                if ($user_status == 'false') {
                    return '2';
                }

                // 记录登录PHP SESSION用户数据
                $_SESSION['wx_user_id'] = $user_info['user_id'];
                $_SESSION['wx_user_name'] = $user_info['user_name'];
                $_SESSION['wx_user_email'] = $user_info['user_email'];

                // update login time stamp
                $login_time = date('Y-m-d H:i:s');
                $login_ip = $this->wx_util->get_login_addr();
                $reset_day_downcount = false;

                $login_info = $this->wxm_user_activity->get_last_login_time($user_info['user_id']);
                if ($login_info) {
                    $last_login_time = $login_info['uactivity_logintime'];
                    if (strncmp($login_time, $last_login_time, 10) > 0) {
                        $reset_day_downcount = true;
                    }
                }
                $this->wxm_user_activity->update_login_time_ip($user_info['user_id'], $login_time, $login_ip, $reset_day_downcount);
                // return true;
                return '0';
            }
        }
        else {
            $_SESSION['renren_open_id'] = $renren_open_id;
            $_SESSION['renren_nice_name'] = $renren_nice_name;
            // return false;
            return '1';
        }
    }
/*****************************************************************************/

/*****************************************************************************/

/*****************************************************************************/
}

/* End of file wxc_user_manager.php */
/* Location: ./application/controllers/core/wxc_user_manager.php */
