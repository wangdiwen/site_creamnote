<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>修改笔记(<?php echo $data_info['data_base']['data_name'];?>)</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_home.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/style.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/menu-css.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/chosen.css" />
    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="/application/frontend/views/resources/js/menu_min.js"></script>
    <script type="text/javascript" src="/application/frontend/views/resources/js/school.js"></script>
    <script type="text/javascript" src="/application/frontend/views/resources/js/chosen.jquery.js"></script>
    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery.autocomplete.min.js"></script>
<script type="text/javascript">
<!-- Javascript functions -->
var area_id;
var area_id;
var step_one_success = 1;
var step_two_success = 1;
var step_three_success = 1;
$(document).ready(function(){
    $("#select_price").chosen();

	$("#first_step").addClass("ca-menu_hover");
    $("#first_step span").addClass("ca-menu_hover_ca-icon");
    $("#first_step h2").addClass("ca-menu_hover_ca-main");
    $("#first_step span").html(".");

    $("#second_step").addClass("ca-menu_hover");
    $("#second_step span").addClass("ca-menu_hover_ca-icon");
    $("#second_step h2").addClass("ca-menu_hover_ca-main");
    $("#second_step span").html(".");

    $("#third_step").addClass("ca-menu_hover");
    $("#third_step span").addClass("ca-menu_hover_ca-icon");
    $("#third_step h2").addClass("ca-menu_hover_ca-main");
    $("#third_step span").html(".");
//=========================================================分类选择=========================================//
    $("#category_selected").click(function(){
        if($(".category_base").css("display") =="none"){
            $(".category_base").css("display","inline-block");
            $(".cate_arrow").css("background","url(/application/frontend/views/resources/images/version/cate_arrow.png) no-repeat 0 -16px");
            $(".category_collect").addClass("category_collect_fixed");
        }else{
            $(".category_base").css("display","none");
            $(".cate_arrow").css("background","url(/application/frontend/views/resources/images/version/cate_arrow.png) no-repeat 0 0");
            $(".category_collect").removeClass("category_collect_fixed");
            step_two();
        }
    });
    //点击一级分类
    $("#cate_one p").click(function(){
        $("#cate_three").removeClass("display_block");
        $("#cate_three").addClass("display_none");
        $("#cate_four").removeClass("display_block");
        $("#cate_four").addClass("display_none");

        var wx_nature = $(this).attr("data-id");
        cate_one(wx_nature);
        $("#category_collect_value").html($(this).text());
        $("#cate_one p").removeClass("selected");
        $(this).addClass("selected");
        step_two();
        sysTag(wx_nature);
    });
    //点击二级分类
    $("#cate_two p").live('click',function(){
        $("#cate_four").removeClass("display_block");
        $("#cate_four").addClass("display_none");

        var wx_nature = $(this).attr("data-id");
        cate_two(wx_nature);

        var cate = $("#category_collect_value").text().split(">")
        if(cate.length==1){
            $("#category_collect_value").append(">"+$(this).text());
        }else{
            $("#category_collect_value").html(cate[0]+">"+$(this).text());
        }
        $("#cate_two p").removeClass("selected");
        $(this).addClass("selected");

        //如果不是考研公共和期末考试，清除地区分类
        if ($("#wx_category_nature").attr("value")=="50"||$("#wx_category_nature").attr("value")=="49"||$("#wx_category_nature").attr("value")=="48"||$("#wx_category_nature").attr("value")=="21"||$("#wx_category_nature").attr("value")!="4"||$("#wx_category_nature").attr("value")!="10") {
            $("#wx_category_area_school").attr("value","");
            $("#wx_category_area_major").attr("value","");
        }
        if(wx_nature=="4"||wx_nature=="11"){
            $("#cate_three p").removeClass("selected");
        }
        step_two();
        sysTag(wx_nature);
    });
    //点击三级分类
    $("#cate_three p").live('click',function(){
        var wx_nature = $(this).attr("data-id");
        var if_area = $(this).attr("filter");
        if(if_area == "c_school"){
            $("#wx_category_area_school").attr("value",wx_nature);
        }else{
            $("#wx_category_nature").attr("value",wx_nature);
            $(".category_base").css("display","none");
            step_two();
            sysTag(wx_nature);
        }
        var cate = $("#category_collect_value").text().split(">")
        if($(this).text() != "其他学校.."){
            if(cate.length==2){
            $("#category_collect_value").append(">"+$(this).text());
            }else{
                $("#category_collect_value").html(cate[0]+">"+cate[1]+">"+$(this).text());
            }
            $("#cate_three p").removeClass("selected");
            $(this).addClass("selected");
        }

        if (($("#wx_category_nature").attr("value")=="50"||$("#wx_category_nature").attr("value")=="49"||$("#wx_category_nature").attr("value")=="48"||$("#wx_category_nature").attr("value")=="21"||$("#wx_category_nature").attr("value")=="4"||$("#wx_category_nature").attr("value")=="10")&&$(this).text() != "其他学校.."){
            $("#cate_four").removeClass("display_none");
            $("#cate_four").addClass("display_block");
            $("#cate_four p").removeClass("selected");
            $("#wx_category_area_major").attr("value","");
        }

        var url ="<?php echo site_url('data/wxc_data/get_depart_by_school'); ?>";
            $.ajax({
                type:"post",
                data:({'wx_school': "<?php echo $base_user_info['user_school'];?>"}),
                url:url,
                dataType:"json",
                success: function(result)
                    {
                        var str="";
                        var i ;
                        for(i in result){
                            str +="<p data-id="+result[i]['carea_id']+">"+result[i]['carea_name']+"</p>"    ;
                            };
                        $("#cate_four").html(str);
                    },
                     error: function(XMLHttpRequest, textStatus, errorThrown) {
                                // alert(XMLHttpRequest.status);
                                // alert(XMLHttpRequest.readyState);
                                // alert(textStatus);
                            }
                });


    });
    //点击四级分类（院系）
    $("#cate_four p").live('click',function(){
        $("#wx_category_area_major").attr("value",$(this).attr("data-id"));
        $("#cate_four p").removeClass("selected");
        $(this).addClass("selected");

        var cate = $("#category_collect_value").text().split(">")
        if(cate.length==3){
            $("#category_collect_value").append(">"+$(this).text());
        }else{
            $("#category_collect_value").html(cate[0]+">"+cate[1]+">"+cate[2]+">"+$(this).text());
        }
        $(".category_base").css("display","none");
        step_two();
    });


    $("#systag >span").live('click',function() {
        var str = "";
        var data_tag = "";
        str = "<span>"+$(this).text()+"<b>X</b></span>";
        var hasenter = false;
        var data_tag_list = $(".usertag").text().split("X");
        for (var i = 0; i < data_tag_list.length; i++) {
            if(data_tag_list[i].replace(/(^\s*)|(\s*$)/g, "") == $(this).text())
            hasenter = true;
        }
        if($(".usertag").children().length>=5){
            errorMes("您最多能输入五个标签");
        }else if(hasenter == true){
            errorMes("不能重复输入标签");
        }else{
            $(".usertag").append(str);
            data_tag_list = $(".usertag").text().split("X");
            for (var i = 0; i < data_tag_list.length; i++) {
                data_tag += data_tag_list[i].replace(/(^\s*)|(\s*$)/g, "") ;
            }
            if(data_tag.length >= 25){
                $(".tagform").css("height","61px");
            }
        }
        step_three();

    });

    $(".usertag  >span b").live('click',function() {
        var data_tag = "";
        $(this).parents('span').remove();
        var data_tag_list = $(".usertag").text().split("X");
        for (var i = 0; i < data_tag_list.length; i++) {
            data_tag += data_tag_list[i].replace(/(^\s*)|(\s*$)/g, "") ;
        }
        if(data_tag.length < 25){
            $(".tagform").css("height","31px");
        }
        step_three();
    });
    $(".tagform").click(function(){
        $("#tag").focus();
    });

});

