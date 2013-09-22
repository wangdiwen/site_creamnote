<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Creamnote(图片笔记)</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_home.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/uploadify.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/style.css" />
    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery-1.8.3.js"></script>

   	<script type="text/javascript" src="/application/frontend/views/resources/js/school.js"></script>
   	<script type="text/javascript" src="/application/frontend/views/resources/js/jquery-ui-1.10.3.custom.js"></script>
    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery.blockUI.js"></script>
<script type="text/javascript">
<!-- Javascript functions -->
var img_id_upload=new Array();//初始化数组，存储已经上传的图片名
var i=0;//初始化数组下标
var step_one_success = 0;
var step_two_success = 0;
var step_three_success = 0;
$(function() {

    $("#filecontent").click(function(){
    //	alert("1");
        showLoading("文档正在努力生成当中,请稍等。。。");
        var order = $("#newid").attr("value");
        var pdfname = $("#pdfname").attr("value");
        var pdfuser = $("#pdfuser").attr("value");
        var pdfheader = $("#pdfheader").attr("value");
        var pdfschool = $("#pdfschool").attr("value");
        var pdfsummary = $("#pdfsummary").attr("value");
        var url ='<?php echo site_url('data/wxc_image/submit'); ?>';
        $.ajax({
        type:"post",
        url:url,
        data:({'order':order,
        	'pdf_name':pdfname,
        	'pdf_user':pdfuser,
        	'pdf_header':pdfheader,
        	'pdf_school':pdfschool,
        	'pdf_summary':pdfsummary
            }),
        success: function(result)
            {
              var temp_info = result.split(",");
              result = temp_info[0];
              var objectname = temp_info[1];
              if(result=='success'){
                //location.reload();
            		location.href="<?php echo site_url('data/wxc_data/data_modify_from_image'); ?>"+"/"+objectname;
                } else if(result=='warning'){
                	warnMes("部分图片有异常，已经忽略该图片");
                  // showLoading("文档正在努力生成当中,请稍等。。。");
                	location.href="<?php echo site_url('data/wxc_data/data_modify_from_image'); ?>"+"/"+objectname;
                } else if(result=='no image'){
					        errorMes("请上传图片");
                }

            },
           error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.readyState);
                        alert(textStatus);
                    }
        });
  });

