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
  <?php $menuParam='fed_complaint';?>
  <?php include("application/backend/views/wxv_menu.php");?>
  <div id="main-content">

    <!-- Page Head -->
    <h2>投诉管理(二)</h2>

    <div class="content-box">
      <div class="content-box-header">
        <h3>投诉列表</h3>
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
                <th>投诉标题</th>
                <th>笔记名称</th>
                <th>投诉者邮箱</th>
                <th>投诉者电话</th>
                <th>投诉者时间</th>
                <th>投诉内容</th>
                <th>操作</th>
              </tr>
            </thead>

            <tbody>
              <?php  foreach ($report_step_second as $key => $report){?>
              <tr>
                <td>
                  <input type="checkbox" value="<?=$report['com_id']?>"/>
                </td>
                <td ><?=$report['com_title']?></td>
                <td ><a target="_blank" href="<?=$report['com_link']?>"><?=$report['com_note_name']?></a></td>
                <td ><?=$report['com_user_email']?></td>
                <td ><?=$report['com_user_phone']?></td>
                <td ><?=$report['com_time']?></td>
                <td ><?=$report['com_describe']?></td>

                <td style="max-width:300px;">
                  <a href="#messages" rel="modal"><input onclick="show_comdata_detail('<?=$report['com_link']?>')" type="button" onclick="" class="button" value="查看详细"></a>
                  <input type="button" onclick="pass_complaint('<?=$report['com_id']?>')" class="button" value="驳回投诉">
                  <input type="button" onclick="close_note('<?=$report['com_user_email']?>','<?=$report['com_note_name']?>','<?=$report['com_link']?>','<?=$report['com_id']?>','<?=$report['com_time']?>')" class="button" value="封闭资料">
                  <a href="#messages" rel="modal"><input type="button" onclick="send_dim_email('<?=$report['com_user_email']?>','<?=$report['com_note_name']?>','<?=$report['com_link']?>','<?=$report['com_id']?>','<?=$report['com_time']?>')" class="button" value="待定"></a>
                  <a href="<?php echo base_url(); ?>cnadmin/report/download_note_data?com_link=<?=$report['com_link']?>" ><input type="button" onclick="" class="button" value="下载"></a>
                  <input type="hidden" name="feedback_offset" id="feedback_offset" value="<?php $feedback_offset;?>">
                </td>
              </tr>
              <?php }?>
              <tr>

                <td colspan="8">
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
        <h3 id="message_title" style="font-size: 18px;">资料详情</h3>
        <div id="message_content" style="font-size: 16px;">

        </div>

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
        show_dialog("一键删除临时文件","操作成功");
      }else if(returnInfo == "no-premission"){
        show_dialog("一键删除临时文件","没有权限");
      }else{
        show_dialog("一键删除临时文件","操作失败");
      }

    }

  });

</script>
</html>
