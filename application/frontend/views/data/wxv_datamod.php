<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>完善资料(<?php echo $data_name;?>)</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_home.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/style.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/menu-css.css" />
     <link rel="stylesheet" href="/application/frontend/views/resources/css/chosen.css" />
    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="/application/frontend/views/resources/js/menu_min.js"></script>
    <script type="text/javascript" src="/application/frontend/views/resources/js/school.js"></script>
    <script type="text/javascript" src="/application/frontend/views/resources/js/chosen.jquery.js"></script>
    <script type="text/javascript" src="/application/frontend/views/resources/js/wx_common.js"></script>

<script type="text/javascript">
<!-- Javascript functions -->
var area_id;
var step_one_success = 1;
var step_two_success = 0;
var step_three_success = 0;
$(document).ready(function(){
     $("#select_price").chosen();

    $("#first_step").addClass("ca-menu_hover");
    $("#first_step span").addClass("ca-menu_hover_ca-icon");
    $("#first_step h2").addClass("ca-menu_hover_ca-main");
    $("#first_step span").html(".");

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
        if ($("#wx_category_nature").attr("value")!="4"||$("#wx_category_nature").attr("value")!="10") {
            $("#wx_category_area_school").attr("value","");
            $("#wx_category_area_major").attr("value","");
        };
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
        }
        var cate = $("#category_collect_value").text().split(">")
        if(cate.length==2){
            $("#category_collect_value").append(">"+$(this).text());
        }else{
            $("#category_collect_value").html(cate[0]+">"+cate[1]+">"+$(this).text());
        }
        $("#cate_three p").removeClass("selected");
        $(this).addClass("selected");

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
            $("#category_collect_value").html(cate[0]+">"+cate[1]+">"+cate[1]+">"+$(this).text());
        }
        $(".category_base").css("display","none");
        step_two();
    });
});

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
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.readyState);
                        alert(textStatus);
                    }
                });
}

function cate_two(wx_nature){
    $("#wx_category_nature").attr("value",wx_nature);
          if(wx_nature==4||wx_nature==10){
             // alert(this.value)
             //pop();
                $("#cate_three p").removeClass("display_none");
                $("#cate_three").removeClass("display_none");
                $("#cate_three").addClass("display_block");
                $("#cate_three p").remove("p[filter!=c_school]");

                $("#cate_four").removeClass("display_none");
                $("#cate_four").addClass("display_block");
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
                                alert(XMLHttpRequest.status);
                                alert(XMLHttpRequest.readyState);
                                alert(textStatus);
                            }
                        });
            }
}

