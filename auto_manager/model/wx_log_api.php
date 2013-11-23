<?php

/*****************************************************************************/
/**
 * 日志记录函数
 */
/*****************************************************************************/
if (! function_exists('wx_log'))
{
    function wx_log($msg = '', $log_file_name = '')
    {
        if (WX_LOG) {
            if (defined('WX_LOG_PATH')) {
                $log_path = WX_LOG_PATH;
                if (empty($log_path) || ! is_dir($log_path)) {
                    // throw new Exception('Log Error: '.$log_path.' not exist');
                    $error_log_file = '/alidata/www/creamnote/auto_manager/log/log_exception_'.date('Y-m-d').'.log';
                    error_log('['.date('Y-m-d H:i:s').'] : Log Exception '.$log_file_name." \n", 3, $error_log_file);
                    return false;
                }

                $log_file = $log_path.WX_SEPARATOR.'auto_'.$log_file_name.'_'.date('Y-m-d').'.log';

                if (WX_DISPLAY_LOG) {
                    echo $msg."\n";
                }

                if (! error_log('['.date('Y-m-d H:i:s').'] : '.$msg."\n", 3, $log_file)) {  // record auto task log
                    // throw new  Exception('Log Error: Write to log file '.$log_file.' failed');
					// if log exception, record to a single log file
					$except_log_file = $log_path.WX_SEPARATOR.'log_exception_'.date('Y-m-d').'.log';
					error_log('['.date('Y-m-d H:i:s').'] : Log Exception '.$log_file_name." \n", 3, $except_log_file);
                    return false;
                }
            }
        }
        return true;
    }
}


?>
