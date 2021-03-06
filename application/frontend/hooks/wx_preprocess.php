<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WX_Preprocess {
/*****************************************************************/
    var $CI;  // Get the CI super object

/*******************************y**********************************/
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->library('wx_session');
    }

/*****************************************************************/
    public function auto_session() {
        // 此钩子在每一个URL请求之前运行，判断当前的PHP session数据是否可用，
        // 及判断当前用户是否登录？是否离开？
        // session_start();

        if (isset($_SESSION['wx_user_id']) && $_SESSION['wx_user_id'] > 0) {
            // 目前CI Session过期时间为10分钟，600s，一般当前时间总是大于等于
            // 最后一次记录的CI Session时间，差值即是CI Session过期的时间秒数
            $last_activity_time = $this->CI->session->userdata('last_activity');  // seconds
            $now_time = time();
            $diff_time = $now_time - $last_activity_time;
            // wx_loginfo('Diff time = '.$diff_time);
            if ($diff_time >= 300) {  // update user's CI session id
                $ci_session = $this->CI->session->userdata('session_id');
                $cur_user_id = $_SESSION['wx_user_id'];
                if ($ci_session && $cur_user_id) {
                    $ret = $this->CI->wx_session->record_user_ci_session($cur_user_id, $ci_session);
                }
            }
            return;
        }

        $cur_url = current_url();
        $home_url = base_url();

        // 当URL为以下几个时，页面正常加载，除此之外，一律重定向到主页面
        // 基础URL
        $default_home_url = $home_url . 'home/index';
        $register_page_url = $home_url . 'home/register_page';
        $logout_url = $home_url . 'home/logout_page';
        $login_url = $home_url . 'home/login';
        $register_url = $home_url . 'home/register';
        $send_register_link_again = $home_url.'home/register_active_link_again';
        $register_email_url = $home_url.'home/check_email';

        // 激活链接验证的url排除
        $active_url = $home_url.'user_active';
        // 注册页中的取得学校相关信息的url，开放
        $school_url = $home_url.'data/wxc_data/get_depart_by_school';
        // 注册页面中获得学校的id，开放
        $school_id_url = $home_url.'data/wxc_data/get_area_id_by_school_name';
        //注册跳转页
        $skip_page =$home_url."home/skip_page";
        // 意见反馈
        $feedback_url = $home_url.'primary/wxc_feedback/feedback_page((/)?([0-9]*)?)';
        $feedback_create_url = $home_url.'primary/wxc_feedback/create_feedback';
        $feedback_follow_url = $home_url.'primary/wxc_feedback/follow_feedback';
        // 添加找回密码url链接
        $find_password_url = $home_url.'home/find_password_page';
        $find_first_url = $home_url.'home/find_password_first_step';
        $find_second_url = $home_url.'home/find_password_sec_step';
        $find_third_url = $home_url.'home/find_password_third_step';
        // 投诉&举报
        $report_url = $home_url.'primary/wxc_feedback/report_page(.*)';
        // 开放通用搜索接口
        $public_search_url = $home_url.'primary/wxc_search/public_search';
        $public_gen_search = $home_url.'primary/wxc_search/gen_search';
        $public_search_user_url = $home_url.'primary/wxc_search/search_by_user';
        $public_view_url_ereg = $home_url."data/wxc_data/data_view/[0-9]+";
        $public_search_area_url_ereg = $home_url."primary/wxc_search/search_by_area/[0-9]+";
        $public_search_nature_url_ereg = $home_url."primary/wxc_search/search_by_nature/[0-9]+";
        $public_gen_area_url_ereg = $home_url."primary/wxc_search/gen_search_by_area_id";
        $public_gen_nature_url_ereg = $home_url."primary/wxc_search/gen_search_by_nature_id";
        $public_data_list = $home_url."data/wxc_data/data_list";
        // super user's search link
        $public_search_super_user = $home_url.'primary/wxc_search/search_by_all_user/[0-9]+';
        $public_search_super_user_area = $home_url.'primary/wxc_search/search_by_all_user/[0-9]+';
        // static public resources
        $static_url = $home_url.'static/(.*)';
        // 404页面
        $page_404 = $home_url.'primary/wxc_home/page_404';
        //获得用户信息tips
        $user_tips = $home_url."primary/wxc_personal/personal_base_tips";

        // auth code iface
        $auth_code_new = $home_url.'core/wxc_util/get_new_auth_code';
        $auth_code_check = $home_url.'core/wxc_util/check_auth_code';
        $browser_check = $home_url.'core/wxc_util/get_browser_info';

        // util require url
        $util_url = $home_url.'core/wxc_util/(.*)';

        // qq link
        $qq_back = $home_url.'core/wxc_user_manager/qq_back_func';
        $qq_check_bind = $home_url.'core/wxc_user_manager/check_qq_bind';
        $third_party_login = $home_url.'core/wxc_user_manager/third_party_login';
        $quick_login = $home_url.'primary/wxc_home/quick_login';
        $check_nice_name = $home_url.'core/wxc_user_manager/check_nice_name';
        $get_login_name = $home_url.'home/get_login_name';
        // weibo link
        $weibo_back_func = $home_url.'core/wxc_user_manager/weibo_back_func';
        $weibo_cancel_back_func = $home_url.'core/wxc_user_manager/weibo_cancel_back_func';
        // renren link
        $renren_back_func = $home_url.'core/wxc_user_manager/renren_back_func';
        // week article
        $week_article = $home_url.'core/wxc_content/(.*)';

        // 支付宝公共回调接口
        $alipay_url = $home_url.'core/wxc_alipay/(.*)';
        // 支付宝账户验证登录接口
        $zhifubao_login_url = $home_url.'core/wxc_zhifubao_login/(.*)';
        // 邮件订阅退订接口，开放
        $reject_digest_email_url = $home_url.'primary/wxc_personal/update_userinfo_page_digest/(.*)';

        // open api
        $open_api_url = $home_url.'openapi/(.*)';

        // 此URL为测试接口，开发阶段验证一些东西，待到项目部署阶段删除
        $test_url = $home_url.'home/test';
        $experiment_url = $home_url.'experiment/(.*)';

        if ($cur_url == $home_url
            || $cur_url == $login_url
            || $cur_url == $find_password_url
            || $cur_url == $find_first_url
            || $cur_url == $find_second_url
            || $cur_url == $find_third_url
            || $cur_url == $default_home_url
            || $cur_url == $register_page_url
            || $cur_url == $register_url
            || $cur_url == $send_register_link_again
            || $cur_url == $register_email_url
            || $cur_url == $logout_url
            || $cur_url == $active_url
            || $cur_url == $school_url
            || $cur_url == $school_id_url
            || ereg($feedback_url, $cur_url)
            || ereg($open_api_url, $cur_url)
            || $cur_url == $feedback_create_url
            || $cur_url == $feedback_follow_url
            || ereg($report_url, $cur_url)
        	|| $cur_url == $skip_page
            || $cur_url == $public_search_url
            || $cur_url == $public_gen_search
            || $cur_url == $public_search_user_url
            || $cur_url == $public_gen_area_url_ereg
            || $cur_url == $public_gen_nature_url_ereg
        	|| $cur_url == $public_data_list
            || $cur_url == $page_404
            || ereg($static_url, $cur_url)
            || $cur_url == $user_tips
            || $cur_url == $auth_code_new
            || $cur_url == $auth_code_check
            || $cur_url == $browser_check
            || $cur_url == $third_party_login
            || $cur_url == $check_nice_name
            || $cur_url == $get_login_name
            || $cur_url == $quick_login
            || $cur_url == $qq_back
            || $cur_url == $qq_check_bind
            || $cur_url == $weibo_back_func
            || $cur_url == $weibo_cancel_back_func
            || $cur_url == $renren_back_func
            || ereg($week_article, $cur_url)
            || ereg($alipay_url, $cur_url)
            || ereg($zhifubao_login_url, $cur_url)
            || ereg($util_url, $cur_url)
            || ereg($public_search_super_user, $cur_url)
            || ereg($public_search_super_user_area, $cur_url)
            || ereg($reject_digest_email_url, $cur_url)
            || ereg($public_view_url_ereg, $cur_url)
            || ereg($public_search_nature_url_ereg, $cur_url)
            || ereg($public_search_area_url_ereg, $cur_url)
            || ereg($experiment_url, $cur_url)
            || $cur_url == $test_url/* Test url iface */) {
            return;
        }
        else
        {
            redirect('home/index');  // 重定向到首页
        }
    }
/*****************************************************************/
}

/* End of wx_preprocess.php */
/* Location: ./application/hooks/wx_preprocess.php */
