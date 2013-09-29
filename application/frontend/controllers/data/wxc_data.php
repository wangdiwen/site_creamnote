<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXC_Data extends CI_Controller
{
/*****************************************************************************/
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('file');
        $this->load->helper('download');
        $this->load->library('wx_data');            // Our library

        $this->load->model('core/wxm_data');
        $this->load->model('core/wxm_user');
        $this->load->model('core/wxm_user_activity');
        $this->load->model('share/wxm_category_area');
        $this->load->model('share/wxm_category_nature');
        $this->load->model('share/wxm_data2carea');
        $this->load->model('share/wxm_data2cnature');
        $this->load->model('share/wxm_grade');
        $this->load->model('share/wxm_data_activity');
        $this->load->model('core/wxm_comment');
        $this->load->model('core/wxm_follow');

        $this->load->library('wx_general');
        $this->load->library('wx_aliossapi');
        $this->load->library('wx_site_manager');
        $this->load->library('wx_util');
    }
/*****************************************************************************/
    // 资料详细页面
    public function data_detail()
    {
        $this->load->view('data/wxv_datadetail');
    }
/*****************************************************************************/
    public function data_modify($data_id = '')  // 完善资料信息
    {
    	// 获得资料属性分类中的一级分类，@1：考研资料，@2：考试，@3：学习笔记
    	$data = array();
        $data_info = $this->wxm_data->get_data_info($data_id);
        if ($data_info) {
            // check data is cur user or not ?
            $user_session_info = $this->wx_util->get_user_session_info();
            $cur_user_id = $user_session_info['user_id'];
            $data_user_id = $data_info->user_id;
            if ($cur_user_id != $data_user_id) {
                redirect('primary/wxc_home/page_404');  // 404 page
                return false;
            }
        }

        $data['first_nature'] = $this->wxm_category_nature->get_first_nature();
        if($data_info)
        {
            $data['data_id'] = $data_id;
            $data['data_name'] = $data_info->data_name;
            if ($data_info->data_type == 'pdf' && $data_info->data_status == 0) {
                $data['pdf_file'] = base_url().'upload/tmp/'.$data_info->data_objectname;
            }
            else {
                $data['pdf_file'] = '';
            }
            $data['data_status'] = $data_info->data_status;
            $data['data_objectname'] = $data_info->data_objectname;

            $data['base_user_info'] = $this->wx_general->get_user_base_info();

            $this->load->view('data/wxv_datamod', $data);
        }
    }
/*****************************************************************************/
    public function data_modify_from_image($data_objectname = '')
    {
        $data_id = $this->wxm_data->get_id_by_objectname($data_objectname);
        if ($data_id > 0) {
            // 获得资料属性分类中的一级分类，@1：考研资料，@2：考试，@3：学习笔记
            $data = array();
            $data['first_nature'] = $this->wxm_category_nature->get_first_nature();
            $data_info = $this->wxm_data->get_data_info($data_id);
            if($data_info)
            {
                $data['data_id'] = $data_id;
                $data['data_name'] = $data_info->data_name;
                if ($data_info->data_type == 'pdf' && $data_info->data_status == 0) {
                    $data['pdf_file'] = base_url().'upload/tmp/'.$data_info->data_objectname;
                }
                else {
                    $data['pdf_file'] = '';
                }
                $data['data_status'] = $data_info->data_status;
                $data['data_objectname'] = $data_info->data_objectname;
            }
            $data['base_user_info'] = $this->wx_general->get_user_base_info();

            $this->load->view('data/wxv_datamod', $data);
        }
    }
/*****************************************************************************/
    // 资料列表页面
    public function data_list()
    {
    	$data = array();
        // get collect data info
        $collect_list = array();
        $cur_user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        if ($cur_user_id > 0) {
            $collect_info = $this->wxm_user_activity->get_collect_data($cur_user_id);
            $collect_str = $collect_info['uactivity_collectdata'];
            if ($collect_str) {
                $collect_list = explode(',', $collect_str);
            }
        }

        $data_recommend = array();
        $top_ten = $this->wxm_data->latest_top_ten();
        if ($top_ten) {
            foreach ($top_ten as $obj) {
                $base_info = $this->wx_general->add_extend_base_info($obj);
                // check collect or not
                if ($collect_list && in_array($obj['data_id'], $collect_list)) {
                    $base_info['collect'] = 'true';
                }
                else {
                    $base_info['collect'] = 'false';
                }
                // merge data
                array_push($data_recommend, $base_info);
            }
        }

        $data_youlike = $this->wx_general->guess_you_like();

        // $data_search = array();
        $data['data_recommend'] = $data_recommend;
        $data['data_youlike'] = $data_youlike;
        // $data['data_search'] = $data_search;

    	$this->load->view('data/wxv_data', $data);
    }
/*****************************************************************************/
    // 通过学校名称，获得所有的院系信息
    public function get_depart_by_school()
    {
        $school = $this->input->post('wx_school');
        $ret = $this->wxm_category_area->get_depart_by_school($school);

        echo json_encode($ret);  // 将对象数组以json格式返回
    }
