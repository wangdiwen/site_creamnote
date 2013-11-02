<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo $data_name;?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/style.css" />
    <link href="/application/frontend/views/resources/css/front.css" media="screen, projection" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery.min.js"></script>
    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_home.css" />
	<script type="text/javascript" src="/application/frontend/views/resources/js/flexpaper.js"></script>
	<script type="text/javascript" src="/application/frontend/views/resources/js/flexpaper_handlers.js"></script>
	<script type="text/javascript" src="/application/frontend/views/resources/js/jquery.color.js"></script>
	<script type="text/javascript" src="/application/frontend/views/resources/js/jquery.quovolver.js"></script>
	<script type="text/javascript" src="/application/frontend/views/resources/js/jquery-ui-1.10.3.custom.js"></script>

<style type="text/css">
.sidebar li li {
    margin: 0;
    padding: 5px 0px;
    border: none;
}
.sidebar li {
    margin: 0;
    padding: 5px 0px;
    border: none;
}
</style>
<!-- Javascript functions -->
<script type="text/javascript">
var if_login = '<?php if (isset($_SESSION["wx_user_name"]) && $_SESSION["wx_user_name"] != "") echo $_SESSION["wx_user_name"]; else echo ""; ?>';
var path = '<?php if (isset($data_swfpath) && $data_swfpath != "") echo $data_swfpath; else echo ""; ?>';
var swfpath;
    if(path.indexOf("http://")>=0){
        swfpath = "http://wx-flash.oss.aliyuncs.com/FlexPaperViewer.swf";
    }else{
        swfpath = "/application/frontend/views/data/FlexPaperViewer.swf";
    }
