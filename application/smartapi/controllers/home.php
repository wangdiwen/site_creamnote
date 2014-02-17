<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
/*****************************************************************************/
    public function __construct() {
        parent::__construct();

        // $this->load->model('core/wxm_admin_user');
        //$this->load->library('wxl_data');
    }
/*****************************************************************************/
    public function index() {
        $data = array();

        $this->load->view('home', $data);
    }
/*****************************************************************************/
/*****************************************************************************/
}

/* End of file home.php */
/* Location: /application/sell/controllers/home.php */
