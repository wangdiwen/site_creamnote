<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WXC_Alioss extends CI_Controller
{
/*****************************************************************************/
    public function __construct()
    {
        parent::__construct();

        $this->load->library('wx_aliossapi');       // 调用自定义的阿里OSS类
    }
/*****************************************************************************/
    public function create_directory()              // 测试接口
    {
        $bucket = 'mybucket';
        $dir = 'mydir';

        $this->wx_aliossapi->create_directory($bucket, $dir);
    }
/*****************************************************************************/
    public function php_ext()                       // 测试PHP的所有扩展
    {
        $extensions = get_loaded_extensions();
        echoxml($extensions);
    }
/*****************************************************************************/
    public function test()                          // 测试接口
    {
        echo 'alioss conntroller...'.'<br />';
        $this->wx_aliossapi->test();
    }
/*****************************************************************************/
}

/* End of file alioss.php */
/* Location: ./application/controllers/alioss.php */
