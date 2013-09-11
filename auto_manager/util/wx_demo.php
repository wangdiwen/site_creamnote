<?php
/*****************************************************************************/
    /**
     * 模块接口的测试用例
     * 1. 日志模块
     * 2. 数据库模块
     * 3. 阿里OSS接口模块
     */
/*****************************************************************************/

/*****************************************************************************/
/**-----------------------  日志模块   --------------------------------------*/
/*****************************************************************************/
// 测试日志接口
// $msg = 'test log iface .... by diwen';
// wx_log($msg);


/*****************************************************************************/
/**-----------------------  OSS 模块   --------------------------------------*/
/*****************************************************************************/
// 测试阿里云存储OSS
// $alioss_service = new WX_ALIOSS();
// -------------------------- get info iface ----------------- //
// $ret = $alioss_service->get_object_info('wx-flash', 'helloworld.pdf');
// if ($ret)
//     print_r($ret);
// else
//     wx_out('failed');

// -------------------------- download iface ----------------- //
// $download_file = WX_LOG_PATH.WX_SEPARATOR.'helloworld.pdf';
// $ret = $alioss_service->get_object('wx-flash', 'helloworld.pdf', $download_file);
// if ($ret) {
//     echo 'download success'."\n";
// }
// else {
//     echo 'download failed'."\n";
// }

// -------------------------- upload iface ----------------- //
// $upload_file = WX_LOG_PATH.WX_SEPARATOR.'helloworld.pdf';
// $ret = $alioss_service->upload_by_file('wx-final-exam', 'helloworld.pdf', $upload_file);
// if ($ret) {
//     echo 'upload success'."\n";
// }
// else {
//     echo 'upload failed'."\n";
// }

// -------------------------- copy iface ----------------- //
// $from_bucket = 'wx-final-exam';
// $from_obj = 'helloworld.pdf';
// $to_bucket = 'wx-graduate-exam';
// $to_obj = 'helloworld.pdf';

// $ret = $alioss_service->copy_object($from_bucket, $from_obj, $to_bucket, $to_obj);
// if ($ret) {
//     echo 'copy success'."\n";
// }
// else {
//     echo 'copy failed'."\n";
// }

// -------------------------- delete iface ----------------- //
// $bucket = 'wx-final-exam';
// $obj = 'helloworld.pdf';

// $ret = $alioss_service->delete_object($bucket, $obj);
// if ($ret) {
//     echo 'delete success'."\n";
// }
// else {
//     echo 'delete failed'."\n";
// }


/*****************************************************************************/
/**-----------------------  数据库模块   ------------------------------------*/
/*****************************************************************************/

// 测试mysql数据库接口
// $db_service = new WX_DB();
// -------------------------- select iface ----------------- //
// $table = 'wx_user';
// $select = array(
//     'user_id',
//     'user_name'
//     );
// $where = array(
//     /*'user_id' => 1*/
//     );
// $limit = 0;
// $orderby = 'user_name';
// $query = $db_service->select($table, $select, $where, $limit, $orderby);
// print_r($query);

// -------------------------- insert iface ----------------- //
// $table = 'wx_notify';
// $data = array(
//     'notify_type' => 4,
//     'notify_content' => 'notify content',
//     'user_id' => 1,
//     'notify_params' => '27',
//     'notify_time' => date('Y-m-d H:i:s')
//     );
// $ret = $db_service->insert($table, $data);
// if (! $ret) {
//     echo 'failed'."\n";
// }

// $query = $db_service->select($table);
// print_r($query);

// -------------------------- update iface ----------------- //
// $table = 'wx_notify';
// $data = array(
//     'notify_type' => 3,
//     'notify_time' => date('Y-m-d H:i:s')
//     );
// $where = array(
//     'notify_id' => 2
//     );

// $query = $db_service->select($table);
// echo 'preview...'."\n";
// print_r($query);

// echo 'update...'."\n";
// $ret = $db_service->update($table, $data, $where);

// $query = $db_service->select($table);
// echo 'current...'."\n";
// print_r($query);

// -------------------------- delete iface ----------------- //
// $table = 'wx_notify';
// $where = array(
//     'notify_id' => 2,
//     'notify_type' => '3'
//     );
// $ret = $db_service->delete($table, $where);
// if ($ret) {
//     echo 'success'."\n";
// }
// else {
//     echo 'failed'."\n";
// }

// $query = $db_service->select($table);
// echo 'current...'."\n";
// print_r($query);

/*****************************************************************************/

?>
