<?php
/*****************************************************************************/
/**
 * 公共的一些辅助函数
 */
/*****************************************************************************/

/*****************************************************************************/
if (! function_exists('wx_delete_file'))
{
    function wx_delete_file($file = '')  // shred the file
    {
        if (file_exists($file)) {
            $status = 1;
            $cmd = '/bin/sh /alidata/server/creamnote/bin/deletefile.sh '.$file.' >/dev/null 2>&1';
            $ret = system($cmd, $status);
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
