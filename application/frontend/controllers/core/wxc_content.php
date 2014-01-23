<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WXC_Content extends CI_Controller
{
/*****************************************************************************/
    public function __construct()
    {
        parent::__construct();
        $this->load->model('core/wxm_week_article');
        $this->load->model('core/wxm_notice');

        // $this->load->library('wx_util');
        $this->load->library('pagination');
    }
/*****************************************************************************/
    public function read_article()
    {
        $article_id = $this->input->get('article_id');

        $data = array(
            );
        $article_info = $this->wxm_week_article->get_article_detail($article_id);
        if ($article_info) {
            $content_url = $article_info['article_content_url'];
            $data['article_category'] = $article_info['article_category'];
            $data['article_author'] = $article_info['article_author'];
            $data['article_time'] = $article_info['article_time'];
            $data['article_title'] = $article_info['article_title'];
            $data['article_notes'] = $article_info['article_notes'];
            $article_url = 'http://wx-article.oss-internal.aliyuncs.com/'.$content_url;
            $content = file_get_contents($article_url);
            $data['article_content'] = $content;

            $this->load->view('article/wxv_article_content', $data);
        }
        else {  // no such article, redirect to 404
            redirect('primary/wxc_home/page_404');
        }
    }
/*****************************************************************************/
    public function more_article($offset = 0) {  // page partiong
        $article_count = $this->wxm_week_article->article_count();

        $config = array(
            'base_url' => base_url().'core/wxc_content/more_article',
            'total_rows' => $article_count,
            'per_page' => 10,
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
            'site_article' => $article_page
            );
        $this->load->view('article/wxv_article_list', $data);
    }
/*****************************************************************************/
    public function more_site_notice($offset = 0) {  // page partiong
        $notice_count = $this->wxm_notice->notice_count();

        $config = array(
            'base_url' => base_url().'core/wxc_content/more_site_notice',
            'total_rows' => $notice_count,
            'per_page' => 10,
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

        $notice_page = $this->wxm_notice->get_page_notice($config['per_page'], $offset);
        $data = array(
            'site_notice' => $notice_page
            );
        $this->load->view('notice/wxv_notice_list', $data);
    }
/*****************************************************************************/
    public function read_notice() {
        $notice_id = $this->input->get('notice_id');

        $notice_info = $this->wxm_notice->get_by_id($notice_id);
        if ($notice_info) {
            $content_url = $notice_info['notice_content_url'];
            $notice_url = 'http://wx-notice.oss-internal.aliyuncs.com/'.$content_url;
            $content = file_get_contents($notice_url);

            $data = array(
                'notice_title' => $notice_info['notice_title'],
                'notice_time' => $notice_info['notice_time'],
                'notice_content' => $content
                );
            $this->load->view('notice/wxv_notice_content', $data);
        }
        else {  // no such site notice, redirect to 404
            redirect('primary/wxc_home/page_404');
        }
    }
/*****************************************************************************/
/*****************************************************************************/
/*****************************************************************************/
}

/* End of file wxc_content.php */
/* Location: ./application/controllers/core/wxc_content.php */