/*****************************************************************************/
    // 获得natrue分类的二级分类
    public function get_second_nature()
    {
        $cnature_id = $this->input->post('wx_nature');
        $ret = $this->wxm_category_nature->get_second_nature($cnature_id);

        echo json_encode($ret);
    }
/*****************************************************************************/
    // 获得natrue分类的三级分类
    public function get_third_nature()
    {
        $cnature_id = $this->input->post('wx_nature');
        $ret = $this->wxm_category_nature->get_third_nature($cnature_id);

        echo json_encode($ret);
    }
/*****************************************************************************/
    public function get_area_id_by_school_name(/*$school_name = '清华大学'*/)
    {
        $school_name = $this->input->post('school_name');
        if ($school_name)
        {
            $area_id = $this->wxm_category_area->get_id_by_name($school_name);
            if ($area_id)
            {
                echo $area_id;
                return;
            }
            else
            {
                echo '0';
            }
        }
    }
/*****************************************************************************/
    // 前台页面的action方法
    public function data_view($data_id = 0) {
        if (! ($data_id > 0)) {
            // echo "当前资料不可用！";
            redirect('primary/wxc_home/page_404');
            return false;
        }

        $data = array();
        $data_info = $this->wxm_data->get_data_info($data_id);
        if ($data_info)
        {
        	$data['data_id'] = $data_id;
            $data['data_name'] = $data_info->data_name;
            $data['data_summary'] = $data_info->data_summary;
            $data['data_objectname'] = $data_info->data_objectname;
            $data['data_type'] = $data_info->data_type;
            $data['data_pagecount'] = $data_info->data_pagecount;
            $data['data_status'] = $data_info->data_status;
            $data['data_preview'] = $data_info->data_preview;
            $data['user_id'] = $data_info->user_id;
            $data['data_uploadtime'] = $data_info->data_uploadtime;
            $data['data_vpspath'] = $data_info->data_vpspath;

            // 检测此份笔记资料的状态，如果正在审核、或者未审核通过，则不可以显示，到404
            // 但是，如果这份笔记是当前用户本人的话，还是可以浏览的
            // 并且，对于未登陆的用户，也同样重定向到404
            $cur_user_info = $this->wx_util->get_user_session_info();
            $cur_user_id = $cur_user_info['user_id'];
            if ($data['user_id'] != $cur_user_id) {
                if ($data['data_status'] != '3') {
                    redirect('primary/wxc_home/page_404');
                    return false;
                }
            }

            // 查找用户的名称
            $user_info = $this->wxm_user->user_info($data_info->user_id);
            $data['user_name'] = $user_info->user_name;

            // 判断资料的拥有者是否被关注了？
            $data['data_follow'] = '0';   // 未知状态
            $login_user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
            if ($login_user_id > 0)
            {
                if ($login_user_id == $data['user_id'])
                {
                    $data['data_follow'] = '3';  // 该资料是用户自己的
                }
                else
                {
                    $has_followed = $this->wxm_follow->has_followed($login_user_id, $data['user_id']);
                    if ($has_followed)
                    {
                        $data['data_follow'] = '2';  // 被关注了
                    }
                    else
                    {
                        $data['data_follow'] = '1';  // 没有被关注
                    }
                }
            }

            // 查找对应的nature分类
            $data['data_nature_id'] = '';
            $data['data_nature_name'] = '';
            $nature_info = $this->wxm_data2cnature->get_nature_id($data['data_id']);
            if ($nature_info) {
                $data['data_nature_id'] = $nature_info->cnature_id;
                $category_nature_info = $this->wxm_category_nature->get_all_info($data['data_nature_id']);
                $data['data_nature_name'] = $category_nature_info->cnature_name;
            }

            // 查找对应的area学校
            $area_info = $this->wxm_data2carea->get_area_id($data['data_id']);
            $data['data_area_id'] = '';
            $data['data_area_name'] = '';
            if ($area_info)
            {
                $data['data_area_id'] = $area_info->carea_id_school;
                $category_area_info = $this->wxm_category_area->get_all_info($data['data_area_id']);
                if ($category_area_info)
                {
                    $data['data_area_name'] = $category_area_info->carea_name;
                }
            }

            // get flash preview file path
            if ($data_info->data_preview == '1') {
                $flash_file = wx_get_filename($data['data_objectname']).'.swf';
                if ($data_info->data_vpspath) {             // local vps has flash file
                    $local_flash_path = "/upload/flash/";
                    $data['data_swfpath'] = $local_flash_path.$flash_file;
                }
                else {   // local vps has no flash file, need get from oss flash bucket
                    $oss_flash_bucket = 'wx-flash';
                    $data['data_swfpath'] = 'http://'.$oss_flash_bucket.'.oss.aliyuncs.com/'.$flash_file;
                }
            }
            else {
                $data['data_swfpath'] = '';
            }
        }

        // 增加浏览的次数
        if ($data_id > 0)
        {
            $view_info = $this->wxm_data_activity->get_by_data_id($data_id);
            if ($view_info)
            {
                $view_count = $view_info['dactivity_view_count'];
                $view_data = array(
                    'data_id' => $data_id,
                    'dactivity_view_count' => $view_count+1
                    );
                $this->wxm_data_activity->update_view($view_data);
            }
        }

        // 取得grade的数据，优秀、良好、不及格的count数
        $data['grade_excellent_count'] = 0;
        $data['grade_well_count'] = 0;
        $data['grade_bad_count'] = 0;
        $grade_data = $this->wxm_grade->get_by_data_id($data_id);
        if ($grade_data)
        {
            $data['grade_excellent_count'] = $grade_data['grade_excellent_count'];
            $data['grade_well_count'] = $grade_data['grade_well_count'];
            $data['grade_bad_count'] = $grade_data['grade_bad_count'];
        }
        // 取得评论数、下载数据、浏览数、购买数
        $data['dactivity_comment_count'] = 0;
        $data['dactivity_download_count'] = 0;
        $data['dactivity_view_count'] = 0;
        $data['dactivity_buy_count'] = 0;
        $data['dactivity_point_count'] = 0;
        $data['dactivity_examine_count'] = 0;
        $activity_data = $this->wxm_data_activity->get_by_data_id($data_id);
        if ($activity_data)
        {
            $data['dactivity_comment_count'] = $activity_data['dactivity_comment_count'];
            $data['dactivity_download_count'] = $activity_data['dactivity_download_count'];
            $data['dactivity_view_count'] = $activity_data['dactivity_view_count'];
            $data['dactivity_buy_count'] = $activity_data['dactivity_buy_count'];
            $data['dactivity_point_count'] = $activity_data['dactivity_point_count'];
            $data['dactivity_examine_count'] = $activity_data['dactivity_examine_count'];
        }
        // 取得评论的数据
        $data['data_comment'] = '';
        if ($data_id > 0)
        {
            $comment_info = $this->wxm_comment->get_by_data_id($data_id);
            if ($comment_info)
            {
                // Add gravator global header url
                foreach ($comment_info as $key => $value) {
                    $user_email = $value['user_email'];
                    $comment_time = substr($value['comment_time'], 0, 10);
                    $header_url = wx_get_gravatar_image($user_email, 25);

                    $comment_info[$key]['head_url'] = $header_url;
                    $comment_info[$key]['comment_time'] = $comment_time;
                }
                $data['data_comment'] = $comment_info;
            }
        }
        // 记录用户的浏览资料数据
        $data['data_recent_view'] = '';
        $login_user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        if ($login_user_id > 0)
        {
            $old_recent_view = $this->wxm_user_activity->get_recent_view($login_user_id);
            if ($old_recent_view)
            {
                $view_data = $old_recent_view['uactivity_recent_view'];
                if ($view_data)
                {
                    $data_id_list = explode(',', $view_data);
                    if ($data_id_list) {  // filter integer
                        foreach ($data_id_list as $key => $value) {
                            if (! is_numeric($value)) {
                                array_splice($data_id_list, $key, 1);
                            }
                        }
                    }
                    // 取得用户自己的资料id
                    $data_id_list_own = array();
                    $data_id_own_info = $this->wxm_data->get_data_id_list($login_user_id);
                    if ($data_id_own_info)
                    {
                        foreach ($data_id_own_info as $own)
                        {
                            array_push($data_id_list_own, $own['data_id']);
                        }
                    }
                    // 取得最近浏览的资料信息给页面使用
                    $ret = $this->wxm_data->get_data_by_id_list($data_id_list);
                    if ($ret)
                    {
                        $data['data_recent_view'] = $ret;
                    }

                    if (! in_array($data_id, $data_id_list) && ! in_array($data_id, $data_id_list_own))
                    {
                        if (count($data_id_list) >= 7)
                        {
                            array_splice($data_id_list, 0, 1);
                        }
                        $data_id_list[] = $data_id;

                        // 加入新的资料id到用户的浏览记录
                        $data_id_string = implode(',', $data_id_list);
                        $this->wxm_user_activity->update_recent_view($login_user_id, $data_id_string);
                    }
                }
                else
                {
                    // 取得用户自己的资料id
                    $data_id_list_own = array();
                    $data_id_own_info = $this->wxm_data->get_data_id_list($login_user_id);
                    if ($data_id_own_info)
                    {
                        foreach ($data_id_own_info as $own)
                        {
                            array_push($data_id_list_own, $own['data_id']);
                        }
                    }
                    if (! in_array($data_id, $data_id_list_own))
                    {
                        // 加入新的资料id到用户的浏览记录
                        $this->wxm_user_activity->update_recent_view($login_user_id, $data_id);
                    }
                }
            }
        }

        $this->load->view('data/wxv_datadetail', $data);
    }
