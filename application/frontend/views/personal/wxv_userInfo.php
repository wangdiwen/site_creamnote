<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php header("Content-type: text/html; charset=UTF-8"); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>账户设置</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="/application/frontend/views/resources/css/reset.css" />
<link rel="stylesheet" href="/application/frontend/views/resources/css/wx_home.css" />
<link rel="stylesheet" href="/application/frontend/views/resources/css/style.css" />
<script type="text/javascript"src="/application/frontend/views/resources/js/jquery-1.8.3.js"></script>
<script type="text/javascript"src="/application/frontend/views/resources/js/school.js"></script>
<style type="text/css">
input[type="password"],select,input[type=text], input[type="search"] {
    width: 360px;
    margin-left: 6px;
}
#thisform label, #thisform1 label, #thisform2 label {
    width: 68px;
    height: 25px;
    padding-top: 7px;
    text-align: right;
    float: left;
}
#thisform fieldset, #thisform1 fieldset, #thisform2 fieldset {
    border: 0;
    padding: 20px;
}
#thisform, #thisform1, #thisform2 {
    font-size: 12px;
    color: #999;
    -webkit-box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0.1);
    box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0.1);
}
</style>
<script type="text/javascript">
<!-- Javascript functions -->
//=========================================================修改基本用户信息=========================================//
$(function(){
	 if(window.localStorage){
	 	window.localStorage.setItem("home-username","xiewang@126.com");
	 }
     var active_roll_back = '<?php if (isset($sign_name) && $sign_name != "") echo $sign_name; else echo ""; ?>';
     if(active_roll_back != ""){
        // if(active_roll_back == "success"){
        successMes("账户激活成功,请确认你的支付宝签名为："+active_roll_back);
        update_account_page();
         // }else{
            // errorMes("");
         // }
     }


    $("#update_user").click(function(){
        var booleans = check_name($("#user_name").val());
        if(!booleans){
            errorMes("昵称已经存在");
            return;
        }
        if($("#user_name").val() == ""){
            errorMes("昵称不能为空");
            return;
        }
    	var wx_school_id = $('#hidden_school').attr("value");
        var wx_name=$("#user_name").attr("value");
        var wx_carea=$("#partment").attr("value");
        var wx_hobby=$("#user_hobby").attr("value");
        var wx_period=$("#user_period").attr("value");
        var wx_phone=$("#user_phone").attr("value");
        var url ="<?php echo site_url('primary/wxc_personal/update_base_info'); ?>";
        $.ajax({
        type:"post",
        data:({'phone':wx_phone,'period': wx_period, 'hobby': wx_hobby,'nice_name':wx_name,'major_id':wx_carea,'school_id':wx_school_id}),
        url:url,
        success: function(result)
            {
                if(result = "success"){
                    successMes("修改成功");
                }else{
                    successMes("修改失败");
                }


            },
             error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.readyState);
                        alert(textStatus);
                    }
        });

    });

    $("#nav_baseinfo").addClass("navhover");

});

//=========================================================判断昵称=========================================//
function check_name(user_name){
	 var url ="<?php echo site_url('primary/wxc_personal/check_nice_name'); ?>";
     var ret = "flase" ;
	 $.ajax({
	        type:"post",
	        data:({'nice_name':user_name}),
	        url:url,
            async: false,
	        success: function(result)
	            {
	               if(result=="failed"){
						errorMes("昵称已经存在");
                        ret =  false;
		           }else{
                        ret = true;
                   }

	            },
             error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            }
	        });
     return ret;
}

