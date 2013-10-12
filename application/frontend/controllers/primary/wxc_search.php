<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WXC_Search extends CI_Controller
{
/*****************************************************************************/
    public function __construct()
    {
        parent::__construct();

        $this->load->model('core/wxm_data');  // 加载资料表
        $this->load->model('share/wxm_data2carea');
        $this->load->model('share/wxm_data2cnature');
        $this->load->model('share/wxm_category_area');
        $this->load->model('share/wxm_category_nature');
        $this->load->model('core/wxm_user2carea');
        $this->load->model('core/wxm_user_activity');

        $this->load->library('wx_general');
        $this->load->library('wx_weibo_renren_api');
    }
/*****************************************************************************/
    public function search_by_user()
    {
    	$user_id = $this->input->post('user_id');
        if ($user_id > 0)
        {
            $data = array();
            // echoxml($data);
            $data = $this->wxm_data->search_user_data_info($user_id);
            $json_data = json_encode($data);
            echo $json_data;
        }

        return false;
    }
/*****************************************************************************/
    public function search_by_area($area_id = 0)
    {
        if ($area_id > 0) {
            $data_info = array();
            $data_info['data_search'] = array();
            // get collect data info
            $collect_list = $this->wx_general->get_user_collect_list();
            $data = $this->wxm_data2carea->get_data_id_by_school($area_id);
            // wx_echoxml($data);
            if ($data) {
                $data_obj = array();
                foreach ($data as $row) {
                    $obj = $this->wx_general->get_data_card($row['data_id']);
                    if ($obj) {
                        if ($collect_list && in_array($row['data_id'], $collect_list)) {
                            $obj['collect'] = 'true';
                        }
                        else {
                            $obj['collect'] = 'false';
                        }
                        $data_obj[] = $obj;
                    }
                }
                $data_info['data_search'] = $data_obj;
            }
            $this->load->view('data/wxv_data', $data_info);
        }
        return false;
    }
/*****************************************************************************/
    public function search_by_nature($nature_id = 0)
    {
        if ($nature_id > 0)
        {
            // get collect data info
            $collect_list = $this->wx_general->get_user_collect_list();

            $data = $this->wxm_data2cnature->get_data_id($nature_id);
            // echoxml($data);
            if ($data)
            {
                $data_obj = array();
                foreach ($data as $row)
                {
                    $obj = $this->wx_general->get_data_card($row->data_id);
                    if ($obj)
                    {
                        if ($collect_list && in_array($row->data_id, $collect_list)) {
                            $obj['collect'] = 'true';
                        }
                        else {
                            $obj['collect'] = 'false';
                        }
                        array_push($data_obj, $obj);
                    }
                }

                // echoxml($data_obj);
                $data_info['data_search'] = $data_obj;
                $this->load->view('data/wxv_data', $data_info);
            }
        }

        return false;
    }
/*****************************************************************************/
    public function search_by_semester($semester = '')
    {
        // Todo...
    }
/*****************************************************************************/
    public function gen_search_by_area_id($area_name = '', $area_id = 0)
    {
        $data = array();
        // get collect data info
        $collect_list = $this->wx_general->get_user_collect_list();

        // 2种情况，area name一定有，而area id 不一定有？
        if ($area_name)
        {
            if (! $area_id)     // area id 没有
            {
                // 第一步，先查资料关联学校 id 的数据
                $school_id = $this->wxm_category_area->get_id_by_name($area_name);
                wx_loginfo('school_name = '.$area_name);
                wx_loginfo('school_id   = '.$school_id);
                if ($school_id)
                {
                    $data_id_list = $this->wxm_data2carea->get_data_id_by_school($school_id);
                    if ($data_id_list)
                    {
                        foreach ($data_id_list as $key => $value)
                        {
                            $data_info = $this->wx_general->get_data_card($value['data_id']);
                            if ($data_info)
                            {
                                if ($collect_list && in_array($value['data_id'], $collect_list)) {
                                    $data_info['collect'] = 'true';
                                }
                                else {
                                    $data_info['collect'] = 'false';
                                }
                                $data[] = $data_info;
                            }
                        }
                    }
                }
            }
            else                // area id 有
            {
                if ($area_id > 0)
                {
                    $data_id_list = $this->wxm_data2carea->get_data_id_by_major($area_id);
                    if ($data_id_list)
                    {
                        foreach ($data_id_list as $data_id)
                        {
                            $data_info = $this->wx_general->get_data_card($data_id->data_id);
                            if ($data_info)
                            {
                                if ($collect_list && in_array($data_id->data_id, $collect_list)) {
                                    $data_info['collect'] = 'true';
                                }
                                else {
                                    $data_info['collect'] = 'false';
                                }
                                $data[] = $data_info;
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }
/*****************************************************************************/
    public function gen_search()
    {
        // Note: This function is a general search,
        //       via category of 'school name', 'major id', 'nature id'.

        $school_name = $this->input->post('school_name');
        $major_id = $this->input->post('major_id');
        $nature_id = $this->input->post('nature_id');
        // $school_name = '清华大学';  // test

        // three conditions
        // 1. only by 'school name' and 'major id'
        // 2. only by 'nature id'
        // 3. both by 'school name' and 'nature id'
        if ($school_name && $nature_id) {
            $data_obj_list = $this->gen_search_area_nature($school_name, $major_id, $nature_id);
            echo json_encode($data_obj_list);
            return true;
        }
        elseif (! $school_name && $nature_id) {
            $data_obj_list = $this->gen_search_by_nature_id($nature_id);
            echo json_encode($data_obj_list);
            return true;
        }
        elseif ($school_name && ! $nature_id) {
            $data_obj_list = $this->gen_search_by_area_id($school_name, $major_id);
            echo json_encode($data_obj_list);
            return true;
        }
    }
/*****************************************************************************/
    public function gen_search_area_nature($school_name = '', $major_id = 0, $nature_id = 0)
    {
        if ($school_name && $nature_id) {
            // nature category is first priority, then area category.
            // search by nature id, get the set of nature.
            $school_id = $this->wxm_category_area->get_id_by_name($school_name);
            $nature_set = $this->gen_search_by_nature_id($nature_id);

            if ($nature_set) {
                if (! $major_id) {  // 没有专业id
                    // 没有专业id的信息，检索学校下面包含的所有专业的id
                    $filter_list = array();
                    $major_id_list = $this->wxm_category_area->get_depart_by_school($school_name);
                    if ($major_id_list) {
                        foreach ($major_id_list as $key => $value) {
                            $filter_list[] = $value['carea_id'];  // major id
                        }
                    }
                    foreach ($nature_set as $key => $value) {
                        if (! in_array($value['data_area_id_major'], $filter_list)) {
                            unset($nature_set[$key]);
                        }
                    }
                }
                else {  // 有专业id，忽略学校的id
                    foreach ($nature_set as $key => $value) {
                        $data_area_id_major = $value['data_area_id_major'];
                        if ($data_area_id_major != $major_id) {
                            unset($nature_set[$key]);
                        }
                    }
                }
            }
            return $nature_set;
        }
    }
/*****************************************************************************/
    public function gen_search_by_nature_id($nature_id = 0)
    {
        if ($nature_id > 0)
        {
            $data_obj = array();
            // get collect data info
            $collect_list = $this->wx_general->get_user_collect_list();

            $nature_info = $this->wxm_category_nature->get_all_info($nature_id);
            $nature_grade = $nature_info->cnature_grade;
            // 一级分类不做实时搜索
            if ($nature_grade == 2)
            {
                // 先直接搜索等级为2的分类
                $nature_id = $nature_info->cnature_id;
                $data_id_list = $this->wxm_data2cnature->get_data_id($nature_id);
                if ($data_id_list)
                {
                    foreach ($data_id_list as $data_id) {
                        $data_info = $this->wx_general->get_data_card($data_id->data_id);
                        if ($data_info)
                        {
                            if ($collect_list && in_array($data_id->data_id, $collect_list)) {
                                $data_info['collect'] = 'true';
                            }
                            else {
                                $data_info['collect'] = 'false';
                            }
                            // array_push($data_obj, $data_info);
                            $data_obj[] = $data_info;
                        }
                    }
                }

                // 然后查询2级下面的3级分类对应的文档资料
                $third_nature_list = $this->wxm_category_nature->get_third_nature($nature_info->cnature_id);
                if ($third_nature_list)
                {
                    foreach ($third_nature_list as $third_nature)
                    {
                        $third_nature_id = $third_nature->cnature_id;
                        $third_data_id_list = $this->wxm_data2cnature->get_data_id($third_nature_id);
                        if ($third_data_id_list)
                        {
                            foreach ($third_data_id_list as $third_data_id)
                            {
                                $data_info = $this->wx_general->get_data_card($third_data_id->data_id);
                                if ($data_info)
                                {
                                    if ($collect_list && in_array($third_data_id->data_id, $collect_list)) {
                                        $data_info['collect'] = 'true';
                                    }
                                    else {
                                        $data_info['collect'] = 'false';
                                    }
                                    // array_push($data_obj, $data_info);
                                    $data_obj[] = $data_info;
                                }
                            }
                        }
                    }
                }
            }
            elseif ($nature_grade == 3)
            {
                $nature_id = $nature_info->cnature_id;
                $data_id_list = $this->wxm_data2cnature->get_data_id($nature_id);

                $data_obj = array();
                if ($data_id_list)
                {
                    foreach ($data_id_list as $data_id)
                    {
                        $data_info = $this->wx_general->get_data_card($data_id->data_id);
                        if ($data_info)
                        {
                            if ($collect_list && in_array($data_id->data_id, $collect_list)) {
                                $data_info['collect'] = 'true';
                            }
                            else {
                                $data_info['collect'] = 'false';
                            }
                            // array_push($data_obj, $data_info);
                            $data_obj[] = $data_info;
                        }
                    }
                }
            }

            return $data_obj;
        }
    }
/*****************************************************************************/
/*****************************************************************************/
    // 公用搜索，支持用户输入任何可搜索的关键词
    // 平台进行模糊匹配
    // 关键词的处理说明：
    // 1. 文档名称：        10字 <=   doc-name     <=  20字
    // 2. 关键词  ：         2字 <=   doc-keyword  <=  10字，输入以空格分隔
    // 3. 分类    ：         2字 <=   doc-category <=  5字
    // 4. 学校、院系、专业：          doc-area         使用data_area数据表进行匹配

    // 注：1、2属于一起，1/2、3、4 作为搜索的同一级
/*****************************************************************************/
    public function public_search()
    {
        $input = $this->input->post('search');

        // first, use zh word segment
        $four_classes = $this->_extract_keyword_user_word_segment($input);
        if (! $four_classes) {  // if word segment failed
            // not use word segment
            $four_classes = $this->_extract_keyword($input);
        }
        $data_search = $this->_search_by_four_classes($four_classes);

        // get collect notes
        $collect_list = $this->wx_general->get_user_collect_list();
        // get recommend notes
        $data_recommend = array();
        $top_ten = $this->wxm_data->latest_top_ten();
        if ($top_ten) {
            foreach ($top_ten as $obj) {
                $base_info = $this->wx_general->add_extend_base_info($obj);
                if ($collect_list && in_array($obj['data_id'], $collect_list)) {
                    $base_info['collect'] = 'true';
                }
                else {
                    $base_info['collect'] = 'false';
                }
                array_push($data_recommend, $base_info);
            }
        }
        // get user like notes
        $data_youlike = $this->wx_general->guess_you_like();

        $data = array();
        $data['data_recommend'] = $data_recommend;
        $data['data_youlike'] = $data_youlike;
        $data['data_search'] = $data_search;

        $this->load->view('data/wxv_data', $data);
    }
/*****************************************************************************/
    public function _search_by_four_classes($four_classes)      // 公用搜索的4中分类方式
    {
        if (! $four_classes) {  // check invalid
            return array();
        }

        $result = array();
        $search_data_id_list = array();

        $name_list = $four_classes['doc-name'];
        $keyword_list = $four_classes['doc-keyword'];
        $category_list = $four_classes['doc-category'];
        $area_list = $four_classes['doc-area'];

        // search by category
        $data_id_list_nature = array();
        if ($category_list)
        {
            foreach ($category_list as $category)
            {
                $category_info = $this->wxm_category_nature->search_by_name_like($category);
                if ($category_info)
                {
                    foreach ($category_info as $info)
                    {
                        if ($info)
                        {
                            $nature_id = $info->cnature_id;
                            $data_info = $this->wxm_data2cnature->get_data_id($nature_id);
                            if ($data_info)
                            {
                                foreach ($data_info as $data)
                                {
                                    array_push($data_id_list_nature, $data->data_id);
                                }
                            }
                        }
                    }
                }
            }
            if ($data_id_list_nature) {
                $data_id_list_nature = array_unique($data_id_list_nature);
            }
        }

        // search by area
        $data_id_list_area = array();
        if ($area_list)
        {
            foreach ($area_list as $area)
            {
                $data_info = $this->wxm_data2carea->get_data_id($area);
                if ($data_info)
                {
                    foreach ($data_info as $data)
                    {
                        array_push($data_id_list_area, $data->data_id);
                    }
                }
            }
            if ($data_id_list_area) {
                $data_id_list_area = array_unique($data_id_list_area);
            }
        }

        $ret_name = array();
        $ret_by_name = $this->wxm_data->search_by_name_like($name_list, $data_id_list_area, $data_id_list_nature);
        if ($ret_by_name) {
            foreach ($ret_by_name as $key => $value) {
                $ret_name[] = $value['data_id'];
            }
        }
        // wx_echoxml($ret_name);

        $ret_keyword = array();
        $ret_by_keyword = $this->wxm_data->search_by_keyword_like($keyword_list, $data_id_list_area, $data_id_list_nature);
        if ($ret_by_keyword) {
            foreach ($ret_by_keyword as $key => $value) {
                $ret_keyword[] = $value['data_id'];
            }
        }
        // wx_echoxml($ret_keyword);

        // get collect data info
        $collect_list = $this->wx_general->get_user_collect_list();
        $search_data_id_list_tmp = array_merge($ret_name, $ret_keyword, $data_id_list_area, $data_id_list_nature);
        $search_data_id_list = array_filter(array_unique($search_data_id_list_tmp));
        $data_baseinfo_list = $this->wxm_data->search_get_baseinfo_by_id_list($search_data_id_list);
        if ($data_baseinfo_list) {
            foreach ($data_baseinfo_list as $key => $value) {
                $base_obj = $this->wx_general->add_extend_base_info($value);
                if ($base_obj) {
                    if ($collect_list && in_array($value['data_id'], $collect_list)) {
                        $base_obj['collect'] = 'true';
                    }
                    else {
                        $base_obj['collect'] = 'false';
                    }
                    $result[] = $base_obj;
                }
            }
        }
        return $result;
    }
/*****************************************************************************/
    public function _merge_result($a, $b)  // a/b is array obj
    {
        if (! $a)   // a->null
        {
            if (! $b)   // b->null
            {
                return $a;
            }
            else        // b->not null
            {
                return $b;
            }
        }
        else        // a->not null
        {
            if (! $b)   // b->null
            {
                return $a;
            }
            else        // b->not null
            {
                $c = array();
                $a_len = count($a);
                $b_len = count($b);
                if ($a_len > $b_len)
                {
                    foreach ($a as $a_key => $a_obj)
                    {
                        foreach ($b as $b_key => $b_obj)
                        {
                            if ($b_obj['data_id'] == $a_obj['data_id'])
                            {
                                array_push($c, $a_obj);
                                array_splice($b, $b_key, 1);
                                array_splice($a, $a_key, 1);
                            }
                        }
                    }
                }
                else
                {
                    foreach ($b as $b_key => $b_obj)
                    {
                        foreach ($a as $a_key => $a_obj)
                        {
                            if ($a_obj['data_id'] == $b_obj['data_id'])
                            {
                                array_push($c, $b_obj);
                                array_splice($a, $a_key, 1);
                                array_splice($b, $b_key, 1);
                            }
                        }
                    }
                }

                $a_b = array_merge($a, $b);         // a,b 除去交集的并集
                // return $c;                       // a,b 的交集
                return array_merge($c, $a_b);       // 交集在前，并集在后
            }
        }
    }
/*****************************************************************************/
    public function _extract_keyword($input = '')
    {
        // 对输入进行过滤，最大支持字符串长度 <= 40
        // 中文字符也算一个字符长度,一个中文在utf-8编码下面占3个字节
        $search_class = array(
            'doc-name' => array(),
            'doc-keyword' => array(),
            'doc-category' => array(),
            'doc-area' => array(),
            );

        if (! $input)
        {
            return $search_class;
        }

        $normal_len = 40;
        $length = mb_strlen($input, 'UTF-8');
        if ($length > $normal_len)
        {
            $input = substr($input, 0, $normal_len*3);
        }

        $input_list = explode(' ', $input);
        // echoxml($input_list);
        foreach ($input_list as $field)
        {
            $field_len = mb_strlen($field);
            if ($field_len >= 2 && $field_len <= 20)       // 是文档名称
            {
                array_push($search_class['doc-name'], $field);
            }

            if ($field_len >= 2 && $field_len <= 10)        // 是关键词
            {
                array_push($search_class['doc-keyword'], $field);
            }

            if ($field_len >= 2 && $field_len <=5)          // 是分类
            {
                array_push($search_class['doc-category'], $field);
            }

            // 看此字段是否属于 data area 信息，即出现‘南京工程’ ‘通信工程’ ‘媒体通信’这样的area信息
            if ($field)
            {
                $area_info = $this->wxm_category_area->search_area_name_like($field);
                if ($area_info)
                {
                    foreach ($area_info as $area)
                    {
                        array_push($search_class['doc-area'], $area->carea_id);
                    }
                }
            }
        }

        // echoxml($search_class);
        return $search_class;
    }
/*****************************************************************************/
    public function _extract_keyword_user_word_segment($input = '') {
        // 对输入进行过滤，最大支持字符串长度 <= 40
        // 中文字符也算一个字符长度,一个中文在utf-8编码下面占3个字节
        $search_class = array(
            'doc-name' => array(),
            'doc-keyword' => array(),
            'doc-category' => array(),
            'doc-area' => array(),
            );
        if (! $input) {
            return false;
        }

        $normal_len = 40;
        $length = mb_strlen($input, 'UTF-8');
        if ($length > $normal_len) {  // one zh word = 3*(english word)
            $input = substr($input, 0, $normal_len*3);
        }

        $words = $this->wx_weibo_renren_api->get_word_segment($input);
        // check zh word segment iface is ok or not
        if (! $words) {
            return false;
        }
        foreach ($words as $field) {
            $field_len = mb_strlen($field);
            if ($field_len >= 2 && $field_len <= 20) {  // 是文档名称
                $search_class['doc-name'][] = $field;
            }
            if ($field_len >= 2 && $field_len <= 10) {  // 是关键词
                $search_class['doc-keyword'][] = $field;
            }
            if ($field_len >= 2 && $field_len <= 5) {  // 是分类
                $search_class['doc-category'][] = $field;
            }
            // 是否属于area信息，即出现‘南京工程,通信工程’这样的area信息
            if ($field) {
                $area_info = $this->wxm_category_area->search_area_name_like($field);
                if ($area_info) {
                    foreach ($area_info as $area) {
                        $search_class['doc-area'][] = $area->carea_id;
                    }
                }
            }
        }
        // filter and limit the doc-area field, just save top 10
        if ($search_class['doc-area']) {
            $search_class['doc-area'] = array_unique($search_class['doc-area']);
            if (count($search_class['doc-area']) > 10) {
                array_splice($search_class['doc-area'], 10);
            }
        }
        return $search_class;
    }
/*****************************************************************************/
    public function test() {
        $context = '清华大学2008媒体通信工程期末考试';
        if ($context) {
            echo 'Use word segment:'.'<br />';
            $ret = $this->_extract_keyword_user_word_segment($context);
            wx_echoxml($ret);
            echo 'Not use word segment:'.'<br />';
            $ret_0 = $this->_extract_keyword($context);
            wx_echoxml($ret_0);
        }
    }
/*****************************************************************************/
}

/* End of file wxc_search.php */
/* Location: ./application/controllers/primary/wxc_search.php */
