<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Creamnote用户反馈</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/reset.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/text.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/960.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_home.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/style.css" />
    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="/application/frontend/views/resources/js/school.js"></script>

<script type="text/javascript">
<!-- Javascript functions -->
//隐藏回复
$(document).ready(function() {
 $("[id *=start]").css("display","none");
});
//显示回复
function reply(feedback_id){
	$("[id *=start]").css("display","none");
	$("#start"+feedback_id).css("display","block");
	//rerutn false;
}

//评论回复
function comment(feedback_id,user_id_list){
	 	var url ='<?php echo site_url('/primary/wxc_feedback/follow_feedback'); ?>';
	    var comment_content=$("#rel"+feedback_id).attr("value");
	    // var user_id_list=$("#user_id_list").attr("value");
        var user_id_list_p = "";
        for(i in user_id_list){
            user_id_list_p += user_id_list[i]+"&";
        }
	    var top_content=$("#top"+feedback_id).attr("value");
	    $.ajax({
	    type:"post",
	    data:({'feedback_content': comment_content,'feedback_id': feedback_id,'user_id_list':user_id_list_p,'feedback_topic':top_content}),
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
	        },

	       error: function(XMLHttpRequest, textStatus, errorThrown) {
	                    alert(XMLHttpRequest.status);
	                    alert(XMLHttpRequest.readyState);
	                    alert(textStatus);
	                }
	    });

}

$(function(){
$("#feed_back_form_submit_btn").click(function(){
    var url ='<?php echo site_url('/primary/wxc_feedback/create_feedback'); ?>';
    var feedback_content=$("#feedback_content").attr("value");
    $.ajax({
    type:"post",
    data:({'feedback_content': feedback_content}),
    url:url,
    success: function(result)
        {
    		if(result=="nologin"){
				alert("请先登录");
	    	}else{
	    		var user_name = result.split(",")[1];
	        	var feedback_id =result.split(",")[0];
	        	if(result!=""){
	        		var str="";
	                str+="<div class='feed_back_item'><div class='feed_back_item_avator'>";
	                str+="<img alt='7956af44bf4c77439bfbc84c5f8ab104' height='48' src='' width='48'></div>";
	        		str+="<div class='feed_back_item_body'><div class='feed_back_item_head'>";
	        		str+=user_name+" 说：</div>";//发起者名字
	        		str+="<p class='feed_back_content'>"+feedback_content+"</p><div class='feed_back_item_bottom'>";//内容
	        		str+="刚刚   <a class='feed_back_reply_btn'  href='#' onclick='reply("+feedback_id+")'>回复</a>";
	        		str+="<input type='hidden' id="+feedback_id+" ></div>";
	        		str+="<div class='feed_back_comment_list' id='comment"+feedback_id+"'><div id='start"+feedback_id+"'>";
	        		str+="<input type='hidden' name='feedback_id'>"	;
	        		str+="<input type='text' class='feed_back_comment_content_input' id='rel"+feedback_id+"' >";
	        		str+="<input type='submit' onclick='comment("+feedback_id+")' class='feed_back_comment_content_submit_btn' value='回复'>";
	        		str+="</div></div></div></div>";
	        		$("#feedback_startup_show").before(str);
	        		$("#start"+feedback_id).css("display","none");
	        		$("#feedback_content").val("");
	            }
                location.href="<?php echo site_url('primary/wxc_feedback/feedback_page'); ?>";
		    }
        },

       error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                }
    });
});

//滚动至顶部
$("#updown").css("top",window.screen.availHeight/2-50+"px");
$(window).scroll(function() {
	if($(window).scrollTop() >= 100){
		$('#updown').fadeIn(300);
	}else{
		$('#updown').fadeOut(300);
	}
});
$('#updown .up').click(function(){$('html,body').animate({scrollTop: '0px'}, 800);});
$('#updown .down').click(function(){$('html,body').animate({scrollTop: document.body.clientHeight+'px'}, 800);});
});
</script>

