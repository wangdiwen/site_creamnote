<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXC_Message extends CI_Controller
{
/*****************************************************************/
    public function __construct()
    {
        parent::__construct();
        $this->load->model('core/wxm_message');
        $this->load->model('core/wxm_notify');
        $this->load->model('core/wxm_user');
    }
/*****************************************************************/
    public function add_message()
    {
        $to_user_id = $this->input->post('to_user_id');
        $content = $this->input->post('message_content');

        $cur_user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        $ajax = '';

        if ($to_user_id > 0 && $cur_user_id > 0 && $content)
        {
            $data = array(
                'message_content' => $content,
                'message_time' => date('Y-m-d H:i:s'),
                'message_user_id' => $cur_user_id,
                'message_to_user_id' => $to_user_id
                );
            $this->wxm_message->insert($data);
            $ajax = 'success';
            echo $ajax;

            // 后台推送通知
            fastcgi_finish_request();

            $has_notify = $this->wxm_notify->has_message_notify($to_user_id, $cur_user_id);
            if (! $has_notify)  // 通知表中还没有此留言的通知记录
            {
                $cur_user_name = '未知';
                // 查询当前留言用户的名称
                $cur_user_info = $this->wxm_user->user_info($cur_user_id);
                if ($cur_user_info)
                {
                    $cur_user_name = $cur_user_info->user_name;
                }

                $notify = array(
                    'notify_type' => '2',
                    'notify_content' => '您有一条留言通知：来自 '.$cur_user_name,
                    'user_id' => $to_user_id,
                    'notify_params' => $cur_user_id,
                    'notify_time' => date('Y-m-d H:i:s')
                    );
                $this->wxm_notify->insert($notify);
            }
        }
        else
        {
            $ajax = 'failed';
            echo $ajax;
        }
    }
/*****************************************************************/

/*****************************************************************/

/*****************************************************************/
}

/* End of file wxc_message.php */
/* Location: ./application/controllers/primary/wxc_message.php */
