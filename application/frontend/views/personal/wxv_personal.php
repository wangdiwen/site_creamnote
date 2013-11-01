<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Creamnote - <?php if (isset($_SESSION["wx_user_name"]) && $_SESSION["wx_user_name"] != "") echo $_SESSION["wx_user_name"]; else echo ""; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/reset.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_home.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/style.css" />
     <link rel="stylesheet" href="/application/frontend/views/resources/css/poshytip/tip-darkgray/tip-darkgray.css" type="text/css" />
    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery-1.8.3.js"></script>
    <script type='text/javascript' src='/application/frontend/views/resources/js/common.js'></script>
    <script type='text/javascript' src='/application/frontend/views/resources/js/jquery.poshytip.js'></script>
    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery-ui-1.10.3.custom.js"></script>
<script type="text/javascript">
<!-- Javascript functions -->

$(document).ready(function(){
//====================================================jquery-ui可拖动弹框=========================================//
    $( "#box" ).draggable();

	//alert(1)
	$('ol .children').css("display","none");

	$("li").hover(function() {
		//alert($(this).find('ol'))
		$(this).click(function(){
			$(this).find('.children').css("display","block");
		});

	}, function() {
		$(this).find('.children').css("display","none");
	});

	//侧边隐藏栏
	var docked = 0;

    $("#dock li ul").height($(window).height());

    $("#dock .dock").click(function(){
        $(this).parent().parent().addClass("docked").removeClass("free");

        docked += 1;
        var dockH = ($(window).height()) / docked
        var dockT = 0;

        $("#dock li ul.docked").each(function(){
            $(this).height(dockH).css("top", dockT + "px");
            dockT += dockH;
        });
        $(this).parent().find(".undock").show();
        $(this).hide();


    });

     $("#dock .undock").click(function(){
        $(this).parent().parent().addClass("free").removeClass("docked")
            .animate({left:"-300px"}, 200).height($(window).height()).css("top", "0px");

        docked = docked - 1;
        var dockH = ($(window).height()) / docked
        var dockT = 0;

        $("#dock li ul.docked").each(function(){
            $(this).height(dockH).css("top", dockT + "px");
            dockT += dockH;
        });
        $(this).parent().find(".dock").show();
        $(this).hide();


    });

    $("#dock li").hover(function(){
        $(this).find("ul").animate({left:"40px"}, 200);
        $("#Webfonts").css("display","none");
    }, function(){
        $(this).find("ul.free").animate({left:"-300px"}, 200);
        $("#Webfonts").css("display","block");
   });
//判断是否有消息
    if($("#messages_exist").attr("value") == "1"){
        blinklink();
    }

    $("._card_edit").poshytip({
    	className:'tip-darkgray',
  	})

});

