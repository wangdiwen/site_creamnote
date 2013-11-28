<?php

/**
 * Author: wangdiwen
 * Date  : 2013-10
 * Note  : This php script is for check area db table.
 */
/*****************************************************************************/
/**-----------------------  加载资源   --------------------------------------*/
/*****************************************************************************/
/**
 * 全局变量
 */
define('WX_BASE_PATH', '/alidata/www/creamnote/auto_manager');
define('WX_SEPARATOR', '/');
// 加载数据库接口类模块
require_once WX_BASE_PATH.WX_SEPARATOR.'model'.WX_SEPARATOR.'wx_database_api.php';

/***************************** 逻辑处理 ************************************/
echo '====================== Checking Area DB Infomation ====================='."\n\n";

$table = 'wx_category_area';
$select = array(
    'carea_id',
    'carea_name',
    );
$db_service = new WX_DB();


$json_path = '/alidata/www/creamnote/auto_manager/bin/new-school.json';
if (! file_exists($json_path)) {
    echo 'file not exist...'."\n";
    return false;
}

$context = file_get_contents($json_path);
$json_data = json_decode($context, true);
$json_count = count($json_data);

// echo '====================== Checking Area  ====================='."\n";
// foreach ($json_data as $key => $single) {
//     $area_name = $single['name'];
//     // echo '地区名称 = '.$area_name."\n";

//     $db_where = array(
//         'carea_name' => $area_name,
//         );
//     $area_info = $db_service->select($table, $select, $db_where, 1);
//     if (! $area_info) {
//         echo '无此地区'.$key.'：'.$area_name."\n";
//     }
//     else {
//         echo '地区'.$key.'：'.$area_name."\t数据库编号 = ".$area_info[0]['carea_id']."\n";
//     }
// }
// echo '====================== Checking Area  ====================='."\n\n";


echo '====================== Checking School  ====================='."\n";
$stdin = fopen('php://stdin', 'r');
$input = '';
while (true) {
    echo '请输入地区的编号（0-33）：';
    $input = trim(fgets($stdin));
    if (is_numeric($input)) {
        // echo "number = ".$input."\n";

        foreach ($json_data as $key => $value) {
            $area_name = $value['name'];
            $school_list = $value['school'];
            // echo $area_name."\n";
            if ($key == $input) {
                // print_r($school_list);
                foreach ($school_list as $key_1 => $school) {
                    $school_name = $school['name'];
                    // echo '地区'.$key.' '.$area_name."\t学校 ".$key_1." ==> ".$school_name."\n";

                    $db_table = 'wx_category_area';
                    $db_select = array(
                        'carea_id',
                        'carea_name',
                        );
                    $db_where = array(
                        'carea_name' => $school_name,
                        'carea_grade' => '2',
                        );
                    $school_info = $db_service->select($db_table, $db_select, $db_where, 1);
                    if (! $school_info) {
                        echo '地区'.$key.' '.$area_name."\t学校 ".$key_1." ==> ".$school_name."\t\t状态：不匹配\n";
                    }
                    // else {
                    //     echo '地区'.$key.' '.$area_name."\t学校 ".$key_1." ==> ".$school_name."\t\t状态：匹配 数据库编号：".$school_info[0]['carea_id']."\n";
                    // }
                }
                break;
            }
        }
    }
    echo '====================== Checking School  ====================='."\n";

    echo '你需要退出操作吗？（y/yes | n/no）: ';
    $quit = trim(fgets($stdin));
    if (in_array($quit, array('y','yes'))) {
        break;
    }
}
fclose($stdin);

echo '====================== Checking School  ====================='."\n";

echo "\n";
echo '====================== Checking Area DB Infomation ====================='."\n";
