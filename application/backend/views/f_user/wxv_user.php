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
  <?php $menuParam='common_user';?>
  <?php include 'application/backend/views/wxv_menu.php';?>
  <div id="main-content">

    <!-- Page Head -->
    <h2>普通用户</h2>

    <div class="content-box">
      <div class="content-box-header">
        <h3>用户数量</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="default-tab">用户总数量</a></li>
          <li><a href="#tab6" class="">分时间段用户数量</a></li>
        </ul>
        <div class="clear"></div>
      </div>

      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">
          <form>
            用户数:<?php echo $user_count;?>
          </form>
        </div>
        <div class="tab-content" id="tab6">
          <div class="form" >
            开始时间<input class="text-input small-input datepicker" style="width:220px!important;" type="text" id="start_time" name="start_time" />
            结束时间<input class="text-input small-input datepicker" style="width:220px!important;" type="text" id="end_time" name="end_time" />
            <input type="button" class="button" onclick="search_by_time()" style="width: 75px;" name="user_search" id="user_search" value="查找">
            <div  id="tab5" style="color:red;">

            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="content-box">
      <div class="content-box-header">
        <h3>用户查找</h3>
        <ul class="content-box-tabs">
        </ul>
        <div class="clear"></div>
      </div>

      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab2">
          <div class="form" >
            <input class="text-input small-input" type="text" id="user_email_or_name" name="user_email_or_name" />
            <input type="button" class="button" onclick="user_search('<?php echo base_url(); ?>cnadmin/user/get_user_simple')" style="width: 75px;" name="user_search" id="user_search" value="查找">
          </div>
        </div>
      </div>
    </div>

    <div class="content-box" id="result_dis" style="display:none;">
      <div class="content-box-header">
        <h3>搜索结果</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab3" class="default-tab">Table</a></li>
        </ul>
        <div class="clear"></div>
      </div>

      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab3">
          <table>
            <thead>
              <tr>
                <th>
                  <input class="check-all" type="checkbox" />
                </th>
                <th>姓名</th>
                <th>邮箱</th>
                <th>注册时间</th>
                <th>届</th>
                <th>用户状态</th>
                <th>操作</th>
              </tr>
            </thead>

            <tbody>
              <tr>
                <td>
                  <input type="checkbox" />
                </td>
                <td id="user_name"></td>
                <td id="user_email"></td>
                <td id="user_reg"></td>
                <td id="user_period"></td>
                <td id="user_status"></td>
                <td id="user_id" data-id="">
                  <!-- Icons -->
                  <a href="#messages" rel="modal2" title="Edit"><input onclick="show_user_detail()" type="button" class="button" value="详情"></a>
                  <a href="#" title="Delete">
                    <input id="disable_user" onclick="disable_user()" type="button" class="button" value="封号">
                  </a>
                </td>
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
        <h3 style="font-size: 18px;">用户详情</h3>
        <p id="user_deatil" style="font-size: 16px;">

        </p>


      </div>
      <div id="dialog" title="修改公告">
       <p id="dialog_content"></p>
      </div>
    <input type="hidden" id="ret_common_json" />
      <!-- End #messages -->
  <!-- End #main-content -->
</div>
</body>
<!-- Download From www.exet.tk-->
<script type="text/javascript">
$(function() {
    $( ".datepicker" ).datepicker({
      dateFormat: 'yy-mm-dd',
      autoSize: true,
      clearText: '清除',
      clearStatus: '清除已选日期',
      closeText: '关闭',
      closeStatus: '不改变当前选择',
      prevText: '<上月',
      prevStatus: '显示上月',
      prevBigText: '<<',
      prevBigStatus: '显示上一年',
      nextText: '下月>',
      nextStatus: '显示下月',
      nextBigText: '>>',
      nextBigStatus: '显示下一年',
      currentText: '今天',
      currentStatus: '显示本月',
      monthNames: ['一月','二月','三月','四月','五月','六月', '七月','八月','九月','十月','十一月','十二月'],
      monthNamesShort: ['一','二','三','四','五','六', '七','八','九','十','十一','十二'],
      monthStatus: '选择月份',
      yearStatus: '选择年份',
      weekHeader: '周',
      weekStatus: '年内周次',
      dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
      dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],
      dayNamesMin: ['日','一','二','三','四','五','六'],
      dayStatus: '设置 DD 为一周起始',
      dateStatus: '选择 m月 d日, DD',
      dateFormat: 'yy-mm-dd',
      firstDay: 1,
      initStatus: '请选择日期',
      isRTL: false
    });
  });
</script>
</html>
