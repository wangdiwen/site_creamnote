<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>联系我们</title>
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
    <div class="foot_list_left" style="height:600px;">
      <h1><b>关于我们</b></h1>
      <ul class="foot_list_ul">
        <li><a href="<?php echo site_url('static/wxc_about/about_creamnote'); ?>" title="关于醍醐">关于醍醐</a></li>
        <li><a href="<?php echo site_url('static/wxc_about/about_us'); ?>" title="团队介绍">团队介绍</a></li>
        <li class="nowli"><a href="#" title="联系我们">联系我们</a></li>
      </ul>
    </div>
    <div class="foot_list_right">
      <h2><b>联系我们</b></h2>
      <div class="text_line" >
        <p style="font-size:14px;">如果您对醍醐网有任何疑问，可以前往
          <a href="<?php echo site_url('static/wxc_help/faq'); ?>">【FAQ】</a>和
          <a href="<?php echo site_url('static/wxc_help/termsofservice'); ?>">【服务条款】</a>页面搜索、查看问题。</p>
        <p style="font-size:14px;">如果您对醍醐网有意见或者建议，可以点击首页右侧的【咨询】，</p>
        <p style="font-size:14px;"> 或者
          <a href="<?php echo site_url('primary/wxc_feedback/feedback_page'); ?>">【点击这里】</a>进入反馈提问页面，向我们提出自己的疑问和建议。</p>
        </br>
        </br>
        <p style="font-size:14px;">醍醐邮箱：creamnote@163.com</p>
        <p style="font-size:14px;">微博：xxxxxxx</p>

      </div>
    </div>
    <a href="<?php echo site_url('primary/wxc_feedback/feedback_page'); ?>">
      <div class="feedback">
      </div>
    </a>
  </div>
  <?php include  'application/frontend/views/share/footer.php';?>
</body>

</html>