/*****************************************************************************/
/*****************************************************************************/
    // 资料上传的第一步
    public function upload_file()
    {
        $upload_path = '/alidata/www/creamnote/upload/tmp/';     // 存放文件的绝对目录路径

        if (! empty($_FILES))
        {
            if ($_FILES["Filedata"]["error"] > 0)   // 错误
            {
                die('上传文件有错误！');
                return false;
            }
            else    // 正常
            {
                $tmp_file_name = $_FILES['Filedata']['tmp_name'];       // 临时文件名称
                $file_name = $_FILES["Filedata"]['name'];               // 文件原名
                $file_type = $_FILES["Filedata"]['type'];               // 文件类型
                $file_size = $_FILES["Filedata"]['size'];               // 文件大小

                // 限制单个文档的大小，不大于4M
                if ($file_size >= 4000000) {
                    echo 'file-size-overflow';
                    return false;
                }

                // 得到源文件的名称和后缀
                $name = wx_get_filename($file_name);
                $suffix = wx_get_suffix($file_name);
                // Time stamp
                $time_stamp = date("YmdHis", time());

                // 记录信息到数据库：资料id、资料name、资料objectname、资料用户id、时间戳、状态
                $random_code = rand(10, 99);
                $data_objectname = $time_stamp.$random_code;
                if ($suffix == 'wps') {  // here, support wps file
                    $data_objectname .= '.doc';
                }
                else {
                    $data_objectname .= '.'.$suffix;
                }

                $user_id = $_SESSION['wx_user_id'];                         // php SESSION
                $data_status = $this->wx_data->PUBLIC_UNFULL;
                $data_uploadtime = date('Y-m-d H:i:s');

                // 插入数据库，第一步
                $data = array();
                $data['data_name'] = $name;
                $data['data_objectname'] = $data_objectname;
                $data['data_type'] = $suffix;
                $data['data_status'] = $data_status;
                $data['user_id'] = $user_id;
                $data['data_uploadtime'] = $data_uploadtime;
                $data['data_vpspath'] = $upload_path;
                $this->wxm_data->set_data_info($data);      // 插入wx_data表

                // 获得资料的id？给前台资料详细页面使用
                $data_id = $this->wxm_data->get_id_by_objectname($data_objectname);
                // 更新wx_grade表
                $grade_info = array(
                    'data_id' => $data_id,
                    'grade_excellent_count' => 0,
                    'grade_well_count' => 0,
                    'grade_bad_count' => 0
                    );
                $this->wxm_grade->insert($grade_info);
                // 更新wx_data_activity表
                $data_activity = array(
                    'data_id' => $data_id,
                    'dactivity_comment_count' => 0,
                    'dactivity_download_count' => 0,
                    'dactivity_view_count' => 0,
                    'dactivity_buy_count' => 0,
                    'dactivity_point_count' => 0,
                    'dactivity_examine_count' => 0,
                    'dactivity_lifetime' => $data_uploadtime
                    );
                $this->wxm_data_activity->insert($data_activity);

                // record and update site note count
                $this->wx_site_manager->add_upload_note();

                // 将上传的临时文件，写入vps
                $save_file_name = $upload_path.$data_objectname;
                if (move_uploaded_file($tmp_file_name, $save_file_name))
                {
                    echo $data_id.','.$data_objectname.','.$suffix;
                }
                else
                {
                    die($file_name . "上传失败！");
                }
            }
        }
    }
