<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXC_Help extends CI_Controller
{
/*****************************************************************************/
    public function __construct()
    {
        parent::__construct();
    }
/*****************************************************************************/
    public function faq()
    {
        $this->load->view('help/wxv_help_faq');
    }
/*****************************************************************************/
    public function skills()
    {
        $this->load->view('help/wxv_help_skills');
    }
/*****************************************************************************/
    public function termsofservice(){
        $this->load->view('help/wxv_termsofservice');
    }
/*****************************************************************************/
    public function termsoflaw(){
        $this->load->view('help/wxv_termsoflaw');
    }
/*****************************************************************************/
    public function privacy(){
        $this->load->view('help/wxv_contentofservice');
    }
/*****************************************************************************/
    public function sitemap(){
        $this->load->view('help/wxv_sitemap');
    }
/*****************************************************************************/
}

/* End of file wxc_direct.php */
/* Location: /application/controllers/frontend/static/wxc_help.php */
