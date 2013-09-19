<?php header('Cache-control: private, must-revalidate');  //支持页面回跳?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>笔记展示</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_home.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/style.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/menu-css.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/chosen.css" />
    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="/application/frontend/views/resources/js/wx_load.js"></script>

    <script type="text/javascript" src="/application/frontend/views/resources/js/menu_min.js"></script>
    <script type="text/javascript" src="/application/frontend/views/resources/js/school.js"></script>
    <script type="text/javascript" src="/application/frontend/views/resources/js/chosen.jquery.js"></script>
    <script type='text/javascript' src='/application/frontend/views/resources/js/jquery.poshytip.js'></script>

<script type="text/javascript">
<!-- Javascript functions -->
var area_id;
$(document).ready(function(){
  $(".menu ul li").menu();

  $(".chosen").chosen();

  $("div").hover(function(e){
    $(".collect_data").poshytip('hide');
  });
//=========================================================判断是否是搜索进入页面=========================================//
   var search = "<? if(isset($data_search)) echo $data_search; else echo ""; ?>"
   if(search!=""&&search!=null){
    $("#button_three").css("display","inline-block");
    $("#button_three_c").css("display","block");
    $("#button_one_c").css("display","none");
    $("#button_one").removeClass("active");
    $("#button_three").addClass("active");
   }
//=========================================================用户信息tips=========================================//

// $(".hoveruser").live('blur',function(){
//   $(".tipforfix").css("display","none");
// });
$('.hoveruser').poshytip({
        className: 'nothing',
        offsetY: 30,
        offsetX: -140,
        content: function(updateCallback) {
          var user_id = this.id;
          var url ="<?php echo site_url('primary/wxc_personal/personal_base_tips')?>";
          var text = this.text;
          var str = "";
          var position = getPosition(this);
          var top = (position.split("&")[0]-80)+"px";
          var left = (position.split("&")[1]-130)+"px";

          $.ajax({
              type:"post",
              data:({'user_id': user_id}),
              url:url,
              dataType:"json",
              success: function(result)
                  {
                    if(result!=''){

                        str += "<div class='tipforfix'><div class='creamnote_tips'>";
                        str += "<div class='tip_card'><div class='tip_content'>";
                        str += "<img class='fl' src='"+result['user_header']+"'>";
                        str += "<div class='tip_right fl'><div class='co fl name'>"+text+"</div>";
                        str += "<div class='co fl'>资料被下载"+result['user_downloaded']+"次</div>";
                        str += "<div class='co fl'>拥有"+result['user_datacount']+"份资料</div></div>";
                        str += "<div class='tip_bottom ''>"+result['user_school']+"/"+result['user_major']+"</div></div></div>";
                        str += "<div class='tip_arrow1'></div><div class='tip_arrow2'></div></div></div>";

                         // $(".creamnote_tips").html(str);
                         // $(".creamnote_tips").css("left",left);
                         // $(".creamnote_tips").css("top",top);
                        // $(".tipforfix").css("display","block");
                    }

                  },
                 error: function(XMLHttpRequest, textStatus, errorThrown) {
                              alert(XMLHttpRequest.status);
                              alert(XMLHttpRequest.readyState);
                              alert(textStatus);
                          }
              });
          window.setTimeout(function() {
            updateCallback(str);
          }, 800);
          return "<div class='tipforfix'><div class='creamnote_tips'><div class='tip_card'><div class='tip_content' style='text-align: center;'>资料读取中...</div></div></div><div class='tip_arrow1'></div><div class='tip_arrow2'></div></div></div>";
        }
      });
});