/*****************************************************************************/
    // 资料上传的第二步
    public function upload_file_info()
    {
        $data_name = $this->input->post('data_name');
        $data_status = $this->input->post('data_status');           // 是否公开？
        $data_summary = $this->input->post('data_summary');
        $data_price = $this->input->post('data_price');
        $data_keyword = $this->input->post('data_keyword');
        $data_preview = $this->input->post('data_preview');
        $data_category_area_school = $this->input->post('data_category_area_school');
        $data_category_area_major = $this->input->post('data_category_area_major');
        $data_category_nature = $this->input->post('data_category_nature');
        $data_id = $this->input->post('data_id');
        $data_objectname = $this->input->post('data_objectname');
        $data_type = $this->input->post('data_type');

        // 根据category分类字段，查找到已经分类，以确定存储的分类目录路径
        $to_path_file = '';
        $category_nature_id = $this->wxm_category_nature->get_first_by_id($data_category_nature);
        if ($category_nature_id == '1')          // 考研资料
        {
            $to_path_file = '/alidata/www/creamnote/upload/graduate-exam/';
        }
        elseif ($category_nature_id == '2')      // 考试
        {
            $to_path_file = '/alidata/www/creamnote/upload/final-exam/';
        }
        elseif ($category_nature_id == '3')      // 学习笔记
        {
            $to_path_file = '/alidata/www/creamnote/upload/study-notes/';
        }
        else
        {
            die("There is no such data category nature id!");
        }

        // 更新指定的资料id的新信息，第二步：
        // loginfo($data_id);
        // loginfo($data_name);
        // loginfo($data_status);
        // loginfo($data_objectname);

        if ($data_name && $data_status && $data_id && $data_objectname)
        {
            // 更新data表
            $data = array(
                        'data_name' => $data_name,
                        'data_status' => $data_status,
                        'data_summary' => $data_summary,
                        'data_price' => $data_price,
                        'data_point' => 0,
                        'data_vpspath' => $to_path_file,
                        'data_keyword' => $data_keyword,
                        'data_preview' => $data_preview,
                        'data_id' => $data_id,
                        'data_uploadtime' => date('Y-m-d H:i:s'),
                        );
            $this->wxm_data->update_data_info($data);
            // 更新data2cnature表
            $data_nature = array(
                                'cnature_id' => $data_category_nature,
                                'data_id' => $data_id
                                );
            $this->wxm_data2cnature->insert($data_nature);
            // 更新wx_grade表，解决此接口同时被图片笔记第2步使用，没有初始化grade表数据的问题
            // 先进行wx_grade表数据的检查，如果没有则插入，有的话，就不需要插入了
            $has_grade_data_id = $this->wxm_grade->has_grade_data_record($data_id);
            if (! $has_grade_data_id) {
                $grade_info = array(
                    'data_id' => $data_id,
                    'grade_excellent_count' => 0,
                    'grade_well_count' => 0,
                    'grade_bad_count' => 0,
                    );
                $this->wxm_grade->insert($grade_info);
            }

            // 根据post数据是否有data_category_area字段，更新data2carea表
            if ($data_category_area_school || $data_category_area_major)
            {
        		$area_id_school = $this->wxm_category_area->get_id_by_name($data_category_area_school);

                $data_area = array(
                    'carea_id_school' => $area_id_school,
                    'carea_id_major' => $data_category_area_major,
                    'data_id' => $data_id
                    );
                $this->wxm_data2carea->insert($data_area);
            }

            echo 'success';  // ajax respose
            // 后台继续运行的代码
            fastcgi_finish_request();

            // 根据资料的status，进行转换flash文件操作，异步模式
            if (($data_status == '0' || $data_status == '1') && $data_preview == '1')
            {
                $this->_convert_to_flash($data_objectname, $to_path_file, $data_id, $data_type);
            }
        }
    }
