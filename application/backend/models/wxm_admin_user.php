<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_Admin_User extends CI_Model
{
    var $wx_table = 'wx_admin_user';

/*****************************************************************************/
    public function __construct()
    {
        $this->load->database();
    }
/*****************************************************************************/
    public function create_admin($info = array())
    {
        if ($info) {
            $user_name = $info['user_name'];
            $user_email = $info['user_email'];
            $user_status = $info['user_status'];
            $user_type = $info['user_type'];
            $user_token = $info['user_token'];
            $user_password = md5($info['user_password']);
            $user_register_time = $info['user_register_time'];

            // check has already user or not
            $has_user = $this->has_admin_user($user_email);
            if ($has_user) {
                return false;
            }
            // check has user name or not
            $has_name = $this->has_user_name($user_name);
            if ($has_name) {
                return false;
            }

            $data = array(
                'user_name' => $user_name,
                'user_email' => $user_email,
                'user_status' => $user_status,
                'user_type' => $user_type,
                'user_token' => $user_token,
                'user_password' => $user_password,
                'user_register_time' => $user_register_time
                );
            $table = $this->wx_table;
            $this->db->insert($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function update_admin_info($user_email = '', $other = array())
    {
        if ($user_email && $other) {
            $has_email = $this->has_admin_user($user_email);
            // $has_name = $this->has_user_name($other['user_name']);
            if ($has_email) {
                $table = $this->wx_table;
                $data = array(
                    'user_name' => $other['user_name'],
                    'user_status' => $other['user_status'],
                    'user_type' => $other['user_type'],
                    'user_token' => $other['user_token']
                    );
                $this->db->where('user_email', $user_email);
                $this->db->update($table, $data);
                return true;
            }
        }
        return false;
    }
/*****************************************************************************/
    public function del_admin_user($user_email = '')
    {
        $has_user = $this->has_admin_user($user_email);
        if ($has_user) {
            $table = $this->wx_table;
            $this->db->where('user_email', $user_email);
            $this->db->delete($table);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function update_user_password($user_email = '', $user_password = '')
    {
        $has_user = $this->has_admin_user($user_email);
        if ($has_user && $user_password) {
            $md5_password = md5($user_password);
            $table = $this->wx_table;
            $data = array(
                'user_password' => $md5_password
                );
            $this->db->where('user_email', $user_email);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function update_user_token($user_email = '', $user_token = '')
    {
        if ($user_email && $user_token) {
            $has_user = $this->has_admin_user($user_email);
            if (! $has_user) {
                return false;
            }

            $table = $this->wx_table;
            $data = array(
                'user_token' => $user_token
                );
            $this->db->where('user_email', $user_email);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function update_user_status($user_email = '', $user_status = '')
    {
        if ($user_email && $user_status) {
            $has_user = $this->has_admin_user($user_email);
            if (! $has_user) {
                return false;
            }

            $table = $this->wx_table;
            $data = array(
                'user_status' => $user_status
                );
            $this->db->where('user_email', $user_email);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function update_user_name($user_email = '', $user_name = '')
    {
        if ($user_email && $user_name) {
            $has_user = $this->has_admin_user($user_email);
            if (! $has_user) {
                return false;
            }

            $table = $this->wx_table;
            $data = array(
                'user_name' => $user_name
                );
            $this->db->where('user_email', $user_email);
            $this->db->update($table, $data);
            return true;
        }
        return false;
    }
/*****************************************************************************/
    public function check_admin_user($user_email = '', $user_password = '')
    {
        if ($user_email && $user_password) {
            $table = $this->wx_table;
            $this->db->select('user_id, user_email, user_status,user_password')->from($table)->where('user_email', $user_email)->limit(1);
            $query = $this->db->get();

            $user_info = $query->row_array();
            if ($user_info) {
                $admin_email = $user_info['user_email'];
                $admin_password = $user_info['user_password'];
                $admin_status = $user_info['user_status'];

                $md5_password = md5($user_password);
                if ($user_email == $admin_email) {
                    if ($md5_password == $admin_password) {
                        if ($admin_status == 'false') {
                            return '3';  // no permission
                        }
                        return '0';  // ok
                    }
                    else {
                        return '2';  // password wrong
                    }
                }
            }
        }
        return '1';
    }
/*****************************************************************************/
    public function get_all_admin_user()
    {
        $table = $this->wx_table;
        $this->db->select('user_id, user_name, user_email, user_status, user_type, user_token, user_register_time')->from($table);
        $query = $this->db->get();
        return $query->result_array();
    }
/*****************************************************************************/
    public function get_by_email($user_email = '')
    {
        $table = $this->wx_table;
        $this->db->select('user_id, user_name, user_email, user_status, user_type, user_token, user_register_time')->from($table)->where('user_email', $user_email)->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }
/*****************************************************************************/
    public function has_user_name($user_name = '')
    {
        if ($user_name) {
            $table = $this->wx_table;
            $this->db->select('user_id, user_name, user_email')->from($table)->where('user_name', $user_name)->limit(1);
            $query = $this->db->get();
            $count = $query->num_rows();
            if ($count)
                return true;
        }
        return false;
    }
/*****************************************************************************/
    public function has_admin_user($user_email = '')
    {
        if ($user_email) {
            $table = $this->wx_table;
            $this->db->select('user_id, user_name, user_email')->from($table)->where('user_email', $user_email)->limit(1);
            $query = $this->db->get();
            $count = $query->num_rows();
            if ($count)
                return true;
        }
        return false;
    }
/*****************************************************************************/
}

/* End of file wxm_admin_user.php */
/* Location: /application/backend/models/wxm_admin_user.php */
