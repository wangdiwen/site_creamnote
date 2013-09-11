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
    public function get_all_article()
    {
        $table = $this->wx_table;
        $this->db->select('article_id, article_category, article_author, article_status, article_time, article_title')->from($table)->order_by('article_time', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }
/*****************************************************************************/
    public function enable_article($article_id = 0)
    {
        if ($article_id > 0) {
            $has_article = $this->has_article($article_id);
            if ($has_article) {
                $table = $this->wx_table;
                $data = array(
                    'article_status' => 'true'
                    );
                $this->db->where('article_id', $article_id);
                $this->db->update($table, $data);
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function disable_article($article_id = 0)
    {
        if ($article_id > 0) {
            $has_article = $this->has_article($article_id);
            if ($has_article) {
                $table = $this->wx_table;
                $data = array(
                    'article_status' => 'false'
                    );
                $this->db->where('article_id', $article_id);
                $this->db->update($table, $data);
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function update_article($info = array())
    {
        if ($info) {
            $article_id = $info['article_id'];
            $data = array(
                'article_category' => $info['article_category'],
                'article_title' => $info['article_title'],
                'article_notes' => $info['article_notes']
                );
            $table = $this->wx_table;
            $this->db->where('article_id', $article_id);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function create_article($info = array())
    {
        if ($info) {
            $data = array(
                'article_category' => $info['article_category'],
                'article_author' => $info['article_author'],
                'article_time' => $info['article_time'],
                'article_title' => $info['article_title'],
                'article_content_url' => $info['article_content_url'],
                'article_notes' => $info['article_notes']
                );
            $table = $this->wx_table;
            $this->db->insert($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function get_by_id($article_id = 0) {
        if ($article_id > 0) {
            $table = $this->wx_table;
            $this->db->select('article_id, article_category, article_author, article_status, article_time, article_title, article_content_url, article_notes')->from($table)->where('article_id', $article_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function get_content_url($article_id = 0) {
        if ($article_id > 0) {
            $table = $this->wx_table;
            $this->db->select('article_id, article_content_url')->from($table)->where('article_id', $article_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function has_article($article_id = 0) {
        if ($article_id > 0) {
            $table = $this->wx_table;
            $this->db->select('article_id')->from($table)->where('article_id', $article_id)->limit(1);
            $query = $this->db->get();
            $count = $query->num_rows();
            if ($count)
                return true;
        }
        return false;
    }
/*****************************************************************************/
    public function article_count()
    {
        $table = $this->wx_table;
        $count = $this->db->count_all($table);
        return $count;
    }
/*****************************************************************************/
    public function get_page_article($per_page_limit = '5', $offset = '10')
    {
        $table = $this->wx_table;
        $this->db->select('article_id, article_category, article_author, article_status, article_time, article_title, article_content_url, article_notes')->order_by('article_time', 'desc');
        $query = $this->db->get($table, $per_page_limit, $offset);
        return $query->result_array();
    }
/*****************************************************************************/
}

/* End of file wxm_week_article.php */
/* Location: /application/backend/models/wxm_week_article.php */
