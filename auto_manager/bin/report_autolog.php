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
wx_tips('===============  Executive Time : '.$today_time." ===============\n");

// auto log vars
$auto_log_path = '/alidata/www/creamnote/auto_manager/log';
$clear_image_pre_name = 'auto_clear_image_';
$clear_unfull_pre_name = 'auto_clear_unfull_data_';
$clear_vps_pre_name = 'auto_clear_vps_data_';
$data_move_pre_name = 'auto_data_move_';
$data_notify_pre_name = 'auto_data_notify_';

// set today log file name
$today = substr($today_time, 0, 10);
$clear_image_log = $auto_log_path.'/'.$clear_image_pre_name.$today.'.log';
$clear_unfull_log = $auto_log_path.'/'.$clear_unfull_pre_name.$today.'.log';
$clear_vps_log = $auto_log_path.'/'.$clear_vps_pre_name.$today.'.log';
$data_move_log = $auto_log_path.'/'.$data_move_pre_name.$today.'.log';
$data_notify_log = $auto_log_path.'/'.$data_notify_pre_name.$today.'.log';

// global statistic data structure
$statistic_data = array(
    'clear-image' => array(
        'total' => 0,
        'success' => 0,
        'failed' => 0,
        ),
    'clear-unfull' => array(
        'total' => 0,
        'success' => 0,
        'failed' => 0,
        ),
    'clear-vps' => array(
        'total' => 0,
        'success' => 0,
        'failed' => 0,
        ),
    'data-move' => array(
        'total' => 0,
        'success' => 0,
        'failed' => 0,
        ),
    'data-notify' => array(
        'total' => 0,
        'success' => 0,
        'failed' => 0,
        ),
    );

// 1, checking clear image tmp log
if (file_exists($clear_image_log)) {
    $total_count = 0;
    $success_count = 0;
    $failed_count = 0;

    $file = fopen($clear_image_log, 'r') or exit('Unable to open clear image log file ! Exit ...');
    while (! feof($file)) {
        $line = fgets($file);
        // echo $line;
        if (ereg('.*Total  Data Count.*', $line)) {  // filter actual data count
            $tmp_list = explode(':', $line);
            // print_r($tmp_list);
            if (! isset($tmp_list[4])) {
                wx_error('checking clear image auto log, cannot filter total count info !');
                exit('cannot filter total count info');
            }
            else {
                $total_count = trim($tmp_list[4]);
            }
        }
        elseif (ereg('.*Data Id.*', $line)) {
            $success_count++;
        }
    }
    fclose($file);

    // compute failed count
    if ((int)$total_count != 0) {
        $failed_count = (int)$total_count - $success_count;
    }
    // statistic data
    $statistic_data['clear-image']['total'] = $total_count;
    $statistic_data['clear-image']['success'] = $success_count;
    $statistic_data['clear-image']['failed'] = $failed_count;
}

// 2, checking clear unfull data log
if (file_exists($clear_unfull_log)) {
    $total_count = 0;
    $success_count = 0;
    $failed_count = 0;

    $file = fopen($clear_unfull_log, 'r') or exit('Unable to open clear unfull log file ! Exit ...');
    while (! feof($file)) {
        $line = fgets($file);
        // echo $line;
        if (ereg('.*Total  Data Count.*', $line)) {  // filter actual data count
            $tmp_list = explode(':', $line);
            // print_r($tmp_list);
            if (! isset($tmp_list[4])) {
                wx_error('checking clear unfull auto log, cannot filter total count info !');
                exit('cannot filter total count info');
            }
            else {
                $total_count = trim($tmp_list[4]);
            }
        }
        elseif (ereg('.*Data Id.*', $line)) {
            $success_count++;
        }
    }
    fclose($file);

    // compute failed count
    if ((int)$total_count != 0) {
        $failed_count = (int)$total_count - $success_count;
    }
    // statistic data
    $statistic_data['clear-unfull']['total'] = $total_count;
    $statistic_data['clear-unfull']['success'] = $success_count;
    $statistic_data['clear-unfull']['failed'] = $failed_count;
}

// 3, checking clear vps data log
if (file_exists($clear_vps_log)) {
    $total_count = 0;
    $success_count = 0;
    $failed_count = 0;

    $file = fopen($clear_vps_log, 'r') or exit('Unable to open clear vps log file ! Exit ...');
    while (! feof($file)) {
        $line = fgets($file);
        // echo $line;
        if (ereg('.*Total  Data Count.*', $line)) {  // filter actual data count
            $tmp_list = explode(':', $line);
            // print_r($tmp_list);
            if (! isset($tmp_list[4])) {
                wx_error('checking clear vps auto log, cannot filter total count info !');
                exit('cannot filter total count info');
            }
            else {
                $num_str = trim($tmp_list[4]);
                $str_list = explode(' ', $num_str);
                $total_count = $str_list[0];
            }
        }
        elseif (ereg('.*Data Id.*', $line)) {
            $success_count++;
        }
    }
    fclose($file);

    // compute failed count
    if ((int)$total_count != 0) {
        $failed_count = (int)$total_count - $success_count;
    }
    // statistic data
    $statistic_data['clear-vps']['total'] = $total_count;
    $statistic_data['clear-vps']['success'] = $success_count;
    $statistic_data['clear-vps']['failed'] = $failed_count;
}