//=========================================================判断电话=========================================//
function check_phone(user_phone){
	 var url ="<?php echo site_url('primary/wxc_personal/check_phone'); ?>";
	 $.ajax({
	        type:"post",
	        data:({'phone':user_phone}),
	        url:url,
	        success: function(result)
	            {
	               if(result=="failed"){
						errorMes("电话号已经存在");
		           }

	            },
	             error: function(XMLHttpRequest, textStatus, errorThrown) {
	                        alert(XMLHttpRequest.status);
	                        alert(XMLHttpRequest.readyState);
	                        alert(textStatus);
	                    }
	        });
}
//========================================================学校插件=====================================================//
//弹出窗口
function pop(){
	//将窗口居中
	makeCenter();

	//初始化省份列表
	initProvince();

	//默认情况下, 给第一个省份添加choosen样式
	$('[province-id="1"]').addClass('choosen');

	//初始化大学列表
	initSchool(1);
}
//隐藏窗口
function hide()
{
	$('#choose-box-wrapper').css("display","none");
}
function initProvince()
{
	//原先的省份列表清空
	$('#choose-a-province').html('');
	for(i=0;i<schoolList.length;i++)
	{
		$('#choose-a-province').append('<a class="province-item" province-id="'+schoolList[i].id+'">'+schoolList[i].name+'</a>');
	}
	//添加省份列表项的click事件
	$('.province-item').bind('click', function(){
			var item=$(this);
			var province = item.attr('province-id');
			var choosenItem = item.parent().find('.choosen');
			if(choosenItem)
				$(choosenItem).removeClass('choosen');
			item.addClass('choosen');
			//更新大学列表
			initSchool(province);
		}
	);
}

function initSchool(provinceID)
{
	//原先的学校列表清空
	$('#choose-a-school').html('');
	var schools = schoolList[provinceID-1].school;
	for(i=0;i<schools.length;i++)
	{
		$('#choose-a-school').append('<a class="school-item" school-id="'+schools[i].id+'">'+schools[i].name+'</a>');
	}
	//添加大学列表项的click事件
	$('.school-item').bind('click', function(){
			var item=$(this);
			var school = item.attr('school-id');
			//更新选择大学文本框中的值
			$('#school_name').val(item.text());

			var wx_school=$("#school_name").attr("value");
			var url ="<?php echo site_url('data/wxc_data/get_depart_by_school'); ?>";
			//$("#form")[0].submit();
			$.ajax({
		        type:"post",
		        data:({'wx_school': wx_school}),
		        url:url,
		        dataType:"json",
		        success: function(result)
		            {
		                var str
		            	var i ;
		            	for(i in result){
		            		str +="<option value="+result[i]['carea_id']+">"+result[i]['carea_name']+"</option>"	;
			            	};
			            $("#partment").html(str)
			            get_school_id(wx_school);
		            },
		             error: function(XMLHttpRequest, textStatus, errorThrown) {
		                        alert(XMLHttpRequest.status);
		                        alert(XMLHttpRequest.readyState);
		                        alert(textStatus);
		                    }
		        });
		    //form.submit();


			//关闭弹窗
			hide();
		}
	);
}

