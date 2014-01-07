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
        // wx_loginfo('weixin digest user msg ...');
        // wx_loginfo($xml_content);

        if ($xml_content) {
            // wx_loginfo($xml_content);
            $xml_obj = simplexml_load_string($xml_content, 'SimpleXMLElement', LIBXML_NOCDATA);
            $from_user_name = $xml_obj->FromUserName;
            $to_user_name = $xml_obj->ToUserName;
            $keyword = trim($xml_obj->Content);

            // convert the user's msg character to utf-8
            $keyword = mb_convert_encoding($keyword, 'UTF-8');
            wx_loginfo('user msg => '.$keyword);

            // here, handle user's msg, based by $keyword
            $respose_text = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
            $respose = '';
            if ($keyword) {
                // process the keyword via $keyword
                // 定义处理的关键词（英文、中文单词，比如：help，帮助），判断下一步需要处理的标记 flag，
                // 关键词标记及功能，如下：
                // '001' => 欢迎致辞
                // '002' => 帮助
                // '003' => 一则世界超短迷你小说
                $process_flag = '001';
                switch ($keyword){
                    case 'hello':
                    case '你好':
                        $process_flag = '001';
                        break;
                    case 'help':
                    case '帮助':
                        $process_flag = '002';
                        break;
                    case 'story':
                    case '故事':
                        $process_flag = '003';
                        break;
                    case 'note':
                    case '笔记':
                        $process_flag = '004';
                        break;

                    default:
                        break;
                }

                $msg_type = 'text';
                $msg_time = time();
                $msg_user = "";
                $msg_file = 'application/weixin/weixin_digest/digest_auto_';

                if ($process_flag === '001') {
                    $msg_file .= $process_flag;
                    if (file_exists($msg_file)) {
                        $msg_user = file_get_contents($msg_file);
                    }
                    $respose = sprintf($respose_text, $from_user_name, $to_user_name, $msg_time, $msg_type, $msg_user);
                }
                elseif ($process_flag === '002') {
                    $msg_file .= $process_flag;
                    if (file_exists($msg_file)) {
                        $msg_user = file_get_contents($msg_file);
                    }
                    $respose = sprintf($respose_text, $from_user_name, $to_user_name, $msg_time, $msg_type, $msg_user);
                }
                elseif ($process_flag === '003') {
                    $msg_file .= $process_flag;
                    if (file_exists($msg_file)) {
                        $msg_user = file_get_contents($msg_file);
                    }
                    $respose = sprintf($respose_text, $from_user_name, $to_user_name, $msg_time, $msg_type, $msg_user);
                }
                elseif ($process_flag === '004') {
                    // todo ...
                    $msg_user = "Creamnote.com 醍醐笔记网\n为您推荐精品“学霸笔记” ~";
                    $respose = sprintf($respose_text, $from_user_name, $to_user_name, $msg_time, $msg_type, $msg_user);
                }

                // respose msg to user
                echo $respose;
            }
        }
        else {
            echo '';
            exit();
        }
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
