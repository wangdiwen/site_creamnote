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
        <h3>管理员列表</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="default-tab">列表</a></li>
          <li><a href="#tab2" class="default-tab">新增管理员</a></li>
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

        <div class="tab-content" id="tab2">
          <table>
            <thead>
              <tr>

                <th>姓名</th>
                <th>邮箱</th>
                <th>口令</th>
                <th>用户状态</th>
                <th>用户等级</th>
                <th>用户密码</th>
              </tr>
            </thead>

            <tbody>
              <tr>
                <td ><input type="text" id="add_user_name"></td>
                <td ><input type="text" id="add_user_email"></td>
                <td ><input type="text" id="add_user_token"></td>
                <td ><select id='add_user_status' style='width: 153px;'><option value='true' selected>启用</option><option value='false'>停用</option></select></td>
                <td ><select id='add_user_type' style='width: 153px;'><option value='super' selected>super</option><option value='admin'>admin</option><option value='common'>common</option></select></td>
                <td ><input type="text" id="add_user_password"></td>
              </tr>
              <tr>
                <td><input type="button" class="button" onclick="add_new_admin()" value="新增" style="width:70px;"></td>
              </tr>
            </tbody>
          </table>
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
    <input type="hidden" id="ret_common_json" />
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
    var returnInfo = "<?php echo isset($return_code)?$return_code : '';?>";
     if(returnInfo !=""){
      if(returnInfo == "success"){
        show_dialog("管理员管理","操作成功")
      }else if(returnInfo == "no-premission"){
        show_dialog("公告管理","没有权限");
      }else{
        show_dialog("管理员管理","操作失败");
      }

    }

  });
</script>
</html>