/*****************************************************************************/
    public function record_data_page_count($tmp_pdf = '', $data_id = 0)
    {
        if (file_exists($tmp_pdf)) {
            $is_valid = 1;
            $cmd = '/usr/bin/pdfinfo '.$tmp_pdf.' >/dev/null 2>&1';
            $ret_info = system($cmd, $is_valid);
            if ($is_valid == 0) {
                $is_valid = 1;
                $out_info = array();
                $cmd = '/usr/bin/pdfinfo '.$tmp_pdf." | grep \"^Pages\" | awk '{ print $2 }'";
                $ret_info = exec($cmd, $out_info, $is_valid);
                if ($is_valid == 0 && $ret_info) {
                    if ($data_id > 0) {
                        // record page count to database
                        $this->wxm_data->update_data_pagecount($data_id, $ret_info);
                    }
                }
                else {
                    // 如果页数读取错误了，那么就自动填充一个页数，目前为6，因为6是个吉祥数字
                    if ($data_id > 0) {
                        $this->wxm_data->update_data_pagecount($data_id, '6');
                    }
                }
            }
            else {
                // 如果页数读取错误了，那么就自动填充一个页数，目前为6，因为6是个吉祥数字
                if ($data_id > 0) {
                    $this->wxm_data->update_data_pagecount($data_id, '6');
                }
            }
        }
    }
/*****************************************************************************/
    public function record_data_page_count_from_word($word_file = '', $data_type = 'doc', $data_id = 0) {
        if (file_exists($word_file)) {
            $is_valid = 1;
            $out_info = array();
            $cmd = '/bin/sh /alidata/server/creamnote/bin/creamnote_msoffice_pagecount.sh '.$word_file.' '.$data_type;
            $ret_info = exec($cmd, $out_info, $is_valid);
            if ($is_valid == 0 && $ret_info && is_numeric($ret_info) && $data_id > 0) {
                // record page count to database
                $this->wxm_data->update_data_pagecount($data_id, $ret_info);
            }
            else {
                // 如果页数读取错误了，那么就自动填充一个页数，目前为6，因为6是个吉祥数字
                if ($data_id > 0) {
                    $this->wxm_data->update_data_pagecount($data_id, '6');
                }
            }
        }
    }
/*****************************************************************************/
    // 将原文件转换为flash文件
    public function _convert_to_flash($data_objectname = '', $to_path = '', $data_id = 0, $data_type = '')
    {
        if ($data_objectname && $to_path)
        {
            $input_file = "/alidata/www/creamnote/upload/tmp/".$data_objectname;
            $tmp_file = "/alidata/www/creamnote/upload/tmp/".wx_get_filename($data_objectname).".pdf";
            $output_file = "/alidata/www/creamnote/upload/flash/".wx_get_filename($data_objectname).".swf";

            // Check the type of input file
            // The suffix like: doc, docx, ppt, pptx, wps, pdf
            $file_suffix = wx_get_suffix($input_file);
            $none_pdf_type = array('doc', 'docx', 'ppt', 'pptx', 'wps');
            if (in_array($file_suffix, $none_pdf_type))
            {
                // 将原文件转换为pdf临时文件
                $cmd = "/bin/sh /alidata/server/creamnote/bin/javaconvert.sh ".$input_file." ".$tmp_file." >/dev/null 2>&1";
                $status = 1;
                $ret = system($cmd, $status);
                if ($status != 0) {
                    return;
                }
                // 将pdf临时文件转换为swf格式的flash文件
                $cmd = "/bin/sh /alidata/server/creamnote/bin/pdf2swf.sh ".$tmp_file." ".$output_file." >/dev/null 2>&1";
                $ret = system($cmd, $status);
                if ($status != 0)
                {
                    return;
                }

                // 如果是doc，docx，那么还需要确认data_type，是否为wps，因为wps是直接改后缀为doc的，造成了
                // 使用POI接口误当成doc去读取页数信息了，这个地方要注意！
                // 其他的情况，从PDF文件中，获取该资料文档的页数信息
                if ($data_id > 0) {
                    if (in_array($file_suffix, array('doc','docx')) && $data_type != 'wps') {  // not wps
                        $this->record_data_page_count_from_word($input_file, $file_suffix, $data_id);
                    }
                    else {  // is a wps real, not a doc file
                        $this->record_data_page_count($tmp_file, $data_id);
                    }
                }

                // 清除临时pdf文件
                $ret = wx_delete_file($tmp_file);
            }
            elseif ($file_suffix == 'pdf')
            {
                // 直接把原文件转换为flash文件
                $cmd = "/bin/sh /alidata/server/creamnote/bin/pdf2swf.sh ".$input_file." ".$output_file." >/dev/null 2>&1";
                $ret = system($cmd, $status);
                if ($status != 0)
                {
                    return;
                }

                // 从PDF文件中，获取该资料文档的页数信息
                if ($data_id > 0) {
                    $this->record_data_page_count($input_file, $data_id);
                }
            }
            else
            {
                die("The type ".$file_suffix." of file is invalid.");
            }

            // 将原资料文件从临时目录转移到分类目录
            $cmd = "mv ".$input_file." ".$to_path." >/dev/null 2>&1";
            $ret = system($cmd, $status);
        }
    }
