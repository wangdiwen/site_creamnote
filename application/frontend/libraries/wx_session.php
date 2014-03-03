<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WX_Session {
/*****************************************************************************/
    var $CI;
/*****************************************************************************/
    public function __construct() {
        $this->CI =& get_instance();

        $this->CI->load->model('openapi/wxm_user_activity_api');
    }
/*****************************************************************************/
    public function record_user_ci_session($user_id = 0, $ci_session_id = '') {
        if ($user_id > 0 && $ci_session_id) {
            $ret = $this->CI->wxm_user_activity_api->update_user_ci_session($user_id, $ci_session_id);
        }
    }
/*****************************************************************************/
}

/* End of file wx_session.php */
/* Location: /application/frontend/libraries/wx_session.php */
