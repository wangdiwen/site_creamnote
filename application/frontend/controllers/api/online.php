<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class Online extends CI_Controller
{
/*****************************************************************************/
    public function __construct() {
        parent::__construct();

        $this->load->model('openapi/wxm_user_activity_api');
        $this->load->model('openapi/wxm_ci_session_api');
        // $this->load->library('wx_util');
    }
/*****************************************************************************/
    public function check_user_online($user_id = 0) {
        $result = 'no';
        if (is_numeric($user_id) && $user_id > 0) {
            // find user's last CI session id info
            $session_info = $this->wxm_user_activity_api->get_ci_session($user_id);
            if ($session_info) {
                $session_id = $session_info['uactivity_ci_session'];
                if ($session_id) {
                    $has_such_session = $this->wxm_ci_session_api->check_has_session_id($session_id);
                    if ($has_such_session) {
                        $result = 'yes';
                    }
                }
            }
        }
        echo $result;
    }
/*****************************************************************************/
}

/* End of file online.php */
/* Location: /application/frontend/controllers/api/online.php */
