<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Creamnote CMS Content</title>
<script type="text/javascript" src="/application/backend/views/js/kindeditor-min.js"></script>
<script type="text/javascript" src="/application/backend/views/js/zh_CN.js"></script>
<script type="text/javascript" src="/application/frontend/views/resources/js/jquery.min.js"></script>
<script type="text/javascript" src="/application/frontend/views/resources/js/flexpaper.js"></script>
<link rel="stylesheet" href="/application/backend/views/css/default.css" type="text/css" media="screen" />
</head>
<body>
<div id="body-wrapper">
  <!-- Wrapper for the radial gradient background -->
  <?php $menuParam='data_examine';?>
  <?php include("application/backend/views/wxv_menu.php");?>
  <div id="main-content">

    <!-- Page Head -->
    <h2>资料审核</h2>

    <div class="content-box">
      <div class="content-box-header">
        <h3>资料列表</h3>
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
                <th>资料名称</th>
                <!-- <th>资料对象</th> -->
                <th>资料类型</th>
                <th>资料页数</th>
                <!-- <th>资料价格</th> -->
                <th>oss路径</th>
                <th>vps路径</th>
                <!-- <th>是否支持预览</th> -->
                <th>上传时间</th>
                <th>用户id</th>
                <!-- <th>资料状态</th> -->
                <th>操作</th>
              </tr>
            </thead>

            <tbody>
              <?php  foreach ($note_data as $key => $data){?>
              <tr>
                <td>
                  <input type="checkbox" value="<?=$data['data_id']?>"/>
                </td>
                <td><?=$data['data_name']?></td>
                <!-- <td ><?=$data['data_objectname']?></td> -->
                <td ><?=$data['data_type']?></td>
                <td ><?=$data['data_pagecount']?></td>
                <!-- <td ><?=$data['data_price']?></td> -->
                <td ><?=$data['data_osspath']?></td>
                <td ><?=$data['data_vpspath']?></td>
                <!-- <td ><?=$data['data_preview']?></td> -->
                <td ><?=$data['data_uploadtime']?></td>
                <td ><?=$data['user_id']?></td>
                <!-- <td ><?=$data['data_status']?></td> -->
                <td >
                  <a href="#messages" rel="modal2"><input onclick="show_data_detail('<?=$data['data_id']?>','<?=$data['data_name']?>','<?=$data['user_id']?>')" type="button" class="button" value="审核"></a>
                  <!-- <input type="button" onclick="" class="button" value="删除"> -->
                </td>
              </tr>
              <?php }?>
              <tr>

                <td colspan="13">
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
        <h3 style="font-size: 18px;">资料预览</h3>
        <p id="documentViewer" class="" style="background-color:#fff;margin: 0 auto;width:450px;height:580px;box-shadow: inset 0 0 10px rgb(150, 153, 167);z-index:10;"></p>
        <input id="add_to_top" type="button" class="button"  value="添加到邮件待选资料" onclick=""/>(审核前选择)
        <a id="data_pass" href=""><input type="button" class="button"  value="通过审核" onclick=""/></a>
        <a id="data_unpass" href=""><input type="button" class="button"  value="不通过审核" onclick=""/></a>
        <input type="hidden" id="offset" value="<?=$note_offset?>">
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
    var returnInfo = "<?php echo isset($_COOKIE['return_code'])?$_COOKIE['return_code'] : '';?>";
    if(returnInfo !=""){
      if(returnInfo == "success"){
        show_dialog("资料审核管理管理","审核成功")
      }else if(returnInfo == "no-premission"){
        show_dialog("资料审核管理","没有权限");
      }else{
        show_dialog("资料审核管理","审核失败");
      }

    }

  });



</script>
</html>
