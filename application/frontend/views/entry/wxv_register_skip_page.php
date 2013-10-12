<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>注册页面</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="/application/frontend/views/resources/css/reset.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/text.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/960.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_home.css" />
    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery-1.8.3.js"></script>
    <script type='text/javascript' src='/application/frontend/views/resources/js/common.js'></script>
    <script type='text/javascript' src='/application/frontend/views/resources/js/jquery.poshytip.js'></script>

<script type="text/javascript">
<!-- Javascript functions -->

//=========================================================邮箱跳转=========================================//
var type_url;
var url;
type_url = "<?php echo $url;?>".split('@')[1];
url = email_hash[type_url];
  $(function(){
      $("#hand_click").html("<a href='"+url+"' >点击此处手动跳转</a>")
  });
	function countDown(sec,elementId,backUrl) {
		for(var i=sec;i>=0;i--) {
			window.setTimeout("clockUpdate('"+elementId+"','"+i+"','"+backUrl+"')", (sec-i)*1000);
		}
	}

	function clockUpdate(elementId,sec,backUrl) {
		document.getElementById(elementId).innerHTML = sec;
		if(sec==0) {
			window.location.href=backUrl;
		}
	}
	countDown(5,'time',url);

</script>

</head>


<body>
    <div class="body" style="min-height: 635px;border-top: 8px solid #839acd;padding: 50px 0;">
      <div class="reg_frame">

        <h2>跳转到您注册邮箱</h2>

        <div class="reg_put" style="text-align: center;">
            5秒后网站自动跳转到注册邮箱服务器进行注册验证
          ><span id="time" style="color: red">5</span><<br/>
          <div id="hand_click">

          </div>

        </div>

      <div class="clear"></div>
    </div>



</body>

</html>