$(function() {
	$('#documentViewer').FlexPaperViewer(
	        { config : {

	            SWFFile : '<?php if (isset($data_swfpath) && $data_swfpath != "") echo $data_swfpath; else echo ""; ?>',

                FlexPaperViewersrc : swfpath,
	            Scale : 1,
	            ZoomTransition : 'easeOut',
	            ZoomTime : 0.5,
	            ZoomInterval : 0.2,
	            FitPageOnLoad : false,
	            FitWidthOnLoad : true,
	            FullScreenAsMaxWindow : false,
	            ProgressiveLoading : false,
	            MinZoomSize : 0.8,
	            MaxZoomSize : 3,
	            SearchMatchAll : false,
	            InitViewMode : 'Portrait',
	            RenderingOrder : 'flash',
	            StartAtPage : '',

	            ViewModeToolsVisible : false,
	            ZoomToolsVisible : true,
	            NavToolsVisible : true,
	            CursorToolsVisible : false,
	            SearchToolsVisible : false,
	            WMode : 'Opaque',
	            localeChain: 'en_US'
	        }}
	);
//====================================================jquery-ui可拖动弹框=========================================//
    $( "#box" ).draggable();
//=========================================================评论滚动=========================================//
var $slider = $('#card_items_data');
var $slider_child_l = Math.round($("#commnet_count").val())/3+2;
var $slider_width = 387;
$slider.width($slider_child_l * $slider_width);
var slider_count = 0;
$('#btn-left').css({cursor: 'auto'});
$('#btn-left').removeClass("dasabled");
function resetArrow(){
    $slider_child_l = Math.round($("#commnet_count").val())/3+2;

    if ($slider_child_l < 3) {
      $('#btn-right').css({cursor: 'auto'});
      $('#btn-left').css({cursor: 'auto'});
    }
}
// var $slider = $('#card_items_data');
// var $slider_child_l = Math.round($("#commnet_count").val())/3+2;
// var $slider_width = 149*3;
// $slider.width($slider_child_l * $slider_width);
// var slider_count = 0;

if ($slider_child_l < 3) {
  $('#btn-right').css({cursor: 'auto'});
  $('#btn-left').css({cursor: 'auto'});
}

$('#btn-right').live("click",(function() {
  if ($slider_child_l < 3 || slider_count >= $slider_child_l - 3) {
    return false;
  }

  slider_count++;
  $slider.animate({left: '-=' + $slider_width + 'px'}, 'normal');
  slider_pic();
}));

$('#btn-left').live("click",(function() {
  if (slider_count <= 0) {
    return false;
  }

  slider_count--;
  $slider.animate({left: '+=' + $slider_width + 'px'}, 'normal');
  slider_pic();
}));

function slider_pic() {
  if (slider_count >= $slider_child_l - 3) {
    $('#btn-right').css({cursor: 'auto'});
    $('#btn-left').css({cursor: 'pointer'});
  }
  else if (slider_count > 0 && slider_count <= $slider_child_l - 3) {
    $('#btn-left').css({cursor: 'pointer'});
    $('#btn-right').css({cursor: 'pointer'});
  }
  else if (slider_count <= 0) {
    $('#btn-left').css({cursor: 'auto'});
    $('#btn-right').css({cursor: 'pointer'});
  }
}
//=========================================================提交评论=========================================//
	$("#comment_button").click(function(){
		if(if_login!=""){
			var comment = $("#comment_content").attr("value");
			var data_id = $("#data_id").attr("value");
			var data_name = $("#data_name").attr("value");
			var data_user_id = $("#data_user_id").attr("value");
			var user_name = $("#user_name").attr("value");
      var loginname = '<?php if (isset($_SESSION["wx_user_name"]) && $_SESSION["wx_user_name"] != "") echo $_SESSION["wx_user_name"]; else echo ""; ?>';
			var url = '<?php echo site_url('core/wxc_data_statistic/insert_comment'); ?>';
            if(comment == ""){
                warnMes("内容为空时不能提交的哦:)！");
            }else{
               $.ajax({
                    type:"post",
                    url:url,
                    data:({'comment_content':comment,
                        'data_id':data_id,
                        'data_name':data_name,
                        'data_user_id':data_user_id
                        }),
                    success: function(result)
                        {
                          var real_ret = result.split(",")[0];
                          var head_url = result.split(",")[1];
                          if(real_ret=='success'){
                              successMes("评论成功！");
                              var str="";
                              str += "<div class='_detail_card_item _detail_card_item_panel'>";
                              // echo "<div class='card_delete'></div>";
                              str += "<p style='margin-top: 0;height: 82px;word-break: break-all;overflow: hidden;'>"+comment+"</p>";
                              str += "<div class='_detail_card_footer' style='padding-top: 4px;height: 35px;'><p style='margin:0;'>";
                              str += "<img class='fl' width='25' height='25' src='"+head_url+"'>";
                              str += "<span style='color:#4c76ac;'>"+loginname+"</span></br><span class='_detail_card_time'>--刚刚";
                              str += "</span></p></div>";
                              str += "</div>";
                              if($("#commnet_count").val() == 0){
                                $("#card_items_data").html(str);
                              }else{
                                $("#card_items_data_one").before(str);
                              }

                              $("#commnet_count").attr("value",Math.round($("#commnet_count").val())+1);
                              if($("#commnet_count").val() > 3){
                                str ="";
                                str += "<div class='_detail_card_arrow' style=''>";
                                str += "<div class='card_arrow_left' style='margin-top: 38px;margin-left: 4px;' id='btn-left'></div>";
                                str += "<div class='card_arrow_right' style='margin-top: 38px;margin-left: 347px;' id='btn-right'></div></div>";
                                $("#card_items_data").after(str);
                                resetArrow()
                              }
                              $("#comment_content").attr("value","");

                            }else if(real_ret = "failed"){
                              errorMes("两小时内不能重复评论，谢谢！");
                            }

                        },
                       error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    alert(XMLHttpRequest.status);
                                    alert(XMLHttpRequest.readyState);
                                    alert(textStatus);
                                }
                    });
            }

		}else{
            warnMes("亲，要先登录的哦！");
			// submit_compliant();
            $('html,body').animate({scrollTop: '0px'}, 800);
		}

	});


//=========================================================显示留言框=========================================//
	$("#leave_message").click(function(){
		if($("#show_message_frame").css("display")=="none"){
			$("#show_message_frame").css("display","block");
            $("#message_content").focus();
		}else{
			$("#show_message_frame").css("display","none");
		}
	});
//=========================================================提交留言=========================================//
	$("#message_button").click(function(){
		var message_content = $("#message_content").attr("value");
		var data_id = $("#data_id").attr("value");
		var data_name = $("#data_name").attr("value");
		var data_user_id = $("#data_user_id").attr("value");
		var user_name = $("#user_name").attr("value");
		var url = '<?php echo site_url('primary/wxc_message/add_message'); ?>';
        if(message_content == ""){
            $("#message_content").poshytip('show');
        }else{
            $.ajax({
                type:"post",
                url:url,
                data:({'message_content':message_content,
                    'to_user_id':data_user_id
                    }),
                success: function(result)
                    {
                      if(result=='success'){
                          alert("留言成功");
                          $("#message_content").attr("value","");
                        }

                    },
                   error: function(XMLHttpRequest, textStatus, errorThrown) {
                                alert(XMLHttpRequest.status);
                                alert(XMLHttpRequest.readyState);
                                alert(textStatus);
                            }
                });
        }

	});
});
//=========================================================评分=========================================//
$(document).ready(function()
		{

	$("#ratings li span").click(function(e) {
		if(if_login!=""){
			e.preventDefault();
            var obj_parent = $(this).parent();
			var grade = $(this).parent().index() + 1;
			var data_id=$("#data_id").attr("value");
			var grade_count=0;

			if(grade==1){
				grade_count=parseInt($("#grade_bad_hidden").attr("value"))+1;
				grade_type='bad';
	         }else if(grade==2){
	        	grade_count=parseInt($("#grade_well_hidden").attr("value"))+1;
				grade_type='well';
		     }else if(grade==3){
		    	 grade_count=parseInt($("#grade_excellent_hidden").attr("value"))+1;
				 grade_type='excellent';
			 }
			var url = '<?php echo site_url('core/wxc_data_statistic/update_grade'); ?>';
			 $.ajax({
			        type:"post",
			        url:url,
			        data:({'grade_type':grade_type,
				        'data_id':data_id,
				        'grade_count':grade_count
			            }),
			        success: function(result)
			            {
			              if(result=='success'){
				              if(grade==1){
				            	  $("#grade_bad_hidden").attr("value",grade_count);
				            	  // $("#grade_bad").html("差（"+grade_count+")");
                                  $(obj_parent).attr("title",""+grade_count+"人觉得差");
					          }else if(grade==2){
						          $("#grade_well_hidden").attr("value",grade_count);
						          // $("#grade_well").html("良好（"+grade_count+")");
                                    $(obj_parent).attr("title",""+grade_count+"人觉得一般");
							  }else if(grade==3){
							      $("#grade_excellent_hidden").attr("value",grade_count);
							      // $("#grade_excellent").html("优秀（"+grade_count+")");
                                    $(obj_parent).attr("title",""+grade_count+"人觉得优秀");
							  }
                               $(".gravatar").poshytip({
                                    className:'tip-darkgray',
                                })
                               successMes("评分成功");
			                }else if(result == "failed"){
                                errorMes("两小时内不能重复评分，谢谢！");
                            }

			            },
			           error: function(XMLHttpRequest, textStatus, errorThrown) {
			                        alert(XMLHttpRequest.status);
			                        alert(XMLHttpRequest.readyState);
			                        alert(textStatus);
			                    }
			        });
		}else{
            warnMes("亲，要先登录的哦！");
			// submit_compliant();
            $('html,body').animate({scrollTop: '0px'}, 800);
		}
	});

//=========================================================评论滚动=========================================//
     // $('blockquote').quovolver(500,600);
	});

