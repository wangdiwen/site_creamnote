<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * This is our debug helper functions,
 * for output some debug info.
*/
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
if (! function_exists('wx_money_distribute')) {         // 笔记付费的分配
    function wx_money_distribute($money_str = '0.00') {
        $money = (float)$money_str;
        $creamnote_money = number_format($money * 0.2, 2, '.', '');
        $alipay_money = number_format($money * 0.012, 2, '.', '');
        $owner_money = number_format($money - $creamnote_money - $alipay_money, 2, '.', '');
        $data = array(
            'creamnote' => $creamnote_money,
            'alipay' => $alipay_money,
            'owner' => $owner_money,
            );
        return $data;
    }
}
/*****************************************************************************/
if (! function_exists('wx_withdraw_money_list')) {      // 返回10的整数倍，列表
    function wx_withdraw_money_list($money_str = '') {
        $data = array();
        $money = (int)$money_str;
        $multi = floor($money / 10);
        for ($i = 1; $i <= $multi; $i++) {
            $data[] = $i * 10;
        }
        return $data;
    }
}
/*****************************************************************************/
if (! function_exists('wx_withdraw_money')) {  // 醍醐提现的金额为10的整数倍
    function wx_withdraw_money($money_str = '') {
        $money = (float)$money_str;
        $multi = floor($money / 10.00);
        $withdraw =  number_format(round($multi * 10), 2, '.', '');
        $balance = number_format($money - $withdraw, 2, '.', '');
        $data = array(
            'money' => number_format($money, 2, '.', ''),
            'withdraw' => $withdraw,
            'balance' => $balance,
            );
        return $data;
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
if (! function_exists('wx_substr_by_length')) {
    // 根据规定的长度值，将字符串分割成等长的字符数组
    // 其中indent为首行缩进值，一个汉字==4个空格，默认首行缩进2个汉字
    function wx_substr_by_length($str = '', $sub_length = 0, $indent = 8) {
        if ($str && $sub_length > 0) {
            $sub_str_list = array();

            $indent_count = floor($indent / 2);
            $indent_space = floor($indent / 4);
            if ($indent_count) {
                for ($i = 0; $i < $indent_count; $i++) {
                    $str = '  '.$str;
                }
            }
            $str_len = mb_strlen($str, 'UTF-8');
            $sub_count = floor($str_len / $sub_length);

            if ($sub_count > 0) {
                for ($i = 0, $j = 0; $i < $sub_count; $i++) {
                    if ($indent_count && $i == 0) {
                        $tmp_str = mb_substr($str, $j, $sub_length + $indent_count + $indent_space, 'UTF-8');
                        $sub_str_list[] = $tmp_str;
                        $j += $sub_length + $indent_count + $indent_space;
                    }
                    else {
                        $tmp_str = mb_substr($str, $j, $sub_length, 'UTF-8');
                        $sub_str_list[] = $tmp_str;
                        $j += $sub_length;
                    }
                }
                $sub_total_len = $sub_length * $sub_count + $indent;
                if ($sub_total_len < $str_len) {
                    $end_str = mb_substr($str, $sub_total_len, $sub_length, 'UTF-8');
                    $sub_str_list[] = $end_str;
                }
            }
            else {
                $sub_str_list[] = $str;
            }
            return $sub_str_list;
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
                        // unlink($dir."/".$object);
                        $ret = wx_delete_file($dir."/".$object);  // shred file
                    }
                }
            }
            reset($objects);
            rmdir($dir);  // del dir
            return true;
        }
        return false;
    }
}
/*****************************************************************************/
if (! function_exists('wx_delete_file'))
{
    function wx_delete_file($file = '')  // shred the file
    {
        if (file_exists($file)) {
            $status = 1;
            // $cmd = '/bin/sh /alidata/server/creamnote/bin/deletefile.sh '.$file.' >/dev/null 2>&1';
            $cmd = 'shred -zu  '.$file.' >/dev/null 2>&1';
            $ret = system($cmd, $status);
            if ($status == 0) {
                return true;
            }
        }
        return false;
    }
}
/*****************************************************************************/
if (! function_exists('wx_move_file'))
{
    function wx_move_file($from_file = '', $to = '')
    {
        if (file_exists($from_file)) {
            $status = 1;
            $cmd = '/bin/sh /alidata/server/creamnote/bin/move_file.sh '.$from_file.' '.$to.' >/dev/null 2>&1';
            $ret = system($cmd, $status);
            if ($status == 0)
                return true;
            return false;
        }
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
if (! function_exists('wx_trim_all')) {
	function wx_trim_all($str = '') {
		if ($str) {
			return str_replace(array(" ","  ","\t","\n","\r"), array("","","","",""), $str);
		}
		return $str;
	}
}
/*****************************************************************************/
if (! function_exists('wx_get_filename')) {
    function wx_get_filename($file_name = '') {
        if ($file_name) {
            $tmp_list = explode('.', $file_name);
            if (count($tmp_list) >= 2) {
                $end = array_pop($tmp_list);
                return implode('.', $tmp_list);
            }
            else {
                return $file_name;
            }
        }
        return '';
    }
}
/*****************************************************************************/
if (! function_exists('wx_get_suffix')) {
    function wx_get_suffix($file_name = '') {
        if ($file_name) {
            $tmp_list = explode('.', $file_name);
            if (count($tmp_list) >= 2) {
                return array_pop($tmp_list);
            }
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
