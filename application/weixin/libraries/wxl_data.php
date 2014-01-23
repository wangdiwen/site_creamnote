<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WXL_Data
{
/*****************************************************************************/
    var $CI;  // Get the CI super object
/*****************************************************************************/
    public function __construct() {
        $this->CI =& get_instance();

        $this->CI->load->model('wxm_data');
        // $this->CI->load->model('share/wxm_data2carea');
        // $this->CI->load->model('share/wxm_data2cnature');
        // $this->CI->load->model('share/wxm_category_area');
        // $this->CI->load->model('share/wxm_category_nature');
    }
/*****************************************************************************/
    public function weixin_note($note_name_list = array()) {
        $result = array();
        if ($note_name_list) {
            // $name_like = $this->CI->wxm_data->search_by_name_like($note_name_list);  // single
            $name_like = $this->CI->wxm_data->search_by_name_like_list($note_name_list);
            if ($name_like) {
                // convert the data
                foreach ($name_like as $key => $obj) {
                    $data_name = $obj['data_name'];
                    $data_summary = $obj['data_summary'];
                    $data_price = $obj['data_price'];
                    // $data_keyword = $obj['data_keyword'];
                    // $data_uploadtime = $obj['data_uploadtime'];

                    if ($data_price == 0.00) {
                        $data_price = '免费';
                    }

                    $per_content = "《".$data_name."》\n"."￥：".$data_price."\n"."简介：".trim($data_summary);
                    $result[] = $per_content;
                }
                return $result;
            }
        }
        return false;
    }
/*****************************************************************************/

/*****************************************************************************/

/*****************************************************************************/
/*****************************************************************************/
/*****************************************************************************/
}

/* End of file WXL_Data.php */
/* Location: ./application/libraries/wx_general.php */
