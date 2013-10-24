<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 支付宝即时到帐 批量退款 API
 * 来源：使用支付宝官方的即时到账开发包，其中的 refund_fastpay_by_platform_pwd-PHP-UTF-8 开发包
 * 版本：3.3
 * 开发包发布日期：2013-03-20
 * 移植接口: wangdiwen
 */

/*****************************************************************************/
class WX_Alipay_refund_api {
    var $CI;

    var $alipay_config;                                                         // Alipay的通用配置项

    var $notify_url = "http://www.creamnote.com/cnadmin/alipay/notify_url";     //服务器异步通知页面路径
    var $seller_email = '';                                                     //卖家支付宝帐户

    // 以下为每次退款的变量，每次都不同，不能放在类变量中
    // 退款当天日期,格式：年[4位]-月[2位]-日[2位] 小时[2位 24小时制]:分[2位]:秒[2位]，如：2007-10-01 13:13:13
    // var $refund_date = '';
    // var $batch_no = '';     // 批次号,格式：当天日期[8位]+序列号[3至24位]，如：201008010000001
    // var $batch_num = '';    // 参数detail_data的值中，“#”字符出现的数量加1，最大支持1000笔（即“#”字符出现的数量999个）
    // var $detail_data = '';  // 退款详细数据,具体格式请参见接口技术文档

/*****************************************************************************/
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->config->load('alipay_config', true);
        $this->CI->load->helper('wx_alipay_public');

        $this->alipay_config = $this->CI->config->item('alipay_config');
    }
/*****************************************************************************/
    public function alipay_submit(  $refund_date = '',      // 退款此时时间戳
                                    $batch_no = '',         // 批次号，当天日期[8位]+序列号[3至24位]
                                    $batch_num = '',        // 有多少笔退款
                                    $detail_data = '') {    // 退款说明
        //构造要请求的参数数组，无需改动
        $parameter = array(
                "service" => "refund_fastpay_by_platform_pwd",
                "partner" => trim($this->alipay_config['partner']),
                "notify_url"    => $this->notify_url,
                "seller_email"  => $this->alipay_config['seller_email'],
                "refund_date"   => $refund_date,
                "batch_no"  => $batch_no,
                "batch_num" => $batch_num,
                "detail_data"   => $detail_data,
                "_input_charset"    => trim(strtolower($this->alipay_config['input_charset']))
        );

        //建立请求
        $submit = new AlipaySubmit($alipay_config);
        $html_text = $submit->buildRequestForm($parameter,"get", "确认");

        // echo $html_text;
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

/*****************************************************************************/

/* End of file wx_alipay_refund_api.php */
/* Location: /application/backend/libraries/wx_alipay_refund_api.php */
