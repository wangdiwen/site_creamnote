<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WX_Aliossapi
{
    var $CI;                        // super CI obj
    var $oss_sdk_service;           // oss server obj

/*****************************************************************************/
    public function __construct()
    {
        $this->CI =& get_instance();

        // 加载移植后的阿里OSS官方sdk接口
        $this->CI->load->helper('wx_alioss_public');
        $this->CI->load->helper('wx_alioss_sdk');

        // 构造OSS存储服务对象
        $this->oss_sdk_service = new ALIOSS();
        // 设置是否打开curl调试模式
        $this->oss_sdk_service->set_debug_mode(FALSE);
    }
/*****************************************************************************/
    /**
     *  以下为平台自定义的阿里OSS开放存储服务接口
     *  说明：Service、Bucket的服务操作暂时不在平台实现，虽然官方的开发文档有这2个接口功能，
     *        平台目前只移植Object的各项功能，并且此接口只适用于云服务器和阿里OSS内网通信！
     *  具体的功能：
     *              1. 创建目录；(暂不支持了)
     *              2. 根据文件而不是内容上传文件；（不支持断点续传）
     *              3. 下载单个文件；（不支持断点下载）
     *              4. 删除单个文件；（不支持多个文件捆绑删除）
     *              5. 拷贝单个文件到指定的bucket；
     *              6. 判断文件是否存在；
     *              7. 获取object的属性信息；
     */
/*****************************************************************************/
/*****************************************************************************/
    public function get_object_info($bucket = '', $object = '')
    {
        if ($bucket && $object)
        {
            $response = $this->oss_sdk_service->get_object_meta($bucket, $object);
            // $this->_format($response);
            if ($response->status == 200)
                return $response->header['_info'];
            else
                return false;
        }
    }
/*****************************************************************************/
    public function get_object($bucket = '', $object = '', $download_file = '')  // 下载单个文件；（不支持断点下载）
    {
        if ($bucket && $object && $download_file)
        {
            $options = array(
                ALIOSS::OSS_FILE_DOWNLOAD => $download_file,
                //ALIOSS::OSS_CONTENT_TYPE => 'txt/html',
            );
            $response = $this->oss_sdk_service->get_object($bucket, $object, $options);
            // $this->_format($response);
            if ($response->status == 200)
                return true;
        }
        return false;
    }
/*****************************************************************************/
    public function upload_by_content($bucket = '', $object = '', $content = '')
    {
        if ($bucket && $object && $content) {
            $upload_options = array(
                'content' => $content,
                'length' => strlen($content),
                ALIOSS::OSS_HEADERS => array(
                        'Expires' => date('Y-m-d H:i:s')
                        ),
                );

            $response = $this->oss_sdk_service->upload_file_by_content($bucket, $object, $upload_options);
            if ($response->status == 200) {
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    // 根据文件而不是内容上传文件；（不支持断点续传）
    public function upload_by_file($bucket = '', $object = '', $file_path = '')
    {
        if ($bucket && $object && $file_path)
        {
            if (file_exists($file_path))
            {
                $response = $this->oss_sdk_service->upload_file_by_file($bucket, $object, $file_path);
                // $this->_format($response);
                if ($response->status == 200)
                    return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function delete_object($bucket = '', $object = '')           // 删除单个文件；（不支持多个文件捆绑删除）
    {
        if ($bucket && $object)
        {
            $has_obj = $this->is_object_exist($bucket, $object);
            if ($has_obj)
            {
                $response = $this->oss_sdk_service->delete_object($bucket, $object);
                // $this->_format($response);
                if ($response->status == 204)
                    return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    // 拷贝单个文件到指定的bucket或者bucket中指定的目录
    public function copy_object($from_bucket = '', $from_object = '', $to_bucket = '', $to_object = '')
    {
        if ($from_bucket && $from_object && $to_bucket && $to_object)
        {
            $response = $this->oss_sdk_service->copy_object($from_bucket, $from_object, $to_bucket, $to_object);
            // $this->_format($response);
            if ($response->status == 200)
                return true;
        }
        return false;
    }
/*****************************************************************************/
    public function is_object_exist($bucket = '', $object = '')         // 判断文件是否存在
    {
        if ($bucket && $object)
        {
            $response = $this->oss_sdk_service->is_object_exist($bucket, $object);
            // $this->_format($response);
            if ($response->status == 200)
                return true;
        }
        return false;
    }
/*****************************************************************************/
    public function _format($response)                               // 格式化返回结果
    {
        echo '|-----------------------Start------------------------------------------------------------'."<br />";
        echo '|-Status:' . $response->status . "<br />";
        echo '|-Body:' ."<br />";
        echo $response->body . "<br />";
        echo "|-Header:<br />";
        echoxml($response->header);
        echo '-------------------------End------------------------------------------------------------'."<br /><br />";
    }
/*****************************************************************************/
    public function oss_info()              // Test iface
    {
        echo 'alioss libraries interface...'.'<br />';
        echo 'OSS_NAME = '.OSS_NAME.'<br />';
        echo 'OSS_ACCESS_ID = '.OSS_ACCESS_ID.'<br />';
        echo 'OSS_ACCESS_KEY = '.OSS_ACCESS_KEY.'<br />';
    }
/*****************************************************************************/
}

/* End of file Aliossapi.php */
/* Location: ./application/libraries/Aliossapi.php */
