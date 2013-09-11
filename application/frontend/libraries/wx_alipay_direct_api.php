<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 支付宝即时到账接口
 * 来源：使用支付宝官方的即时到账开发包，其中的 create_direct_pay_by_user-PHP-UTF-8 开发包
 * 版本：3.3
 * 开发包发布日期：2012-07-19
 * 移植接口: wangdiwen
 */

/*****************************************************************************/
class WX_Alipay_direct_api
{
    var $CI;        // super CI obj

    var $alipay_config;     // Alipay的通用配置项

    var $payment_type = "1";    //支付类型
    var $notify_url = "http://www.xxx.com/notify_url.php";  //服务器异步通知页面路径
    var $return_url = "http://www.xxx.com/return_url.php";  //页面跳转同步通知页面路径
    var $seller_email = '';     //卖家支付宝帐户
    var $anti_phishing_key = "";    // 防钓鱼时间戳, 若要使用请调用类文件submit中的query_timestamp函数
    var $exter_invoke_ip = "";      // 客户端的IP地址, 非局域网的外网IP地址，如：221.0.0.1

    /********* 以下变量为买家方面的，每次交易都不一样 ************************/
    // var $out_trade_no = '';     // 商户订单号, 商户网站订单系统中唯一订单号，必填
    // var $subject = '';      // 订单名称
    // var $total_fee = '';    // 付款金额
    // var $body = '';         // 订单描述
    // var $show_url = '';     // 商品展示地址, 需以http://开头的完整路径

/*****************************************************************************/
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->config->load('alipay_config', true);
        $this->CI->load->helper('wx_alipay_public');

        $this->alipay_config = $this->CI->config->item('alipay_config');
    }
/*****************************************************************************/
    public function alipay_submit($out_trade_no = '', $subject = '',
                                    $total_fee = '', $body = '', $show_url = '')
    {
        //构造要请求的参数数组
        $parameter = array(
                "service" => "create_direct_pay_by_user",
                "partner" => trim($this->alipay_config['partner']),
                "payment_type"  => $this->payment_type,
                "notify_url"    => $this->notify_url,
                "return_url"    => $this->return_url,
                "seller_email"  => $this->seller_email,
                "out_trade_no"  => $out_trade_no,
                "subject"   => $subject,
                "total_fee" => $total_fee,
                "body"  => $body,
                "show_url"  => $show_url,
                "anti_phishing_key" => $this->anti_phishing_key,
                "exter_invoke_ip"   => $this->exter_invoke_ip,
                "_input_charset"    => trim(strtolower($this->alipay_config['input_charset']))
                );
        //建立请求
        $submit = new AlipaySubmit($this->alipay_config);
        $html_text = $submit->buildRequestForm($parameter,"get", "确认");

        // echo $html_text;
        return $html_text;
    }
/*****************************************************************************/
    public function get_notify()
    {
        //计算得出通知验证结果
        $notify = new AlipayNotify($this->alipay_config);
        $verify_result = $notify->verifyReturn();

        return $verify_result;      // true/false
    }
/*****************************************************************************/
    public function test() {
        echoxml($this->alipay_config);
    }
/*****************************************************************************/
}
/*****************************************************************************/

/* End of file wx_alipay_direct_api.php */
/* Location: ./application/libraries/wx_alipay_direct_api.php */