//拖动图片
	$(".m_title").bind('mouseover',function(){
		$(this).css("cursor","move")
	});

    //var $show = $("#loader"); //进度条
    //var $orderlist = $("#orderlist");
	var $list = $("#module_list");
	//var url ='<?php echo site_url('data/wxc_image/upload_image'); ?>';
	$list.sortable({
		opacity: 0.6,
		revert: true,
		cursor: 'move',
		handle: '.m_title',
		update: function(){
			 var new_order = [];
             $list.children(".modules").each(function() {
                new_order.push(this.title);
             });
			 var newid = new_order.join(',');
			// var oldid = $orderlist.val();
			if(newid != $("#oldid").attr("value")){
				$("#newid").attr("value",newid);//如果更新插入新值
			}else{
				$("#newid").attr("value","");//如果和原值一样，则插入空
			}
		}
	});
});
$(document).ready(function(){
	//初始化页面请求json
	var url ='<?php echo site_url('data/wxc_image/get_json_data'); ?>';
	 $.ajax({
	        type:"post",
	        url:url,
	        dataType:"json",
	        success: function(result)
	            {
	              if(result !=''||result!=null){

	            	  var jsondata = result;
	                  var str = "";
	                  var i;
                      var close;
	                  for(i in jsondata){
                        var width ;
                        var height ;
                        if(jsondata[i]['width']<=550){
                            width = jsondata[i]['width']+"px";
                            height = jsondata[i]['height']+"px";
                            close = (jsondata[i]['width']-10)+"px";
                        }else{
                            width = 550+"px";
                            height = 550*(jsondata[i]['height']/jsondata[i]['width'])+"px";
                            close = 540+"px";
                        }

	                      var base_url = "<?php echo base_url(); ?>";
	                      str +="<div class='modules' title="+jsondata[i]['id']+">";
	                      str +=" <h3 class='m_title'>"+jsondata[i]['image'].split("/")[3]+"</h3>";
	                      str +="<a href='#' onclick='delete_image("+jsondata[i]['id']+")'><img src="+base_url+"application/frontend/views/resources/images/close.png"+"></a>";

	      				  str +="<p><img onclick='original_image("+i+")' src=" + base_url + jsondata[i]['thumb_image'] + "></img></p>";
                          str +="<div class='display_none' id="+i+"><p><img style='width:"+width+" ;height:"+height+"' src=" + base_url + jsondata[i]['image'] + "></img></p>";
                          str +="<div class='fancy_close' onclick='unblock()' style='left:"+close+";'></div>";
                          str += "<div class='image_title' style='width:"+width+"'>";
                          str +="<table cellspacing='0' cellpadding='0' border='0'><tbody><tr><td id='fancy_title_left'></td><td id='fancy_title_main'>";
                          str +="<div>"+jsondata[i]['image'].split("/")[3]+"</div></td><td id='fancy_title_right'></td></tr></tbody></table></div></div>";
                          str += "</div>";
	      				//alert(jsondata[i]['thumb_image']);
	                 	 	}
                        if(str!=""){
                            $("#module_list").html(str);
                            $("#first_step").addClass("ca-menu_hover");
                            $("#first_step span").addClass("ca-menu_hover_ca-icon");
                            $("#first_step h2").addClass("ca-menu_hover_ca-main");
                            $("#first_step span").html(".");
                            step_one_success = 1;
                            //第二步
                            $("#second_step").addClass("ca-menu_hover");
                            $("#second_step span").addClass("ca-menu_hover_ca-icon");
                            $("#second_step h2").addClass("ca-menu_hover_ca-main");
                            $("#second_step span").html(".");
                            step_two_success = 1;
                            check_upload();
                        }else{
                            $("#module_list").html("<div style='padding-top:10px;padding-bottom:10px;color: #AA7700;'>上传后的图片根据需要排列顺序，以便按序完成资料的生成！</div>");
                        }
                        $("#module_list").css("display","block");
                        var num1 = Math.round(i);
                        var num2 = Math.round(5);
                        var result = num1/num2;
                        var five = Math.floor(result);
                        var height = (five+1)*162+860+"px";
                        $(".body").css("min-height",height);
                        $(".body").css("overflow","hidden");
						        //得到图片顺序id
		                 var $list = $("#module_list");
		                 var old_order = [];
		                 $list.children(".modules").each(function() {
		                      old_order.push(this.title);
		                   });
		       			 var oldid = old_order.join(',');
		       			// var oldid = $orderlist.val();
		       			 $("#oldid").attr("value",oldid);
	                }
	            },
	           error: function(XMLHttpRequest, textStatus, errorThrown) {
	                        alert(XMLHttpRequest.status);
	                        alert(XMLHttpRequest.readyState);
	                        alert(textStatus);
	                    }
	        });
});