function init(){
	$(".menu ul li").menu();

  $("[name='data_point']").each(function(){
    var data_point = this.value;
    var star_width = data_point*20 +"px";
    $(this).parent(".card_star_blod").css("width",star_width);
});
}
//按类别搜索
function searchbynature(nature_id){
	var url = '<?php echo site_url('primary/wxc_search/gen_search_by_nature_id'); ?>';
    $.ajax({
        type:"post",
        url:url,
        dataType:"json",
        data:({'nature_id':nature_id,
            }),
        success: function(result)
            {
              //if(result=='success'){
               // location.reload();
           		var str="";
          		var i ;
          		for(i in result){
              		var base_search_url = "<?php echo site_url('data/wxc_data/data_view/'); ?>";
              		var dataview = base_search_url + "/" + result[i]['data_id'];
              		var downloadfile = "<?php echo site_url('data/wxc_data/download_file/'); ?>" + "/" + result[i]['data_id'];
                  var search_by_nature = "<?php echo site_url('primary/wxc_search/search_by_nature/'); ?>" + "/" + result[i]['data_nature_id'];
          				var search_by_area = "<?php echo site_url('primary/wxc_search/search_by_area/'); ?>" + "/" + result[i]['data_area_id'];
                  str += "<div class='card_item card_item_panel' style='margin: 22px 0 0 15px;'>";
                  // echo "<a href=".base_url()."data/wxc_data/complete_data_page/".$note['data_id']."><div class='card_edit'></div></a>";
                  // echo "<div class='card_delete'></div>";
                  str += "<div class='card_head'>";
                  str += "<a href="+dataview+">"+result[i]['data_name']+"</a>";
                  str += "</div>";
                  str += "<div class='card_user card_padding'>";
                  str += "作者:<a class='hoveruser2'  id='"+result[i]['user_id']+"' href='#'>"+result[i]['user_name']+"</a>";
                  str += "</div>";
                  str += "<div class='card_cate card_padding'>";
                  str += "<a href="+search_by_nature+" >"+result[i]['data_nature_name']+"</a>|";
                  str += "<a href="+search_by_area+" >"+result[i]['data_area_name']+"</a>";
                  str += "</div>";
                  str += "<div class='card_normal '>";
                  str += "<div class=''>";
                  if(result[i]['data_type'] == "doc"||result[i]['data_type'] == "docx"){
                  str += "<img src='/application/frontend/views/resources/images/version/doc_icon.png'>";
                  }else if(result[i]['data_type'] == "ppt"||result[i]['data_type'] == "pptx"){
                  str += "<img src='/application/frontend/views/resources/images/version/ppt_icon.png'>";
                  }else if(result[i]['data_type'] == "xls"||result[i]['data_type'] == "xlsx"){
                  str += "<img src='/application/frontend/views/resources/images/version/xls_icon.png'>";
                  }else if(result[i]['data_type'] == "txt"){
                  str += "<img src='/application/frontend/views/resources/images/version/txt_icon.png'>";
                  }else if(result[i]['data_type'] == "pdf"){
                  str += "<img src='/application/frontend/views/resources/images/version/pdf_icon.png'>";
                  }
                  str += "|"+result[i]['data_uploadtime'];
                  str += "|"+result[i]['data_pagecount']+"页";
                  str += "</div>";
                  str += "<div class='card_star'>";
                  str += "<input type='hidden' name='data_point' id='data_point' value='"+result[i]['data_point']+"'>";
                  str += "</div>";
                  str += "</div>";

                  str += "<div class='card_count'>";
                  str += "下载:"+result[i]['dactivity_download_count']+"&nbsp&nbsp&nbsp浏览:"+result[i]['dactivity_view_count'];
                  str += "</div>";

                  str += "<div class='card_footer'>";
                  str += "<a href="+downloadfile+"><span class='foot_content_down'></span></a>";
                  str += "<span class='foot_content_price'>"+result[i]['data_price']+"</span>";
                  str += "<a href='#' ><span class='foot_content_heart'></span></a>";
                  str += "</div>";
                  str += "</div>";
	           	 	}
	           	 $("#card_items_data3").html(str);
	           	 init();
                //}
                $("#button_three").css("display","inline-block");
                $("#button_three_c").css("display","block");
                $("#button_one_c").css("display","none");
                $("#button_two_c").css("display","none");
                $("#button_one").removeClass("active");
                $("#button_two").removeClass("active");
                $("#button_three").addClass("active");
                star_grade();
                user_hover();
            },
           error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            }
        });
}

//按地方搜索
function searchbyarea(){
	area_name = $("#hiddenschool").attr("value");
	area_id = $("#partment").attr("value");
	var url = '<?php echo site_url('primary/wxc_search/gen_search_by_area_id'); ?>';
    $.ajax({
        type:"post",
        url:url,
        dataType:"json",
        data:({'area_id':area_id,
            	'area_name':area_name
            }),
        success: function(result)
            {
              //if(result=='success'){
               // location.reload();
           		var str="";
          		var i ;
          		for(i in result){
              		var base_search_url = "<?php echo site_url('data/wxc_data/data_view/'); ?>";
                  var dataview = base_search_url + "/" + result[i]['data_id'];
                  var downloadfile = "<?php echo site_url('data/wxc_data/download_file/'); ?>" + "/" + result[i]['data_id'];
                  var search_by_nature = "<?php echo site_url('primary/wxc_search/search_by_nature/'); ?>" + "/" + result[i]['data_nature_id'];
                  var search_by_area = "<?php echo site_url('primary/wxc_search/search_by_area/'); ?>" + "/" + result[i]['data_area_id'];
                  str += "<div class='card_item card_item_panel' style='margin: 22px 0 0 15px;'>";
                  // echo "<a href=".base_url()."data/wxc_data/complete_data_page/".$note['data_id']."><div class='card_edit'></div></a>";
                  // echo "<div class='card_delete'></div>";
                  str += "<div class='card_head'>";
                  str += "<a href="+dataview+">"+result[i]['data_name']+"</a>";
                  str += "</div>";
                  str += "<div class='card_user card_padding'>";
                  str += "作者:<a class='hoveruser2'  id='"+result[i]['user_id']+"' href='#'>"+result[i]['user_name']+"</a>";
                  str += "</div>";
                  str += "<div class='card_cate card_padding'>";
                  str += "<a href="+search_by_nature+" >"+result[i]['data_nature_name']+"</a>|";
                  str += "<a href="+search_by_area+" >"+result[i]['data_area_name']+"</a>";
                  str += "</div>";
                  str += "<div class='card_normal '>";
                  str += "<div class=''>";
                  if(result[i]['data_type'] == "doc"||result[i]['data_type'] == "docx"){
                  str += "<img src='/application/frontend/views/resources/images/version/doc_icon.png'>";
                  }else if(result[i]['data_type'] == "ppt"||result[i]['data_type'] == "pptx"){
                  str += "<img src='/application/frontend/views/resources/images/version/ppt_icon.png'>";
                  }else if(result[i]['data_type'] == "xls"||result[i]['data_type'] == "xlsx"){
                  str += "<img src='/application/frontend/views/resources/images/version/xls_icon.png'>";
                  }else if(result[i]['data_type'] == "txt"){
                  str += "<img src='/application/frontend/views/resources/images/version/txt_icon.png'>";
                  }else if(result[i]['data_type'] == "pdf"){
                  str += "<img src='/application/frontend/views/resources/images/version/pdf_icon.png'>";
                  }
                  str += "|"+result[i]['data_uploadtime'];
                  str += "|"+result[i]['data_pagecount']+"页";
                  str += "</div>";
                  str += "<div class='card_star'>";
                  str += "<input type='hidden' name='data_point' id='data_point' value='"+result[i]['data_point']+"'>";
                  str += "</div>";
                  str += "</div>";

                  str += "<div class='card_count'>";
                  str += "下载:"+result[i]['dactivity_download_count']+"&nbsp&nbsp&nbsp浏览:"+result[i]['dactivity_view_count'];
                  str += "</div>";

                  str += "<div class='card_footer'>";
                  str += "<a href="+downloadfile+"><span class='foot_content_down'></span></a>";
                  str += "<span class='foot_content_price'>"+result[i]['data_price']+"</span>";
                  str += "<a href='#' ><span class='foot_content_heart'></span></a>";
                  str += "</div>";
                  str += "</div>";
	           	 	}
	           	 $("#card_items_data3").html(str);
	           	 init();
               $("#button_three").css("display","inline-block");
                $("#button_three_c").css("display","block");
                $("#button_one_c").css("display","none");
                $("#button_two_c").css("display","none");
                $("#button_one").removeClass("active");
                $("#button_two").removeClass("active");
                $("#button_three").addClass("active");
                star_grade();
                user_hover();

            },
           error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            }
        });
}

