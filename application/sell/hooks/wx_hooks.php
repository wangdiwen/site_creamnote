<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WX_Hooks {
    var $CI;  // Get the CI super object
/*****************************************************************************/
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->helper('url');
    }
/*****************************************************************************/
    public function check_signin() {
        // add filter urls
        // todo ...
        // echo 'urls hooks ...<br />';
    }
/*****************************************************************************/
}

/* End of wx_hooks.php */
/* Location: /application/sell/hooks/wx_hooks.php */
