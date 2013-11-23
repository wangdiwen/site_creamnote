<?php

/**
 * Author: wangdiwen
 * Date  : 2013-05-26
 * Note  : This php script is a time task, for move the local upload data to AliOSS.
 *         It will exec only once everyday, exec time is 00:00 current day,
 *         When moving the current upload data, it will query the yesterday 00:00 ~ current day 00:00 that
 *         upload to local web server's data.
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
require_once WX_BASE_PATH.WX_SEPARATOR.'model'.WX_SEPARATOR.'wx_alioss_api.php';
// 加载数据库接口类模块
require_once WX_BASE_PATH.WX_SEPARATOR.'model'.WX_SEPARATOR.'wx_database_api.php';

/*****************************************************************************/
/**-----------------------  逻辑代码   --------------------------------------*/
/*****************************************************************************/

/*****************************************************************************/
/**
 * 说明：此脚本由同级目录的 auto_task.sh脚本调用，从当天的00:01开始执行，
 *       负责筛选出当天由用户上传的笔记文件，根据查询时间戳 昨天的00:01分~~当天的00:01分，
 *       这些文件都是存放在服务器本地的磁盘上面，需要把筛选出来的文件从本次磁盘备份到
 *       阿里的OSS云端永久存储，备份到OSS后，服务器本地磁盘的文件暂时还是保留，
 *       等待自动清理任务对它们进行清理文件工作。
 */
/*****************************************************************************/

/***************************** 设定时间戳 ************************************/
$log_name = 'data_move';
$today_time = wx_get_today_time();
$yesterday_time = wx_get_yesterday_time();

// $today_time = '2013-11-02 00:01:00';
// $yesterday_time = '2013-11-01 00:01:00';

// echo $today_time."\n";
// echo $yesterday_time."\n";
// echo 'Filter Time: '.$yesterday_time.' ~ '.$today_time."\n";

/***************************** 查询数据库 ************************************/
$table = 'wx_data';
$select = array(
    'data_id',
    'data_objectname',
    'data_type',
    'data_status',
    'data_uploadtime',
    'data_osspath',
    'data_vpspath',
    'data_preview'
    );
$where = array(
    'data_uploadtime >=' => $yesterday_time,
    'data_uploadtime <=' => $today_time,
    'data_status !=' => '0',
    );
$db_service = new WX_DB();
$pend_data_list = $db_service->select($table, $select, $where);
// print_r($pend_data_list);

/***************************** 对数据按照OSS存储目录分类 *********************/
$oss_pend_data = array();
$oss_category = array(
    'wx-flash' => array(),
    'wx-study-notes'=> array(),
    'wx-graduate-exam' => array(),
    'wx-final-exam' => array()
    );
foreach ($pend_data_list as $pend_data) {
    $vpspath = $pend_data['data_vpspath'];
    if (stripos($vpspath, 'study')) {
        array_push($oss_category['wx-study-notes'], $pend_data);
    }
    elseif (stripos($vpspath, 'graduate')) {
        array_push($oss_category['wx-graduate-exam'], $pend_data);
    }
    elseif (stripos($vpspath, 'final')) {
        array_push($oss_category['wx-final-exam'], $pend_data);
    }
}
// print_r($oss_category);

/***************************** 按照分类目录将文件备份到OSS *******************/
$alioss_service = new WX_ALIOSS();

$total_count = count($pend_data_list);
$actual_count = count($oss_category['wx-study-notes']) + count($oss_category['wx-graduate-exam']) + count($oss_category['wx-final-exam']);
wx_log("---------------------------------------------------------------------------------", $log_name);
wx_log("---------------------  Data Moving Task Everyday --------------------------------", $log_name);
wx_log('--------- Filter Time      : '.$yesterday_time.' ~ '.$today_time.'-----------', $log_name);
wx_log('--------- Total  Data Count: '.$total_count, $log_name);
wx_log('--------- Actual Data Count: '.$actual_count, $log_name);
wx_log("---------------------------------------------------------------------------------", $log_name);
wx_log("---------------------------------------------------------------------------------", $log_name);

