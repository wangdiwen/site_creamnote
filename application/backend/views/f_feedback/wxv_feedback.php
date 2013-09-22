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
  <?php $menuParam='fed_feedback';?>
  <?php include("application/backend/views/wxv_menu.php");?>
  <div id="main-content">

    <!-- Page Head -->
    <h2>反馈管理</h2>

    <div class="content-box">
      <div class="content-box-header">
        <h3>反馈列表</h3>
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
                <th>反馈内容</th>
                <th>反馈时间</th>
                <th>操作</th>
              </tr>
            </thead>

            <tbody>
              <?php  foreach ($feedback_topic as $key => $feed){?>
              <tr>
                <td>
                  <input type="checkbox" value="<?=$feed['feedback_id']?>"/>
                </td>
                <td ><?=$feed['feedback_content']?></td>
                <td ><?=$feed['feedback_time']?></td>
                <td >
                  <a href="#messages" rel="modal"><input type="button" onclick="get_topic_detail('<?=$feed['feedback_id']?>','<?=$feedback_offset;?>')" class="button" value="回复"></a>
                  <input type="hidden" name="feedback_offset" id="feedback_offset" value="<?=$feedback_offset;?>">
                  <a href="<?php echo base_url(); ?>cnadmin/feedback/delete_feedback?feedback_id=<?=$feed['feedback_id']?>&feedback_offset=<?=$feedback_offset?>">
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



      <div id="messages" style="display: none">
        <!-- Messages are shown when a link with these attributes are clicked: href="#messages" rel="modal"  -->
        <h3 id="message_title" style="font-size: 18px;">反馈回复</h3>
        <p id="feedback_re" style="font-size: 16px;">

        </p>
        <textarea style="width:500px;height:100px;" onblur="javascript:feedback_content = this.value" id="feedback_content"></textarea>
        <input type="button" id="feedback_submit" class="button" value="回复" onclick=""/>
        <input type="button" id="feedback_ingore" class="button" value="忽略" onclick=""/>
      </div>

      <div id="dialog" title="修改公告">
       <p id="dialog_content"></p>
      </div>
      <div id="dialog_auto" title="修改公告">
       <p id="dialog_auto_content"></p>
      </div>
<?php include 'application/backend/views/wxv_footer.php';?>
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
        show_dialog("反馈管理","操作成功");
      }else if(returnInfo == "no-premission"){
        show_dialog("反馈管理","没有权限");
      }else{
        show_dialog("反馈管理","操作失败");
      }

    }

  });

</script>
</html>
