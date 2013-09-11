<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Creamnote CMS User</title>
<script type="text/javascript" src="/application/backend/views/js/jquery-1.9.1.js"></script>
</head>
<body>
<div id="body-wrapper">
  <!-- Wrapper for the radial gradient background -->
  <?php $menuParam='admin_user';?>
  <?php include("application/backend/views/wxv_menu.php");?>
  <div id="main-content">

    <!-- Page Head -->
    <h2>管理员</h2>

    <div class="content-box">
      <div class="content-box-header">
        <h3>新增管理员</h3>
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

                <th>姓名</th>
                <th>邮箱</th>
                <th>注册时间</th>
                <th>口令</th>
                <th>用户状态</th>
                <th>用户等级</th>
                <th>操作</th>
              </tr>
            </thead>

            <tbody>
              <?php  foreach ($admin_users as $key => $user){?>
              <tr>
                <td>
                  <input type="checkbox" />
                </td>
                <td ><?=$user['user_name']?></td>
                <td ><?=$user['user_email']?></td>
                <td ><?=$user['user_register_time']?></td>
                <td ><?=$user['user_token']?></td>
                <td ><?=$user['user_status']?></td>
                <td ><?=$user['user_type']?></td>
                <td >
                  <!-- Icons -->
                  <a href="#messages" rel="modal"><input onclick="update_admin_ms('<?=$user['user_name']?>','<?=$user['user_token']?>','<?=$user['user_status']?>','<?=$user['user_type']?>','<?=$user['user_email']?>')" type="button" class="button" value="修改"></a>
                  <input onclick="delete_admin_user('<?=$user['user_email']?>')" id="disable_user"  type="button" class="button" value="删除">
                  <a href="#messages" rel="modal"><input id="disable_user" onclick="reset_admin_pwd_ms('<?=$user['user_email']?>')" type="button" class="button" value="新密码"></a>
                </td>
              </tr>
              <?php }?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div id="dialog" title="修改公告">
       <p id="dialog_content"></p>
      </div>



    <?php include 'application/backend/views/wxv_footer.php';?>
    <!-- End #footer -->
  </div>

  <!-- End #main-content -->
</div>
</body>
<!-- Download From www.exet.tk-->
<script type="text/javascript">

</script>
</html>