</head>

<body>
    <?php include  'application/frontend/views/share/header_home.php';?>

<div class="body">
<div class="container_16" style="background-image:none;">

 <div class="grid_4 prefix_6 suffix_6" style="text-align:center;">
        意见反馈
  </div>

 <div class="grid_10 prefix_3 suffix_3" style="text-align:center;">
     <textarea id="feedback_content" name="" style="height: 100px;width: 519px;"></textarea>
  </div>
   <div class="grid_10 prefix_3 suffix_3" style="text-align:center;">
     <div class="feed_back_form_action">
     <input type="submit" name="feed_back_submit" id="feed_back_form_submit_btn" value="提交">
     </div>
  </div>


  <div class="clear"></div>
<div class="grid_14 prefix_2">

       <p style="padding-left: 20px;">最新反馈信息：</p>
</div>
 <div class="grid_10 prefix_2 suffix_4" >
 	<div id="feedback_startup_show">
 	</div>
 </div>
 <div class="clear"></div>

 <div class="grid_10 prefix_2 suffix_4" >

	 <?php
		//echo $data_info;
		if(isset($feedback_topic)){
			foreach ($feedback_topic as $feed){

				$user_id_list = "";
                $top_feed_id  = "";
                foreach ($feed as $id => $topics)
                {
                	// $user_id_list .= $topics['user_id'].",";
                    $user_id_list[$id] = $topics['user_id'];
                    if ($id == 0)
                    {
                        //echo '话题：'.$topics['feedback_content'].'<br />';
                        echo "<div class='feed_back_item'><div class='feed_back_item_avator'>";
                        echo "<img  height='48' src=".$topics['head_url']." width='48'></div>";
                        echo "<div class='feed_back_item_body'>";
                        echo "<div class='feed_back_item_head'>";
                        echo $topics['user_name'];
                        echo "<input type='hidden' id='top".$topics['feedback_id']."' value='".$topics['feedback_content']."'>";
                        echo ":</div><p class='feed_back_content'>";
                        echo $topics['feedback_content'];
                        echo "</p><div class='feed_back_item_bottom'>";
                        echo $topics['feedback_time'];
                        echo "<a class='feed_back_reply_btn'  href='javascript:void(0)' onclick='reply(".$topics['feedback_id'].")'>回复</a></div>";
                        echo "<div class='feed_back_comment_list'>";
                        $top_feed_id = $topics['feedback_id'];
                    }
                    else
                    {
                        //echo '回复'.$topics['feedback_content'].'<br />';
                    	echo "<div class='feed_back_comment'><img  height='30' src=".$topics['head_url']." width='30'>";
                    	echo "<p class='feed_back_comment_content'>";
                    	echo $topics['user_name'].": ".$topics['feedback_content'];
                    	echo "</p></div>";
                    }

                }
                // $user_id_list = $user_id_list.substr($user_id_list,0, strlen($user_id_list)-1);
                // $user_id_list .="}";
                $user_id_list = json_encode($user_id_list);
                echo "<div id='start".$top_feed_id."'>";
                echo "<input type='text' class='feed_back_comment_content_input'  id='rel".$top_feed_id."' >";
                echo "<input type='submit' onclick='comment(".$top_feed_id.",".$user_id_list.")' id='feed_back_comment_content_submit_btn'  value='回复'>";
                echo "</div>";
                echo "</div></div></div>";
              	echo "<input type='hidden' id='user_id_list' value='" .$user_id_list. "'/>";
			}
		}
		//. base_url()."primary/wxc_personal/delete_data_by_id/".$info->data_id.
    	?>
        <div class="pagination" style="text-align: right;padding-top: 20px;"><?php echo $this->pagination->create_links(); ?></div>

    <!--滚动至顶部插件-->
	<div id="updown"><span class="up"></span><span class="down"></span></div>

  </div>
  <div class="clear"></div>

</div>
</div>
<?php include  'application/frontend/views/share/footer.php';?>

</body>

</html>