//=========================================================弹出消息=========================================//
$(function() {
    $('.allmessage').click(function(){
        $('#overlay').fadeIn('fast',function(){
            $('#box').animate({'top':'66px'},500);
        });
    });
    $('#boxclose').click(function(){
        $('#box').animate({'top':'-900px'},500,function(){
            $('#overlay').fadeOut('fast');
        });
    });

});
//=========================================================展示用户资料和信息=========================================//
function show_userInfo(user_id){
	url = "<?php echo site_url('primary/wxc_search/search_by_user');?>";
	$.ajax({
        type:"post",
        url:url,
        dataType:"json",
        data:({'user_id':user_id
            }),
        success: function(result)
            {
				var data_url;
				var str="";
  				var i ;
  				str+="<ol class='rounded-list' id='rounded-list' style='padding-left: 20px;'>";
  				for(i in result){
  					data_url = "<?php echo site_url('data/wxc_data/data_view/')?>"+"/"+result[i]['data_id'];
					str+="<li><a href='"+data_url+"'>"+result[i]['data_name']+"("+result[i]['data_uploadtime']+")</a>"  ;
					str+="</li>";
  	  	  		}
  	  	  		str+="</ol>";
  				$("#messageshow").html(str);
            },
           error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.readyState);
                        alert(textStatus);
                    }
        });
	// layer_show()
}
//=========================================================未登陆用户弹出登陆框=========================================//
function login_form(){
	 $.blockUI({
       message: $('#loginForm'),
       showOverlay: true,
       css: {
			 width: '0px',
			 height:'0px',
			 border:'1px none #09335F',
			 left: '55%',
			 top:'20%'
			},
		onOverlayClick: $.unblockUI
       });
}
$(function() {


//判断登陆
if(if_login!=""){

}else{
	//$("#personal_page").addClass("show_loginForm");
	$(".show_loginForm").attr("href","#");
}

});

