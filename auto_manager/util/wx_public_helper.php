<?php
/*****************************************************************************/
/**
 * 公共的一些辅助函数
 */
/*****************************************************************************/
if (! function_exists('wx_tips')) {
    function wx_tips($msg = '', $new_line = true) {
        if ($msg) {
            if ($new_line) {
                echo "\033[01;32m".$msg."\033[0m\n";  // green color, 34m is blue
            }
            else {
                echo "\033[01;32m".$msg."\033[0m";  // green color, 34m is blue
            }
        }
    }
}
/*****************************************************************************/
if (! function_exists('wx_warnning')) {
    function wx_warnning($msg = '', $new_line = true) {
        if ($msg) {
            if ($new_line) {
                echo "\033[01;33m".$msg."\033[0m\n";  // yellow color
            }
            else {
                echo "\033[01;33m".$msg."\033[0m";  // yellow color
            }
        }
    }
}
/*****************************************************************************/
if (! function_exists('wx_error')) {
    function wx_error($msg = '', $new_line = true) {
        if ($msg) {
            if ($new_line) {
                echo "\033[01;31m".$msg."\033[0m\n";  // red color
            }
            else {
                echo "\033[01;31m".$msg."\033[0m";  // red color
            }
        }
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
            // echo 'return = '.$ret."\n";
            if ($status == 0) {
                return true;
            }
        }
        return false;
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
if (! function_exists('wx_out'))
{
    function wx_out($msg = '')
    {
        echo $msg."\n";
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
if (! function_exists('wx_get_file_name'))
{
    function wx_get_file_name($file_name = '')
    {
        $name = '';
        if ($file_name) {
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
        }

        return $name;
    }
}
/*****************************************************************************/








?>
