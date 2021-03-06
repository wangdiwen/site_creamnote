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
 * 说明：此脚本由同级目录的 auto_task.sh脚本调用，从当天的凌晨03:00开始执行，
 *       负责清理存放在本地服务器磁盘上面的资料数据文件，并且这些文件超过了
 *       它的生命期时间点。
 */
/*****************************************************************************/

/***************************** 设定时间戳 ************************************/
// $today_time = wx_get_today_time();
// $log_name = 'clear_vps_data';

// echo 'Filter Time: '.$today_time."\n";

/***************************** 查询数据库 ************************************/
/**
 * 筛选说明：
 * data相关的数据表：
 *      1. wx_data
 *      2. wx_data2carea
 *      3. wx_data2cnature
 *      4. wx_data_activity
 * 关于资料的生命期时间点：
 *      wx_data_activity数据表中的“dactivity_lifetime”字段，就是生命期时间点，
 *      有2中情况会用到：
 *      1. 用户上传一份资料的时候，dactivity_lifetime初始化为上传的时间；
 *      2. 当本地没有该数据的文件时候，需要从OSS上面拷贝一份到VPS，那么
 *          此时dactivity_lifetime强制更新为OSS拷贝的时间戳；
 *      3. 当执行本次清理工作的时候，我们会根据当前的时间戳，往后推迟48小时，
 *          即2天时间，把推迟后的时间点和dactivity_lifetime生命期时间点相
 *          比较，如果发现dactivity_lifetime生命期时间点比推迟后的时间点还早，
 *          那说明，该份存放在VPS磁盘上面的文件已经超过了它的生命期，应该把它
 *          从VPS服务器磁盘上面删除，节省服务器磁盘空间，并且将数据库中的
 *          wx_data_activity->dactivity_lifetime字段清空为“0000-00-00 00:00:00",
 *          并且将wx_data->data_vpspath字段清空，说明此时该份资料文件已经从
 *          VPS服务器磁盘上面删除了，如果想使用的话，必须依靠其他用户请求下载该
 *          资料文件，在第一次下载的时候，我们会从OSS上面拷贝一份到服务器磁盘，
 *          记录dactivity_lifetime生命期时间点和wx_data->data_vpspath字段。
 */

// set 'wx_data' table variable
$data_table = 'wx_data';
$data_select = array(
    'data_id',
    'data_objectname',
    'data_vpspath',
    );
$data_where = array(
    'data_osspath !=' => '',
    'data_vpspath !=' => '',
    'data_status !=' => '0',
    );
$data_limit = 100;

// get pending base data
$db_service = new WX_DB();
$pend_data_list = $db_service->select($data_table, $data_select, $data_where, $data_limit);
// print_r($pend_data_list);

// set data activity table variable
$dactivity_data_id_list = array();
// get data id list
foreach ($pend_data_list as $key => $value) {
    $dactivity_data_id_list[] = $value['data_id'];
}

// get data activity info
$data_activity_map = array();
if ($dactivity_data_id_list) {
    $dactivity_table = 'wx_data_activity';
    $dactivity_select = array(
        'data_id',
        'dactivity_lifetime',
        );
    $dactivity_lifetime_info = $db_service->select_where_in($dactivity_table, $dactivity_select, 'data_id', $dactivity_data_id_list);
    if ($dactivity_lifetime_info) {
        foreach ($dactivity_lifetime_info as $key => $value) {
            $data_activity_map[$value['data_id']] = $value['dactivity_lifetime'];
        }
    }
}
// print_r($data_activity_map);

// merge the 'dactivity_lifetime' field to pending data info
if ($pend_data_list) {
    foreach ($pend_data_list as $key => $value) {
        $tmp_data_id = $value['data_id'];
        $pend_data_list[$key]['dactivity_lifetime'] = isset($data_activity_map[$tmp_data_id]) ? $data_activity_map[$tmp_data_id] : '0000-00-00 00:00:00';
    }
}
// print_r($pend_data_list);

// realse some resource
unset($data_select);
unset($data_where);
unset($dactivity_data_id_list);
unset($data_activity_map);

// return false;

/***************************** 设定时间戳 ************************************/
$today_time = wx_get_today_time();
$log_name = 'clear_vps_data';

/***************************** 执行清理工作 **********************************/
$total_count = count($pend_data_list);
$standard_life_time = wx_get_before_yesterday_time();  // 推迟2天后（48小时）的时间点

wx_log("---------------------------------------------------------------------------------", $log_name);
wx_log("---------------------  Clear VPS Data Every Two Days ----------------------------", $log_name);
wx_log('--------- Filter Time      : '.$today_time.'  -------------------------------', $log_name);
wx_log('--------- Standard Lifetime: '.$standard_life_time.'  -------------------------------', $log_name);
wx_log('--------- Total  Data Count: '.$total_count.'  -------------------------------------------------', $log_name);
wx_log("---------------------------------------------------------------------------------", $log_name);
wx_log("---------------------------------------------------------------------------------", $log_name);