function delete_image(id){
	var url ='<?php echo site_url('data/wxc_image/delete_image'); ?>';
	 $.ajax({
	        type:"post",
	        url:url,
	        dataType:"json",
	        data:({'image_id':id}),
	        success: function(result)
	            {
	              if(result !=''||result!=null){
	            	  var jsondata = result;
                      var str = "";
                      var i;
                      var close;
                      for(i in jsondata){
                        var width ;
                        var height ;
                        if(jsondata[i]['width']<=550){
                            width = jsondata[i]['width']+"px";
                            height = jsondata[i]['height']+"px";
                            close = (jsondata[i]['width']-10)+"px";
                        }else{
                            width = 550+"px";
                            height = 550*(jsondata[i]['height']/jsondata[i]['width'])+"px";
                            close = 540+"px";
                        }

                          var base_url = "<?php echo base_url(); ?>";
                          str +="<div class='modules' title="+jsondata[i]['id']+">";
                          str +=" <h3 class='m_title'>"+jsondata[i]['image'].split("/")[3]+"</h3>";
                          str +="<a href='#' onclick='delete_image("+jsondata[i]['id']+")'><img src="+base_url+"application/frontend/views/resources/images/close.png"+"></a>";

                          str +="<p><img onclick='original_image("+i+")' src=" + base_url + jsondata[i]['thumb_image'] + "></img></p>";
                          str +="<div class='display_none' id="+i+"><p><img style='width:"+width+" ;height:"+height+"' src=" + base_url + jsondata[i]['image'] + "></img></p>";
                          str +="<div class='fancy_close' onclick='unblock()' style='left:"+close+";'></div>";
                          str += "<div class='image_title' style='width:"+width+"'>";
                          str +="<table cellspacing='0' cellpadding='0' border='0'><tbody><tr><td id='fancy_title_left'></td><td id='fancy_title_main'>";
                          str +="<div>"+jsondata[i]['image'].split("/")[3]+"</div></td><td id='fancy_title_right'></td></tr></tbody></table></div></div>";
                          str += "</div>";
	      				       //alert(jsondata[i]['thumb_image']);
	                 	 	}
                        var num1 = Math.round(i);
                        var num2 = Math.round(5);
                        var result = num1/num2;
                        var five = Math.floor(result);
                        var height = (five+1)*162+860+"px";
                        $(".body").css("min-height",height);
                        $(".body").css("overflow","hidden");
    	                 	 $("#module_list").html(str);
                         if(str!=""){
                          $("#first_step").addClass("ca-menu_hover");
                          $("#first_step span").addClass("ca-menu_hover_ca-icon");
                          $("#first_step h2").addClass("ca-menu_hover_ca-main");
                          $("#first_step span").html(".");
                          step_one_success = 1;
                          //第二步
                          $("#second_step").addClass("ca-menu_hover");
                          $("#second_step span").addClass("ca-menu_hover_ca-icon");
                          $("#second_step h2").addClass("ca-menu_hover_ca-main");
                          $("#second_step span").html(".");
                          step_two_success = 1;
                          check_upload();
                        }else{
                          $("#module_list").html("<div style='padding-top:10px;padding-bottom:10px;color: #AA7700;'>上传后的图片根据需要排列顺序，以便按序完成资料的生成！</div>");
                          $("#first_step").removeClass("ca-menu_hover");
                          $("#first_step span").removeClass("ca-menu_hover_ca-icon");
                          $("#first_step h2").removeClass("ca-menu_hover_ca-main");
                          $("#first_step span").html("'");
                          step_one_success = 0;
                          //第二步
                          $("#second_step").removeClass("ca-menu_hover");
                          $("#second_step span").removeClass("ca-menu_hover_ca-icon");
                          $("#second_step h2").removeClass("ca-menu_hover_ca-main");
                          $("#second_step span").html("'");
                          step_two_success = 0;
                          check_upload();
                        }
    						        //得到图片顺序id
    		                 var $list = $("#module_list");
    		                 var old_order = [];
    		                 $list.children(".modules").each(function() {
    		                      old_order.push(this.title);
    		                   });
		       			 var oldid = old_order.join(',');
		       			// var oldid = $orderlist.val();
		       			 $("#oldid").attr("value",oldid);
	                }
	            },
	           error: function(XMLHttpRequest, textStatus, errorThrown) {
                  alert(XMLHttpRequest.status);
                  alert(XMLHttpRequest.readyState);
                  alert(textStatus);
              }
	        });
}
//=========================================================弹出原始图片=========================================//
function original_image(id) {
        $.blockUI({
             message: $("#"+id),
             showOverlay: true,
             css: {
                 width: '0px',
                 height:'0px',
                 border:'1px none #09335F',
                 margin: '0 atuo',
                 top: '12%'
                },
            onOverlayClick: $.unblockUI
             });

       // setTimeout($.unblockUI, 2000);
}
function unblock(){
    $.unblockUI();
}

//=========================================================上传步骤=========================================//

//上传第三步
function step_three(){
    var d_user = $("#pdfuser").attr("value");
    var d_name = $("#pdfname").val();
    var d_school = $("#pdfschool").val();
    var d_summary = $("#pdfsummary").val();
    if(d_summary.length!=""&&d_name!=""&&d_user!=""&&d_school!=""){
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
        step_three();
        check_upload();
    }
});
</script>
</head>


<body class="activity_pane">
  <script  defer="defer">
  $("._body").css("overflow","hidden")
</script>
	<?php include  'application/frontend/views/share/header.php';?>

    <?php $nav_param = "upload_image";?>
    <?php include  'application/frontend/views/share/navigation.php';?>
    <!-- end #header -->
