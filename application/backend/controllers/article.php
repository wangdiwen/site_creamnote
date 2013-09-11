<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends CI_Controller
{
/*****************************************************************************/
    public function __construct()
    {
        parent::__construct();
        $this->load->model('wxm_admin_user');
        $this->load->model('wxm_week_article');

        $this->load->library('wx_util');
        $this->load->library('wx_aliossapi');
        $this->load->library('pagination');
    }
/*****************************************************************************/
    public function article_index($offset = 0)
    {
        $article_count = $this->wxm_week_article->article_count();

        $config = array(
            'base_url' => base_url().'cnadmin/article/article_index',
            'total_rows' => $article_count,
            'per_page' => 5,
            'num_links' => 3,
            'uri_segment' => 4,
            'full_tag_open' => '<p>',
            'full_tag_close' => '</p>',
            'first_link' => '首页',
            'first_tag_open' => '<span>',
            'first_tag_close' => '</span>',
            'last_link' => '尾页',
            'last_tag_open' => '<span>',
            'last_tag_close' => '</span>',
            'next_link' => '下一页',
            'next_tag_open' => '<span>',
            'next_tag_close' => '</span>',
            'prev_link' => '上一页',
            'prev_tag_open' => '<span>',
            'prev_tag_close' => '</span>',
            'cur_tag_open' => '<span><a class="number current">',
            'cur_tag_close' => '</a></span>'
            );

        $this->pagination->initialize($config);
        $article_page = $this->wxm_week_article->get_page_article($config['per_page'], $offset);
        $data = array(
            'week_article' => $article_page,
            'article_offset' => $offset
            );

        $this->load->view('f_content/wxv_week', $data);
    }
/*****************************************************************************/
    public function get_all_article()
    {
        $all_article = $this->wxm_week_article->get_all_article();
        // wx_echoxml($all_article);
        return $all_article;
    }
/*****************************************************************************/
    public function publish_article()
    {
        $article_id = $this->input->get('article_id');
        $article_offset = $this->input->get('article_offset');
        $offset = $article_offset ? $article_offset : '';

        $return_code = '';
        $ret = $this->wxm_week_article->enable_article($article_id);
        if ($ret) {
            $return_code = 'success';
        }
        else {
            $return_code = 'failed';
        }

        $cookie = array(
            'name' => 'return_code',
            'value' => $return_code,
            'expire' => '1');
        $this->input->set_cookie($cookie);
        if ($offset) {
            redirect('cnadmin/article/article_index/'.$offset);
        }
        else {
            redirect('cnadmin/article/article_index');
        }
    }
/*****************************************************************************/
    public function cancel_publish()
    {
        $article_id = $this->input->get('article_id');
        $article_offset = $this->input->get('article_offset');
        $offset = $article_offset ? $article_offset : '';

        $return_code = '';
        $ret = $this->wxm_week_article->disable_article($article_id);
        if ($ret) {
            $return_code = 'success';
        }
        else {
            $return_code = 'failed';
        }

        $cookie = array(
            'name' => 'return_code',
            'value' => $return_code,
            'expire' => '1');
        $this->input->set_cookie($cookie);
        if ($offset) {
            redirect('cnadmin/article/article_index/'.$offset);
        }
        else {
            redirect('cnadmin/article/article_index');
        }
    }
/*****************************************************************************/
    public function create_new_article()
    {
        $category = $this->input->post('article_category');
        $title = $this->input->post('article_title');
        $content = $this->input->post('article_content');
        $notes = $this->input->post('article_notes');

        $author = isset($_SESSION['admin_user_name']) ? $_SESSION['admin_user_name'] : '';
        $time = date('Y-m-d');
        $content_url = date('YmdHis').'.php';
        $oss_bucket = 'wx-article';

        if ($author && $category && $title && $content) {
            // 1. save html content to oss bucket
            // http:wx-article/oss-internal.aliyuncs.com/object
            $oss_ret = $this->wx_aliossapi->upload_by_content($oss_bucket, $content_url, $content);

            // 2. record to database
            if ($oss_ret) {
                $data = array(
                    'article_category' => $category,
                    'article_author' => $author,
                    'article_time' => $time,
                    'article_title' => $title,
                    'article_content_url' => $content_url,
                    'article_notes' => $notes
                    );
                $ret = $this->wxm_week_article->create_article($data);
                if ($ret) {
                    echo 'success';
                    return true;
                }
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function modify_article()
    {
        $article_id = $this->input->post('article_id');

        $data = array(
            'article_content' => '',
            'article_notes' => ''
            );
        $base_info = $this->wxm_week_article->get_by_id($article_id);
        if ($base_info) {
            $content_url = $base_info['article_content_url'];
            $article_url = 'http://wx-article.oss-internal.aliyuncs.com/'.$content_url;
            $content = file_get_contents($article_url);
            $notes = $base_info['article_notes'];

            $data['article_content'] = $content;
            $data['article_notes'] = $notes;
        }
        echo json_encode($data);
    }
/*****************************************************************************/
    public function edit_save_article()
    {
        $article_id = $this->input->post('article_id');
        $category = $this->input->post('article_category');
        $title = $this->input->post('article_title');
        $content = $this->input->post('article_content');
        $notes = $this->input->post('article_notes');

        if ($article_id && $category && $title && $content) {
            // 1. save content to oss
            $content_info = $this->wxm_week_article->get_content_url($article_id);
            if ($content_info) {
                $content_url = $content_info['article_content_url'];
            }

            $oss_bucket = 'wx-article';
            $oss_ret = $this->wx_aliossapi->upload_by_content($oss_bucket, $content_url, $content);
            // 2. update to database
            if ($oss_ret) {
                $data = array(
                    'article_id' => $article_id,
                    'article_category' => $category,
                    'article_title' => $title,
                    'article_notes' => $notes
                    );
                $ret = $this->wxm_week_article->update_article($data);
                if ($ret) {
                    echo 'success';
                    return true;
                }
            }
        }
        echo 'failed';
        return false;
    }
/*****************************************************************************/
    public function kindeditor_upload_oss()
    {
        $dir_name = $this->input->get('dir');

        if (!empty($_FILES['imgFile']['error'])) {
            $error = '';
            switch ($_FILES['imgFile']['error']) {
                case '1':
                    $error = '超过php.ini允许的大小';
                    break;
                case '2':
                    $error = '超过表单允许的大小';
                    break;
                case '3':
                    $error = '图片只有部分被上传';
                    break;
                case '4':
                    $error = '请选择图片';
                    break;
                case '6':
                    $error = '找不到临时目录';
                    break;
                case '7':
                    $error = '写文件到硬盘出错';
                    break;
                case '8':
                    $error = 'File upload stopped by extension';
                    break;
                case '999':
                default:
                    $error = '未知错误';
                    break;
            }
            $this->alert_msg($error);
        }

        if ($dir_name != 'image') {
            $this->alert_msg('上传的文件不是图片');
            return;
        }

        $max_size = 1000000;

        if (empty($_FILES) === false) {
            $image_name = $_FILES['imgFile']['name'];
            $tmp_name = $_FILES['imgFile']['tmp_name'];
            $image_type = $_FILES['imgFile']['type'];
            $image_size = $_FILES['imgFile']['size'];

            $image_type_list = explode('/', $image_type);
            $image_type = $image_type_list[1];

            if (! $image_name) {
                $this->alert_msg('请选择文件');
            }
            // if (@is_uploaded_file($tmp_name) === false) {
            //     $this->alert_msg('上传失败');
            // }
            if ($image_size > $max_size) {
                $this->alert_msg('上传文件大小超过限制');
            }
            if (! in_array($image_type, array('gif', 'jpg', 'jpeg', 'png', 'bmp'))) {
                $this->alert_msg('上传图片格式错误');
            }

            // save to oss bucket
            $bucket = 'wx-image-source';
            $save_obj = date("YmdHis").'_'.rand(100, 999).'.'.$image_type;
            $oss_ret = $this->wx_aliossapi->upload_by_file($bucket, $save_obj, $tmp_name);
            if (! $oss_ret) {
                $this->alert_msg('图片保存到OSS云端错误');
                return;
            }
            $oss_url = 'http://'.$bucket.'.oss.aliyuncs.com/'.$save_obj;
            $data = array(
                'error' => 0,
                'url' => $oss_url
                );
            header('Content-type: text/html; charset=UTF-8');
            echo json_encode($data);
            exit();
        }
    }
/*****************************************************************************/
    public function alert_msg($msg = '')
    {
        header('Content-type: text/html; charset=UTF-8');
        $data = array(
            'error' => 1,
            'message' => $msg
            );
        echo json_encode($data);
        exit();
    }
/*****************************************************************************/

/*****************************************************************************/

/*****************************************************************************/
    public function page_partion($offset = 0)
    {


    }
/*****************************************************************************/
}

/* End of file article.php */
/* Location: /application/backend/controllers/article.php */
