<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Creamnote用户投诉举报</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/reset.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_footlist.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_home.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/style.css" />
    <link rel="stylesheet" href="/application/backend/views/css/default.css" type="text/css" media="screen" />
    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery-1.8.3.js"></script>
    <script src="/application/frontend/views/resources/js/jquery.tipsy.js" type="text/javascript"></script>

<script type="text/javascript">

</script>

</head>
<body>
  <?php include  'application/frontend/views/share/header_home.php';?>
  <div class="body article_body" style="border-top: 8px solid #839acd;padding:50px 0;">
    <div class="reg_frame" id="reg_frame">
        <h2>投诉举报</h2>
        <?php if($if_login == "false"){?>
            <div class="reg_put">
              <span style="color:red">提交投诉前需要登录，谢谢您的配合</span><a href="javascript:void(0)" onclick="show_login_win()"><span style="color:#337fe5;cursor:pointer" class="common_show_login_win">【登录】</span></a><br/>
            </div>
          <?php }?>

        <div class="reg_put">
          <span>标题</span><br/>
          <input type="text" name="com_title" id="com_title" onblur="check_com_title(this.value)" value="<?=$com_title?>" placeholder="投诉标题" size="15">
          <input type="hidden" id="com_title_c">
          <div class="reg_error" id="error_com_title" style="display:none;"></div>
        </div>

        <div class="reg_put">
          <span>链接</span><br/>
          <input type="text" name="com_link" id="com_link" onblur="check_com_link(this.value)" value="<?=$com_link?>" placeholder="被投诉资料的链接">
          <input type="hidden" id="com_link_c">
          <div class="reg_error" id="error_com_link" style="display:none;"></div>
        </div>

        <div class="reg_put">
          <span>资料名称</span><br/>
          <input type="text" name="com_note_name" id="com_note_name" onblur="check_com_note_name(this.value)" value="<?=$com_note_name?>" placeholder="被投诉资料的名称">
          <input type="hidden" id="com_note_name_c">
          <div class="reg_error" id="error_com_note_name" style="display:none;"></div>
        </div>

        <div class="reg_put">
          <span>邮箱</span><br/>
          <input type="text" name="com_user_email" id="com_user_email" onblur="check_com_user_email(this.value)" value="<?=$com_user_email?>" placeholder="留下您的邮箱号">
          <input type="hidden" id="com_user_email_c">
          <div class="reg_error" id="error_com_user_email" style="display:none;"></div>
        </div>

        <div class="reg_put">
          <span>手机号</span><br/>
          <input type="text" name="com_user_phone" id="com_user_phone" onblur="" placeholder="留下您的手机号（非必要）">
          <input type="hidden" id="com_user_phone_c">
          <div class="reg_error" id="error_com_user_phone" style="display:none;"></div>
        </div>

        <div class="reg_put">
          <span>描述</span><br/>
          <textarea type="text" name="com_describe" id="com_describe" onblur="check_com_describe(this.value)" placeholder="尽可能详细的描述，以便我们核查"></textarea>
          <input type="hidden" id="com_describe_c">
          <div class="reg_error" id="error_com_describe" style="display:none;"></div>
        </div>

        <div class="reg_co">
          <?php if($if_login == "true"){?>
            <input type="button" class="button_c" name="" onclick="submit_compliant()" id="complaint" value="提交" style="width: 150px;height:33px;"/>
          <?php }else{?>
            <input type="button" class="button_c" name="" id="complaint" value="提交" style="width: 150px;height:33px;cursor: not-allowed;" disabled/>
          <?php }?>
        </div>

      </div>

    <a  href="<?php echo site_url('primary/wxc_feedback/feedback_page'); ?>">
      <div class="feedback">
      </div>
    </a>
  </div>
  <?php include  'application/frontend/views/share/footer.php';?>
</body>

</html>
