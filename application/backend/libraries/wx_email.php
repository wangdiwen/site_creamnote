<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WX_Email
{
/*****************************************************************************/
    var $CI;  // Get the CI super object

    var $from_user_addr;
    var $from_user_name;
    var $to_user_addr;
    var $subject;
    var $message;
    var $attach_file;
/*****************************************************************************/
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('email');
    }
/*****************************************************************************/
    public function send_email()
    {
        $this->CI->email->clear();

        $this->CI->email->from($this->from_user_addr, $this->from_user_name);
        $this->CI->email->to($this->to_user_addr);
        $this->CI->email->subject($this->subject);
        $this->CI->email->message($this->message);

        return $this->CI->email->send();
    }
/*****************************************************************************/
    public function send_email_attach()
    {
        $this->CI->email->clear();

        $this->CI->email->from($this->from_user_addr, $this->from_user_name);
        $this->CI->email->to($this->to_user_addr);
        $this->CI->email->subject($this->subject);
        $this->CI->email->message($this->message);

        $this->CI->email->attach($this->attach_file);

        return $this->CI->email->send();
    }
/*****************************************************************************/
    public function debugger()
    {
        return $this->CI->email->print_debugger();
    }
/*****************************************************************************/
    public function clear()
    {
        $this->from_user_addr = '';
        $this->from_user_name = '';
        $this->to_user_addr = '';
        $this->subject = '';
        $this->message = '';
        $this->attach_file = '';
    }
/*****************************************************************************/
    public function set_from_user($addr = '', $name = '')
    {
        $this->from_user_addr = $addr;
        $this->from_user_name = $name;
    }
/*****************************************************************************/
    public function set_to_user($addr)
    {
        $this->to_user_addr = $addr;
    }
/*****************************************************************************/
    public function set_subject($subject = '')
    {
        $this->subject = $subject;
    }
/*****************************************************************************/
    public function set_message($msg = '')
    {
        $this->message = $msg;
    }
/*****************************************************************************/
    public function set_attach_file($path = '')
    {
        $this->attach_file = $path;
    }
/*****************************************************************************/

/*****************************************************************************/

/*****************************************************************************/
}

/* End of file WX_Email.php */
/* Location: ./application/libraries/WX_Email.php */
