<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends CI_Controller
{
/*****************************************************************************/
    public function __construct() {
        parent::__construct();
        $this->load->model('wxm_notice');

        $this->load->library('wx_util');
        $this->load->library('wx_aliossapi');
        $this->load->library('pagination');
    }
/*****************************************************************************/
    public function notice_index($offset = 0) {
        $notice_count = $this->wxm_notice->notice_count();

        // page partion
        $config = array(
            'base_url' => base_url().'cnadmin/content/notice_index',
            'total_rows' => $notice_count,
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

        $notice_page = $this->wxm_notice->get_page_notice($config['per_page'], $offset);
        $data = array(
            'site_notice' => $notice_page,
            'notice_offset' => $offset
            );
        // wx_echoxml($notice_page);
        $this->load->view('f_content/wxv_notice', $data);
    }
/*****************************************************************************/
    public function modify_notice() {
        $notice_id = $this->input->post('notice_id');

        $data = array(
            'notice_content' => ''
            );
        $notice_info = $this->wxm_notice->get_by_id($notice_id);
        if ($notice_info) {
            $content_url = $notice_info['notice_content_url'];
            $notice_url = 'http://wx-notice.oss-internal.aliyuncs.com/'.$content_url;
            $content = file_get_contents($notice_url);

            $data['notice_content'] = $content;
        }
        echo json_encode($data);
    }
/*****************************************************************************/
    public function edit_save_notice() {
        $notice_id = $this->input->post('notice_id');
        $notice_title = $this->input->post('notice_title');
        $notice_content = $this->input->post('notice_content');

        if ($notice_id && $notice_title && $notice_content) {
            // 1. save content to oss
            $content_info = $this->wxm_notice->get_content_url($notice_id);
            if ($content_info) {
                $content_url = $content_info['notice_content_url'];
            }

            $oss_bucket = 'wx-notice';
            $oss_ret = $this->wx_aliossapi->upload_by_content($oss_bucket, $content_url, $notice_content);
            // 2. update to database
            if ($oss_ret) {
                $data = array(
                    'notice_id' => $notice_id,
                    'notice_title' => $notice_title
                    );
                $ret = $this->wxm_notice->update_notice($data);
                if ($ret) {
                    echo 'success';
                    return true;
                }
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function publish_notice() {
        $notice_id = $this->input->get('notice_id');
        $notice_offset = $this->input->get('notice_offset');
        $offset = $notice_offset ? $notice_offset : 0;

        $return_code = '';
        $ret = $this->wxm_notice->enable_notice($notice_id);
        if ($ret) {
            $return_code = 'success';
        }
        else {
            $return_code = 'failed';
        }

        $cookie = array(
            'name' => 'return_code',
            'value' => $return_code,
            'expire' => '1');
        $this->input->set_cookie($cookie);
        if ($offset) {
            redirect('cnadmin/content/notice_index/'.$offset);
        }
        else {
            redirect('cnadmin/content/notice_index');
        }
    }
/*****************************************************************************/
    public function unpublish_notice() {
        $notice_id = $this->input->get('notice_id');
        $notice_offset = $this->input->get('notice_offset');
        $offset = $notice_offset ? $notice_offset : 0;

        $return_code = '';
        $ret = $this->wxm_notice->disable_notice($notice_id);
        if ($ret) {
            $return_code = 'success';
        }
        else {
            $return_code = 'failed';
        }

        $cookie = array(
            'name' => 'return_code',
            'value' => $return_code,
            'expire' => '1');
        $this->input->set_cookie($cookie);
        if ($offset) {
            redirect('cnadmin/content/notice_index/'.$offset);
        }
        else {
            redirect('cnadmin/content/notice_index');
        }
    }
/*****************************************************************************/
    public function create_notice() {
        $notice_title = $this->input->post('notice_title');
        $notice_content = $this->input->post('notice_content');

        $time = date('Y-m-d');
        $content_url = date('YmdHis').'.php';
        $oss_bucket = 'wx-notice';

        if ($notice_title && $notice_content) {
            // 1. save html content to oss bucket
            // http:wx-notice/oss-internal.aliyuncs.com/object
            $oss_ret = $this->wx_aliossapi->upload_by_content($oss_bucket, $content_url, $notice_content);
            // 2. record to database
            if ($oss_ret) {
                $data = array(
                    'notice_title' => $notice_title,
                    'notice_content_url' => $content_url,
                    'notice_time' => $time
                    );
                $ret = $this->wxm_notice->create_notice($data);
                if ($ret) {
                    echo 'success';
                    return true;
                }
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function test() {
        // $notice_page = $this->wxm_notice->get_page_notice(2, 0);
        // wx_echoxml($notice_page);
    }
/*****************************************************************************/
}

/* End of file content.php */
/* Location: /application/backend/controllers/content.php */