/*****************************************************************************/
    public function convert_flash($data_id = 0, $file_name = '', $in_dir = '', $out_dir = '')
    {
        $tmp_dir = '/alidata/www/creamnote/upload/tmp/';
        if ($file_name && $in_dir && $out_dir)
        {
            $name = wx_get_filename($file_name);
            $file_type = wx_get_suffix($file_name);
            if ($file_type == 'pdf')
            {
                $input_file = $in_dir.$file_name;
                $output_file = $out_dir.$name.'.swf';
                $cmd = "/bin/sh /alidata/server/creamnote/bin/pdf2swf.sh ".$input_file." ".$output_file." >/dev/null 2>&1";
                $status = 1;
                $ret = system($cmd, $status);
                // if ($status == 0)
                // {
                //     loginfo('转换pdf->swf，成功');
                // }
                // 从PDF文件中，获取该资料文档的页数信息
                if ($data_id > 0) {
                    $this->record_data_page_count($input_file, $data_id);
                }
            }
            else
            {
                $input_file = $in_dir.$file_name;
                $tmp_file = $tmp_dir.$name.'.pdf';
                $cmd = "/bin/sh /alidata/server/creamnote/bin/javaconvert.sh ".$input_file." ".$tmp_file." >/dev/null 2>&1";
                $status = 1;
                $ret = system($cmd, $status);
                // if ($status == 0)
                // {
                //     loginfo('转换 -> pdf 临时文件，成功');
                // }
                // 将pdf临时文件转换为swf格式的flash文件
                $output_file = $out_dir.$name.'.swf';
                $cmd = "/bin/sh /alidata/server/creamnote/bin/pdf2swf.sh ".$tmp_file." ".$output_file." >/dev/null 2>&1";
                $ret = system($cmd, $status);
                // if ($status == 0)
                // {
                //     loginfo('pdf 临时文件 -> swf，成功');
                // }
                // 从PDF文件中，获取该资料文档的页数信息
                if ($data_id > 0) {
                    $this->record_data_page_count($tmp_file, $data_id);
                }
                // 清除临时pdf文件
                $ret = wx_delete_file($tmp_file);
                // if ($ret == 0)
                // {
                //     loginfo('清除临时pdf文件，成功');
                // }
            }
            return true;
        }
        return false;
    }
