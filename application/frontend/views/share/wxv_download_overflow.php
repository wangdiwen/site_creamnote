<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Creamnote(当日下载已满)</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/reset.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_footlist.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_home.css" />

    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery-1.8.3.js"></script>
    <script src="/application/frontend/views/resources/js/jquery.tipsy.js" type="text/javascript"></script>

<script type="text/javascript">
</script>

</head>
<body>
  <?php include  'application/frontend/views/share/header_home.php';?>
  <div class="body article_body" style="border-top: 8px solid #839acd;">

    <div style="text-align: center;margin-top: 100px;color: red;font-size: 35px;">你当日免费下载量已满5份，请明天再试！
      <a style="text-decoration:underline;" href="javascript:history.back()">返回</a></div>
    <a  href="<?php echo site_url('primary/wxc_feedback/feedback_page'); ?>">
      <div class="feedback">
      </div>
    </a>
  </div>
  <?php include  'application/frontend/views/share/footer.php';?>
</body>

</html>
