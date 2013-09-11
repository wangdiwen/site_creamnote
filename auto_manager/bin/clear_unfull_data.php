<?php

/**
 * Author: wangdiwen
 * Date  : 2013-06-03
 * Note  : This php script is a time task, for clear some data file which user
 *         not complete it's info, just like upload a file and can't write
 *         the note file's info, also like convert to a new pdf file from
 *         some note photor, we will clear these data,
 *         1. delete the vps file, path is upload/tmp/
 *         2. delete the data record from database
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
 *       负责清理未完善资料信息的数据，包括：
 *       1）存放在服务器磁盘upload/tmp/ 目录下面的资料文件；
 *       2）删除数据库中对应data_id和与之关联的所有数据信息；
 *       注意：清理的策略是，每天执行此清理脚本，但是从数据库中筛选的数据，是
 *             从“前天~昨天”发现的未完善的资料数据，因为通知完善资料的自动化
 *             任务是每天即12个小时发送的，那么清理的周期我们就定为48个小时，
 *             也就是每2天清理一次为完善的资料数据。
 */
/*****************************************************************************/

/***************************** 设定时间戳 ************************************/
$today_time = wx_get_today_time();
$yesterday_time = wx_get_yesterday_time();
$before_yesterday_time = wx_get_before_yesterday_time();
// $before_yesterday_time = '2013-05-26 00:01:00';

// echo $today_time."\n";
// echo $yesterday_time."\n";
// echo $before_yesterday_time."\n";
echo 'Filter Time: '.$before_yesterday_time.' ~ '.$yesterday_time."\n";

/***************************** 查询数据库 ************************************/
/**
 * 筛选说明：
 *      数据库中wx_data数据表中的data_status字段，为‘0’的话，就表明这条数据是
 *      未完善的资料信息。
 * data相关的数据表：
 *      1. wx_data
 *      2. wx_data2carea
 *      3. wx_data2cnature
 *      4. wx_data_activity
 *      5. wx_grade
 */
$table = 'wx_data';
$select = array(
    'data_id',
    'data_objectname',
    'data_type',
    // 'data_status',
    'user_id',
    // 'data_uploadtime',
    // 'data_osspath',
    'data_vpspath'
    );
$where = array(
    'data_uploadtime >=' => $before_yesterday_time,
    'data_uploadtime <=' => $yesterday_time,
    'data_status' => '0'
    );
$db_service = new WX_DB();
$pend_data_list = $db_service->select($table, $select, $where);
// print_r($pend_data_list);

/***************************** 执行清理工作 **********************************/
$total_count = count($pend_data_list);

wx_log("---------------------------------------------------------------------------------");
wx_log("---------------------  Clear Unfull Data Every Two Days -----------------------");
wx_log('--------- Filter Time      : '.$before_yesterday_time.' ~ '.$yesterday_time.'-----------');
wx_log('--------- Total  Data Count: '.$total_count);
wx_log("---------------------------------------------------------------------------------");
wx_log("---------------------------------------------------------------------------------");

foreach ($pend_data_list as $pend_data) {
    $data_id = $pend_data['data_id'];
    $data_objectname = $pend_data['data_objectname'];
    $data_type = $pend_data['data_type'];
    // $user_id = $pend_data['user_id'];
    $data_vpspath = $pend_data['data_vpspath'];

    $file_name = $data_vpspath.$data_objectname;

    wx_log("Data Id       : ".$data_id);
    wx_log("Data Obj Name : ".$data_objectname);
    wx_log("Data Vps Path : ".$data_vpspath);

    // first, delete the vps file
    $ret_del_file = wx_delete_file($file_name);
    if ($ret_del_file) {
        $msg = 'Info: Delete Tmp Data File Success';
        wx_log($msg);
    }
    else {
        $msg = 'Error: Delete Tmp Data File Failed';
        wx_log($msg);
    }

    // second, delete the data info of table:
    // table: 'wx_data'/'wx_data2carea'/'wx_data2cnature'/'wx_data_activity'/'wx_grade'/'wx_notify'

    $table_data = 'wx_data';
    $table_area = 'wx_data2carea';
    $table_nature = 'wx_data2cnature';
    $table_activity = 'wx_data_activity';
    $table_grade = 'wx_grade';
    $table_notify = 'wx_notify';

    $del_where = array(
        'data_id' => $data_id
        );

    // table: 'wx_data'
    $ret_del_db = $db_service->delete($table_data, $del_where);
    if ($ret_del_db) {
        $msg = 'Info: Delete Database(wx_data) Record Success';
        wx_log($msg);
    }
    else {
        $msg = 'Error: Delete Database(wx_data) Record Failed';
        wx_log($msg);
    }

    // table: 'wx_data2carea'
    $ret_del_db = $db_service->delete($table_area, $del_where);
    if ($ret_del_db) {
        $msg = 'Info: Delete Database(wx_data2carea) Record Success';
        wx_log($msg);
    }
    else {
        $msg = 'Error: Delete Database(wx_data2carea) Record Failed';
        wx_log($msg);
    }

    // table: 'wx_data2cnature'
    $ret_del_db = $db_service->delete($table_nature, $del_where);
    if ($ret_del_db) {
        $msg = 'Info: Delete Database(wx_data2cnature) Record Success';
        wx_log($msg);
    }
    else {
        $msg = 'Error: Delete Database(wx_data2cnature) Record Failed';
        wx_log($msg);
    }

    // table: 'wx_data_activity'
    $ret_del_db = $db_service->delete($table_activity, $del_where);
    if ($ret_del_db) {
        $msg = 'Info: Delete Database(wx_data_activity) Record Success';
        wx_log($msg);
    }
    else {
        $msg = 'Error: Delete Database(wx_data_activity) Record Failed';
        wx_log($msg);
    }

    // table: 'wx_grade'
    $ret_del_db = $db_service->delete($table_grade, $del_where);
    if ($ret_del_db) {
        $msg = 'Info: Delete Database(wx_grade) Record Success';
        wx_log($msg);
    }
    else {
        $msg = 'Error: Delete Database(wx_grade) Record Failed';
        wx_log($msg);
    }

    // table: 'wx_notify'
    $notify_where = array(
        'notify_params' => $data_id
        );
    $ret_del_db = $db_service->delete($table_notify, $notify_where);
    if ($ret_del_db) {
        $msg = 'Info: Delete Database(wx_notify) Record Success';
        wx_log($msg);
    }
    else {
        $msg = 'Error: Delete Database(wx_notify) Record Failed';
        wx_log($msg);
    }


    wx_log("---------------------------------------------------------------------------------");
}

wx_log("");

/*****************************************************************************/
/* End of file clear_unfull_data.php */
/* Location: ./bin/clear_unfull_data.php */
