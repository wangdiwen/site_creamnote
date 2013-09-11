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
	<script type="text/javascript" src="/application/frontend/views/resources/js/jquery.blockUI.js"></script>
	<script type="text/javascript" src="/application/frontend/views/resources/js/jquery-ui-1.10.3.custom.js"></script>

<style type="text/css">
#sidebar li li {
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
	            SearchMatchAll : true,
	            InitViewMode : 'Portrait',
	            RenderingOrder : 'flash',
	            StartAtPage : '',

	            ViewModeToolsVisible : false,
	            ZoomToolsVisible : true,
	            NavToolsVisible : true,
	            CursorToolsVisible : true,
	            SearchToolsVisible : false,
	            WMode : 'Opaque',
	            localeChain: 'en_US'
	        }}
	);
//====================================================jquery-ui可拖动弹框=========================================//
    $( "#box" ).draggable();
//=========================================================评论滚动=========================================//
var $slider = $('#card_items_data');
var $slider_child_l = 10;
var $slider_width = 163*3;
$slider.width($slider_child_l * $slider_width);
var slider_count = 0;

if ($slider_child_l < 5) {
  $('#btn-right').css({cursor: 'auto'});
  $('#btn-right').removeClass("dasabled");
}

$('#btn-right').click(function() {
  if ($slider_child_l < 5 || slider_count >= $slider_child_l - 5) {
    return false;
  }

  slider_count++;
  $slider.animate({left: '-=' + $slider_width + 'px'}, 'normal');
  slider_pic();
});

$('#btn-left').click(function() {
  if (slider_count <= 0) {
    return false;
  }

  slider_count--;
  $slider.animate({left: '+=' + $slider_width + 'px'}, 'normal');
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
//=========================================================提交评论=========================================//
	$("#comment_button").click(function(){
		if(if_login!=""){
			var comment = $("#comment_content").attr("value");
			var data_id = $("#data_id").attr("value");
			var data_name = $("#data_name").attr("value");
			var data_user_id = $("#data_user_id").attr("value");
			var user_name = $("#user_name").attr("value");
			var url = '<?php echo site_url('core/wxc_data_statistic/insert_comment'); ?>';
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
			              if(result=='success'){
				              var str="";
				              var count = $("#comment_count").attr("value");
				              str+="<blockquote><p>"+comment+"<cite>&ndash;"+user_name+"(Quote #"+count+")刚刚</cite></p></blockquote>";
				              $("#comment_content").attr("value","");
				              count--;
				              //$("#commnet"+count).after(str);
				             // $('blockquote').quovolver(500,600);
			                }

			            },
			           error: function(XMLHttpRequest, textStatus, errorThrown) {
			                        alert(XMLHttpRequest.status);
			                        alert(XMLHttpRequest.readyState);
			                        alert(textStatus);
			                    }
			        });
		}else{
			submit_compliant();
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
			// Variable to set the duration of the animation
			var animationTime = 500;

			// Variable to store the colours
			var colours = ["bd2c33", "e49420", "ecdb00", "3bad54", "1b7db9"];

			// Add rating information box after rating
			var ratingInfobox = $("<div />")
				.attr("id", "ratinginfo")
				.insertAfter($("#rating"));

			// Function to colorize the right ratings
			var colourizeRatings = function(nrOfRatings) {
				$("#rating li a").each(function() {
					if($(this).parent().index() <= nrOfRatings) {
						$(this).stop().animate({ backgroundColor : "#" + colours[nrOfRatings] } , animationTime);
					}
				});
			};

			// Handle the hover events
			$("#rating li a").hover(function() {

				// Empty the rating info box and fade in
				ratingInfobox
					.empty()
					.stop()
					.animate({ opacity : 1 }, animationTime);

				// Add the text to the rating info box
				$("<p />")
					.html($(this).html())
					.appendTo(ratingInfobox);

				// Call the colourize function with the given index
				colourizeRatings($(this).parent().index());
			}, function() {
                $("<p />")
                    .html("")
				// Fade out the rating information box
				ratingInfobox
					.stop()
					.animate({ opacity : 0}, animationTime);

				// Restore all the rating to their original colours
				$("#rating li a").stop().animate({ backgroundColor : "#333" } , animationTime);
			});

			// Prevent the click event and show the rating
			$("#rating li a").click(function(e) {
				if(if_login!=""){
					e.preventDefault();
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
						            	  $("#grade_bad").html("差（"+grade_count+")");
							          }else if(grade==2){
								          $("#grade_well_hidden").attr("value",grade_count);
								          $("#grade_well").html("良好（"+grade_count+")");
									  }else if(grade==3){
									      $("#grade_excellent_hidden").attr("value",grade_count);
									      $("#grade_excellent").html("优秀（"+grade_count+")");
									   }
					                }

					            },
					           error: function(XMLHttpRequest, textStatus, errorThrown) {
					                        alert(XMLHttpRequest.status);
					                        alert(XMLHttpRequest.readyState);
					                        alert(textStatus);
					                    }
					        });
				}else{
					submit_compliant();
                    $('html,body').animate({scrollTop: '0px'}, 800);
				}
			});