<div class="backcolor_body ">
    <div class="body _body" style="min-height: 890px;overflow:hidden;">
		<div id="_content" class="_content">

        <div class="post" style="padding: 0 20px ;width:781px;">
          <h2 class="_data_title _nomargin" id="info_title">
                <div class="_grgh">图片资料生成器</div>
            </h2>
    		<div class="entry">
                <input type="hidden" value="1215154" name="tmpdir" id="id_file">
         		<div  id="thisform" >
              <fieldset>
                <le>第一步：上传资料 </le>
                <div  style="margin-top: 12px;">

                  <div id="batch_upload" class="fl">
                    <span ><a style="color:#fff" href="javascript:$('#file_upload').uploadify('settings', 'formData', {'typeCode':document.getElementById('id_file').value});$('#file_upload').uploadify('upload','*')">批量上传</a></span>
                    <span ><a style="color:#fff" href="javascript:$('#file_upload').uploadify('cancel','*')">取消上传</a></span>
                  </div>
                  <input type="file" name="file_upload" id="file_upload" />
                </div>
                <p style="color: #AA7700;">
      				    支持的图片格式(*.jpg; *.jpeg; *.png; *.gif)
      				  </p>
      				<div id="uploadsuccess"></div>
              </fieldset>
            </div>
         	</div>

      		<div class="entry">
             	<div  id="thisform1" >
                    <fieldset>
                        <le>第二步：图片排序 </le>
                    	<div id="main">
                      		<div id="module_list" style="display:none;">
                      		<input type="hidden" id="orderlist" value="" />

                       		<div class="modules" title="1">
                         	 <h3 class="m_title">Module:1</h3>
                         	 <p><img src=""></p>
                      		 </div>
                      		 <div class="clear"></div>
                      		</div>
                      		<input type="hidden" id="newid" value="">
                      		<input type="hidden" id="oldid" value="">
                    	</div>
                    </fieldset>
                </div>
    		</div>

    		<div class="entry">
            	<div  id="thisform2" >
                    <fieldset>
                        <le>第三步：完善 PDF信息</le>
                        <p><label  accesskey="9">笔记题目</label><br />
                        <input type="text" id="pdfname" name="pdfname" onblur="step_three()"></p>
                        <p><label  accesskey="9">笔记作者</label><br />
                        <input type="text" id="pdfuser" name="pdfuser" value="<?php echo $base_user_info['user_name'];?>" onblur="step_three()"></p>
                        <p><label  accesskey="9">作者所在学校</label><br />
                        <input type="text" id="pdfschool" name="pdfschool" value="<?php echo $base_user_info['user_school'];?>" onblur="step_three()"></p>
                        <p><label  accesskey="9">笔记页眉</label><br />
                        <input type="text" id="pdfheader" name="pdfheader" onblur="step_three()" maxlength="20"></p>
                        <p><label for="name" accesskey="9">简介</label><br />
                        <textarea id="pdfsummary" name="" onblur="step_three()"></textarea></p>

                        <input type="hidden" name="dataid" id ="dataid">
                        <input type="hidden" name="dataobjectname" id ="dataobjectname">
                    </fieldset>
                </div>
    		</div>

            <input type="button" name="filecontent" id="filecontent" value="完成上传" onclick="" disabled style="cursor:not-allowed;height:32px;width:100px" class="button_c">

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
            <div class="help">
                <a href="">使用帮助</a>
            </div>
        </div>
        <div style="clear: both;">&nbsp;</div>
	</div><!-- end #content -->

	<!-- end #body -->
    <?php include  'application/frontend/views/share/footer.php';?>
    <!-- end #footer -->
