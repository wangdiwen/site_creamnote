<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WX_General
{
/*****************************************************************************/
    var $CI;  // Get the CI super object

/*****************************************************************************/
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('core/wxm_user');
        $this->CI->load->model('core/wxm_user2carea');
        $this->CI->load->model('core/wxm_user_activity');
        $this->CI->load->model('core/wxm_data');
        $this->CI->load->model('share/wxm_data2carea');
        $this->CI->load->model('share/wxm_data2cnature');
        $this->CI->load->model('share/wxm_data_activity');
        $this->CI->load->model('share/wxm_category_area');
        $this->CI->load->model('share/wxm_category_nature');

        $this->CI->load->library('wx_util');
        $this->CI->load->library('wx_weibo_renren_api');  // invoke taobao ip rest api
    }
/*****************************************************************************/
    public function get_user_base_info()
    {
        if (isset($_SESSION['wx_user_id']) && $_SESSION['wx_user_id'])
        {
            $user_id = $_SESSION['wx_user_id'];
            if ($user_id > 0)
            {
                $info = array(
                    'user_name' => '',
                    'user_hobby' => '',
                    'user_email' => '',
                    'user_period' => '',
                    'user_phone' => '',
                    'user_school' => '',
                    'user_school_id' => 0,
                    'user_major' => '',
                    'user_major_id' => 0
                    );
                $base_info = $this->CI->wxm_user->get_base_info($user_id);
                $school_info = $this->CI->wxm_user2carea->get_by_user_id($user_id);
                if ($base_info)
                {
                    $info['user_name'] = $base_info['user_name'];
                    $info['user_email'] = $base_info['user_email'];
                    $info['user_hobby'] = $base_info['user_hobby'];
                    $info['user_period'] = $base_info['user_period'];
                    $info['user_phone'] = $base_info['user_phone'];
                }
                if ($school_info)
                {
                    $area_info_school = $this->CI->wxm_category_area->get_all_info($school_info['carea_id_school']);
                    if ($area_info_school)
                    {
                        $info['user_school'] = $area_info_school->carea_name;
                        $info['user_school_id'] = $area_info_school->carea_id;
                    }

                    $area_info_major = $this->CI->wxm_category_area->get_all_info($school_info['carea_id_major']);
                    if ($area_info_major)
                    {
                        $info['user_major'] = $area_info_major->carea_name;
                        $info['user_major_id'] = $area_info_major->carea_id;
                    }
                }

                // echoxml($info);
                return $info;
            }
        }
    }
/*****************************************************************************/
    public function get_user_collect_list()
    {
        // get collect data info
        $collect_list = array();
        $cur_user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        if ($cur_user_id > 0) {
            $collect_info = $this->CI->wxm_user_activity->get_collect_data($cur_user_id);
            $collect_str = $collect_info['uactivity_collectdata'];
            if ($collect_str) {
                $collect_list = explode(',', $collect_str);
            }
        }
        return $collect_list;
    }
