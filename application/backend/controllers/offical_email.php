<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Offical_Email extends CI_Controller {
/*****************************************************************************/
    public function __construct() {
        parent::__construct();
        $this->load->model('wxm_site_manager');
        $this->load->model('wxm_user');
        $this->load->model('wxm_data');
        $this->load->model('wxm_data_activity');

        $this->load->library('wx_sendcloud_email');
    }
/*****************************************************************************/
/*****************************************************************************/
    public function send_week_recommend() {
        $data_id_list = $this->input->post('data_id_list');
        // $data_id_list = '41,42,43,47';                   // testing
        // wx_loginfo('week data_id_list ==> ['.$data_id_list.']');

        // 设置PHP脚本超时，不限时
        ini_set('max_execution_time', 0);

        $data_list = explode(',', $data_id_list);
        $data_list_info = $this->wxm_data->filter_note_by_id_list($data_list);
        if ($data_list_info) {
            $email_header = file_get_contents('application/backend/site_file/recommand_note_header.html');
            $email_content = '';
            // $email_footer = file_get_contents('application/backend/site_file/recommand_note_footer.html');
            $email_footer = '';  // new func, add digest user email reject iface

            foreach ($data_list_info as $data) {
                $data_id = $data['data_id'];
                $data_name = $data['data_name'];
                $data_type = $data['data_type'];
                $data_pagecount = $data['data_pagecount'];
                $data_price = $data['data_price'];
                $data_uploadtime = $data['data_uploadtime'];
                $data_keyword = $data['data_keyword'];

                $email_content .= '<tr><td colspan="2" align="left" bgcolor="#f3f2ce" style="color: #3399FF;font-size: 14px;"><a href="http://www.creamnote.com/data/wxc_data/data_view/'.$data_id.'" style="text-decoration: none;color:#3399FF" target="_blank">'.$data_name.'</a></td></tr><tr><td colspan="2" valign="top" bgcolor="#f3f2ce" style="font-size: 12px;"><div style="margin-top:-10px;margin-left:10px;">类型：'.$data_type.'&nbsp;&nbsp;&nbsp;&nbsp;页数：'.$data_pagecount.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;￥'.$data_price.'&nbsp;&nbsp;&nbsp;&nbsp;关键词：'.$data_keyword.'</div></td></tr>';
            }
            $content = $email_header.$email_content.$email_footer;

            // use new send official email interface
            $ret_send = $this->_send_official_email('每周精品笔记推荐', $content, false);
            if ($ret_send) {
                echo 'success';
                return true;
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function send_month_recommend() {
        $data_id_list = $this->input->post('data_id_list');
        // $data_id_list = '41,42,43,47';       // testing
        // wx_loginfo('month data_id_list ==> ['.$data_id_list.']');

        // 设置PHP脚本超时，不限时
        ini_set('max_execution_time', 0);

        $data_list = explode(',', $data_id_list);
        $data_list_info = $this->wxm_data->filter_note_by_id_list($data_list);
        if ($data_list_info) {
            $email_header = file_get_contents('application/backend/site_file/recommand_note_month_header.html');
            $email_content = '';
            // $email_footer = file_get_contents('application/backend/site_file/recommand_note_footer.html');
            $email_footer = '';  // new func, add digest user email

            foreach ($data_list_info as $data) {
                $data_id = $data['data_id'];
                $data_name = $data['data_name'];
                $data_type = $data['data_type'];
                $data_pagecount = $data['data_pagecount'];
                $data_price = $data['data_price'];
                $data_uploadtime = $data['data_uploadtime'];
                $data_keyword = $data['data_keyword'];

                $email_content .= '<tr><td colspan="2" align="left" bgcolor="#f3f2ce" style="color: #3399FF;font-size: 14px;"><a href="http://www.creamnote.com/data/wxc_data/data_view/'.$data_id;
                $email_content .= '" style="text-decoration: none;color:#3399FF" target="_blank">'.$data_name.'</a></td></tr><tr><td colspan="2" valign="top" bgcolor="#f3f2ce" style="font-size: 12px;"><div style="margin-top:-10px;margin-left:10px;">类型：'.$data_type.'&nbsp;&nbsp;&nbsp;&nbsp;页数：'.$data_pagecount.'&nbsp;&nbsp;&nbsp;&nbsp;￥'.$data_price.'&nbsp;&nbsp;&nbsp;&nbsp;关键词：'.$data_keyword.'</div></td></tr>';
            }
            $content = $email_header.$email_content.$email_footer;

            // use new send official email interface
            $ret_send = $this->_send_official_email('每月精品笔记推荐', $content, false);
            if ($ret_send) {
                echo 'success';
                return true;
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function send_welcome_email() {
        $email_content = $this->input->post('email_content');
        // $email_content = '欢迎加入 Creamnote-醍醐笔记网！';  // testing

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

                // testing
                // $user_name = 'Steven';
                // $user_email = 'dw_wang126@126.com';

                $greet = "<html><head></head>你好 <b>".$user_name."</b>：<p></p>";
                $content = $greet.$email_content.'</html>';
                $send_ret = $this->_send_ceo_email($user_email, '欢迎加入 Creamnote-醍醐笔记网', $content);

                // break;          // testing
            }
            echo 'success';
            return true;
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
/*****************************************************************************/
    public function test_send_welcome_email() {
        $email_content = $this->input->post('email_content');

        $test_email = 'dw_wang126@126.com';  // my 126 email for testing
        $user_name = 'Steven Wang';

        $greet = "<html><head></head>你好 <b>".$user_name."</b>：<p></p>";
        $content = $greet.$email_content.'</html>';

        $send_ret = $this->_send_ceo_email($test_email, '欢迎加入 Creamnote-醍醐笔记网', $content);
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
        // $data_id_list = '1,2,3';     // testing
        // wx_loginfo('data_id_list = ['.$data_id_list.']');

        $data_list = explode(',', $data_id_list);
        $data_list_info = $this->wxm_data->filter_note_by_id_list($data_list);
        if ($data_list_info) {
            $email_header = file_get_contents('application/backend/site_file/recommand_note_header.html');
            $email_content = '';
            // $email_footer = file_get_contents('application/backend/site_file/recommand_note_footer.html');
            $email_footer = '';  // testing

            foreach ($data_list_info as $data) {
                $data_id = $data['data_id'];
                $data_name = $data['data_name'];
                $data_type = $data['data_type'];
                $data_pagecount = $data['data_pagecount'];
                $data_price = $data['data_price'];
                $data_uploadtime = $data['data_uploadtime'];
                $data_keyword = $data['data_keyword'];

                $email_content .= '<tr><td colspan="2" align="left" bgcolor="#f3f2ce" style="color: #3399FF;font-size: 14px;"><a href="http://www.creamnote.com/data/wxc_data/data_view/'.$data_id.'" style="text-decoration: none;color:#3399FF" target="_blank">'.$data_name.'</a></td></tr><tr><td colspan="2" valign="top" bgcolor="#f3f2ce" style="font-size: 12px;"><div style="margin-top:-10px;margin-left:10px;">类型：'.$data_type.'&nbsp;&nbsp;&nbsp;&nbsp;页数：'.$data_pagecount.'&nbsp;&nbsp;&nbsp;&nbsp;￥'.$data_price.'&nbsp;&nbsp;&nbsp;&nbsp;关键词：'.$data_keyword.'</div></td></tr>';
            }
            $content = $email_header.$email_content.$email_footer;

            // use my test email to send
            $test_email = 'dw_wang126@126.com';  // my test email

            // 订阅邮件尾，退订接口 加入用户的 email，邮箱地址进行 url 加密
            $email_url_encrypt = urlencode($test_email);
            $email_footer_digest = '<tr><td height="30" colspan="2" align="center"  valign="middle" style="font-size:14px;" >如果您不想订阅醍醐精品推荐，点击这里<a href="http://www.creamnote.com/primary/wxc_personal/update_userinfo_page_digest/';
            $email_footer_digest .= $email_url_encrypt.'" target="_blank">退订</a></td><tr></table></td></tr></table>';

            $content = $content.$email_footer_digest;

            $send_ret = $this->_send_digest_email($test_email, '每周精品笔记推荐', $content);
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
        // $data_id_list = '1,2,3';     // testing

        $data_list = explode(',', $data_id_list);
        $data_list_info = $this->wxm_data->filter_note_by_id_list($data_list);
        if ($data_list_info) {
            $email_header = file_get_contents('application/backend/site_file/recommand_note_month_header.html');
            $email_content = '';
            // $email_footer = file_get_contents('application/backend/site_file/recommand_note_footer.html');
            $email_footer = '';

            foreach ($data_list_info as $data) {
                $data_id = $data['data_id'];
                $data_name = $data['data_name'];
                $data_type = $data['data_type'];
                $data_pagecount = $data['data_pagecount'];
                $data_price = $data['data_price'];
                $data_uploadtime = $data['data_uploadtime'];
                $data_keyword = $data['data_keyword'];

                $email_content .= '<tr><td colspan="2" align="left" bgcolor="#f3f2ce" style="color: #3399FF;font-size: 14px;"><a href="http://www.creamnote.com/data/wxc_data/data_view/'.$data_id.'" style="text-decoration: none;color:#3399FF" target="_blank">'.$data_name.'</a></td></tr><tr><td colspan="2" valign="top" bgcolor="#f3f2ce" style="font-size: 12px;"><div style="margin-top:-10px;margin-left:10px;">类型：'.$data_type.'&nbsp;&nbsp;&nbsp;&nbsp;页数：'.$data_pagecount.'&nbsp;&nbsp;&nbsp;&nbsp;￥'.$data_price.'&nbsp;&nbsp;&nbsp;&nbsp;关键词：'.$data_keyword.'</div></td></tr>';
            }
            $content = $email_header.$email_content.$email_footer;

            // use my email to send
            $test_email = 'dw_wang126@126.com';  // test email

            // 订阅邮件尾，退订接口 加入用户的 email，邮箱地址进行 url 加密
            $email_url_encrypt = urlencode($test_email);
            $email_footer_digest = '<tr><td height="30" colspan="2" align="center"  valign="middle" style="font-size:14px;" >如果您不想订阅醍醐精品推荐，点击这里<a href="http://www.creamnote.com/primary/wxc_personal/update_userinfo_page_digest/';
            $email_footer_digest .= $email_url_encrypt.'" target="_blank">退订</a></td><tr></table></td></tr></table>';

            $content = $content.$email_footer_digest;

            $send_ret = $this->_send_digest_email($test_email, '每月精品笔记推荐', $content);
            if ($send_ret) {
                echo 'success';
                return true;
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
/*****************************************************************************/
    public function _send_official_email($email_subject = '',
                                         $email_content = '',
                                         $has_greet = false) {
        if ($email_subject && $email_content) {
            // get user info by group of 20 user per group
            $group_user_count = 20;
            $group_offset = 0;
            $all_user_count = $this->wxm_user->get_all_user_count();
            $group_count = floor($all_user_count / $group_user_count);
            $mod_val = $all_user_count % $group_user_count;
            if ($mod_val > 0) {
                $group_count = $group_count + 1;
            }

            // statistic data
            $success_send_count = 0;

            for ($i = 0; $i < $group_count; $i++) {
                $user_group = $this->wxm_user->get_user_name_email_by_group($group_user_count, $group_offset);
                if ($user_group) {
                    foreach ($user_group as $key => $value) {
                        // $user_id = $value['user_id'];
                        $user_name = $value['user_name'];
                        $user_email = $value['user_email'];
                        $user_is_digest = $value['user_is_digest'];     // '0'-> 退订，'1'-> 接受订阅；
                        // echo 'user id    = '.$user_id.'<br />';
                        // echo 'user name  = '.$user_name.'<br />';
                        // echo 'user email = '.$user_email.'<br />';

                        // testing ...
                        // $user_name = 'Steven';
                        // $user_email = 'dw_wang126@126.com';

                        if ($user_name && $user_email && $email_content && $user_is_digest) {
                            $content = '';
                            if ($has_greet) {  // ceo email
                                $content = "<html><head></head>你好 <b>".$user_name."</b>：<p></p>".$email_content.'</html>';
                            }
                            else {              // digest email
                                $content = $email_content;

                                // 订阅邮件尾，退订接口 加入用户的 email，邮箱地址进行 url 加密
                                $email_url_encrypt = urlencode($user_email);
                                $email_footer_digest = '<tr><td height="30" colspan="2" align="center"  valign="middle" style="font-size:14px;" >如果您不想订阅醍醐精品推荐，点击这里<a href="http://www.creamnote.com/primary/wxc_personal/update_userinfo_page_digest/';
                                $email_footer_digest .= $email_url_encrypt.'" target="_blank">退订</a></td><tr></table></td></tr></table>';

                                $content = $content.$email_footer_digest;
                            }

                            // send offical email via sendcloud digest interface
                            $ret = $this->_send_digest_email($user_email, $email_subject, $content);
                            if (! $ret) {       // 如果发现发送失败，记录日志，继续发送
                                wx_loginfo('send offical email exception, user email: '.$user_email);
                            }
                            else {
                                $success_send_count++;
                            }
                        }
                        // break;  // testing
                    }
                }
                $group_offset = $group_offset + $group_user_count;
                // break;  // testing
            }

            // record offical email send statistic
            wx_loginfo('Offical Email Statistic: Total = '.$all_user_count.'  Success = '.$success_send_count);
            return true;
        }
        return false;
    }
/*****************************************************************************/
/*****************************************************************************/
    public function _send_digest_email($user_email = '', $subject = '', $content = '') {
        if ($user_email && $subject && $content) {
            $this->wx_sendcloud_email->clear();
            $this->wx_sendcloud_email->set_from_user('weekly-digest@digest.creamnote.com', 'Creamnote-醍醐笔记网');
            $this->wx_sendcloud_email->set_to_user($user_email);
            $this->wx_sendcloud_email->set_subject($subject);
            $this->wx_sendcloud_email->set_message($content);
            $result = $this->wx_sendcloud_email->send_digest_email();
            if ($result) {
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function _send_letters_email($user_email = '', $subject = '', $content = '') {
        if ($user_email && $subject && $content) {
            $this->wx_sendcloud_email->clear();
            $this->wx_sendcloud_email->set_from_user('no-reply@letters.creamnote.com', 'Creamnote-醍醐笔记网');
            $this->wx_sendcloud_email->set_to_user($user_email);
            $this->wx_sendcloud_email->set_subject($subject);
            $this->wx_sendcloud_email->set_message($content);
            $result = $this->wx_sendcloud_email->send_letters_email();
            if ($result) {
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function _send_ceo_email($user_email = '', $subject = '', $content = '') {
        if ($user_email && $subject && $content) {
            $this->wx_sendcloud_email->clear();
            $this->wx_sendcloud_email->set_from_user('co-founder@letters.creamnote.com', 'Creamnote-醍醐笔记网');
            $this->wx_sendcloud_email->set_to_user($user_email);
            $this->wx_sendcloud_email->set_subject($subject);
            $this->wx_sendcloud_email->set_message($content);
            $result = $this->wx_sendcloud_email->send_letters_email();
            if ($result) {
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
/*****************************************************************************/
    public function testing() {

        // echo 'testing offical email new iface ...';

        // test send send_welcome_email
        // $ret_wel = $this->send_welcome_email();
        // if ($ret_wel) {
        //     echo '欢迎 成功！<br />';
        // }


        // // test send week recommand
        // $ret_week = $this->send_week_recommend();
        // if ($ret_week) {
        //     echo '每周 成功！<br />';
        // }
        // sleep(3);
        // $ret_month = $this->send_month_recommend();
        // if ($ret_month) {
        //     echo '每月 成功！<br />';
        // }

        // // test ceo
        // $ret_ceo = $this->_send_ceo_email('dw_wang126@126.com', 'Creamnote-创始人', 'Creamnote-创始人邮件！');
        // if ($ret_ceo) {
        //     echo 'CEO 成功<br />';
        // }
        // // test letters
        // $ret_letters = $this->_send_letters_email('dw_wang126@126.com', 'Creamnote 系统邮件', 'Creamnote 系统邮件测试！');
        // if ($ret_letters) {
        //     echo '系统邮件 成功<br />';
        // }
        // // test digest
        // $ret_digest = $this->_send_digest_email('creamnote@163.com', 'Creamnote 订阅邮件', 'Creamnote 订阅邮件！');
        // if ($ret_digest) {
        //     echo '订阅邮件 成功<br />';
        // }
    }
/*****************************************************************************/
}

/* End of file offical_email.php */
/* Location: /application/backend/controllers/offical_email.php */
