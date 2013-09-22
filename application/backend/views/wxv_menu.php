<link rel="stylesheet" href="/application/backend/views/css/reset.css" type="text/css" media="screen" />
<!-- Main Stylesheet -->
<link rel="stylesheet" href="/application/backend/views/css/style.css" type="text/css" media="screen" />
<!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
<link rel="stylesheet" href="/application/backend/views/css/invalid.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/application/backend/views/css/jquery-ui-1.10.3.custom.css" type="text/css" media="screen" />
<!-- jQuery -->
<script type="text/javascript" src="/application/backend/views/js/jquery.wysiwyg.js"></script>
<!-- jQuery Configuration -->
<script type="text/javascript" src="/application/backend/views/js/simpla.jquery.configuration.js"></script>
<!-- Facebox jQuery Plugin -->
<script type="text/javascript" src="/application/backend/views/js/facebox.js"></script>
<script type="text/javascript" src="/application/backend/views/js/jquery-ui-1.10.3.custom.js"></script>
<script type="text/javascript" src="/application/backend/views/js/back-common.js"></script>
<div id="sidebar">
    <div id="sidebar-wrapper">
      <!-- Sidebar with logo and menu -->
      <h1 id="sidebar-title"><a href="#">Creamnote</a></h1>
      <!-- Logo (221px wide) -->
      <a href="#"><img id="logo" src="/application/backend/views/images/dy_head_logo.png" alt="Simpla Admin logo" /></a>
      <!-- Sidebar Profile links -->
      <div id="profile-links"> Hello, <a href="#" title="Edit your profile"><?php if (isset($_SESSION['admin_user_name'])) echo $_SESSION['admin_user_name']; else echo '匿名管理员'; ?></a><br />
        <br />

        <a target="_blank" href="<?php echo base_url();?>" title="View the Site">跳到Creamnote</a> | <a href="<?php echo base_url(); ?>cnadmin/home/logout" title="Sign Out">注销</a> </div>
      <ul id="main-nav">
        <!-- Accordion Menu -->
        <li> <a href="#" class="nav-top-item <?php echo ($menuParam=='common_user')||($menuParam=='admin_user')? 'current' :'';?>">
          <!-- Add the class "no-submenu" to menu items with no sub menu -->
          用户管理 </a>
          <ul>
            <li><a class="<?php echo ($menuParam=='common_user')? 'current' :'';?>" href="<?php echo base_url(); ?>cnadmin/user/user_index">普通用户</a></li>
            <li><a class="<?php echo ($menuParam=='admin_user')? 'current' :'';?>" href="<?php echo base_url(); ?>cnadmin/user/admin_index">管理员</a></li>

            <?php ($menuParam=='common_user')? 'current' :'';?>
          </ul>
        </li>

        <li> <a href="#" class="nav-top-item <?php echo ($menuParam=='data_examine')? 'current' :'';?>">
          <!-- Add the class "current" to current menu item -->
             资料审核管理 </a>
          <ul>
            <li><a class="<?php echo ($menuParam=='data_examine')? 'current' :'';?>" href="<?php echo base_url(); ?>cnadmin/audit/audit_index">资料审核</a></li>
          </ul>
        </li>

        <li> <a href="#" class="nav-top-item <?php echo ($menuParam=='content_week'||$menuParam=='content_notice')? 'current' :'';?>">网站内容管理</a>
          <ul>
            <li><a class="<?php echo ($menuParam=='content_notice')? 'current' :'';?>" href="<?php echo base_url(); ?>cnadmin/content/notice_index">公告管理</a></li>
            <li><a class="<?php echo ($menuParam=='content_week')? 'current' :'';?>" href="<?php echo base_url(); ?>cnadmin/article/article_index">每周一文</a></li>
          </ul>
        </li>

        <li> <a href="#" class="<?php echo ($menuParam=='integrate_target'||$menuParam=='integrate_email')? 'current' :'';?> nav-top-item">网站综合管理</a>
          <ul>
            <li><a class="<?php echo ($menuParam=='integrate_target')? 'current' :'';?>" href="<?php echo base_url(); ?>cnadmin/general/general_index">指标管理</a></li>
            <li><a class="<?php echo ($menuParam=='integrate_email')? 'current' :'';?>" href="<?php echo base_url(); ?>cnadmin/general/system_email_index">系统邮件</a></li>
          </ul>
        </li>
        <li> <a class="<?php echo ($menuParam=='fed_feedback'||$menuParam=='fed_complaint'||$menuParam=='fed_complaint_sec')||$menuParam=='fed_complaint_thi'? 'current' :'';?> nav-top-item">反馈、投诉&举报管理</a>
          <ul>
            <li><a class="<?php echo ($menuParam=='fed_feedback')? 'current' :'';?>" href="<?php echo base_url(); ?>cnadmin/feedback/feedback_index">反馈管理</a></li>
            <li><a class="<?php echo ($menuParam=='fed_complaint')? 'current' :'';?>" href="<?php echo base_url(); ?>cnadmin/report/report_step_one_index">投诉管理(一)</a></li>
            <li><a class="<?php echo ($menuParam=='fed_complaint_sec')? 'current' :'';?>" href="<?php echo base_url(); ?>cnadmin/report/report_step_second_index">投诉管理(二)</a></li>
            <!-- <li><a class="<?php echo ($menuParam=='fed_complaint_thi')? 'current' :'';?>" href="<?php echo base_url(); ?>cnadmin/report/report_step_third_index">投诉管理(三)</a></li> -->
          </ul>
        </li>


        <li> <a href="#" class="nav-top-item">提现申请、审核&操作管理</a>
          <ul>
            <li><a href="#">General</a></li>
            <li><a href="#">Design</a></li>
            <li><a href="#">Your Profile</a></li>
            <li><a href="#">Users and Permissions</a></li>
          </ul>
        </li>
      </ul>
      <!-- End #main-nav -->

    </div>
    <input type="hidden" id="baseUrl" value="<?php echo base_url();?>">
  </div>
  <!-- End #sidebar -->
