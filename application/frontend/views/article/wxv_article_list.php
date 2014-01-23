<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Creamnote(精彩博文)</title>
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

    <div class="article_content" style="margin: 10px 200px;padding: 5px 10px;border: 1px solid #ccc;background: #fff;">
      <h2 class="article_title"  ><b>精彩博文</b></h2>
      <div style="padding:10px 0;">
        <?php
          $num = 1;
          foreach ($site_article as $key => $week){?>
          <div class="article_list lh25">
            <?php if($num>3){?>
              <div class="article_icon"></div>
            <?php }else{?>
              <div class="article_icon_fire article_icon"></div>
            <?php }?>
            <a style="font-size: 18px;" href="<?php echo base_url()?>core/wxc_content/read_article?article_id=<?=$week['article_id']?>"><?=$week['article_title']?></a><br>
            <div style="line-height: 16px; margin-bottom: -6px;">
              <span class="fr" style="font-size: 12px;">分类:<?=$week['article_category']?></span></br>
              <span class="fr" style="font-size: 12px;">作者:<?=$week['article_author']?>/<?=$week['article_time']?></span>
            </div>
            </br>
          </div>
      <?php
        $num++;
      }?>
      </div>
      <div class="pagination" style="text-align: right;"><?php echo $this->pagination->create_links(); ?></div>
    </div>
    <a  href="<?php echo site_url('primary/wxc_feedback/feedback_page'); ?>">
      <div class="feedback">
      </div>
    </a>
  </div>
  <?php include  'application/frontend/views/share/footer.php';?>
</body>

</html>
