<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Share extends CI_Controller {
/*****************************************************************************/
    public function __construct() {
        parent::__construct();

        $this->load->library('wxl_data');
    }
/*****************************************************************************/
    public function page_404() {
        echo 'Weixin 404 page';
    }
/*****************************************************************************/
    public function testing() {
        $name = '通信 考试';
        $result = $this->wxl_data->weixin_note(explode(' ', $name));
        if ($result) {
            wx_echoxml($result);
        }
    }
/*****************************************************************************/
/*****************************************************************************/
}

/* End of file share.php */
/* Location: /application/weixin/controllers/share.php */
