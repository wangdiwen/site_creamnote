<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends CI_Controller
{
/*****************************************************************************/
    public function __construct() {
        parent::__construct();
        $this->load->model('wxm_report');
        $this->load->model('wxm_user');
        $this->load->model('wxm_data');

        $this->load->library('wx_util');
        $this->load->library('pagination');
        $this->load->library('wx_aliossapi');
        $this->load->library('wx_email');
        $this->load->helper('download');
    }
/*****************************************************************************/
    public function report_step_one_index($offset = 0) {
        $step_one_count = $this->wxm_report->get_step_one_count();
        $config = array(
            'base_url' => base_url().'cnadmin/report/report_step_one_index',
            'total_rows' => $step_one_count,
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
        $report_page = $this->wxm_report->get_step_one_page($config['per_page'], $offset);
        $has_tmp_file = 'false';
        $has_download_tmp_file = $this->has_download_tmp_file();
        if ($has_download_tmp_file) {
            $has_tmp_file = 'true';
        }
        $data = array(
            'report_step_one' => $report_page,
            'has_download_tmp_file' => $has_tmp_file,
            'offset' => $offset,
            );
        // wx_echoxml($data);
        $this->load->view('f_feedback/wxv_complaint', $data);
    }
/*****************************************************************************/
    public function report_step_second_index($offset = 0) {
        $step_second_count = $this->wxm_report->get_step_second_count();
        $config = array(
            'base_url' => base_url().'cnadmin/report/report_step_second_index',
            'total_rows' => $step_second_count,
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
        $report_page = $this->wxm_report->get_step_second_page($config['per_page'], $offset);
        $data = array(
            'report_step_second' => $report_page,
            'offset' => $offset,
            );
        // wx_echoxml($data);
        $this->load->view('f_feedback/wxv_complaint_sec', $data);
    }
/*****************************************************************************/
    public function report_step_third_index($offset = 0) {
        $step_third_count = $this->wxm_report->get_step_third_count();
        $config = array(
            'base_url' => base_url().'cnadmin/report/report_step_third_index',
            'total_rows' => $step_third_count,
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
        $report_page = $this->wxm_report->get_step_third_page($config['per_page'], $offset);
        $data = array(
            'report_step_third' => $report_page,
            'offset' => $offset,
            );
        // wx_echoxml($data);
        $this->load->view('f_feedback/wxv_complaint_thi', $data);
    }
/*****************************************************************************/
    public function get_report_statistic() {
        $data = array(
            'disposed' => 0,
            'undisposed' => 0,
            'total' => 0,
            );
        $disposed_count = $this->wxm_report->get_disposed_count();
        $undisposed_count = $this->wxm_report->get_undisposed_count();
        $total_count = $this->wxm_report->get_total_count();

        $data['disposed'] = $disposed_count;
        $data['undisposed'] = $undisposed_count;
        $data['total'] = $total_count;

        // wx_echoxml($data);
        echo json_encode($data);
    }
/*****************************************************************************/
/*****************************************************************************/
    public function handle_step_one() {
        // 1. look report; 2. out result; 3. admin sign, like '地文'
        $report_id = $this->input->post('report_id');
        $report_result = $this->input->post('report_result');
        $admin_sign = $this->input->post('admin_sign');

        if ($report_id > 0 && $report_result && $admin_sign) {
            $ret = $this->wxm_report->handle_step_one($report_id, $report_result, $admin_sign);
            if ($ret) {
                echo 'success';
                return true;
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function handle_step_second() {
        // 1. check report status : true? false?
        // 2. if false: send email to reportor
        // 3. if true: look note detail, and send system email
        // to note owner, and note to del note himselt

    }
/*****************************************************************************/
    public function handle_step_third() {
        // 1. if report result is not to del note, send thank email to reportor
        // 2. if report result is to del note, admin to del note, by note id
        // 3. send email to note owner, note: your xxx note has been del
        // 4. admin sign

    }
/*****************************************************************************/
/*****************************************************************************/
    public function query_note_detail() {
        $com_link = $this->input->post('com_link');
        // $com_link = 'http://www.creamnote.com/data/wxc_data/data_view/47';
        preg_match("/^http:\/\/www.creamnote.com\/data\/wxc_data\/data_view\/([0-9]+)$/", $com_link, $match);
        // wx_echoxml($match);
        if ($match) {
            $data_id = $match[1];
            $data_detail = $this->wxm_data->query_data_detail($data_id);
            if ($data_detail) {
                // wx_echoxml($data_detail);
                echo json_encode($data_detail);
                return;
            }
        }
        echo json_encode('');
    }
/*****************************************************************************/
    public function download_note_data() {
        $com_link = $this->input->get('com_link');
        // $com_link = 'http://www.creamnote.com/data/wxc_data/data_view';
        preg_match("/^http:\/\/www.creamnote.com\/data\/wxc_data\/data_view\/([0-9]+)$/", $com_link, $match);
        // wx_echoxml($match);
        if (! $match) {
            return false;
        }
        $data_id = $match[1];
        if ($data_id && ! $data_id > 0) {
            return false;
        }

        $data_info = $this->wxm_data->get_download_info($data_id);
        if ($data_info) {
            // get data base info
            $data_id = $data_info['data_id'];
            $data_name = $data_info['data_name'];
            $data_objectname = $data_info['data_objectname'];
            $data_type = $data_info['data_type'];
            $data_price = $data_info['data_price'];
            $data_own_user_id = $data_info['user_id'];
            $data_osspath = $data_info['data_osspath'];
            $data_vpspath = $data_info['data_vpspath'];

            if ($data_osspath) {
                $bucket = $data_osspath;
                $object = $data_objectname;

                $save_path = '/alidata/www/creamnote/upload/download_tmp/';
                if (is_dir($save_path)) {
                    $save_file = $save_path.$object;
                    $oss_ret = $this->wx_aliossapi->get_object($bucket, $object, $save_file);
                    if ($oss_ret) {
                        $file_data = file_get_contents($save_file);
                        $file_name = $data_name.'.'.$data_type;
                        $this->output->set_header("Content-type: application/octet-stream");
                        $this->output->set_header("Accept-Ranges: bytes");
                        $this->output->set_header("Content-type: text/html; charset=utf-8");
                        if ($file_name && $file_data) {
                            force_download($file_name, $file_data);
                        }
                    }
                }
            }
        }
    }
/*****************************************************************************/
    public function get_all_download_tmp_file() {
        $dir = '/alidata/www/creamnote/upload/download_tmp/';
        $objects = scandir($dir);
        $file_list = array();
        foreach ($objects as $obj) {
            if (filetype($dir.$obj) == 'file') {
                $file_list[] = $obj;
            }
        }
        // wx_echoxml($file_list);
        return $file_list;
    }
/*****************************************************************************/
    public function has_download_tmp_file() {
        $dir = '/alidata/www/creamnote/upload/download_tmp/';
        $objects = scandir($dir);
        $flag = false;
        foreach ($objects as $obj) {
            if ($obj != '.' && $obj != '..' && filetype($dir.$obj) == 'file') {
                $flag = true;
                break;
            }
        }
        return $flag;
    }
/*****************************************************************************/
    public function one_key_clear_download_tmp_file() {
        $dir = '/alidata/www/creamnote/upload/download_tmp/';
        $objects = scandir($dir);
        $file_list = array();
        foreach ($objects as $obj) {
            if (filetype($dir.$obj) == 'file') {
                $file_list[] = $obj;
            }
        }
        if ($file_list) {
            foreach ($file_list as $file) {
                $clear_file = $dir.$file;
                $del_ret = wx_delete_file($clear_file);
            }
        }
        echo 'success';
        return true;
    }
/*****************************************************************************/
    public function pass_this_report() {
        $com_id = $this->input->post('com_id');

        if ($com_id > 0) {
            $admin_name = '';
            $admin_info = $this->wx_util->get_admin_info();
            if ($admin_info) {
                $admin_name = $admin_info['admin_user_name'];
            }
            $result = '本条投诉，验证没有问题，可以忽略';
            $ret = $this->wxm_report->report_pass($com_id, $admin_name,
                $result);
            if ($ret) {
                echo 'success';
                return true;
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function close_this_note() {
        $com_id = $this->input->post('com_id');
        $com_email = $this->input->post('com_user_email');
        $com_note_name = $this->input->post('com_note_name');
        $com_link = $this->input->post('com_link');
        $com_time = $this->input->post('com_time');

        // $com_id = 1;
        // $com_email = 'dw_wang126@126.com';
        // $com_note_name = '图片笔记，测试';
        // $com_link = 'http://www.creamnote.com/data/wxc_data/data_view/47';
        // $com_time = '2013-08-23 00:00:00';

        preg_match("/^http:\/\/www.creamnote.com\/data\/wxc_data\/data_view\/([0-9]+)$/", $com_link, $match);
        // wx_echoxml($match);
        if ($com_id > 0 && $match) {
            // get such report some info
            $data_id = $match[1];
            $owner_name = '';
            $owner_email = '';

            $data_user_info = $this->wxm_data->get_data_user_id($data_id);
            if ($data_user_info) {
                $user_id = $data_user_info['user_id'];
                $owner_info = $this->wxm_user->get_user_name_email($user_id);
                if ($owner_info) {
                    $owner_name = $owner_info['user_name'];
                    $owner_email = $owner_info['user_email'];
                }
            }

            // check email
            $ret_reportor = wx_check_email($com_email);
            $ret_owner = wx_check_email($owner_email);
            // send report email to reportor and note owner
            if ($ret_reportor && $ret_owner) {
                // init system email settings
                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'smtp.ym.163.com';
                $config['smtp_port'] = 25;
                $config['smtp_user'] = 'report@creamnote.com';
                $config['smtp_pass'] = 'wx@creamnote';
                $config['mailtype'] = 'html';
                $config['validate'] = true;
                $config['crlf'] = '\r\n';
                $config['charset'] = 'utf-8';
                $this->email->initialize($config);

                $reportor_content = '<p>尊敬的 '.$com_email.' 用户，您好：</p><p></p><p>您在 '.substr($com_time, 0, 10).' 举报&投诉的一份笔记名称为《'.$com_note_name.'》的资料，经过我们审查与核实，已经得到处理。</p>'.'<p></p><p>非常感谢您对Creamnote醍醐笔记网的支持！</p><p></p><p>Creamnote投诉&举报服务小组</p><p>report@creamnote.com</p>';

                $owner_content = '<p>尊敬的 '.$owner_email.' 用户，您好：</p><p></p><p>您的一份名称为《'.$com_note_name.'》的笔记资料，由于受到投诉&举报，经过审核，的确存在部分内容违规，我们暂时将此份资料数据驳回到【未通过审核】状态，如果您的笔记资料，没有违反《关于网站上传笔记资料内容的规范》，以及没有侵犯任何第三方的笔记资料版权，您可以给网站的 投诉&举报服务小组（report@creamnote.com）写信，说明情况，我们会做后续的处理工作。</p><p></p><p>非常感谢您对Creamnote醍醐笔记网的支持！</p><p></p><p>Creamnote投诉&举报服务小组</p><p>report@creamnote.com</p>';
                $send_reportor_ret = $this->_send_report_email_to_user($com_email, $reportor_content);
                $send_owner_ret = $this->_send_report_email_to_user($owner_email, $owner_content);
                if ($send_owner_ret) {
                    // send 2 email, success
                    // close this note data, 'data_status=2'
                    $close_ret = $this->wxm_data->check_not_pass($data_id);
                    // complete this report
                    $cur_admin_info = $this->wx_util->get_admin_info();
                    $admin_name = $cur_admin_info['admin_user_name'];
                    $com_result = '笔记内容违规，封闭此份笔记资料，状态为【未通过审核】';
                    $pass_ret = $this->wxm_report->complete_report($com_id, $admin_name, $com_result);
                    echo 'success';
                    return true;
                }
                else {  // if send mail failed, go to step 2
                    $cur_admin_info = $this->wx_util->get_admin_info();
                    $admin_name = $cur_admin_info['admin_user_name'];
                    $com_result = '被投诉者（笔记的拥有者）的邮件发送不成功';
                    $change_step_two = $this->wxm_report->change_other_step($com_id, $admin_name, $com_result);
                    echo 'email-failed';
                    return false;
                }
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function wait_to_step_two() {
        $com_id = $this->input->post('com_id');
        $com_email = $this->input->post('com_user_email');
        $com_note_name = $this->input->post('com_note_name');
        $com_link = $this->input->post('com_link');
        $com_time = $this->input->post('com_time');
        $com_reason = $this->input->post('com_reason');

        // $com_id = 1;
        // $com_email = 'dw_wang126@126.com';
        // $com_note_name = '图片笔记，测试';
        // $com_link = 'http://www.creamnote.com/data/wxc_data/data_view/47';
        // $com_time = '2013-08-23 00:00:00';
        // $com_reason = '这是管理员自定义的违规原因说明';

        preg_match("/^http:\/\/www.creamnote.com\/data\/wxc_data\/data_view\/([0-9]+)$/", $com_link, $match);
        if ($com_id > 0 && $match && $com_reason) {
            // get such report some info
            $data_id = $match[1];
            $owner_name = '';
            $owner_email = '';

            $data_user_info = $this->wxm_data->get_data_user_id($data_id);
            if ($data_user_info) {
                $user_id = $data_user_info['user_id'];
                $owner_info = $this->wxm_user->get_user_name_email($user_id);
                if ($owner_info) {
                    $owner_name = $owner_info['user_name'];
                    $owner_email = $owner_info['user_email'];
                }
            }

            // check email
            $ret_reportor = wx_check_email($com_email);
            $ret_owner = wx_check_email($owner_email);
            // send report email to reportor and note owner
            if ($ret_reportor && $ret_owner) {
                // init system email settings
                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'smtp.ym.163.com';
                $config['smtp_port'] = 25;
                $config['smtp_user'] = 'report@creamnote.com';
                $config['smtp_pass'] = 'wx@creamnote';
                $config['mailtype'] = 'html';
                $config['validate'] = true;
                $config['crlf'] = '\r\n';
                $config['charset'] = 'utf-8';
                $this->email->initialize($config);

                $reportor_content = '<p>尊敬的 '.$com_email.' 用户，您好：</p><p></p><p>您在 '.substr($com_time, 0, 10).' 举报&投诉的一份笔记名称为《'.$com_note_name.'》的资料，经过我们审查与核实，已经证实部分内容存在违规，我们正在和笔记的拥有者进行进一步的协商、沟通，有关处理结果我们会第一时间通知您。</p>'.'<p></p><p>非常感谢您对Creamnote醍醐笔记网的支持！</p><p></p><p>Creamnote投诉&举报服务小组</p><p>report@creamnote.com</p>';

                $owner_content = '<p>尊敬的 '.$owner_email.' 用户，您好：</p><p></p><p>您的一份名称为《'.$com_note_name.'》的笔记资料，由于受到投诉&举报，经过审核，的确存在部分内容违规，违规的原因请查看邮件下方【违规原因】。</p><p>如果您的笔记确实与【违规原因】中的说明一致，那么请您在3个工作日期间给我们 投诉&举报服务小组写信确认情况属实。</p><p>如果您的笔记资料，没有违反《关于网站上传笔记资料内容的规范》，以及没有侵犯任何第三方的笔记资料版权，您可以给网站的 投诉&举报服务小组（report@creamnote.com）写信，说明情况，我们会做后续的处理工作。</p><p>如果您在接收到此封后的5个工作日内还没有和我们 投诉&举报服务小组 取得联系，那么最终的处理和解释权归Creamnote醍醐笔记网所有。</p>【违规原因】<p>'.$com_reason.'</p><p></p><p>非常感谢您对Creamnote醍醐笔记网的支持！</p><p></p><p>Creamnote投诉&举报服务小组</p><p>report@creamnote.com</p>';
                $send_reportor_ret = $this->_send_report_email_to_user($com_email, $reportor_content);
                $send_owner_ret = $this->_send_report_email_to_user($owner_email, $owner_content);
                if ($send_owner_ret) {
                    // send email to owner
                    $cur_admin_info = $this->wx_util->get_admin_info();
                    $admin_name = $cur_admin_info['admin_user_name'];
                    $com_result = '笔记内容由于部分原因，处于待定状态，需要和投诉人、笔记拥有者进行沟通、协商解决。';
                    $pass_ret = $this->wxm_report->change_step_two($com_id, $admin_name, $com_result);
                    echo 'success';
                    return true;
                }
                else {  // if send mail failed, go to step 2
                    $cur_admin_info = $this->wx_util->get_admin_info();
                    $admin_name = $cur_admin_info['admin_user_name'];
                    $com_result = '被投诉者（笔记的拥有者）的邮件发送不成功';
                    $change_step_two = $this->wxm_report->change_step_two($com_id, $admin_name, $com_result);
                    echo 'email-failed';
                    return false;
                }
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function query_report_result() {
        $com_id = $this->input->post('com_id');

        $result_info = $this->wxm_report->query_report_result($com_id);
        if ($result_info) {
            echo json_encode($result_info['com_result']);
            return true;
        }
        echo json_encode('');
        return false;
    }
/*****************************************************************************/
    public function step_two_pass() {
        $com_id = $this->input->post('com_id');

        if ($com_id > 0) {
            $admin_name = '';
            $admin_info = $this->wx_util->get_admin_info();
            if ($admin_info) {
                $admin_name = $admin_info['admin_user_name'];
            }
            $ret = $this->wxm_report->just_pass_report($com_id, $admin_name);
            if ($ret) {
                echo 'success';
                return true;
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function _send_report_email_to_user($user_email = '', $content) {
        // $user_email = 'dw_wang126@126.com';
        if ($user_email && $content) {
            $this->wx_email->clear();

            $this->wx_email->set_from_user('report@creamnote.com', '醍醐笔记');
            $this->wx_email->set_to_user($user_email);
            $this->wx_email->set_subject('投诉&举报服务中心');
            $this->wx_email->set_message($content);

            $ret = $this->wx_email->send_email();
            if ($ret)
                return true;
        }
        return false;
    }
/*****************************************************************************/
/*****************************************************************************/
}

/* End of file report.php */
/* Location: /application/backend/controllers/report.php */
