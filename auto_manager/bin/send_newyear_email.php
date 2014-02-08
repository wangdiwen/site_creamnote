<?php

/**
 * Author: wangdiwen
 * Date  : 2013-06-03
 * Note  : This php script is a time task, for clear some data file which
 *         storage in VPS server disk.
 */

/*****************************************************************************/
/**-----------------------  加载资源   --------------------------------------*/
/*****************************************************************************/
/**
 * 全局变量
 */
define('WX_BASE_PATH', '/alidata/www/creamnote/auto_manager');
define('WX_SEPARATOR', '/');

// 加载基础全局配置文件：auto_manager/conf/base_config.php
require_once WX_BASE_PATH.WX_SEPARATOR.'conf'.WX_SEPARATOR.'base_config.php';
// 加载辅助函数资源
require_once WX_BASE_PATH.WX_SEPARATOR.'util'.WX_SEPARATOR.'wx_public_helper.php';
// 加载日志记录模块
require_once WX_BASE_PATH.WX_SEPARATOR.'model'.WX_SEPARATOR.'wx_log_api.php';
// 加载阿里云存储OSS的API类接口模块
// require_once WX_BASE_PATH.WX_SEPARATOR.'model'.WX_SEPARATOR.'wx_alioss_api.php';
// load local email class
require_once WX_BASE_PATH.WX_SEPARATOR.'model'.WX_SEPARATOR.'wx_local_email_api.php';
// 加载数据库接口类模块
require_once WX_BASE_PATH.WX_SEPARATOR.'model'.WX_SEPARATOR.'wx_database_api.php';

/*****************************************************************************/
/**-----------------------  逻辑代码   --------------------------------------*/
/*****************************************************************************/

/*****************************************************************************/
/**
 * 说明：此脚本由同级目录的 auto_task.sh脚本调用，从当天的凌晨03:00开始执行，
 *       负责清理存放在本地服务器磁盘上面的资料数据文件，并且这些文件超过了
 *       它的生命期时间点。
 */
/*****************************************************************************/

/*****************************************************************************/
/**************************** 按照模板发送新年邮件  *******************************/

function read_email($start = 1, $end = 10) {
    $lines = array();
    $email_file = '/alidata/www/creamnote/auto_manager/bin/Creamnote-users.txt';
    $filter_lines = array();

    $file = fopen($email_file, 'r');
    while (! feof($file)) {
        $line = fgets($file);
        // echo $line;
        $tmp_list = explode('#', $line);
        // print_r($tmp_list);
        if (count($tmp_list) == 3) {
            $cur_num = (int)$tmp_list[0] + 1;
            if ($cur_num >= $start && $cur_num <= $end) {
                array_push($lines, $tmp_list);
            }
            if ($cur_num > $end) {
                break;
            }
        }
    }
    fclose($file);

    return $lines;
}

$pend_data_list = read_email(100, 110);  // aleady send
// print_r($lines);
// exit();

$Mail_Server = new WX_Local_Email();
$Mail_Server->set_send_account('steven_wang@creamnote.com', '208080236', 'Creamnote.com 醍醐笔记网');

$subject = '醍醐新年祝福';
$content_greet1 = '<html><head></head>尊敬的 ';
$content_greet2 = ' 用户，你好：<p />';
$content = '<p>感谢您一直以来对 Creamnote.com 的支持！</p>
<p>作为一个面向高校大学生群体的笔记资料分享、交易网站，我们始终以更加高效、方便、快捷的方式，为同学提供友好的笔记资料搜索、分享服务。</p>
<p>
1、让在考试、考研、科创、专业方面积累的“学霸”笔记能够分享给需要帮助的同学；<br />
2、让宝贵的知识资源得到复用，下届的学弟学妹也会受益于你的分享；<br />
3、让大学学习成为一件轻松、快乐的事情，不再枯燥、无味；<br /><br />
Helping Students Help Each Other，The Study Help You Need When You Need It.<br /><br />
</p>
<b>新春佳节来临之际，醍醐笔记网团队在这里祝您：马上有钱，马上健康，马上幸福，马到成功！</b><br /><br />

祝好，<br /><br />
王地文<br />
醍醐联合创始人<br />
steven_wang@creamnote.com
';

if ($pend_data_list) {
    foreach ($pend_data_list as $key => $value) {
        # code...
        $cur_obj = $pend_data_list[$key];
        $user_id = $cur_obj[0];
        $user_name = $cur_obj[1];
        $user_email = $cur_obj[2];

        $email_content = $content_greet1.$user_name.$content_greet2.$content;
        $ret_send = $Mail_Server->send($user_email, $subject, $email_content);
        echo '发送给  id='.$user_id.' '.$user_name;
        if ($ret_send) {
            echo "\t【成功】"."\n";
            wx_log('send new yeal success: id= '.$user_id, 'success_user');
        }
        else {
            echo "\t【失败】"."\n";
            wx_log('send new yeal failed: ', 'failed_user');
        }

        sleep(2);  # cache time
    }
}


exit();

/*****************************************************************************/
/*****************************************************************************/
/* End of file test.php */
/* Location: ./bin/test.php */