//点击分类生成系统默认tag
function sysTag(cnature_id){
    var url = "<?php echo site_url('openapi/category/category_tag'); ?>";
    var params = ({'category_id':cnature_id});
    var retData;
    retData = ajax_common_json_get(url,params);
    var str = "推荐标签："
    for(i in retData){
        if(retData[0] == ""){
            str = "";
        }else{
            str += "<span>"+retData[i]+"</span>";
        }

    }
    $("#systag").html(str);
}
//=========================================================分类选择辅助函数=========================================//
function cate_one(wx_nature){
    var url ="<?php echo site_url('data/wxc_data/get_second_nature'); ?>";
          $("#wx_category_nature").attr("value",wx_nature);
            $.ajax({
                type:"post",
                data:({'wx_nature': wx_nature}),
                url:url,
                dataType:"json",
                success: function(result)
                    {
                        if(result!=""){
                            var str = "";
                            var i ;
                            for(i in result){
                                str +="<p data-id="+result[i]['cnature_id']+">"+result[i]['cnature_name']+"</p>"    ;
                                };
                            $("#cate_two").html(str);
                        }else{
                            $("#cate_two").html("");
                        }
                        $("#cate_two").removeClass("display_none");
                        $("#cate_two").addClass("display_block");
                    },
                     error: function(XMLHttpRequest, textStatus, errorThrown) {
                        // alert(XMLHttpRequest.status);
                        // alert(XMLHttpRequest.readyState);
                        // alert(textStatus);
                    }
                });
}