/*****************************************************************************/
/*****************************************************************************/
    public function complete_data_info()
    {
        $data_id = $this->input->post('data_id');
        $data_name = $this->input->post('data_name');
        $data_status = $this->input->post('data_status');  // 是否公开
        $data_summary = $this->input->post('data_summary');
        $data_price = $this->input->post('data_price');
        $data_preview = $this->input->post('data_preview');  // 是否支持在线预览, 统一为支持=1
        $data_keyword = $this->input->post('data_keyword');
        $data_category_nature = $this->input->post('data_category_nature');
        $data_category_area_school = $this->input->post('data_category_area_school');
        $data_category_area_major = $this->input->post('data_category_area_major');

        // 查看资料的nature分类，以确定是否要进行文件的目录转移？
        $data_objectname = '';
        $data_osspath = '';
        $data_vpspath = '';
        $storage_info = $this->wxm_data->get_storage_path($data_id);
        if ($storage_info) {
            $data_objectname = $storage_info['data_objectname'];
            $data_osspath = $storage_info['data_osspath'];
            $data_vpspath = $storage_info['data_vpspath'];
        }

        // diff 2 nature category
        $old_first_nature_id = 0;
        $cur_first_nature_id = 0;

        $old_nature_info = $this->wxm_data2cnature->get_nature_id($data_id);
        if ($old_nature_info) {
            $old_nature_id = $old_nature_info->cnature_id;
            $old_first_nature_id = $this->wxm_category_nature->get_first_by_id($old_nature_id);
        }
        $cur_first_nature_id = $this->wxm_category_nature->get_first_by_id($data_category_nature);

        if ($old_first_nature_id != $cur_first_nature_id) {
            // here, need to move data
            $new_bucket = '';
            $to_local_path = '/alidata/www/creamnote/upload/';

            if ($cur_first_nature_id == '1') {
                $to_local_path = $to_local_path.'graduate-exam/';
                $new_bucket = 'wx-graduate-exam';
            }
            elseif ($cur_first_nature_id == '2') {
                $to_local_path = $to_local_path.'final-exam/';
                $new_bucket = 'wx-final-exam';
            }
            elseif ($cur_first_nature_id == '3') {
                $to_local_path = $to_local_path.'study-notes/';
                $new_bucket = 'wx-study-notes';
            }
            else {
                die('local nature category wrong, exit');
            }

            // 1. move local data
            if ($data_vpspath) {
                $from_local_file = $data_vpspath.$data_objectname;

                // update the vps path record
                $data_vpspath = $to_local_path;
                // invoke helper func
                $ret = wx_move_file($from_local_file, $to_local_path);
            }

            // 2. move oss data
            if ($data_osspath && $new_bucket && $data_osspath != $new_bucket) {
                // copy to new nature category path
                // del the old oss data
                $from_bucket = $data_osspath;
                $to_bucket = $new_bucket;
                $object = $data_objectname;
                // here, change the oss path
                $data_osspath = $new_bucket;

                if ($from_bucket && $to_bucket && $object) {
                    $ret = $this->wx_aliossapi->copy_object($from_bucket, $object, $to_bucket, $object);
                    $ret = $this->wx_aliossapi->delete_object($from_bucket, $object);
                }
            }
        }

        echo 'success';  // ajax value
        fastcgi_finish_request();

        // 更新数据库
        $data = array(
            'data_id' => $data_id,
            'data_name' => $data_name,
            'data_status' => $data_status,
            'data_summary' => $data_summary,
            'data_price' => $data_price,
            'data_preview' => $data_preview,
            'data_keyword' => $data_keyword,
            'data_osspath' => $data_osspath,
            'data_vpspath' => $data_vpspath
            );
        $this->wxm_data->complete_data_info($data);
        $data_nature = array(
            'data_id' => $data_id,
            'nature_id' => $data_category_nature
            );
        $this->wxm_data2cnature->update($data_nature);

        $has_area_id = $this->wxm_data2carea->get_area_id($data_id);
        if ($has_area_id) {
            if ($data_category_area_school)
            {
                $area_id_school = $this->wxm_category_area->get_id_by_name($data_category_area_school);
                $data_area = array(
                    'data_id' => $data_id,
                    'carea_id_school' => $area_id_school,
                    'carea_id_major' => $data_category_area_major
                    );
                $this->wxm_data2carea->update($data_area);
            }
            else {
                // del this area record
                $this->wxm_data2carea->delete_by_data_id($data_id);
            }
        }
        else {
            if ($data_category_area_school)
            {
                $area_id_school = $this->wxm_category_area->get_id_by_name($data_category_area_school);
                $data_area = array(
                    'data_id' => $data_id,
                    'carea_id_school' => $area_id_school,
                    'carea_id_major' => $data_category_area_major
                    );
                $this->wxm_data2carea->insert($data_area);
            }
        }
    }
/*****************************************************************************/
    public function complete_data_page($data_id = 0)
    {
        $data_info = $this->get_data_all_info($data_id);
        if (isset($data_info['is_valid']) && $data_info['is_valid'] == 'false') {
            redirect('primary/wxc_home/page_404');  // 404 page
            return false;
        }

        $data = array();
        $data['data_info'] = $data_info;
        $data['base_user_info'] = $this->wx_general->get_user_base_info();
        $this->load->view('data/wxv_updatedata', $data);
    }
