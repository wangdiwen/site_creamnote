<?php
/*****************************************************************************/
    /**
     * 下面是自动化定时维护任务的基础全局变量
     */
    define('WX_AUTHOR', 'wangdiwen');
    define('WX_DATE', '2013-05-26');
    define('WX_VERSION', '1.0');
/*****************************************************************************/

/*****************************************************************************/
    /**
     *  以下为平台移植的阿里官方OSS开放存储服务公共配置文件
     *  说明：官方php开发包sdk文件为：conf.inc.php
     */
/*****************************************************************************/
    define('OSS_ACCESS_ID', 'DvOTloljBCJo84zj');                //ACCESS_ID
    define('OSS_ACCESS_KEY', '3YoDhKaoeBxp9jiWXbkvrBSedJm5sB'); //ACCESS_KEY
    define('ALI_LOG', FALSE);									//是否记录日志
    define('ALI_LOG_PATH','../logs');							//自定义日志路径，如果没有设置，则使用系统默认路径，在./logs/
    define('ALI_DISPLAY_LOG', FALSE);							//是否显示LOG输出
    define('ALI_LANG', 'zh');									//语言版本设置

    //定义软件名称，版本号等信息
    define('OSS_NAME','oss-sdk-php');
    define('OSS_VERSION','1.1.5');
    define('OSS_BUILD','201210121010245');
    define('OSS_AUTHOR', 'xiaobing.meng@alibaba-inc.com');
/*****************************************************************************/
    /**
     * 日志功能
     */
    define('WX_LOG', TRUE);
    define('WX_LOG_PATH', WX_BASE_PATH.WX_SEPARATOR.'log');
    define('WX_DISPLAY_LOG', TRUE);
/*****************************************************************************/
    /**
     * 本地VPS文件存储路径
     */
    define('WX_UPLOAD_PATH', '/alidata/www/creamnote/upload');
    define('WX_UPLOAD_FLASH_PATH', '/alidata/www/creamnote/upload/flash');
/*****************************************************************************/

?>
