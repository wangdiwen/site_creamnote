<?php header('Cache-control: private, must-revalidate');  //支持页面回跳?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>醍醐笔记</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_home.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/style.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/advertise.css" />
    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="/application/frontend/views/resources/js/wx_load.js"></script>
    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery.blockUI.js"></script>

    <script type='text/javascript' src='/application/frontend/views/resources/js/jquery.poshytip.js'></script>
    <script type='text/javascript' src='/application/frontend/views/resources/js/common.js'></script>



</head>
<body>


    <div id="cover1">
        <div class="f_top">
            <div class="grid top">
                <a href="<?php echo site_url('home/index'); ?>"><div class="logo fl"></div></a>
                <a href="<?php echo site_url('home/index'); ?>"><div class="fr bu">首页</div></a>
                <div class="fr bu" id="creamnote_login">登录
                    <div class="login_win_base" style="display:none" id="login_win">
                        <div class="arr">
                        </div>
                        <div class="login_win_box">
                            <h1>使用社交账户登录</h1>
                            <div style="position: relative;height: 40px;">
                              <div onclick="qqLogin()" class="qq img_shadow"></div>
                              <div onclick="renrenLogin()" class="renren img_shadow"></div>
                              <div onclick="weiboLogin()" class="weibo img_shadow"></div>
                            </div>
                            <div class="login_win_orWrapper">
                                <div class="bar"></div>
                                <div class="or">或者</div>
                                <div class="bar"></div>
                            </div>
                            <div class="login_win_un">
                                <input type="text" id="username" name="username" class="login_win_un_input" placeholder="邮箱" style="margin:auto;" autocomplete = "off">
                            </div>
                            <div class="login_win_un">
                                <input type="password" id="password" name="password" value="" class="login_win_un_input" placeholder="密码" style="margin:auto;">
                            </div>
                            <div class="login_win_sub">
                                <div class="checkbox"><input type="checkbox" id="remember" name="remember_me" value="1"></div>
                                <div class="rem">下次自动登录</div>
                                <button id="signin_submit" class="button">登录</button>
                            </div>
                            <div class="login_win_forgot">
                                <a style="text-decoration: none;" href="<?php echo site_url('home/find_password_page')?>" id="resend_password_link">
                                    <span style="color: rgb(131,154,205);">忘记密码</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo site_url('home/register_page'); ?>"><div class="fr bu">注册</div></a>
                <!-- <a href="<?php echo site_url('static/wxc_about/about_creamnote'); ?>"><div class="fr bu">关于我们</div></a> -->
            </div>
        </div>

        <div class="grid center">
            <div class="tihu"></div>
            <div class="mynote">
                <span>大学生自己的</span><span>资料社区</span>
            </div>
            <div class="small">
                <span>——</span>
                <span>The community of data</span>
                <span>——</span>
            </div>
        </div>
    </div>
    <div id="cover2" class="fs18">
        <div class="title">这些学校学生正在使用醍醐笔记</div>
        <div class="colleges">
          <img src="http://www.zhengjicn.com/Photo/UploadPhotos/201010/20101020131456831.jpg">
          <img src="http://www.zhengjicn.com/Photo/UploadPhotos/201010/20101020131346194.jpg">
          <img src="http://www.zhengjicn.com/Photo/UploadPhotos/201010/20101020131235792.jpg">
          <img src="http://www.zhengjicn.com/Photo/UploadPhotos/201010/20101020130812816.jpg">
          <img src="http://www.zhengjicn.com/Photo/UploadPhotos/201010/20101020130417630.jpg">
          <img src="http://www.zhengjicn.com/Photo/UploadPhotos/201010/20101020130642197.jpg">
          <img src="http://www.zhengjicn.com/Photo/UploadPhotos/201010/20101020125924841.jpg">
        </div>
    </div>
    <div id="cover3">
        <div class="slogan grid sright">
            <div class="pic pic1 fl"></div>
            <div class="detail">
                <div class="one">资料太多，无所适从？</div>
                <div class="two fs18">在醍醐，你可以精准地找到你想要的学习笔记、期末考试题、考研复习资料...</div>
            </div>

        </div>
        <div class="slogan grid sleft">
            <div class="pic pic2 fr"></div>
            <div class="detail">
                <div class="one">平时偷懒，考前干捉急？</div>
                <div class="two fs18">在醍醐，你可以发现身边的学霸，交个朋友，摆脱“少一分受罪”的尴尬！</div>
            </div>
        </div>
        <div class="slogan grid sright">
            <div class="pic pic3 fl"></div>
            <div class="detail">
                <div class="one">上传资料,学霸变土豪</div>
                <div class="two fs18">在醍醐，我们将定价您的优质笔记，学霸们学习之余还可以获得金钱回报，何乐不为？</div>
            </div>
        </div>
        <div class="slogan grid sleft" style="border-bottom:0;">
            <div class="pic pic4 fr"></div>
            <div class="detail">
                <div class="one">人人共享，创造学习新氛围</div>
                <div class="two fs18">在醍醐，我们服务高校师生、促进知识共享、开创未来中国高等教育的新时代</div>
            </div>
        </div>
    </div>
    <div id="cover4">
      <div class="grid">
        <div class="weixin">
          <img src="/application/frontend/views/resources/images/cover/weixin.png" alt="">
          <p>关注我们的微信</p>
        </div>
        <div class="re">
            <input  name="email" id="email" class="ad" type="text" placeholder="常用邮箱" required="required">
            <input type="hidden" id="email_c">
            <div class="reg_error" id="error_mail" style="display:none;"></div>
            <input  name="repassword" id="repassword" class="ad" type="password" placeholder="密码" required="required">
            <div class="reg_error" id="error_pwd" style="display:none;"></div>
            <button id="register" class="ad">免费注册</button>
            <div  class="fl clause fs18">点击注册表明你同意我们的<span><a target="_blank" href="<?php echo site_url('static/wxc_help/termsofservice'); ?>">[使用条款]</a></span></div>
        </div>
      </div>


    </div>


    <!-- end #body -->
    <?php include  'application/frontend/views/share/footer.php';?>
    <!-- end #footer -->
