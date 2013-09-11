<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Week_Article extends CI_Model
{
    var $wx_table = 'wx_week_article';
/*****************************************************************************/
    public function __construct()
    {
        $this->load->database();
    }
/*****************************************************************************/
    public function get_article_four()
    {
        $table = $this->wx_table;
        $this->db->select('article_id, article_title')->from($table)->where('article_status', 'true')->limit(4)->order_by('article_time', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }
/*****************************************************************************/
    public function get_article_detail($article_id = 0)
    {
        if ($article_id > 0) {
            $table = $this->wx_table;
            $this->db->select('article_id, article_category, article_author, article_status, article_time, article_title, article_content_url, article_notes')->from($table)->where('article_id', $article_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function article_count() {
        $table = $this->wx_table;
        $this->db->select('article_id')->from($table)->where('article_status', 'true');
        $query = $this->db->get();
        $count = $query->num_rows();
        return $count;
    }
/*****************************************************************************/
    public function get_page_article($per_page_limit = 5, $offset = 10) {
        $table = $this->wx_table;
        $this->db->select('article_id, article_category, article_author, article_time, article_title, article_notes')->where('article_status', 'true')->order_by('article_time', 'desc');
        $query = $this->db->get($table, $per_page_limit, $offset);
        return $query->result_array();
    }
/*****************************************************************************/
}

/* End of file wxm_week_article.php */
/* Location: ./application/models/core/wxm_week_article.php */
