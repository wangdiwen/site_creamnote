<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Creamnote CMS Content</title>
<script type="text/javascript" src="/application/backend/views/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="/application/backend/views/js/kindeditor-min.js"></script>
<script type="text/javascript" src="/application/backend/views/js/zh_CN.js"></script>
<link rel="stylesheet" href="/application/backend/views/css/default.css" type="text/css" media="screen" />
</head>
<body style="">
<div id="body-wrapper">
  <!-- Wrapper for the radial gradient background -->
  <?php $menuParam='content_week';?>
  <?php include("application/backend/views/wxv_menu.php");?>
  <div id="main-content">

    <!-- Page Head -->
    <h2>每周一文</h2>

    <div class="content-box">
      <div class="content-box-header">
        <h3>文章列表</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="default-tab">列表</a></li>
        </ul>
        <div class="clear"></div>
      </div>

      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">
          <table>
            <thead>
              <tr>
                <th>
                  <input class="check-all" type="checkbox" />
                </th>
                <th>文章标题</th>
                <th>文章作者</th>
                <th>文章分类</th>
                <th>状态</th>
                <th>编写时间</th>
                <th>操作</th>
              </tr>
            </thead>

            <tbody>
              <?php  foreach ($week_article as $key => $week){?>
              <tr>
                <td>
                  <input type="checkbox" value="<?=$week['article_id']?>"/>
                </td>
                <td ><?=$week['article_title']?></td>
                <td ><?=$week['article_author']?></td>
                <td ><?=$week['article_category']?></td>
                <td ><?=$week['article_status']?></td>
                <td ><?=$week['article_time']?></td>
                <td >
                  <?php if ($week['article_status'] == "false"){?>
                  <a href="<?php echo base_url(); ?>cnadmin/article/publish_article?article_id=<?=$week['article_id']?>&article_offset=<?=$article_offset?>"><input type="button" class="button" value="发布"></a>
                    <?php }else{?>
                    <a href="<?php echo base_url(); ?>cnadmin/article/cancel_publish?article_id=<?=$week['article_id']?>&article_offset=<?=$article_offset?>"><input type="button" class="button" value="撤销发布"></a>
                    <?php }?>
                  <input type="button" onclick="edit_article('<?=$week['article_id']?>','<?=$week['article_category']?>','<?=$week['article_title']?>')" class="button" value="编辑">
                  <a href="<?php echo base_url(); ?>cnadmin/article/delete_article?article_id=<?=$week['article_id']?>&article_offset=<?=$article_offset?>">
                    <input type="button" style="background:red !important;border: 1px solid red !important;" class="button" value="删除">
                  </a>
                </td>
              </tr>
              <?php }?>
              <tr>

                <td colspan="7">
                  <div class="pagination"><?php echo $this->pagination->create_links(); ?></div>
                </td>

              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="content-box">
      <div class="content-box-header">
        <h3>新建文章(修改)</h3>
        <ul class="content-box-tabs">
        </ul>
        <div class="clear"></div>
      </div>

      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab2">
          <textarea id="editor_id" name="content" style="width:800px;height:400px;">

          </textarea>
          <div class="form" style="margin-top:10px;">
            标题：<input class="text-input small-input" style="width: 746px!important;" type="text" name="title" id="title" onfocus="javascript:$('#error_title').html('')"><div style="color:red;" id="error_title"></div><br/>
            分类：<input class="text-input small-input" style="width: 746px!important;" type="text" name="category" id="category" onfocus="javascript:$('#error_category').html('')"><div style="color:red;" id="error_category"></div><br/>
            <span style="margin-top: 10px;float: left;">注释：</span><textarea style="width: 746px!important;height: 16px;" class="text-input small-input"  type="text" name="note" id="note"></textarea>
          <div>
          <input type="button" class="button" id="save_button" value="保存" onclick="save_article()">
          <input type="button" class="button" value="发布">
        </div>
      </div>
    </div>

    <?php include 'application/backend/views/wxv_footer.php';?>
    <!-- End #footer -->
  </div>
      <div id="messages" style="display: none">
        <!-- Messages are shown when a link with these attributes are clicked: href="#messages" rel="modal"  -->
        <h3 id="message_title" style="font-size: 18px;">修改密码</h3>
        <p id="user_deatil" style="font-size: 16px;">

        </p>
      </div>
      <div id="dialog" title="修改公告">
       <p id="dialog_content"></p>
      </div>
      <div id="dialog_auto" title="修改公告">
       <p id="dialog_auto_content"></p>
      </div>
      <!-- End #messages -->
  <!-- End #main-content -->
</div>
</body>
<!-- Download From www.exet.tk-->
<script type="text/javascript">
$(function() {
    $( ".datepicker" ).datepicker({
      dateFormat: 'yy-mm-dd',
      autoSize: true
    });
    var returnInfo = "<?php echo isset($_COOKIE['return_code'])?$_COOKIE['return_code'] : '';?>";
    if(returnInfo !=""){
      if(returnInfo == "success"){
        show_dialog("每周一文管理","操作成功");
      }else if(returnInfo == "no-premission"){
        show_dialog("每周一文管理","没有权限");
      }else{
        show_dialog("每周一文管理","操作失败");
      }

    }

  });

</script>
</html>