function searchbefore(the,type,val){
  area_name = $("#hiddenschool").attr("value");
  area_id = $("#partment").attr("value");
  nature_id = $("#nature_id").attr("value");
  if(type==1){
    $("#nature_id").attr("value",val);
    var str = the.text+"<b onclick='searchbefore(this,3,null)' class='del'>X</b>";
    $("#filter_o").html(str);
    $("#filter_o").css("display","inline-block");
    searchall(val,area_name,area_id);
  }
  else if(type == 2){
    if(area_name != ""){
      var str = area_name+"<b onclick='searchbefore(this,4,null)' class='del'>X</b>";
      $("#filter_t").html(str);
      $("#filter_t").css("display","inline-block");
    }
    if(area_id != ""){
      var str = $("#partment").find("option:selected").text()+"<b onclick='searchbefore(this,5,null)' class='del'>X</b>";
      $("#filter_th").html(str);
      $("#filter_th").css("display","inline-block");
    }
    // alert(nature_id+","+area_name+","+area_id)
    searchall(nature_id,area_name,area_id);
  }
  else if(type == 3){
    $("#filter_o").css("display","none");
    $("#nature_id").attr("value","");
    searchall($("#nature_id").attr("value"),area_name,area_id);
  }
  else if(type == 4){
    $("#filter_t").css("display","none");
    $("#filter_th").css("display","none");
    $("#hiddenschool").attr("value","");
    $("#school-name").attr("value","");

    $("#partment").find("option").remove();
    $("#partment").removeClass("chzn-done");
    $("#partment_chzn").css("display","none");
    $("#partment").chosen();
    searchall(nature_id,$("#hiddenschool").attr("value"),area_id);
  }
  else if(type == 5){
    $("#filter_th").css("display","none");
    $("#partment").attr("value","-1");
    searchall(nature_id,area_name,"");
  }
}
//按地方搜索
function searchall(nature_id,area_name,area_id){
  // area_name = $("#hiddenschool").attr("value");
  // area_id = $("#partment").attr("value");

  var url = '<?php echo site_url('primary/wxc_search/gen_search'); ?>';
    $.ajax({
        type:"post",
        url:url,
        dataType:"json",
        data:({'major_id':area_id,
              'school_name':area_name,
              'nature_id':nature_id
            }),
        success: function(result)
            {
              //if(result=='success'){
               // location.reload();
              var str="";
              var i ;
              for(i in result){
                  var base_search_url = "<?php echo site_url('data/wxc_data/data_view/'); ?>";
                  var dataview = base_search_url + "/" + result[i]['data_id'];
                  var downloadfile = "<?php echo site_url('core/wxc_download_note/download_file/'); ?>" + "/" + result[i]['data_id'];
                  var search_by_nature = "<?php echo site_url('primary/wxc_search/search_by_nature/'); ?>" + "/" + result[i]['data_nature_id_school'];
                  var search_by_area = "<?php echo site_url('primary/wxc_search/search_by_area/'); ?>" + "/" + result[i]['data_area_id_major'];
                  str += "<div class='_card_item _card_item_panel' ><div class='_item_actual'>";
                  str += "<div class='_card_content'>";
                  str += "<div class='card_head'>";
                  str += "<a href="+dataview+">"+result[i]['data_name']+"</a>";
                  str += "</div>";
                  str += "<div class='card_user _card_user'>";
                  str += "作者:<a class='hoveruser2'  id='"+result[i]['user_id']+"' href='#'>"+result[i]['user_name']+"</a>";
                  str += "</div>";
                  str += "<div class='card_cate _card_cate card_padding'>";
                  str += "分类:<a href="+search_by_nature+" >"+result[i]['data_nature_name']+"</a>";
                  if(result[i]['data_area_name_school']!=""){str+="|";}
                  str += "<a href="+search_by_area+" >"+result[i]['data_area_name_school']+"</a>";
                  if(result[i]['data_area_name_major']!=""){str+="|";}
                  str += "<a href="+search_by_area+" >"+result[i]['data_area_name_major']+"</a>";
                  str += "</div>";
                  str += "<div class='card_normal '>";
                  str += "<div class='_card_page'>";
                  if(result[i]['data_type'] == "doc"||result[i]['data_type'] == "docx"){
                  str += "<img src='/application/frontend/views/resources/images/version/doc_icon.png'>";
                  }else if(result[i]['data_type'] == "ppt"||result[i]['data_type'] == "pptx"){
                  str += "<img src='/application/frontend/views/resources/images/version/ppt_icon.png'>";
                  }else if(result[i]['data_type'] == "xls"||result[i]['data_type'] == "xlsx"){
                  str += "<img src='/application/frontend/views/resources/images/version/xls_icon.png'>";
                  }else if(result[i]['data_type'] == "txt"){
                  str += "<img src='/application/frontend/views/resources/images/version/txt_icon.png'>";
                  }else if(result[i]['data_type'] == "pdf"){
                  str += "<img src='/application/frontend/views/resources/images/version/pdf_icon.png'>";
                  }else if(result[i]['data_type'] == "wps"){
                  str += "<img src='/application/frontend/views/resources/images/version/wps_icon.png'>";
                  }
                  str += "<div class='fr'>"+result[i]['data_pagecount']+"页</div>";
                  str += "</div>";
                  str += "<div class='card_star'><div class='card_star_blod' >";
                  str += "<input type='hidden' name='data_point' id='data_point' value='"+result[i]['data_point']+"'>";
                  str += "</div></div>";
                  str += "</div>";

                  // str += "<div class='card_count'>";
                  // str += "下载:"+result[i]['dactivity_download_count']+"&nbsp&nbsp&nbsp浏览:"+result[i]['dactivity_view_count'];
                  // str += "</div>";

                  str += "<div class='_card_footer'>";
                  //str += "<a href="+downloadfile+"><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_down.png' class='_foot_content_down fl'></a>";
                  str += "<a onclick='download_notes("+result[i]['data_id']+")' href='javascript:void(0)'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_down.png' class='common_show_login_win _foot_content_down fl'></a>";
                  str += "<div id='collect_3"+result[i]['data_id']+"' style='display: initial;' class='collect_data'>";
                  if(result[i]['collect'] == "true"){
                    str += "<a id='col"+result[i]['data_id']+"' href='javascript:void(0)' onclick='uncollect(3,"+result[i]['data_id']+")'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_store_red.png' class='_card_edit fl' title='取消收藏'></a>";
                  }else{
                    str += "<a id='col"+result[i]['data_id']+"' href='javascript:void(0)' onclick='collect(3,"+result[i]['data_id']+")'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_store.png' class='common_show_login_win _card_edit fl' title='收藏'></a>";
                  }
                  str += "</div>";
                  str += "<span class='_foot_content_price fr'>"+result[i]['data_price']+"</span>";
                  str += "</div></div>";
                  str += "<div class='_card_bottom'>";
                  str += "<div class='fl'><img src='/application/frontend/views/resources/images/new_version/dy_card_view.png'><span>"+result[i]['dactivity_view_count']+"</span></div>";
                  str += "<div class='fr'>"+result[i]['data_uploadtime']+"</div>";
                  str += "</div></div></div>";

                }
                if(i == -1 ||!i){
                  str ="<div style='margin: 14px 0 0 28px;color: red;'>对不起，未找到您要找资料！</div>";
                }
               $("#card_items_data3").html(str);
               init();
               $("#button_three").css("display","inline-block");
                $("#button_three_c").css("display","block");
                $("#button_one_c").css("display","none");
                $("#button_two_c").css("display","none");
                $("#button_one").removeClass("active");
                $("#button_two").removeClass("active");
                $("#button_three").addClass("active");
                star_grade();
                user_hover();

            },
           error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            }
        });
}
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
    //=======重新选择学校是去除标签start=====//
      $("#filter_t").css("display","none");
      $("#filter_th").css("display","none");
      $("#hiddenschool").attr("value","");
      $("#school-name").attr("value","");

      $("#partment").find("option").remove();
      $("#partment").removeClass("chzn-done");
      $("#partment_chzn").css("display","none");
      $("#partment").chosen();
    //=======重新选择学校是去除标签end=====//
			var item=$(this);
			var school = item.attr('school-id');
			//更新选择大学文本框中的值
			$('#school-name').val(item.text());
			$('#hiddenschool').val(item.text());

			var wx_school=$("#hiddenschool").attr("value");
			var url ="<?php echo site_url('data/wxc_data/get_depart_by_school'); ?>";
			//$("#form")[0].submit();
			$.ajax({
		        type:"post",
		        data:({'wx_school': wx_school}),
		        url:url,
		        dataType:"json",
		        success: function(result)
		            {
		              var str="";
		            	var i ;
		              str +="<option value='' id='-1' value='-1'>选择专业</option>";
		            	for(i in result){
		            		str +="<option value="+result[i]['carea_id']+">"+result[i]['carea_name']+"</option>"	;
			            	};
			            $("#partment").html(str);
			            area_id = $("#partment").attr("value");
                  $("#partment").removeClass("chzn-done");
                  $("#partment_chzn").css("display","none");
                  $("#partment").chosen();
		            },
		             error: function(XMLHttpRequest, textStatus, errorThrown) {
		                        alert(XMLHttpRequest.status);
		                        alert(XMLHttpRequest.readyState);
		                        alert(textStatus);
		                    }
		        });
		    //form.submit();


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
//=========================================================用户信息tips=========================================//

