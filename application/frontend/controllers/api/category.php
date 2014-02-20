<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class Category extends CI_Controller
{
/*****************************************************************************/
    public function __construct() {
        parent::__construct();

        $this->load->model('openapi/wxm_category_nature');
        $this->load->model('share/wxm_data_tag');
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
    public function fetch_tag() {
        $tag_keyword = $this->input->get('tag_keyword');

        $tag_list = array();
        $tag_keyword = trim($tag_keyword);
        $tag_info = $this->wxm_data_tag->fetch_data_tag($tag_keyword);
        if ($tag_info) {
            foreach ($tag_info as $key => $value) {
                $tag_list[] = $value['tag_name'];
            }
        }
        echo json_encode($tag_list);
    }
/*****************************************************************************/
/*****************************************************************************/
/*****************************************************************************/
    public function test() {
        echo 'category open api<br>';
        $tag_name = '考试大纲';
        $has_tag = $this->wxm_data_tag->has_such_tag($tag_name);
        if ($has_tag)
            echo 'has';
        else {
            echo 'no add a new one ';
            $ret =  $this->wxm_data_tag->add_new_tag($tag_name);
            if ($ret) {
                echo 'success';
            }
        }

        // echo urlencode('期末复习');

    }
/*****************************************************************************/
}

/* End of file category.php */
/* Location: /application/frontend/controllers/api/category.php */
