<?php

/*****************************************************************************/
/**
 * 日志记录函数
 */
/*****************************************************************************/
if (! function_exists('wx_log'))
{
    function wx_log($msg = '')
    {
        if (WX_LOG) {
            if (defined('WX_LOG_PATH')) {
                $log_path = WX_LOG_PATH;
                if (empty($log_path) || !file_exists($log_path)) {
                    throw new Exception('Log Error: '.$log_path.' not exist');
                }

                $log_file = $log_path.WX_SEPARATOR.'auto_manager_'.date('Y-m-d').'.log';

                if (WX_DISPLAY_LOG) {
                    echo $msg."\n";
                }

                if (! error_log('['.date('Y-m-d H:i:s').'] : '.$msg."\n", 3, $log_file)) {
                    throw new  Exception('Log Error: Write to log file '.$log_file.' failed');
                }
            }
        }
    }
}


?>
