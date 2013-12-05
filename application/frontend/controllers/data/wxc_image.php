<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXC_Image extends CI_Controller
{
/*****************************************************************************/
	public function __construct()
	{
		parent::__construct();

        $this->load->library('wx_data');

        $this->load->model('core/wxm_user');
        $this->load->model('core/wxm_data');
        $this->load->model('share/wxm_image');
        $this->load->model('share/wxm_data_activity');

        $this->load->library('wx_imageapi');
        $this->load->library('wx_tcpdfapi');
        $this->load->library('wx_site_manager');
	}
/*****************************************************************************/
    // 图片转换的第一步
    public function upload_image()
    {
        if (!(isset($_SESSION['wx_user_id']) && $_SESSION['wx_user_id']))
        {
            redirect('static/wxc_direct/sys_error');
            return false;
        }

    	//在image路径下建立私有目录
        $user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        $user_dir = 'upload/image/'.$user_id;
        if (! is_dir($user_dir))
        {
            if (! mkdir($user_dir, 0777))
            {
                die('创建用户的临时图片目录失败！');
                return false;
            }
        }

        // 图片文件上传操作
        if (! empty($_FILES))
        {

            if ($_FILES['Filedata']['error'] > 0)   // 错误
            {
                die('上传文件有错误！');
                return false;
            }
            else                                    // 正常
            {
                $tmp_file_name = $_FILES['Filedata']['tmp_name'];       // 临时文件名称
                $file_name = wx_trim_all($_FILES['Filedata']['name']);  // 文件原名
                $file_type = $_FILES['Filedata']['type'];               // 文件类型
                $file_size = $_FILES['Filedata']['size'];               // 文件大小，字节

                // 限制单个上传图片的大小，不大于2M
                if ($file_size >= 2000000) {
                    echo 'image-size-overflow';
                    return false;
                }

                $image_info = getimagesize($tmp_file_name);
                $image_width = 0;
                $image_height = 0;
                if ($image_info) {
                    $image_width = $image_info[0];
                    $image_height = $image_info[1];
                }

                $image_type = $image_info[2];
                $type = 'JPG';
                switch ($image_type)
                {
                    case 1:                     // gif
                        $type = 'GIF';
                        break;
                    case 2:                     // jpg
                        $type = 'JPG';
                        break;
                    case 3:                     // png
                        $type = 'PNG';
                        break;
                    default:
                        $type = 'UNKNOWN';
                        break;
                }
                if ($type == 'UNKNOWN')
                {
                    echo 'UNKNOWN';
                    return false;
                }

                // 将上传的临时文件，写入vps
				$image_name = date('YmdHis').rand(100, 999);
				$image_suffix = wx_get_suffix($file_name);
                $save_file_name = $user_dir.'/'.$image_name.'.'.$image_suffix;
                if (move_uploaded_file($tmp_file_name, $save_file_name))
                {
                    // 生成缩略图
                    $ret = $this->wx_imageapi->thumb_image($save_file_name);
                    if ($ret)  // 上传缩略图成功
                    {
                        $thumb_image = $user_dir.'/'.$image_name.'_thumb.'.$image_suffix;

                        $json_info = $this->add_image($user_id, $save_file_name, $thumb_image, $image_width, $image_height);
                        if (! $json_info) {
                            echo 'image-count-overflow';  // 最多只能上传30张图片
                        }
                        else {
                            echo $json_info;   // 返回json文本数据给ajax
                        }
                    }
                }
                else
                {
                    die($file_name." 上传失败！");
                }
            }
        }
    }
/*****************************************************************************/
    public function add_image($user_id = 0, $image = '', $thumb_image = '', $width = 0, $height = 0)
    {
        if ($user_id && $image)
        {
            // 得到数据库的相关信息
            $image_info = $this->wxm_image->get_all_by_user_id($user_id);
            if ($image_info)                // 已经上传了图片数据
            {
                // 将图片的属性和顺序信息，写入数据库
                $json = json_decode($image_info->image_json, true);
                $id = count($json);
                $info = array(
                    'id' => $id,
                    'image' => $image,
                    'thumb_image' => $thumb_image,
                    'width' => $width,
                    'height' => $height
                    );

                // check if over 30 images
                if ($id >= 31) {
                    return '';
                }

                array_push($json, $info);
                $image_json = json_encode($json);

                $data = array(
                    'image_json' => $image_json,
                    'user_id' => $user_id
                    );
                $this->wxm_image->update($data);

                return $image_json;
            }
            else                            // 第一次上传图片
            {
                // 将图片的属性和顺序信息，写入数据库
                $image_info = array();
                $info = array(
                    'id' => 0,
                    'image' => $image,
                    'thumb_image' => $thumb_image,
                    'width' => $width,
                    'height' => $height,
                    );
                array_push($image_info, $info);
                $image_json = json_encode($image_info);

                $data = array(
                    'image_json' => $image_json,
                    'user_id' => $user_id
                    );
                $this->wxm_image->insert($data);

                return $image_json;
            }
        }
        return '';
    }
/*****************************************************************************/
    public function delete_image()
    {
        $image_id = $this->input->post('image_id');

        if (is_numeric($image_id) && $image_id > -1)
        {
            $image_json = '';
            if (isset($_SESSION['wx_user_id']) && $_SESSION['wx_user_id'])
            {
                $user_id = $_SESSION['wx_user_id'];
                // 从数据库中得到图片的json信息
                if ($user_id > 0)
                {
                    $image_info = $this->wxm_image->get_all_by_user_id($user_id);
                    if ($image_info)
                    {
                        $image_json = $image_info->image_json;
                    }
                }

                // 删除json_obj中包含image_id的值
                $json_obj = json_decode($image_json);
                if ($json_obj)
                {
                    $j = 0;
                    foreach ($json_obj as $obj)
                    {
                        $json_id = $obj->id;
                        if ($json_id == $image_id)
                        {
                            array_splice($json_obj, $j, 1);
                        }
                        $j++;
                    }
                }

                // 把新的json数据写入数据库
                $json = json_encode($json_obj);
                $user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
                $data = array(
                        'image_json' => $json,
                        'user_id' => $user_id
                        );
                $this->wxm_image->update($data);

                // 返回到当前页面
                echo $json;         // For ajax
            }
        }
    }
/*****************************************************************************/
    public function submit()
    {
        $order = $this->input->post('order');       // 从页面传递的排列顺序
        $pdf_name = $this->input->post('pdf_name');
        $pdf_user = $this->input->post('pdf_user');
        $pdf_school = $this->input->post('pdf_school');
        $pdf_header = $this->input->post('pdf_header');
        $pdf_summary = $this->input->post('pdf_summary');

        // 屏蔽警告和通知
        // error_reporting(E_ERROR /*| E_WARNING*/ | E_PARSE /*| E_NOTICE*/);

        // 获得用户上传图片的各项信息，json格式数据
        $image_json = '';
        if (isset($_SESSION['wx_user_id']) && $_SESSION['wx_user_id'])
        {
            $user_id = $_SESSION['wx_user_id'];
            if ($user_id > 0)
            {
                $image_info = $this->wxm_image->get_all_by_user_id($user_id);
                if ($image_info)
                {
                    $image_json = $image_info->image_json;
                }
            }
        }
        else
        {
            echo 'not login';
            return;                                 // 如果当前的用户session错误，则不执行以下代码
        }

        // 排序用户上传的图片
        if (! $image_json)
        {
            echo 'no image';
            return;
        }
        $json_obj = json_decode($image_json, true);       // 数据库中的json对象数组
        $new_json_obj = array();                    // 存放新的图片顺序，json数组对象

        if ($order == '')                           // 表示自然顺序，用户没有进行自定义排序
        {
            $new_json_obj = $json_obj;
        }
        else                                        //  用户变更了图片的顺序
        {
            $order_list = explode(',', $order);     // Like: {4, 1, 2, 3, 0, 5}
            foreach ($order_list as $i => $order_id)
            {
                foreach ($json_obj as $j => $obj)
                {
                    $json_id = $obj['id'];
                    if ($json_id == $order_id)
                    {
                        $tmp = array(
                            'id' => $i,
                            'image' => $obj['image'],
                            'thumb_image' => $obj['thumb_image'],
                            'width' => $obj['width'],
                            'height' => $obj['height'],
                            );
                        unset($json_obj[$j]);
                        array_push($new_json_obj, $tmp);
                    }
                }
            }
        }

        // 构造新的图片顺序，json格式文本，写入数据库
        $user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        $json = json_encode($new_json_obj);
        if ($json)
        {
            $data = array(
                'image_json' => $json,
                'user_id' => $user_id
                );
            $this->wxm_image->update($data);
        }
        else
        {
            echo 'no image';
            return false;
        }

        // 生成PDF文件
        $pdf_info = array(
            'pdf_name' => trim($pdf_name),
            'pdf_user' => trim($pdf_user),
            'pdf_school' => trim($pdf_school),
            'pdf_header' => trim($pdf_header),
            'pdf_summary' => wx_trim_all($pdf_summary),
            );
        $ret = $this->_create_pdf($new_json_obj, $pdf_info);  // 返回的是pdf数组信息

        // echo 'success';         // For ajax data
        $data_objectname = $ret['pdf_name'];
        $pdf_has_error = $ret['is_error'];
        if ($pdf_has_error)
        {
            echo 'warning'.','.$data_objectname;
        }
        else
        {
            echo 'success'.','.$data_objectname;
        }

        // 后台完成，删除临时目录、插入data及关联表、删除wx_image表
        fastcgi_finish_request();

        // 删除用户存放图片的临时目录
		$ret = $this->_del_user_image_dir($user_id);

        // 生成一条data资料数据，写入数据库表 wx_data
        // loginfo('生成一条data资料数据，写入数据库表 wx_data');
        $data_status = $this->wx_data->PUBLIC_UNFULL;
        $data_uploadtime = date("Y-m-d H-i-s");
        $upload_path = '/alidata/www/creamnote/upload/tmp/';

        $data = array();
        $data['data_name'] = $pdf_name;
        $data['data_objectname'] = $data_objectname;
        $data['data_type'] = 'pdf';
        $data['data_status'] = $data_status;
        $data['user_id'] = $user_id;
        $data['data_uploadtime'] = $data_uploadtime;
        $data['data_vpspath'] = $upload_path;
        $this->wxm_data->set_data_info($data);
        // 初始化wx_data_activity表
        $data_id = $this->wxm_data->get_id_by_objectname($data_objectname);
        $data_activity = array(
            'data_id' => $data_id,
            'dactivity_comment_count' => 0,
            'dactivity_point_count' => 0,
            'dactivity_download_count' => 0,
            'dactivity_view_count' => 0,
            'dactivity_examine_count' => 0,
            'dactivity_buy_count' => 0
            );
        $this->wxm_data_activity->insert($data_activity);

        // record site image note count, and update total count
        $this->wx_site_manager->add_image_note();

        // 删除此用户对应的 wx_image表的信息
        // loginfo('删除此用户对应的 wx_image表的信息');
        $this->wxm_image->delete($user_id);
    }
/*****************************************************************************/
    public function _create_pdf($json_data, $pdf_info)   // json_data是一个对象数组
    {
        // 返回一个数组，表示创建pdf过程中的信息
        $data = array(
            'is_error' => false,
            'pdf_name' => ''
            );

        if ($json_data && $pdf_info)
        {
            // 初始化一张pdf
            $pdf_header = $pdf_info['pdf_header'];
            $pdf_name = $pdf_info['pdf_name'];
            $pdf_user = $pdf_info['pdf_user'];
            $pdf_school = $pdf_info['pdf_school'];
            $pdf_summary = $pdf_info['pdf_summary'];

            $pdf_header = mb_substr($pdf_header, 0, 30, 'UTF-8');

            $this->wx_tcpdfapi->init_pdf();
            // $this->wx_tcpdfapi->set_header('header_logo.png', 40, $pdf_header);  // old header logo
            $this->wx_tcpdfapi->set_header('new_header_logo.jpg', 28, $pdf_header);     // new header logo
            $this->wx_tcpdfapi->set_font('droidsansfallback');
            // $this->wx_tcpdfapi->set_font('times');
            // 制作pdf封面
            $this->wx_tcpdfapi->add_surface($pdf_name, $pdf_user, $pdf_school, $pdf_summary);

            // 根据数据库中的json字段重新进行，对每张图片的大小和
            // TCpdf中的实际显示大小，计算出哪些图片可以在同一页显示
            $new_json_data = $this->compute_width_hight($json_data);
            $group_array = $this->group_image($new_json_data);

            // 添加图片作为pdf正文
            $this->wx_tcpdfapi->add_group_image($group_array);
            // 判断上传的图片是否有非法的图片
            foreach ($json_data as $data)
            {
                $image = $data['image'];  // 图片文件的路径
                $image_info = getimagesize($image);
                $image_type = $image_info[2];
                $type = 'JPG';
                switch ($image_type)
                {
                    case 1:                     // gif
                        $type = 'GIF';
                        break;
                    case 2:                     // jpg
                        $type = 'JPG';
                        break;
                    case 3:                     // png
                        $type = 'PNG';
                        break;
                    default:
                        $type = 'UNKNOWN';
                        break;
                }

                if ($type != 'UNKNOWN')
                {
                    $data['is_error'] = false;
                }
                else
                {
                    $data['is_error'] = true;
                }
            }

            // 输出pdf，'I' 表示输出到浏览器
            $random_code = rand(10, 99);
            $time_stamp = date("YmdHis");
            $pdf_name = $time_stamp.$random_code.'.pdf';
            $pdf_file = 'upload/tmp/'.$pdf_name;
            // $this->wx_tcpdfapi->output_pdf('new.pdf', 'I');
            $this->wx_tcpdfapi->output_pdf($pdf_file, 'F');

            $data['pdf_name'] = $pdf_name;
        }
        return $data;
    }
/*****************************************************************************/
    public function compute_width_hight($json_data)
    {
        $new_json_data = array();
        if ($json_data)
        {
            foreach ($json_data as $sigle_image)
            {
                $image_path = $sigle_image['image'];
                $image_info = getimagesize($image_path);
                $image_type = $image_info[2];
                $type = 'JPG';
                switch ($image_type)
                {
                    case 1:                     // gif
                        $type = 'GIF';
                        break;
                    case 2:                     // jpg
                        $type = 'JPG';
                        break;
                    case 3:                     // png
                        $type = 'PNG';
                        break;
                    default:
                        $type = 'UNKNOWN';
                        break;
                }
                if ($type != 'UNKNOWN')
                {
                    // 计算根据比例得到的高度值
                    $width = $image_info[0];
                    $height = $image_info[1];
                    $ratio_width = 0;
                    $ratio_height = 0;
                    if ($width < 648)
                    {
                        $ratio_width = $width;
                        $ratio_height = $height;
                    }
                    else
                    {
                        $ratio_width = 648;
                        $ratio_height = floor(648*$height/$width);
                    }

                    $data = array(
                        'image' => $image_path,
                        'type' => $type,
                        'ratio_width' => $ratio_width,
                        'ratio_height' => $ratio_height
                        );
                    array_push($new_json_data, $data);
                }
            }
        }
        return $new_json_data;
    }
/*****************************************************************************/
    public function group_image($new_json_data)
    {
        $data = array();
        if ($new_json_data)
        {
            $global_index = 0;
            for ($index = 0; $index < count($new_json_data); $index++)
            {
                $group = array(
                    'image_info' => array(),
                    'total_height' => 0
                    );

                for ($i = $global_index; $i < count($new_json_data); $i++)
                {
                    $sigle_image = $new_json_data[$i];
                    $tmp_data = array(
                            'image' => $sigle_image['image'],
                            'tc_type' => $sigle_image['type'],
                            'tc_height' => $sigle_image['ratio_height'],
                            'tc_width' => $sigle_image['ratio_width']
                            );
                    if ($group['total_height'] == 0)
                    {
                        array_push($group['image_info'], $tmp_data);
                        $group['total_height'] += $sigle_image['ratio_height'];
                        $global_index++;
                    }
                    else
                    {
                        if (($group['total_height'] + $sigle_image['ratio_height']) < 954)
                        {

                            array_push($group['image_info'], $tmp_data);
                            $group['total_height'] += $sigle_image['ratio_height'];
                            $global_index++;
                        }
                        elseif (($group['total_height'] + $sigle_image['ratio_height']) >= 954 && ($group['total_height'] + floor($sigle_image['ratio_height']*2/3)) < 954)
                        {
                            array_push($group['image_info'], $tmp_data);
                            $group['total_height'] += $sigle_image['ratio_height'];
                            $global_index++;
                        }
                        else
                        {
                            break;
                        }
                    }
                }
                // 把当前分组中的高度值，重新平摊
                if($group['image_info'])
                {
                    $total_height = $group['total_height'];
                    // loginfo($group['image_info'][0]['tc_width']);
                    if ($total_height > 954)
                    {
                        $diff_height = $total_height - 954;
                        $diff = floor($diff_height/count($group['image_info']));
                        for ($i = 0; $i < count($group); $i++)
                        {
                            $group['image_info'][$i]['tc_height'] -= $diff;
                        }
                    }
                    else
                    {
                        $diff_height = 954 - $total_height;
                        $diff = floor($diff_height/count($group['image_info']));
                        for ($i = 0; $i < count($group['image_info']); $i++)
                        {
                            $group['image_info'][$i]['tc_height'] += $diff;
                        }
                    }
                    array_push($data, $group);
                }
            }
        }
        return $data;
    }
/*****************************************************************************/
    public function get_image_size($image = '')
    {
        $size = array(
            'width' => 0,
            'height' => 0,
            'type' => ''
            );
        if ($image)
        {
            $info = getimagesize($image);
            $size['width'] = $info[0];
            $size['height'] = $info[1];
            $size['type'] = $info[2];
        }

        return $size;
    }
/*****************************************************************************/
    public function _del_user_image_dir($user_id = 0)
    {
        if ($user_id > 0)
        {
            $user_image_dir = '/alidata/www/creamnote/upload/image/'.$user_id;
            $ret = wx_delete_dir($user_image_dir);
			return $ret;
        }
    }
/*****************************************************************************/
    public function get_json_data()
    {
        if (isset($_SESSION['wx_user_id']) && $_SESSION['wx_user_id'])
        {
            $user_id = $_SESSION['wx_user_id'];
            if ($user_id > 0)
            {
                $image_info = $this->wxm_image->get_all_by_user_id($user_id);
                if ($image_info)
                {
                    echo $image_info->image_json;       // ajax use
                }
            }
        }
    }
/*****************************************************************************/
    public function image_rotate() {
        $image_path = $this->input->post('image');
        $thumb_image_path = $this->input->post('thumb_image');
        $rotate_direct = $this->input->post('rotate_direct');

        // wx_loginfo($image_path);
        // wx_loginfo($thumb_image_path);
        // wx_loginfo($rotate_direct);

        if ($image_path && $thumb_image_path
            && in_array($rotate_direct, array('right', 'left'))) {
            // 快速处理缩略图图片
            $ret_thumb = $this->wx_imageapi->rotate_image($thumb_image_path, $rotate_direct);
            $ret_image = $this->wx_imageapi->rotate_image($image_path, $rotate_direct);
            if ($ret_thumb) {
                echo 'success';
            }
            else {
                echo 'failed';
                die('rotate image failed');  // 中断本次请求
            }

            // fastcgi_finish_request();  // 耗时处理原图片
            // $ret_image = $this->wx_imageapi->rotate_image($image_path, $rotate_direct);
        }
    }
/*****************************************************************************/
/*****************************************************************************/
    public function test()
    {
        $this->wx_tcpdfapi->test();
        // $this->image_rotate();
        // $data = array(
        //     array('image' => 'upload/image/1/1_avatar.jpg'),
        //      array('image' => 'upload/image/1/2_avatar.jpg'),
        //       array('image' => 'upload/image/1/3_avatar.jpg'),
        //         array('image' => 'upload/image/1/4_avatar.jpg'),
        //         array('image' => 'upload/image/1/5_avatar.jpg')
        //     );
        // $ret = $this->compute_width_hight($data);
        // echoxml($ret);

        // $ret1 = $this->group_image($ret);
        // echoxml($ret1);
    }
/*****************************************************************************/
}

/* End of file wxc_image.php */
/* Location: ./application/controllers/data/wxc_image.php */
