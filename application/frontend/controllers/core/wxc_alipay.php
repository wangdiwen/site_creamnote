<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WXC_Alipay extends CI_Controller
{
/*****************************************************************************/
    public function __construct()
    {
        parent::__construct();

        $this->load->library('wx_alipayapi');      // 调用自定义的支付宝类
    }
/*****************************************************************************/
    public function alipay_submit()             // 支付接口
    {
        // 在此，可以加入平台的一些前期的逻辑处理代码
        // 取得当次的订单参数
        // 商户订单号, 商户网站订单系统中唯一订单号，必填
        $out_trade_no = '';
        // 订单名称
        $subject = '';
        // 付款金额
        $total_fee = '';
        // 订单描述
        $body = '';
        // 商品展示地址, 需以http://开头的完整路径，例如：http://www.xxx.com/myorder.html
        $show_url = '';

        // Todo...

        $html_content = $this->wx_alipayapi->alipay_submit($out_trade_no, $subject, $total_fee, $body, $show_url);
        echo $html_content;
    }
/*****************************************************************************/
    public function return_url()    // 支付宝同步通知跳转url
    {
        $verify_result = $this->wx_alipayapi->get_notify();
        if ($verify_result)     // 验证成功
        {
            //商户订单号
            $out_trade_no = $this->input->get('out_trade_no');
            //支付宝交易号
            $trade_no = $this->input->get('trade_no');
            //交易状态
            $trade_status = $this->input->get('trade_status');

            if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS')
            {
                // 支付宝返回的支付成功状态
                // 可以再次加入平台的订单处理逻辑代码，如记录数据库，提供用户一下必要的服务信息等
                // 注意：如果是异步通知的url，还可以根据订单号对数据库中的订单进行查询，判断是否已经处理了？

                // Todo...
            }
            else
            {
                echo "支付宝同步通知: 非法返回的状态 ".$trade_status."<br />";
            }

            echo "支付宝同步通知: 验证成功"."<br />";
        }
        else
        {
            echo "支付宝同步通知: 验证失败";
            return;
        }
    }
/*****************************************************************************/
    public function notify_url()    // 支付宝异步通知url
    {
        $verify_result = $this->wx_alipayapi->get_notify();
        if ($verify_result)     // 验证成功
        {
            //商户订单号
            $out_trade_no = $this->input->get('out_trade_no');
            //支付宝交易号
            $trade_no = $this->input->get('trade_no');
            //交易状态
            $trade_status = $this->input->get('trade_status');

            if($trade_status == 'TRADE_FINISHED')
            {
                //注意：
                //该种交易状态只在两种情况下出现
                //1、开通了普通即时到账，买家付款成功后。
                //2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。

                // 说明：我们网站平台一般在此做一些逻辑处理，比如：判断订单数据库中是否已经对改订单号进行了处理了？
                // 因为在同步通知url中会提前做业务处理，使用在此做判断，是为了保险起见。

                // Todo...
            }
            elseif ($trade_status == 'TRADE_SUCCESS')
            {
                //注意：
                //该种交易状态只在一种情况下出现——开通了高级即时到账，买家付款成功后。

                // 说明：状态的逻辑按照我们平台开通的服务决定？

                // Todo...
            }
            else
            {
                echo "支付宝异步通知: 非法返回的状态 ".$trade_status."<br />";
                return;
            }

            echo "success";     //请不要修改或删除, 必须返回succes字符串给支付宝后台！！！
        }
        else
        {
            echo "支付宝异步通知: 验证失败";
            return;
        }
    }
/*****************************************************************************/
    public function test()
    {
        echo "hi, alipay controller function..."."<br />";
        $this->wx_alipayapi->test();
    }
/*****************************************************************************/
}

/* End of file alipay.php */
/* Location: ./application/controllers/alipay.php */