//查看信息
function viewMessage(the,notify_id,notify_type,notify_params){
	 var url = "<?php echo site_url('/primary/wxc_personal/get_single_notify'); ?>";
	 $.ajax({
		    type:"post",
		    data:({'notify_id': notify_id,'notify_type': notify_type,'notify_params':notify_params}),
		    url:url,
		    dataType:"json",
		    success: function(result)
		        {
		    		    var str="";
          			var i ;
          			var startid;
          			var user_id_list="";
          			if(notify_type==1){ //反馈
          				for(i in result){
              				user_id_list += result[i]['user_id']+",";
                  			if(i==0){
                 				str+="<div class='feed_back_item_personal'><div class='feed_back_item_avator'>";
              	                str+="<img alt='7956af44bf4c77439bfbc84c5f8ab104' height='48' src='"+result[i]['head_url']+"' width='48'></div>";
              	        		str+="<div class='feed_back_item_body'><div class='feed_back_item_head'>";
              	        		str+=result[i]['user_name']+" 说：";//发起者名字
              	        		str+="<input type='hidden' id='top"+result[i]['feedback_id']+"' value='"+result[i]['feedback_content']+"'></div>";
              	        		str+="<p class='feed_back_content'>"+result[i]['feedback_content']+"</p><div class='feed_back_item_bottom'>";//内容
              	        		str+=result[i]['feedback_time']+" <a class='feed_back_reply_btn'  href='#' onclick='reply("+result[i]['feedback_id']+")'>回复</a>";
              	        		str+="<input type='hidden' id="+result[i]['feedback_id']+" ></div>";
              	        		str+="<div class='feed_back_comment_list' id='comment"+result[i]['feedback_id']+"'>";
              	        		startid = result[i]['feedback_id'];
                          	}else{
                          		str+= "<div class='feed_back_comment'><img alt='Ul34411334-12' height='30' src='"+result[i]['head_url']+"' width='30'>";
                          		str+= "<p class='feed_back_comment_content'>";
                          		str+= result[i]['user_name']+": "+result[i]['feedback_content'];
                          		str+= "</p></div>";
                            }
                  		}
                			str+="<div id='start"+startid+"'>";
        	        		str+="<input type='hidden' name='feedback_id'>"	;
        	        		str+="<input type='text' class='feed_back_comment_content_input' style='width: 289px;' id='rel"+startid+"' >";
        	        		str+="<input type='submit' style='height: 32px;' onclick='comment("+startid+")' class='feed_back_comment_content_submit_btn button_c'  value='回复'>";
        	        		str+="</div></div></div></div>";
        	        		str+="<input type='hidden' id='user_id_list' value='" +user_id_list+ "'/>";
                      $("#message_title").html("反馈信息");
                			$("#messageshow").html(str);
                			$("#start"+startid).css("display","none");
                      $("#return_message").css("display","none");

                      var feedback_count = $("#feedback_red").text();
                      if(feedback_count==1){
                          $("#feedback_red").html("")
                      }else{
                          feedback_count = feedback_count-1;
                          $("#feedback_red").html(feedback_count--)
                      }
                  	}else if(notify_type==3){ //评论
          						for(i in result){
          							if(i==0){
          								var data_url = "<?php echo site_url('data/wxc_data/data_view/')?>"+"/"+result[i]['data_id'];
          								str+="<div style='text-align:center;margin-top: 10px;'>关于《<a style='color:blue;' href='"+data_url+"'>"+result[i]['data_name']+"</a>》的评论信息</div>";
          								//str+="<div>"+result[i]['user_name']+":<br/>"+result[i]['comment_content']+"--"+result[i]['comment_time']+"</div>";
          							}else{
          								str+="<div class='feed_back_comment'><img style='width:37px;height:37px;' height='30' src="+result[i]['head_url']+" width='30'><span style='padding-left:5px'>"+result[i]['user_name']+":</span><br/><span style='padding-left:15px;'>"+result[i]['comment_content']+"<span class='fr'>--"+result[i]['comment_time']+"</span></span></div>";
          							}
          						}
                      $("#message_title").html("评论信息");
          						$("#messageshow").html(str);
                      $("#return_message").css("display","none");

                      var comment_count = $("#comment_red").text();
                      if(comment_count==1){
                          $("#comment_red").html("")
                      }else{
                          comment_count = comment_count-1;
                          $("#comment_red").html(comment_count--)
                      }
                    }else if(notify_type==2){ //留言
          						for(i in result){
          							//var data_url = "<?php echo site_url('data/wxc_data/data_view/')?>"+"/"+result[i]['data_id'];
          							if(i==0){
          								str+="<div class='feed_back_comment' style='color:black;padding-top: 10px;'><img style='width:37px;height:37px;' height='30' src="+result[i]['head_url']+" width='30'><span style='padding-left:5px'><span>"+result[i]['user_name']+":</span><br/><span style='padding-left:15px;'>"+result[i]['message_content']+"<span class='fr'>--"+result[i]['message_time']+"</span></span></div>";
          								str+="<input type='hidden' id='message_user_id' value='"+result[i]['message_user_id']+"'>"
          							}else{
          								str+="<div style='color:black;'>"+result[i]['user_name']+"&nbsp;&nbsp;says:<br/>&nbsp;&nbsp;"+result[i]['message_content']+"--"+result[i]['message_time']+"</div>";
          							}

          						}
                      $("#message_title").html("留言信息");
          						$("#messageshow").html(str);
                      $("#return_message").css("display","block");

                      var message_count = $("#message_red").text();
                      if(message_count==1){
                          $("#message_red").html("")
                      }else{
                          message_count = message_count-1;
                          $("#message_red").html(message_count)
                      }
                    }else if(notify_type == 4){//系统通知
                        for(i in result){
                            //var data_url = "<?php echo site_url('data/wxc_data/data_view/')?>"+"/"+result[i]['data_id'];
                            if(i==0){
                                str+="<div style='color:black;'>"+result[i]['notify_title']+"</div>";
                                str+="<div style='color: black; padding-top: 10px;'>"+result[i]['notify_content']+"</div>";
                                str+="<div style='color: black; float:right;padding-top: 10px;'>--"+result[i]['notify_time']+"</div>";
                            }else{

                            }

                        }
                        $("#return_message").css("display","none");
                        $("#message_title").html("系统通知");
                        $("#messageshow").html(str);

                        var system_count = Number($("#system_red").text());
                        if(system_count==1){
                            $("#system_red").html("")
                        }else{
                            system_count = system_count-1;
                            $("#system_red").html(system_count)
                        }
                    }

		        },

		       error: function(XMLHttpRequest, textStatus, errorThrown) {
		                    alert(XMLHttpRequest.status);
		                    alert(XMLHttpRequest.readyState);
		                    alert(textStatus);
		                }
		    });
    $(the).html("");
}

//显示回复
function reply(feedback_id){
	$("[id *=start]").css("display","none");
	$("#start"+feedback_id).css("display","block");
  $("#rel"+feedback_id).focus();
	//rerutn false;
}
//评论回复
function comment(feedback_id){
	 	var url ='<?php echo site_url('/primary/wxc_feedback/follow_feedback'); ?>';
	    var comment_content=$("#rel"+feedback_id).attr("value");
	    var user_id_list=$("#user_id_list").attr("value");
	    var top_content=$("#top"+feedback_id).attr("value");
	    $.ajax({
	    type:"post",
	    data:({'feedback_content': comment_content,'feedback_id': feedback_id,'user_id_list':user_id_list,'feedback_topic':top_content}),
	    url:url,
	    success: function(result)
	        {
	        	if(result=="nologin"){
					   alert("请先登录");
  			    }else{
  			    	var user_name = result.split(",")[0];
              var head_url = result.split(",")[1];
  	        	//var feedback_id =result.split(",")[0];
  	        	if(result!=""){
  	        		var str="";
  	        		str+="<div class='feed_back_comment'>";
  	        		str+="<img alt='Ul34411334-12' height='30' src='"+head_url+"' width='30'>"	;
  	        		str+="<p class='feed_back_comment_content'>"+user_name+"："+comment_content+"</p></div>";
  	        		$("#start"+feedback_id).after(str);
  	        		$("[id *=rel]").val("");
  	        		$("[id *=start]").css("display","none");
  	            }
  				  }
            // close_message_dialog();
	        },

	       error: function(XMLHttpRequest, textStatus, errorThrown) {
	                    alert(XMLHttpRequest.status);
	                    alert(XMLHttpRequest.readyState);
	                    alert(textStatus);
	                }
	    });

}