function cate_two(wx_nature){
    $("#wx_category_nature").attr("value",wx_nature);
          if(wx_nature==4||wx_nature==10||wx_nature==21||wx_nature==48||wx_nature==49||wx_nature==50){
             // alert(this.value)
             //pop();
                $("#cate_three p").removeClass("display_none");
                $("#cate_three").removeClass("display_none");
                $("#cate_three").addClass("display_block");
                $("#cate_three p").remove("p[filter!=c_school]");

                // $("#cate_four").removeClass("display_none");
                // $("#cate_four").addClass("display_block");
            }else if(wx_nature==46||wx_nature==11){
                $("#cate_three").append("");
                $("#cate_three").removeClass("display_block");
                $("#cate_three").addClass("display_none");
                $(".category_base").css("display","none");

            }else{
                  var url ="<?php echo site_url('data/wxc_data/get_third_nature'); ?>";
                    $.ajax({
                        type:"post",
                        data:({'wx_nature': wx_nature}),
                        url:url,
                        dataType:"json",
                        success: function(result)
                            {
                                if(result!=""){
                                    var str="";
                                    var i ;
                                    for(i in result){
                                        str +="<p data-id="+result[i]['cnature_id']+">"+result[i]['cnature_name']+"</p>"    ;
                                        };
                                    $("#cate_three p").addClass("display_none");
                                    $("#cate_three p").removeClass("selected");
                                    $("#cate_four p").removeClass("selected");
                                    $("#cate_three").append(str);
                                }else{
                                    $("#cate_three").append("");
                                }
                                $("#cate_three").removeClass("display_none");
                                $("#cate_three").addClass("display_block");
                            },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                                // alert(XMLHttpRequest.status);
                                // alert(XMLHttpRequest.readyState);
                                // alert(textStatus);
                            }
                        });
            }
}

//=========================================================上传步骤=========================================//
//上传第二步
function step_two(){
    var c_nature = $("#wx_category_nature").attr("value");
    // var d_keyword = $("#datakeyword").attr("value");
    if(c_nature!=""&&c_nature!=null){
        $("#second_step").addClass("ca-menu_hover");
        $("#second_step span").addClass("ca-menu_hover_ca-icon");
        $("#second_step h2").addClass("ca-menu_hover_ca-main");
        $("#second_step span").html(".");
        step_two_success = 1;
    }else{
        $("#second_step").removeClass("ca-menu_hover");
        $("#second_step span").removeClass("ca-menu_hover_ca-icon");
        $("#second_step h2").removeClass("ca-menu_hover_ca-main");
        $("#second_step span").html("'");
        step_two_success = 0;
    }
    check_upload();
}

//上传第三步
function step_three(){
    // var d_summary = $("#datasummary").attr("value");
    var tag = $(".usertag").text().split("X");
    var d_name = $("#dataname").val();
    if(tag.length>1&&d_name!=""){
        $("#third_step").addClass("ca-menu_hover");
        $("#third_step span").addClass("ca-menu_hover_ca-icon");
        $("#third_step h2").addClass("ca-menu_hover_ca-main");
        $("#third_step span").html(".");
        step_three_success = 1;
    }else{
        $("#third_step").removeClass("ca-menu_hover");
        $("#third_step span").removeClass("ca-menu_hover_ca-icon");
        $("#third_step h2").removeClass("ca-menu_hover_ca-main");
        $("#third_step span").html("'");
        step_three_success = 0;
    }
    check_upload();
}
//=========================================================判断上传按钮是否可用=========================================//
function check_upload(){
    if(step_one_success == 1&&step_two_success == 1&&step_three_success == 1){
        $("#filecontent").attr("disabled",false);
        $("#filecontent").css("cursor","pointer");
    }else{
        $("#filecontent").attr("disabled",true);
        $("#filecontent").css("cursor","not-allowed");
    }
}
//=========================================================键盘敲击事件=========================================//
$(document).keydown(function(event){
    var key = event.keyCode;
    if(key!=""&&key!=null){
        step_two();
        step_three();
        check_upload();
    }
});

