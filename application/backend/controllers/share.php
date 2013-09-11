<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Share extends CI_Controller
{
/*****************************************************************************/
    public function __construct()
    {
        parent::__construct();
    }
/*****************************************************************************/
    public function page_404()
    {
        $this->load->view('wxv_404');
    }
/*****************************************************************************/
}

/* End of file share.php */
/* Location: ./application/backend/controllers/share.php */
