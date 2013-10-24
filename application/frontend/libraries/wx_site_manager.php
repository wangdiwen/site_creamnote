<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WX_Site_Manager {
/*****************************************************************************/
    var $CI;
    var $wx_table = 'wx_site_manager';
/*****************************************************************************/
    public function __construct() {
        $this->CI =& get_instance();

        $this->CI->load->model('core/wxm_site_manager');
    }
/*****************************************************************************/
    public function _check_cur_month_record() {  // first invoke before any record
        $cur_month = wx_month();
        $has_cur_info = $this->CI->wxm_site_manager->has_by_date($cur_month);
        if (! $has_cur_info) {  // create new record
            $last_month = wx_last_month();
            $has_last_info = $this->CI->wxm_site_manager->has_by_date($last_month);
            if (! $has_last_info) {  // init cur month record
                $cur_month_data = array(
                    'site_date' => $cur_month,
                    'site_users' => 0,
                    'site_note_count' => 0,
                    'site_upload_count' => 0,
                    'site_imagenote_count' => 0,
                    'site_freedown_count' => 0,
                    'site_paydown_count' => 0,
                    'site_download_count' => 0,
                    'site_total_income' => 0,
                );
                $this->CI->wxm_site_manager->insert_new($cur_month_data);
            }
            else {  // copy last month data, init cur month data
                $last_month_data = $this->CI->wxm_site_manager->get_by_date($last_month);
                if ($last_month_data) {
                    $last_month_data['site_date'] = $cur_month;
                    $this->CI->wxm_site_manager->insert_new($last_month_data);
                }
            }
            return false;
        }
        return true;
    }
/*****************************************************************************/
    public function add_new_register_user() {  // trigger iface
        // check cur month record ready
        $ret = $this->_check_cur_month_record();

        // add new user register
        $cur_month = wx_month();
        $reg_user_info = $this->CI->wxm_site_manager->get_by_date($cur_month);
        if ($reg_user_info) {
            $user_count = $reg_user_info['site_users'];
            $user_count = $user_count + 1;
            $data = array(
                'site_users' => $user_count
                );
            $this->CI->wxm_site_manager->update_site_manager($cur_month, $data);
        }
    }
/*****************************************************************************/
    public function add_upload_note() {
        // check cur month record ready
        $ret = $this->_check_cur_month_record();

        // add note count, and upload count
        $cur_month = wx_month();
        $note_count_info = $this->CI->wxm_site_manager->get_by_date($cur_month);
        if ($note_count_info) {
            $note_count = $note_count_info['site_note_count'];
            $upload_count = $note_count_info['site_upload_count'];
            $note_count += 1;
            $upload_count += 1;
            $data = array(
                'site_note_count' => $note_count,
                'site_upload_count' => $upload_count,
                );
            $this->CI->wxm_site_manager->update_site_manager($cur_month, $data);
        }
    }
/*****************************************************************************/
    public function add_image_note() {
        // check cur month record ready
        $ret = $this->_check_cur_month_record();

        // create a new image pdf file
        $cur_month = wx_month();
        $site_info = $this->CI->wxm_site_manager->get_by_date($cur_month);
        if ($site_info) {
            $note_count = $site_info['site_note_count'];
            $imagenote_count = $site_info['site_imagenote_count'];
            $note_count += 1;
            $imagenote_count += 1;
            $data = array(
                'site_note_count' => $note_count,
                'site_imagenote_count' => $imagenote_count,
                );
            $this->CI->wxm_site_manager->update_site_manager($cur_month, $data);
        }
    }
/*****************************************************************************/
    public function add_free_download_count() {
        // check cur month record ready
        $ret = $this->_check_cur_month_record();

        // add a download count
        $cur_month = wx_month();
        $site_info = $this->CI->wxm_site_manager->get_by_date($cur_month);
        if ($site_info) {
            $free_down_count = $site_info['site_freedown_count'];
            $download_count = $site_info['site_download_count'];
            $free_down_count += 1;
            $download_count += 1;
            $data = array(
                'site_freedown_count' => $free_down_count,
                'site_download_count' => $download_count,
                );
            $this->CI->wxm_site_manager->update_site_manager($cur_month, $data);
        }
    }
/*****************************************************************************/
    public function add_pay_download_count() {
        // check cur month record ready
        $ret = $this->_check_cur_month_record();

        // for pay to download, update count record
        $cur_month = wx_month();
        $site_info = $this->CI->wxm_site_manager->get_by_date($cur_month);
        if ($site_info) {
            $pay_count = $site_info['site_paydown_count'];
            $down_count = $site_info['site_download_count'];
            $pay_count += 1;
            $down_count += 1;
            $data = array(
                'site_paydown_count' => $pay_count,
                'site_download_count' => $down_count,
                );
            $this->CI->wxm_site_manager->update_site_manager($cur_month, $data);
        }
    }
/*****************************************************************************/
    public function add_site_income($creamnote_income = 0.00) {
        if ($creamnote_income > 0.00) {
            // check cur month record ready
            $ret = $this->_check_cur_month_record();

            // add creamnote profit or income
            $cur_month = wx_month();
            $site_info = $this->CI->wxm_site_manager->get_by_date($cur_month);
            if ($site_info) {
                $site_total_income = $site_info['site_total_income'];
                $new_total_income = number_format($site_total_income + $creamnote_income, 2, '.', '');

                $data = array(
                    'site_total_income' => $new_total_income,
                    );
                $this->CI->wxm_site_manager->update_site_manager($cur_month, $data);
            }
        }
    }
/*****************************************************************************/
/*****************************************************************************/
/*****************************************************************************/
    public function test() {

    }
/*****************************************************************************/
}

/* End of file wx_site_manager.php */
/* Location: /application/frontend/libraries/wx_site_manager.php */
