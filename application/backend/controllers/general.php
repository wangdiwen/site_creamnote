<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General extends CI_Controller {
/*****************************************************************************/
    public function __construct() {
        parent::__construct();
        $this->load->model('wxm_site_manager');
        $this->load->model('wxm_user');
        $this->load->model('wxm_data');
        $this->load->model('wxm_data_activity');

        $this->load->library('pagination');
        // $this->load->library('wx_util');
        $this->load->library('wx_email');
    }
/*****************************************************************************/
    public function general_index($offset = 0) {
        $site_count = $this->wxm_site_manager->site_count();
        $config = array(
            'base_url' => base_url().'cnadmin/general/general_index',
            'total_rows' => $site_count,
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
            'cur_tag_close' => '</a></span>',
            );
        $this->pagination->initialize($config);
        $site_page = $this->wxm_site_manager->get_page_info($config['per_page'], $offset);
        $cur_month_data = $this->get_cur_month_info();
        $data = array(
            'site_info' => $site_page,
            'site_month_info' => $cur_month_data,
            'site_offset' => $offset,
            );
        $this->load->view('f_integrate/wxv_target', $data);
    }
/*****************************************************************************/
    public function get_cur_month_info() {
        $cur_month = wx_month();
        $has_cur_info = $this->wxm_site_manager->has_by_date($cur_month);
        if (! $has_cur_info) {  // create new record
            $last_month = wx_last_month();
            $has_last_info = $this->wxm_site_manager->has_by_date($last_month);
            if (! $has_last_info) {  // init cur month record
                $cur_month_data = array(
                    'site_date' => $cur_month,
                    'site_users' => 0,
                    'site_note_count' => 0,
                    'site_upload_count' => 0,
                    'site_imagenote_count' => 0,
                    'site_freedown_count' => 0,
                    'site_paydown_count' => 0,
                    'site_download_count' => 0,
                    'site_total_income' => 0,
                );
                $this->wxm_site_manager->insert_new($cur_month_data);
            }
            else {  // copy last month data, init cur month data
                $last_month_data = $this->wxm_site_manager->get_by_date($last_month);
                if ($last_month_data) {
                    $last_month_data['site_date'] = $cur_month;
                    $this->wxm_site_manager->insert_new($last_month_data);
                }
            }
        }
        // get cur month info
        $cur_month_data = $this->wxm_site_manager->get_by_date($cur_month);
        // wx_echoxml($cur_month_data);
        return $cur_month_data;
    }
/*****************************************************************************/
    public function query_any_month() {
        $start_time = $this->input->post('start_month');
        $end_time = $this->input->post('end_month');

        // $start_time = '2013-08';
        // $end_time = '2013-11';
        $ret_exp_start = ereg('^[0-9]{4,4}-[0-9]{2,2}$', $start_time);
        $ret_exp_end = ereg('^[0-9]{4,4}-[0-9]{2,2}$', $end_time);
        if (! $ret_exp_start || ! $ret_exp_end) {
            echo json_encode(array());
            return false;
        }

        $result = $this->wxm_site_manager->query_any_month($start_time, $end_time);
        // wx_echoxml($result);
        echo json_encode($result);
        return true;
    }
/*****************************************************************************/
/*****************************************************************************/
    public function _send_system_email_to_user($user_email = '', $content) {
        // $user_email = 'dw_wang126@126.com';
        if ($user_email && $content) {
            $this->wx_email->clear();

            $this->wx_email->set_from_user('co-founder@creamnote.com', '醍醐笔记官方');
            $this->wx_email->set_to_user($user_email);
            $this->wx_email->set_subject('欢迎加入醍醐笔记网');
            $this->wx_email->set_message($content);

            $ret = $this->wx_email->send_email();
            if ($ret)
                return true;
        }
        return false;
    }
/*****************************************************************************/
    public function _send_recommand_email_to_user($user_email = '', $content) {
        // $user_email = 'dw_wang126@126.com';
        if ($user_email && $content) {
            $this->wx_email->clear();

            $this->wx_email->set_from_user('no-reply@creamnote.com', '醍醐笔记官方');
            $this->wx_email->set_to_user($user_email);
            $this->wx_email->set_subject('精品笔记推荐');
            $this->wx_email->set_message($content);

            $ret = $this->wx_email->send_email();
            if ($ret)
                return true;
        }
        return false;
    }
/*****************************************************************************/
    public function sys_email_index() {
        $cur_week = wx_cur_week();
        $data = array();
        if ($cur_week == 1) {
            $data['current_week_tip'] = "<p><b>当前是星期 一，</b></p><p>需要给上周新注册用户<b>发送欢迎邮件</b></p>";
            // wx_echoxml($data);
        }
        $this->load->view('f_integrate/wxv_email', $data);
    }
/*****************************************************************************/
    public function send_welcome_email() {
        $email_content = $this->input->post('email_content');

        // 设置PHP脚本超时，不限时
        ini_set('max_execution_time', 0);

        $email_content = preg_replace(array('<html>','</html>'), array('',''), $email_content);

        $last_week_today = wx_get_any_before_today('7');
        $today = wx_get_today_time();
        $last_week_new_user = $this->wxm_user->get_any_time_users($last_week_today, $today);
        // wx_echoxml($last_week_new_user);
        if ($last_week_new_user) {
            foreach ($last_week_new_user as $new_user) {
                $user_id = $new_user['user_id'];
                $user_name = $new_user['user_name'];
                $user_email = $new_user['user_email'];
                // $user_register_time = $new_user['user_register_time'];

                $greet = "<html><head></head>你好 <b>".$user_name."</b>：<p></p>";
                $content = $greet.$email_content.'</html>';
                $send_ret = $this->_send_system_email_to_user($user_email, $content);

                // set delay, 2 seconds
                sleep(2);
            }
            echo 'success';
            return true;
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function init_email_content() {
        $welcome_email_file = 'application/backend/site_file/welcome_email.html';
        $content = file_get_contents($welcome_email_file);
        echo $content;
    }
/*****************************************************************************/
    public function save_welcome_content() {
        $email_content = $this->input->post('email_content');
        if ($email_content) {
            $welcome_email_file = 'application/backend/site_file/welcome_email.html';
            $save_ret = file_put_contents($welcome_email_file, $email_content);
            if ($save_ret) {
                echo 'success';
                return true;
            }
            echo 'failed';
            return false;
        }
    }
/*****************************************************************************/
    public function last_week_good_note() {
        $last_week_today = wx_get_any_before_today('7');
        $today = wx_get_today_time();

        $last_week_good_note = $this->wxm_data->query_good_data_any_time($last_week_today, $today);
        // wx_echoxml($last_week_good_note);
        echo json_encode($last_week_good_note);
    }
/*****************************************************************************/
    public function last_month_good_note() {
        $last_month = wx_last_month();
        $today = wx_get_today_time();
        $start_time = $last_month.'-01 00:00:00';

        $last_month_good_note = $this->wxm_data->query_good_data_any_time($start_time, $today);
        // wx_echoxml($last_month_good_note);
        echo json_encode($last_month_good_note);
    }
/*****************************************************************************/
    public function send_week_recommend() {
        $data_id_list = $this->input->post('data_id_list');
        // $data_id_list = '41,42,43,47';
        $data_list = explode(',', $data_id_list);
        // wx_echoxml($data_list);

        $data_list_info = $this->wxm_data->filter_note_by_id_list($data_list);
        // wx_echoxml($data_list_info);
        if ($data_list_info) {
            $email_header = file_get_contents('application/backend/site_file/recommand_note_header.html');
            $email_content = '';
            $email_footer = file_get_contents('application/backend/site_file/recommand_note_footer.html');
            foreach ($data_list_info as $data) {
                $data_id = $data['data_id'];
                $data_name = $data['data_name'];
                $data_type = $data['data_type'];
                $data_pagecount = $data['data_pagecount'];
                $data_price = $data['data_price'];
                $data_uploadtime = $data['data_uploadtime'];
                $data_keyword = $data['data_keyword'];

                $email_content .= '<tr><td colspan="2" align="left" bgcolor="#f3f2ce" style="color: #3399FF;font-size: 14px;"><a href="http://www.creamnote.com/data/wxc_data/data_view/'.$data_id.'" style="text-decoration: none;color:#3399FF" target="_blank">'.$data_name.'</a></td></tr><tr><td colspan="2" valign="top" bgcolor="#f3f2ce" style="font-size: 12px;"><div style="margin-top:-10px;margin-left:10px;">类型:'.$data_type.'&nbsp;&nbsp;&nbsp;&nbsp;页数:'.$data_pagecount.'&nbsp;&nbsp;&nbsp;&nbsp;价格￥:'.$data_price.'&nbsp;&nbsp;&nbsp;&nbsp;关键词:'.$data_keyword.'</div></td></tr>';
            }
            $content = $email_header.$email_content.$email_footer;

            // use new send official email interface
            $ret_send = $this->_send_official_email($content, '每周精品笔记推荐', false);
            if ($ret_send) {
                echo 'success';
                return true;
            }

            // init system email settings
            // $config['protocol'] = 'smtp';
            // $config['smtp_host'] = 'smtp.ym.163.com';
            // $config['smtp_port'] = 25;
            // $config['smtp_user'] = 'no-reply@creamnote.com';
            // $config['smtp_pass'] = 'wx@creamnote';
            // $config['mailtype'] = 'html';
            // $config['validate'] = true;
            // $config['crlf'] = '\r\n';
            // $config['charset'] = 'utf-8';
            // $this->email->initialize($config);

            // // get all register user email
            // $all_user_email_info = $this->wxm_user->get_all_user_email();
            // if ($content && $all_user_email_info) {
            //     foreach ($all_user_email_info as $user_info) {
            //         $user_email = $user_info['user_email'];
            //         $send_ret = $this->_send_recommand_email_to_user($user_email, $content);
            //     }
            //     echo 'success';
            //     return true;
            // }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function send_month_recommend() {
        $data_id_list = $this->input->post('data_id_list');

        // $data_id_list = '41,42,43,47';
        $data_list = explode(',', $data_id_list);
        // wx_echoxml($data_list);

        $data_list_info = $this->wxm_data->filter_note_by_id_list($data_list);
        // wx_echoxml($data_list_info);
        if ($data_list_info) {
            $email_header = file_get_contents('application/backend/site_file/recommand_note_month_header.html');
            $email_content = '';
            $email_footer = file_get_contents('application/backend/site_file/recommand_note_footer.html');
            foreach ($data_list_info as $data) {
                $data_id = $data['data_id'];
                $data_name = $data['data_name'];
                $data_type = $data['data_type'];
                $data_pagecount = $data['data_pagecount'];
                $data_price = $data['data_price'];
                $data_uploadtime = $data['data_uploadtime'];
                $data_keyword = $data['data_keyword'];

                $email_content .= '<tr><td colspan="2" align="left" bgcolor="#f3f2ce" style="color: #3399FF;font-size: 14px;"><a href="http://www.creamnote.com/data/wxc_data/data_view/'.$data_id;
                $email_content .= '" style="text-decoration: none;color:#3399FF" target="_blank">'.$data_name.'</a></td></tr><tr><td colspan="2" valign="top" bgcolor="#f3f2ce" style="font-size: 12px;"><div style="margin-top:-10px;margin-left:10px;">类型:'.$data_type.'&nbsp;&nbsp;&nbsp;&nbsp;页数:'.$data_pagecount.'&nbsp;&nbsp;&nbsp;&nbsp;价格:￥'.$data_price.'&nbsp;&nbsp;&nbsp;&nbsp;关键词:'.$data_keyword.'</div></td></tr>';
            }
            $content = $email_header.$email_content.$email_footer;

            // use new send official email interface
            $ret_send = $this->_send_official_email($content, '每月精品笔记推荐', false);
            if ($ret_send) {
                echo 'success';
                return true;
            }

            // init system email settings
            // $config['protocol'] = 'smtp';
            // $config['smtp_host'] = 'smtp.ym.163.com';
            // $config['smtp_port'] = 25;
            // $config['smtp_user'] = 'no-reply@creamnote.com';
            // $config['smtp_pass'] = 'wx@creamnote';
            // $config['mailtype'] = 'html';
            // $config['validate'] = true;
            // $config['crlf'] = '\r\n';
            // $config['charset'] = 'utf-8';
            // $this->email->initialize($config);

            // // get all register user email
            // $all_user_email_info = $this->wxm_user->get_all_user_email();
            // if ($content && $all_user_email_info) {
            //     foreach ($all_user_email_info as $user_info) {
            //         $user_email = $user_info['user_email'];
            //         $send_ret = $this->_send_recommand_email_to_user($user_email, $content);
            //     }
            //     echo 'success';
            //     return true;
            // }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function test_send_welcome_email() {
        $email_content = $this->input->post('email_content');

        $test_email = 'dw_wang126@126.com';
        // $test_email = 'xiewang0501@126.com';

        $user_name = 'Steven Wang';
        $greet = "<html><head></head>你好 <b>".$user_name."</b>：<p></p>";
        $content = $greet.$email_content.'</html>';

        $send_ret = $this->_send_system_email_to_user($test_email, $content);
        if ($send_ret) {
            echo 'success';
            return true;
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function test_send_week_recommend() {
        $data_id_list = $this->input->post('data_id_list');
        // $data_id_list = '1,2,3';
        $data_list = explode(',', $data_id_list);
        // wx_echoxml($data_list);

        $data_list_info = $this->wxm_data->filter_note_by_id_list($data_list);
        // wx_echoxml($data_list_info);
        if ($data_list_info) {
            $email_header = file_get_contents('application/backend/site_file/recommand_note_header.html');
            $email_content = '';
            $email_footer = file_get_contents('application/backend/site_file/recommand_note_footer.html');
            foreach ($data_list_info as $data) {
                $data_id = $data['data_id'];
                $data_name = $data['data_name'];
                $data_type = $data['data_type'];
                $data_pagecount = $data['data_pagecount'];
                $data_price = $data['data_price'];
                $data_uploadtime = $data['data_uploadtime'];
                $data_keyword = $data['data_keyword'];

                $email_content .= '<tr><td colspan="2" align="left" bgcolor="#f3f2ce" style="color: #3399FF;font-size: 14px;"><a href="http://www.creamnote.com/data/wxc_data/data_view/'.$data_id.'" style="text-decoration: none;color:#3399FF" target="_blank">'.$data_name.'</a></td></tr><tr><td colspan="2" valign="top" bgcolor="#f3f2ce" style="font-size: 12px;"><div style="margin-top:-10px;margin-left:10px;">类型:'.$data_type.'&nbsp;&nbsp;&nbsp;&nbsp;页数:'.$data_pagecount.'&nbsp;&nbsp;&nbsp;&nbsp;价格￥:'.$data_price.'&nbsp;&nbsp;&nbsp;&nbsp;关键词:'.$data_keyword.'</div></td></tr>';
            }
            $content = $email_header.$email_content.$email_footer;
            // wx_loginfo('content ==> '.$content);

            // init system email settings
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'smtp.ym.163.com';
            $config['smtp_port'] = 25;
            $config['smtp_user'] = 'no-reply@creamnote.com';
            $config['smtp_pass'] = 'wx@creamnote';
            $config['mailtype'] = 'html';
            $config['validate'] = true;
            $config['crlf'] = '\r\n';
            $config['charset'] = 'utf-8';
            $this->email->initialize($config);

            // get all register user email
            $test_email = 'dw_wang126@126.com';  // test email
            // $test_email = 'xiewang0501@126.com';
            $send_ret = $this->_send_recommand_email_to_user($test_email, $content);
            if ($send_ret) {
                echo 'success';
                return true;
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function test_send_month_recommend() {
        $data_id_list = $this->input->post('data_id_list');

        // $data_id_list = '1,2,3';
        $data_list = explode(',', $data_id_list);
        // wx_echoxml($data_list);

        $data_list_info = $this->wxm_data->filter_note_by_id_list($data_list);
        // wx_echoxml($data_list_info);
        if ($data_list_info) {
            $email_header = file_get_contents('application/backend/site_file/recommand_note_month_header.html');
            $email_content = '';
            $email_footer = file_get_contents('application/backend/site_file/recommand_note_footer.html');
            foreach ($data_list_info as $data) {
                $data_id = $data['data_id'];
                $data_name = $data['data_name'];
                $data_type = $data['data_type'];
                $data_pagecount = $data['data_pagecount'];
                $data_price = $data['data_price'];
                $data_uploadtime = $data['data_uploadtime'];
                $data_keyword = $data['data_keyword'];

                $email_content .= '<tr><td colspan="2" align="left" bgcolor="#f3f2ce" style="color: #3399FF;font-size: 14px;"><a href="http://www.creamnote.com/data/wxc_data/data_view/'.$data_id.'" style="text-decoration: none;color:#3399FF" target="_blank">'.$data_name.'</a></td></tr><tr><td colspan="2" valign="top" bgcolor="#f3f2ce" style="font-size: 12px;"><div style="margin-top:-10px;margin-left:10px;">类型:'.$data_type.'&nbsp;&nbsp;&nbsp;&nbsp;页数:'.$data_pagecount.'&nbsp;&nbsp;&nbsp;&nbsp;价格:￥'.$data_price.'&nbsp;&nbsp;&nbsp;&nbsp;关键词:'.$data_keyword.'</div></td></tr>';
            }
            $content = $email_header.$email_content.$email_footer;

            // init system email settings
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'smtp.ym.163.com';
            $config['smtp_port'] = 25;
            $config['smtp_user'] = 'no-reply@creamnote.com';
            $config['smtp_pass'] = 'wx@creamnote';
            $config['mailtype'] = 'html';
            $config['validate'] = true;
            $config['crlf'] = '\r\n';
            $config['charset'] = 'utf-8';
            $this->email->initialize($config);

            // get all register user email
            $test_email = 'dw_wang126@126.com';  // test email
            // $test_email = 'xiewang0501@126.com';
            $send_ret = $this->_send_recommand_email_to_user($test_email, $content);
            if ($send_ret) {
                echo 'success';
                return true;
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function _send_official_email($email_content = '', $email_subject = '醍醐笔记官方邮件', $has_greet = false) {
        // 设置PHP脚本超时，不限时
        ini_set('max_execution_time', 0);

        if ($email_content && $email_subject) {
            // echo 'E-mail email_content: '.$email_content.'<br />';
            // get user info by group of 10 user per group
            $group_user_count = 20;
            $group_offset = 0;
            $all_user_count = $this->wxm_user->get_all_user_count();
            $group_count = floor($all_user_count / $group_user_count);
            $mod_val = $all_user_count % $group_user_count;
            if ($mod_val > 0) {
                $group_count = $group_count + 1;
            }

            // echo 'group count: '.$group_count.'<br />';
            // echo 'mod value  : '.$mod_val.'<br />';

            // init system email settings
            $config = array();
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'smtp.ym.163.com';
            $config['smtp_port'] = 25;
            $config['smtp_user'] = 'no-reply@creamnote.com';
            $config['smtp_pass'] = 'wx@creamnote';
            $config['mailtype'] = 'html';
            $config['validate'] = true;
            $config['crlf'] = '\r\n';
            $config['charset'] = 'utf-8';
            $this->email->initialize($config);

            for ($i = 0; $i < $group_count; $i++) {
                $user_group = $this->wxm_user->get_user_name_email_by_group($group_user_count, $group_offset);

                // send official email
                // echo '=================================<br />';
                // echo 'group  '.$i.'<br />';
                // echo 'offset '.$group_offset.'<br />';
                if ($user_group) {
                    foreach ($user_group as $key => $value) {
                        // $user_id = $value['user_id'];
                        $user_name = $value['user_name'];
                        $user_email = $value['user_email'];
                        // echo 'user id    = '.$user_id.'<br />';
                        // echo 'user name  = '.$user_name.'<br />';
                        // echo 'user email = '.$user_email.'<br />';

                        // test ...
                        // $user_name = 'Steven';
                        // $user_email = 'dw_wang126@126.com';

                        if ($user_name && $user_email && $email_content) {
                            $content = '';
                            if ($has_greet) {
                                $content = "<html><head></head>你好 <b>".$user_name."</b>：<p></p>".$email_content.'</html>';
                            }
                            else {
                                $content = $email_content;
                            }

                            $this->wx_email->clear();
                            $this->wx_email->set_from_user('no-reply@creamnote.com', 'Creamnote 醍醐笔记网');
                            $this->wx_email->set_to_user($user_email);
                            $this->wx_email->set_subject($email_subject);
                            $this->wx_email->set_message($content);

                            $ret = $this->wx_email->send_email();
                            // break;  // test ...
                            if (! $ret) {  // 如果发现发送失败，则中断发送
                                $debug = $this->wx_email->debugger();
								wx_loginfo('send offical email exception, user email: '.$user_email);
                                wx_loginfo(trim($debug));
                                // return false;
                            }
                        }

                        // add send email delay, 2 seconds
                        sleep(2);
                    }
                }
                $group_offset = $group_offset + $group_user_count;
                // echo '=================================<br /><br />';
            }
        }
        return true;
    }
/*****************************************************************************/
    public function test() {

        // $this->_send_official_email('Test sending official email ...', '醍醐笔记主题', true);
        // $this->_send_official_email('Test sending official email ...', '醍醐笔记主题', false);

        // $user_email = 'dw_wang126@126.com';
        // $content = '这是测试邮件，测试CEO邮箱是否可用';
        // $ret = $this->_send_system_email_to_user($user_email, $content);
        // if ($ret)
        //     echo 'CEO邮箱测试成功！';
        // $week = wx_cur_week();
        // echo '星期 '.$week;
        // if ($week == 3) {
        //     echo 'week = 3';
        // }
    }
/*****************************************************************************/
}

/* End of file general.php */
/* Location: /application/backend/controllers/general.php */
