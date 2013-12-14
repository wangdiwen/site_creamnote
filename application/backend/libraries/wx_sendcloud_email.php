<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WX_Sendcloud_Email
{
/*****************************************************************************/
// 说明：本接口采用 搜狐 SendCloud 邮件服务，Web方式接入，采用 Curl 请求
/*****************************************************************************/
    var $sendcloud_letters_api_user = 'postmaster@letters-creamnote.sendcloud.org';
    var $sendcloud_letters_api_key = 'rD7lBNKr';
    var $sendcloud_digest_api_user = 'postmaster@digest-creamnote.sendcloud.org';
    var $sendcloud_digest_api_key = 'iugYirq1';

    var $from_user_addr = '';
    var $from_user_name = '';
    var $to_user_addr = '';
    var $subject = '';
    var $message = '';
/*****************************************************************************/
    public function __construct() {
        // todo ...
    }
/*****************************************************************************/
    public function clear() {
        $this->from_user_addr = '';
        $this->from_user_name = '';
        $this->to_user_addr = '';
        $this->subject = '';
        $this->message = '';
    }
/*****************************************************************************/
    public function set_from_user($addr = '', $name = '') {
        $this->from_user_addr = $addr;
        $this->from_user_name = $name;
    }
/*****************************************************************************/
    public function set_to_user($addr) {
        $this->to_user_addr = $addr;
    }
/*****************************************************************************/
    public function set_to_user_list($addr_list) {
        if ($addr_list) {
            $this->to_user_addr = implode(';', $addr_list);
        }
    }
/*****************************************************************************/
    public function set_subject($subject = '') {
        $this->subject = $subject;
    }
/*****************************************************************************/
    public function set_message($msg = '') {
        $this->message = $msg;
    }
/*****************************************************************************/
// 发送常规邮件，如注册、激活链接、密码找回
    public function send_letters_email() {
        $curl_obj = curl_init();
        curl_setopt($curl_obj, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl_obj, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl_obj, CURLOPT_URL, 'https://sendcloud.sohu.com/webapi/mail.send.json');

        $param = array(
                    'api_user' => $this->sendcloud_letters_api_user,
                    'api_key' => $this->sendcloud_letters_api_key,
                    'from' => $this->from_user_addr,
                    'fromname' => $this->from_user_name,
                    'to' => $this->to_user_addr,
                    'subject' => $this->subject,
                    'html' => $this->message,
                    );
        curl_setopt($curl_obj, CURLOPT_POSTFIELDS, $param);

        $result = curl_exec($curl_obj);
        if ($result == false) {  // request failed
            wx_loginfo('send_letters_email error: '.curl_error($curl_obj));
        }

        curl_close($curl_obj);
        return $result;
    }
/*****************************************************************************/
// 发送批量邮件，如每周订阅
    public function send_digest_email() {
        $curl_obj = curl_init();
        curl_setopt($curl_obj, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl_obj, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl_obj, CURLOPT_URL, 'https://sendcloud.sohu.com/webapi/mail.send.json');

        $param = array(
                    'api_user' => $this->sendcloud_digest_api_user,
                    'api_key' => $this->sendcloud_digest_api_key,
                    'from' => $this->from_user_addr,
                    'fromname' => $this->from_user_name,
                    'to' => $this->to_user_addr,
                    'subject' => $this->subject,
                    'html' => $this->message,
                    );
        curl_setopt($curl_obj, CURLOPT_POSTFIELDS, $param);

        $result = curl_exec($curl_obj);
        if ($result == false) {  // request failed
            wx_loginfo('send_digest_email error: '.curl_error($curl_obj));
        }

        curl_close($curl_obj);
        return $result;
    }
/*****************************************************************************/
}

/* End of file WX_Sendcloud_Email.php */
/* Location: ./application/libraries/WX_Sendcloud_Email.php */
