<?php

/**
 * Author: wangdiwen
 * Date  : 2013-06-03
 * Note  : This php script is a time task, for notify the user to complete
 *         their upload data info that storage in ./upload/tmp/ of vps,
 *         We save the tmp data file 48h, if out of timestamp, we will clear
 *         the tmp data file, and previous of time, we will notify the data
 *         own user to complete their data info, and the time is 12h of it's
 *         clear deadline.
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
 * 说明：此脚本由同级目录的 auto_task.sh脚本调用，从当天的凌晨05:00开始执行，
 *       负责检查用户在当天使用图片笔记功能，但是没有使用提交，或者是用户使用图片笔记
 *       功能，但是由于系统出错，导致服务器上的存放图片的目录下面，残留一些无用的
 *       用户id目录，以及目录下面的图片文件。
 *       在清除图片目录下面的以用户id为目录名称的目录和文件时，同时检测数据库中是否
 *       有对应用户id的记录，如果有，则清除数据库记录。
 */
/*****************************************************************************/
/***************************** 设定时间戳 ************************************/
$today_time = wx_get_today_time();
$log_name = 'clear_image';
/***************************** 查询数据库 ************************************/
$table = 'wx_image';
$select = array(
    'user_id',
    );
$where = array();
$db_service = new WX_DB();
$pend_data_list = $db_service->select($table, $select, $where);
// print_r($pend_data_list);

/***************************** 逻辑处理 *************************************/
$total_count = count($pend_data_list);
$image_path = '/alidata/www/creamnote/upload/image/';

wx_log("---------------------------------------------------------------------------------", $log_name);
wx_log("---------------------  Clear Timeout Images Everyday ----------------------------", $log_name);
wx_log('--------- Filter Time      : '.$today_time.'  -------------------------------', $log_name);
wx_log('--------- Total  Data Count: '.$total_count, $log_name);
wx_log("---------------------------------------------------------------------------------", $log_name);
wx_log("---------------------------------------------------------------------------------", $log_name);

foreach ($pend_data_list as $pend_data) {
    $user_id = $pend_data['user_id'];
    $clear_path = $image_path.$user_id;

    wx_log("User Id    : ".$user_id, $log_name);
    wx_log("Clear Path : ".$clear_path, $log_name);

    if ($clear_path && is_dir($clear_path)) {
        $ret = wx_delete_dir($clear_path);
        if ($ret) {
            wx_log('Info: Clear User Image Path Success', $log_name);
        }
        else {
            wx_log('Error: Clear User Image Path Failed', $log_name);
        }

        // clear database data, table is 'wx_image'
        $del_where = array(
            'user_id' => $user_id,
            );
        $del_table = 'wx_image';
        $ret_del = $db_service->delete($del_table, $del_where);
        if ($ret_del) {
            wx_log('Info: Delete Database Record Success', $log_name);
        }
        else {
            wx_log('Error: Delete Database Record Failed', $log_name);
        }
    }

    wx_log("---------------------------------------------------------------------------------", $log_name);
}

wx_log("", $log_name);
