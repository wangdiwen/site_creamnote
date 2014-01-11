<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXC_Home extends CI_Controller
{
/*****************************************************************************/
    public function __construct() {
        parent::__construct();

        $this->load->model('share/wxm_category_nature');
    }
/*****************************************************************************/

/*****************************************************************************/
    public function nature_data() {
        echo "<center>笔记分类数据</center>";

        $all_data = array();

        $grade_one = $this->wxm_category_nature->get_first_nature();
        // wx_echoxml($grade_one);
        foreach ($grade_one as $key => $obj) {
            $nature_id = $obj->cnature_id;
            $nature_name = $obj->cnature_name;

            $str = $nature_id.' '.$nature_name;
            $all_data[$str] = array();

            $grade_two = $this->wxm_category_nature->get_second_nature($nature_id);
            // wx_echoxml($grade_two);

            foreach ($grade_two as $key_2 => $obj_2) {
                $nature_id_two = $obj_2->cnature_id;
                $nature_name_two = $obj_2->cnature_name;

                $str_two = $nature_id_two.' '.$nature_name_two;
                $all_data[$str][$str_two] = array();

                $grade_three = $this->wxm_category_nature->get_third_nature($nature_id_two);
                // wx_echoxml($grade_three);

                if ($grade_three) {
                    foreach ($grade_three as $key_3 => $obj_3) {
                        $nature_id_three = $obj_3->cnature_id;
                        $nature_name_three = $obj_3->cnature_name;

                        $str_three = $nature_id_three.' '.$nature_name_three;
                        $all_data[$str][$str_two][] = $str_three;
                    }
                }
            }
        }
        wx_echoxml($all_data);
    }
/*****************************************************************************/

/*****************************************************************************/
}

/* End of file wxc_home.php */
/* Location: /application/controllers/frontend/experiment/wxc_home.php */
