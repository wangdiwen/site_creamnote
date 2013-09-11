<?php if (! defined('BASEPATH')) exit('No direct script access allowed.');

class WXC_Data_statistic extends CI_Controller
{
/*****************************************************************************/
    public function __construct()
    {
        parent::__construct();

        $this->load->model('core/wxm_data');
        $this->load->model('share/wxm_data_activity');
        $this->load->model('share/wxm_grade');
        $this->load->model('core/wxm_comment');
        $this->load->model('core/wxm_notify');
    }
/*****************************************************************************/
    public function update_activity_point($data_id = 0)
    {
        if ($data_id > 0)
        {
            // 从wx_grade数据表取得3个字段的数据
            $grade_data = $this->wxm_grade->get_by_data_id($data_id);
            if ($grade_data)
            {
                $grade_excellent_count = $grade_data['grade_excellent_count'];
                $grade_well_count = $grade_data['grade_well_count'];
                $grade_bad_count = $grade_data['grade_bad_count'];

                // 算法=0.1x[ 50x( (3x+2y-5z)/(3x+2y+5z) + 1) ]
                $grade_point = 0;
                if ($grade_excellent_count == 0
                    && $grade_well_count == 0
                    && $grade_bad_count == 0)
                {
                    $grade_point = 10;
                }
                else
                {
                    $grade_point = floor(5*((3*$grade_excellent_count + 2*$grade_well_count - 5*$grade_bad_count) / (3*$grade_excellent_count + 2*$grade_well_count + 5*$grade_bad_count) +1));
                }

                $data = array(
                    'data_id' => $data_id,
                    'dactivity_point_count' => $grade_point
                    );
                $this->wxm_data_activity->update_point($data);
            }
        }
    }
/*****************************************************************************/
    public function update_grade()
    {
        $data_id = $this->input->post('data_id');
        $type = $this->input->post('grade_type');
        $count = $this->input->post('grade_count');

        if ($type == 'excellent')
        {
            $data = array(
                'data_id' => $data_id,
                'grade_excellent_count' => $count
                );
            $this->wxm_grade->update_excellent($data);
            echo 'success';
            return;
        }
        elseif ($type == 'well')
        {
            $data = array(
                'data_id' => $data_id,
                'grade_well_count' => $count
                );
            $this->wxm_grade->update_well($data);
            echo 'success';
            return;
        }
        elseif ($type == 'bad')
        {
            $data = array(
                'data_id' => $data_id,
                'grade_bad_count' => $count
                );
            $this->wxm_grade->update_bad($data);
            echo 'success';
            return;
        }
        echo 'failed';
    }
/*****************************************************************************/
    public function update_data_point($data_id = 0)
    {
        // 算法：data_point的值
        // 1. 审核 = 满分50分（暂定）
        // 2. 资料打分，根据grade表3个字段算法得出，满分10分
        // 3. 评论，每条1分，>=10条，满分10分
        // 4. 下载数，每2次1分，>=20次，满分10分
        // 5. 浏览数，每5次1分，>=50次，满分10分
        // 6. 购买数，每1次2分，>=5次，满分10分
        if ($data_id > 0)
        {
            $activity_data = $this->wxm_data_activity->get_by_data_id($data_id);
            if ($activity_data)
            {
                $comment_count = $activity_data['dactivity_comment_count'];
                $download_count = $activity_data['dactivity_download_count'];
                $view_count = $activity_data['dactivity_view_count'];
                $buy_count = $activity_data['dactivity_buy_count'];
                $point_count = $activity_data['dactivity_point_count'];
                $examine_count = $activity_data['dactivity_examine_count'];

                $comment_count = $comment_count < 10 ? $comment_count : 10;
                $download_count = floor($download_count*0.5) < 10 ? floor($download_count*0.5) : 10;
                $view_count = floor($view_count*0.2) < 10 ? floor($view_count*0.2) : 10;
                $buy_count = floor($buy_count*2) < 10 ? floor($buy_count*2) : 10;

                $data_point = $examine_count + $point_count + $comment_count + $download_count + $view_count + $buy_count;
                // echo $data_point;
                // 更新到 wx_data表
                $data = array(
                    'data_id' => $data_id,
                    'data_point' => $data_point
                    );
                $this->wxm_data->update_point($data);
            }
        }
    }
/*****************************************************************************/
    public function update_comment($data_id = 0, $comment_count = 0)
    {
        if ($data_id > 0)
        {
            $comment = array(
                'data_id' => $data_id,
                'dactivity_comment_count' => $comment_count
                );
            $this->wxm_data_activity->update_comment($comment);
        }
    }
/*****************************************************************************/
    public function insert_comment()
    {
        $data_id = $this->input->post('data_id');              // 资料的id
        $data_user_id = $this->input->post('data_user_id');    // 资料拥有者的id
        $data_name = $this->input->post('data_name');          // 资料的名称
        $comment_content = $this->input->post('comment_content');  // 评论内容
        $comment_count = $this->input->post('comment_count');  // 页面返回最新的评论数

        $user_id = isset($_SESSION['wx_user_id']) ? $_SESSION['wx_user_id'] : 0;
        $comment_time = date('Y-m-d H:i:s');
        $comment_status = 'unmask';
        if ($data_id > 0 && $user_id > 0)
        {
            $data = array(
                'data_id' => $data_id,
                'user_id' => $user_id,
                'comment_content' => $comment_content,
                'comment_time' => $comment_time,
                'comment_status' => $comment_status
                );
            $this->wxm_comment->insert($data);
            echo 'success';
            // return;

            // 后台记录数据
            fastcgi_finish_request();
            // 更新评论的条数
            $this->update_comment($data_id, $comment_count);

            // 记录通知
            if ($data_user_id != $user_id)
            {
                // 查看在通知表notify中是否已经存在被评论资料的信息了？
                $has_comment_notify = $this->wxm_notify->has_comment_notify($data_user_id, $data_id);
                if (! $has_comment_notify)
                {
                    $notify = array(
                        'notify_type' => '3',
                        'notify_content' => '您有一条资料评论信息：'.substr($data_name, 0, 30).'...',
                        'user_id' => $data_user_id,
                        'notify_params' => $data_id,
                        'notify_time' => date('Y-m-d H:i:s')
                        );
                    $this->wxm_notify->insert($notify);
                }
            }
        }
        echo 'failed';
    }
/*****************************************************************************/

/*****************************************************************************/

/*****************************************************************************/

/*****************************************************************************/
}

/* End of file wxc_data_statistic.php */
/* Location: ./application/controllers/core/wxc_data_statistic.php */