//=========================================================关注=========================================//
	function follow_user(){
		var url = '<?php echo site_url('core/wxc_user_manager/add_follow'); ?>';
		var followed_user_id = $("#data_user_id").attr("value");
		 $.ajax({
		        type:"post",
		        url:url,
		        data:({'followed_user_id':followed_user_id
		            }),
		        success: function(result)
		            {
		              if(result=='success'){

			              //$("#commnet"+count).after(str);
			             // $('blockquote').quovolver(500,600);
				             var str="";
				             str+="<input style='width:90px' type='button' class='button_c' onclick='unfollow_user()' value='取消关注' id='unfollow'>";
				             $("#if_follow").html(str);

                     str="";
                     str+="<input style='text-align: right;width: 85px;' type='button' class='button_c' id='unfollow' value='已关注'><span class='Webfonts' style='margin-left: -84px;color: #fff;'>.</span>";
                     $("#if_follow_first").html(str);
		                }

		            },
		           error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.readyState);
                        alert(textStatus);
                    }
		        });
	}
//=========================================================取消关注=========================================//
	function unfollow_user(){
		var url = '<?php echo site_url('core/wxc_user_manager/del_follow'); ?>';
		var followed_user_id = $("#data_user_id").attr("value");
		 $.ajax({
		        type:"post",
		        url:url,
		        data:({'followed_user_id':followed_user_id
		            }),
		        success: function(result)
		            {
		              if(result=='success'){

			              //$("#commnet"+count).after(str);
			             // $('blockquote').quovolver(500,600);
		            	  var str="";
  				          str+="<input type='button' class='button_c' onclick='follow_user()' value='关注' id='follow'>";
  				          $("#if_follow").html(str);

                    str ="<input style='text-align: right;width: 69px;' type='button' class='button_c' onclick='follow_user()' id='follow' value='关注'><span class='Webfonts' style='margin-left: -67px;color: #fff;'>+</span>";
                    $("#if_follow_first").html(str)
		                }

		            },
		           error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.readyState);
                        alert(textStatus);
                    }
		        });
	}

</script>

</head>
<body>
	<?php include  'application/frontend/views/share/header.php';?>

    <?php $nav_param = "note";?>
  	<?php include  'application/frontend/views/share/navigation.php';?>


