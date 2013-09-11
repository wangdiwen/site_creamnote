<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Creamnote(<?php echo $article_title;?>)</title>
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
      <h2><b><?php echo $article_title;?></b></h2>
      <div class="article_author">
        作者:<?php echo $article_author;?><br/>
        时间:<?php echo $article_time;?><br/>
        分类:<?php echo $article_category;?>
      </div>

      <?php echo $article_content;?>
      <div class="article_note">
        <div style="padding-left:0;">
          <span>注释</span><br/><?php echo $article_notes;?>
        </div>
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
