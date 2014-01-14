<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
/*****************************************************************************/
    public function __construct() {
        parent::__construct();
    }
/*****************************************************************************/
    public function index() {
        echo "<center>亲，欢迎来到 Creamnote.com 微信公众号！</center>";
    }
/*****************************************************************************/
    public function digest() {      // 微信订阅号，入口，用于接收用户信息
        $xml_content = file_get_contents('php://input');

        if ($xml_content) {
            $xml_obj = simplexml_load_string($xml_content, 'SimpleXMLElement', LIBXML_NOCDATA);
            $user_name = $xml_obj->FromUserName;
            $my_name = $xml_obj->ToUserName;
            $msg_type = isset($xml_obj->MsgType) ? $xml_obj->MsgType : '';
            $keyword = isset($xml_obj->Content) ? trim($xml_obj->Content) : '';

            $respose = '';
            if ($msg_type == 'text') {
                // convert the user's msg character to utf-8
                $keyword = mb_convert_encoding($keyword, 'UTF-8');
                if ($keyword) {
                    $process_flag = $this->_filter_keyword($keyword);
                    $respose = $this->_weixin_basefunc($process_flag, $user_name, $my_name); // 3 base func
                }
            }
            elseif ($msg_type == 'event') {
                $event_content = isset($xml_obj->Event) ? $xml_obj->Event : '';  // 事件类型，subscribe(订阅)、unsubscribe(取消订阅)
                $respose = $this->_weixin_event($event_content, $user_name, $my_name);
            }

            if ($respose) {
                echo $respose;
            }
        }
        else {
            echo '';
            exit();
        }
    }
/*****************************************************************************/
/*****************************************************************************/
/*****************************************************************************/
    private function _weixin_event($event = '', $user_name = '', $my_name = '') {
        $respose_text = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
        $msg_time = time();
        $msg_type = 'text';
        $msg_user = '';
        $msg_file = 'application/weixin/weixin_digest/digest_auto_001';
        if ($event == 'subscribe' || $event == 'unsubscribe') {
            if (file_exists($msg_file)) {
                $msg_user = file_get_contents($msg_file);
            }
        }
        if ($msg_user && $user_name && $my_name) {
            return sprintf($respose_text, $user_name, $my_name, $msg_time, $msg_type, $msg_user);
        }
        return '';
    }
/*****************************************************************************/
    private function _weixin_basefunc($process_flag = '001', $user_name = '', $my_name = '') {
        $respose_text = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
        $msg_time = time();
        $msg_type = 'text';
        $msg_user = '';
        $msg_file = 'application/weixin/weixin_digest/digest_auto_';

        if ($process_flag == '001') {
            $msg_file .= $process_flag;
            if (file_exists($msg_file)) {
                $msg_user = file_get_contents($msg_file);
            }
        }
        elseif ($process_flag == '002') {
            $msg_file .= $process_flag;
            if (file_exists($msg_file)) {
                $msg_user = file_get_contents($msg_file);
            }
        }
        elseif ($process_flag == '003') {
            $msg_file .= $process_flag;
            if (file_exists($msg_file)) {
                $msg_user = file_get_contents($msg_file);
            }
        }

        if ($msg_user) {
            return sprintf($respose_text, $user_name, $my_name, $msg_time, $msg_type, $msg_user);
        }
        return '';
    }
/*****************************************************************************/
    private function _filter_keyword($keyword = '') {
        // 定义处理的关键词（英文、中文单词，比如：help，帮助），判断下一步需要处理的标记 flag，
        // 关键词标记及功能，如下：
        // '001' => 欢迎致辞
        // '002' => 帮助
        // '003' => 一则世界超短迷你小说
        $flag = '001';
        if ($keyword) {
            switch ($keyword) {
                case 'hello':
                case '你好':
                    $flag = '001';
                    break;
                case 'help':
                case '帮助':
                    $flag = '002';
                    break;
                case 'story':
                case '故事':
                    $flag = '003';
                    break;
                default:
                    break;
            }
        }
        return $flag;
    }
/*****************************************************************************/
    public function digest_check() {    // 订阅号，开发者验证
        // echo 'digest_check ...';
        $signature = $this->input->get('signature');
        $timestamp = $this->input->get('timestamp');
        $nonce = $this->input->get('nonce');
        $echostr = $this->input->get('echostr');

        $token = 'creamnote';
        $tmp_arr = array($token, $timestamp, $nonce);
        sort($tmp_arr);
        $tmp_str = implode($tmp_arr);
        $tmp_str = sha1($tmp_str);

        // checking valid
        if ($tmp_str == $signature) {
            echo $echostr;      // 接入成功，则原样返回微信的随机字符串
        }
        else {
            echo 'failed';
        }
    }
/*****************************************************************************/
    public function service() {     // 微信服务号，入口

    }
/*****************************************************************************/
    public function service_check() {   // 服务号，开发者验证

    }
/*****************************************************************************/

/*****************************************************************************/
    public function testing() {
        if ('01' === '1') {
            echo 'fuck';
        }
    }
/*****************************************************************************/
}

/* End of file home.php */
/* Location: /application/weixin/controllers/home.php */
