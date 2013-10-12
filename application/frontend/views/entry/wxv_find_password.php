<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Creamnote找回密码</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" href="/application/frontend/views/resources/css/style.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/menu-css.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_home.css" />
	<link rel="stylesheet" href="/application/frontend/views/resources/css/style.css" />
    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery-1.8.3.js"></script>

<script type="text/javascript">
<!-- Javascript functions -->
$(function() {
	$("#next_one").click(function(){
		var email=$("#email").attr("value");
		var url ="<?php echo site_url("home/find_password_first_step");?>";
		if(email == ""){
			$("#error_content").html("邮箱号不能为空");
			 $("#error_content").css("display","inline-block");
			 $("#error_content").css("color","red");
			 $("#email").attr("value","");
		}else{
			if (email.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1){
				$.ajax({
			        type:"post",
			        data:({'user_email': email}),
			        url:url,
			        //dataType:"json",
			        success: function(result)
			            {
			                if(result=='success'){
			                	 var str="";
			                	 str+="找回密码验证邮件已发送到您的邮箱,请到你注册的邮箱查收,填在下面！（请勿关闭此页）<br/>";
			                	 str+="<input type='text' name='back_code' id='back_code'>";
			                	 str+="<div class='reg_error' id='error_content' style='display:none;'>";
			                	 $("#content_change").html(str);
			                	 str = "";
			                	 str+="<input type='button' name='' class='button_c' id='next_two' onclick='next_two()' value='下一步'>";
			                	 $("#button_change").html(str);
			                	 $("#title").html("输入验证码");
				              }else if(result="has-no-email"){
								 $("#error_content").html("网站不存在该邮箱");
								 $("#error_content").css("display","inline-block");
								 $("#error_content").css("color","red");
								 $("#email").attr("value","");
					          }
			            },
			             error: function(XMLHttpRequest, textStatus, errorThrown) {
	                        alert(XMLHttpRequest.status);
	                        alert(XMLHttpRequest.readyState);
	                        alert(textStatus);
	                    }
			        });
			} else{
				$("#error_content").html("请输入正确邮箱格式");
				$("#error_content").css("display","inline-block");
				$("#error_content").css("color","red");
			 	$("#email").attr("value","");
			}
		}

	});

});
function next_two(){
	var back_code=$("#back_code").attr("value");
	var url ="<?php echo site_url("home/find_password_sec_step");?>";
	if(back_code == ""){
		$("#error_content").html("验证码错误");
		$("#error_content").css("display","inline-block");
		$("#error_content").css("color","red");
	}else{
		$.ajax({
	        type:"post",
	        data:({'auth_code': back_code}),
	        url:url,
	        //dataType:"json",
	        success: function(result)
	            {
	                if(result=='success'){
	                	 var str="";
	                	 str+="&nbsp;&nbsp;新密码&nbsp;&nbsp;<input type='password' name='new_password' id='new_password'><br/>";
	                	 str+="重复密码<input type='password' name='repeat_new_password' id='repeat_new_password'>";
	                	 str+="<div class='reg_error' id='error_content' style='display:none;'>";
	                	 $("#content_change").html(str);
	                	 str="";
	                	 str+="<input type='button' name='' class='button_c' id='next_three' onclick='next_three()' value='完成'>";
	                	 $("#button_change").html(str);
	                	 $("#title").html("修改密码");
		              }else if(result="failed"){
		              	$("#error_content").html("验证码错误");
						$("#error_content").css("display","inline-block");
						$("#error_content").css("color","red");
						$("#back_code").attr("value","");
			          }
	            },
	             error: function(XMLHttpRequest, textStatus, errorThrown) {
	                        alert(XMLHttpRequest.status);
	                        alert(XMLHttpRequest.readyState);
	                        alert(textStatus);
	                    }
	        });

	}

}

function next_three(){
	var password=$("#new_password").attr("value");
	var repeat_password=$("#repeat_new_password").attr("value");
	var url ="<?php echo site_url("home/find_password_third_step");?>";
	if(password == ""&&repeat_password == ""){
		$("#error_content").html("密码不能为空");
		$("#error_content").css("display","inline-block");
		$("#error_content").css("color","red");
	}else if(password != repeat_password){
		$("#error_content").html("两次密码不一致");
		$("#error_content").css("display","inline-block");
		$("#error_content").css("color","red");
		$("#new_password").attr("value","");
		$("#repeat_new_password").attr("value","");
	}else{
		$.ajax({
	        type:"post",
	        data:({'new_password': password}),
	        url:url,
	        //dataType:"json",
	        success: function(result)
	            {
	                if(result=='success'){
	                	location.href='<?php echo site_url('home/personal'); ?>';
		              }else if(result=="failed"){
						 alert("密码修改失败");
			          }
	            },
	             error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                }
	        });

	}

}
</script>

</head>
<body>
	<?php include  'application/frontend/views/share/header_home.php';?>

	<div class="body" style="min-height: 510px;border-top: 8px solid #839acd;padding: 50px 0;">
      <div class="reg_frame" id="reg_frame">

        <h2 id="title">输入邮箱号</h2>

        <div class="reg_put" id="content_change">
          <input type="text" name="email" id="email">
          <div class="reg_error" id="error_content" style="display:none;"></div>
        </div>
         <div class="reg_put" style="padding-left: 204px;" id="button_change">
          <input type="button" class="button_c" name="" id="next_one" value="下一步">
        </div>

      </div>

      <div class="clear"></div>
	</div>



<?php include  'application/frontend/views/share/footer.php';?>
</body>

</html>