function makeCenter()
{
	$('#choose-box-wrapper').css("display","block");
	$('#choose-box-wrapper').css("position","absolute");
	$('#choose-box-wrapper').css("top", Math.max(0, (($(window).height() - $('#choose-box-wrapper').outerHeight()) / 2) + $(window).scrollTop()) + "px");
	$('#choose-box-wrapper').css("left", Math.max(0, (($(window).width() - $('#choose-box-wrapper').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
}
//=========================================================获得学校id=========================================//
function get_school_id(wx_school){
	var url ="<?php echo site_url('data/wxc_data/get_area_id_by_school_name'); ?>";
	$.ajax({
        type:"post",
        data:({'school_name': wx_school}),
        url:url,
        dataType:"json",
        success: function(result)
            {
                $("#hidden_school").attr("value",result);
            },
             error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.readyState);
                        alert(textStatus);
                    }
        });
}
//=========================================================更新密码=========================================//
//局部更新页面，给出修改密码表单
function update_password_page(){
	var str="";
	str+="<div  id='thisform' ><fieldset><le id='le_title' style='margin-top: -52px;'>账户设置</le><p><label >原密码:</label>";
	str+="<input type='password' id='old_password' name='old_password' value='' ></p>";
	str+="<label >新密码:</label>";
	str+="<input type='password' id='new_password' name='new_password' value='' ></p>";
	str+="<label >重复密码:</label>";
	str+="<input type='password' id='repeat_new_password' name='repeat_new_password' value='' ></p>";
	str+="<p style='padding-left: 222px;'><input type='button' class='button_c' id='update_password' name='update_password' value='修改' onclick='update_password()'></p></fieldset></div>";

	$("#change_form").html(str);
    $("a").removeClass("navhover");
    $("#nav_pwd").addClass("navhover");
    // $("._grgh").html("密码修改");
}
//提交表单
function update_password(){
	var url ="<?php echo site_url('primary/wxc_personal/update_passwd'); ?>";
	var old_password=$("#old_password").attr("value");
	var new_password=$("#new_password").attr("value");
	var repeat_new_password=$("#repeat_new_password").attr("value");
	if(repeat_new_password!=new_password){
		errorMes("两次密码不一致");
		$("#new_password").attr("value","");
		$("#repeat_new_password").attr("value","");
	}else if(new_password==""){
		errorMes("密码不能为空");
	}else{
		$.ajax({
	        type:"post",
	        data:({'wx_password_old': old_password,'wx_password_new': new_password}),
	        url:url,
	        success: function(result)
	            {
	               if(result=="old-password-wrong"){
						errorMes("原始密码错误");
						$("#old_password").attr("value","");
						$("#new_password").attr("value","");
						$("#repeat_new_password").attr("value","");
	               }else if(result=="success"){
						successMes("修改成功");
						$("#old_password").attr("value","");
						$("#new_password").attr("value","");
						$("#repeat_new_password").attr("value","");
	               }else if(result=="failed"){
						errorMes("修改失败");
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
//=========================================================更新个人账户=========================================//
//局部更新页面，给出修改账户表单
function update_account_page(){
	var url="<?php echo site_url('primary/wxc_personal/user_account_info'); ?>";
	$.ajax({
        type:"post",
        data:({}),
        url:url,
        dataType:"json",
        success: function(result)
            {
            	if(result!=null){
            		var str="";
            		str+="<div  id='thisform' ><fieldset><le id='le_title' style='margin-top: -52px;'>账户设置</le><p style='height:30px;'><label style='padding-left: 55px;float: none;'>账户类型:</label>";
            		str+="<span style='padding-left: 5px;'>"+result['user_account_type']+"</span></p>";
                    str+="<p style='height:30px;'><label style='padding-left: 54px;float: none;'>账户状态:</label>";
                     if(result['user_account_active'] == 'false'){
                        str+="<span style='padding-left: 5px;'>未激活</span></p>";
                    }else{
                        str+="<span style='padding-left: 5px;'>已激活</span></p>";
                    }
                    str+="<p style='height:30px;'><label style='padding-left: 43px;float: none;' title='支付宝签名为您支付宝的真实姓名，他将作为提现时核对的标准' class='gravatar'>支付宝签名:</label>";
                    str+="<span style='height:30px;padding-left: 5px;' title='支付宝签名为您支付宝的真实姓名，他将作为提现时核对的标准' class='gravatar'>"+result['user_account_realname']+"</span></p>";

                    if(result['user_account_status'] == 0){
                        str+="<p style='border-bottom:1px dotted rgb(76, 118, 172);padding-bottom: 15px;'><label style='padding-left: 79px;float: none;'>账户:</label>";
                        str+="<input type='text' id='user_account_name' name='user_account_name' value='"+result['user_account_name']+"'>";
                        if(result['user_account_active']== 'false'){
                            str+="<input type='button' style='height:30px;' id='update_account' name='update_account' value='激活' class='button_c'  onclick='update_account(1)'></p>";
                        }else{
                            str+="<input type='button' style='height:30px;' id='update_account' name='update_account' value='更改账户' class='button_c'  onclick='update_account(2)'></p>";
                        }
                    }else if(result['user_account_status'] == 1){
                        str+="<p style='border-bottom:1px dotted rgb(76, 118, 172);padding-bottom: 15px;'><label style='padding-left: 79px;float: none;'>账户:</label>";
                        str+="<input type='text' id='user_account_name' name='user_account_name' value='"+result['user_account_name']+"' disabled>";
                        if(result['user_account_active']== 'false'){
                            str+="<input type='button' style='height:30px;' id='update_account' name='update_account' value='激活' class='button_c'  onclick='update_account(3)'></p>";
                        }else{
                            str+="<input type='button' style='height:30px;' id='update_account' name='update_account' value='更改账户' class='button_c'  onclick='update_account(3)'></p>";
                        }
                    }


                    str+="<p style='height:30px;padding-top: 15px;'><label style='padding-left: 54px;float: none;'>提现状态:</label>";
                     if(result['user_account_status'] == 0){
                        str+="<span id='user_account_status' style='padding-left: 5px;'>未提现</span></p>";
                    }else{
                        str+="<span id='user_account_status' style='padding-left: 5px;'>提现受理中</span></p>";
                    }

            		str+="<p style='height:30px;'><label style='padding-left: 31px;float: none;'>当前收益余额:</label>";
            		str+="<span id='user_account_money_leave' style='padding-left: 5px;'>￥"+result['user_account_money']+"</span></p>";
                    str+="<p style='height:30px;'><label style='padding-left: 43px;float: none;'>收益总累计:</label>";
                    str+="<span style='padding-left: 5px;'>￥"+result['user_account_total']+"</span></p>";

                    str+="<p style=''><label style='padding-left: 31px;float: none;'>醍醐提现口令:</label>";
                    str+="<span style='padding-left: 3px;'><input type='password' name='' style='display:none;' id='account_token' value=''>";
                    if(result['user_account_token'] == ""){
                        str+="<input type='button' id='token_button'  class='button_c' value='设置口令' onclick='set_token_input()' style='height:30px;'></span></p>";
                    }else{
                        str+="<input type='button' id='token_button' class='button_c' value='更改口令' onclick='set_token_input()' style='height:30px;'></span></p>";
                    }
                    // str+="<p id='token_ver' style='display:none;'><label style='padding-left: 43px;float: none;'>口令验证码:</label>";
                    // str+="<span ><input type='text' name='' id='token_ver_value' value='' onblur='ver_token(this)'>";
                    // str+="<input type='button' class='button_c' value='验证' onclick='ver_token_frommail()' style='height:30px;'></span></p>";

            		if(result['user_account_money']>=10){
                        var ten_count = parseInt(result['user_account_money']/10);
                        // var ten_count = parseInt(100/10);
                        str+="<p style='height:30px;'><label style='padding-left: 79px;float: none;'>提现:</label><span>";
                        str+="<select style='width: 381px;height:30px;' id='withdraw_count'>";
                        str+="<option>选择十元整数倍的提现金额</option>";
                        for(var i = 1; i<=ten_count;i++){
                            str+="<option value="+i+">￥"+i*10+"</option>";
                        }
                        str+="</select>"
                        str+="</span><input type='button' class='button_c' value='申请提现' onclick='show_token_window()' style='height:30px;'></p>";
                    }
                    // str+="<p><label style='padding-left: 32px;float: none;'>是否激活账户:</label><input style='padding-left: 5px;' type='button' class='button_c' id='' name='' value='激活' onclick=''></p></fieldset></div>";

            		$("#change_form").html(str);
                    $("a").removeClass("navhover");
                    $("#nav_account").addClass("navhover");
                    $(".gravatar").poshytip({
                        className:'tip-darkgray',
                      })
                    // $("._grgh").html("个人账户");
                }
            },
             error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.readyState);
                        alert(textStatus);
                    }
        });

}
//提交表单
function update_account(type){
    if(type == 3){
        errorMes("您的账户处于提现期间，暂时不能更改账户");
        return;
    }
    if(type == 1){
       var url ="<?php echo site_url('core/wxc_user_account/account_active'); ?>";
        var user_account_name=$("#user_account_name").attr("value");
        if(user_account_name==""){
            errorMes("账户不能为空");
        }else if(!com_check_eamil(user_account_name)){
            errorMes("账户不合法");
        }else{
            $.ajax({
                type:"post",
                data:({'account_email': user_account_name}),
                url:url,
                success: function(result)
                    {
                       if(result=="has_actived"){
                            errorMes("已激活，不可重新激活");
                       }else if(result=="failed"){
                            errorMes("激活请求失败");
                            // successMes("修改成功");
                       }else if(result=="success"){
                            successMes("账户正在准备跳转到支付宝登陆页面。。。");
                            setTimeout("location.href='<?php echo site_url('core/wxc_user_account/redirect_to_alipay'); ?>'",2000)
                       }else if(result=="success"){
                            errorMes("您的账户处于提现期间，暂时不能更改账户");
                       }
                    },
                     error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.readyState);
                        alert(textStatus);
                    }
                });
        }
    }else if(type = 2){
        var url ="<?php echo site_url('core/wxc_user_account/change_ali_account'); ?>";
        var user_account_name=$("#user_account_name").attr("value");
         if(user_account_name==""){
            errorMes("账户不能为空");
        }else if(!com_check_eamil(user_account_name)){
            errorMes("账户不合法");
        }else{
            $.ajax({
                type:"post",
                data:({'account_email': user_account_name}),
                url:url,
                success: function(result)
                    {
                       if(result=="no-actived"){
                            errorMes("还未激活过账号");
                       }else if(result=="failed"){
                            errorMes("激活请求失败");
                            // successMes("修改成功");
                       }else if(result=="success"){
                            successMes("账户正在准备跳转到支付宝登陆页面。。。");
                            setTimeout("location.href='<?php echo site_url('core/wxc_user_account/redirect_to_alipay'); ?>'",2000)
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


}

//=========================================================更新个人账户=========================================//
//局部更新页面，给出绑定
function social_count_bind(){
    var url="<?php echo site_url('core/wxc_user_manager/show_third_party_account'); ?>";
    $.ajax({
        type:"post",
        data:({}),
        url:url,
        dataType:"json",
        success: function(result)
            {
                if(result!=null){
                    var i;
                    var str="";
                    str+="<div  id='thisform' ><fieldset><le id='le_title' style='margin-top: -52px;'>账户设置</le><p style='border-bottom: 1px solid #85b1de;font-size:16px;'><label class='fl' style='text-align: center;width: 240px;height: 26px;padding-top: 0px;'>社交账号</label><label class='fl' style='text-align: center;width: 240px;height: 26px;padding-top: 0px;'>账户名称</label><label class='fl' style='text-align: center;width: 240px;height: 26px;padding-top: 0px;'>操作</label><br />";
                    for(i in result){
                        str+="<p style='height: 15px;' id='"+result[i]["type"]+"'>"
                        str+="<span class='fl _third_acount' >"+result[i]["show_name"]+"</span><span class='fl _third_acount' >"+result[i]["nice_name"];
                        str+="</span><span class='fl _third_acount' ><input class='button_c' onclick='del_count(this)' name='"+result[i]["type"]+"' style='width:90px;' type='button' value='解除绑定'></span>";
                        str+="</p>";
                    }
                    str+="</fieldset></div>";
                    $("#change_form").html(str);
                    $("a").removeClass("navhover");
                    $("#nav_bind").addClass("navhover");
                    // $("._grgh").html("绑定账号");
                }
            },
             error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            }
        });
}

function del_count(count_type){
    var url="<?php echo site_url('core/wxc_user_manager/del_third_party'); ?>";
    $.ajax({
        type:"post",
        data:({'third_party_type':count_type.name}),
        url:url,
        success: function(result)
            {
                if(result == "success"){
                    $("#"+count_type.name).css("display","none");
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
    <!-- end #header -->
<div class="backcolor_body" >
    <div class="body _body" style="min-height: 640px;">

		<div id="_content" class="_content" >
            <div class="_post" id="post" style="padding-top:0;">
            <div class="_data_title" style="height: 56px;">
                <div class="_grgh"></div>

            </div>
			<div style="margin-top: 0px; width: 780px;padding-left:0;border-top: 5px solid rgb(125 ,142 ,167);background-color: rgb(225, 228, 230);" id="change_form">
				<div id="thisform">
					<fieldset >
                        <le id='le_title' style='margin-top: -52px;'>账户设置</le>
						<p>
							<label>昵称:</label> <input type="text" id="user_name"
								name="user_name" class="updata_name" value="<?php echo $base_info['user_name'];?>"
								onblur="check_name(this.value)">
						</p>
						<p>
							<label>偏好:</label> <input type="text" id="user_hobby"
								name="user_hobby" value="<?php echo $base_info['user_hobby'];?>">
						</p>
						<p>
							<label>邮箱:</label><input type="text" id="user_email"
								name="user_email" value="<?php echo $base_info['user_email'];?>"
								disabled>
						</p>
						<p>
							<label>电话号:</label> <input type="text" id="user_phone"
								name="user_phone" value="<?php echo $base_info['user_phone'];?>"
								onblur="check_phone(this.value)">
						</p>

						<p>
							<label>学校:</label>
                            <input type="hidden" name="hidden_school" id="hidden_school"value="<?php echo $base_info['user_school_id'];?>">
                            <input type="text" name="school_name" id="school_name" value="<?php echo $base_info['user_school'];?>" onclick="pop()">
                        </p>

						<div id="choose-box-wrapper" style="z-index: 1000">
							<div id="choose-box">
								<div id="choose-box-title">
									<span>选择学校</span>
								</div>
								<div id="choose-a-province"></div>
								<div id="choose-a-school"></div>
								<div id="choose-box-bottom">
									<input type="button" onclick="hide()" value="关闭" />
								</div>
							</div>
						</div style="border: 0;">

						<p>
							<label>专业:</label> <select id='partment' name='partment'
								style='width: 381px;'>
								<option value="<?php echo $base_info['user_major_id'];?>"><?php echo $base_info['user_major'];?></option>
							</select>
						</p>
						<p>
							<label>入学年份:</label>
                            <!-- <input type="text"
								name="" > -->
                            <select id="user_period" style='width: 381px;'>
                                <option value="<?php echo $base_info['user_period'];?>"><?php echo $base_info['user_period'];?></option>
                                <option value="2013">2013</option>
                                <option value="2012">2012</option>
                                <option value="2011">2011</option>
                                <option value="2010">2010</option>
                                <option value="2009">2009</option>
                                <option value="2008">2008</option>
                            </select>
						</p>

						<p style="padding-left:180px;">
							<input type="button" name="" id="update_user" class="button_c" style="width:90px;" value="提交信息" >
						</p>
					</fieldset>
				</div>
			</div>
        </div>
		</div>
		<!-- end #content -->
        <div id="_sidebar" class="_sidebar">
            <h2 style="padding-left: 10px;padding-bottom:10px;"></h2>
            <div >
                <div class="navbox">
                    <ul class="nav">
                        <li><a id="nav_baseinfo" href="<?php echo site_url('primary/wxc_personal/update_userinfo_page'); ?>">基本资料</a></li>
                        <li><a id="nav_pwd" href="javascript:void(0)" onclick="update_password_page()">密码修改</a></li>
                        <li><a id="nav_bind" href="javascript:void(0)" onclick="social_count_bind()">绑定账号</a></li>
                        <li><a id="nav_account" href="javascript:void(0)" onclick="update_account_page()">个人账户</a></li>
                    </ul>
                </div>
            </div>
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
