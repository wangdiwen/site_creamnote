<?php
/*****************************************************************************/
/**
 * 本地邮件发送接口，使用域名邮箱，no-reply@creamnote.com
 * 使用第三方开源的 PHPMailer 邮件发送类
 */
/**
 * example like this
 */
// $Mail_Server = new WX_Local_Email();
// $Mail_Server->set_from_user('no-reply@creamnote.com', 'Creamnote.com 醍醐笔记网');

// $to_user_list = array(
//     'dw_wang126@126.com',
//     'dw_wang126@163.com',
//     '785644342@qq.com',
//     );
// $subject = '醍醐订阅';
// $contect = 'Creamnote.com 欢迎你！';

// foreach ($to_user_list as $key => $to_user) {
//     $ret_send = $Mail_Server->send($to_user, $subject, $contect);
//     if ($ret_send) {
//         echo 'send success '.$key."\n";
//     }
//     else {
//         echo 'send failed'.$key."\n";
//     }
    // sleep(2);  # cache time
// }
/*****************************************************************************/
// require_once WX_BASE_PATH.WX_SEPARATOR.'lib/phpmailer'.WX_SEPARATOR.'class.phpmailer.php';
// require_once WX_BASE_PATH.WX_SEPARATOR.'lib/phpmailer'.WX_SEPARATOR.'class.smtp.php';
require_once WX_BASE_PATH.WX_SEPARATOR.'lib/phpmailer'.WX_SEPARATOR.'PHPMailerAutoload.php';
/*****************************************************************************/
class WX_Local_Email {
/**************************** config items ***********************************/
    var $PHPMailer = null;

    var $smtp_protocol = 'smtp';
    var $smtp_host = 'smtp.ym.163.com';
    var $smtp_port = 25;
    var $smtp_user = 'no-reply@creamnote.com';
    var $smtp_pass = 'wx@creamnote';
    var $smtp_charset = 'utf-8';

    var $from_user_email = 'no-reply@creamnote.com';
    var $from_user_name = 'Creamnote.com';
    var $to_user_email = '';
    var $subject = '';
    var $message = '';
/*****************************************************************************/
    public function __construct() {
        $this->PHPMailer = new PHPMailer();
        $this->_init();
        // echo "init ok\n";
    }
    public function __destruct() {
        unset($this->PHPMailer);
        $this->PHPMailer = null;
        // echo "del ok\n";
    }
/*****************************************************************************/
/***************************** Email Send API ********************************/
/*****************************************************************************/
    private function _init() {
        // init the PHPMailer server
        $this->PHPMailer->SMTPDebug = 0;
        $this->PHPMailer->isSMTP();
        $this->PHPMailer->Host = $this->smtp_host;
        $this->PHPMailer->Port = $this->smtp_port;
        $this->PHPMailer->SMTPAuth = true;
        $this->PHPMailer->Username = $this->smtp_user;
        $this->PHPMailer->Password = $this->smtp_pass;
        $this->PHPMailer->SMTPSecure = 'tls';
        $this->PHPMailer->CharSet = $this->smtp_charset;
        $this->PHPMailer->WordWrap = 80;
        $this->PHPMailer->isHTML(true);
    }
/*****************************************************************************/
/*****************************************************************************/
    public function set_from_user($from_user_email = '', $from_user_name = '') {
        if ($from_user_email && $from_user_name) {
            if ($this->PHPMailer) {
                $this->PHPMailer->Username = $from_user_email;
            }
            $this->from_user_email = $from_user_email;
            $this->from_user_name = $from_user_name;
        }
    }
/*****************************************************************************/
    public function send($to_user_email = '', $subject = '', $message = '') {
        if ($to_user_email && $subject && $message) {
            // todo ...
            if ($this->PHPMailer) {
                $this->PHPMailer->clearAddresses();
                $this->PHPMailer->clearCCs();
                $this->PHPMailer->clearBCCs();

                $this->PHPMailer->From = $this->from_user_email;
                $this->PHPMailer->FromName = $this->from_user_name;

                $this->PHPMailer->addAddress($to_user_email);
                $this->PHPMailer->Subject = $subject;
                $this->PHPMailer->Body    = $message;
                if ($this->PHPMailer->send()) {
                    return true;
                }
                else {
                    wx_log('PHPMailer send failed: '.$this->PHPMailer->ErrorInfo, 'phpmailer_error');
                }
            }
        }
        return false;
    }
/*****************************************************************************/
}
