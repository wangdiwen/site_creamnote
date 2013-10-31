<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Share extends CI_Controller {
/*****************************************************************************/
    public function __construct() {
        parent::__construct();
    }
/*****************************************************************************/
    public function page_404() {
        $this->load->view('wxv_404');
    }
/*****************************************************************************/
/*****************************************************************************/
/***********************      日志功能      ***********************************/
/*****************************************************************************/
    public function frontend_log_page() {  // 分类日志文件列表
        $frontend_log = $this->_frontend_log_list();
        $data = array(
            'frontend_log_list' => $frontend_log,
            );
        $this->load->view('f_log/wxv_frontend_log', $data);
    }
/*****************************************************************************/
    public function backend_log_page() {
        $backend_log = $this->_backend_log_list();
        $data = array(
            'backend_log_list' => $backend_log,
            );
        $this->load->view('f_log/wxv_backend_log', $data);
    }
/*****************************************************************************/
    public function auto_task_log_page() {
        $auto_log = $this->_auto_task_log_list();

        $data = array(
            'auto_log_list' => $auto_log,
            'tab_no' => 'tab1',
            );

        $tab_no = $this->session->userdata('auto_log_tab_no');
        if ($tab_no) {
            $data['tab_no'] = $tab_no;
        }
        else {
            $tab_no_cookie = array(
                'auto_log_tab_no' => 'tab1',
                );
            $this->session->set_userdata($tab_no_cookie);
        }

        $this->load->view('f_log/wxv_auto_log', $data);
    }
/*****************************************************************************/
    public function _frontend_log_list() {
        $data = array();
        $frontend_path = '/alidata/www/creamnote/application/frontend/logs';
        if (is_dir($frontend_path)) {
            $frontend_objs = scandir($frontend_path);
            foreach ($frontend_objs as $obj) {
                if ($obj != '.' && $obj != '..') {
                    $data[] = $obj;
                }
            }
        }
        if ($data) {
            arsort($data);
        }
        return $data;
    }
/*****************************************************************************/
    public function _backend_log_list() {
        $data = array();
        $backend_path = '/alidata/www/creamnote/application/backend/logs';
        if (is_dir($backend_path)) {
            $backend_objs = scandir($backend_path);
            foreach ($backend_objs as $obj) {
                if ($obj != '.' && $obj != '..') {
                    $data[] = $obj;
                }
            }
        }
        if ($data) {
            arsort($data);
        }
        return $data;
    }
/*****************************************************************************/
    public function _auto_task_log_list() {
        $data = array(
            'clear-image' => array(),
            'clear-unfull' => array(),
            'clear-vps' => array(),
            'data-move' => array(),
            'data-notify' => array(),
            );

        $auto_manager_path = '/alidata/www/creamnote/auto_manager/log';
        if (is_dir($auto_manager_path)) {
            $auto_objs = scandir($auto_manager_path);
            foreach ($auto_objs as $obj) {
                if ($obj != '.' && $obj != '..') {
                    if (ereg('^auto_clear_image', $obj)) {
                        $data['clear-image'][] = $obj;
                    }
                    elseif (ereg('^auto_clear_unfull', $obj)) {
                        $data['clear-unfull'][] = $obj;
                    }
                    elseif (ereg('^auto_clear_vps', $obj)) {
                        $data['clear-vps'][] = $obj;
                    }
                    elseif (ereg('^auto_data_move', $obj)) {
                        $data['data-move'][] = $obj;
                    }
                    elseif (ereg('^auto_data_notify', $obj)) {
                        $data['data-notify'][] = $obj;
                    }
                }
            }
        }

        arsort($data['clear-image']);
        arsort($data['clear-unfull']);
        arsort($data['clear-vps']);
        arsort($data['data-move']);
        arsort($data['data-notify']);
        return $data;
    }
/*****************************************************************************/
/*****************************************************************************/
    public function _read_log($log_file = '') {
        if (file_exists($log_file)) {
            $file = fopen($log_file, 'r') or exit('Unable to open file ! Exit ...');
            while (! feof($file)) {
                echo fgets($file).'<br />';
            }
            fclose($file);
        }
        else {
            echo 'No log file [ '.$log_file.' ]<br />';
        }
    }
/*****************************************************************************/
/*****************************************************************************/
    public function auto_task_log() {
        $log_name = $this->input->get('log_name');
        // $log_name = 'auto_clear_image_2013-10-22.log';  // test ...

        $auto_manager_path = '/alidata/www/creamnote/auto_manager/log';
        $log_file = $auto_manager_path.'/'.$log_name;
        $this->_read_log($log_file);
    }
/*****************************************************************************/
    public function frontend_log() {
        $log_name = $this->input->get('log_name');
        // $log_name = 'log-2013-10-29.php';  // test ...

        $frontend_path = '/alidata/www/creamnote/application/frontend/logs';
        $log_file = $frontend_path.'/'.$log_name;
        $this->_read_log($log_file);
    }
/*****************************************************************************/
    public function backend_log() {
        $log_name = $this->input->get('log_name');
        // $log_name = 'log-2013-10-30.php';  // test ...

        $backend_path = '/alidata/www/creamnote/application/backend/logs';
        $log_file = $backend_path.'/'.$log_name;
        $this->_read_log($log_file);
    }
/*****************************************************************************/
/*****************************************************************************/
    public function auto_task_log_clear() {
        $log_name = $this->input->post('log_name');
        $tab_no = $this->input->post('tab_no');
        // $log_name = 'auto_clear_image_2013-10-22.log';  // test ...

        $tab_no_cookie = array(
            'auto_log_tab_no' => $tab_no,
            );
        $this->session->set_userdata($tab_no_cookie);

        $auto_manager_path = '/alidata/www/creamnote/auto_manager/log';
        $log_file = $auto_manager_path.'/'.$log_name;
        if (file_exists($log_file)) {
            $ret_del = wx_delete_file($log_file);
            if ($ret_del) {
                echo 'success';
            }
            else {
                echo 'failed';
            }
        }
        else {
            echo 'not-exist';
        }
    }
/*****************************************************************************/
    public function frontend_log_clear() {
        $log_name = $this->input->post('log_name');
        // $log_name = 'log-2013-10-29.php';  // test ...

        $frontend_path = '/alidata/www/creamnote/application/frontend/logs';
        $log_file = $frontend_path.'/'.$log_name;
        if (file_exists($log_file)) {
            $ret_del = wx_delete_file($log_file);
            if ($ret_del) {
                echo 'success';
                return true;
            }
            else {
                echo 'failed';
                return false;
            }
        }
        else {
            echo 'not-exist';
            return false;
        }
    }
/*****************************************************************************/
    public function backend_log_clear() {
        $log_name = $this->input->post('log_name');
        // $log_name = 'log-2013-10-30.php';  // test ...

        $backend_path = '/alidata/www/creamnote/application/backend/logs';
        $log_file = $backend_path.'/'.$log_name;
        if (file_exists($log_file)) {
            $ret_del = wx_delete_file($log_file);
            if ($ret_del) {
                echo 'success';
                return true;
            }
            else {
                echo 'failed';
                return false;
            }
        }
        else {
            echo 'not-exist';
            return false;
        }
    }
/*****************************************************************************/
}

/* End of file share.php */
/* Location: /application/backend/controllers/share.php */
