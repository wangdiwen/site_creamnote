<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXC_About extends CI_Controller
{
/*****************************************************************/
    public function __construct()
    {
        parent::__construct();
    }
/*****************************************************************/
    public function about_us()
    {
        $this->load->view('about/wxv_about_us');
    }
    public function about_creamnote()
    {
        $this->load->view('about/wxv_about_creamnote');
    }
    public function connect_us()
    {
        $this->load->view('about/wxv_connect_us');
    }
}
