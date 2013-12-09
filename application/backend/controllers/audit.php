<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Audit extends CI_Controller
{
/*****************************************************************************/
    public function __construct()
    {
        parent::__construct();
        $this->load->model('wxm_data');
        $this->load->model('wxm_data_activity');
        $this->load->model('wxm_notify');

        $this->load->library('wx_util');
        $this->load->library('pagination');
    }
/*****************************************************************************/
    public function audit_index($offset = 0) {
        $note_count = $this->wxm_data->note_count();

        // page partion
        $config = array(
            'base_url' => base_url().'cnadmin/audit/audit_index',
            'total_rows' => $note_count,
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

        $note_page = $this->wxm_data->get_note_page($config['per_page'], $offset);
        $data = array(
            'note_data' => $note_page,
            'note_offset' => $offset
            );
        // wx_echoxml($note_page);
        $this->load->view('f_data_examine/wxv_data_examine', $data);
    }
/*****************************************************************************/
    public function pass_audit() {
        $data_id = $this->input->get('data_id');
        $data_offset = $this->input->get('note_offset');
        $offset = $data_offset ? $data_offset : 0;

        $return_code = '';
        $ret = $this->wxm_data->pass_audit($data_id);
        // 审核通过的资料，将审核得分置为50点
        $ret_exam = $this->wxm_data_activity->update_examine_point($data_id, 50);
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
            redirect('cnadmin/audit/audit_index/'.$offset);
        }
        else {
            redirect('cnadmin/audit/audit_index');
        }
    }
/*****************************************************************************/
    public function unpass_audit() {
        $data_id = $this->input->get('data_id');
        $user_id = $this->input->get('user_id');
        $data_name = $this->input->get('data_name');
        $data_offset = $this->input->get('note_offset');
        $offset = $data_offset ? $data_offset : 0;

        $return_code = '';
        $ret = $this->wxm_data->unpass_audit($data_id);
        if ($ret) {
            // send system notify
            $title = '您有一条系统通知：笔记资料审核';
            $content = '亲爱的用户,您的名称为【'.$data_name.'】笔记资料没有通过审核~~';
            $notify_ret = $this->send_note_notify($user_id, $title, $content);
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
            redirect('cnadmin/audit/audit_index/'.$offset);
        }
        else {
            redirect('cnadmin/audit/audit_index');
        }
    }
/*****************************************************************************/
    public function auto_audit_note() {
        // echo '========== 自动审核笔记（ 批次处理 10 份）==========<br />';
        // 自动审核通过要求：1，页数 >= 5；2，笔记免费；3，笔记所有者分享笔记 >= 5；
        // 批处理 10 份，与 CMS 分页数量保持一致；第（3）点暂时不加；

        // get 10 notes for handle
        $note_page = $this->wxm_data->get_note_page(10, 0);
        if ($note_page) {
            foreach ($note_page as $key => $value) {
                $data_id = $value['data_id'];
                $data_page_count = $value['data_pagecount'];
                $data_price = $value['data_price'];
                $user_id = $value['user_id'];  // 3 point, not support

                // filter conditions
                if ($data_page_count >= 5 && $data_price == 0.00) {
                    // 将资料状态打为开放搜索
                    $ret = $this->wxm_data->pass_audit($data_id);
                    // 审核通过的资料，将审核得分置为50点
                    $ret_exam = $this->wxm_data_activity->update_examine_point($data_id, 50);
                    // if ($ret && $ret_exam) {
                    //     echo '========== 序号：'.$key.' ==> Data ID : '.$data_id.' 审核【 通过 】<br />';
                    // }
                    // else {
                    //     echo '========== 序号：'.$key.' ==> Data ID : '.$data_id.' 审核【 失败 】<br />';
                    // }
                }
                else {
                    // echo '========== 序号：'.$key.' ==> Data ID : '.$data_id.' 审核【 不满足条件 】<br />';
                }
            }
        }
        else {
            // echo '========== 没有笔记需要审核 。。。<br />';
            echo 'failed';  // no note to handle
            return false;
        }

        echo 'success';  // audit ok
        return true;
    }
/*****************************************************************************/
    public function mark_goog_note() {
        $data_id = $this->input->post('data_id');

        $ret = $this->wxm_data->mark_good($data_id);
        if ($ret) {
            echo 'success';
            return true;
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function preview_note() {
        $data_id = $this->input->post('data_id');

        $data = array(
            'data_flash_url' => ''
            );
        $data_storage_info = $this->wxm_data->get_storage_info($data_id);
        if ($data_storage_info) {
            $object_name = $data_storage_info['data_objectname'];
            $oss_path = $data_storage_info['data_osspath'];
            $vps_path = $data_storage_info['data_vpspath'];

            $flash_file = wx_get_filename($object_name).'.swf';
            if ($oss_path) {
                $bucket = 'wx-flash';
                $data_flash_url = 'http://'.$bucket.'.oss.aliyuncs.com/'.$flash_file;
                $data['data_flash_url'] = $data_flash_url;
            }
            elseif ($vps_path) {
                $data['data_flash_url'] = '/upload/flash/'.$flash_file;
            }
        }
        echo json_encode($data);
        return;
    }
/*****************************************************************************/
    public function delete_note_force() {
        $data_id = $this->input->get('data_id');

        // Todo...
    }
/*****************************************************************************/
    public function send_note_notify($user_id = 0, $notify_title = '', $notify_content = '') {
        // system notify, type = 4
        if ($user_id > 0 && $notify_title && $notify_content) {
            $ret = $this->wxm_notify->send_system_notify($user_id, $notify_title, $notify_content);
            if ($ret) {
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
}

/* End of file audit.php */
/* Location: /application/backend/controllers/audit.php */