//=========================================================评论滚动=========================================//
     $('blockquote').quovolver(500,600);
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


//=========================================================用户登陆=========================================//
    document.onkeydown = function(e){
	    var ev = document.all ? window.event : e;
	    if(ev.keyCode==13) {
	    	 var wx_email=$("#username").attr("value");
         var wx_password=$("#password").attr("value");
         var url ='<?php echo site_url('home/login'); ?>';
         $.ajax({
         type:"post",
         data:({'wx_email': wx_email, 'wx_password': wx_password}),
         url:url,
         success: function(result)
             {
             	if(result=='success'){
             		alert("欢迎回来");
             		$.unblockUI();
             		window.location.reload();
                 }else if(result=='no-user'){
                     alert("没有该用户");
                 }else if (result=='passwd-wrong'){
                 	 alert("密码错误");
                 }
                 else if (result=='database-wrong'){
                 	alert("数据库连接失败");
                 }

             },

            error: function(XMLHttpRequest, textStatus, errorThrown) {
                         alert(XMLHttpRequest.status);
                         alert(XMLHttpRequest.readyState);
                         alert(textStatus);
                     }
         });

	     }
	};

$("#signin_submit").click(function(){
    var wx_email=$("#username").attr("value");
    var wx_password=$("#password").attr("value");
    var url ='<?php echo site_url('home/login'); ?>';
    $.ajax({
    type:"post",
    data:({'wx_email': wx_email, 'wx_password': wx_password}),
    url:url,
    success: function(result)
        {
        	if(result=='success'){
        		alert("欢迎回来");
        		$.unblockUI();
        		window.location.reload();
            }else if(result=='no-user'){
                alert("没有该用户");
            }else if (result=='passwd-wrong'){
            	 alert("密码错误");
            }
            else if (result=='database-wrong'){
            	alert("数据库连接失败");
            }

        },

       error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                }
    });
});

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
				             str+="<input type='button' class='button_c' onclick='unfollow_user()' value='取消关注' id='unfollow'>";
				             $("#if_follow").html(str);
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