$(function() {
	//弹出消息
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
    //提交留言
	$("#message_button").click(function(){
		var message_content = $("#message_content").attr("value");
		var data_user_id = $("#message_user_id").attr("value");
		var url = '<?php echo site_url('primary/wxc_message/add_message'); ?>';
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
                    close_message_dialog();
		              }

		            },
		           error: function(XMLHttpRequest, textStatus, errorThrown) {
		                        alert(XMLHttpRequest.status);
		                        alert(XMLHttpRequest.readyState);
		                        alert(textStatus);
		                    }
		        });
		});
//=========================================================删除资料=========================================//
    $("._card_delete").click(function(){
        var str = "";
        str += "<a style='padding-right: 20px;' href='javascript:void(0)' onclick='delete_data("+this.id+")'>是</a>";
        str += "<a href='javascript:void(0)' onclick='delete_display()' >否</a>";
        $(".tip_center").html(str);

        var position = getPosition(this);
        var top = (position.split("&")[0]-40)+"px";
        var left = (position.split("&")[1]-65)+"px";
        $(".creamnote_tips").css("left",left);
        $(".creamnote_tips").css("top",top);
        $(".tipforfix").css("display","block");
    });
});

//关注
function follow(the,user_id){
	var url = '<?php echo site_url('core/wxc_user_manager/add_follow'); ?>';
	var followed_user_id = user_id;
	 $.ajax({
	        type:"post",
	        url:url,
	        data:({'followed_user_id':followed_user_id
	            }),
	        success: function(result)
	            {
	              if(result=='success'){
                    $(the).html("已关注");
                    $(the).parent(".op_follow_section").addClass('al_follow_section');
                    $(the).parent(".op_follow_section").removeClass('op_follow_section');
                    $(the).attr("onclick","");
                    $("#like_count").html(Number($("#like_count").text())+1);
	                }

	            },
	           error: function(XMLHttpRequest, textStatus, errorThrown) {
	                        alert(XMLHttpRequest.status);
	                        alert(XMLHttpRequest.readyState);
	                        alert(textStatus);
	                    }
	        });
}
//取消关注
function unfollow(the,user_id){
	var url = '<?php echo site_url('core/wxc_user_manager/del_follow'); ?>';
	var followed_user_id = user_id;
	 $.ajax({
        type:"post",
        url:url,
        data:({'followed_user_id':followed_user_id
            }),
        success: function(result)
            {
              if(result=='success'){
                  $("#unf_"+user_id).css("display","none");
                  $("#like_count").html(Number($("#like_count").text())-1);
                }

            },
           error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.readyState);
                        alert(textStatus);
                    }
        });
}
//显示关注和被关注人
function show_follow(direction){
	var url="";
	if(direction==1){//关注谁
		url="<?php echo site_url('core/wxc_user_manager/you_like_who')?>";
	}else if(direction==2){
		url="<?php echo site_url('core/wxc_user_manager/who_like_you')?>";
	}
	$.ajax({
	    type:"post",
	    url:url,
	    dataType:"json",
	    success: function(result)
	        {
	        	var str="";
	        	var i;
	        	if(direction==1){
	        		str+="<div class='_grgh'>关注</div>";
              str+="<div class='filter _grgh' style='float:right;'>";
              str+="<div class='fl'><a href='"+$("#baseUrl").val()+"home/personal' style='cursor: pointer;'>返回</a></div>"
              str+="</div>";
              $("._data_title").html(str);
              str ="";
              str +="<div class='_card_total_common'>";
		        	for(i in result){
                str+="<div id='unf_"+result[i]['follow_followed_user_id']+"'>"
    						str+="<div class='follow_section fl'>";
    						str+="<a href='javascript:void(0)' class='hoveruser' id='"+result[i]['follow_followed_user_id']+"'>"+result[i]['user_name']+"</a>";
    						str+="</div>";
                str+="<div class='op_follow_section fl'>";
                str+="<a href='javascript:void(0)' onclick='unfollow(this,"+result[i]['follow_followed_user_id']+")'>取消关注</a>";
                str+="</div>";
                str+="</div>";
    				  }
				      str+="</div>";
		        }else if(direction==2){
		        	str+="<div class='_grgh'>粉丝</div>";
              str+="<div class='filter _grgh' style='float:right;'>";
              str+="<div class='fl'><a href='"+$("#baseUrl").val()+"home/personal' style='cursor: pointer;'>返回</a></div>"
              str+="</div>";
              $("._data_title").html(str);
              str ="";
              str +="<div class='_card_total_common'>";
		        	for(i in result){
		        		str+="<div class='follow_section fl'>";
    						str+="<a href='javascript:void(0)' class='hoveruser' id='"+result[i]['follow_user_id']+"'>"+result[i]['user_name']+"</a>";
                str+="</div>";
    						if(result[i]['has_followed']==="false"){
                  str+="<div class='op_follow_section fl'>";
                  str+="<a href='javascript:void(0)' onclick='follow(this,"+result[i]['follow_user_id']+")'>关注</a>";
                  str+="</div>";
      					}else{
                  str+="<div class='al_follow_section fl'>";
                  str+="<a href='javascript:void(0)' >已关注</a>";
                  str+="</div>";
                }

				      }
				      str+="</div>";
				    }
				    $("#buttons").html(str);
            hover_user();
	        },

	       error: function(XMLHttpRequest, textStatus, errorThrown) {
	                    alert(XMLHttpRequest.status);
	                    alert(XMLHttpRequest.readyState);
	                    alert(textStatus);
	                }
	    });
}
//=========================================================历史留言=========================================//
function history_message(){

var url="<?php echo site_url('primary/wxc_personal/history_message')?>";
$.ajax({
    type:"post",
    url:url,
    dataType:"json",
    success: function(result)
        {
    		var str="";
        	for(i in result){
            	if(result[i]['message_user_id']==<?php echo $_SESSION["wx_user_id"]?>){
            		str+="<div id='wraper2'><div style='height:5px;' ></div><div style='text-align: right;padding-right:20px'><div class='bubble'><div class='content'>";
            		str+=result[i]['message_content']+"("+result[i]['message_time']+")";
            		str+="</div></div><div>"+result[i]['user_name']+"</div></div></div>";
               	}else{
               		str+="<div id='wraper1'><div style='height:20px;' ></div><div style='text-align: left;padding-left:20px'><div class='bubble'><div class='content'>";
            		str+=result[i]['message_content']+"("+result[i]['message_time']+")";
            		str+="</div></div><div>"+result[i]['user_name']+"</div></div></div>";
                }

			}
        	$("#messageshow").html(str);
        	$("#return_message").html("");
        	$("#message_title").html("历史留言");

        },

       error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                }
    });
}

