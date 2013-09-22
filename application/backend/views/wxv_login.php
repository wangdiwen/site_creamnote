<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Creamnote.com  CMS</title>
<link rel="stylesheet" href="/application/backend/views/css/reset.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/application/backend/views/css/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/application/backend/views/css/invalid.css" type="text/css" media="screen" />
<script type="text/javascript" src="/application/backend/views/js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="/application/backend/views/js/simpla.jquery.configuration.js"></script>
<script type="text/javascript" src="/application/backend/views/js/facebox.js"></script>
<script type="text/javascript" src="/application/backend/views/js/jquery.wysiwyg.js"></script>
<script type="text/javascript" src="/application/backend/views/js/back-common.js"></script>
</head>
<body id="login">
<div id="login-wrapper" class="png_bg">
  <div id="login-top">
    <h1>Creamnote.com  CMS</h1>
    <!-- Logo (221px width) -->
    <a href="#"><img id="logo" src="/application/backend/views/images/dy_head_logo.png" alt="Simpla Admin logo" /></a> </div>
  <!-- End #logn-top -->
  <div id="login-content">

    <form class="form" action="<?php echo base_url(); ?>cnadmin/home/login" id="loginForm" method="post" style="width:370px;">
      <p>
        <label>邮箱号</label>
        <input class="text-input" style="float:left;" type="text" id="admin_email" name="admin_email" autocomplete="off" onfocus="javascript:$('#error_mail').html('')"/>
        <span id="error_mail" style="color:red;"></span>
      </p>
      <div class="clear"></div>
      <p>
        <label>密码</label>
        <input class="text-input" style="float:left;" type="password" id="admin_password" name="admin_password" onfocus="javascript:$('#error_pwd').html('')"/>
        <span id="error_pwd" style="color:red;"></span>
      </p>
      <div class="clear"></div>
      <p id="check">
        <label>验证</label>
        <div style="float:left;padding-left: 19px;font-size: 14px;"><?php echo $admin_auth_code;?>=？</div>
        <input type="text" id="admin_auth_code" name="admin_auth_code" style="width:50px;float:left;height: 20px;" onfocus="javascript:$('#error_code').html('')">
        <span id="error_code" style="color:red;"></span>
      </p>
      <div class="clear"></div>
      <p>
        <input class="button" type="button" onclick="login()" value="登 录" style="margin-right: 96px;"/>
      </p>
      <input type="hidden" id="baseurl" value="<?php echo base_url(); ?>">
    </form >
  </div>
  <!-- End #login-content -->
</div>
<!-- End #login-wrapper -->
</body>
</html>
