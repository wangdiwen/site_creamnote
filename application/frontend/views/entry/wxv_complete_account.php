<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Creamnote注册</title>
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

$(function(){
    $("#register").click(function(){
      if(check_reg()){
        var school_id = $('#hiddenschool').attr("value");
        var wx_name=$("#name").attr("value");
        var wx_carea=$("#partment").attr("value");
        var url ="<?php echo site_url('primary/wxc_personal/complete_register_other'); ?>";
        $.ajax({
        type:"post",
        data:({'nice_name':wx_name,'major_id':wx_carea,'school_id':school_id}),
        url:url,
        success: function(result)
            {
              if(result = "success"){
                    location.href ="<?php echo site_url('home/index'); ?>"
                }else{
                    errorMes("修改失败");
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
                    str +="<option value="+result[i]['carea_id']+">"+result[i]['carea_name']+"</option>"  ;
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


//=========================================================check是否可以注册=========================================//
function check_reg(){
  checkName($("#name").val());
  checkSchool($("#school-name").val());

  if(
  checkName($("#name").val())&&
  checkSchool($("#school-name").val())
  ){
    return true;
  }else{
    return false;
  }
}

</script>

</head>


<body>
  <div id="msgTxt"></div>
  <a href="http://www.baidu.com" id="skip_page" target="_blank" style="display:none;">百度</a>
    <?php include  'application/frontend/views/share/header_home.php';?>
    <div class="body article_body" style="min-height: 635px;border-top: 8px solid #839acd;padding: 50px 0;">
      <div class="reg_frame" id="reg_frame">

        <h2>完善个人信息</h2>



        <div class="reg_put">
          <span>昵称</span><br/>
          <input type="text" name="name" id="name" onblur="checkName(this.value)" placeholder="一个个性的昵称">
          <input type="hidden" id="name_c">
          <div class="reg_error" id="error_name" style="display:none;"></div>
        </div>

        <div class="reg_put">
          <span>学校</span><br/>
          <input type="text" name="school" id="school-name" onblur="checkSchool(this.value)" onclick="pop()" onfocus="pop()" data-id="" value="" placeholder="你就读的学校">
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
        </div>

        <div class="reg_put">
          <span>院系</span><br/>
          <select id='partment' name='partment' style='width: 422px;' placeholder="你所在的专业">
          </select>
          <div class="reg_error" id="error_partment" style="display:none;"></div>
        </div>


        <div class="reg_put" style="text-align: center;">
          <input type="button" class="button_c" name="" id="register" value="完成" style="width: 150px;height:33px;">
        </div>

      </div>

      <div></div>
      <div class="clear"></div>
</div>
<?php include  'application/frontend/views/share/footer.php';?>
</body>

</html>
