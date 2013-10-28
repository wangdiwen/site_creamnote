<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--[if lt IE 7 ]><html class="ie6"><![endif]-->
<!--[if IE 7 ]><html class="ie7"><![endif]-->
<!--[if IE 8 ]><html class="ie8"><![endif]-->
<!--[if IE 9 ]><html class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html class=""><!--<![endif]-->
<head>
    <title>Creamnote醍醐笔记网:分享你珍贵的资料</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta property="qc:admins" content="145042776163251567456375" />
    <meta property="wb:webmaster" content="6aba2630b40f0fd8" />
    <meta name="baidu-site-verification" content="kcZ7s2PtTQ" />
    <meta name="google-site-verification" content="Hbt0iSqYKgfRRkrtN7VBLlAkxNXm8MgCM5xCPMC5dA0" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/application/frontend/views/resources/css/reset.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/text.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_home.css" />
    <link rel='stylesheet' id='camera-css'  href='/application/frontend/views/resources/css/camera.css' type='text/css' media='all'>
    <link rel="stylesheet" href="/application/frontend/views/resources/css/poshytip/tip-darkgray/tip-darkgray.css" type="text/css" />

    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery.min.js"></script>
    <script type='text/javascript' src='/application/frontend/views/resources/js/jquery.mobile.customized.min.js'></script>
    <script type='text/javascript' src='/application/frontend/views/resources/js/jquery.easing.1.3.js'></script>
    <script type='text/javascript' src='/application/frontend/views/resources/js/camera.min.js'></script>
    <script type='text/javascript' src='/application/frontend/views/resources/js/common.js'></script>
    <script type='text/javascript' src='/application/frontend/views/resources/js/jquery.poshytip.js'></script>
    <script type="text/javascript" src="/application/frontend/views/resources/js/school.js"></script>

<script type="text/javascript">
<!-- Javascript functions -->
var loginname = '<?php if (isset($_SESSION["wx_user_name"]) && $_SESSION["wx_user_name"] != "") echo $_SESSION["wx_user_name"]; else echo ""; ?>';
    $(document).ready(function() {

    // $(".signin").click(function(e) {
    //     e.preventDefault();
    //     $("fieldset#signin_menu").toggle();
    //     $(".signin").toggleClass("menu-open");
    // });

    // $("fieldset#signin_menu").mouseup(function() {
    //     return false
    //   });
    //   $(document).mouseup(function(e) {
    //     if($(e.target).parent("a.signin").length==0) {
    //       $(".signin").removeClass("menu-open");
    //       $("fieldset#signin_menu").hide();
    //     }
    //   });
  $("._head_search").css("display","none");

$("div").hover(function(e){
    $(".collect_data").poshytip('hide');
  });


//=========================================================camera=========================================//
  $('#camera_wrap').camera({
        thumbnails: true,
        height: '430px',
        opacityOnGrid:true,
        fx          : 'random',
        pieDiameter     : 58,
        time        : 4000,
        transPeriod     : 1000,
        // onLoaded      : function() {  alert(1)},

      });

//=========================================================用户信息tips=========================================//

// $(".hoveruser").live('blur',function(){
//   $(".tipforfix").css("display","none");
// });

  $(".user_sm").poshytip({
    className:'tip-darkgray',
  })
//=========================================================资料滚动=========================================//
var $slider = $('#card_items_data');
var $slider_child_l = 9;
var $slider_width = 223*2;
$slider.width($slider_child_l * $slider_width);
var slider_count = 0;
$('#btn-left').css({cursor: 'auto'});
if ($slider_child_l < 5) {
  $('#btn-right').css({cursor: 'auto'});
  $('#btn-right').removeClass("dasabled");
}

$('#btn-right').click(function() {
  if ($slider_child_l < 5 || slider_count >= $slider_child_l - 5) {
    return false;
  }

  slider_count++;
  $slider.animate({left: '-=' + $slider_width + 'px'}, 'slow');
  slider_pic();
});

$('#btn-left').click(function() {
  if (slider_count <= 0) {
    return false;
  }

  slider_count--;
  $slider.animate({left: '+=' + $slider_width + 'px'}, 'slow');
  slider_pic();
});

function slider_pic() {
  if (slider_count >= $slider_child_l - 5) {
    $('#btn-right').css({cursor: 'auto'});
    $('#btn-right').addClass("dasabled");
  }
  else if (slider_count > 0 && slider_count <= $slider_child_l - 5) {
    $('#btn-left').css({cursor: 'pointer'});
    $('#btn-left').removeClass("dasabled");
    $('#btn-right').css({cursor: 'pointer'});
    $('#btn-right').removeClass("dasabled");
  }
  else if (slider_count <= 0) {
    $('#btn-left').css({cursor: 'auto'});
    $('#btn-left').addClass("dasabled");
  }
}

//=========================================================用户滚动=========================================//
var $slider_u = $('#card_items_user');
var $slider_child_l_u = 12;
var $slider_width_u = 223;
$slider_u.width($slider_child_l_u * $slider_width_u);
var slider_count_u = 0;
$('#btn-left_u').css({cursor: 'auto'});
if ($slider_child_l_u < 5) {
  $('#btn-right_u').css({cursor: 'auto'});
  $('#btn-right_u').removeClass("dasabled");
}

$('#btn-right_u').click(function() {
  if ($slider_child_l_u < 5 || slider_count_u >= $slider_child_l_u - 5) {
    return false;
  }

  slider_count_u++;
  $slider_u.animate({left: '-=' + $slider_width_u + 'px'}, 'slow');
  slider_pic_u();
});

$('#btn-left_u').click(function() {
  if (slider_count_u <= 0) {
    return false;
  }

  slider_count_u--;
  $slider_u.animate({left: '+=' + $slider_width_u + 'px'}, 'slow');
  slider_pic_u();

  });

function slider_pic_u() {
  if (slider_count_u >= $slider_child_l_u - 5) {
    $('#btn-right_u').css({cursor: 'auto'});
    $('#btn-right_u').addClass("dasabled");
  }
  else if (slider_count_u > 0 && slider_count_u <= $slider_child_l_u - 5) {
    $('#btn-left_u').css({cursor: 'pointer'});
    $('#btn-left_u').removeClass("dasabled");
    $('#btn-right_u').css({cursor: 'pointer'});
    $('#btn-right_u').removeClass("dasabled");
  }
  else if (slider_count_u <= 0) {
    $('#btn-left_u').css({cursor: 'auto'});
    $('#btn-left_u').addClass("dasabled");
  }
}
});


