<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WX_Tcpdfapi
{
    var $CI;                        // super CI obj

    var $config = array(            // Lang config data
        'a_meta_charset' => 'UTF-8',
        'a_meta_dir' => 'ltr',
        'a_meta_language' => 'cn',
        'w_page' => ''
        );
    var $tcpdf_service;
    var $tcpdf_page_no;

/*****************************************************************************/
    public function __construct()
    {
        $this->CI =& get_instance();

        $this->CI->load->helper('tcpdf/tcpdf');
        $this->tcpdf_service = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    }
/*****************************************************************************/
    public function init_pdf()
    {
        $this->tcpdf_service->SetCreator(PDF_CREATOR);
        $this->tcpdf_service->SetAuthor('creamnote.com');
        $this->tcpdf_service->SetTitle('www.creamnote.com');
        // $this->tcpdf_service->SetSubject('subject');
        // $this->tcpdf_service->SetKeywords('set, key, words');

        $this->tcpdf_service->setLanguageArray($this->config);

        $this->tcpdf_page_no = 0;
    }
/*****************************************************************************/
    public function set_header($logo = '', $logo_width = 0, $title = '')
    {
        if ($logo == '')
            $logo = PDF_HEADER_LOGO;
        if ($logo_width == 0)
            $logo_width = PDF_HEADER_LOGO_WIDTH;

        $this->tcpdf_service->SetHeaderData($logo, $logo_width, '', $title);

        $this->tcpdf_service->setHeaderFont(Array('droidsansfallback', '', 12));
        $this->tcpdf_service->setFooterFont(Array('droidsansfallback', '', 10));

        $this->tcpdf_service->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->tcpdf_service->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->tcpdf_service->SetFooterMargin(PDF_MARGIN_FOOTER);

        $this->tcpdf_service->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    }
/*****************************************************************************/
    public function set_font($font = 'droidsansfallback', $style = '', $font_size = 12)
    {
        $this->tcpdf_service->SetFont($font, $style, $font_size);
    }
/*****************************************************************************/
    public function add_page()
    {
        $this->tcpdf_service->AddPage();

        $this->tcpdf_page_no++;
    }
/*****************************************************************************/
    public function write($content = '', $align = 'L')
    {
        $this->tcpdf_service->Write(0, $content, '', 0, $align, true, 0, false, false, 0);
        // $this->tcpdf_service->Ln(10);
    }
/*****************************************************************************/
    public function insert_image($image = '',                   // 文件路径
                                 $x = 0, $y = 25,               // 图片位置坐标
                                 $width = 180, $high = 275,     // 图片 宽度X高度
                                 $type = 'JPG',                 // 图片类型
                                 $align = 'C')                  // 居中显示
    {
        if ($image == '')
            $image = K_PATH_IMAGES.'image_demo.jpg';
        $this->tcpdf_service->Image($image, $x, $y, $width, $high, $type, '', '', true, 150,
                                    $align, false, false, 1, false, false, false);
    }
/*****************************************************************************/
    public function delete_page($page_num = 0)
    {
        $this->tcpdf_service->deletePage($page_num);

        $this->tcpdf_page_no--;
    }
/*****************************************************************************/
    public function get_page_num()
    {
        return $this->tcpdf_page_no;
    }
/*****************************************************************************/
    public function new_line($high = 5)
    {
        $this->tcpdf_service->Ln($high);
    }
/*****************************************************************************/
    public function set_color($r = 255, $g = 0, $b = 0)
    {
        $this->tcpdf_service->SetDrawColor($r, $g, $b);
    }
/*****************************************************************************/
    public function set_stroke($stroke = 0.2)
    {
        $this->tcpdf_service->setTextRenderingMode($stroke, $fill = false, $clip = false);
    }
/*****************************************************************************/
    public function add_water($image = '',
                              $x = 0, $y = 110,
                              $width = 100, $high = 40,
                              $type = 'JPG',
                              $align = 'C')
    {
        if ($image == '')
            $image = K_PATH_IMAGES.'image_demo.jpg';

        $this->tcpdf_service->StartTransform();
        $this->tcpdf_service->Rotate(45, 80, 120);

        $this->tcpdf_service->SetAlpha(0.5);
        $this->tcpdf_service->Image($image, $x, $y, $width, $high, $type, '', '', true, 150,
                                    $align, false, false, 1, false, false, false);
        $this->tcpdf_service->SetAlpha(1);

        $this->tcpdf_service->StopTransform();
    }
/*****************************************************************************/
    public function output_pdf($name = 'hello.pdf', $type = 'I')
    {
        // 选择输出文件类型：
        // 'I': 输出到浏览器
        // 'F': 输出到文件
        $this->tcpdf_service->Output($name, $type);
    }
/*****************************************************************************/
    public function get_image_size($image = '')
    {
        $size = array(
            'width' => 0,
            'height' => 0
            );
        if ($image)
        {
            $info = getimagesize($image);
            $size['width'] = $info[0];
            $size['height'] = $info[1];
        }

        return $size;
    }
/*****************************************************************************/
    public function add_surface($title = '', $user = '', $school = '', $summary = '')
    {
        $this->add_page();
        $this->set_font('droidsansfallback', '', 24);

        $image = K_PATH_IMAGES.'surface.jpg';
        $this->tcpdf_service->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $this->insert_image($image, 0, 20, 207, 300, 'JPG', 'L');

        for ($i = 0; $i < 10; $i++)
        {
            $this->new_line();
        }
        $this->write('题目： '.$title, 'C');
        for ($i = 0; $i < 10; $i++)
        {
            $this->new_line();
        }
        $this->set_font('droidsansfallback', '', 12);
        $this->write("                                                              作者： ".$user, 'L');
        $this->new_line();
        $this->write("                                                              学校： ".$school, 'L');
        for ($i = 0; $i < 5; $i++)
        {
            $this->new_line();
        }

        if ($summary) {
            $this->write('笔记内容简介', 'C');
            $str_list = wx_substr_by_length($summary, 30);  // 辅助函数，以20个汉字为长度分割字符串
            $len = count($str_list);
            if ($len > 1) {
                foreach ($str_list as $content) {
                    $this->write('                            '.$content, 'L');
                }
            }
            elseif ($len == 1) {
                $this->write($str_list[0], 'C');
            }
        }
        $this->new_line();
    }
/*****************************************************************************/
    public function add_group_image($group_image)
    {
        if ($group_image)
        {
            foreach ($group_image as $group)
            {
                if ($group)
                {
                    $cur_height = 20;
                    $this->add_page();
                    foreach ($group['image_info'] as $sigle_image)
                    {
                        $image = $sigle_image['image'];
                        $tc_width = $sigle_image['tc_width'];
                        $tc_height = $sigle_image['tc_height'];
                        $tc_type = $sigle_image['tc_type'];
                        $width = floor($tc_width/3.6);
                        $height = floor($tc_height/3.6);
                        $this->insert_image($image, 0, $cur_height, $width, $height, $tc_type, 'C');
                        $cur_height += $height;
                    }
                }
            }
        }
    }
/*****************************************************************************/
    public function test()
    {
        $this->init_pdf();
        $this->set_header('header_logo.png', 40, '我的标题我的标题我的标题我的标题我的标题我的标题我的标题');
        // $this->set_font();

        // $this->add_page();
        // // $this->write();
        // $this->insert_image();
        // $this->add_water();

        // $this->add_page();
        // $this->write('文件的内容');
        // $this->write('文件的内容');

        $title = '嵌入式安全监控系统的设计与实现嵌入式安全监控系统的设计与实现';
        $user = '王地文';
        $school = '南京工程学院';
        $summary = '随着嵌入式技术网络技随着嵌入式';
        $this->add_surface($title, $user, $school, $summary);

        // $this->add_page();
        // $image1 = 'upload/image/1/1_avatar.jpg';
        // $image2 = 'upload/image/1/2_avatar.jpg';
        // $image3 = 'upload/image/1/3_avatar.jpg';
        // if (file_exists($image1))
        // {
        //     echo 'here';
        // }
        // $this->insert_image($image1, 0, 25, 45, 20, 'JPG', 'C');
        // $this->insert_image($image2, 0, 75, 180, 50, 'JPG', 'C');
        // $this->insert_image($image3, 0, 125, 180, 50, 'JPG', 'C');

        $this->output_pdf('upload/tmp/hello.pdf', 'I');

        // $image = K_PATH_IMAGES.'image_demo.jpg';
        // $size = $this->get_image_size($image);
        // echoxml($size);

        // echo 'upload/tmp/hello.pdf';
    }
/*****************************************************************************/
}

/* End of file tcpdfapi.php */
/* Location: ./application/libraries/tcpdfapi.php */