// 4, checking move data log
if (file_exists($data_move_log)) {
    $total_count = 0;
    $success_count = 0;
    $failed_count = 0;

    $file = fopen($data_move_log, 'r') or exit('Unable to open data move log file ! Exit ...');
    while (! feof($file)) {
        $line = fgets($file);
        // echo $line;
        if (ereg('.*Total  Data Count.*', $line)) {  // filter actual data count
            $tmp_list = explode(':', $line);
            // print_r($tmp_list);
            if (! isset($tmp_list[4])) {
                wx_error('checking data move auto log, cannot filter total count info !');
                exit('cannot filter total count info');
            }
            else {
                $total_count = trim($tmp_list[4]);
            }
        }
        elseif (ereg('.*Data Id.*', $line)) {
            $success_count++;
        }
    }
    fclose($file);

    // compute failed count
    if ((int)$total_count != 0) {
        $failed_count = (int)$total_count - $success_count;
    }
    // statistic data
    $statistic_data['data-move']['total'] = $total_count;
    $statistic_data['data-move']['success'] = $success_count;
    $statistic_data['data-move']['failed'] = $failed_count;
}

// 5, checking notify data log
if (file_exists($data_notify_log)) {
    $total_count = 0;
    $success_count = 0;
    $failed_count = 0;

    $file = fopen($data_notify_log, 'r') or exit('Unable to open data notify log file ! Exit ...');
    while (! feof($file)) {
        $line = fgets($file);
        // echo $line;
        if (ereg('.*Total  Data Count.*', $line)) {  // filter actual data count
            $tmp_list = explode(':', $line);
            // print_r($tmp_list);
            if (! isset($tmp_list[4])) {
                wx_error('checking data notify auto log, cannot filter total count info !');
                exit('cannot filter total count info');
            }
            else {
                $total_count = trim($tmp_list[4]);
            }
        }
        elseif (ereg('.*Data Id.*', $line)) {
            $success_count++;
        }
    }
    fclose($file);

    // compute failed count
    if ((int)$total_count != 0) {
        $failed_count = (int)$total_count - $success_count;
    }
    // statistic data
    $statistic_data['data-notify']['total'] = $total_count;
    $statistic_data['data-notify']['success'] = $success_count;
    $statistic_data['data-notify']['failed'] = $failed_count;
}

// statistic the global data
wx_tips('=====================================================================');
echo "=============== |  Total  |  Success  |  Failed  |  =================\n";

echo "clear image     |  ".$statistic_data['clear-image']['total']."  |  ".$statistic_data['clear-image']['success'].'  |  '.$statistic_data['clear-image']['failed']."  |  ";
if ($statistic_data['clear-image']['failed'] == 0) {
    echo "==========> ";
    wx_tips("OK\n", false);
}
else {
    wx_warnning("==========> Warnning\n", false);
}

echo "clear unfull    |  ".$statistic_data['clear-unfull']['total'].'  |  '.$statistic_data['clear-unfull']['success'].'  |  '.$statistic_data['clear-unfull']['failed']."  |  ";
if ($statistic_data['clear-unfull']['failed'] == 0) {
    echo "==========> ";
    wx_tips("OK\n", false);
}
else {
    wx_warnning("==========> Warnning\n", false);
}

echo "clear vps       |  ".$statistic_data['clear-vps']['total'].'  |  '.$statistic_data['clear-vps']['success'].'  |  '.$statistic_data['clear-vps']['failed']."  |  ";
if ($statistic_data['clear-vps']['failed'] == 0) {
    echo "==========> ";
    wx_tips("OK\n", false);
}
else {
    wx_warnning("==========> Warnning\n", false);
}

echo "data  move      |  ".$statistic_data['data-move']['total'].'  |  '.$statistic_data['data-move']['success'].'  |  '.$statistic_data['data-move']['failed']."  |  ";
if ($statistic_data['data-move']['failed'] == 0) {
    echo "==========> ";
    wx_tips("OK\n", false);
}
else {
    wx_warnning("==========> Warnning\n", false);
}

echo "data  notify    |  ".$statistic_data['data-notify']['total'].'  |  '.$statistic_data['data-notify']['success'].'  |  '.$statistic_data['data-notify']['failed']."  |  ";
if ($statistic_data['data-notify']['failed'] == 0) {
    echo "==========> ";
    wx_tips("OK\n", false);
}
else {
    wx_warnning("==========> Warnning\n", false);
}

wx_tips('=====================================================================');

// print_r($statistic_data);
exit();

/*****************************************************************************/
/* End of file report_autolog.php */
/* Location: ./bin/report_autolog.php */
