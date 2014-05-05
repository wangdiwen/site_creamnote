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
                    $data_id = $obj['data_id'];
                    $data_name = $obj['data_name'];
                    $data_type = $obj['data_type'];
                    $data_pagecount = $obj['data_pagecount'];
                    $data_price = $obj['data_price'];
                    // $data_uploadtime = $obj['data_uploadtime'];
                    $data_tag = $obj['data_tag'];

                    if ($data_price == 0.00) {
                        $data_price = '免费';
                    }

                    $data_tag_msg = "\n主题：";
                    if (! $data_tag) {
                        $data_tag_msg = '';
                    }
                    else {
                        $data_tag_msg .= $data_tag;
                    }

                    $per_content = "《".$data_name."》\n".$data_type." ".$data_pagecount."页 ￥".$data_price.$data_tag_msg;
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
