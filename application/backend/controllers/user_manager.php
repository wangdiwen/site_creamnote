<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Manager extends CI_Controller
{
/*****************************************************************************/
    public function __construct()
    {
        parent::__construct();
        $this->load->model('wxm_admin_user');
        $this->load->model('wxm_user');
        $this->load->model('wxm_user2carea');
        $this->load->model('wxm_user_activity');
        $this->load->model('wxm_category_area');

        $this->load->library('wx_util');
    }
/*****************************************************************************/
    //       Customer User Manager
/*****************************************************************************/
    public function update_admin_password()
    {
        $user_email = $this->input->post('user_email');
        $user_password = $this->input->post('user_password');

        if ($user_email && $user_password) {
            $ret = $this->wxm_admin_user->update_user_password($user_email, $user_password);
            if ($ret) {
                echo 'success';
                return true;
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function del_admin_user()
    {
        $user_email = $this->input->get('user_email');
        // wx_loginfo($user_email);

        $data = array();
        $data['return_code'] = '';
        // only 'super' admin can do this
        $auth = $this->wx_util->check_admin_permission('super');
        if (! $auth) {
            $data['return_code'] = 'no-permission';
        }

        $ret = $this->wxm_admin_user->del_admin_user($user_email);
        if ($ret) {
            $data['return_code'] = 'success';
        }

        if (! $data['return_code'])
            $data['return_code'] =  'failed';

        $all_admin = $this->get_all_admin_user();
        if ($all_admin) {
            $data['admin_users'] = $all_admin;
        }
        $this->load->view('f_user/wxv_admin', $data);
    }
/*****************************************************************************/
    public function update_admin_info()
    {
        $user_email = $this->input->post('user_email');
        $user_name = $this->input->post('user_name');
        $user_status = $this->input->post('user_status');
        $user_token = $this->input->post('user_token');
        $user_type = $this->input->post('user_type');

        // filter space char
        $user_name = trim($user_name);
        $user_email = trim($user_email);
        $user_token = trim($user_token);

        // wx_loginfo($user_name);
        // wx_loginfo($user_email);
        // wx_loginfo($user_status);
        // wx_loginfo($user_type);
        // wx_loginfo($user_token);

        $auth = $this->wx_util->check_admin_permission('super');
        if (! $auth) {
            echo 'no-permission';
            return false;
        }

        if (! in_array($user_status, array('true', 'false'))
            || ! in_array($user_type, array('super', 'admin', 'common'))) {
            echo 'failed';
            return false;
        }

        if ($user_email && $user_name && $user_status && $user_token && $user_type) {
            $data = array(
                'user_name' => $user_name,
                'user_status' => $user_status,
                'user_type' => $user_type,
                'user_token' => $user_token
                );
            $ret = $this->wxm_admin_user->update_admin_info($user_email, $data);
            if ($ret) {
                echo 'success';
                return true;
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function create_admin_user()
    {
        $user_name = $this->input->post('user_name');
        $user_email = $this->input->post('user_email');
        $user_status = $this->input->post('user_status');
        $user_type = $this->input->post('user_type');
        $user_token = $this->input->post('user_token');
        $user_password = $this->input->post('user_password');

        // filter space char
        $user_name = trim($user_name);
        $user_email = trim($user_email);
        $user_token = trim($user_token);
        $user_password = trim($user_password);

        // wx_loginfo($user_name);
        // wx_loginfo($user_email);
        // wx_loginfo($user_status);
        // wx_loginfo($user_type);
        // wx_loginfo($user_token);
        // wx_loginfo($user_password);

        // only 'super' admin can do this
        $auth = $this->wx_util->check_admin_permission('super');
        if (! $auth) {
            echo 'no-permission';
            return false;
        }

        if (! $user_name || ! $user_email || ! $user_status
            || ! $user_type || ! $user_token || ! $user_password) {
            echo 'failed';
            return false;
        }

        if (! in_array($user_status, array('true', 'false'))
            || ! in_array($user_type, array('super', 'admin', 'common'))) {
            echo 'failed';
            return false;
        }

        $data = array(
                'user_name' => $user_name,
                'user_email' => $user_email,
                'user_status' => $user_status,
                'user_type' => $user_type,
                'user_token' => $user_token,
                'user_password' => $user_password,
                'user_register_time' => date('Y-m-d'),
                );
        $ret = $this->wxm_admin_user->create_admin($data);
        if ($ret) {
            echo 'success';
            return true;
        }
        echo 'failed';  // email or nice_name has already existed
        return false;
    }
/*****************************************************************************/
    public function init_admin_users() {
        $admin_0 = array(
            'user_name' => 'steven',
            'user_email' => 'wangdiwen@creamnote.com',
            'user_status' => 'true',
            'user_type' => 'super',
            'user_token' => 'fish',
            'user_password' => 'wangdiwen123!@#@creamnote',
            'user_register_time' => date('Y-m-d'),
            );
        $admin_1 = array(
            'user_name' => 'xiewang',
            'user_email' => 'xiewang@creamnote.com',
            'user_status' => 'true',
            'user_type' => 'super',
            'user_token' => 'cowboy',
            'user_password' => 'xiewang123!@#@creamnote',
            'user_register_time' => date('Y-m-d'),
            );
        $ret_0 = $this->wxm_admin_user->create_admin($admin_0);
        $ret_1 = $this->wxm_admin_user->create_admin($admin_1);

        if ($ret_0 && $ret_1) {
            echo 'Init Admin Users Success !!!';
        }
    }
/*****************************************************************************/
    public function admin_index()
    {
        $data = array();
        // get all admin users
        $all_admin = $this->get_all_admin_user();
        if ($all_admin) {
            $data['admin_users'] = $all_admin;
        }
        $this->load->view('f_user/wxv_admin', $data);
    }
/*****************************************************************************/
    public function get_all_admin_user()
    {
        $admin_users = $this->wxm_admin_user->get_all_admin_user();
        // wx_echoxml($admin_users);
        return $admin_users;
    }
/*****************************************************************************/
/*****************************************************************************/
    //       Customer User Manager
/*****************************************************************************/

/*****************************************************************************/
    public function enable_user()
    {
        $user_email = $this->input->post('user_email');

        $auth = $this->wx_util->check_admin_permission('super', 'admin');
        if (! $auth) {
            echo 'no-permission';
            return false;
        }

        if ($user_email) {
            $ret = $this->wxm_user->enable_user($user_email);
            if ($ret) {
                echo 'success';
                return true;
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function disable_user()
    {
        $user_email = $this->input->post('user_email');
        // $user_email = 'dw_wang126@126.com';  // test

        $auth = $this->wx_util->check_admin_permission('super', 'admin');
        if (! $auth) {
            echo 'no-permission';
            return false;
        }

        if ($user_email) {
            $ret = $this->wxm_user->disable_user($user_email);
            if ($ret) {
                echo 'success';
                return true;
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function user_index()
    {
        $user_count = $this->wxm_user->register_user_count();
        $data = array(
            'user_count' => $user_count
            );
        // echo $user_count;
        $this->load->view('f_user/wxv_user', $data);
    }
/*****************************************************************************/
    public function get_user_simple()
    {
        $user_email_or_name = $this->input->post('user_email_or_name');
        // $user_email_or_name = 'dw_wang126@126.com';  // test
        $user_email_or_name = trim($user_email_or_name);

        if ($user_email_or_name) {
            $base_info = $this->wxm_user->base_info($user_email_or_name);
            if ($base_info) {
                // wx_echoxml($base_info);
                echo json_encode($base_info);
            }
        }
    }
/*****************************************************************************/
    public function get_user_detail()
    {
        $user_email = $this->input->post('user_email');
        // $user_email = 'dw_wang126@126.com';  // test

        $base_info = $this->wxm_user->get_user_detail($user_email);
        $user_id = $base_info['user_id'];

        $area_info = $this->wxm_user2carea->get_user_detail($user_id);
        if ($area_info) {
            $area_id_major = $area_info['carea_id_major'];
            $area_id_shool = $area_info['carea_id_school'];
            $major = $this->wxm_category_area->get_name_by_id($area_id_major);
            $shool = $this->wxm_category_area->get_name_by_id($area_id_shool);
            if ($major && $shool) {
                $area_info['carea_name_major'] = $major['carea_name'];
                $area_info['carea_name_shool'] = $shool['carea_name'];
            }
        }

        $activity_info = $this->wxm_user_activity->get_user_detail($user_id);
        $data = array_merge($base_info, $area_info, $activity_info);
        // process the null field

        foreach ($data as $key => $value) {
            if (! $value && $value != 0) {
                $data[$key] = '';
            }
        }
        // wx_echoxml($data);
        echo json_encode($data);
    }
/*****************************************************************************/
    public function stat_register_count()  # statistics user count by time
    {
        $start_time = $this->input->post('start_time'); # time format: 2013-06-06
        $end_time = $this->input->post('end_time');
        // $start_time = '2013-04-1';
        // $end_time = '2013-06-01';
        $reg_exp = '[0-9]{4,4}-[0-9]{2,2}-[0-9]{2,2}';
        $ret_start = ereg($reg_exp, $start_time);
        $ret_end = ereg($reg_exp, $end_time);
        if (! $ret_start || ! $ret_end) {
            // wx_loginfo('exp wrong');
            return false;
        }

        # time format: 2013-06-06 00:00:00
        if ($start_time && $end_time) {
            $start_time = $start_time.' 00:00:00';
            $end_time = $end_time.' 00:00:00';
            $count = $this->wxm_user->stat_register_count($start_time, $end_time);
            echo $count;
        }
    }
/*****************************************************************************/
/*****************************************************************************/
/*****************************************************************************/
}

/* End of file user_manager.php */
/* Location: /application/backend/controllers/user_manager.php */