//=========================================================通知闪烁=========================================//
function blinklink() {
    if (!document.getElementById('Webfonts').style.color) {
        document.getElementById('Webfonts').style.color = "lime";
    }
    if (document.getElementById('Webfonts').style.color == "lime") {
        document.getElementById('Webfonts').style.color = "white";
    } else {
        document.getElementById('Webfonts').style.color = "lime";
    }
    timer = setTimeout("blinklink()", 500);
}
function stoptimer() {
    if("undefined" != typeof timer){
        clearTimeout(timer);
    }
    document.getElementById('Webfonts').style.color = "#bdd319";
    // alert(Math.round($("#dock").css("left").substr(0, $("#dock").css("left").length-2)))
    if(Math.round($("#dock").css("left").substr(0, $("#dock").css("left").length-2)) == '-50'){
        $("#dock").animate({'left':'0'},500);
        $("#Webfonts").animate({'left':'50'},500);
    }else{
        $("#dock").animate({'left':'-50'},500);
        $("#Webfonts").animate({'left':'0'},500);
    }
}

//=========================================================删除资料=========================================//

function delete_display(){
    $(".tipforfix").css("display","none");
}
function delete_data(dataid){
    var url ="<?php echo site_url("data/wxc_data/delete_data/");?>";
    $.ajax({
        type:"post",
        data:({'delete_data_id': dataid}),
        url:url,
        //dataType:"json",
        success: function(result)
            {
                if(result=='success'){
                     location.reload();
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

    <?php $nav_param = "home";?>
	<?php include  'application/frontend/views/share/navigation.php';?>
	<div id="Webfonts" onclick="stoptimer()">o</div>

    <div class="tipforfix" style="display: none;">
      <div class="creamnote_tips" style="position:absolute;">
        <div class="tip_card" style="width:120px;">
          <div class="tip_content">
             <div class=" fl">
              <div class="co fl">是否要删除资料?</div>
            </div>
            <div class="tip_center">
              <a style='padding-right: 20px;' href="">是</a>
              <a href="">否</a>
            </div>
          </div>
        </div>
        <div class="tip_arrow1"></div>
        <div class="tip_arrow2"></div>
      </div>
    </div>
	<!-- end #header -->
<div class="backcolor_body">
	<div class="body _body" style="min-height: 650px;">

	<?php
		if(isset($user_notify))
		{
			echo "<ul id='dock' style='left:-50px;'>";
			$feedback = $user_notify['feedback'];
			$message = $user_notify['message'];
			$comment = $user_notify['comment'];
			$system = $user_notify['system'];
            if(!$feedback&&!$message&&!$comment&&!$system){
                echo "<input type='hidden' id='messages_exist' value='0'>";
            }else{
                echo "<input type='hidden' id='messages_exist' value='1'>";
            }
			if($message||$message==null)
			{
				$counts = 0;
				$counts = count($message);
				if($counts!=0)
				{
					echo "<li id='message'><p id='message_red' style='padding-left:6px;color:red;margin: 0;'>".$counts."</p><ul class='free'>";
				}
				else
				{
					echo "<li id='message'><ul class='free'>";
				}
				echo "<li class='header'><a href='#' class='dock'>锁定</a><a href='#' class='undock'>解除锁定</a>留言信息   <a href='#' class='allmessage' onclick='history_message()'>历史留言</a></li>";
				foreach ($message as $meg)
				{
					echo "<li><a class='allmessage' href='#' onclick='viewMessage(this,".$meg['notify_id'].",".$meg['notify_type'].",".$meg['notify_params'].")'>".$meg['notify_content']."</a></li>";
				}
				echo "</ul></li>";
			}
			if($comment||$comment==null)
			{
				$counts = 0;
				$counts = count($comment);
				if($counts!=0)
				{
					echo "<li id='comment'><p id='comment_red' style='padding-left:6px;color:red;margin: 0;'>".$counts."</p><ul class='free'>";
				}
				else
				{
					echo "<li id='comment'><ul class='free'>";
				}
				echo "<li class='header'><a href='#' class='dock'>锁定</a><a href='#' class='undock'>解除锁定</a>评论信息</li>";
				foreach ($comment as $com)
				{
					echo "<li><a class='allmessage' href='#' onclick='viewMessage(this,".$com['notify_id'].",".$com['notify_type'].",".$com['notify_params'].")'>".$com['notify_content']."</a></li>";
				}
				echo "</ul></li>";
			}
			if($system)
			{
				$counts = count($system);
				echo "<li id='system'><p id='system_red' style='padding-left:6px;color:red;margin: 0;'>".$counts."</p><ul class='free'>";
				echo "<li class='header'><a href='#' class='dock'>锁定</a><a href='#' class='undock'>解除锁定</a>系统信息</li>";
				foreach ($system as $sys)
				{
					echo "<li><a class='allmessage' href='#' onclick='viewMessage(this,".$sys['notify_id'].",".$sys['notify_type'].",1)'>".$sys['notify_title']."</a></li>";
				}
				echo "</ul></li>";
			}
			if($feedback)
			{
				$counts = 0;
				$counts = count($feedback);
				echo "<li id='feedback'><p id='feedback_red' style='padding-left:6px;color:red;margin: 0;'>".$counts."</p><ul class='free'>";
				echo "<li class='header'><a href='#' class='dock'>锁定</a><a href='#' class='undock'>解除锁定</a>反馈信息</li>";
				foreach ($feedback as $fed)
				{
				echo "<li><a class='allmessage' href='#' onclick='viewMessage(this,".$fed['notify_id'].",".$fed['notify_type'].",".$fed['notify_params'].")'>".$fed['notify_content']."</a></li>";
				}
				echo "</ul></li>";
			}
			echo "</ul>";
		}

	?>
	<!------ 弹出消息 ------>
	<div class="overlay" id="overlay" style="display:none;"></div>
	<div class="box" id="box" style="z-index:1000;"> <a class="boxclose" id="boxclose"></a>
  	<h1 id="message_title">反馈信息</h1>
  	<p id="messageshow" style="max-height: 500px;overflow-y: scroll;">  </p>
  	<div id="return_message">
  	<p><textarea style='width:96%;height: 55px;' placeholder="给留言者一个回复" id="message_content"></textarea></p>
	   <p style='padding-top: 5px;'><input type="button" class='button_c' name="message_button" id="message_button" value="提交回复"></p>
	</div>
	</div>
	<!------ 弹出消息 ------>

	<div id="_content" class="_content">
		<div class="_post" id="post" style="padding-top:0;">
        <div class="_data_title">
            <div class="_grgh">个人干货</div>

            <div class="filter _grgh" style="float:right;">
            <div class="fl"><a class="active" id="button_one" onclick="javascript:delete_display()" rel="button_one_c" style="cursor: pointer;">已分享笔记</a></div>
            <div class="_changedata_nick fl"><a id="button_two" onclick="javascript:delete_display()" rel="button_two_c" style="cursor: pointer;">待审核笔记</a></div>
            <div class="_changedata_nick fl"><a id="button_three" onclick="javascript:delete_display()" rel="button_three_c" style="cursor: pointer;">需完善笔记</a></div>
            <?php if(isset($data_info['data_unpass'])){
              $data_unpass_count = count($data_info['data_unpass']);
              if($data_unpass_count>0){
            ?>
            <div class="_changedata_nick fl"><a id="button_four" onclick="javascript:delete_display()" rel="button_four_c" style="margin-right:0;cursor: pointer;">审核未通过笔记</a></div>
            <?php }}?>
            </div>
        </div>

        <div id="buttons">
		<div class="entry" id="button_one_c" style="display: inline-block;;">
            <div class="_card_total_common">
              <div class="_card_items_common" id="card_items_data1">
                <?php
                  if(isset($data_info['data_ok'])){
                    foreach ($data_info['data_ok'] as $key => $note) {
                      echo "<div class='_card_item _card_item_panel' ><div class='_item_actual'>";

                      echo "<div class='_card_delete' id='".$note['data_id']."'><img src='/application/frontend/views/resources/images/new_version/dy_delete.png'></div>";
                      echo "<div class='_card_content'>";
                      echo "<div class='card_head'>";
                      echo "<a href=".base_url()."data/wxc_data/data_view/".$note['data_id'].">".str_replace(array(" ","\r","\n"), array("","",""), $note['data_name'])."</a>";
                      echo "</div>";
                      echo "<div class='card_user _card_user'>";
                      echo "作者：".$note['user_name'];
                      echo "</div>";
                      echo "<div class='card_cate _card_cate card_padding'>";
                      echo "分类：<a href=".base_url()."primary/wxc_search/search_by_nature/".$note['data_nature_id']." >".$note['data_nature_name']."</a>";
                      echo $note['data_area_name_school']!=""?"|":"";
                      echo "<a href=".base_url()."primary/wxc_search/search_by_area/".$note['data_area_id_school']." >".$note['data_area_name_school']."</a>";
                      echo $note['data_area_name_major']!=""?"|":"";
                      echo "<a href=".base_url()."primary/wxc_search/search_by_area/".$note['data_area_id_major']." >".$note['data_area_name_major']."</a>";
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
                      echo "<div class='card_star' style='margin-top:5px;'><div class='card_star_blod' >";
                      echo "<input type='hidden' name='data_point' id='data_point' value='".$note['data_point']."'>";
                      echo "</div></div>";
                      echo "</div>";

                      // echo "<div class='card_count'>";
                      // echo "下载:".$note['dactivity_download_count']."&nbsp&nbsp&nbsp浏览:".$note['dactivity_view_count'];
                      // echo "</div>";

                      echo "<div class='_card_footer'>";
                      echo "<a href=".base_url()."core/wxc_download_note/download_file/".$note['data_id']."><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_down.png' class='_foot_content_down fl'></a>";
                      echo "<a href=".base_url()."data/wxc_data/complete_data_page/".$note['data_id']."><img src='/application/frontend/views/resources/images/new_version/creamnote_card_edit.png' class='_card_edit fl' title='修改资料'></a>";
                      echo "<span class='_foot_content_price fr'>".$note['data_price']."</span>";
                      //echo "<a href='#' ><span class='foot_content_heart'></span></a>";
                      echo "</div></div>";
                      echo "<div class='_card_bottom'>";
                      echo "<div class='fl'><img src='/application/frontend/views/resources/images/new_version/dy_card_view.png'><span>".$note['dactivity_view_count']."</span></div>";
                      echo "<div class='fr'>".$note['data_uploadtime']."</div>";
                      echo "</div></div></div>";
                    }
                  }
                ?>

              </div>
            </div>


		</div>
		<div class="entry" id="button_two_c" style="display:none;">

            <div class="_card_total_common">
              <div class="_card_items_common" id="card_items_data2">
                <?php
                  if(isset($data_info['data_waiting'])){
                    foreach ($data_info['data_waiting'] as $key => $note) {
                      echo "<div class='_card_item _card_item_panel' ><div class='_item_actual'>";
                      //echo "<a href=".base_url()."data/wxc_data/complete_data_page/".$note['data_id']."><div class='card_edit' title='修改资料'></div></a>";
                      echo "<div class='_card_delete' id='".$note['data_id']."'><img src='/application/frontend/views/resources/images/new_version/dy_delete.png'></div>";
                      echo "<div class='_card_content'>";
                      echo "<div class='card_head'>";
                      echo "<a href=".base_url()."data/wxc_data/data_view/".$note['data_id'].">".str_replace(array(" ","\r","\n"), array("","",""), $note['data_name'])."</a>";
                      echo "</div>";
                      echo "<div class='card_user _card_user'>";
                      echo "作者：".$note['user_name'];
                      echo "</div>";
                      echo "<div class='card_cate _card_cate card_padding'>";
                      echo "分类：<a href=".base_url()."primary/wxc_search/search_by_nature/".$note['data_nature_id']." >".$note['data_nature_name']."</a>";
                      echo $note['data_area_name_school']!=""?"|":"";
                      echo "<a href=".base_url()."primary/wxc_search/search_by_area/".$note['data_area_id_school']." >".$note['data_area_name_school']."</a>";
                      echo $note['data_area_name_major']!=""?"|":"";
                      echo "<a href=".base_url()."primary/wxc_search/search_by_area/".$note['data_area_id_major']." >".$note['data_area_name_major']."</a>";
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
                      echo "<div class='card_star' style='margin-top:5px;'><div class='card_star_blod' >";
                      echo "<input type='hidden' name='data_point' id='data_point' value='".$note['data_point']."'>";
                      echo "</div></div>";
                      echo "</div>";

                      // echo "<div class='card_count'>";
                      // echo "下载:".$note['dactivity_download_count']."&nbsp&nbsp&nbsp浏览:".$note['dactivity_view_count'];
                      // echo "</div>";

                      echo "<div class='_card_footer'>";
                      echo "<a href=".base_url()."core/wxc_download_note/download_file/".$note['data_id']."><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_down.png' class='_foot_content_down fl'></a>";
                      echo "<a href=".base_url()."data/wxc_data/complete_data_page/".$note['data_id']."><img src='/application/frontend/views/resources/images/new_version/creamnote_card_edit.png' class='_card_edit fl' title='修改资料'></a>";
                      echo "<span class='_foot_content_price fr'>".$note['data_price']."</span>";
                      echo "</div></div>";
                      echo "<div class='_card_bottom'>";
                      echo "<div class='fl'><img src='/application/frontend/views/resources/images/new_version/dy_card_view.png'><span>".$note['dactivity_view_count']."</span></div>";
                      echo "<div class='fr'>".$note['data_uploadtime']."</div>";
                      echo "</div></div></div>";
                    }
                  }
                ?>

              </div>
            </div>
		</div>

        <div class="entry" id="button_three_c" style="display:none;">

            <div class="_card_total_common">
              <div class="_card_items_common" id="card_items_data3">
                <?php
                  if(isset($data_info['data_undefine'])){
                    foreach ($data_info['data_undefine'] as $key => $note) {
                      echo "<div class='_card_item _card_item_panel' ><div class='_item_actual'>";
                      //echo "<a href=".base_url()."data/wxc_data/data_modify/".$note['data_id']."><div class='card_edit' title='完善资料'></div></a>";
                      echo "<div class='_card_delete' id='".$note['data_id']."'><img src='/application/frontend/views/resources/images/new_version/dy_delete.png'></div>";
                      echo "<div class='_card_content'>";
                      echo "<div class='card_head'>";
                      echo "<a href='#'>".str_replace(array(" ","\r","\n"), array("","",""), $note['data_name'])."</a>";
                      echo "</div>";
                      echo "<div class='card_user _card_user'>";
                      echo "作者：".$note['user_name'];
                      echo "</div>";
                      echo "<div class='card_cate _card_cate card_padding'>";
                      echo "分类：<a href=".base_url()."primary/wxc_search/search_by_nature/".$note['data_nature_id']." >".$note['data_nature_name']."</a>";
                      echo $note['data_area_name_school']!=""?"|":"";
                      echo "<a href=".base_url()."primary/wxc_search/search_by_area/".$note['data_area_id_school']." >".$note['data_area_name_school']."</a>";
                      echo $note['data_area_name_major']!=""?"|":"";
                      echo "<a href=".base_url()."primary/wxc_search/search_by_area/".$note['data_area_id_major']." >".$note['data_area_name_major']."</a>";
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
                      echo "<div class='card_star' style='margin-top:5px;'><div class='card_star_blod' >";
                      echo "<input type='hidden' name='data_point' id='data_point' value='".$note['data_point']."'>";
                      echo "</div></div>";
                      echo "</div>";

                      // echo "<div class='card_count'>";
                      // echo "下载:".$note['dactivity_download_count']."&nbsp&nbsp&nbsp浏览:".$note['dactivity_view_count'];
                      // echo "</div>";

                      echo "<div class='_card_footer'>";
                      echo "<a href=".base_url()."core/wxc_download_note/download_file/".$note['data_id']."><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_down.png' class='_foot_content_down fl'></a>";
                      echo "<a href=".base_url()."data/wxc_data/data_modify/".$note['data_id']."><img src='/application/frontend/views/resources/images/new_version/creamnote_card_edit.png' class='_card_edit fl' title='完善资料'></a>";
                      echo "<span class='_foot_content_price fr'>".$note['data_price']."</span>";
                      echo "</div></div>";
                      echo "<div class='_card_bottom'>";
                      echo "<div class='fl'><img src='/application/frontend/views/resources/images/new_version/dy_card_view.png'><span>".$note['dactivity_view_count']."</span></div>";
                      echo "<div class='fr'>".$note['data_uploadtime']."</div>";
                      echo "</div></div></div>";
                    }
                  }
                ?>

              </div>
            </div>

        </div>

        <div class="entry" id="button_four_c" style="display:none;">

            <div class="_card_total_common">
              <div class="_card_items_common" id="card_items_data4">
                <?php
                  if(isset($data_info['data_unpass'])){
                    foreach ($data_info['data_unpass'] as $key => $note) {
                      echo "<div class='_card_item _card_item_panel' ><div class='_item_actual'>";
                      //echo "<a href=".base_url()."data/wxc_data/data_modify/".$note['data_id']."><div class='card_edit' title='完善资料'></div></a>";
                      echo "<div class='_card_delete' id='".$note['data_id']."'><img src='/application/frontend/views/resources/images/new_version/dy_delete.png'></div>";
                      echo "<div class='_card_content'>";
                      echo "<div class='card_head'>";
                      echo "<a href='".base_url()."data/wxc_data/data_view/".$note['data_id']."'>".str_replace(array(" ","\r","\n"), array("","",""), $note['data_name'])."</a>";
                      echo "</div>";
                      echo "<div class='card_user _card_user'>";
                      echo "作者：".$note['user_name'];
                      echo "</div>";
                      echo "<div class='card_cate _card_cate card_padding'>";
                      echo "分类：<a href=".base_url()."primary/wxc_search/search_by_nature/".$note['data_nature_id']." >".$note['data_nature_name']."</a>";
                      echo $note['data_area_name_school']!=""?"|":"";
                      echo "<a href=".base_url()."primary/wxc_search/search_by_area/".$note['data_area_id_school']." >".$note['data_area_name_school']."</a>";
                      echo $note['data_area_name_major']!=""?"|":"";
                      echo "<a href=".base_url()."primary/wxc_search/search_by_area/".$note['data_area_id_major']." >".$note['data_area_name_major']."</a>";
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
                      echo "<div class='card_star' style='margin-top:5px;'><div class='card_star_blod' >";
                      echo "<input type='hidden' name='data_point' id='data_point' value='".$note['data_point']."'>";
                      echo "</div></div>";
                      echo "</div>";

                      // echo "<div class='card_count'>";
                      // echo "下载:".$note['dactivity_download_count']."&nbsp&nbsp&nbsp浏览:".$note['dactivity_view_count'];
                      // echo "</div>";

                      echo "<div class='_card_footer'>";
                      echo "<a href=".base_url()."core/wxc_download_note/download_file/".$note['data_id']."><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_down.png' class='_foot_content_down fl'></a>";
                      echo "<a href=".base_url()."data/wxc_data/complete_data_page/".$note['data_id']."><img src='/application/frontend/views/resources/images/new_version/creamnote_card_edit.png' class='_card_edit fl' title='修改资料'></a>";
                      echo "<span class='_foot_content_price fr'>".$note['data_price']."</span>";
                      echo "</div></div>";
                      echo "<div class='_card_bottom'>";
                      echo "<div class='fl'><img src='/application/frontend/views/resources/images/new_version/dy_card_view.png'><span>".$note['dactivity_view_count']."</span></div>";
                      echo "<div class='fr'>".$note['data_uploadtime']."</div>";
                      echo "</div></div></div>";
                    }
                  }
                ?>

              </div>
            </div>

        </div>

    </div>
	</div>

    </div><!-- end #content -->
		<div id="_sidebar" class="_sidebar">
			<ul>
				<li style="padding-top:0;">
					<h2>个人信息</h2>
					<div id="head_pic">

					<?php
						// if($user_level==1){
						// 	echo "<a href='#' style='padding-left:61px;'><img src='/application/frontend/views/resources/images/".$user_level."_avatar.jpg'/></a>";
						// }elseif ($user_level==2){
						// 	echo "<a href='#' style='padding-left:61px;'><img src='/application/frontend/views/resources/images/".$user_level."_avatar.jpg'/></a>";
						// }elseif ($user_level==3){
						// 	echo "<a href='#' style='padding-left:61px;'><img src='/application/frontend/views/resources/images/".$user_level."_avatar.jpg'/></a>";
						// }elseif ($user_level==4){
						// 	echo "<a href='#' style='padding-left:61px;'><img src='/application/frontend/views/resources/images/".$user_level."_avatar.jpg'/></a>";
						// }elseif ($user_level==5){
						// 	echo "<a href='#' style='padding-left:61px;'><img src='/application/frontend/views/resources/images/".$user_level."_avatar.jpg'/></a>";
						// }
					?>
                    <a href="https://en.gravatar.com/site/signup/" class="gravatar" target="_blank" title="点击到Gravatar注册<br/>替换修改你的全球唯一头像"><img src="<?php echo $head_url;?>"/></a>
					</div>
					<ul>
						<li class="_nick_personal1" style="text-align: center;font-size:16px; "><a style="padding: 0;" href="<?php echo site_url('primary/wxc_personal/update_userinfo_page'); ?>"><?php echo $user_info->user_name; ?></a></li>
						<div class="">
							<li class="_nick_personal2">
                                <div style="height:35px;">
                                    <div onclick="show_follow(2)" class="_personal_view fl" title="粉丝"><span><?php echo $liked_count?></span></div>
                                    <div onclick="show_follow(1)" class="_personal_store fr" title="关注"><span id="like_count"><?php echo $like_count?></span></div>
                                </div>
                                <div class="_word_break gravatar"  title="<?php echo $user_info->user_email; ?>"><a ><?php echo "邮箱: &nbsp;".$user_info->user_email; ?></a></div>
                                <div class="_word_break gravatar" title="<?php  echo substr($user_info->user_register_time,0,strlen($user_info->user_register_time)-3);   ?>"><a ><?php  echo "加入: &nbsp;".substr($user_info->user_register_time,0,strlen($user_info->user_register_time)-3);   ?></a></div>
							    <div class="_word_break gravatar" title="<?php echo $user_info->user_school; ?>/<?php echo $user_info->user_major; ?>"><a ><?php echo $user_info->user_school; ?>/<?php echo $user_info->user_major; ?></a></div>
                            </li>
						</div>
            <div>
              <span onclick="show_all_collect()" class="common_bule_button" style="position: absolute;margin-top: 10px;cursor: pointer;">收藏夹</span>
              <span onclick="order_history()" class="common_bule_button" style="position: absolute;margin-top: 60px;cursor: pointer;">订单记录</span>
              <span onclick="buy_history()" class="common_bule_button" style="position: absolute;margin-top: 110px;cursor: pointer;">购买记录</span>
              <span onclick="free_history()" class="common_bule_button" style="position: absolute;margin-top: 160px;cursor: pointer;">免费下载记录</span>
            </div>
					</ul>
				</li>
				<li>
					<!-- <ul class="attention">
						<li class="fl" ><a id="like_count" href="javascript:void(0)">关注 </a></li>
						<li class="fl" ><a id="liked_count" href="javascript:void(0)">粉丝 </a></li>
					</ul> -->
				</li>
			</ul>
		</div>
		<!-- end #sidebar -->
		<div style="clear: both;">&nbsp;</div>
	</div>

	<!-- end #body -->
	<?php include  'application/frontend/views/share/footer.php';?>
	<!-- end #footer -->
</div>
</body>

</html>