<div class="backcolor_body" style='padding-top: 20px;'>
	<div class=" _detail_body" id="page">
	<!------ 弹出消息 ------>
	<div class="overlay" id="overlay" style="display:none;"></div>
	<div class="box" id="box" style="z-index:1000;"> <a class="boxclose" id="boxclose"></a>
  	<h1 class="message_title">用户信息</h1>
  	<div style="font-size: 16px;color: black;border-bottom: solid 1px #ccc;padding-top: 12px;">昵称：<?php echo $user_name?>
  	<p >
  	<?php
  			if(isset($data_follow))
  			{
  				if($data_follow=='0')
  				{
  					echo"";
  				}
  				elseif ($data_follow=='1')
  				{
  					echo"<span id='if_follow'><input type='button' class='button_c' onclick='follow_user()' id='follow' value='关注'></span>";
  					echo"<input type='button' class='button_c' id='leave_message' value='留言'></p>";
  					echo"<div style='display:none;' id='show_message_frame'><p><textarea onfocus='hidden_mess_tip()' style='width:96%;height: 32px;' placeholder='给资料所有者留言' id='message_content'></textarea></p>";
  					echo"<p><input type='button' name='message_button' class='button_c' id='message_button' value='提交留言'></p></div>";
  				}
  				elseif ($data_follow=='2')
  				{
  					echo"<span id='if_follow'><input style='width:90px' type='button' class='button_c' onclick='unfollow_user()' id='unfollow' value='取消关注'></span>";
  					echo"<input type='button' class='button_c' id='leave_message' value='留言'></p>";
  					echo"<div style='display:none;' id='show_message_frame'><p><textarea onfocus='hidden_mess_tip()' style='width:96%;height: 32px;' placeholder='给资料所有者留言' id='message_content'></textarea></p>";
  					echo"<p><input type='button' name='message_button' class='button_c' id='message_button' value='提交留言'></p></div>";
  				}
  				elseif ($data_follow=='3')
  				{
  					echo"";
  				}
  			}

  		?></p>

    </div>
    <div style='color: black;font-size: 16px;'>
        <p style='margin-top: 10px;margin-bottom: 0px;'>他的笔记干货</p>
    </div>

  	<p id="messageshow">  </p>
	</div>
	<!------ 弹出消息 ------>
		<div id="content" style="float: left;width:625px;border:0;">
		  <div class="post" style="width: 615px;padding: 0px 0 0 0;">

			<div class="">
        <div class="_detail_info_title" style="padding-bottom: 18px;font-weight: bold;font-size:20px;">
          <div class="_grgh" style="margin: 33px 0 10px 0px;">
            <?php if (isset($data_swfpath) && $data_swfpath != "") echo $data_name; else echo ""; ?>
          </div>
        </div>
				<p id="documentViewer" class="" style="background-color:#fff;margin: 0 auto;width:615px;height:730px;box-shadow: inset 0 0 10px rgb(150, 153, 167);z-index:10;"></p>

				<div class="fl">
          <ul id="ratings" class="fl">
  					<li class="gravatar" style="margin-left: 0px;" title="<?php echo $grade_bad_count;?>人觉得差"><span class="_detail_grade_bad common_show_login_win" id="grade_bad"></span><input type="hidden" id="grade_bad_hidden" value="<?php echo $grade_bad_count;?>"></li>
  					<li class="gravatar" title="<?php echo $grade_well_count;?>人觉得一般"><span class="_detail_grade_well common_show_login_win" id="grade_well"></span><input type="hidden" id="grade_well_hidden" value="<?php echo $grade_well_count;?>"></li>
  					<li class="gravatar" title="<?php echo $grade_excellent_count;?>人觉得优秀"><span class="_detail_grade_good common_show_login_win" id="grade_excellent"></span><input type="hidden" id="grade_excellent_hidden" value="<?php echo $grade_excellent_count;?>"></li>
					</ul>
        </div>
        <div class="fl">
          <span style="position: absolute;margin-top: 25px;width: 225px;margin-left: 15px;font-size: 10px;">(我们提供3页的笔记内容预览，如果想查看完整内容，您可以下载或者购买！)</span>
        </div>
        <div class="fl">
          <ul style="list-style-type: none;padding-left: 262px;margin-top: 25px;">
          <li>
            <?php
            if($data_price != "0.00"){
              if (isset($_SESSION["wx_user_name"]) && $_SESSION["wx_user_name"] != ""){
                echo "<a target='_blank' class=' common_show_login_win'  style='color:red;' href=".base_url()."core/wxc_download_note/download_file/".$data_id."><input style='width: 90px;' type='button' class='button_c' value='购买笔记'></a>";
              }else{
                echo "<a target='_blank' class='show_loginForm  ' onclick='download_notes(".$data_id.")' style='color:red;' href='#'><input  style='width: 90px;' type='button' class='button_c common_show_login_win' value='购买笔记'></a>";
              }
            }else{
              if (isset($_SESSION["wx_user_name"]) && $_SESSION["wx_user_name"] != ""){
                echo "<a class=' common_show_login_win'  style='color:red;' href=".base_url()."core/wxc_download_note/download_file/".$data_id."><input style='width: 90px;' type='button' class='button_c' value='下载笔记'></a>";
              }else{
                echo "<a class='show_loginForm  ' onclick='download_notes(".$data_id.")' style='color:red;' href='#'><input  style='width: 90px;' type='button' class='button_c common_show_login_win' value='下载笔记'></a>";
              }
            }

           ?>
             <form target="_blank" style="margin-top: -28px;margin-left: 98px;"  method="post" action="<?php echo base_url();?>primary/wxc_feedback/report_page">
                <input type="hidden" value="<?=$data_id?>" name="data_id">
                <a  href="#"  target="_blank"><input style='width: 90px;margin-right: 0;' type="submit" class="button_c" value="举报文档"></a>
              </form>
          </li>
        </ul>
        </div>
				 <input type="hidden" id="data_id" value="<?php echo $data_id;?>">
				 <input type="hidden" id="data_name" value="<?php echo $data_name;?>">
				 <input type="hidden" id="data_user_id" value="<?php echo $user_id;?>">
				 <input type="hidden" id="user_name" value="<?php echo $user_name;?>">
        		<div class='fl'><textarea class='_detail_comment' placeholder="写些你对该份资料的意见建议" maxlength="200" id="comment_content"></textarea></div>
        		<div class="_detail_buttonframe fl">
                    <input type="button" class="button_c common_show_login_win" style="width:100px" name="comment_button" id="comment_button" value="提交评论">
            </div>


            <div class="card_total fl _detail_card" id="card_total_data" style="width: 383px;height:127px;">
              <div class="card_items" id="card_items_data">
                <?php
                  $commnet_count = 0;
                  if(isset($data_comment)&&$data_comment){
                    foreach ($data_comment as $key => $com) {
                        if($commnet_count == 0){
                            echo "<div class='_detail_card_item _detail_card_item_panel' id='card_items_data_one'>";
                        }else{
                            echo "<div class='_detail_card_item _detail_card_item_panel'>";
                        }
                      // echo "<div class='card_delete'></div>";
                      echo "<p style='margin-top: 0;height: 82px;word-break: break-all;overflow: hidden;'>".$com['comment_content']."</p>";
                      echo "<div class='_detail_card_footer' style='padding-top: 4px;height: 35px;'>";
                      echo "<p style='margin:0;'>";
                      echo "<img class='fl' width='25' height='25' src='".$com['head_url']."'>";
                      echo "<span style='color:#4c76ac;'>".$com['user_name']."</span></br><span class='_detail_card_time'>--".$com['comment_time'];
                      echo "</span></p></div>";
                      echo "</div>";
                      $commnet_count++;

                    }
                    echo "<input type='hidden' value='".$commnet_count."' id='commnet_count'>";
                  }else{
                      echo "<div class='_detail_card_item _detail_card_item_panel' id='card_items_data_one'>";
                      // echo "<div class='card_delete'></div>";
                      echo "<p>还没有评论</p>";
                      // echo
                      echo "</div>";
                      echo "<input type='hidden' value='0' id='commnet_count'>";
                  }
                ?>

              </div>
              <?php if(isset($data_comment)&&$data_comment&&count($data_comment)>3){?>
                <div class="_detail_card_arrow" style="">
                    <div class="card_arrow_left" style='margin-top: 38px;margin-left: 4px;' id="btn-left"></div>
                    <div class="card_arrow_right" style='margin-top: 38px;margin-left: 347px;' id="btn-right"></div>
                </div>
            <?php }?>
            </div>

			</div>
		  </div>

		</div><!-- end #content -->
		<div class="sidebar" style="">
      <div class="_detail_baseinfo">
        <div class="_detail_info_title">
          <div class="_grgh" style="margin: 35px 0 10px 0px;">资料详情</div>
        </div>
      </div>
			<ul>
			 <li style="padding: 10px 20px 0px 0px;margin-bottom: 0px;">

				<ul style="font-size:15px">
  	       <!-- <li>
            <a class="datadetailLi" href="#"><span style="color: rgb(76, 118, 172);"><?php if (isset($data_swfpath) && $data_swfpath != "") echo $data_name; else echo ""; ?>&nbsp;</span></a>
          </li> -->
          <li>
            作者：<a class="datadetailLi allmessage" style="color: rgb(76, 118, 172);" href="#" onclick="show_userInfo(<?php echo $user_id;?>)" ><?php if (isset($user_name) && $user_name != "") echo $user_name; else echo ""; ?> </a>
            <?php
        if(isset($data_follow))
        {
          if($data_follow=='0')
          {
            echo"";
          }
          elseif ($data_follow=='1')
          {
            echo"<span id='if_follow_first'><input style='text-align: right;width: 69px;' type='button' class='button_c' onclick='follow_user()' id='follow' value='关注'><span class='Webfonts' style='margin-left: -67px;color: #fff;'>+</span></span>";
          }
          elseif ($data_follow=='2')
          {
            echo"<span id='if_follow_first'><input style='text-align: right;width: 85px;' type='button' class='button_c' id='unfollow' value='已关注'><span class='Webfonts' style='margin-left: -84px;color: #fff;'>.</span></span>";
          }
          elseif ($data_follow=='3')
          {
            echo"";
          }
        }

      ?>

          </li>
          <li>页数：<?php if (isset($data_pagecount) && $data_pagecount!= "") echo $data_pagecount; else echo ""; ?>&nbsp;页</li>
          <li>价格：<?php if (isset($data_price) && $data_price!= "" &&$data_price!= "0.00" ) echo $data_price."&nbsp;RMB"; else echo "免费"; ?></li>
          <li>
            上传时间：<?php if (isset($data_uploadtime) && $data_uploadtime != "") echo $data_uploadtime; else echo ""; ?>
          </li>
          <li>
            <span class="datadetailLi">分类：</span>
  			    <a class="datadetaila" style="color: rgb(76, 118, 172);" href="<?php echo site_url("primary/wxc_search/search_by_nature/$data_nature_id");?>" >
  			        <?php if (isset($data_nature_name) && $data_nature_name!= "") echo $data_nature_name; else echo ""; ?>
  			    </a>
          </li>
          <?php if (isset($data_area_name) && $data_area_name!= ""){ ?>
          <li>
              <span class="datadetailLi">学校：</span>
      				<a class="datadetaila" style="color: rgb(76, 118, 172);" href="<?php echo site_url("primary/wxc_search/search_by_area/$data_area_id");?>" >
      				    <?php if (isset($data_area_name) && $data_area_name!= "") echo $data_area_name; else echo ""; ?>
      				</a>
          </li>
          <?php }?>


          <li style="max-height: 100px;overflow: hidden;overflow-y: auto;">
              <span class="datadetailLi">简介：<?php if (isset($data_summary) && $data_summary!= "") echo $data_summary; else echo "作者比较懒，什么简介都没有！"; ?></span>
          </li>

				</ul>
			</li>
		</ul>

		</div>

    <div class="sidebar" style="">
      <div class="_detail_viewinfo">
        <div class="_detail_view_title">
          <div class="_grgh" style="margin: 10px 0 10px 0px;">综合指标</div>
        </div>
      </div>
      <ul>
        <li>
            <span class="datadetailLi">下载量：<?php echo $dactivity_download_count?></span>
            <span style="margin-left: 20px;" class="datadetailLi">购买量：<?php echo $dactivity_buy_count?></span>
          </li>
          <li>
            <span class="datadetailLi">浏览量：<?php echo $dactivity_view_count?></span>
            <span style="margin-left: 20px;" class="datadetailLi">评论量：<?php echo $dactivity_comment_count?></span>
          </li>
      </ul>
    </div>

		<div class="sidebar" style="">
      <div class="_detail_viewinfo">
        <div class="_detail_view_title">
          <div class="_grgh" style="margin: 10px 0 10px 0px;">最近浏览笔记</div>
        </div>
      </div>
			<ul>
				<li style="padding: 0px 20px 10px 0px;">

				<ul style="font-size:15px">


 				<?php if($data_recent_view){
					foreach ($data_recent_view as $note){
            echo "<li class='datadetail_view_li'>";
            if($note['data_type'] == "doc"||$note['data_type'] == "docx"){
              echo "<img width='25' height='25' src='/application/frontend/views/resources/images/version/doc_icon.png'>";
              }else if($note['data_type'] == "ppt"||$note['data_type'] == "pptx"){
              echo "<img width='25' height='25' src='/application/frontend/views/resources/images/version/ppt_icon.png'>";
              }else if($note['data_type'] == "xls"||$note['data_type'] == "xlsx"){
              echo "<img width='25' height='25' src='/application/frontend/views/resources/images/version/xls_icon.png'>";
              }else if($note['data_type'] == "txt"){
              echo "<img width='25' height='25' src='/application/frontend/views/resources/images/version/txt_icon.png'>";
              }else if($note['data_type'] == "pdf"){
              echo "<img width='25' height='25' src='/application/frontend/views/resources/images/version/pdf_icon.png'>";
              }else if($note['data_type'] == "wps"){
              echo "<img width='25' height='25' src='/application/frontend/views/resources/images/version/wps_icon.png'>";
              }
						echo "<a href='".base_url()."data/wxc_data/data_view/".$note['data_id']."'><span class='datadetail_view_span' style='color: rgb(76, 118, 172);'>";
						echo $note['data_name'];
						echo "</span></a></li>";
					}
				}?>
    	 </ul>
			</li>
		</ul>
		</div>
		<!-- end #sidebar -->
		<div style="display:none" id="loginForm">
    		<fieldset id="signin_menu" style="display:block;-webkit-border-top-right-radius: 5px;width: 420px;height: 240px;">

              	<label for="username">邮箱</label>
              	<input id="username" name="username" value="" title="username" tabindex="4" type="text" style="width: 300px;">
              	<p>
                	<label for="password">密码</label>
                	<input id="password" name="password" value="" title="password" tabindex="5" type="password" style="width: 300px;">
              	</p>
              	<p class="remember">
                	<input id="signin_submit" value="登录" tabindex="6" type="submit">
                	<input id="remember" name="remember_me" value="1" tabindex="7" type="checkbox">
                	<label for="remember">下次自动登录</label>
              	</p>
              	<p class="forgot">
              		<a href="<?php echo site_url('home/find_password_page')?>" id="resend_password_link">忘记密码？</a>
              		<a style="padding-left: 60px;" href="<?php echo site_url('home/register_page'); ?>">注册</a>
              	</p>
            </fieldset>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div>
	<!-- end #body -->
    <?php include  'application/frontend/views/share/footer.php';?>
    <!-- end #footer -->
</div>
    <!-- JiaThis Button BEGIN -->
    <div class="jiathis_share_slide jiathis_share_32x32" id="jiathis_share_slide">
    <div class="jiathis_share_slide_top" id="jiathis_share_title"></div>
    <div class="jiathis_share_slide_inner">
    <div class="jiathis_style_32x32">
    <a class="jiathis_button_qzone"></a>
    <a class="jiathis_button_tsina"></a>
    <a class="jiathis_button_tqq"></a>
    <a class="jiathis_button_weixin"></a>
    <a class="jiathis_button_renren"></a>
    <a href="http://www.jiathis.com/share" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a>
    <script type="text/javascript">
    var jiathis_config = {
        slide:{
            divid:'page',//设定分享按钮的位置在哪个DIV的边缘，一般是主体内容的外层DIV框架ID,
            pos:'left'
        }
    };
    </script>
    <script type="text/javascript" src="http://v3.jiathis.com/code/jia.js?uid=1365524450783576" charset="utf-8"></script>
    <script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_slide.js" charset="utf-8"></script>
    </div></div></div>
    <!-- JiaThis Button END -->
</body>

</html>
