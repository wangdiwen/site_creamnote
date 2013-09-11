<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WXC_Util extends CI_Controller
{
/*****************************************************************************/
    public function __construct()
    {
        parent::__construct();
        $this->load->library('wx_util');
    }
/*****************************************************************************/
    public function get_new_auth_code()
    {
        $new_code = $this->wx_util->get_auth_code();
        echo $new_code;  // ajax
    }
/*****************************************************************************/
    public function check_auth_code()
    {
        $auth_result = $this->input->post('wx_auth_code');
        $ret_auth = $this->wx_util->check_auth_code($auth_result);
        if (! $ret_auth) {
            echo 'auth-code-error';  // ajax
            return;
        }
        echo 'auth-code-ok';  // ajax
    }
/*****************************************************************************/
    public function _create_auth_code()
    {
        // 验证码功能
        $auth_json = array();
        $auth_json[] = array('math' => '1 + 1', 'result' => '2');
        $auth_json[] = array('math' => '2 + 3', 'result' => '5');
        $auth_json[] = array('math' => '5 x 6', 'result' => '30');
        $auth_json[] = array('math' => '3 + 4', 'result' => '7');
        $auth_json[] = array('math' => '4 / 2', 'result' => '2');
        $auth_json[] = array('math' => '6 + 7', 'result' => '13');
        $auth_json[] = array('math' => '9 + 2', 'result' => '11');
        $auth_json[] = array('math' => '9 / 3', 'result' => '3');
        $auth_json[] = array('math' => '10 / 2', 'result' => '5');
        $auth_json[] = array('math' => '8 + 3', 'result' => '11');
        $auth_json[] = array('math' => '5 - 1', 'result' => '4');
        $auth_json[] = array('math' => '100 - 2', 'result' => '98');
        $auth_json[] = array('math' => '45 + 5', 'result' => '50');
        $auth_json[] = array('math' => '34 - 12', 'result' => '22');
        $auth_json[] = array('math' => '22 / 2', 'result' => '11');
        $auth_json[] = array('math' => '34 / 2', 'result' => '17');
        $auth_json[] = array('math' => '8 x 9', 'result' => '72');
        $auth_json[] = array('math' => '5 + 6', 'result' => '11');
        $auth_json[] = array('math' => '5 x 6', 'result' => '30');
        $auth_json[] = array('math' => '7 x 8', 'result' => '56');
        $auth_json[] = array('math' => '9 x 0', 'result' => '0');
        $auth_json[] = array('math' => '3 + 10', 'result' => '13');
        $auth_json[] = array('math' => '89 + 1', 'result' => '90');
        $auth_json[] = array('math' => '56 + 4', 'result' => '60');
        $auth_json[] = array('math' => '70 - 4', 'result' => '66');
        $auth_json[] = array('math' => '88 + 2', 'result' => '90');
        $auth_json[] = array('math' => '12 / 6', 'result' => '2');
        $auth_json[] = array('math' => '32 - 2', 'result' => '30');
        $auth_json[] = array('math' => '22 + 2', 'result' => '24');
        $auth_json[] = array('math' => '8 + 8', 'result' => '16');

        echo 'len = '.count($auth_json).'<br />';
        $json_context = json_encode($auth_json);

        $auth_file = 'application/frontend/license/auth_code.json';
        // $ret = file_put_contents($auth_file, $json_context);
        $context = file_get_contents($auth_file);
        echo $context.'<br />';
    }
/*****************************************************************************/
}

/* End of file wxc_util.php */
/* Location: ./application/controllers/core/wxc_util.php */
