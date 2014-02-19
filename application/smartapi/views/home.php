<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Smart API - 开放免费的互联网实用接口聚合平台</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.min.js"></script>
<link href="/application/smartapi/views/css/main.css" rel="stylesheet" type="text/css">

<script type="text/javascript">

function wx_search() {
    // alert("search ...");
    var search_text = $("#search_text").attr("value");
    search_text = search_text.trim();

    $("#search_text").val('输入您想找的服务内容，多个关键词使用“空格”分开');
    $("#search_text").css('color', '#999');

    alert('搜索：【'+search_text+'】');
}

$(document).ready(function(){
    // bind 'Enter' key for auto search
    $(function() {
        $("#search_text").bind('keypress', function(event) {
            if (event.keyCode == "13") {
                wx_search();
            }
        });
    });
});
</script>

</head>

<body>

<nav>
    <div class="in">
        <a href="#">首页</a>
        <a href="#">关于我们</a>
        <a href="#">分类检索</a>
        <a href="#">接口标准</a>
        <a href="#">开发者社区</a>
        <a href="#">合作伙伴</a>
        <a href="#">加入我们</a>
    </div>
</nav>

<section class="banner clearfix">
    <center><h2><b>Open 开放、Free 免费、Standard 标准、Stable 稳定、Pragmatic 实用</b></h2></center>
    <center><h4>寻找你迫切想要的互联网开放数据接口</h4></center>
    <center><h2>让开发互联网应用不在从 O 开始</h2></center>
    <div class="b_input">
        <input  id="search_text" value="输入您想找的服务内容，多个关键词使用“空格”分开"
        onclick="if(value == defaultValue) { value=''; this.style.color='#000'; }"
        onBlur="if(!value) {value = defaultValue; this.style.color='#999'}" style="color:#999" />
        <div class="b_search"><a href="javascript://" onclick="wx_search();"></a></div>
    </div>
</section>

<section class="grid clearfix">
    <div class="in">
        <div class="grid_in"></div>
        <div class="grid_in"></div>
        <div class="grid_in"></div>
        <div class="grid_in"></div>
        <div class="grid_in"></div>
        <div class="grid_in"></div>
        <div class="grid_in"></div>
        <div class="grid_in"></div>
        <div class="grid_in"></div>
        <div class="grid_in"></div>
        <div class="grid_in"></div>
        <div class="grid_in"></div>
    </div>
</section>

<section class="content clearfix">
    <div class="in">
    <p>关于我们</p>
    </div>
</section>


<footer>
<br>
<p style="font-size:90%">Copyright © 2014 smartapi.creamnote.com  All Rights Reserved
    <br>
    <a href="http://www.miitbeian.gov.cn/" style="font-size:80%">苏ICP备13034212号-1</a>
</p>
</footer>


</body>
</html>
