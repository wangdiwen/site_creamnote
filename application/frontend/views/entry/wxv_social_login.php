<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Creamnote登录</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/reset.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/text.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/960.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_home.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/style.css" />
    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="/application/frontend/views/resources/js/school.js"></script>
    <script type='text/javascript' src='/application/frontend/views/resources/js/common.js'></script>
<script type="text/javascript">
<!-- Javascript functions -->
var gol_password = "";
$(function(){
    $("#register").click(function(){
      if(check_reg()){
        // var school_id = $('#hiddenschool').attr("value");
        var wx_email=$("#email").attr("value");
        var wx_password=gol_password;
        // var wx_name=$("#name").attr("value");
        // var wx_carea=$("#partment").attr("value");
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
                      window.open(url,"_blank");
                    }else{
                      //
                    }
                    var str="<h2>验证邮件已经发送您邮箱，请到您注册的邮箱进行验证！</h2>";
                    str +="<p style='margin-top:50px;'></p>";
                    str +="<h2>没有收到邮件？</h2>";
                    str +="<p>1、到垃圾箱里看看有没有</p>";
                    str +="<p>2、如果邮箱填写错了，那就<a href='<?php echo site_url('home/register_page'); ?>' style='color: black;TEXT-DECORATION:underline'>重新注册</a>吧</p>";
                    str +="<p>3、稍等一会，如果还是没有收到验证邮件，点击<a href='javascript:void(0)' onclick='re_send_email()' style='color: black;TEXT-DECORATION:underline'>重新发送</p></a>";
                    $("#reg_frame").html(str);
                    $("#reg_frame").css("width","600px");
                    $("#reg_frame").css("text-align","left");
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

  $("#bind").click(function(){
      var wx_email=$("#hasreg_email").attr("value");
      var wx_password=$("#hasreg_pwd").attr("value");
      var url ="<?php echo site_url('primary/wxc_home/quick_login'); ?>";
      $.ajax({
      type:"post",
      data:({'user_email': wx_email, 'user_passwd': wx_password}),
      url:url,
      success: function(result)
          {
              if(result=='success'){
                    location.href='<?php echo site_url('home/personal'); ?>';
                 }else if(result=='no-user'){
                    $("#hasreg_error_name").html("没有该用户");
                    $("#hasreg_error_name").css("display","block");
                 }else if (result=='passwd-wrong'){
                    $("#hasreg_error_name").html("密码错误");
                    $("#hasreg_error_name").css("display","block");
                 }
                 else if (result=='database-wrong'){
                    $("#hasreg_error_name").html("连接失败");
                    $("#hasreg_error_name").css("display","block");
                 }

          },
           error: function(XMLHttpRequest, textStatus, errorThrown) {
              alert(XMLHttpRequest.status);
              alert(XMLHttpRequest.readyState);
              alert(textStatus);
          }
      });

    });
//window.location.href="<?php echo site_url().'home/skip_page?email='; ?>"+"1010658096@qq.com";
// var param_mail = $("#email").attr("value");
// var url="<?php echo site_url().'home/skip_page?email='; ?>"+"1010658096@qq.com";
// window.open(url);
//$("#skip_page").target = "_blank";
//location.href = $("#skip_page").attr("href");
//$("#reg_frame").html("<h2>请到您注册的邮箱进行验证！</h2>")
//=========================================================填充昵称=========================================//
// if(QC.Login.check()){

//         //用JS SDK调用OpenAPI
//         QC.api("get_user_info", {})
//         //指定接口访问成功的接收函数，s为成功返回Response对象
//         .success(function(s){
//             //成功回调，通过s.data获取OpenAPI的返回数据
//             $("#nickname").html(s.data.nickname);
//         })
//         //指定接口访问失败的接收函数，f为失败返回Response对象
//         .error(function(f){
//             //失败回调
//             alert("获取用户信息失败！");
//         })
//         //指定接口完成请求后的接收函数，c为完成请求返回Response对象
//         .complete(function(c){
//             //完成请求回调
//             //alert("获取用户信息完成！");
//         });

//     }
 });
//=========================================================check email=========================================//
function isEmail(strEmail) {
    if(strEmail==""){
        $("#error_mail").html("邮箱不能为空");
    $("#error_mail").css("display","block");
    return false;
    }else{
        if (strEmail.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1){
            return check_email(strEmail);
        } else{
            $("#error_mail").html("邮箱不正确");
      $("#error_mail").css("display","block");
      return false;
        }
    }
}
//=========================================================学校插件=========================================//
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
  if($("#school-name").val()==""){
    $("#error_school").html("学校不能为空");
    $("#error_school").css("display","block");
  }else{
    $("#error_school").css("display","none");
  }
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
            $('#school-name').val(item.text());
            $('#hiddenschool').val(item.text());

            var wx_school=$("#hiddenschool").attr("value");
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
                            str +="<option value="+result[i]['carea_id']+">"+result[i]['carea_name']+"</option>"    ;
                            };
                        $("#partment").html(str);
                    },
                     error: function(XMLHttpRequest, textStatus, errorThrown) {
                                alert(XMLHttpRequest.status);
                                alert(XMLHttpRequest.readyState);
                                alert(textStatus);
                            }
                });
            get_school_id(wx_school);
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
        success: function(result)
            {
                $("#hiddenschool").attr("value",result);
            },
             error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.readyState);
                        alert(textStatus);
                    }
        });
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
                         $("#error_mail").html("该邮箱已经注册");
               $("#error_mail").css("display","block");
                         $("#email").attr("value","");
               $("#email_c").attr("value","false");
              }else{
                $("#error_mail").css("display","none");
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
//=========================================================check昵称=========================================//
function checkName(name){
  if(name==""){
    $("#error_name").html("昵称不能为空");
    $("#error_name").css("display","block");
    $("#name_c").attr("value","false");
  }else{
    var url ="<?php echo site_url('core/wxc_user_manager/check_nice_name'); ?>";
   $.ajax({
          type:"post",
          data:({'user_nice_name':name}),
          url:url,
          success: function(result)
              {
                 if(result=="true"){
                  $("#error_name").html("昵称已经被使用");
                  $("#error_name").css("display","block");
                  $("#name_c").attr("value","false");
                 }else{
                    $("#error_name").css("display","none");
                    $("#name_c").attr("value","true");
                 }

              },
               error: function(XMLHttpRequest, textStatus, errorThrown) {
                  alert(XMLHttpRequest.status);
                  alert(XMLHttpRequest.readyState);
                  alert(textStatus);
              }
          });
  }
  return $("#name_c").attr("value");
}
//=========================================================check学校=========================================//
function checkSchool(school_name){
  if(school_name==""){
    $("#error_school").html("学校不能为空");
    $("#error_school").css("display","block");
    return false;
  }else{
    $("#error_school").css("display","none");
    return true;
  }
}
//=========================================================check密码=========================================//
function checkPwd(pwd){
  gol_password = pwd;
  if(pwd==""){
    $("#error_pwd").html("密码不能为空");
    $("#error_pwd").css("display","block");
    return false;
  }else if(pwd.length<6){
    $("#error_pwd").html("密码过于简单");
    $("#error_pwd").css("display","block");
    return false;
  }else{
    $("#error_pwd").css("display","none");
    return true;
  }
}
//=========================================================check验证码=========================================//
function checkCode(auth_code){
  var url ="<?php echo site_url('core/wxc_util/check_auth_code'); ?>";
  $.ajax({
        type:"post",
        data:({'wx_auth_code': auth_code}),
        url:url,
        success: function(result)
            {
              if(result=="auth-code-error"){
               $("#auth_code_su").html("'");
               $("#auth_code_su").css("display","inline-block");
               $("#auth_code_su").css("color","red");
               $("#auth_code").attr("value","");
               $("#auth_code_c").attr("value","false");
              }else{
                $("#auth_code_su").css("display","inline-block");
                $("#auth_code_su").html(".");
                $("#auth_code_su").css("color","#3EB917");
                $("#auth_code_c").attr("value","true");
              }
            },
             error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            }
        });
  return $("#auth_code_c").attr("value");
}
//=========================================================check是否同意协议=========================================//
function checkTerms(){
  if($("#check_te").attr("checked")!="checked"){
    $("#error_terms").html("需要同意本站的服务条款");
    $("#error_terms").css("display","block");
    return false;
  }else{
    $("#error_terms").css("display","none");
    return true;
  }
}
//=========================================================check是否可以注册=========================================//
function check_reg(){
  isEmail($("#email").val());
  // checkName($("#name").val());
  // checkSchool($("#school-name").val());
  checkPwd(gol_password);
  checkTerms();
  // alert(isEmail($("#email").val()))
  // alert(checkName($("#name").val()))
  // alert(checkSchool($("#school-name").val()))
  // alert(checkPwd(gol_password))
  // alert()
  // alert()

  if(isEmail($("#email").val())&&
  // checkName($("#name").val())&&
  // checkSchool($("#school-name").val())&&
  checkPwd(gol_password)&&
  checkTerms()){
    return true;
  }else{
    return false;
  }
}
//=========================================================获得新验证码=========================================//
function get_new_code(){
   var url ="<?php echo site_url('core/wxc_util/get_new_auth_code'); ?>";
  $.ajax({
        type:"post",
        url:url,
        success: function(result)
            {
              if(result!=""){
                $("#auth_code_display").html(result+"=?");
                $("#auth_code_su").css("display","none");
              }
            },
             error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            }
        });

}
//=========================================================表单切换=========================================//
function checkReg_yes(){
  $("#reg_no").css("display","none");
  $("#reg_yes").css("display","block");
}
function checkReg_no(){
   $("#reg_no").css("display","block");
  $("#reg_yes").css("display","none");
}
</script>

