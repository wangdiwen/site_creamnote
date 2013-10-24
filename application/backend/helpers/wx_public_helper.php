<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * This is our debug helper functions,
 * for output some debug info.
*/
/*****************************************************************************/

/*****************************************************************************/
if (! function_exists('wx_check_email'))
{
    function wx_check_email($email = '') {
        $rule = "^[a-zA-Z0-9_]+@[a-zA-Z0-9.]+";
        if (ereg($rule, $email)) {
            return true;
        }
        return false;
    }
}
/*****************************************************************************/
if (! function_exists('wx_is_ten_multi')) {  // 判断给的2位小数的数字是否为10.00的整数倍
    function wx_is_ten_multi($money_str = '') {
        if (ereg('^[1-9]+[0-9]*0\.00$', $money_str)) {
            return true;
        }
        return false;
    }
}
/*****************************************************************************/
if (! function_exists('wx_withdraw_money_list')) {      // 返回10的整数倍，列表
    function wx_withdraw_money_list($money_str = '', $decimal = false) {
        $data = array();
        $money = (int)$money_str;
        $multi = floor($money / 10);
        for ($i = 1; $i <= $multi; $i++) {
            if ($decimal) {
                $data[] = number_format($i * 10, 2, '.', '');
            }
            else {
                $data[] = $i * 10;
            }
        }
        return $data;
    }
}
/*****************************************************************************/
if (! function_exists('wx_get_gravatar_image'))
{
    function wx_get_gravatar_image($email = '', $image_size = 80)
    {
        $url = '';
        if ($email) {
            $url = $url.'http://www.gravatar.com/avatar/'.md5(strtolower(trim($email))).'?s='.$image_size.'&d=identicon&r=PG';
        }
        return $url;
    }
}
/*****************************************************************************/
if (! function_exists('wx_delete_file'))
{
    function wx_delete_file($file = '')
    {
        if (file_exists($file)) {
            $status = 1;
            $cmd = '/bin/sh /alidata/server/creamnote/bin/deletefile.sh '.$file.' >/dev/null 2>&1';
            $ret = system($cmd, $status);
            if ($status == 0)
                return true;
            return false;
        }
    }
}
/*****************************************************************************/
if (! function_exists('wx_delete_dir'))
{
    function wx_delete_dir($dir = '')
    {
        if ($dir && is_dir($dir))
        {
            $objects = scandir($dir);
            foreach ($objects as $object)
            {
                if ($object != "." && $object != "..")
                {
                    if (filetype($dir."/".$object) == "dir")
                    {
                        wx_delete_dir($dir."/".$object);  // invoke recursively
                    }
                    else
                    {
                        unlink($dir."/".$object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}
/*****************************************************************************/
if (! function_exists('wx_cur_week')) {
    function wx_cur_week() {
        $today = date('Y-m-d');
        return date('w',strtotime($today));
    }
}
/*****************************************************************************/
if (! function_exists('wx_month')) {
    function wx_month() {
        return date('Y-m');
    }
}
/*****************************************************************************/
if (! function_exists('wx_last_month')) {
    function wx_last_month() {
        return date('Y-m', strtotime('-1 month'));
    }
}
/*****************************************************************************/
if (! function_exists('wx_before_last_month')) {
    function wx_before_last_month() {
        return date('Y-m', strtotime('-2 month'));
    }
}
/*****************************************************************************/
if (! function_exists('wx_get_today_time'))
{
    function wx_get_today_time()
    {
        $set_time = '00:01:00';
        $today = date('Y-m-d');
        $today_time = $today.' '.$set_time;
        return $today_time;
    }
}
/*****************************************************************************/
if (! function_exists('wx_get_yesterday_time'))
{
    function wx_get_yesterday_time()
    {
        $set_time = '00:01:00';
        $yesterday = date('Y-m-d', strtotime('yesterday'));
        $yesterday_time = $yesterday.' '.$set_time;
        return $yesterday_time;
    }
}
/*****************************************************************************/
if (! function_exists('wx_get_before_yesterday_time'))
{
    function wx_get_before_yesterday_time()
    {
        $set_time = '00:01:00';
        $before_yesterday = date('Y-m-d', strtotime('-2 day'));
        $before_yesterday_time = $before_yesterday.' '.$set_time;
        return $before_yesterday_time;
    }
}
/*****************************************************************************/
if (! function_exists('wx_get_any_before_today')) {
    function wx_get_any_before_today($any_day_before = '7') {
        $set_time = '00:01:00';
        $any_before = date('Y-m-d', strtotime('-'.$any_day_before.' day'));
        $any_before_time = $any_before.' '.$set_time;
        return $any_before_time;
    }
}
/*****************************************************************************/
if (! function_exists('wx_get_tomorrow_time'))
{
    function wx_get_tomorrow_time()
    {
        $set_time = '00:01:00';
        $tomorrow = date('Y-m-d', strtotime('tomorrow'));
        $tomorrow_time = $tomorrow.' '.$set_time;
        return $tomorrow_time;
    }
}
/*****************************************************************************/
if (! function_exists('wx_get_after_tomorrow_time')) {
    function wx_get_after_tomorrow_time() {
        $set_time = '00:01:00';
        $after_tomorrow = date('Y-m-d', strtotime('+2 day'));
        $after_tomorrow_time = $after_tomorrow.' '.$set_time;
        return $after_tomorrow_time;
    }
}
/*****************************************************************************/
if (! function_exists('wx_get_filename'))
{
    function wx_get_filename($file_name = '')
    {
        if ($file_name)
        {
            $list = explode('.', $file_name);
            $list = array_reverse($list);
            $suffix = $list[0];
            $length = count($list);
            $name = "";
            for ($i = $length - 1; $i >= 1; $i--)
            {
                if ($i == $length-1)
                {
                    $name = $name . $list[$i];
                }
                else
                {
                    $name = $name . '.' . $list[$i];
                }
            }

            return $name;
        }
        return '';
    }
}
/*****************************************************************************/
if (! function_exists('wx_get_suffix'))
{
    function wx_get_suffix($file = '')
    {
        if ($file)
        {
            $array = explode('.', $file);
            $array = array_reverse($array);
            return $array[0];
        }
        return '';
    }
}
/*****************************************************************************/
if ( ! function_exists('wx_loginfo'))  // 记录日志函数，使用级别为error最高级别
{
    function wx_loginfo($msg = '')
    {
        log_message('error', $msg);
    }
}
/*****************************************************************************/
if ( ! function_exists('wx_debug'))  // 输出500错误页面，以供调试使用
{
    function wx_debug($msg = '')
    {
        show_error($msg, 500, 'Debuging Mode Page');
    }
}
/*****************************************************************************/
if ( ! function_exists('wx_echoxml'))  // 打印数组类型的变量，以xml格式化输出
{
    function wx_echoxml($msg = '')
    {
        echo '<pre>';
        print_r($msg);
        echo '</pre>';
    }
}
/*****************************************************************************/

/* End of file wx_debug_helper.php */
/* Location: ./application/helpers/wx_debug_helper.php */
