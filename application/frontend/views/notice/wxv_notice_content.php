<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Creamnote(<?php echo $notice_title;?>)</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/reset.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_footlist.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_home.css" />
    <link rel="stylesheet" href="/application/backend/views/css/default.css" type="text/css" media="screen" />

    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery-1.8.3.js"></script>
    <script src="/application/frontend/views/resources/js/jquery.tipsy.js" type="text/javascript"></script>

<script type="text/javascript">
</script>

</head>
<body>
  <?php include  'application/frontend/views/share/header_home.php';?>
  <div class="body article_body" style="border-top: 8px solid #839acd;">

    <div class="article_content">
      <div class="reg_frame notice_frame" style="text-align: left;width:600px;" id="reg_frame">
        <img src="/application/frontend/views/resources/images/version/trumpet.jpg" style="width: 135px;position: absolute;margin-left: -29px;margin-top: -30px;">
        <h2 style="padding: 0px 0 30px 95px;line-height: 35px;">
          <b><?php echo $notice_title;?></b>
        </h2>
        <div style="color:#000;">
          <?php echo $notice_content;?>
        </div>
        <img src="/application/frontend/views/resources/images/version/trumpet2.jpg" style="width: 135px;position: absolute;margin-left: 494px;margin-top: -61px;">
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
