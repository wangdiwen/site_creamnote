<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class Category extends CI_Controller
{
/*****************************************************************************/
    public function __construct() {
        parent::__construct();

        $this->load->model('openapi/wxm_category_nature');
        // $this->load->library('wx_util');
    }
/*****************************************************************************/
    public function category_tag() {
        $nature_id = $this->input->get('category_id');

        $nature_id = trim($nature_id);
        $tag_list = array();
        $category_info = $this->wxm_category_nature->get_nature_tag($nature_id);
        if ($category_info) {
            $tag_info = $category_info['cnature_tag'];
            $tag_list = explode(',', $tag_info);
        }
        echo json_encode($tag_list);
    }
/*****************************************************************************/
/*****************************************************************************/
/*****************************************************************************/
/*****************************************************************************/
    public function test() {
        echo 'category open api';
    }
/*****************************************************************************/
}

/* End of file category.php */
/* Location: /application/frontend/controllers/api/category.php */