foreach ($pend_data_list as $pend_key => $pend_data) {
    $data_id = $pend_data['data_id'];
    $data_objectname = $pend_data['data_objectname'];
    $data_vpspath = $pend_data['data_vpspath'];
    $dactivity_lifetime = $pend_data['dactivity_lifetime'];

    $file_name = $data_vpspath.$data_objectname;
    $flash_file_name = wx_get_file_name($data_objectname);
    $flash_file = WX_UPLOAD_FLASH_PATH.WX_SEPARATOR.$flash_file_name.'.swf';

    wx_log("Data Id       : ".$data_id, $log_name);
    wx_log("Data Obj Name : ".$data_objectname, $log_name);
    wx_log("Data Vps Path : ".$data_vpspath, $log_name);


    /**
     * 比较生命期时间点和推迟2天(48h)的时间点，如果发现生命期时间点比推迟48h时间点还早，
     * 那么删除VPS服务器磁盘文件，并且更新数据库信息
     * 2张表及字段：wx_data->data_vpspath ; wx_data_activity->dactivity_lifetime
     */

    if ($dactivity_lifetime <= $standard_life_time) {
        // clear working
        wx_log('Info: This Data Is Out Of Lifetime, Clear It', $log_name);

        // first, delete the vps file,
        // and if it has flash file on vps disk, path like: 'creamnote/upload/flash/'
        // we also delete the flash file
        if (! file_exists($file_name)) {
            wx_log('Warning: VPS File Not Existed', $log_name);

            // delete the flash file if has
            $ret_del_file = wx_delete_file($flash_file);
            usleep(500000);  // 0.5s
            if ($ret_del_file) {
                wx_log('Info: Delete VPS Flash File Success', $log_name);
            }
            else {
                wx_log('Warning: It Has No Flash File', $log_name);
            }

            // clear 'wx_data' table -> vpspath field
            $update_data = array(
                    'data_vpspath' => '',
                );
            $update_data_where = array(
                    'data_id' => $data_id,
                    );
            $ret_update_data = $db_service->update('wx_data', $update_data, $update_data_where);
            usleep(500000);  // 0.5s
            if ($ret_update_data) {
                wx_log('Info: Clear Vps Path(wx_data) Record Success', $log_name);
            }
            else {
                wx_log('Error: Clear Vps Path(wx_data) Record Failed', $log_name);
            }

            wx_log("---------------------------------------------------------------------------------", $log_name);
            continue;
        }

        $ret_del_file = wx_delete_file($file_name);
        usleep(500000);  // 0.5s
        if ($ret_del_file) {
            wx_log('Info: Delete VPS Data File Success', $log_name);

            // delete the flash file if has
            $ret_del_flash = wx_delete_file($flash_file);
            usleep(500000);  // 0.5s
            if ($ret_del_flash) {
                wx_log('Info: Delete VPS Flash File Success', $log_name);
            }
            else {
                wx_log('Warning: It Has No Flash File', $log_name);
            }

            // second, update the data info of table
            // table: 'wx_data' and 'wx_data_activity'
            $table_data = 'wx_data';
            $table_activity = 'wx_data_activity';

            $update_data_table = array(
                'data_vpspath' => ''
                );
            $update_where = array(
                'data_id' => $data_id
                );
            $update_data_activity = array(
                'dactivity_lifetime' =>'0000-00-00 00:00:00'
                );

            // table: 'wx_data'
            $ret_update = $db_service->update($table_data, $update_data_table, $update_where);
            usleep(500000);  // 0.5s
            if ($ret_update) {
                wx_log('Info: Update Database(wx_data) Record Success', $log_name);

                // table: 'wx_data_activity'
                $ret_update_act = $db_service->update($table_activity, $update_data_activity, $update_where);
                usleep(500000);  // 0.5s
                if ($ret_update_act) {
                    wx_log('Info: Update Database(wx_data_activity) Record Success', $log_name);
                }
                else {
                    wx_log('Error: Update Database(wx_data_activity) Record Failed', $log_name);
                }
            }
            else {
                wx_log('Error: Update Database(wx_data) Record Failed', $log_name);
            }
        }
        else {
            wx_log('Error: Delete VPS Data File Failed', $log_name);
        }
    }
    else {
        wx_log('Warining: This Data Is Health, Donnot Need Clear', $log_name);
    }

    wx_log("---------------------------------------------------------------------------------", $log_name);
}

wx_log("", $log_name);

/*****************************************************************************/
/* End of file clear_vps_data.php */
/* Location: ./bin/clear_vps_data.php */