/*****************************************************************************/
    public function get_data_all_info($data_id = 0)
    {
        if ($data_id > 0)
        {
            $data_info = array(
                'data_base' => '',
                'data_area' => '',
                'data_nature' => '',
                'is_valid' => '',
                );
            $info = $this->wxm_data->get_data_all_info($data_id);
            // check this data is cur user ?
            if ($info) {
                $user_session_info = $this->wx_util->get_user_session_info();
                $data_owner_id = $info['user_id'];
                if ($data_owner_id != $user_session_info['user_id']) {
                    $data_info['is_valid'] = 'false';
                    return $data_info;
                }
            }

            $data_info['data_base'] = $info;
            $data_info['data_nature'] = array(
                'one' => array('nature_id' => 0, 'nature_name' => ''),
                'two' => array('nature_id' => 0, 'nature_name' => ''),
                'three' => array('nature_id' => 0, 'nature_name' => '')
                );
            $data_info['data_area'] = array(
                'school' => array('area_id' => 0, 'area_name' => ''),
                'major' => array('area_id' => 0, 'area_name' => '')
                );

            // nature info
            $nature_info = $this->wxm_data2cnature->get_nature_id($data_id);
            $nature_id = 0;
            if ($nature_info)
            {
                $nature_id = $nature_info->cnature_id;
            }
            $category_nature = $this->wxm_category_nature->get_all_info($nature_id);
            if ($category_nature)
            {
                $nature_name = $category_nature->cnature_name;
                $nature_grade = $category_nature->cnature_grade;
                $nature_flag = $category_nature->cnature_flag;
                if ($nature_grade == '2')
                {
                    $data_info['data_nature']['two']['nature_id'] = $nature_id;
                    $data_info['data_nature']['two']['nature_name'] = $nature_name;

                    $nature_first_flag = $nature_flag[0];
                    $nature_first_info = $this->wxm_category_nature->get_by_flag($nature_first_flag);
                    if ($nature_first_info)
                    {
                        $data_info['data_nature']['one']['nature_id'] = $nature_first_info['cnature_id'];
                        $data_info['data_nature']['one']['nature_name'] = $nature_first_info['cnature_name'];
                    }
                }
                elseif ($nature_grade == '3')
                {
                    $data_info['data_nature']['three']['nature_id'] = $nature_id;
                    $data_info['data_nature']['three']['nature_name'] = $nature_name;

                    $nature_sec_flag = substr($nature_flag, 0, 3);
                    $nature_sec_info = $this->wxm_category_nature->get_by_flag($nature_sec_flag);
                    if ($nature_sec_info)
                    {
                        $data_info['data_nature']['two']['nature_id'] = $nature_sec_info['cnature_id'];
                        $data_info['data_nature']['two']['nature_name'] = $nature_sec_info['cnature_name'];

                        $nature_first_flag = substr($nature_sec_flag, 0, 1);
                        $nature_first_info = $this->wxm_category_nature->get_by_flag($nature_first_flag);
                        if ($nature_first_info)
                        {
                            $data_info['data_nature']['one']['nature_id'] = $nature_first_info['cnature_id'];
                            $data_info['data_nature']['one']['nature_name'] = $nature_first_info['cnature_name'];
                        }
                    }
                }
            }

            // area info
            $area_info = $this->wxm_data2carea->get_area_id($data_id);
            if ($area_info)
            {
                $school_id = $area_info->carea_id_school;
                $major_id = $area_info->carea_id_major;
                $school_info = $this->wxm_category_area->get_all_info($school_id);
                if ($school_info) {
                    $data_info['data_area']['school']['area_id'] = $school_id;
                    $data_info['data_area']['school']['area_name'] = $school_info->carea_name;
                }
                $major_info = $this->wxm_category_area->get_all_info($major_id);
                if ($major_info) {
                    $data_info['data_area']['major']['area_id'] = $major_id;
                    $data_info['data_area']['major']['area_name'] = $major_info->carea_name;
                }
            }

            //echoxml($data_info);
            return $data_info;
        }
    }
/*****************************************************************************/
    public function delete_data()
    {
        $data_id = $this->input->post('delete_data_id');

        if ($data_id && is_numeric($data_id) && $data_id > 0) {
            // database table: 'wx_data','wx_data2carea','wx_data2cnature',
            // 'wx_data_activity','wx_grade','wx_comment'

            // first, get oss data info
            $oss_info = $this->wxm_data->get_oss_info($data_id);
            // check cur user login, and check this data is cur user or not?
            $cur_user_info = $this->wx_util->get_user_session_info();
            $cur_user_id = $cur_user_info['user_id'];
            if (! $cur_user_id > 0
                || ! $oss_info
                || $cur_user_id != $oss_info['user_id']) {
                redirect('static/wxc_direct/sys_error');
                return false;
            }

            // second, delete db info
            $this->wxm_data->delete_data_by_id($data_id);
            $this->wxm_data2carea->delete_by_data_id($data_id);
            $this->wxm_data2cnature->delete_by_data_id($data_id);
            $this->wxm_data_activity->delete_by_data_id($data_id);
            $this->wxm_grade->delete_by_data_id($data_id);
            $this->wxm_comment->delete_by_data_id($data_id);

            echo 'success';  // ajax value
            fastcgi_finish_request();

            // third, delete the data from aliyun OSS, or loacl web site
            if ($oss_info) {
                $oss_bucket = $oss_info['data_osspath'];
                $object = $oss_info['data_objectname'];
                $vps_path = $oss_info['data_vpspath'];

                $object_name = wx_get_filename($object);
                $flash_file = $object_name.'.swf';
                $flash_bucket = 'wx-flash';

                if ($oss_bucket) {
                    $ret = $this->wx_aliossapi->delete_object($oss_bucket, $object);
                    $ret = $this->wx_aliossapi->delete_object($flash_bucket, $flash_file);
                }

                if ($vps_path) {
                    $local_file = $vps_path.$object;
                    $local_flash_path = '/alidata/www/creamnote/upload/flash/';
                    $local_flash = $local_flash_path.$object_name.'.swf';

                    $ret = wx_delete_file($local_file);
                    $ret = wx_delete_file($local_flash);
                }
            }
        }
        else {
            redirect('static/wxc_direct/sys_error');
            return false;
        }
    }
/*****************************************************************************/
}


/* End of file wxc_data.php */
/* Location: /application/controllers/frontend/data/wxc_data.php */
