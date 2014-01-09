<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_User extends CI_Model
{
    var $wx_table = 'wx_user';

/*****************************************************************************/
    public function __construct()
    {
        $this->load->database();
    }
/*****************************************************************************/
    public function reject_week_digest_by_email($user_email = '') {
        if ($user_email) {
            $has_user = $this->has_such_user($user_email);
            if ($has_user) {
                $table = $this->wx_table;
                $data = array(
                    'user_is_digest' => '0',
                    );
                $this->db->where('user_email', $user_email);
                $this->db->update($table, $data);
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function accept_week_digest_by_email($user_email = '') {
        if ($user_email) {
            $has_user = $this->has_such_user($user_email);
            if ($has_user) {
                $table = $this->wx_table;
                $data = array(
                    'user_is_digest' => '1',
                    );
                $this->db->where('user_email', $user_email);
                $this->db->update($table, $data);
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function get_all_user_count() {
        $table = $this->wx_table;
        return $this->db->count_all($table);
    }
/*****************************************************************************/
    public function get_user_name_email_by_group($per_page_limit = 10, $offset = 0) {
        if ($per_page_limit > 0) {
            $table = $this->wx_table;
            $this->db->select('user_id, user_name, user_email, user_is_digest');
            $query = $this->db->get($table, $per_page_limit, $offset);
            return $query->result_array();
        }
        return false;
    }
/*****************************************************************************/
    public function stat_register_count($start_time = '', $end_time = '')
    {
        if ($start_time && $end_time) {
            # time format: 2013-06-06 00:00:00
            $table = $this->wx_table;
            $where = array(
                'user_register_time >=' => $start_time,
                'user_register_time <=' => $end_time
                );
            $this->db->select('user_id')->from($table)->where($where);
            $query = $this->db->get();
            $count = $query->num_rows();
            return $count;
        }
    }
/*****************************************************************************/
    public function get_any_time_users($start_time = '', $end_time = '') {
        if ($start_time && $end_time) {
            # time format: 2013-06-06 00:00:00
            $table = $this->wx_table;
            $where = array(
                'user_register_time >=' => $start_time,
                'user_register_time <=' => $end_time
                );
            $this->db->select('user_id, user_name, user_email, user_register_time')->from($table)->where($where);
            $query = $this->db->get();
            return $query->result_array();
        }
    }
/*****************************************************************************/
    public function enable_user($user_email = '')
    {
        if ($user_email) {
            $has_user = $this->has_such_user($user_email);
            if ($has_user) {
                $table = $this->wx_table;
                $data = array(
                    'user_status' => 'true'
                    );
                $this->db->where('user_email', $user_email);
                $this->db->update($table, $data);
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function disable_user($user_email = '')
    {
        if ($user_email) {
            $has_user = $this->has_such_user($user_email);
            if ($has_user) {
                $table = $this->wx_table;
                $data = array(
                    'user_status' => 'false'
                    );
                $this->db->where('user_email', $user_email);
                $this->db->update($table, $data);
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function has_such_user($user_email = '')
    {
        if ($user_email) {
            $table = $this->wx_table;
            $this->db->select('user_id')->from($table)->where('user_email', $user_email)->limit(1);
            $query = $this->db->get();
            $count = $query->num_rows();
            if ($count)
                return true;
        }
        return false;
    }
/*****************************************************************************/
    public function register_user_count()
    {
        $table = $this->wx_table;
        $count = $this->db->count_all($table);
        return $count;
    }
/*****************************************************************************/
    public function base_info($user_email_or_name = '')  // new add, user_id
    {
        if ($user_email_or_name) {
            $table = $this->wx_table;
            $this->db->select('user_id, user_name, user_email, user_hobby, user_period, user_register_time,
                                user_status')
                        ->from($table)->where('user_email', $user_email_or_name)
                        ->or_where('user_name', $user_email_or_name)->limit(1);
            $query = $this->db->get();
            $data = $query->row_array();
            if ($data) {
                return $data;
            }
        }
    }
/*****************************************************************************/
    public function base_info_by_id($user_id = 0)  // new add, user_id
    {
        if ($user_id > 0) {
            $table = $this->wx_table;
            $this->db->select('user_id, user_name, user_email, user_hobby, user_period, user_register_time,
                                user_status')
                        ->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            $data = $query->row_array();
            if ($data) {
                return $data;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function get_id_by_email_or_name($email_or_name = '') {
        if ($email_or_name) {
            $table = $this->wx_table;
            $this->db->select('user_id')->from($table)->where('user_email', $email_or_name)
                        ->or_where('user_name', $email_or_name)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
        return false;
    }
/*****************************************************************************/
/*****************************************************************************/
    public function get_user_detail($user_email = '')
    {
        if ($user_email) {
            $table = $this->wx_table;
            $this->db->select('user_id, user_name, user_email, user_hobby, user_period,
                                user_register_time, user_status, user_account_name, user_account_realname,
                                user_account_type, user_account_active, user_account_status,
                                user_account_money, user_account_total,
                                user_phone, user_qq_nicename, user_weibo_nicename, user_renren_nicename')
                    ->from($table)->where('user_email', $user_email)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function get_user_name_email($user_id = 0) {
        if ($user_id > 0) {
            $table = $this->wx_table;
            $this->db->select('user_id, user_name, user_email')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
        return false;
    }
/*****************************************************************************/
    public function get_all_user_email() {
        $table = $this->wx_table;
        $this->db->select('user_id, user_email')->from($table);
        $query = $this->db->get();
        return $query->result_array();
    }
/*****************************************************************************/
    public function get_account_base_info($user_id = 0) {
        if ($user_id > 0) {
            $table = $this->wx_table;
            $this->db->select('user_account_name, user_account_realname, user_account_type,
                                user_account_active, user_account_status, user_account_money')
                        ->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
        return false;
    }
/*****************************************************************************/
    public function change_account_withdraw_status_and_refund($user_id = 0, $refund_money = 0.00) {
        if ($user_id > 0 && $refund_money >= 0.00) {
            $table = $this->wx_table;
            $this->db->select('user_account_money')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            $money_info = $query->row_array();
            if ($money_info) {
                $balance = $money_info['user_account_money'];
                $new_balance = number_format($balance + $refund_money, 2, '.', '');
                $data = array(
                    'user_account_status' => '0',
                    'user_account_money' => $new_balance,
                    );
                $this->db->where('user_id', $user_id);
                $this->db->update($table, $data);
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function refund_account_withdraw_money($user_id, $refund_money = 0.00) {  // 返还提现金额给用户的收益账户余额
        if ($user_id > 0 && $refund_money >= 0.00) {
            $table = $this->wx_table;
            $this->db->select('user_account_money')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            $money_info = $query->row_array();
            if ($money_info) {
                $balance = $money_info['user_account_money'];
                $new_balance = number_format($balance + $refund_money, 2, '.', '');
                $data = array(
                    'user_account_money' => $new_balance,
                    );
                $this->db->where('user_id', $user_id);
                $this->db->update($table, $data);
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function change_account_withdraw_status_ok($user_id = 0) {
        if ($user_id > 0) {
            $table = $this->wx_table;
            $data = array(
                'user_account_status' => '0',
                );
            $this->db->where('user_id', $user_id);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
}

/* End of file wxm_admin_user.php */
/* Location: /application/backend/models/wxm_admin_user.php */