<div class="backcolor_body">
	<div class="body" style="padding: 20px 20px 0px 20px;width:1026px" id="page">
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
  					echo"<span id='if_follow'><input type='button' class='button_c' onclick='unfollow_user()' id='unfollow' value='取消关注'></span>";
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
		<div id="content" style="float: left;width:580px;border:0;">
		  <div class="post" style="width: 500px;padding: 0px 20px;">

			<div class="entry">
				<p id="documentViewer" class="" style="background-color:#fff;margin: 0 auto;width:555px;height:730px;box-shadow: inset 0 0 10px rgb(150, 153, 167);z-index:10;"></p>
				<p style="font-size: 20px;padding-left: 30px;margin: 0 auto;color:#337fe5;">打分</p>
				<p>	<ul id="rating">
					<li><a href="#" class="common_show_login_win" id="grade_bad">差(<?php echo $grade_bad_count;?>)</a><input type="hidden" id="grade_bad_hidden" value="<?php echo $grade_bad_count;?>"></li>
					<li><a href="#" class="common_show_login_win" id="grade_well">良好(<?php echo $grade_well_count;?>)</a><input type="hidden" id="grade_well_hidden" value="<?php echo $grade_well_count;?>"></li>
					<li><a href="#" class="common_show_login_win" id="grade_excellent">优秀(<?php echo $grade_excellent_count;?>)</a><input type="hidden" id="grade_excellent_hidden" value="<?php echo $grade_excellent_count;?>"></li>
					</ul>
				</p>
				 <input type="hidden" id="data_id" value="<?php echo $data_id;?>">
				 <input type="hidden" id="data_name" value="<?php echo $data_name;?>">
				 <input type="hidden" id="data_user_id" value="<?php echo $user_id;?>">
				 <input type="hidden" id="user_name" value="<?php echo $user_name;?>">
        		<p><textarea style="width:530px;" placeholder="Write your comments here" id="comment_content"></textarea></p>
        		<p><input type="button" class="button_c common_show_login_win" style="width:128px" name="comment_button" id="comment_button" value="提交你的评论"></p>

            <div class="card_total" style="width:966px;height:155px;">
              <div class="card_items" id="card_items_data">
                <?php
                  if(isset($data_comment)&&$data_comment){
                    foreach ($data_comment as $key => $com) {
                      echo "<div class='card_item card_item_panel'>";
                      // echo "<div class='card_delete'></div>";
                      echo "<p style='height: 100px;word-break: break-all;'>".$com['comment_content']."</p>";
                      echo "<div class='card_footer' style='padding-top: 4px;height: 35px;'><p style='margin:0;'>";
                      echo "<span style='color:#4c76ac;'>".$com['user_name']."</span></br><span>&nbsp;&nbsp;--".$com['comment_time'];
                      echo "</span></p></div>";
                      echo "</div>";
                    }
                  }else{
                    echo "<div class='card_item card_item_panel'>";
                      // echo "<div class='card_delete'></div>";
                      echo "<p>还没有评论</p>";
                      // echo
                      echo "</div>";
                  }
                ?>

              </div>
            </div>
            <?php if(isset($data_comment)&&$data_comment&&count($data_comment)>6){?>
            <div class="card_arrow" style="width:982px;margin:-106px 0 0 -8px;">
                <div class="card_arrow_left" id="btn-left"></div>
                <div class="card_arrow_right" id="btn-right"></div>
            </div>
            <?php }?>
			</div>
		  </div>

		</div><!-- end #content -->
		<div id="sidebar" style="float: right;width: 400px;color: #666666;">
			<ul>
			 <li style="padding: 10px 20px 0px 10px;margin-bottom: 0px;">
			     <h2>资料详情</h2>
				<ul style="font-size:15px">
     				<li>
                        <a class="datadetailLi" href="#"><span style="color:#337fe5;"><?php if (isset($data_swfpath) && $data_swfpath != "") echo $data_name; else echo ""; ?>&nbsp;</span></a>
                    </li>
    				<li>
                        <a class="datadetailLi allmessage" style="color:#337fe5;" href="#" onclick="show_userInfo(<?php echo $user_id;?>)" ><?php if (isset($user_name) && $user_name != "") echo $user_name; else echo ""; ?> </a>
                        |<?php if (isset($data_uploadtime) && $data_uploadtime != "") echo $data_uploadtime; else echo ""; ?>
                    </li>
    				<li>
                        <span class="datadetailLi">分类：</span>
    				    <a class="datadetaila" style="color:#337fe5;" href="<?php echo site_url("primary/wxc_search/search_by_nature/$data_nature_id");?>" >
    				        <?php if (isset($data_nature_name) && $data_nature_name!= "") echo $data_nature_name; else echo ""; ?>
    				    </a>
                    </li>
                    <li>
                        <span class="datadetailLi">学校：</span>
        				<a class="datadetaila" style="color:#337fe5;" href="<?php echo site_url("primary/wxc_search/search_by_area/$data_area_id");?>" >
        				    <?php if (isset($data_area_name) && $data_area_name!= "") echo $data_area_name; else echo ""; ?>
        				</a>
                    </li>
                    <li>
    				    <span class="datadetailLi">下载数：<?php echo $dactivity_download_count?></span>
                    </li>
                    <li>
    				    <span class="datadetailLi">浏览数：<?php echo $dactivity_view_count?></span>
                    </li>
                    <li>
    				    <span class="datadetailLi">购买数：<?php echo $dactivity_buy_count?></span>
                    </li>
                    <li>
    				    <span class="datadetailLi">评论数：<?php echo $dactivity_comment_count?></span>
    				</li>
                    <li style="max-height: 53px;overflow: hidden;overflow-y: auto;">
                        <span class="datadetailLi">简介：<?php if (isset($data_summary) && $data_summary!= "") echo $data_summary; else echo ""; ?></span>
                    </li>
                    <li>
            			<?php
            			if (isset($_SESSION["wx_user_name"]) && $_SESSION["wx_user_name"] != ""){
            				echo "<a class='datadetail_dowload common_show_login_win' onclick='submit_compliant()' style='color:red;' href=".base_url()."core/wxc_download_note/download_file/".$data_id."></a>";
            			}else{
            				echo "<a class='show_loginForm datadetail_dowload common_show_login_win' style='color:red;' href='#'></a>";
            			}
            			?>
                        <a href="<?php echo base_url();?>primary/wxc_feedback/report_page?data_id=<?=$data_id?>" target="_blank"><input type="button" class="button_c" value="举报文档"></a>
                    </li>
				</ul>
			</li>
		</ul>

		</div>

		<div id="sidebar" style="float: right;width: 400px;">
			<ul>
				<li style="padding: 0px 20px 10px 10px;">
			<h2>最近浏览资料</h2>
				<ul style="font-size:15px">


 				<?php if($data_recent_view){
					foreach ($data_recent_view as $data_item){
						echo "<li class='datadetail_view_li'><a href='".base_url()."data/wxc_data/data_view/".$data_item['data_id']."'><span class='datadetail_view_span' style='color:#337fe5;'>";
						echo $data_item['data_name'];
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
