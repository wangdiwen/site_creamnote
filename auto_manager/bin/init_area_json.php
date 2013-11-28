<?php

/**
 * Author: wangdiwen
 * Date  : 2013-10
 * Note  : This php script is for create a new area-school json data,
 *         for give frontend to use.
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

$db_table = 'wx_category_area';
$db_select = array(
    'carea_id',
    'carea_name',
    'carea_grade',
    'carea_flag',
    );
$db_service = new WX_DB();

$new_json_data = array();       // 存储前端需要的地区+学校 json 数据

/***************************** 获取地区学校信息 ************************************/
echo '====================== Geting Area Infomation  ====================='."\n";
$db_where_area = array(
    'carea_grade' => '1',
    );
$area_info = $db_service->select($db_table, $db_select, $db_where_area);
if ($area_info) {
    foreach ($area_info as $key => $area) {
        // echo '地区'.$key."\t".$area['carea_name']."\t编号 = ".$area['carea_flag']."\n";

        // init area json format array
        $area_dict = array(
            'id' => $area['carea_flag'],
            'name' => $area['carea_name'],
            'school' => array(),
            );

        // 获取地区对应的学校信息
        $school_where = array(
            'carea_flag >=' => $area['carea_flag'].'001',
            'carea_flag <=' => $area['carea_flag'].'999',
            );
        $school_info = $db_service->select($db_table, $db_select, $school_where);
        if ($school_info) {
            foreach ($school_info as $key_1 => $school) {
                // echo '学校：'.$school['carea_name']."\t编号 = ".$school['carea_flag']."\n";

                $school_tmp_dict = array(
                    'id' => $school['carea_flag'],
                    'name' => $school['carea_name'],
                    );
                $area_dict['school'][] = $school_tmp_dict;
            }
        }

        $new_json_data[] = $area_dict;

        // testing
        // if ($key == 0) {
        //     // print_r($area_dict);
        //     break;
        // }
    }
}

/***************************** 生成新的json格式文件 ************************************/
echo '====================== Create New Json File  ====================='."\n";
// print_r($new_json_data);
$json_file = '/alidata/www/creamnote/auto_manager/bin/new-school.json';
$json_content = json_encode($new_json_data);
$ret_write = file_put_contents($json_file, $json_content);
if ($ret_write) {
    echo '创建新 Json 格式化数据，成功 ！'."\n";
}
else {
    echo '创建新 Json 格式化数据，失败 ！'."\n";
}