//=========================================================大学选择=========================================//
//弹出窗口
function pop(){
	//将窗口居中
	makeCenter();

	//初始化省份列表
	initProvince();

	//默认情况下, 给第一个省份添加choosen样式
	$('[province-id="1"]').addClass('choosen');

	//初始化大学列表
	initSchool(1);
}
//隐藏窗口
function hide()
{
	$('#choose-box-wrapper').css("display","none");
}

function initProvince()
{
	//原先的省份列表清空
	$('#choose-a-province').html('');
	for(i=0;i<schoolList.length;i++)
	{
		$('#choose-a-province').append('<a class="province-item" province-id="'+schoolList[i].id+'">'+schoolList[i].name+'</a>');
	}
	//添加省份列表项的click事件
	$('.province-item').bind('click', function(){
			var item=$(this);
			var province = item.attr('province-id');
			var choosenItem = item.parent().find('.choosen');
			if(choosenItem)
				$(choosenItem).removeClass('choosen');
			item.addClass('choosen');
			//更新大学列表
			initSchool(province);
		}
	);
}

function initSchool(provinceID)
{
    //原先的学校列表清空
    $('#choose-a-school').html('');
    var schools = schoolList[provinceID-1].school;
    for(i=0;i<schools.length;i++)
    {
        $('#choose-a-school').append('<a class="school-item" school-id="'+schools[i].id+'">'+schools[i].name+'</a>');
    }
    //添加大学列表项的click事件
    $('.school-item').bind('click', function(){
            var item=$(this);
            //var school = item.attr('school-id');
            //更新选择大学文本框中的值
            //$('#school-name').val(item.text());

            $("#cate_three p[fi=c_school]").html(item.text());
            $("#cate_three p[fi=c_school]").attr("data-id",item.text());
            $("#cate_three p[fi=c_school]").addClass("selected");
            $("#cate_three p[fi!=c_school]").removeClass("selected");
            $("#wx_category_area_school").attr("value",item.text());
            $("#category_collect_value").html($("#category_collect_value").text().split(">")[0]+">"+$("#category_collect_value").text().split(">")[1]+">"+item.text())
//=========================================================初始化院系=========================================//
            var url ="<?php echo site_url('data/wxc_data/get_depart_by_school'); ?>";
            $.ajax({
                type:"post",
                data:({'wx_school': item.text()}),
                url:url,
                dataType:"json",
                success: function(result)
                    {
                        var str="";
                        var i ;
                        for(i in result){
                            str +="<p data-id="+result[i]['carea_id']+">"+result[i]['carea_name']+"</p>"    ;
                            };
                        $("#cate_four").html(str);

                        $("#cate_four").removeClass("display_none");
                        $("#cate_four").addClass("display_block");
                        $("#cate_four p").removeClass("selected");
                        $("#wx_category_area_major").attr("value","");
                    },
                     error: function(XMLHttpRequest, textStatus, errorThrown) {
                                // alert(XMLHttpRequest.status);
                                // alert(XMLHttpRequest.readyState);
                                // alert(textStatus);
                            }
                });

            //关闭弹窗
            hide();
        }
    );
}

