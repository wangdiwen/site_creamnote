<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Alipay extends CI_Controller {
/*****************************************************************************/
    public function __construct() {
        parent::__construct();
        // $this->load->model('wxm_admin_user');
        // $this->load->library('wx_util');

        $this->load->library('wx_alipay_refund_api');       // 调用自定义的支付宝 退款类
    }
/*****************************************************************************/
    public function alipay_refund_submit() {
        $refund_date = date('Y-m-d H:i:s');     // 退款当前时间戳
        $batch_no = date('Ymd').'123456';       // 批次号，当天日期[8位]+序列号[3至24位]
        $batch_num = '1';                       // 总笔数
        $detail_data = '';                  // 单笔数据集

        $html_content = $this->wx_alipay_refund_api->alipay_submit($refund_date, $batch_no, $batch_num, $detail_data);
        echo $html_content;
    }
/*****************************************************************************/

/*****************************************************************************/
    public function notify_url() {      // 退款异步通知接口
        $verify_result = $this->wx_alipay_refund_api->get_notify();
        if ($verify_result) {           // 验证成功
            $batch_no = $this->input->post('batch_no');             // 批次号
            $success_num = $this->input->post('success_num');       // 批量退款数据中转账成功的笔数
            $result_details = $this->input->post('result_details'); // 批量退款数据中的详细信息

            // 根据上面得到的信息，判断网站中是否已经处理了此笔退款
            // Todo ...


            echo "success";         //请不要修改或删除, 必须返回succes字符串给支付宝后台！！！
        }
        else {                      // 验证失败
            echo 'fail';
        }
    }
/*****************************************************************************/
    public function test() {
        echo 'CMS alipay interface...';
    }
/*****************************************************************************/
}

/* End of file alipay.php */
/* Location: /application/backend/controllers/alipay.php */
