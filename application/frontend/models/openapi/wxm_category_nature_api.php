<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Category_nature_api extends CI_Model
{
    var $wx_table = 'wx_category_nature';
/*****************************************************************************/
    public function __construct() {
        $this->load->database();
    }
/*****************************************************************************/
    public function get_nature_tag($nature_id = 0) {
        // note: 1 grade must has recommend tag, but 2 or 3 grade maybe not has
        // if 2 or 3 grade not has recommend tag,
        // then fetch the next up grade's tag
        if ($nature_id > 0) {
            // first, check cur nature grade and other info
            $table = $this->wx_table;
            $this->db->select('cnature_id, cnature_grade, cnature_flag, cnature_tag')
                    ->from($table)->where('cnature_id', $nature_id);
            $query = $this->db->get();
            $result = $query->row_array();

            $recommend_tag = '';

            if ($result) {
                $recommend_tag = $result['cnature_tag'];
                // if cur grade has no system tag info,
                // then fetch up grade tag info, if not too, then up to grade
                if (! $recommend_tag) {
                    $cur_grade = $result['cnature_grade'];
                    $cur_flag = $result['cnature_flag'];
                    $up_next_flag = substr($cur_flag, 0, -2);

                    if ($cur_grade <= '0') {        # here, check 1 grade has no tag
                        return false;
                    }

                    $up_next_tag_info = $this->_fetch_tag_by_flag($up_next_flag);
                    $recommend_tag = $up_next_tag_info['cnature_tag'];
                    if (! $recommend_tag) {
                        return $this->get_nature_tag($up_next_tag_info['cnature_id']);
                    }
                }

                return $recommend_tag;
            }
        }
        return false;
    }
/*****************************************************************************/
    private function _fetch_tag_by_flag($nature_flag = '') {
        if ($nature_flag) {
            $table = $this->wx_table;
            $this->db->select('cnature_id, cnature_grade, cnature_flag, cnature_tag')
                    ->from($table)->where('cnature_flag', $nature_flag);
            $query = $this->db->get();
            return $query->row_array();

        }
        return false;
    }
/*****************************************************************************/
/*****************************************************************************/
}

/* End of file wxm_category_nature_api.php */
/* Location: /application/frontend/models/openapi/wxm_category_nature_api.php */
