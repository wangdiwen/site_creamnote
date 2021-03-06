<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Creamnote CMS account</title>
<script type="text/javascript" src="/application/backend/views/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="/application/backend/views/js/kindeditor-min.js"></script>
<script type="text/javascript" src="/application/backend/views/js/zh_CN.js"></script>

<link rel="stylesheet" href="/application/backend/views/css/default.css" type="text/css" media="screen" />
</head>
<body>
<div id="body-wrapper">
  <!-- Wrapper for the radial gradient background -->
  <?php $menuParam='account_withdraw_history';?>
  <?php include("application/backend/views/wxv_menu.php");?>
  <div id="main-content">

    <!-- Page Head -->
    <h2>提现历史</h2>

    <div class="content-box">
      <div class="content-box-header">
        <h3>提现历史列表</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab2" class="default-tab">提现查询列表</a></li>
        </ul>
        <div class="clear"></div>
      </div>

      <div class="content-box-content">


        <div class="tab-content default-tab" id="tab2">
          <div class="form" >
            <input class="text-input small-input" type="text" id="user_email" name="user_email" />
            <input type="button" class="button" onclick="get_withdraw_by_user()" style="width: 75px;" name="user_search" id="user_search" value="查找">
          （根据邮箱精确查找）
          </div>
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
                <th>提现管理员</th>
                <th>提现成功时间</th>

              </tr>
            </thead>

            <tbody id="query_by_user">
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
