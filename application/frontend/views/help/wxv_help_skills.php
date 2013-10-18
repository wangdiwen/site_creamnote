<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>帮助教程</title>
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
    <div class="foot_list_left" style="padding-bottom:0px!important;margin-bottom:0px!important;height: 7000px;">
      <h1><b>帮助</b></h1>
      <ul class="foot_list_ul">
        <li><a href="<?php echo site_url('static/wxc_help/faq'); ?>" title="常见问题">常见问题</a></li>
        <li class="nowli"><a href="#" title="帮助教程">帮助教程</a></li>
        <li><a href="<?php echo site_url('static/wxc_help/sitemap'); ?>" title="网站地图">网站导航</a></li>
      </ul>
    </div>
    <div class="foot_list_right" style="padding-bottom:0px!important;margin-bottom:0px!important;">
      <h2><b>帮助教程</b></h2>
      <div class="text_line">
        <p>目录<p>
        <!-- 目录 -->
        <a href="#upload_notes"><p style="font-size:14px" class="pointer" ><b>1.分享干货</b></p></a>
        <a href="#image_notes"><p style="font-size:14px" class="pointer" ><b>2.图片笔记</b></p></a>
        <a href="#buy_notes"><p style="font-size:14px" class="pointer" ><b>3.购买笔记</b></p></a>
        <a href="#account_activate"><p style="font-size:14px" class="pointer" ><b>4.账户管理（激活）</b></p></a>
        <a href="#account_deposit"><p style="font-size:14px" class="pointer" ><b>5.账户管理（提现）</b></p></a>
        <a href="#feedback"><p style="font-size:14px" class="pointer" ><b>6.给平台提供建议和反馈</b></p></a>
        <a href="#grade_comment"><p style="font-size:14px" class="pointer" ><b>7.笔记打分和评论</b></p></a>
        <a href="#article"><p style="font-size:14px" class="pointer" ><b>8.精彩博文和系统公告</b></p></a>
        <a href="#collect_notes"><p style="font-size:14px" class="pointer" ><b>9.收藏笔记</b></p></a>
        <a href="#follow"><p style="font-size:14px" class="pointer" ><b>10.关注和粉丝</b></p></a>
        <a href="#message"><p style="font-size:14px" class="pointer _help_tail" ><b>11.给笔记所有者留言</b></p></a>
        <!-- 目录 -->

        <!-- 分享干货 -->
        <a name="upload_notes"><p style="font-size:14px" class="pointer" ><b>1.分享干货</b></p></a>
          <div class="help_section">
            分享干货是网站上传已有笔记资料的主入口，如果你觉得你的私有笔记资料很价值，需要分享，都可以在这里上传，定义
            好你的分类、关键词、价格等。
          </div>
          <div class="help_section">
            <div class="fl help_stand_right">
              第一步：已有creamnote账号的用户，登录后进入“个人中心”或者“笔记展示”可以看见顶部展示的导航栏，点击“分享干货”，跳转到
              分享上传页面
            </div>
            <div class="help_stand_left">
              <img style='width:500px;' src="/application/frontend/views/resources/images/help_image/2.jpg"></img>
            </div>
          </div>
          <div class="help_section">
            <div class="fl help_stand_right">
              第二步：点击“选择资料”按钮，选择一份您电脑本地的笔记资料，网站会自动上传都服务器上，目前支持的文件格式有（*.pdf;*.docx;*.doc;*.wps;*.ppt;*.pptx），笔记最大为4M
            </div>
            <div class="help_stand_left">
              <img style='width:500px;' src="/application/frontend/views/resources/images/help_image/1.jpg"></img>
            </div>
          </div>
          <div class="help_section">
            <div class="fl help_stand_right">
              第三步：给你上传的笔记分类，在级联选择框中选择，并添加你觉得合适的关键词，如：“化学”、“计算机”
            </div>
            <div class="help_stand_left">
              <img style='width:500px;' src="/application/frontend/views/resources/images/help_image/3.jpg"></img>
            </div>
          </div>
          <div class="help_section">
            <div class="fl help_stand_right">
              第四步：给你上传的笔记定义标题、价格、和简介，标题会自动生成并且可根据你具体需要修改，价格可在免费到9.99元之间选择，简介尽量详尽，不少与20个字；当所有内容都完成时（左侧提示步骤都为绿色），可点击“完成上传”按钮完成上传工作
            </div>
            <div class="help_stand_left">
              <img style='width:500px;' src="/application/frontend/views/resources/images/help_image/4.jpg"></img>
            </div>
          </div>
          <div class="help_section">
            <div class="fl help_stand_right" style="width: 150px;">
              第五步：完成上传步骤后会跳转到个人中心，您刚才上传的笔记资料会出现在“待审核笔记”一栏中，我们会及时审核你上传的内容
            </div>
            <div class="help_stand_left" style="margin-left: 148px;">
              <img style='width:600px;' src="/application/frontend/views/resources/images/help_image/5.jpg"></img>
            </div>
          </div>
        <p class="_help_tail"></p>
        <!-- 分享干货 -->

        <!-- 图片笔记 -->
        <a name="image_notes"><p style="font-size:14px" class="pointer" ><b>2.图片笔记</b></p></a>
          <div class="help_section">
            图片笔记是creamnote的一个特殊功能，出发点是为了让学生的手写笔记能够有个更好的用处，在这里，你可以将你的手写笔记拍成一张张照片，然后上传到我们网站，制作成一份图片笔记
          </div>
          <div class="help_section">
              <div class="fl help_stand_left_t">
                第一步：已有creamnote账号的用户，登录后进入“个人中心”或者“笔记展示”可以看见顶部展示的导航栏，点击“图片笔记”，跳转到
              图片笔记制作页面
              </div>
              <div class="help_stand_right_t">
                <img style='width:500px;' src="/application/frontend/views/resources/images/help_image/6.jpg"></img>
              </div>
          </div>
          <div class="help_section">
              <div class="fl help_stand_left_t">
                第二步：点击“上传图片”，上传你拍的笔记照片，支持批量上传，目前支持的图片格式(*.jpg; *.jpeg; *.png; *.gif),单张最大（2M）
              </div>
              <div class="help_stand_right_t">
                <img style='width:500px;' src="/application/frontend/views/resources/images/help_image/7.jpg"></img>
              </div>
          </div>
          <div class="help_section">
              <div class="fl help_stand_left_t">
                第三步：在这里，根据您的需要，可以拖动红色标记区域来更改笔记的顺序，以及可以对每张图片进行必要的旋转
              </div>
              <div class="help_stand_right_t">
                <img style='width:500px;' src="/application/frontend/views/resources/images/help_image/8.jpg"></img>
              </div>
          </div>
          <div class="help_section">
              <div class="fl help_stand_left_t">
                第四步：填写笔记、笔记作者、作者所在学校、笔记页眉、笔记简介信息，填写完整后点击“完成上传”，creamnote会自动帮您生成一份笔记文档
              </div>
              <div class="help_stand_right_t">
                <img style='width:500px;' src="/application/frontend/views/resources/images/help_image/9.jpg"></img>
              </div>
          </div>
          <div class="help_section">
              <div class="fl help_stand_left_t">
                第五步：笔记文档生成后，会跳转到笔记完善页，您需要完善在creamnote的分类信息，以便推广您的笔记，具体过程类似<a href="#upload_notes">“分享干货”</a>
              </div>
              <div class="help_stand_right_t">
                <img style='width:500px;' src="/application/frontend/views/resources/images/help_image/10.jpg"></img>
              </div>
          </div>
        <p class="_help_tail"></p>
        <!-- 图片笔记 -->

        <!-- 购买笔记 -->
        <a name="buy_notes"><p style="font-size:14px" class="pointer" ><b>3.购买笔记</b></p></a>
        <p class="_help_tail"></p>
        <!-- 购买笔记 -->

        <!-- 账户管理（激活） -->
        <a name="account_activate"><p style="font-size:14px" class="pointer" ><b>4.账户管理（激活）</b></p></a>
        <p class="_help_tail"></p>
        <!-- 账户管理（激活） -->

        <!-- 账户管理（提现） -->
        <a name="account_deposit"><p style="font-size:14px" class="pointer" ><b>5.账户管理（提现）</b></p></a>
        <p class="_help_tail"></p>
        <!-- 账户管理（提现） -->

        <!-- 给平台提供建议和反馈 -->
        <a name="feedback"><p style="font-size:14px" class="pointer" ><b>6.给平台提供建议和反馈</b></p></a>
          为了更好地倾听用户的声音，及时反馈用户的信息，Creamnote单独建立了一个“咨询和反馈”功能页，您可以在这个畅所欲言，说说你对Creamnote的看法
          <div class="help_section">
              <div class="fl help_stand_left_t">
                在任何一个网页中，你都可以看到左侧的按钮，点击后就会进入“咨询和反馈”页面
              </div>
              <div class="help_stand_right_t">
                <img style='width:110px;height: 130px;' src="/application/frontend/views/resources/images/help_image/11.jpg"></img>
              </div>
          </div>
          <div class="help_section">
              <div class="fl help_stand_left_t">
                在这里你可以在输入框中输入发起一个话题，写好后，点击按钮提交，当然，内容必须积极向上哦！
              </div>
              <div class="help_stand_right_t">
                <img style='width:500px;' src="/application/frontend/views/resources/images/help_image/12.jpg"></img>
              </div>
          </div>
          <div class="help_section">
              <div class="fl help_stand_left_t">
                你可以对你感兴趣的话题反馈信息进行回复，在每一块话题中都有一个“回复”按钮，点击出现回复框，在这里填写你的回复内容
              </div>
              <div class="help_stand_right_t">
                <img style='width:500px;' src="/application/frontend/views/resources/images/help_image/13.jpg"></img>
              </div>
          </div>
        <p class="_help_tail"></p>
        <!-- 给平台提供建议和反馈 -->

        <!-- 笔记打分和评论 -->
        <a name="grade_comment"><p style="font-size:14px" class="pointer" ><b>7.笔记打分和评论</b></p></a>
          <div class="help_section">
            笔记打分：你可以在笔记预览页面，点击文档先优良差三个评分点，给出你对该份笔记的评价，两小时内只能评分一次

          </div>
          <div class="help_section">
            笔记评论：你可以在笔记预览页面，关于笔记写一下一些评论内容，两小时内只能评论一次

          </div>
        <p class="_help_tail"></p>
        <!-- 笔记打分和评论 -->

        <!-- 精彩博文和系统公告 -->
        <a name="article"><p style="font-size:14px" class="pointer" ><b>8.精彩博文和系统公告</b></p></a>
        <p class="_help_tail"></p>
        <!-- 精彩博文和系统公告 -->

        <!-- 收藏笔记 -->
        <a name="collect_notes"><p style="font-size:14px" class="pointer" ><b>9.收藏笔记</b></p></a>
        <p class="_help_tail"></p>
        <!-- 收藏笔记 -->

        <!-- 关注和粉丝 -->
        <a name="follow"><p style="font-size:14px" class="pointer" ><b>10.关注和粉丝</b></p></a>
        <p class="_help_tail"></p>
        <!-- 关注和粉丝 -->

        <!-- 给笔记所有者留言 -->
        <a name="message"><p style="font-size:14px" class="pointer" ><b>11.给笔记所有者留言</b></p></a>
        <p class="_help_tail"></p>
        <!-- 给笔记所有者留言 -->

        <!--滚动至顶部-->
        <div id="updown"><span class="up transition"></span></div>
      </div>
    </div>
  </div>
  <?php include  'application/frontend/views/share/footer.php';?>
</body>

</html>
