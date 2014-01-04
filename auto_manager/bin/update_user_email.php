<?php

/**
 * Author: wangdiwen
 * Date  : 2013-12-07
 * Note  : This php script is a time task, for checking the auto task logs.
 *         And last, it will give a statistic report.
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
// load local email class
require_once WX_BASE_PATH.WX_SEPARATOR.'model'.WX_SEPARATOR.'wx_local_email_api.php';
// 加载阿里云存储OSS的API类接口模块
// require_once WX_BASE_PATH.WX_SEPARATOR.'model'.WX_SEPARATOR.'wx_alioss_api.php';
// 加载数据库接口类模块
// require_once WX_BASE_PATH.WX_SEPARATOR.'model'.WX_SEPARATOR.'wx_database_api.php';

/*****************************************************************************/
/**-----------------------  逻辑代码   --------------------------------------*/
/*****************************************************************************/

/*****************************************************************************/
/**
 * 说明：此脚本由同级目录的 check_report_autolog.sh 脚本调用，由 "diwen" 用户执行。
 */
/*****************************************************************************/

/*****************************************************************************/
/**************************** 检测自动化日志，并生成统计结果报告 ****************/

// show exec time
$today_time = wx_get_today_time();
wx_tips('===============  Update All User Email ==============================');
wx_tips('===============  Executive Time : '.$today_time." ===============\n");

// $Mail_Server = new WX_Local_Email();


wx_tips('=====================================================================');
exit();

/*****************************************************************************/
/* End of file update_user_email.php */
/* Location: ./bin/update_user_email.php */
