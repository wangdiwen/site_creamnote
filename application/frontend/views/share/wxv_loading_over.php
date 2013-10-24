<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Creamnote(购买笔记)</title>
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
    <div class="reg_frame _feedback_frame _accountselect_frame" style="">
        <div class="_accountselect_title"><span>完成购买</span></div>
        <div style="border-bottom: 1px dashed rgb(185, 194, 197);padding: 10px 0;">
          亲爱的用户，本次笔记下载请求已经完成
        </div>

    </div>
     <div class="reg_frame _feedback_frame _accountselect_frame" style="">
        <div class="_accountselect_title"><span>提示</span></div>
        <div style="border-bottom: 1px dashed rgb(185, 194, 197);padding: 10px 0;">
          1、点击<a style="text-decoration:underline" href="<?php echo site_url('core/wxc_alipay/require_download_direct'); ?>">下载</a>以获得您购买的笔记<br/>
          2、如果下载失败，您也可以在<a style="text-decoration:underline" href="<?php echo site_url('home/personal'); ?>">个人中心</a>的购买记录中免费下载该份笔记<br/>
          3、支付完成后该份笔记你将拥有永久下载权<br/>

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
