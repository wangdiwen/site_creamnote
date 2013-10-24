<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Creamnote CMS account</title>
<script type="text/javascript" src="/application/backend/views/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="/application/backend/views/js/kindeditor-min.js"></script>
<script type="text/javascript" src="/application/backend/views/js/zh_CN.js"></script>
<script type="text/javascript" src="/application/backend/views/js/ZeroClipboard.js"></script>
<link rel="stylesheet" href="/application/backend/views/css/default.css" type="text/css" media="screen" />
</head>
<body>
<div id="body-wrapper">
  <!-- Wrapper for the radial gradient background -->
  <?php $menuParam='account_withdraw';?>
  <?php include("application/backend/views/wxv_menu.php");?>
  <div id="main-content">

    <!-- Page Head -->
    <h2>提现管理</h2>

    <div class="content-box">
      <div class="content-box-header">
        <h3>提现列表</h3>
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
                <th>提现单号</th>
                <th>提现者ID</th>
                <th>提现者账户</th>
                <th>提现金额</th>
                <th>提现时间</th>
                <th>提现状态</th>
                <th>提现管理员</th>
                <th>提现成功时间</th>
                <th>操作</th>

              </tr>
            </thead>

            <tbody>
              <?php  foreach ($withdraw_order as $key => $withdraw){?>
              <tr>
                <td>
                  <input type="checkbox" value="<?=$withdraw['draw_id']?>"/>
                </td>
                <td ><?=$withdraw['draw_no']?></td>
                <td ><?=$withdraw['draw_user_id']?></td>
                <td ><?=$withdraw['draw_ali_account']?></td>
                <td ><?=$withdraw['draw_money']?></td>
                <td ><?=$withdraw['draw_timestamp']?></td>
                <td ><?=$withdraw['draw_status']?></td>
                <td ><?=$withdraw['draw_admin']?></td>
                <td ><?=$withdraw['draw_admin_time']?></td>

                <td style="max-width:300px;">

                  <input type="hidden" id="hidden_account_name" value="<?=$withdraw['draw_ali_account']?>">
                  <input type="hidden" id="hidden_draw_money" value="<?=$withdraw['draw_money']?>">

                  <a href="#messages" rel="modal">
                    <input onclick="check_order_valid('<?=$withdraw['draw_no']?>','<?=$withdraw['draw_user_id']?>','<?=$withdraw['draw_money']?>')" type="button" onclick="" class="button" value="校验数据正确性">
                  </a>

                </td>
              </tr>

              <?php }?>

              <tr>

                <td colspan="10">
                  <div class="pagination"><?php echo $this->pagination->create_links(); ?></div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

      <div id="messages" >
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
