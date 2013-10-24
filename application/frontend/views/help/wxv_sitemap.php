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
        <a href="<?php echo site_url('home/index'); ?>">
          <div  class="_site_map_button _text_align pointer common_bule_button fl">Creamnote首页</div>
        </a>
        <a href="<?php echo site_url('home/data_upload_page'); ?>">
          <div  class="_site_map_button _text_align pointer common_bule_button fl">分享干货</div>
        </a>
        <a href="<?php echo site_url('home/image_upload_page'); ?>">
          <div  class="_site_map_button _text_align pointer common_bule_button fl">图片笔记</div>
        </a>
        <a href="<?php echo site_url('data/wxc_data/data_list'); ?>">
          <div  class="_site_map_button _text_align pointer common_bule_button fl">笔记展示</div>
        </a>
        <a href="<?php echo site_url('primary/wxc_feedback/feedback_page'); ?>">
          <div  class="_site_map_button _text_align pointer common_bule_button fl">反馈&咨询</div>
        </a>
        <a href="<?php echo site_url('core/wxc_content/more_article'); ?>">
          <div  class="_site_map_button _text_align pointer common_bule_button fl">精彩博文</div>
        </a>
        <a href="<?php echo site_url('core/wxc_content/more_site_notice'); ?>">
          <div  class="_site_map_button _text_align pointer common_bule_button fl">醍醐公告</div>
        </a>

        <a href="<?php echo site_url('static/wxc_about/about_creamnote'); ?>">
          <div  class="_site_map_button _text_align pointer common_bule_button fl">关于醍醐</div>
        </a>
        <a href="<?php echo site_url('static/wxc_about/about_us'); ?>">
          <div  class="_site_map_button _text_align pointer common_bule_button fl">团队介绍</div>
        </a>
        <a href="<?php echo site_url('static/wxc_about/connect_us'); ?>">
          <div  class="_site_map_button _text_align pointer common_bule_button fl">联系我们</div>
        </a>
        <a href="<?php echo site_url('static/wxc_help/faq'); ?>">
          <div  class="_site_map_button _text_align pointer common_bule_button fl">常见问题</div>
        </a>
        <a href="<?php echo site_url('static/wxc_help/skills'); ?>">
          <div  class="_site_map_button _text_align pointer common_bule_button fl">帮助教程</div>
        </a>
        <a href="<?php echo site_url('static/wxc_help/sitemap'); ?>">
          <div  class="_site_map_button _text_align pointer common_bule_button fl">网站导航</div>
        </a>
        <a href="<?php echo site_url('static/wxc_cooperation/medium'); ?>">
          <div  class="_site_map_button _text_align pointer common_bule_button fl">媒体报道</div>
        </a>
        <a href="<?php echo site_url('static/wxc_cooperation/friendly_link'); ?>">
          <div  class="_site_map_button _text_align pointer common_bule_button fl">友情链接</div>
        </a>
      </div>
    </div>
  </div>
  <?php include  'application/frontend/views/share/footer.php';?>
</body>

</html>