</div>

<script type="text/javascript">
//=========================================================点击非登陆按钮=========================================//
    $(document).click(function(event){
        var login = event.target.id;
        var target = event.target;
        var i = 0 ;
        while (target.className != "login_win_box") {
            if(target.parentNode!=null){
                target = target.parentNode;
            }else{
                break;
            }
        }

        if(login != "creamnote_login"
          &&target.className != "login_win_box"
          &&login != "foot_content_heart"
          &&target.className != "foot_content_heart"
          &&login != "common_show_login_win"
          &&target.className != "common_show_login_win"){
            $("#login_win").css("display","none");
        }
        target = event.target;
        if(target.className.indexOf("common_show_login_win")>=0&&loginname==""){
          $("#login_win").css("display","block");
        }
    });
$(function(){
    $("#creamnote_login").click(function(){
        var display_var = $("#login_win").css("display");
        // var login = event.target.id;
        // var target = event.target;
        // var i = 0 ;
        // while (target.className != "login_win_box") {
        //     if(target.parentNode!=null){
        //         target = target.parentNode;
        //     }else{
        //         break;
        //     }
        // }
        if(display_var == "none"||target.className == "login_win_box"){
            $("#login_win").css("display","block");
        }else{
            $("#login_win").css("display","none");
        }
    });


$(window).scroll(scroll);
function scroll() {
    var scrollTop = $(window).scrollTop();//滚动高度
    if(scrollTop>0){
        $(".f_top").addClass("scrolledshadow");
    }else{
        $(".f_top").removeClass("scrolledshadow");
    }
}
//=========================================================登录=========================================//
var loginname = '<?php if (isset($_SESSION["wx_user_name"]) && $_SESSION["wx_user_name"] != "") echo $_SESSION["wx_user_name"]; else echo ""; ?>';
    document.onkeydown = function(e){
      var wx_email=$("#username").attr("value");
      var wx_password=$("#password").attr("value");
      var ev = document.all ? window.event : e;
      var login_block = $(".login_win_base").css("display");
      if(login_block == "none"){
        return;
      }
      if(wx_email == "" && ev.keyCode ==13){
        warnMes("邮箱号不能为空");
        return;
      }
      if(wx_password == "" && ev.keyCode ==13){
        warnMes("密码不能为空");
        return;
      }
      if(loginname==""&&wx_email!=""&&wx_password!=""){

        if(ev.keyCode==13) {
        var if_auto_login = "false";
        if ($("#remember").attr("checked")) {
          if_auto_login = "true";
        }else{
          if_auto_login = "false";
        }

         var url ='<?php echo site_url('home/login'); ?>';
         $.ajax({
         type:"post",
         data:({'wx_email': wx_email, 'wx_password': wx_password,'if_auto_login':if_auto_login} ),
         url:url,
         success: function(result)
             {
                if(result=='success'){
                    location.href='<?php echo site_url('home/index'); ?>';
                    // location.reload() ;
                 }else if(result=='no-user'){
                     errorMes("没有该用户");
                 }else if (result=='passwd-wrong'){
                     errorMes("密码错误");
                 }else if (result=='database-wrong'){
                    errorMes("数据库连接失败");
                 }else if (result=='user-close'){
                    errorMes("您的账号已经被封号，如有疑义请联系我们！");
                 }else if(result=='not-complete'){
                    location.href='<?php echo site_url('home/complete_register_other_page'); ?>';
                 }

             },

            error: function(XMLHttpRequest, textStatus, errorThrown) {
                         // alert(XMLHttpRequest.status);
                         // alert(XMLHttpRequest.readyState);
                         // alert(textStatus);
                     }
         });

         }
      }

    };

    $("#signin_submit").click(function(){
        var if_auto_login = "false";
            if ($("#remember").attr("checked")) {
              if_auto_login = "true";
            }else{
              if_auto_login = "false";
            }
        var wx_email=$("#username").attr("value");
        var wx_password=$("#password").attr("value");
        var url ='<?php echo site_url('home/login'); ?>';
        if(wx_email == "" ){
          warnMes("邮箱号不能为空");
          return;
        }
        if(wx_password == "" ){
          warnMes("密码不能为空");
          return;
        }
        $.ajax({
        type:"post",
        data:({'wx_email': wx_email, 'wx_password': wx_password ,'if_auto_login':if_auto_login}),
        url:url,
        success: function(result)
            {
                if(result=='success'){
                    location.href='<?php echo site_url('home/index'); ?>';
                    // location.reload() ;
                 }else if(result=='no-user'){
                     errorMes("没有该用户");
                 }else if (result=='passwd-wrong'){
                     errorMes("密码错误");
                 }else if (result=='database-wrong'){
                    errorMes("数据库连接失败");
                 }else if (result=='user-close'){
                    errorMes("您的账号已经被封号，如有疑义请联系我们！");
                 }else if(result=='not-complete'){
                    location.href='<?php echo site_url('home/complete_register_other_page'); ?>';
                 }

            },

           error: function(XMLHttpRequest, textStatus, errorThrown) {
                        // alert(XMLHttpRequest.status);
                        // alert(XMLHttpRequest.readyState);
                        // alert(textStatus);
                    }
        });
    });
  var gol_password = "";
  $("#register").click(function(){
      if(check_reg()){
        var wx_email=$("#email").attr("value");
        var wx_password=$("#repassword").val();
        var url ="<?php echo site_url('home/register'); ?>";
        $.ajax({
        type:"post",
        data:({'wx_email': wx_email, 'wx_password': wx_password}),
        url:url,
        success: function(result)
            {
                if(result=="exist"){
                    $("#error_mail").html("该邮箱已经注册")
                }
                else{
                    var param_mail = $("#email").attr("value");
                    if(email_hash[param_mail.split('@')[1]]!=undefined){
                      var url="<?php echo site_url().'home/skip_page?email='; ?>"+param_mail;

                      // document.getElementById("msgTxt").innerHTML="<form id='hiddenlink' action='"+url+"' target='_blank'><input type='email' name='"+email+"' value=''></form>";
                      // var s=document.getElementById("hiddenlink");
                      // s.submit();
                      window.open(url,"_blank");
                    }else{
                      //
                    }
                    // var str="<h2>验证邮件已经发送您邮箱，请到您注册的邮箱进行验证！</h2>";
                    // str +="<p style='margin-top:50px;'></p>";
                    // str +="<h2>没有收到邮件？</h2>";
                    // str +="<p>1、到垃圾箱里看看有没有</p>";
                    // str +="<p>2、如果邮箱填写错了，那就<a href='<?php echo site_url('home/register_page'); ?>' style='color: black;TEXT-DECORATION:underline'>重新注册</a>吧</p>";
                    // str +="<p>3、稍等一会，如果还是没有收到验证邮件，点击<a href='javascript:void(0)' onclick='re_send_email()' style='color: black;TEXT-DECORATION:underline'>重新发送</p></a>";
                    // $("#reg_frame").html(str);
                    // $("#reg_frame").css("width","600px");
                    // $("#reg_frame").css("text-align","left");
                    location.href='<?php echo site_url(''); ?>';
                }

            },
             error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            }
        });
      }else{
        //
      }
    });

});
//=========================================================check email=========================================//
function isEmail(strEmail) {
  if(strEmail==""){
    errorMes("邮箱不能为空");
    // $("#error_mail").css("display","block");
    return false;
  }else{
    if (strEmail.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1){
      return check_email(strEmail);
    } else{
      errorMes("邮箱不正确");
      // $("#error_mail").css("display","block");
      return false;
    }
  }
}
//=========================================================check邮箱=========================================//
function check_email(user_email){
  var url ="<?php echo site_url('home/check_email'); ?>";
  var ret = false;
  $.ajax({
        type:"post",
        data:({'wx_email': user_email}),
        url:url,
        success: function(result)
            {
              if(result=="failed"){
               errorMes("该邮箱已经注册");
               // $("#error_mail").css("display","block");
               $("#email").attr("value","");
               $("#email_c").attr("value","false");
              }else{
                // $("#error_mail").css("display","none");
                $("#email_c").attr("value","true");
              }
            },
             error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            }
        });
  return $("#email_c").attr("value");
}
//=========================================================check密码=========================================//
function checkPwd(pwd){
  gol_password = pwd;
  if(pwd==""){
    errorMes("密码不能为空");
    // $("#error_pwd").css("display","block");
    return false;
  }else if(pwd.length<6){
    errorMes("密码过于简单");
    // $("#error_pwd").css("display","block");
    return false;
  }else{
    // $("#error_pwd").css("display","none");
    return true;
  }
}
//=========================================================check是否可以注册=========================================//
function check_reg(){
  isEmail($("#email").val());
  // checkName($("#name").val());
  // checkSchool($("#school-name").val());
  checkPwd($("#repassword").val());

  if(isEmail($("#email").val())&&
  // checkName($("#name").val())&&
  // checkSchool($("#school-name").val())&&
  checkPwd($("#repassword").val())
  ){
    return true;
  }else{
    return false;
  }
}
</script>
</body>

</html>