function user_hover(){
    $('.hoveruser2').poshytip({
        className: 'nothing',
        offsetY: 30,
        offsetX: -140,
        content: function(updateCallback) {
          var user_id = this.id;
          var url ="<?php echo site_url('primary/wxc_personal/personal_base_tips')?>";
          var text = this.text;
          var str = "";
          var position = getPosition(this);
          var top = (position.split("&")[0]-80)+"px";
          var left = (position.split("&")[1]-130)+"px";

          $.ajax({
              type:"post",
              data:({'user_id': user_id}),
              url:url,
              dataType:"json",
              success: function(result)
                  {
                    if(result!=''){

                        str += "<div class='tipforfix'><div class='creamnote_tips'>";
                        str += "<div class='tip_card'><div class='tip_content'>";
                        str += "<img class='fl' src='"+result['user_header']+"'>";
                        str += "<div class='tip_right fl'><div class='co fl name'>"+text+"</div>";
                        str += "<div class='co fl'>"+result['user_email']+"</div>";
                        str += "<div class='co fl'>拥有"+result['user_datacount']+"份资料</div></div>";
                        str += "<div class='tip_bottom ''>"+result['user_school']+"/"+result['user_major']+"</div></div></div>";
                        str += "<div class='tip_arrow1'></div><div class='tip_arrow2'></div></div></div>";

                         // $(".creamnote_tips").html(str);
                         // $(".creamnote_tips").css("left",left);
                         // $(".creamnote_tips").css("top",top);
                        // $(".tipforfix").css("display","block");
                    }

                  },
                 error: function(XMLHttpRequest, textStatus, errorThrown) {
                              alert(XMLHttpRequest.status);
                              alert(XMLHttpRequest.readyState);
                              alert(textStatus);
                          }
              });
          window.setTimeout(function() {
            updateCallback(str);
          }, 800);
          return "<div class='tipforfix'><div class='creamnote_tips'><div class='tip_card'><div class='tip_content' style='text-align: center;'>资料读取中...</div></div></div><div class='tip_arrow1'></div><div class='tip_arrow2'></div></div></div>";
        }
      });
}


