<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 支付宝即时到账 收款 API
 * 来源：使用支付宝官方的即时到账开发包，其中的 create_direct_pay_by_user-PHP-UTF-8 开发包
 * 版本：3.3
 * 开发包发布日期：2013-03-20
 * 移植接口: wangdiwen
 */

/*****************************************************************************/
class WX_Zhifubao_Login_Api {
    var $CI;

    var $alipay_config;                                                         // Alipay的通用配置项

    var $return_url = "http://www.creamnote.com/core/wxc_zhifubao_login/zhifubao_return_url";    //页面跳转同步通知页面路径
    var $anti_phishing_key = "";                                                // 防钓鱼时间戳, 若要使用请调用类文件submit中的query_timestamp函数
    var $exter_invoke_ip = "";                                                  // 客户端的IP地址, 非局域网的外网IP地址，如：221.0.0.1

/*****************************************************************************/
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->config->load('alipay_config', true);     // 数组名与配置文件名称相同
        $this->CI->load->helper('wx_alipay_public');

        $this->alipay_config = $this->CI->config->item('alipay_config');
    }
/*****************************************************************************/
    public function alipay_submit() {
        //构造要请求的参数数组
        $parameter = array(
				"service" => "alipay.auth.authorize",
                "partner" => trim($this->alipay_config['partner']),
                "target_service" => "user.auth.quick.login",
                "return_url"    => $this->return_url,
                "anti_phishing_key" => $this->anti_phishing_key,
                "exter_invoke_ip"   => $this->exter_invoke_ip,
                "_input_charset"    => trim(strtolower($this->alipay_config['input_charset'])),
                );
        //建立请求
        $submit = new AlipaySubmit($this->alipay_config);
        $html_text = $submit->buildRequestForm($parameter,"get", "确认");
        return $html_text;
    }
/*****************************************************************************/
    public function get_notify() {
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

/* End of file wx_zhifubao_login_api.php */
/* Location: /application/frontend/libraries/wx_alipay_direct_api.php */
