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
    public function update_account_balance($info = array()) {
        if ($info) {
            $user_id = $info['user_id'];
            $user_account_money = $info['user_account_money'];
            if ($user_id > 0) {
                $table = $this->wx_table;
                $data = array(
                    'user_account_money' => $user_account_money,
                    );
                $this->db->where('user_id', $user_id);
                $this->db->update($table, $data);
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function get_super_users_by_new_user() {
        $table = $this->wx_table;
        $join_table = 'wx_user_activity';
        $join_where = $join_table.'.user_id = '.$table.'.user_id';

        $this->db->select('wx_user.user_id, user_name, user_email, user_register_time,
                           wx_user_activity.uactivity_datacount, wx_user_activity.uactivity_downloadcount,
                           wx_user_activity.uactivity_logincount, wx_user_activity.uactivity_level')
                        ->from($table)->join($join_table, $join_where, 'left')
                        ->limit(10)->order_by('user_register_time', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }
/*****************************************************************************/
    public function add_account_balance($info = array()) {
        if ($info) {
            $user_id = $info['user_id'];
            $add_balance = $info['user_account_money_add'];
            if ($user_id > 0) {
                $table = $this->wx_table;
                $this->db->select('user_account_money, user_account_total')->from($table)->where('user_id', $user_id)->limit(1);
                $query = $this->db->get();
                $account_info = $query->row_array();
                if ($account_info) {
                    $cur_account_money = $account_info['user_account_money'];
                    $cur_account_total = $account_info['user_account_total'];
                    $new_account_money = number_format((float)$cur_account_money + (float)$add_balance, 2, '.', '');
                    $new_account_total = number_format((float)$cur_account_total + (float)$add_balance, 2, '.', '');

                    $data = array(
                        'user_account_money' => $new_account_money,
                        'user_account_total' => $new_account_total,
                        );
                    $this->db->where('user_id', $user_id);
                    $this->db->update($table, $data);
                    return true;
                }
            }
        }
        return false;
    }
/*****************************************************************************/
    public function get_user_account_money($user_id = 0) {
        if ($user_id > 0) {
            $table = $this->wx_table;
            $this->db->select('user_account_money, user_account_status')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
        return false;
    }
/*****************************************************************************/
    public function record_renren_account($user_id = 0, $renren_open_id = '', $renren_nice_name = '')
    {
        if ($user_id > 0 && $renren_open_id && $renren_nice_name) {
            $table = $this->wx_table;
            $data = array(
                'user_renren_openid' => $renren_open_id,
                'user_renren_nicename' => $renren_nice_name
                );
            $this->db->where('user_id', $user_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function get_by_renren_openid($renren_open_id = '')
    {
        if ($renren_open_id) {
            $table = $this->wx_table;
            $this->db->select('user_id, user_name, user_email, user_status')->from($table)->where('user_renren_openid', $renren_open_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function has_renren_account($renren_open_id = '')
    {
        if ($renren_open_id) {
            $table = $this->wx_table;
            $this->db->select('user_id, user_renren_openid')->from($table)->where('user_renren_openid', $renren_open_id)->limit(1);
            $query = $this->db->get();
            $count = $query->num_rows();
            if ($count)
                return true;
        }
        return false;
    }
/*****************************************************************************/
    public function record_weibo_account($user_id = 0, $weibo_open_id = '', $weibo_nice_name = '')
    {
        if ($user_id > 0 && $weibo_open_id && $weibo_nice_name) {
            $table = $this->wx_table;
            $data = array(
                'user_weibo_openid' => $weibo_open_id,
                'user_weibo_nicename' => $weibo_nice_name
                );
            $this->db->where('user_id', $user_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function get_by_weibo_openid($weibo_open_id = '')
    {
        if ($weibo_open_id) {
            $table = $this->wx_table;
            $this->db->select('user_id, user_name, user_email, user_status')->from($table)->where('user_weibo_openid', $weibo_open_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function has_weibo_account($weibo_open_id = '')
    {
        if ($weibo_open_id) {
            $table = $this->wx_table;
            $this->db->select('user_id, user_weibo_openid')->from($table)->where('user_weibo_openid', $weibo_open_id)->limit(1);
            $query = $this->db->get();
            $count = $query->num_rows();
            if ($count)
                return true;
        }
        return false;
    }
/*****************************************************************************/
    public function get_by_qq_openid($qq_open_id = '')
    {
        if ($qq_open_id) {
            $table = $this->wx_table;
            $this->db->select('user_id, user_name, user_email, user_status')->from($table)->where('user_qq_openid', $qq_open_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function get_third_party_account($user_id = 0)
    {
        if ($user_id > 0) {
            $table = $this->wx_table;
            $this->db->select('user_id, user_qq_openid, user_qq_nicename, user_weibo_openid, user_weibo_nicename, user_renren_openid, user_renren_nicename')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function del_bind_third_party($user_id = 0, $third_type = '')
    {
        if ($user_id > 0 && $third_type) {
            $data = array();
            if ($third_type == 'qq') {
                $data['user_qq_openid'] = '';
                $data['user_qq_nicename'] = '';
            }
            elseif ($third_type == 'weibo') {
                $data['user_weibo_openid'] = '';
                $data['user_weibo_nicename'] = '';
            }
            elseif ($third_type == 'renren') {
                $data['user_renren_openid'] = '';
                $data['user_renren_nicename'] = '';
            }

            if ($data) {
                $table = $this->wx_table;
                $this->db->where('user_id', $user_id);
                $this->db->update($table, $data);
            }
        }
    }
/*****************************************************************************/
    public function record_qq_account($user_id = 0, $qq_open_id = '', $qq_nice_name = '')
    {
        if ($user_id > 0 && $qq_open_id && $qq_nice_name) {
            $table = $this->wx_table;
            $data = array(
                'user_qq_openid' => $qq_open_id,
                'user_qq_nicename' => $qq_nice_name
                );
            $this->db->where('user_id', $user_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function has_qq_account($qq_open_id = '')
    {
        if ($qq_open_id) {
            $table = $this->wx_table;
            $this->db->select('user_id, user_qq_openid')->from($table)->where('user_qq_openid', $qq_open_id)->limit(1);
            $query = $this->db->get();
            $count = $query->num_rows();
            if ($count)
                return true;
        }
        return false;
    }
/*****************************************************************************/
    public function get_email_by_id($user_id = 0)
    {
        if ($user_id > 0) {
            $table = $this->wx_table;
            $this->db->select('user_id, user_email')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function get_name_by_id($user_id = 0)
    {
        if ($user_id > 0) {
            $table = $this->wx_table;
            $this->db->select('user_id, user_name')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function get_user_name_email_by_id_list($user_id_list = array()) {
        if ($user_id_list) {
            $table = $this->wx_table;
            $this->db->select('user_id, user_name, user_email')->from($table)->where_in('user_id', $user_id_list);
            $query = $this->db->get();
            return $query->result_array();
        }
        return false;
    }
/*****************************************************************************/
    public function get_by_id_list($user_id_list = array())
    {
        if ($user_id_list)
        {
            $table = $this->wx_table;
            $this->db->select('user_id, user_name')->from($table)->where_in('user_id', $user_id_list);
            $query = $this->db->get();
            return $query->result_array();
        }
    }
/*****************************************************************************/
    public function get_user_account($user_id = 0)
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('user_account_name, user_account_realname, user_account_type, user_account_token,
								user_account_active, user_account_status, user_account_money, user_account_total')
				->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
        return false;
    }
/*****************************************************************************/
	public function get_account_status($user_id = 0) {
		if ($user_id > 0) {
			$table = $this->wx_table;
			$this->db->select('user_account_active, user_account_status')->from($table)->where('user_id', $user_id)->limit(1);
			$query = $this->db->get();
			return $query->row_array();
		}
		return false;
	}
/*****************************************************************************/
    // 这个状态，标记是否提现，0->未提现，1->提现受理，同时更新账户余额
    public function update_account_money_status($user_id = 0, $user_account_status = '0', $user_account_money = '0.00') {
        if ($user_id > 0) {
            $table = $this->wx_table;
            $data = array(
                    'user_account_status' => $user_account_status,
                    'user_account_money' => $user_account_money,
                    );
            $this->db->where('user_id', $user_id);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
	public function update_account_token($user_id = 0, $user_account_token = '') {
		if ($user_id > 0 && $user_account_token) {
			$table = $this->wx_table;
			$data = array(
					'user_account_token' => $user_account_token,
					);
			$this->db->where('user_id', $user_id);
			$this->db->update($table, $data);
			return true;
		}
		return false;
	}
/*****************************************************************************/
	public function update_account_realname($user_id = 0, $user_account_realname = '') {
		if ($user_id > 0 && $user_account_realname) {
			$table = $this->wx_table;
			$data = array(
					'user_account_realname' => $user_account_realname,
					'user_account_active' => 'true',
					);
			$this->db->where('user_id', $user_id);
			$this->db->update($table, $data);
			return true;
		}
		return false;
	}
/*****************************************************************************/
    public function update_account_name($user_id = 0, $user_account_name = '')
    {
        if ($user_id > 0 && $user_account_name)
        {
            $data = array(
                'user_account_name' => $user_account_name
                );
            $table = $this->wx_table;
            $this->db->where('user_id', $user_id);
            $this->db->update($table, $data);
			return true;
        }
		return false;
    }
/*****************************************************************************/
    public function update_account_name_and_status($user_id = 0, $user_account_name = '') {
        if ($user_id > 0 && $user_account_name) {
            $data = array(
                'user_account_name' => $user_account_name,
                'user_account_realname' => '',
                'user_account_active' => 'false',
                );
            $table = $this->wx_table;
            $this->db->where('user_id', $user_id);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function get_base_info($user_id = 0)
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('user_name, user_email, user_hobby, user_period, user_phone')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function update_base_info($info)
    {
        if ($info)
        {
            $user_id = $info['user_id'];
            $user_name = $info['user_name'];
            $user_hobby = $info['user_hobby'];
            $user_period = $info['user_period'];
            $user_phone = $info['user_phone'];
            if ($user_id > 0)
            {
                $data = array(
                    'user_name' => $user_name,
                    'user_hobby' => $user_hobby,
                    'user_period' => $user_period,
                    'user_phone' => $user_phone
                    );
                $table = $this->wx_table;
                $this->db->where('user_id', $user_id);
                $this->db->update($table, $data);
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/

/*****************************************************************************/
    // 用户的所有信息
    public function user_info($user_id = 0)
    {
        if ($user_id > 0)
        {
            $table = 'wx_user';
            $this->db->select('user_name, user_email, user_hobby, user_period, user_phone, user_account_name, user_account_type, user_account_active, user_account_money, user_register_time')
                     ->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();

            return $query->row();
        }
        else
        {
            return array();
        }
    }
/*****************************************************************/
    // Delete the user by e-mail
    public function delete_user($email = '')
    {
        // Check the e-mail
        if ($email == '')
        {
            return false;
        }

        // Has the user account or not
        $has_user = $this->has_user($email);
        if (!$has_user)
        {
            return false;
        }

        // Delete the user account
        $this->db->where('user_email', $email);
        $this->db->delete('wx_user');

        // Query the delete operation success or failed
        $this->db->select('user_id, user_name')->from('wx_user')->where('user_email', $email);
        $query = $this->db->get();

        $rows = $query->num_rows();
        if ($rows <= 0)  // Delete success
        {
            return true;
        }
        else             // Delete failed
        {
            return false;
        }
    }
/*****************************************************************/
    // Get the user's session data, like id, name, by user's e-mail
    public function get_id_name($email = '')
    {
        $this->db->select('user_id, user_name')->from('wx_user')->where('user_email', $email);
        $query = $this->db->get();
        $row = $query->row();
        return $row;  // $row is a object
    }
/*****************************************************************/
    public function get_id_name_by_user_id($user_id = 0) {
        $this->db->select('user_id, user_name, user_email')->from('wx_user')->where('user_id', $user_id)->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }
/*****************************************************************/
    public function check_password($user_id = 0, $password = '')
    {
        // $this->load->library('encrypt');

        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('user_password')->from($table)->where('user_id', $user_id);
            $query = $this->db->get();
            $row = $query->row_array();
            if ($row)
            {
                $encrypt_passwd = $row['user_password'];
                $md5_passwd = md5($password);
                if ($encrypt_passwd == $md5_passwd) {
                    return true;
                }
                // $old_password = $this->encrypt->decode($encrypt_passwd);
                // if ($old_password == $password)
                // {
                //     return true;
                // }
            }
        }
        return false;
    }
/*****************************************************************/
    public function update_passwd_by_email($user_email = '', $passwd = '')
    {
        // $this->load->library('encrypt');

        if ($user_email && $passwd)
        {
            // $encrypt_passwd = $this->encrypt->encode($passwd);
            $encrypt_passwd = md5($passwd);
            $data = array(
                'user_password' => $encrypt_passwd
                );
            $this->db->where('user_email', $user_email);
            $this->db->update('wx_user', $data);

            // Check success or not
            $this->db->select('user_password')->from('wx_user')->where('user_email', $user_email);
            $query = $this->db->get();
            $row = $query->row();
            // $user_password = $this->encrypt->decode($row->user_password);
            $user_password = $row->user_password;
            if ($user_password == md5($passwd))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        return false;
    }
/*****************************************************************/
    // Return: true/false
    public function update_passwd($id = 0, $passwd = '')
    {
        // $this->load->library('encrypt');

        if ($id && $passwd)
        {
            // $encrypt_passwd = $this->encrypt->encode($passwd);
            $encrypt_passwd = md5($passwd);
            $data = array('user_password' => $encrypt_passwd);
            $this->db->where('user_id', $id);
            $this->db->update('wx_user', $data);

            // Check success or not
            $this->db->select('user_password')->from('wx_user')->where('user_id', $id);
            $query = $this->db->get();
            $row = $query->row();
            // $user_password = $this->encrypt->decode($row->user_password);
            $user_password = $row->user_password;
            if ($user_password == md5($passwd))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
/*****************************************************************/
    // Return: true/false
    public function update_name($id = 0, $name = '')
    {
        $data = array('user_name' => $name);

        $this->db->where('user_id', $id);
        $this->db->update('wx_user', $data);

        // Check
        $this->db->select('user_name')->from('wx_user')->where('user_id', $id);
        $query = $this->db->get();
        $row = $query->row();

        $user_name = $row->user_name;
        if ($user_name == $name)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
/*****************************************************************/
    // Return: 'user_name'/'0'/'1'/'2'
    public function login($email = '', $passwd = '')
    {
        // Encript lib, for md5 method
        // $this->load->library('encrypt');

        $has_user = $this->has_user($email);  // Check user e-mail account
        if ($has_user)
        {
            // Check user passwd
            $this->db->select('user_name, user_email, user_password, user_status')
                        ->from('wx_user')->where('user_email', $email);
            $query = $this->db->get();

            $rows = $query->num_rows();
            if ( $rows > 0 && $rows < 2)  // Must only one item account, rows=1
            {
                $first = $query->row();
                $user_password = $first->user_password;
                $user_name = $first->user_name;
                $user_status = $first->user_status;

                // $encrypt_passwd = $this->encrypt->decode($user_password);

                // 判断用户是否被封号
                if ($user_status == 'false') {
                    return '3';
                }

                if ($user_password == md5($passwd))
                {
                    return $user_name;  // Successed, return name
                }
                else
                {
                    return '0';  // Passwd is wrong
                }
            }
            else
            {
                return '2';  // Database exception
            }
        }
        else
        {
            return '1';  // No such e-mail account
        }
    }
/*****************************************************************/
    // Return: '0'/'1'
    public function register($name = '', $email = '', $passwd = '')
    {
        // Encript lib, for md5 method
        // $this->load->library('encrypt');

        $has_name = $this->has_name($name);
        $has_user = $this->has_user($email);  // Check if has e-mail account or not
        if ($has_user)
        {
            return '1';     // Already has e-mail account
        }
        elseif ($has_name)
        {
            return '2';     // Already has the nice name
        }
        else
        {
            // $encrypt_passwd = $this->encrypt->encode($passwd);
            $encrypt_passwd = md5($passwd);
            $register_time = date("Y-m-d H:i:s", time());

            // Insert cur account to database
            $data = array('user_name' => $name,
                          'user_password' => $encrypt_passwd,
                          'user_email' => $email,
                          'user_register_time' => $register_time,
                          'user_account_name' => '',
                          'user_account_type' => '支付宝',
                          'user_account_money' => 0.00,
                          'user_account_active' => 'false'
                          );
            $this->db->insert('wx_user', $data);

            return '0';  // Insert successfully
        }
    }
/*****************************************************************/
    public function has_name($name = '')
    {
        $this->db->select('user_id')->from('wx_user')->where('user_name', $name)->limit(1);
        $query = $this->db->get();
        $rows = $query->num_rows();

        if ($rows > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
/*****************************************************************/
    public function check_nice_name($user_id = 0, $user_name = '')
    {
        $table = $this->wx_table;
        $where = array(
            'user_name' => $user_name,
            'user_id != ' => $user_id
            );
        $this->db->select('user_id')->from('wx_user')->where($where)->limit(1);
        $query = $this->db->get();
        $rows = $query->num_rows();
        if ($rows > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
/*****************************************************************/
    public function check_phone($user_id = 0, $phone = '')
    {
        $table = $this->wx_table;
        $where = array(
            'user_phone' => $phone,
            'user_id != ' => $user_id
            );
        $this->db->select('user_id')->from('wx_user')->where($where)->limit(1);
        $query = $this->db->get();
        $rows = $query->num_rows();
        if ($rows > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
/*****************************************************************/
    // Return: true/false
    public function has_user($user_email = '')
    {
        if ($user_email)
        {
            $table = $this->wx_table;
            $where = array('user_email' => $user_email);
            $this->db->select('user_id')->from($table)->where($where)->limit(1);
            $query = $this->db->get();
            $rows = $query->num_rows();
            if ($rows > 0)
            {
                return true;  // Has e-mail account
            }
        }
        return false;
    }
/*****************************************************************************/

/*****************************************************************************/

/*****************************************************************************/

/*****************************************************************************/
}

/* End of file wxm_user.php */
/* Location: ./application/models/core/wxm_user.php */
