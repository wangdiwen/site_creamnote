<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Creamnote CMS integrate</title>
<script type="text/javascript" src="/application/backend/views/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="/application/backend/views/js/kindeditor-min.js"></script>
<script type="text/javascript" src="/application/backend/views/js/zh_CN.js"></script>
<link rel="stylesheet" href="/application/backend/views/css/default.css" type="text/css" media="screen" />
</head>
<body>
<div id="body-wrapper">
  <!-- Wrapper for the radial gradient background -->
  <?php $menuParam='integrate_target';?>
  <?php include("application/backend/views/wxv_menu.php");?>
  <div id="main-content">

    <!-- Page Head -->
    <h2>指标管理</h2>

    <div class="content-box">
      <div class="content-box-header">
        <h3>网站指标</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="default-tab">网站笔记指标</a></li>
          <li><a href="#tab2" class="">月度笔记指标</a></li>
          <li><a href="#tab3" class="">查询笔记指标</a></li>
          <li><a href="#tab4" onclick="complaint_count()" class="">投诉指标</a></li>
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
                <th>时间轴</th>
                <th>用户数量</th>
                <th>笔记总数量</th>
                <th>上传数量</th>
                <th>图片笔记数量</th>
                <th>免费笔记次数</th>
                <th>付费下载次数</th>
                <th>下载总次数</th>
                <th>累计营收</th>
              </tr>
            </thead>

            <tbody>
              <?php  foreach ($site_info as $key => $info){?>
              <tr>
                <td>
                  <input type="checkbox" value="<?=$info['site_id']?>"/>
                </td>
                <td ><?=$info['site_date']?></td>
                <td ><?=$info['site_users']?></td>
                <td ><?=$info['site_note_count']?></td>
                <td ><?=$info['site_upload_count']?></td>
                <td ><?=$info['site_imagenote_count']?></td>
                <td ><?=$info['site_freedown_count']?></td>
                <td ><?=$info['site_paydown_count']?></td>
                <td ><?=$info['site_download_count']?></td>
                <td ><?=$info['site_total_income']?></td>

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

        <div class="tab-content" id="tab2">
          <table>
            <thead>
              <tr>
                <th>
                  <input class="check-all" type="checkbox" />
                </th>
                 <th>时间轴</th>
                <th>用户数量</th>
                <th>笔记总数量</th>
                <th>上传数量</th>
                <th>图片笔记数量</th>
                <th>免费笔记次数</th>
                <th>付费下载次数</th>
                <th>下载总次数</th>
                <th>累计营收</th>
              </tr>
            </thead>

            <tbody>

              <tr>
                <td>
                  <input type="checkbox" value="<?=$info['site_id']?>"/>
                </td>
                <td ><?=$site_month_info['site_date']?></td>
                <td ><?=$site_month_info['site_users']?></td>
                <td ><?=$site_month_info['site_note_count']?></td>
                <td ><?=$site_month_info['site_upload_count']?></td>
                <td ><?=$site_month_info['site_imagenote_count']?></td>
                <td ><?=$site_month_info['site_freedown_count']?></td>
                <td ><?=$site_month_info['site_paydown_count']?></td>
                <td ><?=$site_month_info['site_download_count']?></td>
                <td ><?=$site_month_info['site_total_income']?></td>
              </tr>

              <tr>

                <td colspan="10">
                  <div class="pagination"><?php echo $this->pagination->create_links(); ?></div>
                </td>

              </tr>
            </tbody>
          </table>
        </div>

        <div class="tab-content " id="tab3">
          <div class="form" >
            开始时间:<select id="start_year">
              <option>2013</option>
              <option>2014</option>
              <option>2015</option>
              <option>2016</option>
              <option>2017</option>
              <option>2018</option>
            </select>
            <select id="start_month">
              <option>01</option>
              <option>02</option>
              <option>03</option>
              <option>04</option>
              <option>05</option>
              <option>06</option>
              <option>07</option>
              <option>08</option>
              <option>09</option>
              <option>10</option>
              <option>11</option>
              <option>12</option>
            </select>
            结束时间:<select id="end_year">
              <option>2013</option>
              <option>2014</option>
              <option>2015</option>
              <option>2016</option>
              <option>2017</option>
              <option>2018</option>
            </select>
            <select id="end_month">
              <option>01</option>
              <option>02</option>
              <option>03</option>
              <option>04</option>
              <option>05</option>
              <option>06</option>
              <option>07</option>
              <option>08</option>
              <option>09</option>
              <option>10</option>
              <option>11</option>
              <option>12</option>
            </select>
            <input type="button" class="button" onclick="query_any_month()" style="width: 75px;" name="user_search" id="user_search" value="查找">
          </div>
          <table>
            <thead>
              <tr>
                <th>
                  <input class="check-all" type="checkbox" />
                </th>
                  <th>时间轴</th>
                <th>用户数量</th>
                <th>笔记总数量</th>
                <th>上传数量</th>
                <th>图片笔记数量</th>
                <th>免费笔记次数</th>
                <th>付费下载次数</th>
                <th>下载总次数</th>
                <th>累计营收</th>
              </tr>
            </thead>

            <tbody >
              <tr id="search_dis">

                <td colspan="10">
                  <div class="pagination"><?php echo $this->pagination->create_links(); ?></div>
                </td>

              </tr>
            </tbody>
          </table>
        </div>

         <div class="tab-content" id="tab4">
          <div>
            <div style="float:left;font-size:20px;">总量:<span id="total"></span></div>
            <div style="float:left;font-size:20px;color:green;">处理量:<span id="disposed"></span></div>
            <div style="float:left;font-size:20px;color:red;">未处理量:<span id="undisposed"></span></div>
          </div>
        </div>


      </div>
    </div>
<?php include 'application/backend/views/wxv_footer.php';?>

</body>
<!-- Download From www.exet.tk-->

</html>
