<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXC_Direct extends CI_Controller
{
/*****************************************************************************/
    public function __construct() {
        parent::__construct();
    }
/*****************************************************************************/
    public function sys_error() {
        $this->load->view('share/wxv_system_error');
    }
/*****************************************************************************/
    public function free_download_over() {
        $this->load->view('share/wxv_download_overflow');
    }
/*****************************************************************************/
    public function user_close_page() {  // 用户封号页面
        $this->load->view('entry/wxv_account_ban');
    }
/*****************************************************************************/
}

/* End of file wxc_direct.php */
/* Location: /application/controllers/frontend/static/wxc_direct.php */