foreach ($oss_category as $oss_bucket => $obj_list) {
    $data_id = '';
    $data_objectname = '';
    $data_type = '';
    $data_uploadtime = '';
    $data_osspath = '';
    $data_vpspath = '';
    $data_preview = '';
    if ($obj_list) {
        foreach ($obj_list as $obj) {
            $data_id = $obj['data_id'];
            $data_objectname = $obj['data_objectname'];
            $data_type = $obj['data_type'];
            $data_uploadtime = $obj['data_uploadtime'];
            $data_osspath = $obj['data_osspath'];
            $data_vpspath = $obj['data_vpspath'];
            $data_preview = $obj['data_preview'];
            $vps_data_file = $data_vpspath.$data_objectname;

            wx_log("Data Id        : ".$data_id, $log_name);
            wx_log("Data ObjectName: ".$data_objectname, $log_name);
            wx_log("Data VPS Path  : ".$vps_data_file, $log_name);
            wx_log("Data OSS Path  : ".'/'.$oss_bucket."/".$data_objectname, $log_name);

            if (file_exists($vps_data_file)) {
                // 文件备份到OSS
                $oss_obj_exist = $alioss_service->is_object_exist($oss_bucket, $data_objectname);
                if (! $oss_obj_exist) {
                    $ret_upload = $alioss_service->upload_by_file($oss_bucket, $data_objectname, $vps_data_file);
                    if ($ret_upload) {
                        $msg = "Info: Upload data to OSS success";
                        wx_log($msg, $log_name);
                        // 备份flash文件
                        if ($data_preview == '1') {
                            $flash_path = '/alidata/www/creamnote/upload/flash/';
                            $flash_name = wx_get_file_name($data_objectname);
                            $oss_flash_obj = $flash_name.'.swf';
                            $flash_file = $flash_path.$flash_name.'.swf';
                            if (file_exists($flash_file)) {
                                $oss_flash_obj_exist = $alioss_service->is_object_exist('wx-flash', $oss_flash_obj);
                                if (! $oss_flash_obj_exist) {
                                    $ret_flash_upload = $alioss_service->upload_by_file('wx-flash', $oss_flash_obj, $flash_file);
                                    if ($ret_flash_upload) {
                                        $msg = 'Info: Upload local flash file success';
                                        wx_log($msg, $log_name);
                                    }
                                    else {
                                        $msg = 'Error: Upload local flash file failed';
                                        wx_log($msg, $log_name);
                                    }
                                }
                                else {
                                    $msg = 'Warning: OSS Flash file is already exist';
                                    wx_log($msg, $log_name);
                                }
                            }
                            else {
                                $msg = 'Warning: Flash not exist, '.$flash_file;
                                wx_log($msg, $log_name);
                            }
                        }
                        // 更新数据库信息
                        $sql_table = 'wx_data';
                        $sql_update_data = array(
                            'data_osspath' => $oss_bucket
                            );
                        $sql_where = array(
                            'data_id' => $data_id
                            );
                        $ret_update = $db_service->update($sql_table, $sql_update_data, $sql_where);
                        if ($ret_update) {
                            $msg = "Info: Update database OSS-Path success";
                            wx_log($msg, $log_name);
                        }
                        else {
                            $msg = "Error: Update database OSS-Path success";
                            wx_log($msg, $log_name);
                        }
                    }
                    else {
                        $msg = "Error: Upload data to OSS failed";
                        wx_log($msg, $log_name);
                    }
                }
                else {
                    $msg = "Warning: OSS has already data file, ".$oss_bucket.'/'.$data_objectname;
                    wx_log($msg, $log_name);
                }
            }
            else {
                $msg = "Error: has no vps data file, ".$vps_data_file;
                wx_log($msg, $log_name);
            }
            wx_log("---------------------------------------------------------------------------------", $log_name);
        }
    }
}

wx_log("", $log_name);

/*****************************************************************************/



/*****************************************************************************/
/* End of file data_move_everyday.php */
/* Location: ./bin/data_move_everyday.php */
