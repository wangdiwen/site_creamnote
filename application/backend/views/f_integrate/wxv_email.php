<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Creamnote CMS integrate</title>
<script type="text/javascript" src="/application/backend/views/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="/application/backend/views/js/kindeditor-min.js"></script>
<script type="text/javascript" src="/application/backend/views/js/zh_CN.js"></script>
<link rel="stylesheet" href="/application/backend/views/css/default.css" type="text/css" media="screen" />
</head>
<body>
<div id="body-wrapper">
  <!-- Wrapper for the radial gradient background -->
  <?php $menuParam='integrate_email';?>
  <?php include("application/backend/views/wxv_menu.php");?>
  <div id="main-content">

    <!-- Page Head -->
    <h2>邮件管理</h2>

    <div class="content-box">
      <div class="content-box-header">
        <h3>发送邮件</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="default-tab">欢迎邮件</a></li>
          <li><a href="#tab2" onclick="week_send()" class="">周推荐</a></li>
          <li><a href="#tab3" onclick="month_send()"class="">月推荐</a></li>
        </ul>
        <div class="clear"></div>
      </div>

      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">
         <?php if(isset($current_week_tip)){
          echo $current_week_tip;?>
          <input type="button" class="button" value="发送邮件" onclick="send_welcome_email_ed()">
         <?php }else{?>
          你还不需要给用户发送邮件噢！
         <?php }?>
        </div>

        <div class="tab-content" id="tab2">
          <table>
            <thead>
              <tr>
                <th>
                  <input class="check-all" type="checkbox" />
                </th>
                <th>资料名称</th>
                <th>资料类型</th>
                <th>上传时间</th>
                <th>操作</th>
              </tr>
            </thead>

            <tbody id="week_dis">

            </tbody>
          </table>
          <input type='button' class='button' value='发送邮件' onclick="send_week_eamil()">
        </div>

        <div class="tab-content" id="tab3">
          <table>
            <thead>
              <tr>
                <th>
                  <input class="check-all" type="checkbox" />
                </th>
                <th>资料名称</th>
                <th>资料类型</th>
                <th>上传时间</th>
                <th>操作</th>
              </tr>
            </thead>

            <tbody id="month_dis">

            </tbody>
          </table>
          <input type='button' class='button' value='发送邮件' onclick="send_month_eamil()">
        </div>

      </div>
    </div>

    <div class="content-box" id="eamil_send" style="display:none;">
      <div class="content-box-header">
        <h3>邮件编辑</h3>
        <ul class="content-box-tabs">
        </ul>
        <div class="clear"></div>
      </div>

      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab2">
          <textarea id="editor_id" name="content" style="width:800px;height:400px;">

          </textarea>
          <div class="form" style="margin-top:10px;">

            <div>
              <input type="button" class="button" value="保存草稿" onclick="save_welcome_email()">
              <input type="button" class="button" value="发送" onclick="send_welcome_email()">
            </div>
          </div>
        </div>

      </div>

    </div>
    <div id="dialog_auto" title="修改公告">
       <p id="dialog_auto_content"></p>
    </div>
<?php include 'application/backend/views/wxv_footer.php';?>


</body>
<!-- Download From www.exet.tk-->

</html>
