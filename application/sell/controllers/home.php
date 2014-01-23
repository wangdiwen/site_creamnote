<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
/*****************************************************************************/
    public function __construct() {
        parent::__construct();

        //$this->load->library('wxl_data');
    }
/*****************************************************************************/
    public function index() {
        echo "<center>亲，欢迎来到 Creamnote.com 二手交易市场！</center>";
    }
/*****************************************************************************/
/*****************************************************************************/
}

/* End of file home.php */
/* Location: /application/sell/controllers/home.php */
