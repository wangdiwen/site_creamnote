<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Creamnote CMS log</title>
<script type="text/javascript" src="/application/backend/views/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="/application/backend/views/js/kindeditor-min.js"></script>
<script type="text/javascript" src="/application/backend/views/js/zh_CN.js"></script>

<link rel="stylesheet" href="/application/backend/views/css/default.css" type="text/css" media="screen" />
</head>
<body>
<div id="body-wrapper">
  <!-- Wrapper for the radial gradient background -->
  <?php $menuParam='a_log';?>
  <?php include("application/backend/views/wxv_menu.php");?>
  <div id="main-content">

    <!-- Page Head -->
    <h2>自动化日志</h2>

    <div class="content-box">
      <div class="content-box-header">
        <h3>日志列表</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="<?php if($tab_no=="tab1"){?>default-tab<?php }?>">清理图片数据</a></li>
          <li><a href="#tab2" class="<?php if($tab_no=="tab2"){?>default-tab<?php }?>">清理未完善本地笔记资料数据</a></li>
          <li><a href="#tab3" class="<?php if($tab_no=="tab3"){?>default-tab<?php }?>">清理本地下载笔记资料数据</a></li>
          <li><a href="#tab4" class="<?php if($tab_no=="tab4"){?>default-tab<?php }?>">每天数据迁移</a></li>
          <li><a href="#tab5" class="<?php if($tab_no=="tab5"){?>default-tab<?php }?>">每天发送笔记资料通知记录</a></li>
        </ul>
        <div class="clear"></div>
      </div>

      <div class="content-box-content">

        <div class="tab-content <?php if($tab_no=="tab1"){?>default-tab<?php }?>" id="tab1">
          <table>
            <thead>
              <tr>
                <th>
                  <input class="check-all" type="checkbox" />
                </th>
                <th>日志内容</th>
                <th>操作</th>

              </tr>
            </thead>

            <tbody id="query_by_user">
              <?php  foreach ($auto_log_list['clear-image'] as $key => $log){?>
              <tr>
                <td>
                  <input type="checkbox" value="<?=$key?>"/>
                </td>
                <td ><?=$log?></td>

                <td style="max-width:300px;">
                  <input type="button" class="button" value="删除" onclick="delete_auto_log('<?=$log?>','tab1')">
                  <a href="http://www.creamnote.com/cnadmin/share/auto_task_log?log_name=<?=$log?>" target="_bank"><input type="button" class="button" value="查看"></a>
                </td>
              </tr>

              <?php }?>
              <tr>
                <td colspan="3">
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="tab-content<?php if($tab_no=="tab2"){?>default-tab<?php }?>" id="tab2">
          <table>
            <thead>
              <tr>
                <th>
                  <input class="check-all" type="checkbox" />
                </th>
                <th>日志内容</th>
                <th>操作</th>

              </tr>
            </thead>

            <tbody id="query_by_user">
              <?php  foreach ($auto_log_list['clear-unfull'] as $key => $log){?>
              <tr>
                <td>
                  <input type="checkbox" value="<?=$key?>"/>
                </td>
                <td ><?=$log?></td>

                <td style="max-width:300px;">
                  <input type="button" class="button" value="删除" onclick="delete_auto_log('<?=$log?>','tab2')">
                  <a href="http://www.creamnote.com/cnadmin/share/auto_task_log?log_name=<?=$log?>" target="_bank"><input type="button" class="button" value="查看"></a>
                </td>
              </tr>

              <?php }?>
              <tr>
                <td colspan="3">
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="tab-content <?php if($tab_no=="tab3"){?>default-tab<?php }?>" id="tab3">
          <table>
            <thead>
              <tr>
                <th>
                  <input class="check-all" type="checkbox" />
                </th>
                <th>日志内容</th>
                <th>操作</th>

              </tr>
            </thead>

            <tbody id="query_by_user">
              <?php  foreach ($auto_log_list['clear-vps'] as $key => $log){?>
              <tr>
                <td>
                  <input type="checkbox" value="<?=$key?>"/>
                </td>
                <td ><?=$log?></td>

                <td style="max-width:300px;">
                  <input type="button" class="button" value="删除" onclick="delete_auto_log('<?=$log?>','tab3')">
                  <a href="http://www.creamnote.com/cnadmin/share/auto_task_log?log_name=<?=$log?>" target="_bank"><input type="button" class="button" value="查看"></a>
                </td>
              </tr>

              <?php }?>
              <tr>
                <td colspan="3">
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="tab-content <?php if($tab_no=="tab4"){?>default-tab<?php }?>" id="tab4">
          <table>
            <thead>
              <tr>
                <th>
                  <input class="check-all" type="checkbox" />
                </th>
                <th>日志内容</th>
                <th>操作</th>

              </tr>
            </thead>

            <tbody id="query_by_user">
              <?php  foreach ($auto_log_list['data-move'] as $key => $log){?>
              <tr>
                <td>
                  <input type="checkbox" value="<?=$key?>"/>
                </td>
                <td ><?=$log?></td>

                <td style="max-width:300px;">
                  <input type="button" class="button" value="删除" onclick="delete_auto_log('<?=$log?>','tab4')">
                  <a href="http://www.creamnote.com/cnadmin/share/auto_task_log?log_name=<?=$log?>" target="_bank"><input type="button" class="button" value="查看"></a>
                </td>
              </tr>

              <?php }?>
              <tr>
                <td colspan="3">
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="tab-content <?php if($tab_no=="tab5"){?>default-tab<?php }?>" id="tab5">
          <table>
            <thead>
              <tr>
                <th>
                  <input class="check-all" type="checkbox" />
                </th>
                <th>日志内容</th>
                <th>操作</th>

              </tr>
            </thead>

            <tbody id="query_by_user">
              <?php  foreach ($auto_log_list['data-notify'] as $key => $log){?>
              <tr>
                <td>
                  <input type="checkbox" value="<?=$key?>"/>
                </td>
                <td ><?=$log?></td>

                <td style="max-width:300px;">
                  <input type="button" class="button" value="删除" onclick="delete_auto_log('<?=$log?>','tab5')">
                  <a href="http://www.creamnote.com/cnadmin/share/auto_task_log?log_name=<?=$log?>" target="_bank"><input type="button" class="button" value="查看"></a>
                </td>
              </tr>

              <?php }?>
              <tr>
                <td colspan="3">
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
