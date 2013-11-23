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
// 设置PHP脚本超时，不限时
ini_set('max_execution_time', 0);

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
 * 说明：此脚本由同级目录的 auto_task.sh脚本调用，从当天的凌晨02:00开始执行，
 *       负责通知用户去完善他们拥有的一些没有完善信息的资料数据，因为没有
 *       完善信息的笔记文件数据，网站平台在展示和搜索的时候，都是找不到的，
 *       也就是说，这些存储在云服务器磁盘上面的数据是无用的，而且占用存储空间，
 *       所以我们要在执行全局自动化清理工作提前12小时及时的通知用户，让用户
 *       去完善他们拥有的一些没有完善资料信息的笔记数据。
 */
/*****************************************************************************/

/***************************** 设定时间戳 ************************************/
$log_name = 'data_notify';
$today_time = wx_get_today_time();
$yesterday_time = wx_get_yesterday_time();
// $today_time = '2013-06-04 00:01:00';
// $yesterday_time = '2013-05-19 00:01:00';

// echo 'Filter Time: '.$yesterday_time.' ~ '.$today_time."\n";

/***************************** 查询数据库 ************************************/
$table = 'wx_data';
$select = array(
    'data_id',
    'data_name',
    'data_objectname',
    'data_type',
    'data_status',
    'user_id',
    'data_uploadtime'
    );
$where = array(
    'data_uploadtime >=' => $yesterday_time,
    'data_uploadtime <=' => $today_time,
    'data_status' => '0'
    );
$db_service = new WX_DB();
$pend_data_list = $db_service->select($table, $select, $where);
// print_r($pend_data_list);

/***************************** 发送用户通知 **********************************/
/* --------------------------- 每条未完善的数据，发送一条通知完善系统通知 -- */
// $tomorrow_time = wx_get_tomorrow_time();
$after_tomorrow_time = wx_get_after_tomorrow_time();
$current_time = date('Y-m-d H:i:s');
$total_count = count($pend_data_list);

wx_log("---------------------------------------------------------------------------------", $log_name);
wx_log("---------------------  Data Notify Complete Info Everyday -----------------------", $log_name);
wx_log('--------- Filter Time      : '.$yesterday_time.' ~ '.$today_time.'-----------', $log_name);
wx_log('--------- Total  Data Count: '.$total_count, $log_name);
wx_log("---------------------------------------------------------------------------------", $log_name);
wx_log("---------------------------------------------------------------------------------", $log_name);

foreach ($pend_data_list as $pend_data) {
    $data_id = $pend_data['data_id'];
    $data_name = $pend_data['data_name'];
    $data_objectname = $pend_data['data_objectname'];
    $data_type = $pend_data['data_type'];
    $user_id = $pend_data['user_id'];

    $data_full_name = $data_name.'.'.$data_type;
    $notify_title = '系统通知：【'.$data_full_name.'】笔记资料';
    $notify_content = '亲爱的用户，您还没有为【'.$data_full_name.'】笔记完善信息，其他人无法查阅和使用，系统将会在'.$after_tomorrow_time.'自动清除资料数据！';

    wx_log("Data Id       : ".$data_id, $log_name);
    wx_log("Data Obj Name : ".$data_objectname, $log_name);
    // wx_log("Data Full Name: ".$data_full_name, $log_name);

    // 将通知内容写入数据库
    $insert_data = array(
        'notify_type' => '4',
        'notify_title' => $notify_title,
        'notify_content' => $notify_content,
        'user_id' => $user_id,
        'notify_params' => $data_id,
        'notify_time' => $current_time
        );
    $insert_table = 'wx_notify';
    $ret_insert = $db_service->insert($insert_table, $insert_data);
    if ($ret_insert) {
        $msg = 'Info: Send Notify Success';
        wx_log($msg, $log_name);
    }
    else {
        $msg = 'Error: Send Notify Failed';
        wx_log($msg, $log_name);
    }

    wx_log("---------------------------------------------------------------------------------", $log_name);
}

wx_log("", $log_name);


/*****************************************************************************/
/* End of file notify_data_info.php */
/* Location: ./bin/notify_data_info.php */
