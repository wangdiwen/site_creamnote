<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>网站导航</title>
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
  <div class="body article_body">
    <div class="foot_list_left">
      <h1><b>帮助</b></h1>
      <ul class="foot_list_ul">
        <li><a href="<?php echo site_url('static/wxc_help/faq'); ?>" title="常见问题">常见问题</a></li>
        <li><a href="<?php echo site_url('static/wxc_help/skills'); ?>" title="帮助教程">帮助教程</a></li>
        <li class="nowli"><a href="#" title="网站地图">网站导航</a></li>
      </ul>
    </div>
    <div class="foot_list_right">
      <h2><b>网站导航</b></h2>
      <div class="text_line">
        <p style="font-size:14px" class="pointer" onclick="showcontent(1)"><b>Creamnote首页</b></p>

      </div>
    </div>
  </div>
  <?php include  'application/frontend/views/share/footer.php';?>
</body>

</html>