</head>

<body>
    <?php include  'application/frontend/views/share/header_home.php';?>
    <div class="body" style="min-height: 635px;border-top: 8px solid #839acd;padding: 50px 0;">
      <div class="reg_frame" id="reg_frame">

        <h2>HI,<span id="nickname" style="color:rgb(45, 93, 197);"><?php echo $user_nice_name; ?></span>,欢迎来Creamnote</h2>

        <div class="reg_put" style="padding-left: 170px;">
          <input type="radio" name="checkReg" id="checkReg_yes" onclick="checkReg_yes()" value="yes"/>已有账户
          <input type="radio" name="checkReg" id="checkReg_no" onclick="checkReg_no()" value="no" checked/>快速注册
        </div>

        <div id="reg_no">
          <div class="reg_put">
            <span>邮箱</span><br/>
            <input type="text" name="email" id="email" onblur="isEmail(this.value)" placeholder="你常用的邮箱">
            <input type="hidden" id="email_c">
            <div class="reg_error" id="error_mail" style="display:none;"></div>
          </div>

         <!--  <div class="reg_put">
            <span>昵称</span><br/>
            <input type="text" name="name" id="name" onblur="checkName(this.value)" placeholder="一个个性的昵称">
            <input type="hidden" id="name_c">
            <div class="reg_error" id="error_name" style="display:none;"></div>
          </div>

          <div class="reg_put">
            <span>学校</span><br/>
            <input type="text" name="school" id="school-name" onblur="checkSchool(this.value)" onclick="pop()" onfocus="pop()" data-id="<?php echo $login_school;?>" value="" placeholder="你就读的学校">
            <input type="hidden" id="hiddenschool" name="wx_school">
            <div class="reg_error" id="error_school" style="display:none;"></div>
            <div id="choose-box-wrapper">
              <div id="choose-box">
                <div id="choose-box-title">
                  <span>选择学校</span>
                </div>
                <div id="choose-a-province">
                </div>
                <div id="choose-a-school">
                </div>
                <div id="choose-box-bottom">
                  <input type="button" onclick="hide()" value="关闭" />
                </div>
              </div>
            </div>
          </div> -->

        <!--   <div class="reg_put">
            <span>院系</span><br/>
            <select id='partment' name='partment' style='width: 422px;' placeholder="你所在的专业">
            </select>
            <div class="reg_error" id="error_partment" style="display:none;"></div>
          </div> -->

          <div class="reg_put">
            <span>密码</span><br/>
            <input type="password" name="password" id="password" onblur="checkPwd(this.value)" style="width:400px;" placeholder="不少于六位">
            <div class="reg_error" id="error_pwd" style="display:none;"></div>
          </div>

          <div class="reg_put">
            <input type="checkbox" name="check_te" id="check_te" onclick="checkTerms()">同意<a href="<?php echo site_url('static/wxc_help/termsofservice'); ?>">「使用条款」</a>
            <div class="reg_error" id="error_terms" style="display:none;"></div>
          </div>

          <!-- <div class="reg_put">
            <span>验证</span><br/>
            <span id="auth_code_display"><?php echo $auth_code;?>=?</span>
            <input type="text" name="auth_code" id="auth_code" style="width: 50px;" onblur="checkCode(this.value)">
            <input type="hidden" name="auth_code_c" id="auth_code_c">
            <span id="auth_code_su" class="Webfonts reg_code" style="display:none;">.</span>
            <div class="reg_error" id="auth_code_error" style="display:none;"></div>
            <a href="javascript:void(0)" onclick="get_new_code()" style="text-decoration: underline;"><span>获得新验证码<span></a>
          </div> -->

          <div class="reg_co" style="border-bottom:0;">
            <input type="button" class="button_c" name="" id="register" value="注册" style="width: 150px;height:33px;">
          </div>
        </div>

        <div id="reg_yes" style="display: none;">
          <div class="reg_put">
            <span>邮箱</span><br/>
            <input type="text" name="hasreg_email" id="hasreg_email" onblur="" placeholder="邮箱">
            <input type="hidden" id="email_c">
            <div class="reg_error" id="hasreg_error_mail" style="display:none;"></div>
          </div>

          <div class="reg_put" style="border-bottom:0;">
            <span>密码</span><br/>
            <input type="password" name="hasreg_pwd" id="hasreg_pwd" onblur="" style="width:400px;" placeholder="密码">
            <div class="reg_error" id="hasreg_error_name" style="display:none;"></div>
          </div>

          <div class="reg_co">
            <input type="button" class="button_c" name="" id="bind" value="绑定" style="width: 150px;height:33px;">
          </div>
        </div>
      </div>

      <div></div>
      <div class="clear"></div>
</div>
<?php include  'application/frontend/views/share/footer.php';?>
</body>

</html>
