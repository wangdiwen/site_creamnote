<link rel="stylesheet" href="/application/frontend/views/resources/css/wx_common.css" />
<script type='text/javascript' src='/application/frontend/views/resources/js/common.js'></script>
<script type='text/javascript' src='/application/frontend/views/resources/js/jquery.poshytip.js'></script>
<link href="/application/frontend/views/resources/css/showLoading.css" rel="stylesheet"/>
<link rel="stylesheet" href="/application/frontend/views/resources/css/easydialog.css" />
<script src="/application/frontend/views/resources/js/easydialog.js" type="text/javascript"></script>
<script type="text/javascript" src="/application/frontend/views/resources/js/jquery.blockUI.js"></script>
<style type='text/css'>
._head {
    background: #3C424C url(/application/frontend/views/resources/images/new_version/dy_bg.png) repeat left top !important;
}
</style>
<input type="hidden" value="<?php if (isset($_SESSION["wx_user_name"]) && $_SESSION["wx_user_name"] != "") echo $_SESSION["wx_user_name"]; else echo ""; ?>" id="g_login_name">
<script type="text/javascript" src="/application/frontend/views/resources/js/jquery.showLoading.js"></script>
<div class="_head">
    <div class="_head_center">
        <a href="<?php echo site_url('home/index'); ?>">
            <div class="_brand transition">
            </div>
        </a>
        <form id="search_form_head" method="post" action="<?php echo site_url('primary/wxc_search/public_search'); ?>" onsubmit="return search()">
          <div class="_head_search fl">
            <input type="text" name="search" onclick="hiddenTip()" id="" size="15" maxlength="20">
            <span class="search-submit-head"></span>
          </div>
        </form>
        <div id="check_login" style="display:none;">
            <a href="<?php echo site_url('home/register_page'); ?>">
                <div class="fr register transition">
                </div>
            </a>
            <div class="fr login transition" id="creamnote_login">
            </div>
        </div>

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
</div>
<input type="hidden" id="baseUrl" value="<?php echo base_url();?>">
<script type="text/javascript">
<!-- Javascript functions -->
//=========================================================临时=========================================//
// if(QC.Login.check()){
//         //用JS SDK调用OpenAPI
//         QC.api("get_user_info", {})
//         //指定接口访问成功的接收函数，s为成功返回Response对象
//         .success(function(s){
//             //成功回调，通过s.data获取OpenAPI的返回数据
//             qq_name = s.data.nickname;
//             var str ="<div class='fr logout transition' style='cursor:pointer' onclick='qqLogout()'><div class='logout_icon'>注销</div></div>";
//             str +="<a href='#'><div class='fr login_user transition'><div class='temp_login_user_icon'>"+qq_name+"</div></div></a>";

//             $("#check_login").html(str);

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
  function qqLogout(){
    QC.Login.signOut();
    location.href='<?php echo site_url('home/index'); ?>';
  }
//=========================================================登录登出=========================================//
var loginname = '<?php if (isset($_SESSION["wx_user_name"]) && $_SESSION["wx_user_name"] != "") echo $_SESSION["wx_user_name"]; else echo ""; ?>';
    $(function(){

      // if(loginname == ""){
      //   var timer = setInterval( 'myrefresh()', 4000);
      // }
        $("#creamnote_login").click(function(){
            var display_var = $("#login_win").css("display");
            if(display_var == "none"){
                $("#login_win").css("display","block");
            }else{
                $("#login_win").css("display","none");
            }
        });

        if(loginname!=""){
           $("#check_login").html("");
            //var str ="<div class='fr logout transition' style='cursor:pointer' id='logout'><div class='logout_icon'>注销</div></div>";
            //str +="<a href='<?php echo site_url('home/personal'); ?>'><div class='fr login_user transition'><div class='login_user_icon'>"+loginname+"</div></div></a>";
            var str = "<div class='fr _show_count'><span class='fl' style='padding-left:10px;'>"+loginname+"</span><span class='fr _user_name'></span>";
            str += "<div class='_al_login'>";
            str += "<a id='_al_user' href='javascript:void(0)'><div class='_al_user fl'>个人中心</div></a>";
            str += "<a id='_al_count' href='javascript:void(0)'><div class='_al_count fl'>账户设置</div></a>";
            str += "<a id='_al_logout' href='javascript:void(0)'><div class='_al_logout fl'>退出</div></a>";
            str += "</div>";
            str +="</div>";
            str +="<span class='fr'>欢迎 ,</span>";
            $("#check_login").html(str);

            $("#_al_user").attr("href","<?php echo site_url('home/personal'); ?>");
            $("#_al_count").attr("href","<?php echo site_url('primary/wxc_personal/update_userinfo_page'); ?>");

        }else{
          $("._head_search").css("margin-left","534px");
        }
        $("#check_login").css("display","block");

        $("._al_logout").click(function(){
          var url ='<?php echo site_url('home/logout'); ?>';
          $.ajax({
          type:"post",
          url:url,
          success: function(result)
            {
              if(result=='success'){
                location.reload();
                //qq退出
                // if(QC.Login.check()){
                //   qqLogout();
                // }

                }

            },
           error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
              }
            });
        });
//=========================================================hover姓名=========================================//
  $("._show_count").mouseover(function() {
      $("._user_name").addClass('_user_name_hover');
      $("._al_login").css("display","block");
      $("._show_count").css("background-color","rgb(43, 64, 87)");
  });
  $("._show_count").mouseout(function() {
      $('._user_name').removeClass('_user_name_hover');
      $("._al_login").css("display","none");
      $("._show_count").removeAttr("style");
  });

//=========================================================登录=========================================//
    document.onkeydown = function(e){
      var wx_email=$("#username").attr("value");
      var wx_password=$("#password").attr("value");
      if(loginname==""&&wx_email!=""&&wx_password!=""){
        var ev = document.all ? window.event : e;
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
                    location.href='<?php echo site_url('home/personal'); ?>';
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
        $.ajax({
        type:"post",
        data:({'wx_email': wx_email, 'wx_password': wx_password ,'if_auto_login':if_auto_login}),
        url:url,
        success: function(result)
            {
                if(result=='success'){
                    location.href='<?php echo site_url('home/personal'); ?>';
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

});
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
//=========================================================QQ=========================================//
// QC.Login({
//   btnId:"qqLoginBtn"    //插入按钮的节点id
// });
// if(QC.Login.check()){//如果已登录
//   QC.Login.getMe(function(openId, accessToken){
//     //$("#search-text").attr("value",openId+"accessToken为："+accessToken);
//   });
//   //这里可以调用自己的保存接口
//   //...
// }
function showPopup(){
  QC.Login.showPopup({
    appId:"100486041",
    redirectURI:"http://www.creamnote.com/core/wxc_user_manager/qq_back_func"
  })
  //关闭当前页
  if (navigator.userAgent.indexOf("Firefox") > 0) {
      //window.location.href = 'about:blank ';
      window.open('','_parent','');
      window.close();
    }else{
      self.close();
    }

}



//=========================================================未登录时定时检测是否登录=========================================//
function myrefresh(){
   var url ='<?php echo site_url('home/get_login_name'); ?>';
        $.ajax({
        type:"get",
        data:({}),
        url:url,
        success: function(result)
            {
              //alert(result)
                if(result!=""){
                  window.location.reload();
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
