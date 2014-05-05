<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXC_Cover extends CI_Controller
{
/*****************************************************************************/
    public function __construct() {
        parent::__construct();
    }
/*****************************************************************************/
    public function cover() {
        $this->load->view('entry/cover');
    }
/*****************************************************************************/
}

/* End of file wxc_cover.php */
/* Location: /application/frontend/controllers/static/wxc_cover.php */
