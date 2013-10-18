<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXC_Cooperation extends CI_Controller
{
/*****************************************************************/
    public function __construct()
    {
        parent::__construct();
    }
/*****************************************************************/
    public function medium()
    {
        $this->load->view('coop/wxv_coop_medium');
    }
    public function friendly_link()
    {
        $this->load->view('coop/wxv_coop_link');
    }
}
