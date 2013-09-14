<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WX_Preprocess
{
/*****************************************************************/
    var $CI;  // Get the CI super object

/*******************************y**********************************/
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->helper('url');
    }

/*****************************************************************/
    public function auto_session()
    {
        // 此钩子在每一个URL请求之前运行，判断当前的PHP session数据是否可用，
        // 及判断当前用户是否登录？是否离开？
        // session_start();

        if (isset($_SESSION['wx_user_name']) && $_SESSION['wx_user_name'] != '') {
            return;
        }

        $cur_url = current_url();
        $home_url = base_url();

        // 当URL为以下几个时，页面正常加载，除此之外，一律重定向到主页面
        // 1. http://xxx.com
        // 2. http://xxx.com/home/index
        // 3. http://xxx.com/home/register_page
        // 4. http://xxx.com/home/logout_page
        // 5. http://xxx.com/home/login
        // 基础URL
        $default_home_url = $home_url . 'home/index';
        $register_page_url = $home_url . 'home/register_page';
        $logout_url = $home_url . 'home/logout_page';
        $login_url = $home_url . 'home/login';
        $register_url = $home_url . 'home/register';
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
        //一些静态宣传页面
        $static = explode("static/",$cur_url);
        // 404页面
        $page_404 = $home_url.'primary/wxc_home/page_404';
        // system error
        $sys_error = $home_url.'static/wxc_direct/sys_error';

        //获得用户信息tips
        $user_tips = $home_url."primary/wxc_personal/personal_base_tips";

        // auth code iface
        $auth_code_new = $home_url.'core/wxc_util/get_new_auth_code';
        $auth_code_check = $home_url.'core/wxc_util/check_auth_code';

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

        // 此URL为测试接口，开发阶段验证一些东西，待到项目部署阶段删除
        $test_url = $home_url.'home/test';
		// experiment interface, just for develop new version
		$experiment_url = $home_url.'experiment/wxc_(.*)';

        if ($cur_url == $home_url
            || $cur_url == $find_password_url
            || $cur_url == $find_first_url
            || $cur_url == $find_second_url
            || $cur_url == $find_third_url
            || $cur_url == $default_home_url
            || $cur_url == $register_page_url
            || $cur_url == $register_url
            || $cur_url == $register_email_url
            || $cur_url == $logout_url
            || $cur_url == $active_url
            || $cur_url == $school_url
            || $cur_url == $school_id_url
            // || $cur_url == $feedback_url
            || ereg($feedback_url, $cur_url)
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
            || $cur_url == $sys_error
            || count($static) > 1
            || $cur_url == $user_tips
            || $cur_url == $auth_code_new
            || $cur_url == $auth_code_check
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
            || $cur_url == $test_url/* Test url iface */
			|| ereg($experiment_url, $cur_url)) {
            return;
        }
        else
        {
            if ($cur_url == $login_url
                || ereg($public_view_url_ereg, $cur_url) // 正则匹配通用搜索取得资料详细
                || ereg($public_search_nature_url_ereg, $cur_url)
                || ereg($public_search_area_url_ereg, $cur_url)
                )
            {
                return;
            }

            redirect('home/index');  // 重定向到首页
        }
    }
/*****************************************************************/
}

/* End of wx_preprocess.php */
/* Location: ./application/hooks/wx_preprocess.php */