/*****************************************************************************/
    public function get_data_card($data_id = 0)
    {
        $cur_time = date('Y-m-d H:i:s');
        $yesterday_time = wx_get_yesterday_time();

        if ($data_id > 0) {
            $base_info = $this->CI->wxm_data->search_get_data_info($data_id);
            if ($base_info) {
                $user_id = $base_info['user_id'];
                $data_upload_time = $base_info['data_uploadtime'];
                $data_point = $base_info['data_point'];

                // check price
                $data_price = $base_info['data_price'];
                if ($data_price == '0.00') {
                    $base_info['data_price'] = '免费';
                }
                else {
                    $base_info['data_price'] = '￥'.$data_price;
                }

                // user nice name info
                $user_name_info = $this->CI->wxm_user->get_name_by_id($user_id);
                if ($user_name_info) {
                    $base_info['user_name'] = $user_name_info['user_name'];
                }
                else {
                    $base_info['user_name'] = 'unknown user';
                }

                // note download and view count info
                $down_view_info = $this->CI->wxm_data_activity->get_download_view_count($data_id);
                if ($down_view_info) {
                    $base_info['dactivity_download_count'] = $down_view_info['dactivity_download_count'];
                    $base_info['dactivity_view_count'] = $down_view_info['dactivity_view_count'];
                }
                else {
                    $base_info['dactivity_download_count'] = 0;
                    $base_info['dactivity_view_count'] = 0;
                }

                // 查找对应的area学校
                $base_info['data_area_id_school'] = 0;
                $base_info['data_area_name_school'] = '';
                $base_info['data_area_id_major'] = 0;
                $base_info['data_area_name_major'] = '';
                $area_info = $this->CI->wxm_data2carea->get_area_id($data_id);
                if ($area_info) {
                    // school info
                    $category_area_info = $this->CI->wxm_category_area->get_all_info($area_info->carea_id_school);
                    if ($category_area_info) {
                        $base_info['data_area_id_school'] = $category_area_info->carea_id;
                        $base_info['data_area_name_school'] = $category_area_info->carea_name;
                    }
                    // major info
                    $category_area_info = $this->CI->wxm_category_area->get_all_info($area_info->carea_id_major);
                    if ($category_area_info) {
                        $base_info['data_area_id_major'] = $category_area_info->carea_id;
                        $base_info['data_area_name_major'] = $category_area_info->carea_name;
                    }
                }

                // 查找对应的nature分类
                $base_info['data_nature_id'] = 0;
                $base_info['data_nature_name'] = '';
                $nature_info = $this->CI->wxm_data2cnature->get_nature_id($data_id);
                if ($nature_info) {
                    $category_nature_info = $this->CI->wxm_category_nature->get_name_by_id($nature_info->cnature_id);
                    if ($category_nature_info) {
                        $base_info['data_nature_id'] = $category_nature_info['cnature_id'];
                        $base_info['data_nature_name'] = $category_nature_info['cnature_name'];
                    }
                }

                // filter the upload time info
                if ($data_upload_time >= $yesterday_time) {
                    // show hours
                    $diff = strtotime($cur_time) - strtotime($data_upload_time);
                    $diff_time = floor($diff/3600);
                    if ($diff_time > 0) {
                        $base_info['data_uploadtime'] = $diff_time.'小时之前';
                    }
                    else {
                        $base_info['data_uploadtime'] = '1小时内';
                    }
                }
                else {
                    // show year/mouth/day
                    $time_list = explode(' ', $data_upload_time);
                    $base_info['data_uploadtime'] = $time_list[0];
                }

                // filter the data point, output stars
                if ($data_point < 20) {
                    $base_info['data_point'] = 1;
                }
                else if ($data_point < 40) {
                    $base_info['data_point'] = 2;
                }
                else if ($data_point < 60) {
                    $base_info['data_point'] = 3;
                }
                else if ($data_point < 80) {
                    $base_info['data_point'] = 4;
                }
                else {
                    $base_info['data_point'] = 5;
                }
            }
            // echoxml($base_info);
            return $base_info;
        }
        return false;
    }
