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
<body>
<div id="body-wrapper">
  <!-- Wrapper for the radial gradient background -->
  <?php $menuParam='content_notice';?>
  <?php include("application/backend/views/wxv_menu.php");?>
  <div id="main-content">

    <!-- Page Head -->
    <h2>系统公告</h2>

    <div class="content-box">
      <div class="content-box-header">
        <h3>公告列表</h3>
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
                <th>标题</th>
                <th>时间</th>
                <th>状态</th>
                <th>操作</th>
              </tr>
            </thead>

            <tbody>
              <?php  if(is_array($site_notice)){foreach ($site_notice as $key => $notice){?>
              <tr>
                <td>
                  <input type="checkbox" value="<?=$notice['notice_id']?>"/>
                </td>
                <td ><?=$notice['notice_title']?></td>
                <td ><?=$notice['notice_time']?></td>
                <td ><?=$notice['notice_status']?></td>
                <td >
                  <?php if ($notice['notice_status'] == "false"){?>
                  <a href="<?php echo base_url(); ?>cnadmin/content/publish_notice?notice_id=<?=$notice['notice_id']?>&notice_offset=<?=$notice_offset?>"><input type="button" class="button" value="发布"></a>
                    <?php }else{?>
                    <a href="<?php echo base_url(); ?>cnadmin/content/unpublish_notice?notice_id=<?=$notice['notice_id']?>&notice_offset=<?=$notice_offset?>"><input type="button" class="button" value="撤销发布"></a>
                    <?php }?>
                  <input type="button" onclick="edit_notice('<?=$notice['notice_id']?>','<?=$notice['notice_title']?>')" class="button" value="编辑">
                </td>
              </tr>
              <?php }}?>
              <tr>

                <td colspan="5">
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
        <h3>新建公告(修改)</h3>
        <ul class="content-box-tabs">
        </ul>
        <div class="clear"></div>
      </div>

      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab2">
          <textarea id="editor_id" name="content" style="width:1000px;height:400px;">

          </textarea>
          <div class="form" style="margin-top:10px;">
            标题：<input class="text-input small-input" type="text" name="title" id="title" onfocus="javascript:$('#error_title').html('')"><div style="color:red;" id="error_title"></div><br/>
          <div>
          <input type="button" class="button" id="save_button" value="保存" onclick="save_notice()">
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
        show_dialog("公告管理","操作成功")
      }else if(returnInfo == "no-premission"){
        show_dialog("公告管理","没有权限");
      }else{
        show_dialog("公告管理","操作失败");
      }

    }

  });

</script>
</html>