//=========================================================上传步骤=========================================//
//上传第二步
function step_two(){
    var c_nature = $("#wx_category_nature").attr("value");
    var d_keyword = $("#datakeyword").attr("value");
    if(c_nature!=""&&c_nature!=null&&d_keyword!=""&&d_keyword!=null){
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
    var d_summary = $("#datasummary").attr("value");
    var d_name = $("#dataname").val();
    if(d_summary.length>20&&d_name!=""){
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
                    },
                     error: function(XMLHttpRequest, textStatus, errorThrown) {
                                alert(XMLHttpRequest.status);
                                alert(XMLHttpRequest.readyState);
                                alert(textStatus);
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
    	var data_status;
    	var data_preview;
        if($("#datastatus").attr("checked")){
        	 data_status= '1';
            }else{
             data_status= '1';
            }
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
        var data_summary=$("#datasummary").attr("value");
        //var data_price=$("#password").attr("value");
        var data_keyword=$("#datakeyword").attr("value");
        var data_id='<?php echo $data_id;?>';
        var data_objectname='<?php echo $data_objectname;?>';
        var wx_category_area_school = $("#wx_category_area_school").attr("value");
        var wx_category_area_major = $("#wx_category_area_major").attr("value");
        var wx_category_nature = $("#wx_category_nature").attr("value");
        alert(data_id);
        var url ='<?php echo site_url('data/wxc_data/upload_file_info'); ?>';
        $.ajax({
        type:"post",
        url:url,
        data:({'data_name':data_name,
        	'data_status':data_status,
        	'data_summary':data_summary,
        	'data_objectname':data_objectname,
        	'data_id':data_id,
        	'data_price':data_price,
        	'data_keyword':data_keyword,
        	'data_preview':data_preview,
        	'data_category_nature':wx_category_nature,
        	'data_category_area_school':wx_category_area_school,
            'data_category_area_major':wx_category_area_major
            }),
        success: function(result)
            {
              if(result=='success'){
               // location.reload();
            	  location.href='<?php echo site_url('home/personal'); ?>';
                }

            },
           error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.readyState);
                        alert(textStatus);
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
</script>

</head>
<body>
	<?php include  'application/frontend/views/share/header.php';?>

    <?php $nav_param = "home";?>
    <?php include  'application/frontend/views/share/navigation.php';?>

<div class="backcolor_body activity_pane">
	<div class="body _body" style="min-height: 900px;">

	<div id="_content" class="_content">

        <div class="post" style="padding: 0 20px;width:781px;">
            <h2 class="_data_title _nomargin" id="info_title">
                <div class="_grgh">完善后的资料才有价值哦！</div>
            </h2>
            <?php  if(!$pdf_file){ }
                    else{
               ?>
            <div class="entry">
                <input type="hidden" value="1215154" name="tmpdir" id="id_file">

                 <div  id="thisform" >
                    <fieldset>
                    <legend>资料预览 </legend>
                    <a href="#" id="showpdf">打开</a>
                    <!------ 弹出消息 ------>
                    <div class="overlay" id="overlay" style="display:none;"></div>
                    <div class="box" id="box"> <a class="boxclose" id="boxclose"></a>
                        <h1>PDF预览</h1>
                        <p id="messageshow"> <EMBED src="<?php  echo $pdf_file; ?>" height="450px" width="100%"/> </p>
                   </div>
                   <!------ 弹出消息 ------>
                    </fieldset>
                </div>
             </div>
            <?php   }
            ?>

		    <div class="entry">
             	<div  id="thisform1" >
                <fieldset>
                    <legend>第二步：填写资料类型 </legend>
                        <p style="margin-bottom: 0px;"><label for="" accesskey="9">资料分类</label><br /></p>
                        <!--   start of category      -->
                        <div class="category_selected" id="category_selected">
                            <div class="category_collect">
                                <span id="category_collect_value">分类选择...</span>
                                <span class="cate_arrow"></span>
                            </div>
                        </div>
                        <div class="category_base">
                            <ul>
                                <li id="cate_one">
                                    <?php
                                        foreach ($first_nature as $nature){
                                            echo "<p data-id=".$nature->cnature_id.">" .$nature->cnature_name."</p>";
                                        }
                                    ?>
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

                    <input type="hidden" id="wx_category_nature" value="">
                    <input type="hidden" id="wx_category_area_school" value="">
                    <input type="hidden" id="wx_category_area_major" value="">
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
                    <p><label for="name" accesskey="9">关键词</label><br />
                    <input type="text" id="datakeyword" name="name" value="" onblur="step_two()"></p>
                    <!-- <p><label for="" accesskey="9">是否公开:</label>
                    <input type="checkbox" id="datastatus" name="datastatus" ></p>
                     <p><label for="" accesskey="9">是否提供预览:</label>
                    <input type="checkbox" id="datapreview" name="datapreview" ></p> -->
                    </fieldset>
                </div>
		    </div>

		    <div class="entry">
            	 <div  id="thisform2" >
                    <fieldset>
                    <legend>第三步：添加资料描述 </legend>
                    <p><label  accesskey="9">标题</label><br />
                    <input type="text" id="dataname" name="dataname" value="<?php echo $data_name;?>" onblur="step_three()"></p>
                    <p><label for="data_price" accesskey="9">价格</label><br />
                    <select id='select_price' name='select_price' style='width: 445px;' class="chosen">
                    <option>免费</option>
                    <option>￥1.99</option>
                    <option>￥2.99</option>
                    <option>￥3.99</option>
                    <option>￥4.99</option>
                    <option>￥5.99</option>
                    <option>￥6.99</option>
                    <option>￥7.99</option>
                    <option>￥8.99</option>
                    <option>￥9.99</option>

                </select></p>
                    <p><label for="name" accesskey="9">简介</label><br />
                    <textarea id="datasummary" name="" onblur="step_three()"></textarea></p>

                    <input type="hidden" name="dataid" id ="dataid" value="">
                    <input type="hidden" name="dataobjectname" id ="dataobjectname">
                    <div id="" style="margin-bottom: 12px;color:#AA7700;">*建议您输入不少于20字的简介，好让你的资料更受关注</div>
                    </fieldset>
                </div>
		    </div>


        <input type="button" name="filecontent" id="filecontent" value="完成上传" onclick="" disabled style="cursor:not-allowed;height:32px;width:100px;" class="button_c">

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