/*****************************************************************************/
    public function add_extend_base_info($base_info = array())
    {
        $cur_time = date('Y-m-d H:i:s');
        $yesterday_time = wx_get_yesterday_time();

        if ($base_info) {
            $data_id = $base_info['data_id'];
            $user_id = $base_info['user_id'];
            $data_upload_time = $base_info['data_uploadtime'];
            $data_point = $base_info['data_point'];

            // check data price
            $data_price = $base_info['data_price'];
            if ($data_price == '0.00') {
                $base_info['data_price'] = '免费';
            }
            else {
                $base_info['data_price'] = '￥'.$data_price;
            }

            // user nice name info
            $user_name_info = $this->CI->wxm_user->get_name_by_id($user_id);
            if ($user_name_info) {
                $base_info['user_name'] = $user_name_info['user_name'];
            }
            else {
                $base_info['user_name'] = 'unknown user';
            }

            // note download and view count info
            $down_view_info = $this->CI->wxm_data_activity->get_download_view_count($data_id);
            if ($down_view_info) {
                $base_info['dactivity_download_count'] = $down_view_info['dactivity_download_count'];
                $base_info['dactivity_view_count'] = $down_view_info['dactivity_view_count'];
            }
            else {
                $base_info['dactivity_download_count'] = 0;
                $base_info['dactivity_view_count'] = 0;
            }

            // 查找对应的area学校
            $base_info['data_area_id_school'] = 0;
            $base_info['data_area_name_school'] = '';
            $base_info['data_area_id_major'] = 0;
            $base_info['data_area_name_major'] = '';
            $area_info = $this->CI->wxm_data2carea->get_area_id($data_id);
            if ($area_info) {
                // school info
                $category_area_info = $this->CI->wxm_category_area->get_all_info($area_info->carea_id_school);
                if ($category_area_info) {
                    $base_info['data_area_id_school'] = $category_area_info->carea_id;
                    $base_info['data_area_name_school'] = $category_area_info->carea_name;
                }
                // major info
                $category_area_info = $this->CI->wxm_category_area->get_all_info($area_info->carea_id_major);
                if ($category_area_info) {
                    $base_info['data_area_id_major'] = $category_area_info->carea_id;
                    $base_info['data_area_name_major'] = $category_area_info->carea_name;
                }
            }

            // 查找对应的nature分类
            $base_info['data_nature_id'] = 0;
            $base_info['data_nature_name'] = '';
            $nature_info = $this->CI->wxm_data2cnature->get_nature_id($data_id);
            if ($nature_info) {
                $category_nature_info = $this->CI->wxm_category_nature->get_name_by_id($nature_info->cnature_id);
                if ($category_nature_info) {
                    $base_info['data_nature_id'] = $category_nature_info['cnature_id'];
                    $base_info['data_nature_name'] = $category_nature_info['cnature_name'];
                }
            }

            // filter the upload time info
            if ($data_upload_time >= $yesterday_time) {
                // show hours
                $diff = strtotime($cur_time) - strtotime($data_upload_time);
                $diff_time = floor($diff/(60*60));
                if ($diff_time > 0) {
                    $base_info['data_uploadtime'] = $diff_time.'小时之前';
                }
                else {
                    $base_info['data_uploadtime'] = '1小时内';
                }
            }
            else {
                // show year/mouth/day
                $time_list = explode(' ', $data_upload_time);
                $base_info['data_uploadtime'] = $time_list[0];
            }

            // filter the data point, output stars
            if ($data_point < 20) {
                $base_info['data_point'] = 1;
            }
            else if ($data_point < 40) {
                $base_info['data_point'] = 2;
            }
            else if ($data_point < 60) {
                $base_info['data_point'] = 3;
            }
            else if ($data_point < 80) {
                $base_info['data_point'] = 4;
            }
            else {
                $base_info['data_point'] = 5;
            }
        }
        return $base_info;
    }
/*****************************************************************************/
    public function add_user_school_major($user_id = 0)
    {
        $data = array();

        if ($user_id > 0) {
            $school_info = $this->CI->wxm_user2carea->get_by_user_id($user_id);
            if ($school_info) {
                $area_info_school = $this->CI->wxm_category_area->get_all_info($school_info['carea_id_school']);
                if ($area_info_school)
                {
                    $data['user_school'] = $area_info_school->carea_name;
                }

                $area_info_major = $this->CI->wxm_category_area->get_all_info($school_info['carea_id_major']);
                if ($area_info_major)
                {
                    $data['user_major'] = $area_info_major->carea_name;
                }
            }
        }

        return $data;
    }