//=========================================================判断是否登录=========================================//
function checkLogin(){
  if(loginname == ""){
    $("#login_win").css("display","block");
  }
}

</script>

</head>
<body>
  <?php include  'application/frontend/views/share/header_home.php';?>
  <!-- #tips -->
  <div class="tipforfix" style="display:none;">
              <div class="creamnote_tips">
                <div class="tip_card">
                  <div class="tip_content">
                    <img class="fl" src="http://www.gravatar.com/avatar/40034fb133a2e2920877a2c9cdebd556?s=50&d=identicon&r=PG">
                    <div class="tip_right fl">
                      <div class="co fl name">xiewang</div>
                      <div class="co fl">1010658096@qq.com</div>
                      <div class="co fl">拥有100份资料</div>
                    </div>
                    <div class="tip_bottom ">
                      XXXX大学/XXX专业
                    </div>
                  </div>
                </div>
                <div class="tip_arrow1"></div>
                <div class="tip_arrow2"></div>
              </div>
            </div>
  <div class="body">

    <!-- #camera_wrap -->
    <div class="camera_wrap camera_azure_skin" style="height: 430px;" id="camera_wrap">
            <div data-link="<?php echo site_url('data/wxc_data/data_list'); ?>" data-target="_blank" data-thumb="/application/frontend/views/resources/images/slides/thumbs/creamnote_ad_1t.jpg" data-src="/application/frontend/views/resources/images/slides/creamnote_ad_1.jpg">
                <div class="camera_caption fadeFromBottom">
                  <em>找课堂学习笔记、找期末考试习题、找考研复习资料 -- 上Creamnote醍醐笔记网</em>
                </div>
            </div>
            <div data-link="<?php echo site_url('home/data_upload_page'); ?>" data-target="_blank" data-thumb="/application/frontend/views/resources/images/slides/thumbs/creamnote_ad_2t.jpg" data-src="/application/frontend/views/resources/images/slides/creamnote_ad_2.jpg">
                <div class="camera_caption fadeFromBottom">
                    <em>学霸sell笔记，从中获得收益</em>
                </div>
            </div>
            <div data-link="<?php echo site_url('data/wxc_data/data_list'); ?>" data-target="_blank" data-thumb="/application/frontend/views/resources/images/slides/thumbs/creamnote_ad_3t.jpg" data-src="/application/frontend/views/resources/images/slides/creamnote_ad_3.jpg">
                <div class="camera_caption fadeFromBottom">
                    <em>快捷搜索、分类搜索、轻松找你想要的笔记</em>
                </div>
            </div>
            <div data-link="<?php echo site_url('home/image_upload_page'); ?>" data-target="_blank" data-thumb="/application/frontend/views/resources/images/slides/thumbs/creamnote_ad_4t.jpg" data-src="/application/frontend/views/resources/images/slides/creamnote_ad_4.jpg">
                <div class="camera_caption fadeFromBottom">
                    <em>特色功能:"图片笔记"，数字化你的手写笔记碎片，珍藏你的劳动成果</em>
                </div>
            </div>
            <div data-link="<?php echo site_url('home/data_upload_page'); ?>" data-target="_blank" data-thumb="/application/frontend/views/resources/images/slides/thumbs/creamnote_ad_5s_t.jpg" data-src="/application/frontend/views/resources/images/slides/creamnote_ad_5s.jpg">
                <div class="camera_caption fadeFromBottom">
                    <em>珍藏一份大学时代的学习经历，分享自己的笔记资料干货，创造学习价值，让“学霸”从中受益</em>
                </div>
            </div>
    </div>
        <!-- #camera_wrap -->
        <form  method="post" action="<?php echo site_url('primary/wxc_search/public_search'); ?>">
        <div class="creamnote_search fl" style="margin-left: 47px;">
          <input name="search" id="search-text" class="creamnote_search_input" maxlength="20" placeholder="输入关键词（ 笔记名称 学校 专业 分类）"/>
          <div onclick="init_home_school()"><span style="position: absolute;margin-left: 440px;margin-top: -34px;padding: 5px 8px;cursor: pointer;" class="common_bule_button">选择学校</span></div>
        </div>
        <div id="choose-box-wrapper" style='z-index: 1000;'>
            <div id="choose-box">
              <div id="choose-box-title">
                <span>选择学校</span>
              </div>
              <div id="choose-a-province">
              </div>
              <div id="choose-a-school">
              </div>
              <div id="choose-box-bottom">
                <input type="button" onclick="hide_home()" value="关闭" />
              </div>
            </div>
          </div>
        <div class="creamnote_search_button fl">
          <input type="submit"  value="" style="background: transparent;width: 124px;height: 37px;cursor:pointer;border: 0;"/>
        </div>
        </form>

        <!-- creamnote card -->
        <div class="card_frame">
          <div class="card_frame_section">
            <h6>优秀笔记</h6>
            <div class="card_total" id="card_total_data">
              <div class="card_items" id="card_items_data" style='margin: -34px 0 0 -30px;'>
                <?php
                if(isset($recommend_notes)){
                  foreach ($recommend_notes as $key => $note) {
                    echo "<div class='_card_item _card_item_panel' style='margin: 22px 0 0 30px;min-height: 224px;' ><div class='_item_actual'>";
                    // echo "<a href=".base_url()."data/wxc_data/complete_data_page/".$note['data_id']."><div class='card_edit'></div></a>";
                    // echo "<div class='card_delete'></div>";
                    echo "<div class='_card_content' style='padding: 10px 20px 5px 20px;'>";
                    echo "<div class='card_head'>";
                    echo "<a target='_blank' href=".base_url()."data/wxc_data/data_view/".$note['data_id'].">".str_replace(array(" ","\r","\n"), array("","",""), $note['data_name'])."</a>";
                    echo "</div>";
                    echo "<div class='card_user _card_user'>";
                    echo "作者：<a class='hoveruser'  id='".$note['user_id']."' href='#'>".$note['user_name']."</a>";
                    echo "</div>";
                    echo "<div class='card_cate _card_cate card_padding'>";
                    echo "分类：<a href=".base_url()."primary/wxc_search/search_by_nature/".$note['data_nature_id']." >".$note['data_nature_name']."</a>";
                    echo $note['data_area_name_school']!=""?"|":"";
                    echo "<a target='_blank' href=".base_url()."primary/wxc_search/search_by_area/".$note['data_area_id_school']." >".$note['data_area_name_school']."</a>";
                    echo $note['data_area_name_major']!=""?"|":"";
                    echo "<a target='_blank' href=".base_url()."primary/wxc_search/search_by_area/".$note['data_area_id_major']." >".$note['data_area_name_major']."</a>";
                    echo "</div>";
                    echo "<div class='card_normal '>";
                    echo "<div class='_card_page'>";
                    if($note['data_type'] == "doc"||$note['data_type'] == "docx"){
                    echo "<img src='/application/frontend/views/resources/images/version/doc_icon.png'>";
                    }else if($note['data_type'] == "ppt"||$note['data_type'] == "pptx"){
                    echo "<img src='/application/frontend/views/resources/images/version/ppt_icon.png'>";
                    }else if($note['data_type'] == "xls"||$note['data_type'] == "xlsx"){
                    echo "<img src='/application/frontend/views/resources/images/version/xls_icon.png'>";
                    }else if($note['data_type'] == "txt"){
                    echo "<img src='/application/frontend/views/resources/images/version/txt_icon.png'>";
                    }else if($note['data_type'] == "pdf"){
                    echo "<img src='/application/frontend/views/resources/images/version/pdf_icon.png'>";
                    }else if($note['data_type'] == "wps"){
                    echo "<img src='/application/frontend/views/resources/images/version/wps_icon.png'>";
                    }
                    // echo "|".$note['data_uploadtime'];
                    echo "<div class='fr'>".$note['data_pagecount']."页</div>";
                    echo "</div>";
                    echo "<div class='card_star'><div class='card_star_blod' >";
                    echo "<input type='hidden' name='data_point' id='data_point' value='".$note['data_point']."'>";
                    echo "</div></div>";
                    echo "</div>";

                    // echo "<div class='card_count'>";
                    // echo "下载:".$note['dactivity_download_count']."&nbsp&nbsp&nbsp浏览:".$note['dactivity_view_count'];
                    // echo "</div>";

                    echo "<div class='_card_footer'>";
                    echo "<a onclick='download_notes(".$note['data_id'].")' href='javascript:void(0)'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_down.png' class='common_show_login_win _foot_content_down fl'></a>";
                    echo "<div id='collect_2".$note['data_id']."' style='display: initial;' class='collect_data'>";
                    if($note['collect'] == "true"){
                      echo "<a id='col".$note['data_id']."' href='javascript:void(0)' onclick='uncollect(2,".$note['data_id'].")'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_store_red.png' class='common_show_login_win _card_edit fl' title='取消收藏'></a>";
                    }else{
                      echo "<a id='col".$note['data_id']."' href='javascript:void(0)' onclick='collect(2,".$note['data_id'].")'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_store.png' class='common_show_login_win _card_edit fl' title='收藏'></a>";
                    }
                    echo "</div>";
                    echo "<span class='_foot_content_price fr'>".$note['data_price']."</span>";
                    echo "</div></div>";
                    echo "<div class='_card_bottom' style='margin-bottom: 5px;'>";
                    echo "<div class='fl' style='margin-top: -13px;'><img src='/application/frontend/views/resources/images/new_version/dy_card_view.png'><span>".$note['dactivity_view_count']."</span></div>";
                    echo "<div class='fr' style='margin-top: -13px;'>".$note['data_uploadtime']."</div>";
                    echo "</div></div></div>";

                  }
                }
              ?>
                <div class='card_item card_item_panel' style='height: 203px;margin: 22px 0 0 30px;width:166px;'>
                  <div style='text-align: center;margin-top: 45px;font-size: 14px;'>
                    <a target='_blank' href="<?php echo site_url('data/wxc_data/data_list'); ?>">更多笔记...</a>
                  </div>
                </div>
              </div>
                <div class="card_arrow_left" id="btn-left"></div>
                <div class="card_arrow_right" id="btn-right"></div>
            </div>
          </div>
          <!-- <div class="card_arrow" id="card_arrow_data"> -->

            <!-- </div> -->
          <div class="card_frame_section" style="border-bottom:0px;">
            <h6>明星用户</h6>
            <div class="card_total" style="height: 168px;" id="card_total_user">
              <div class="card_items" id="card_items_user" style="margin: -34px 0px 0px -30px;">
                <?php
                  if(isset($super_users)){
                    foreach ($super_users as $key => $note) {
                      echo "<div class='_card_item _card_item_panel' style='min-height: 168px;margin: 22px 0 0 30px;'>";
                      // echo "<div class='card_delete'></div>";
                      echo "<div class='_item_actual'>";
                      echo "<div class='_card_content' style='padding: 10px 20px 5px 20px;'>";
                      echo "<div class='card_head' style='font-size: 18px;'>";
                      echo "<img src='".$note['user_head_url']."'/>";
                      echo "<a class='user_head' href='#'>".str_replace(array(" ","\r","\n"), array("","",""), $note['user_name'])."</a>";
                      echo "</div>";
                      echo "<div class='card_user card_padding'>";
                      // echo "<div class='mail'>邮箱:</div><div class='content'>".$note['user_email']."</div>";
                      echo "</div>";
                      echo "<div class=' card_padding' style='padding-top: 8px;'>";
                      echo "<div class='mail'><span>注册时间:</span><span>".$note['user_register_time']."</span></div>";
                      echo "</div>";
                      echo "<div style='color:rgb(76, 118, 172);' class=' user_sm' title='".$note['user_school']."'>";
                      echo "学校：".$note['user_school'];
                      echo "</div>";
                      echo "<div style='color:rgb(76, 118, 172);border-bottom:none;padding-bottom: 8px;' class='card_cate user_sm' title='".$note['user_major']."'>";
                      echo "专业：".$note['user_major'];
                      echo "</div>";



                      echo "</div>";
                      echo "</div>";
                      echo "<div class='card_count' style='padding-top: 5px;margin-top: -39px;'>";
                      echo "<span class='fl'>资料量:".$note['uactivity_datacount']."</span><span class='fr'>&nbsp&nbsp&nbsp下载量:".$note['uactivity_downloadcount']."</span>";
                      echo "</div>";
                      echo "</div>";
                    }
                  }
                ?>
              </div>
                <div class="card_arrow_left" id="btn-left_u" style="margin-top: 51px;"></div>
                <div class="card_arrow_right" id="btn-right_u" style="margin-top: 51px;"></div>
            </div>
          </div>

        </div>
        <!-- creamnote card -->
        <div class="right_notice">
          <div class="right_notice_section">
            <h6>精彩博文<a href="<?php echo base_url()?>core/wxc_content/more_article"><h6 style="float: right;font-size: 14px;margin-top: 5px;">更多>></h6></a></h6>
            <ol class="rounded-list" style="overflow: inherit;margin-bottom: 16px;">
              <?php  foreach ($week_article as $key => $week){?>
                <li>
                  <a style="padding: 0.6em .3em .0em 1.8em;" href="<?php echo base_url()?>core/wxc_content/read_article?article_id=<?=$week['article_id']?>"><span style="width: 237px;" class='gravatar' title="<?=$week['article_title']?>"><?=$week['article_title']?></span></a>
                </li>
              <?php }?>

            </ol>
          </div>
          <div class="right_notice_section" style="margin-bottom: 0;border-bottom:0px;">
            <h6>醍醐公告<a href="<?php echo base_url()?>core/wxc_content/more_site_notice"><h6 style="float: right;font-size: 14px;margin-top: 5px;">更多>></h6></a></h6>

            <ol class="rounded-list" style="overflow: inherit;margin-bottom: 16px;">
              <?php  foreach ($site_notice as $key => $notice){?>
                <li>
                  <a style="padding: 0.6em .3em .0em 1.8em;" href="<?php echo base_url()?>core/wxc_content/read_notice?notice_id=<?=$notice['notice_id']?>"><span style="width: 237px;"><?=$notice['notice_title']?></span></a>
                </li>
              <?php }?>
            </ol>
          </div>
        </div>

  </div>
<?php include  'application/frontend/views/share/footer.php';?>

</body>

</html>
