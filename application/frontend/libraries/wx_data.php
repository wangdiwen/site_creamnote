<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WX_Data
{
/*****************************************************************************/
    var $CI;  // Get the CI super object

    var $PUBLIC_UNFULL = '0';            // 公开，上传完成，未完善
    var $PUBLIC_WAITING = '1';           // 公开，上传完成，等待审核
    var $PUBLIC_FULL_UNVERIFY = '2';     // 公开，上传完成，未审核通过
    var $PUBLIC_FULL_VERIFY = '3';       // 公开，上传完成，审核通过
    var $UNPUBLIC_UNFULL = '4';          // 不公开，上传完成，未完善
    var $UNPUBLIC_FULL = '5';            // 不公开，上传完成，完善
/*****************************************************************************/
    public function __construct()
    {
        $this->CI =& get_instance();
    }
/*****************************************************************************/
}

/* End of file wx_data.php */
/* Location: ./application/libraries/wx_data.php */