/*****************************************************************************/
    public function guess_you_like() {
        $cur_user_info = $this->CI->wx_util->get_user_session_info();
        $cur_user_id = $cur_user_info['user_id'];
        if ($cur_user_id > 0) {                     // aleady login
            // filter user's school and major notes
            $user_school_info = $this->CI->wxm_user2carea->get_by_user_id($cur_user_id);
            if ($user_school_info) {
                $major_id = $user_school_info['carea_id_major'];
                $school_id = $user_school_info['carea_id_school'];
            }

            // 首先，按照用户的学校筛选笔记资料，然后把与用户的专业相同的放到前面
            $data_id_list = array();
            $major_relate = array();
            $school_relate = array();

            $collect_data_list = $this->get_user_collect_list();

            $school_notes_50 = $this->CI->wxm_data2carea->get_by_school_50($school_id);
            if ($school_notes_50) {
                foreach ($school_notes_50 as $value) {
                    $data_id_list[] = $value['data_id'];
                }
            }

            if ($data_id_list) {
                foreach ($data_id_list as $data_id) {
                    $data_info = $this->get_data_card($data_id);
                    if ($data_info) {
                        // flag user collect note
                        if ($collect_data_list && in_array($data_id, $collect_data_list)) {
                            $data_info['collect'] = 'true';
                        }
                        else {
                            $data_info['collect'] = 'false';
                        }
                        // major notes order forword
                        if ($data_info['data_area_id_major'] == $major_id) {
                            $major_relate[] = $data_info;
                        }
                        else {
                            $school_relate[] = $data_info;
                        }
                    }
                }
            }

            // 在平台初期，数据比较少，可能找不到与用户学校专业相关的笔记资料
            // 那么，在查找地区相关的笔记资料
            if (! $major_relate && ! $school_relate) {
                // 首先，定位用户所在地区（省市地区），按照数据库中的地区（1级）
                // 找到对应的学校（2级），然后，根据笔记的排名（热门程度），选出
                // 并且是所在地区的所有学校的笔记资料
                $login_ip = $this->CI->wx_util->get_login_addr();
                $school_id_list = array();
                $result = array();

                if ($login_ip != 'unknow') {
                    $taobao_ip_info = $this->CI->wx_weibo_renren_api->get_taobao_ip_info($login_ip);
                    if ($taobao_ip_info) {
                        $region = $taobao_ip_info['data']['region'];
                        $login_region = str_replace(
                                            array('省','市','自治区','直辖市','特别行政区'),
                                            array('','','','',''),
                                            $region);  // like: 江苏、上海等
                        // filter all school in login region location
                        $school_area_id_info = $this->CI->wxm_category_area->get_all_school_by_region($login_region);
                        if ($school_area_id_info) {
                            foreach ($school_area_id_info as $key => $value) {
                                $school_id_list[] = $value['carea_id'];
                            }
                        }
                    }
                }

                if ($school_id_list) {
                    $hot_top_100_notes = $this->CI->wxm_data->hot_top_100();
                    if ($hot_top_100_notes) {
                        foreach ($hot_top_100_notes as $value) {
                            $data_info = $this->add_extend_base_info($value);
                            if ($data_info) {
                                $data_info['collect'] = 'false';
                                $data_area_id_school = $data_info['data_area_id_school'];
                                if ($data_area_id_school == 0  // 公共类的笔记资料
                                    || in_array($data_area_id_school, $school_id_list)) {
                                    $result[] = $data_info;
                                }
                            }
                        }
                    }
                }
                return $result;
            }
            else {
                return array_merge($major_relate, $school_relate);
            }
        }
        else {  // not login
            // 首先，定位用户所在地区（省市地区），按照数据库中的地区（1级）
            // 找到对应的学校（2级），然后，根据笔记的排名（热门程度），选出
            // 并且是所在地区的所有学校的笔记资料
            $login_ip = $this->CI->wx_util->get_login_addr();
            $school_id_list = array();
            $result = array();

            if ($login_ip != 'unknow') {
                $taobao_ip_info = $this->CI->wx_weibo_renren_api->get_taobao_ip_info($login_ip);
                if ($taobao_ip_info) {
                    $region = $taobao_ip_info['data']['region'];
                    $login_region = str_replace(
                                        array('省','市','自治区','直辖市','特别行政区'),
                                        array('','','','',''),
                                        $region);  // like: 江苏、上海等
                    // filter all school in login region location
                    $school_area_id_info = $this->CI->wxm_category_area->get_all_school_by_region($login_region);
                    if ($school_area_id_info) {
                        foreach ($school_area_id_info as $key => $value) {
                            $school_id_list[] = $value['carea_id'];
                        }
                    }
                }
            }

            if ($school_id_list) {
                $hot_top_100_notes = $this->CI->wxm_data->hot_top_100();
                if ($hot_top_100_notes) {
                    foreach ($hot_top_100_notes as $value) {
                        $data_info = $this->add_extend_base_info($value);
                        if ($data_info) {
                            $data_info['collect'] = 'false';
                            $data_area_id_school = $data_info['data_area_id_school'];
                            if ($data_area_id_school == 0  // 公共类的笔记资料
                                || in_array($data_area_id_school, $school_id_list)) {
                                $result[] = $data_info;
                            }
                        }
                    }
                }
            }
            return $result;
        }
    }
/*****************************************************************************/
}

/* End of file WX_General.php */
/* Location: ./application/libraries/wx_general.php */
