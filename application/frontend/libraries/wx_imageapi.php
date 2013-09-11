<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WX_Imageapi
{
    var $CI;                        // super CI obj
    var $image_service;

/*****************************************************************************/
    public function __construct()
    {
        $this->CI =& get_instance();

        $this->CI->load->library('image_lib');
    }
/*****************************************************************************/
    public function add_water_text($image = '', $text = '')
    {
        if (file_exists($image) && $text != '')
        {
            // Add water text
            $config_water = array(
                'source_image' => $image,
                'wm_text' => $text,
                'wm_type' => 'text',
                'quality' => 80,
                'wm_font_path' => 'system/fonts/texb.ttf',
                'wm_font_size' => '100',
                'wm_font_color' => 'ffffff',
                'wm_vrt_alignment' => 'middle',
                'wm_hor_alignment' => 'center',
                // 'wm_padding' => '20'
                );
            $this->CI->image_lib->clear();
            $this->CI->image_lib->initialize($config_water);
            if ($this->CI->image_lib->watermark())
            {
                return true;
            }
            else
            {
                echo $this->CI->image_lib->display_errors();
                return false;
            }
        }

        return false;
    }
/*****************************************************************************/
    public function add_water_image($image = '', $image_water = '')
    {
        $suffix = wx_get_suffix($image_water);
        if (! ($suffix == 'png' || $suffix == 'gif' || $suffix == 'jpg'))
        {
            echo 'Imageapi: invalid water image type ['.$suffix.']'.'<br />';
            return false;
        }
        // 支持的水印图片，目前只支持格式为PNG/GIF
        if (file_exists($image) && file_exists($image_water))
        {
            $config_water = array(
                'source_image' => $image,
                'wm_type' => 'overlay',
                'quality' => 80,
                'wm_overlay_path' => $image_water,
                'wm_opacity' => 50,
                'wm_vrt_alignment' => 'top',
                'wm_hor_alignment' => 'right',
                'wm_padding' => '0'
                );
            $this->CI->image_lib->clear();
            $this->CI->image_lib->initialize($config_water);
            if ($this->CI->image_lib->watermark())
            {
                return true;
            }
            else
            {
                echo $this->CI->image_lib->display_errors();
                return false;
            }
        }
        return false;
    }
/*****************************************************************************/
    // 图片的文件路径，相对于网站根目录
    public function thumb_image($image = '', $width = 112, $height = 118)
    {
        if (file_exists($image))
        {
            $config_thumb = array(
                'image_library' => 'gd2',
                'source_image' => $image,
                'create_thumb' => true,
                'maintain_ratio' => false,       // 保持原图片的显示比例
                'width' => $width,
                'height' => $height
                );
            $this->CI->image_lib->clear();
            $this->CI->image_lib->initialize($config_thumb);
            if (! $this->CI->image_lib->resize())
            {
                echo $this->CI->image_lib->display_errors();
                return false;
            }
            return true;
        }

        return false;
    }
/*****************************************************************************/
    public function test()                          // 测试接口
    {
        echo 'Imageapi libraries...'.'<br />';

        // thumb image
        $ret0 = $this->thumb_image('upload/image/image_demo.jpg');
        $ret1 = $this->add_water_text('upload/image/image_demo.jpg', 'water text');
        $ret2 = $this->add_water_image('upload/image/image_demo.jpg', 'upload/image/logo_example.png');
        if ($ret0 && $ret1 && $ret2)
            echo '... success ...'.'<br />';
        else
            echo '... failed ...'.'<br />';
    }
/*****************************************************************************/
}

/* End of file Imageapi.php */
/* Location: ./application/libraries/Imageapi.php */