</script>

</head>
<body>
    <?php include  'application/frontend/views/share/header.php';?>

	<?php $nav_param = "note";?>
  <?php include  'application/frontend/views/share/navigation.php';?>

	<!-- end #header -->
<div class="backcolor_body">
	<div class="body _body" style="min-height:660px">
		<div class="_content" id="_content" >
		  <div class="post" style="padding-top:0;width: 781px;">
        <h2 class="_data_title _nomargin" id="info_title">
                <div class="_grgh">根据学校专业检索</div>
        </h2>

				<div class="meta">
				<div class="fl">学校 &nbsp</div><div class="fl"><input type="text" placeholder="点击选择学校" name="school" id="school-name" onclick="pop()" style="width: 175px;height:17px;"></div>
						<input type="hidden" id="hiddenschool" name="wx_school">
            <input type="hidden" id="nature_id" name="nature_id">
				<div class="fl" style="padding-left:5px;">专业 &nbsp</div><div class="fl"><select id='partment' name='partment' style='width: 200px;' class="chosen">  </select></div>
				<div class="fl" style="padding-left:5px;"><input type="submit" name="area_search" id="area_search" class="button_c" value="搜索" onclick="searchbefore(this,2,'')"></div>
				</div>
        <div class="meta" id="search_filter">
          <span>你已选择</span>
          <a id="filter_o" style="display:none;">考研资料<b onclick="searchall()" class="del">X</b></a>
          <a id="filter_t" style="display:none;">考研资料<b onclick="searchall()" class="del">X</b></a>
          <a id="filter_th" style="display:none;">考研资料<b onclick="searchall()" class="del">X</b></a>
        </div>
				<div id="choose-box-wrapper" style="z-index: 10000;">
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
              <div>
                <div class="filter" style=" ">
                    <div class="fl"><a class="active" id="button_one" rel="button_one_c" style="cursor: pointer;">优秀笔记</a></div>
                    <div class="_changedata_nick fl"><a class="" id="button_two" rel="button_two_c" style="cursor: pointer;">猜你喜欢</a></div>
                    <div class="_changedata_nick fl"><a class="" id="button_three" rel="button_three_c" style="cursor: pointer;display: none;">搜索结果</a></div>
                </div>
              </div>
				<div id="buttons">
                    <div class="entry" id="button_one_c" style="display:block;">
                        <div class="_card_total_common">
                          <div class="_card_items_common" id="card_items_data1">
                            <?php
                              if(isset($data_recommend)){
                                foreach ($data_recommend as $key => $note) {
                                  echo "<div class='_card_item _card_item_panel' ><div class='_item_actual'>";
                                  // echo "<a href=".base_url()."data/wxc_data/complete_data_page/".$note['data_id']."><div class='card_edit'></div></a>";
                                  // echo "<div class='card_delete'></div>";
                                  echo "<div class='_card_content'>";
                                  echo "<div class='card_head'>";
                                  echo "<a href=".base_url()."data/wxc_data/data_view/".$note['data_id'].">".str_replace(array(" ","\r","\n"), array("","",""), $note['data_name'])."</a>";
                                  echo "</div>";
                                  echo "<div class='card_user _card_user'>";
                                  echo "作者:<a class='hoveruser'  id='".$note['user_id']."' href='#'>".$note['user_name']."</a>";
                                  echo "</div>";
                                  echo "<div class='card_cate _card_cate card_padding'>";
                                  echo "分类:<a href=".base_url()."primary/wxc_search/search_by_nature/".$note['data_nature_id']." >".$note['data_nature_name']."</a>";
                                  echo $note['data_area_name_school']!=""?"|":"";
                                  echo "<a href=".base_url()."primary/wxc_search/search_by_area/".$note['data_area_id_school']." >".$note['data_area_name_school']."</a>";
                                  echo $note['data_area_name_major']!=""?"|":"";
                                  echo "<a href=".base_url()."primary/wxc_search/search_by_area/".$note['data_area_id_major']." >".$note['data_area_name_major']."</a>";
                                  echo "</div>";
                                  echo "<div class='card_normal '>";
                                  echo "<div class='_card_page'>";
                                  if($note['data_type'] == "doc"||$note['data_type'] == "docx"){
                                  echo "<img src='/application/frontend/views/resources/images/version/doc_icon.png'>";
                                  }else if($note['data_type'] == "ppt"||$note['data_type'] == "pptx"){
                                  echo "<img src='/application/frontend/views/resources/images/version/ppt_icon.png'>";
                                  }else if($note['data_type'] == "xls"||$note['data_type'] == "xlsx"){
                                  echo "<img src='/application/frontend/views/resources/images/version/xls_icon.png'>";
                                  }else if($note['data_type'] == "txt"){
                                  echo "<img src='/application/frontend/views/resources/images/version/txt_icon.png'>";
                                  }else if($note['data_type'] == "pdf"){
                                  echo "<img src='/application/frontend/views/resources/images/version/pdf_icon.png'>";
                                  }else if($note['data_type'] == "wps"){
                                  echo "<img src='/application/frontend/views/resources/images/version/wps_icon.png'>";
                                  }
                                  // echo "|".$note['data_uploadtime'];
                                  echo "<div class='fr'>".$note['data_pagecount']."页</div>";
                                  echo "</div>";
                                  echo "<div class='card_star'><div class='card_star_blod' >";
                                  echo "<input type='hidden' name='data_point' id='data_point' value='".$note['data_point']."'>";
                                  echo "</div></div>";
                                  echo "</div>";

                                  // echo "<div class='card_count'>";
                                  // echo "下载:".$note['dactivity_download_count']."&nbsp&nbsp&nbsp浏览:".$note['dactivity_view_count'];
                                  // echo "</div>";

                                  echo "<div class='_card_footer'>";
                                  echo "<a onclick='download_notes(".$note['data_id'].")' href='javascript:void(0)'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_down.png' class='common_show_login_win _foot_content_down fl'></a>";
                                  echo "<div id='collect_1".$note['data_id']."' style='display: initial;' class='collect_data'>";
                                  if($note['collect'] == "true"){
                                    echo "<a id='col".$note['data_id']."' href='javascript:void(0)' onclick='uncollect(1,".$note['data_id'].")'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_store_red.png' class='_card_edit fl' title='取消收藏'></a>";
                                  }else{
                                    echo "<a id='col".$note['data_id']."' href='javascript:void(0)' onclick='collect(1,".$note['data_id'].")'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_store.png' class='common_show_login_win _card_edit fl' title='收藏'></a>";
                                  }
                                  echo "</div>";
                                  echo "<span class='_foot_content_price fr'>".$note['data_price']."</span>";
                                  echo "</div></div>";
                                  echo "<div class='_card_bottom'>";
                                  echo "<div class='fl'><img src='/application/frontend/views/resources/images/new_version/dy_card_view.png'><span>".$note['dactivity_view_count']."</span></div>";
                                  echo "<div class='fr'>".$note['data_uploadtime']."</div>";
                                  echo "</div></div></div>";

                                }
                              }
                            ?>
                          </div>
                        </div>
    		        </div>

                    <div class="entry" id="button_two_c" style="display:none;">
                        <div class="_card_total_common">
                          <div class="_card_items_common" id="card_items_data2">
                            <?php
                              if(isset($data_youlike)){
                                foreach ($data_youlike as $key => $note) {
                                  echo "<div class='_card_item _card_item_panel' ><div class='_item_actual'>";
                                  // echo "<a href=".base_url()."data/wxc_data/complete_data_page/".$note['data_id']."><div class='card_edit'></div></a>";
                                  // echo "<div class='card_delete'></div>";
                                  echo "<div class='_card_content'>";
                                  echo "<div class='card_head'>";
                                  echo "<a href=".base_url()."data/wxc_data/data_view/".$note['data_id'].">".str_replace(array(" ","\r","\n"), array("","",""), $note['data_name'])."</a>";
                                  echo "</div>";
                                  echo "<div class='card_user _card_user'>";
                                  echo "作者:<a class='hoveruser'  id='".$note['user_id']."' href='#'>".$note['user_name']."</a>";
                                  echo "</div>";
                                  echo "<div class='card_cate _card_cate card_padding'>";
                                  echo "分类:<a href=".base_url()."primary/wxc_search/search_by_nature/".$note['data_nature_id']." >".$note['data_nature_name']."</a>";
                                  echo $note['data_area_name_school']!=""?"|":"";
                                  echo "<a href=".base_url()."primary/wxc_search/search_by_area/".$note['data_area_id_school']." >".$note['data_area_name_school']."</a>";
                                  echo $note['data_area_name_major']!=""?"|":"";
                                  echo "<a href=".base_url()."primary/wxc_search/search_by_area/".$note['data_area_id_major']." >".$note['data_area_name_major']."</a>";
                                  echo "</div>";
                                  echo "<div class='card_normal '>";
                                  echo "<div class='_card_page'>";
                                  if($note['data_type'] == "doc"||$note['data_type'] == "docx"){
                                  echo "<img src='/application/frontend/views/resources/images/version/doc_icon.png'>";
                                  }else if($note['data_type'] == "ppt"||$note['data_type'] == "pptx"){
                                  echo "<img src='/application/frontend/views/resources/images/version/ppt_icon.png'>";
                                  }else if($note['data_type'] == "xls"||$note['data_type'] == "xlsx"){
                                  echo "<img src='/application/frontend/views/resources/images/version/xls_icon.png'>";
                                  }else if($note['data_type'] == "txt"){
                                  echo "<img src='/application/frontend/views/resources/images/version/txt_icon.png'>";
                                  }else if($note['data_type'] == "pdf"){
                                  echo "<img src='/application/frontend/views/resources/images/version/pdf_icon.png'>";
                                  }else if($note['data_type'] == "wps"){
                                  echo "<img src='/application/frontend/views/resources/images/version/wps_icon.png'>";
                                  }
                                  // echo "|".$note['data_uploadtime'];
                                  echo "<div class='fr'>".$note['data_pagecount']."页</div>";
                                  echo "</div>";
                                  echo "<div class='card_star'><div class='card_star_blod' >";
                                  echo "<input type='hidden' name='data_point' id='data_point' value='".$note['data_point']."'>";
                                  echo "</div></div>";
                                  echo "</div>";

                                  // echo "<div class='card_count'>";
                                  // echo "下载:".$note['dactivity_download_count']."&nbsp&nbsp&nbsp浏览:".$note['dactivity_view_count'];
                                  // echo "</div>";

                                  echo "<div class='_card_footer'>";
                                  echo "<a onclick='download_notes(".$note['data_id'].")' href='javascript:void(0)'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_down.png' class='common_show_login_win _foot_content_down fl'></a>";
                                  echo "<div id='collect_2".$note['data_id']."' style='display: initial;' class='collect_data'>";
                                  if($note['collect'] == "true"){
                                    echo "<a id='col".$note['data_id']."' href='javascript:void(0)' onclick='uncollect(2,".$note['data_id'].")'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_store_red.png' class='common_show_login_win _card_edit fl' title='取消收藏'></a>";
                                  }else{
                                    echo "<a id='col".$note['data_id']."' href='javascript:void(0)' onclick='collect(2,".$note['data_id'].")'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_store.png' class='common_show_login_win _card_edit fl' title='收藏'></a>";
                                  }
                                  echo "</div>";
                                  echo "<span class='_foot_content_price fr'>".$note['data_price']."</span>";
                                  echo "</div></div>";
                                  echo "<div class='_card_bottom'>";
                                  echo "<div class='fl'><img src='/application/frontend/views/resources/images/new_version/dy_card_view.png'><span>".$note['dactivity_view_count']."</span></div>";
                                  echo "<div class='fr'>".$note['data_uploadtime']."</div>";
                                  echo "</div></div></div>";

                                }
                              }
                            ?>
                          </div>
                        </div>
                    </div>

                    <div class="entry" id="button_three_c" style="display:none;">
                        <div class="_card_total_common">
                          <div class="_card_items_common" id="card_items_data3">
                            <?php
                              $data_count=0;
                              if(isset($data_search)){
                                foreach ($data_search as $key => $note) {
                                  $data_count++;
                                  echo "<div class='_card_item _card_item_panel' ><div class='_item_actual'>";
                                  // echo "<a href=".base_url()."data/wxc_data/complete_data_page/".$note['data_id']."><div class='card_edit'></div></a>";
                                  // echo "<div class='card_delete'></div>";
                                  echo "<div class='_card_content'>";
                                  echo "<div class='card_head'>";
                                  echo "<a href=".base_url()."data/wxc_data/data_view/".$note['data_id'].">".str_replace(array(" ","\r","\n"), array("","",""), $note['data_name'])."</a>";
                                  echo "</div>";
                                  echo "<div class='card_user _card_user'>";
                                  echo "作者:<a class='hoveruser'  id='".$note['user_id']."' href='#'>".$note['user_name']."</a>";
                                  echo "</div>";
                                  echo "<div class='card_cate _card_cate card_padding'>";
                                  echo "分类:<a href=".base_url()."primary/wxc_search/search_by_nature/".$note['data_nature_id']." >".$note['data_nature_name']."</a>";
                                  echo $note['data_area_name_school']!=""?"|":"";
                                  echo "<a href=".base_url()."primary/wxc_search/search_by_area/".$note['data_area_id_school']." >".$note['data_area_name_school']."</a>";
                                  echo $note['data_area_name_major']!=""?"|":"";
                                  echo "<a href=".base_url()."primary/wxc_search/search_by_area/".$note['data_area_id_major']." >".$note['data_area_name_major']."</a>";
                                  echo "</div>";
                                  echo "<div class='card_normal '>";
                                  echo "<div class='_card_page'>";
                                  if($note['data_type'] == "doc"||$note['data_type'] == "docx"){
                                  echo "<img src='/application/frontend/views/resources/images/version/doc_icon.png'>";
                                  }else if($note['data_type'] == "ppt"||$note['data_type'] == "pptx"){
                                  echo "<img src='/application/frontend/views/resources/images/version/ppt_icon.png'>";
                                  }else if($note['data_type'] == "xls"||$note['data_type'] == "xlsx"){
                                  echo "<img src='/application/frontend/views/resources/images/version/xls_icon.png'>";
                                  }else if($note['data_type'] == "txt"){
                                  echo "<img src='/application/frontend/views/resources/images/version/txt_icon.png'>";
                                  }else if($note['data_type'] == "pdf"){
                                  echo "<img src='/application/frontend/views/resources/images/version/pdf_icon.png'>";
                                  }else if($note['data_type'] == "wps"){
                                  echo "<img src='/application/frontend/views/resources/images/version/wps_icon.png'>";
                                  }
                                  // echo "|".$note['data_uploadtime'];
                                  echo "<div class='fr'>".$note['data_pagecount']."页</div>";
                                  echo "</div>";
                                  echo "<div class='card_star'><div class='card_star_blod' >";
                                  echo "<input type='hidden' name='data_point' id='data_point' value='".$note['data_point']."'>";
                                  echo "</div></div>";
                                  echo "</div>";

                                  // echo "<div class='card_count'>";
                                  // echo "下载:".$note['dactivity_download_count']."&nbsp&nbsp&nbsp浏览:".$note['dactivity_view_count'];
                                  // echo "</div>";

                                  echo "<div class='_card_footer'>";
                                  echo "<a onclick='download_notes(".$note['data_id'].")' href='javascript:void(0)'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_down.png' class='common_show_login_win _foot_content_down fl'></a>";
                                  echo "<div id='collect_3".$note['data_id']."' style='display: initial;' class='collect_data'>";
                                  if($note['collect'] == "true"){
                                    echo "<a id='col".$note['data_id']."' href='javascript:void(0)' onclick='uncollect(1,".$note['data_id'].")'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_store_red.png' class='common_show_login_win _card_edit fl' title='取消收藏'></a>";
                                  }else{
                                    echo "<a id='col".$note['data_id']."' href='javascript:void(0)' onclick='collect(3,".$note['data_id'].")'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_store.png' class='common_show_login_win _card_edit fl' title='收藏'></a>";
                                  }
                                  echo "</div>";
                                  echo "<span class='_foot_content_price fr'>".$note['data_price']."</span>";
                                  echo "</div></div>";
                                  echo "<div class='_card_bottom'>";
                                  echo "<div class='fl'><img src='/application/frontend/views/resources/images/new_version/dy_card_view.png'><span>".$note['dactivity_view_count']."</span></div>";
                                  echo "<div class='fr'>".$note['data_uploadtime']."</div>";
                                  echo "</div></div></div>";

                                }
                              }
                              if($data_count == 0){
                                echo "<div style='margin: 14px 0 0 28px;color: red;'>对不起，未找到您要找资料！</div>";
                              }
                            ?>
                          </div>
                        </div>
                    </div>
                </div>
		  </div>

		</div>
	<!-- end #content -->
	<div id="_sidebar"  class="_sidebar_step">
    <h1 class="">根据分类检索</h1>
	<div id="content1">
        <div class="menu">
        <ul style="padding-left: 0px;">
        	<li><a class="active" href="javascript:void(0)">考研资料</a>
        		<ul style="display: block;">
        			<li><a href="javascript:void(0)" onclick="searchbefore(this,1,4)">考研非公共</a></li>
        			<li><a href="javascript:void(0)" onclick="searchbefore(this,1,5)">考研公共</a>
        				<ul>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,6)">考研英语</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,7)">考研数学</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,8)">考研政治</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,9)">考研其他</a></li>
        				</ul>
        			</li>
        		</ul>
        	</li>
        	<li><a href="javascript:void(0)">考试</a>
        		<ul>
        			<li><a href="javascript:void(0)" onclick="searchbefore(this,1,10)">期末考试</a></li>
        			<li><a href="javascript:void(0)" onclick="searchbefore(this,1,11)">其他考试</a>
        				<ul>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,12)">IT认证</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,13)">公务员</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,14)">财会/金融</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,15)">从业资格</a></li>
        				</ul>
        			</li>
        		</ul>
        	</li>
        	<li><a href="javascript:void(0)">学习笔记</a>
        		<ul>
        			<li><a href="javascript:void(0)" onclick="searchbefore(this,1,16)">IT/计算机</a>
        				<ul>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,22)">网络</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,23)">计算机软件</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,24)">计算机硬件</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,25)">其他</a></li>
        				</ul>
        			</li>
        			<li><a href="javascript:void(0)" onclick="searchbefore(this,1,17)">工程科学</a>
        				<ul>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,26)">信息与通信</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,27)">电子与电路</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,28)">建筑/土木</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,29)">其他</a></li>
        				</ul>
        			</li>
        			<li><a href="javascript:void(0)" onclick="searchbefore(this,1,18)">自然科学</a>
        				<ul>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,30)">数学</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,31)">物理</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,32)">化学</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,33)">生物</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,34)">地理</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,35)">其他</a></li>
        				</ul>
        			</li>
        			<li><a href="javascript:void(0)" onclick="searchbefore(this,1,19)">人文社科</a>
        				<ul>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,36)">法律</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,37)">设计/艺术</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,38)">社会学</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,39)">教育学</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,40)">其他</a></li>
        				</ul>
        			</li>
        			<li><a href="javascript:void(0)" onclick="searchbefore(this,1,20)">医药</a>
        				<ul>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,41)">基础医学</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,42)">中医中药</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,43)">药学</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,44)">临床医学</a></li>
        					<li><a href="javascript:void(0)" onclick="searchbefore(this,1,45)">其他</a></li>
        				</ul>
        			</li>
        			<li><a href="javascript:void(0)" onclick="searchbefore(this,1,21)">其他</a></li>
        		</ul>
        	</li>
        </ul>
        </div>
        </div>
    </div>
    <!--滚动至顶部-->
  <div id="updown"><span class="up transition"></span></div>

	<div style="clear: both;">&nbsp;</div>
	</div>
	<!-- end #body -->
	<?php include  'application/frontend/views/share/footer.php';?>
	<!-- end #footer -->
</div>

</body>

</html>
