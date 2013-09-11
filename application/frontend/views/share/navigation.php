<div id="outhead"  style="width:100%;height:50px;z-index: 999;">
  <div id="header" class="_headShadow" style="width:100%;position: static;top: 0px;z-index: 888;">

    <div id="menu">
      <ul>
        <li class="<?php echo $nav_param=='home'?'_current_page_item':'';?>"><a id="home_icon" href="<?php echo site_url('home/index'); ?>">主页</a></li>
        <!-- <li ><a href='<?php echo site_url('home/personal'); ?>'>个人中心</a></li> -->
        <li class="<?php echo $nav_param=='note'?'_current_page_item':'';?> nick"><a href="<?php echo site_url('data/wxc_data/data_list'); ?>">笔记展示</a></li>
        <li class="<?php echo $nav_param=='upload_note'?'_current_page_item':'';?> nick "><a class="common_show_login_win" href="javascript:void(0)" onclick="jump_to_uploadfile()">分享干货</a></li>
        <li class="<?php echo $nav_param=='upload_image'?'_current_page_item':'';?> nick "><a class="common_show_login_win" href="javascript:void(0)" onclick="jump_to_uploadimage()">图片笔记</a></li>
                <!-- <li><a href="<?php echo site_url('primary/wxc_personal/update_userinfo_page'); ?>">账户设置</a></li> -->
      </ul>
            <?php include  'application/frontend/views/share/search.php';?>
        <!-- end #search -->
    </div>
    <!-- end #menu -->

  </div>
  </div>
