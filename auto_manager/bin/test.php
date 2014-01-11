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
/**************************** 获取笔记分类数据  *******************************/

// DB service obj
$db_service = new WX_DB();

// get grade 1
$data_table = 'wx_category_nature';
$data_select = array(
    'cnature_name',
    'cnature_grade',
    'cnature_flag',
    );
$data_where = array(
    'cnature_grade' => '1',
    );

$grade_one_data = $db_service->select($data_table, $data_select, $data_where);
$json_str = json_encode($grade_one_data);
// print_r($grade_one_data);
echo $json_str;


exit();

/*****************************************************************************/

/***************************** 设定时间戳 ************************************/
$today_time = wx_get_today_time();
$log_name = 'test_task';

// echo 'Filter Time: '.$today_time."\n";

/***************************** 查询数据库 ************************************/
wx_log("", $log_name);

/*****************************************************************************/
/* End of file test.php */
/* Location: ./bin/test.php */