function makeCenter()
{
	$('#choose-box-wrapper').css("display","block");
	$('#choose-box-wrapper').css("position","absolute");
	$('#choose-box-wrapper').css("top", Math.max(0, (($(window).height() - $('#choose-box-wrapper').outerHeight()) / 2) + $(window).scrollTop()) + "px");
	$('#choose-box-wrapper').css("left", Math.max(0, (($(window).width() - $('#choose-box-wrapper').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
}

$(function(){
	$("#filecontent").click(function(){
    	var data_status = "<?php echo $data_info['data_base']['data_status'];?>";
    	var data_preview;
        // if($("#datastatus").attr("checked")){
        // 	 data_status= '1';
        //     }else{
        //      data_status= '1';
        //     }
        if($("#datapreview").attr("checked")){
        		data_preview= '1';
              }else{
           		 data_preview= '1';
              }
        var data_price = '0.00';
        var data_price_temp= $("#select_price").attr("value");
        if(data_price_temp=="免费"){
            data_price = '0.00';
        }else{
            data_price = data_price_temp.replace("￥","");
        }
    	var data_name=$("#dataname").attr("value");
       // var data_type=$("#datatype").attr("value");
        // var data_summary=$("#datasummary").attr("value");
        //var data_price=$("#password").attr("value");
        // var data_keyword=$("#datakeyword").attr("value");
        // var data_price='0';
        var data_id=$("#dataid").attr("value");
        var data_objectname=$("#dataobjectname").attr("value");
        var wx_category_area_school = $("#wx_category_area_school").attr("value");
        var wx_category_area_major = $("#wx_category_area_major").attr("value");
        var wx_category_nature = $("#wx_category_nature").attr("value");
        var data_tag = "";
        var data_tag_list = $(".usertag").text().split("X");
        for (var i = 0; i < data_tag_list.length; i++) {
            if(data_tag_list.length-i>2){
                data_tag += data_tag_list[i].replace(/(^\s*)|(\s*$)/g, "") + ",";
            }else{
                data_tag += data_tag_list[i].replace(/(^\s*)|(\s*$)/g, "");
            }
        }
        var url ='<?php echo site_url('data/wxc_data/complete_data_info'); ?>';
        $.ajax({
        type:"post",
        url:url,
        data:({'data_name':data_name,
        	'data_status':data_status,
        	// 'data_summary':data_summary,
        	'data_objectname':data_objectname,
        	'data_id':data_id,
        	'data_price':data_price,
        	// 'data_keyword':data_keyword,
        	'data_preview':data_preview,
        	'data_category_nature':wx_category_nature,
        	'data_category_area_school':wx_category_area_school,
            'data_category_area_major':wx_category_area_major,
            'data_tag':data_tag
            }),
        success: function(result)
            {
              if(result=='success'){
               // location.reload();
            	  location.href='<?php echo site_url('home/personal'); ?>';
                }

            },
           error: function(XMLHttpRequest, textStatus, errorThrown) {
                        // alert(XMLHttpRequest.status);
                        // alert(XMLHttpRequest.readyState);
                        // alert(textStatus);
                    }
        });
  });

	//弹出消息
	   $('#showpdf').click(function(){
	       $('#overlay').fadeIn('fast',function(){
	           $('#box').animate({'top':'140px'},500);
	       });
	   });
	   $('#boxclose').click(function(){
	       $('#box').animate({'top':'-900px'},500,function(){
	           $('#overlay').fadeOut('fast');
	       });
	   });

});

var enter = function(obj,e) {
    var key = window.event ? e.keyCode : e.which;
    var data_tag_list = $(".usertag").text().split("X");
    var data_tag = "";

    if (key == 13) {
        var str = "";
        var hasenter = false;
        for (var i = 0; i < data_tag_list.length; i++) {
            if(data_tag_list[i].replace(/(^\s*)|(\s*$)/g, "") == $(obj).val())
            hasenter = true;
        }
        str = "<span>"+$(obj).val()+"<b>X</b></span>";
         if($(".usertag").children().length>=5){
            errorMes("您最多能输入五个标签");
        }else if(hasenter == true){
            errorMes("不能重复输入标签");
        }else{
            $(".usertag").append(str);
            $(obj).val("");
        }
        $(obj).css("width","80px");
        data_tag_list = $(".usertag").text().split("X");
        for (var i = 0; i < data_tag_list.length; i++) {
            data_tag += data_tag_list[i].replace(/(^\s*)|(\s*$)/g, "") ;
        }
        if(data_tag.length >= 25){
            $(".tagform").css("height","61px");
        }
    }
    if(key == 8){//删除
        if($(obj).val() == "")
        $(".usertag span:last-child").remove();
        data_tag_list = $(".usertag").text().split("X");
        for (var i = 0; i < data_tag_list.length; i++) {
            data_tag += data_tag_list[i].replace(/(^\s*)|(\s*$)/g, "") ;
        }
        if(data_tag.length < 25){
            $(".tagform").css("height","31px");
        }
    }

    if($(obj).val().length>5&&$(obj).css("width").replace(/[^0-9]/ig,"")<=125){
        var overlength = $(obj).val().length - 5 ;
        var width = 80 + 15 * overlength;
        $(obj).css("width",width+"px")
    }
    step_three();

    return false;
}

$(function(){
    //=========================================================自动填充=========================================//
    var url = "<?php echo site_url('openapi/category/fetch_tag'); ?>";
    var params = "";
    var retData;
    params = ({});
    retData = ajax_common_json_get(url,params);
    $("#tag").focus().autocomplete(retData);
});
</script>

</head>
<body>
	<?php include  'application/frontend/views/share/header.php';?>

    <?php $nav_param = "home";?>
    <?php include  'application/frontend/views/share/navigation.php';?>
    <!-- end #header -->
<div class="backcolor_body">
	<div class="body _body" style="min-height: 860px;">
    	<div id="_content" class="_content">

        <div class="post" style="padding: 0 20px;width:781px;">
            <h2 class="_data_title _nomargin" id="info_title">
                <div class="_grgh">在下面完成修改工作</div>
            </h2>
    		<div class="entry" style="">
                <input type="hidden" value="1215154" name="tmpdir" id="id_file">

     		     <div  id="thisform" >
                    <fieldset style="padding-bottom: 10px;">
                        <le>笔记名称 </le>
                        <a style="font-weight: bold;" href="<?php echo site_url('data/wxc_data/data_view/').'/'.$data_info['data_base']['data_id'];?>" target="_blank"><?php echo $data_info['data_base']['data_name'];?></a>

                    </fieldset>
                </div>
     		</div>

    		<div class="entry">
             	<div  id="thisform1" >
                    <fieldset>
                        <le>第二步：填写笔记类型 </le>
                        <p><label for="" accesskey="9">资料分类</label><br />

                        <!--   start of category      -->
                        <div class="category_selected" id="category_selected">
                            <div class="category_collect">
                                <span id="category_collect_value"><?php echo $data_info['data_nature']['one']['nature_name'];?>>
                                    <?php echo $data_info['data_nature']['two']['nature_name'];?>>
                                    <?php if($data_info['data_nature']['three']['nature_id']!=0){?>
                                    <?php echo $data_info['data_nature']['three']['nature_name'];?>
                                    <?php }else{?>
                                    <?php echo $data_info['data_area']['school']['area_name'];
                                        }
                                        if($data_info['data_area']['major']['area_name']){
                                            echo ">".$data_info['data_area']['major']['area_name'];
                                        }
                                        ?>

                                </span>
                                <span class="cate_arrow"></span>
                            </div>
                        </div>
                        <div class="category_base">
                            <ul>
                                <li id="cate_one">
                                    <p data-id="1">考研资料</p>
                                    <p data-id="2">考试</p>
                                    <p data-id="3">学习笔记</p>
                                </li>
                                <li id="cate_two" class="display_none">
                                    <p>学习笔记</p>
                                    <p class="selected">考研资料</p>
                                    <p>考试资料</p>
                                </li>
                                <li id="cate_three" class="display_none">
                                    <?php
                                        echo "<p disabled fi='c_school' filter='c_school' data-id=".$base_user_info['user_school'].">" .$base_user_info['user_school']."</p>";
                                    ?>
                                    <p filter="c_school" onclick="pop()">其他学校..</p>
                                </li>
                                <li id="cate_four" class="display_none">
                                    <?php
                                        echo "<p id='c_major' data-id=".$base_user_info['user_major_id'].">" .$base_user_info['user_major']."</p>";
                                    ?>
                                </li>
                            </ul>
                        </div>
                        <!--   end of category       -->
                        </p>
                        <p style="display:none;">
                        <select id="data_type" name="data_type" style='width: 150px;'>
                        <option value="<?php echo $data_info['data_nature']['one']['nature_id'];?>" ><?php echo $data_info['data_nature']['one']['nature_name'];?></option>
                        <option value="1" >考研资料</option>
                        <option value="2">考试</option>
                        <option value="3">学习笔记</option>
                        </select>
                        </p>
                        <p id="naturetwo" style="display:none;">
            			<select id="data_type2" style='width: 150px;'>
            			<option value="<?php echo $data_info['data_nature']['two']['nature_id'];?>" ><?php echo $data_info['data_nature']['two']['nature_name'];?></option>
            			</select>
                        </p>
                        <p id="naturethree" style="display:none;">
                         <select style='width: 150px;' id='data_type3'>
                         <?php if($data_info['data_nature']['three']['nature_id']!=0){?>
                         <option value="<?php echo $data_info['data_nature']['three']['nature_id'];?>" ><?php echo $data_info['data_nature']['three']['nature_name'];?></option>
                         <?php }else{?>
                         <option value="<?php echo $data_info['data_area']['school']['area_id'];?>" ><?php echo $data_info['data_area']['school']['area_name'];?></option>
                         <?php }?>
                         </select>
                        </p>
                        <p id="naturefour" style="display:none;">
                         <select style='width: 150px;' id='data_type4'>
                         <option value="<?php echo $data_info['data_area']['major']['area_id'];?>" ><?php echo $data_info['data_area']['major']['area_name'];?></option>
                         </select>
                        </p>
                        <?php if($data_info['data_nature']['three']['nature_id']!=0){?>
                          <input type="hidden" id="wx_category_nature" value="<?php echo $data_info['data_nature']['three']['nature_id'];?>">
                          <input type="hidden" id="wx_category_area" value="">
                         <?php }elseif($data_info['data_area']['major']['area_id']){?>
                          <input type="hidden" id="wx_category_nature" value="<?php echo $data_info['data_nature']['two']['nature_id'];?>">
                          <input type="hidden" id="wx_category_area_major" value="<?php echo $data_info['data_area']['major']['area_id'];?>">
                          <input type="hidden" id="wx_category_area_school" value="<?php echo $data_info['data_area']['major']['area_id'];?>">
                         <?php }else{?>
                          <input type="hidden" id="wx_category_nature" value="<?php echo $data_info['data_nature']['two']['nature_id'];?>">
                          <input type="hidden" id="wx_category_area_major" value="<?php echo $data_info['data_area']['major']['area_id'];?>">
                          <input type="hidden" id="wx_category_area_school" value="<?php echo $data_info['data_area']['major']['area_id'];?>">
                         <?php }?>
                         <?php if($data_info['data_area']['major']['area_id']){?>
                         <!-- <p >
            				<select id="partment" style='width: 150px;'>
            					<option value="<?php echo $data_info['data_area']['major']['area_id'];?>" ><?php echo $data_info['data_area']['major']['area_name'];?></option>
            				</select>
                        </p> -->
                         <?php }?>

                            <div id="choose-box-wrapper">
                        	  <div id="choose-box">
                        		<div id="choose-box-title">
                        			<span>选择学校</span>
                        		</div>
                        		<div id="choose-a-province">
                        		</div>
                        		<div id="choose-a-school">
                        		</div>
                        		<div id="choose-box-bottom">
                        			<input type="button" onclick="hide()" value="关闭" />
                        		</div>
                        	  </div>
                        	</div>
                            <!-- <p><label for="name" accesskey="9">关键词</label><br /> -->
                            
                    </fieldset>
                </div>
    		</div>

    		<div class="entry">
        	   <div  id="thisform2" >
                    <fieldset>
                        <le>第三步：添加笔记描述 </le>
                        <p><label  accesskey="9">标题</label><br />
                        <input type="text" id="dataname" name="dataname" onblur="step_three()" value="<?php echo $data_info['data_base']['data_name'];?>"></p>
                        <p><label for="data_price" accesskey="9">价格</label><br />
                            <select id='select_price' name='select_price' style='width: 445px;' class="chosen">
                            <?php if($data_info['data_base']['data_price']=='0.00'){?>
                            <option selected>免费</option>
                            <?php }else{?>
                            <option>免费</option>
                            <?php }?>
                            <?php if($data_info['data_base']['data_price']=='0.99'){?>
                            <option selected>￥0.99</option>
                            <?php }else{?>
                            <option>￥0.99</option>
                            <?php }?>
                            <?php if($data_info['data_base']['data_price']=='1.99'){?>
                            <option selected>￥1.99</option>
                            <?php }else{?>
                            <option>￥1.99</option>
                            <?php }?>
                            <?php if($data_info['data_base']['data_price']=='2.99'){?>
                            <option selected>￥2.99</option>
                            <?php }else{?>
                            <option>￥2.99</option>
                            <?php }?>
                            <?php if($data_info['data_base']['data_price']=='3.99'){?>
                            <option selected>￥3.99</option>
                            <?php }else{?>
                            <option>￥3.99</option>
                            <?php }?>
                            <?php if($data_info['data_base']['data_price']=='4.99'){?>
                            <option selected>￥4.99</option>
                            <?php }else{?>
                            <option>￥4.99</option>
                            <?php }?>
                            <?php if($data_info['data_base']['data_price']=='5.99'){?>
                            <option selected>￥5.99</option>
                            <?php }else{?>
                            <option>￥5.99</option>
                            <?php }?>
                            <?php if($data_info['data_base']['data_price']=='6.99'){?>
                            <option selected>￥6.99</option>
                            <?php }else{?>
                            <option>￥6.99</option>
                            <?php }?>
                            <?php if($data_info['data_base']['data_price']=='7.99'){?>
                            <option selected>￥7.99</option>
                            <?php }else{?>
                            <option>￥7.99</option>
                            <?php }?>
                            <?php if($data_info['data_base']['data_price']=='8.99'){?>
                            <option selected>￥8.99</option>
                            <?php }else{?>
                            <option>￥8.99</option>
                            <?php }?>
                            <?php if($data_info['data_base']['data_price']=='9.99'){?>
                            <option selected>￥9.99</option>
                            <?php }else{?>
                            <option>￥9.99</option>
                            <?php }?>


                        </select></p>
                         <p><label accesskey="9">标签</label><br />
                            
                            <div class="tagform">
                                <div id="tag_input">
                                    <div class="usertag">
                                        <!-- <span>笔记笔记笔记笔记<b>X</b></span>
                                        <span>笔记笔记笔记笔记<b>X</b></span>
                                        <span>笔记笔记笔记笔记<b>X</b></span>
                                        <span>笔记笔记笔记笔记<b>X</b></span>
                                        <span>笔记笔记笔记笔记<b>X</b></span> -->
                                        <?php 
                                            $data_tags = split(",", $data_info['data_base']['data_tag']); 
                                            for($i=0;$i<count($data_tags);$i++){
                                                echo "<span>".$data_tags[$i]."<b>X</b></span>";
                                            }
                                         ?>
                                    </div>
                                    <input type="text" id="tag" class="taginput fl" name="" maxlength="8" onkeydown="enter(this,event)" onblur="step_three()" autocomplete="off" style="width:80px;">
                                </div>
                                <div id="enter"></div>
                            </div>


                        </p>

                        <input type="hidden" name="dataid" id ="dataid" value="<?php echo $data_info['data_base']['data_id'];?>">
                        <input type="hidden" name="dataobjectname" id ="dataobjectname" value="<?php echo $data_info['data_base']['data_objectname'];?>">
                        <div id="systag" class="mb12 systag mt12">
                            <!-- 推荐标签：
                            <span>笔记</span>
                            <span>内部资料</span> -->
                        </div>
                    </fieldset>
                </div>
    		</div>


        <input type="button" name="filecontent" id="filecontent" value="提交修改" onclick="" style="height:32px;width:100px;" class="button_c">

    	</div>
    </div>

    <div id="_sidebar" class="_sidebar_step" >
          <h1 class="">步骤提示</h1>
            <ul class="ca-menu">
                    <li class="" id="first_step">
                        <span class="ca-icon">'</span>
                        <div class="ca-content" >
                            <h2 class="ca-main">第一步</h2>
                        </div>
                    </li>
                    <li id="second_step">
                        <span class="ca-icon">'</span>
                        <div class="ca-content">
                            <h2 class="ca-main">第二步</h2>
                        </div>
                    </li>
                    <li id="third_step">
                        <span class="ca-icon" id="heart">'</span>
                        <div class="ca-content">
                            <h2 class="ca-main">第三步</h2>
                        </div>
                    </li>
            </ul>
    </div>
	</div>
	<!-- end #body -->
    <?php include  'application/frontend/views/share/footer.php';?>
    <!-- end #footer -->
</div>
</body>

</html>
