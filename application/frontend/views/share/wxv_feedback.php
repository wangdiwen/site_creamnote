<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Creamnote用户反馈</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/reset.css" />
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
        if(comment_content == ""){
            warnMes("至少写点内容再提交吧，大侠！");
            return;
        }
	    $.ajax({
	    type:"post",
	    data:({'feedback_content': comment_content,'feedback_id': feedback_id,'user_id_list':user_id_list_p,'feedback_topic':top_content}),
	    url:url,
	    success: function(result)
	        {
	        	if(result=="nologin"){
					warnMes("亲，要先登录的哦!");
			    }else{
			    	var user_name = result.split(",")[0];
                    var head_url = result.split(",")[1];
		        	//var feedback_id =result.split(",")[0];
		        	if(result!=""){
		        		var str="";
		        		str+="<div class='feed_back_comment'>";
		        		str+="<img alt='Ul34411334-12' height='30' src='"+head_url+"' width='30'>"	;
		        		str+="<p class='feed_back_comment_content'>"+user_name+"："+comment_content+"</p></div>";
		        		$("#start"+feedback_id).before(str);
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
    if(feedback_content == ""){
        warnMes("至少写点内容再提交吧，大侠！");
        return;
    }
    $.ajax({
    type:"post",
    data:({'feedback_content': feedback_content}),
    url:url,
    success: function(result)
        {
    		if(result=="nologin"){
				warnMes("亲，要先登录的哦!");
	    	}else{
	    		var user_name = result.split(",")[1];
	        	var feedback_id =result.split(",")[0];
	        	if(result!=""){
	        		var str="";
	                str+="<div class='feed_back_item' style='margin-top:0;padding-bottom: 0;'><div class='feed_back_item_avator'>";
	                str+="<img alt='7956af44bf4c77439bfbc84c5f8ab104' height='48' src='' width='48'></div>";
	        		str+="<div class='feed_back_item_body'><div class='feed_back_item_head'>";
	        		str+=user_name+" 说：</div>";//发起者名字
	        		str+="<p class='feed_back_content'>"+feedback_content+"</p><div class='feed_back_item_bottom' style='margin-bottom:0;'>";//内容
	        		str+="刚刚   <a class='feed_back_reply_btn'  href='#' onclick='reply("+feedback_id+")'>回复</a>";
	        		str+="<input type='hidden' id="+feedback_id+" ></div>";
	        		str+="<div class='feed_back_comment_list' id='comment"+feedback_id+"'><div id='start"+feedback_id+"' style='padding-left:68px;'>";
	        		str+="<input type='hidden' name='feedback_id'>"	;
	        		str+="<input type='text' class='feed_back_comment_content_input' id='rel"+feedback_id+"' >";
	        		str+="<input type='submit' onclick='comment("+feedback_id+")' class='feed_back_comment_content_submit_btn' style='height: 32px;' class='button_c' value='回复'>";
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
});

</script>

</head>

<body>
    <?php include  'application/frontend/views/share/header_home.php';?>

<div class="body article_body" style="border-top: 8px solid #839acd;">
<div class="" style="background-image:none;">

 <div class="reg_frame _feedback_frame" style="text-align: left;border: 8px double rgb(185, 194, 197);">
        真诚期待您对Creamnote的批评指正，与我们共进步。</br>
        你可以在下面提交您的意见、疑问、建议、反馈。</br>
        我们会在第一时间回复您。
  </div>

 <div class="reg_frame _feedback_frame" style="text-align:center;width:715px">
    <div class="">
        <textarea id="feedback_content" class="_detail_comment" name="" style="height: 48px;width: 593px;"></textarea>
        <div class="_detail_buttonframe _feedback_textarea_frame">
            <input type="submit" name="feed_back_submit" id="feed_back_form_submit_btn"  class="button_c common_show_login_win" style="width:100px" name="comment_button" id="comment_button" value="提交反馈">
        </div>
    </div>

  </div>



	 <?php
		//echo $data_info;
		if(isset($feedback_topic)){
			foreach ($feedback_topic as $feed){
                echo " <div class='reg_frame _feedback_frame' >";
				$user_id_list = "";
                $top_feed_id  = "";
                foreach ($feed as $id => $topics)
                {

                	// $user_id_list .= $topics['user_id'].",";
                    $user_id_list[$id] = $topics['user_id'];
                    if ($id == 0)
                    {
                        //echo '话题：'.$topics['feedback_content'].'<br />';
                        echo "<div class='feed_back_item' style='margin-top:0;padding-bottom: 0;'><div class='feed_back_item_avator'>";
                        echo "<img  height='48' src=".$topics['head_url']." width='48'></div>";
                        echo "<div class='feed_back_item_body'>";
                        echo "<div class='feed_back_item_head'>";
                        echo $topics['user_name'];
                        echo "<input type='hidden' id='top".$topics['feedback_id']."' value='".$topics['feedback_content']."'>";
                        echo ":</div><p class='feed_back_content'>";
                        echo $topics['feedback_content'];
                        echo "</p><div class='feed_back_item_bottom' style='margin-bottom:0;'>";
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
                echo "<div id='start".$top_feed_id."' style=''>";
                echo "<input type='text' class='feed_back_comment_content_input'  id='rel".$top_feed_id."' >";
                echo "<input type='submit' onclick='comment(".$top_feed_id.",".$user_id_list.")' class='button_c' style='height: 32px;' id='feed_back_comment_content_submit_btn'  value='回复'>";
                echo "</div>";
                echo "</div>";
                echo "</div></div></div>";
              	echo "<input type='hidden' id='user_id_list' value='" .$user_id_list. "'/>";
			}
		}
		//. base_url()."primary/wxc_personal/delete_data_by_id/".$info->data_id.
    	?>
    <div class="pagination fr" style="padding-right: 150px;padding-top: 20px;padding-bottom: 20px;"><?php echo $this->pagination->create_links(); ?></div>

    <!--滚动至顶部-->
	<div id="updown"><span class="up transition"></span></div>

  </div>
  <div class="clear"></div>

</div>
</div>
<?php include  'application/frontend/views/share/footer.php';?>

</body>

</html>
