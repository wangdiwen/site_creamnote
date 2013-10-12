<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXM_User_activity extends CI_Model
{
    var $wx_table = 'wx_user_activity';
/*****************************************************************************/
    public function __construct()
    {
        $this->load->database();
    }
/*****************************************************************************/
    public function insert($info)
    {
        if ($info)
        {
            $user_id = $info['user_id'];
            $uactivity_datacount = $info['uactivity_datacount'];
            $uactivity_downloadcount = $info['uactivity_downloadcount'];
            $uactivity_loginip = $info['uactivity_loginip'];
            $uactivity_logintime = $info['uactivity_logintime'];
            $uactivity_logouttime = $info['uactivity_logouttime'];
            $uactivity_status = $info['uactivity_status'];
            $uactivity_recent_view = $info['uactivity_recent_view'];
            $uactivity_logincount = $info['uactivity_logincount'];

            if ($user_id > 0)
            {
                $data = array(
                    'user_id' => $user_id,
                    'uactivity_datacount' => $uactivity_datacount,
                    'uactivity_downloadcount' => $uactivity_downloadcount,
                    'uactivity_loginip' => $uactivity_loginip,
                    'uactivity_logintime' => $uactivity_logintime,
                    'uactivity_logouttime' => $uactivity_logouttime,
                    'uactivity_status' => $uactivity_status,
                    'uactivity_recent_view' => $uactivity_recent_view,
                    'uactivity_logincount' => $uactivity_logincount
                    );
                $table = $this->wx_table;
                $this->db->insert($table, $data);
            }
        }
    }
/*****************************************************************************/
    public function update_download_count($info)  // and record history
    {
        if ($info)
        {
            $user_id = $info['user_id'];
            $uactivity_downloadcount = $info['uactivity_downloadcount'];
            $uactivity_download = $info['uactivity_download'];
            $uactivity_day_downcount = isset($info['uactivity_day_downcount']) ? $info['uactivity_day_downcount'] : 0;
            if ($user_id > 0)
            {
                $data = array(
                    'uactivity_downloadcount' => $uactivity_downloadcount,
                    'uactivity_download' => $uactivity_download,
                    );
                if ($uactivity_day_downcount) {
                    $data['uactivity_day_downcount'] = $uactivity_day_downcount;
                }
                $table = $this->wx_table;
                $this->db->where('user_id', $user_id);
                $this->db->update($table, $data);
            }
        }
    }
/*****************************************************************************/
    public function add_been_download_count($user_id = 0) {
        if ($user_id > 0) {
            $table = $this->wx_table;
            $this->db->select('uactivity_downloaded_count')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            $result = $query->row_array();
            if ($result) {
                $old_count = $result['uactivity_downloaded_count'];
                $data = array(
                    'uactivity_downloaded_count' => $old_count + 1,
                    );
                $this->db->where('user_id', $user_id);
                $this->db->update($table, $data);
            }
        }
    }
/*****************************************************************************/
    public function update_data_count($info)
    {
        if ($info)
        {
            $user_id = $info['user_id'];
            $uactivity_datacount = $info['uactivity_datacount'];
            if ($user_id > 0)
            {
                $data = array(
                    'uactivity_datacount' => $uactivity_datacount
                    );
                $table = $this->wx_table;
                $this->db->where('user_id', $user_id);
                $this->db->update($table, $data);
            }
        }
    }
/*****************************************************************************/
    public function get_data_count($user_id = 0)
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('uactivity_datacount, uactivity_downloadcount, uactivity_downloaded_count')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function check_auto_login($user_id = 0)
    {
        if ($user_id && $user_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('uactivity_session_id, uactivity_logincount')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function update_logout($info)
    {
        if ($info)
        {
            $user_id = $info['user_id'];
            $uactivity_logouttime = $info['uactivity_logouttime'];
            if ($user_id > 0)
            {
                $data = array(
                    'uactivity_logouttime' => $uactivity_logouttime
                    );
                $table = $this->wx_table;
                $this->db->where('user_id', $user_id);
                $this->db->update($table, $data);
            }
        }
    }
/*****************************************************************************/
    public function update_login($info)
    {
        if ($info)
        {
            $user_id = $info['user_id'];
            $uactivity_loginip = $info['uactivity_loginip'];
            $uactivity_logintime = $info['uactivity_logintime'];
            $uactivity_logincount = isset($info['uactivity_logincount']) ? $info['uactivity_logincount'] : 0;
            $uactivity_session_id = $info['session_id'];
            $reset_day_downcount = isset($info['reset_day_downcount']) ? $info['reset_day_downcount'] : false;
            if ($user_id > 0)
            {
                $data = array(
                    'uactivity_loginip' => $uactivity_loginip,
                    'uactivity_logintime' => $uactivity_logintime,
                    'uactivity_session_id' => $uactivity_session_id,
                    );
                if ($uactivity_logincount) {
                    $data['uactivity_logincount'] = $uactivity_logincount;
                }
                if ($reset_day_downcount) {
                    $data['uactivity_day_downcount'] = 0;
                }

                $table = $this->wx_table;
                $this->db->where('user_id', $user_id);
                $this->db->update($table, $data);
            }
        }
    }
/*****************************************************************************/
    public function get_login_count($user_id = 0) {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('uactivity_logincount')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function get_last_login_time($user_id = 0) {
        if ($user_id > 0) {
            $table = $this->wx_table;
            $this->db->select('uactivity_logintime, uactivity_logincount')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function update_login_time_ip($user_id = 0, $login_time = '', $login_ip = '', $reset_day_count = false) {
        if ($user_id > 0 && $login_time && $login_ip) {
            $data = array(
                'uactivity_loginip' => $login_ip,
                'uactivity_logintime' => $login_time,
                );
            if ($reset_day_count) {
                $data['uactivity_day_downcount'] = 0;
            }

            $table = $this->wx_table;
            $this->db->where('user_id', $user_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function update_level($user_id = 0, $user_level = 0)
    {
        if ($user_id > 0)
        {
            $data = array(
                'uactivity_level' => $user_level
                );
            $table = $this->wx_table;
            $this->db->where('user_id', $user_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function get_user_level($user_id = 0)
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('uactivity_level')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function get_download_info($user_id = 0) {
        if ($user_id > 0) {
            $table = $this->wx_table;
            $this->db->select('uactivity_id, uactivity_downloadcount, uactivity_day_downcount, uactivity_download')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function get_all_by_user_id($user_id = 0)
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('uactivity_id, uactivity_datacount, uactivity_downloadcount, uactivity_loginip, uactivity_logintime, uactivity_logouttime, uactivity_status, uactivity_recent_view, uactivity_logincount, uactivity_level')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function get_recent_view($user_id = 0)
    {
        if ($user_id > 0)
        {
            $table = $this->wx_table;
            $this->db->select('uactivity_id, uactivity_recent_view')->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function update_recent_view($user_id = 0, $recent_view = '')
    {
        if ($user_id > 0 && $recent_view)
        {
            $data = array(
                'uactivity_recent_view' => $recent_view
                );
            $table = $this->wx_table;
            $this->db->where('user_id', $user_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
    public function get_super_users()
    {
        $table = $this->wx_table;
        $join_table = 'wx_user';
        $join_where = $join_table.'.user_id = '.$table.'.user_id';

        $this->db->select('uactivity_id, uactivity_datacount, uactivity_downloadcount,
                           uactivity_logincount, uactivity_level, wx_user.user_id, wx_user.user_name, wx_user.user_email, user_register_time')
                        ->from($table)->join($join_table, $join_where, 'left')
                        ->limit(10)->order_by('uactivity_level', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }
/*****************************************************************************/
    public function get_collect_data($user_id = 0)
    {
        if ($user_id > 0) {
            $table = $this->wx_table;
            $this->db->select('uactivity_id, uactivity_collectdata')
                     ->from($table)->where('user_id', $user_id)->limit(1);
            $query = $this->db->get();
            return $query->row_array();
        }
    }
/*****************************************************************************/
    public function set_collect_data($user_id = 0, $data_id_str = '')
    {
        if ($user_id > 0 && $data_id_str > 0) {
            $table = $this->wx_table;
            $data = array(
                'uactivity_collectdata' => $data_id_str
                );
            $this->db->where('user_id', $user_id);
            $this->db->update($table, $data);
        }
    }
/*****************************************************************************/
}

/* End of file wxm_user_activity.php */
/* Location: ./application/models/core/wxm_user_activity.php */