</div>
<script type="text/javascript" src="/application/frontend/views/resources/js/jquery.uploadify.js"></script>
<script type="text/javascript">
$(function() {
  $('#file_upload').uploadify({
      'auto'     : false,//关闭自动上传
      'removeTimeout' : 1,//文件队列上传完成1秒后删除
      //'debug' : true,
        'swf'      : '/application/frontend/views/data/uploadify.swf',
        'uploader' : '<?php echo site_url('data/wxc_image/upload_image');?>',
        'formData': { 'PHPSESSID': '<?php echo session_id();?>'},
        'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
        'buttonText' : '选择图片',//设置按钮文本
        'multi'    : true,//允许同时上传多张图片
        'uploadLimit' : 30,//一次最多只允许上传10张图片
        'fileTypeDesc' : 'Image Files',//只允许上传图像
        'fileTypeExts' : '*.jpg; *.jpeg; *.png; *.gif',//限制允许上传的图片后缀
        'fileSizeLimit' : '2000KB',//限制上传的图片不得超过2MB
        'onSelect' : function(file) {
          $("#batch_upload").css("display","block");
        },
        'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
            if(data == "UNKNOWN"){
                errorMes("图片有异常，请重新上传新的图片");
             }else if(data == "image-size-overflow"){
                warnMes("一次图片笔记中，最大只支持30幅图片，如果您的图片笔记未完成，建议您可以分开制作、分享!");
             }else if(data == "image-count-overflow"){
                errorMes("您上传的图片超过最大限制2M!");
             } else{
              var jsondata = $.parseJSON(data);
                var str = "";
                var i;
                var str = "";
                var i;
                var close;
                var width ;
                var height ;
                for(i in jsondata){
                      if(i != 29){
                        if(jsondata[i]['width']<=550){
                          width = jsondata[i]['width']+"px";
                          height = jsondata[i]['height']+"px";
                          close = (jsondata[i]['width']-10)+"px";
                        }else{
                            width = 550+"px";
                            height = 550*(jsondata[i]['height']/jsondata[i]['width'])+"px";
                            close = 540+"px";
                        }

                        var base_url = "<?php echo base_url(); ?>";
                        str +="<div class='modules' title="+jsondata[i]['id']+">";
                        str +=" <h3 class='m_title'>"+jsondata[i]['image'].split("/")[3]+"</h3>";
                        str +="<a href='#' onclick='delete_image("+jsondata[i]['id']+")'><img src="+base_url+"application/frontend/views/resources/images/close.png"+"></a>";

                        str +="<p><img onclick='original_image("+i+")' src=" + base_url + jsondata[i]['thumb_image'] + "></img></p>";
                        str +="<div class='display_none' id="+i+"><p><img style='width:"+width+" ;height:"+height+"' src=" + base_url + jsondata[i]['image'] + "></img></p>";
                        str +="<div class='fancy_close' onclick='unblock()' style='left:"+close+";'></div>";
                        str += "<div class='image_title' style='width:"+width+"'>";
                        str +="<table cellspacing='0' cellpadding='0' border='0'><tbody><tr><td id='fancy_title_left'></td><td id='fancy_title_main'>";
                        str +="<div>"+jsondata[i]['image'].split("/")[3]+"</div></td><td id='fancy_title_right'></td></tr></tbody></table></div></div>";
                        str += "</div>";
                      }

                //alert(jsondata[i]['thumb_image']);
                    }
                    var num1 = Math.round(i);
                    if(num1 == 29){
                      warnMes("一次图片笔记中，最大只支持30幅图片，如果您的图片笔记未完成，建议您可以分开制作、分享")
                    }else{
                      var num2 = Math.round(5);
                      var result = num1/num2;
                      var five = Math.floor(result);
                      var height = (five+1)*162+860+"px";
                      $(".body").css("min-height",height);
                      $(".body").css("overflow","hidden");
                      $("#module_list").html(str);
                      $("#module_list").css("display","block");
                    }

             }

        },
        'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数
           // if(img_id_upload.length>0)
           // alert('成功上传的文件有：'+encodeURIComponent(img_id_upload));
           //第一步
           $("#first_step").addClass("ca-menu_hover");
            $("#first_step span").addClass("ca-menu_hover_ca-icon");
            $("#first_step h2").addClass("ca-menu_hover_ca-main");
            $("#first_step span").html(".");
            step_one_success = 1;
            //第二步
          $("#second_step").addClass("ca-menu_hover");
          $("#second_step span").addClass("ca-menu_hover_ca-icon");
          $("#second_step h2").addClass("ca-menu_hover_ca-main");
          $("#second_step span").html(".");
          step_two_success = 1;
          check_upload();
        }
        // Put your options here
    });
});
</script>
</body>

</html>




