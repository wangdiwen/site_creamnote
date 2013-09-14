
//=========================================================统一ajax=========================================//
function ajax_common_json(url,params){
    var retData;
    $.ajax({
            type:"post",
            data:params,
            url:url,
            dataType:"json",
            // timeout:1000,
            async: false,//同步
            success: function(result)
            {
                // var obj = $.parseJSON(result);
                // $("#ret_common_json").attr("value",result);
                retData = result;
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            }
        });
    return retData;
}
function ajax_common(url,params){
    var retData;
    $.ajax({
            type:"post",
            data:params,
            url:url,
            //dataType:"json",
            async: false,//同步
            success: function(result)
            {
                retData = result;
                $("#ret_common_json").attr("value",result);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            }
        });
    return retData;
}

$(function() {
//=========================================================卡片hover=========================================//
    $("._item_actual").live({
        mouseenter:function(){
            $(this).find('._card_footer').css("display","block");
        },
        mouseleave:function(){
            $(this).find('._card_footer').css("display","none");
        }
    });
    // $("._item_actual").mouseover(function() {
    //     $(this).find('._card_footer').css("display","block");
    // });
    // $("._item_actual").mouseout(function() {
    //     $(this).find('._card_footer').css("display","none");
    // });
//=========================================================卡片菜单=========================================//
    $(".filter a").click(function() {

        // Figure out current list via CSS class
        var curList = $(".filter a.active").attr("rel");

        // List moving to
        var $newList = $(this);

        // Set outer wrapper height to height of current inner list
        var curListHeight = $("#buttons").height();
        $("#buttons").height(curListHeight);

        // Remove highlighting - Add to just-clicked tab
        $(".filter a").removeClass("active");
        $newList.addClass("active");

        // Figure out ID of new list
        var listID = $newList.attr("rel");

        if (listID != curList) {

            // Fade out current list
            $("#"+curList).fadeOut(100, function() {

                // Fade in new list on callback
                $("#"+listID).fadeIn(1000);
                $("#"+listID).css("display","inline-block");
                // Adjust outer wrapper to fit new list snuggly
                var newHeight = $("#"+listID).height();
                $("#buttons").animate({
                    height: newHeight
                });

            });

        }

        // Don't behave like a regular link
        return false;
    });
//=========================================================首页箭头出现消失=========================================//
$("#card_total_data").mouseover(function() {
    $("#btn-left").css("display","block");
    $("#btn-right").css("display","block");
});

$("#card_total_data").mouseout(function() {
    $("#btn-left").css("display","none");
    $("#btn-right").css("display","none");
});

$("#card_total_user").mouseover(function() {
    $("#btn-left_u").css("display","block");
    $("#btn-right_u").css("display","block");
});

$("#card_total_user").mouseout(function() {
    $("#btn-left_u").css("display","none");
    $("#btn-right_u").css("display","none");
});
// $(".card_arrow_right").mouseover(function() {
//     $(this).css("background","url(../images/version/right_grey.png) no-repeat");
// });
// $(".card_arrow_right").mouseout(function() {
//     $(this).css("background","url(../images/version/right_grey_trans.png) no-repeat");
// });

//=========================================================计算等级=========================================//
$("[name='data_point']").each(function(){
    var data_point = this.value;
    var star_width = data_point*20 +"px";
    $(this).parent(".card_star_blod").css("width",star_width);
});
//=========================================================头像tips=========================================//
$(".gravatar").poshytip({
    className:'tip-darkgray',
  })
//=========================================================粉丝tips=========================================//
$("._personal_view").poshytip({
    className:'tip-darkgray',
  })
//=========================================================关注tips=========================================//
$("._personal_store").poshytip({
    className:'tip-darkgray',
  })
//=========================================================搜索tips=========================================//
$("#search-text").poshytip({
    content: '请输入关键词',
    className:'tip-darkgray',
    showOn: 'none',
    alignTo: 'target',
    alignX: 'inner-left',
    offsetX: 30,
    offsetY: -0.1
})
//=========================================================留言tips=========================================//
$("#message_content").poshytip({
    content: '请输入留言内容',
    className:'tip-darkgray',
    showOn: 'none',
    alignTo: 'target',
    alignX: 'inner-left',
    offsetX: 30,
    offsetY: -0.1
})
//=========================================================收藏tips=========================================//
$(".collect_data").poshytip({
    content: '收藏成功',
    className:'tip-darkgray',
    showOn: 'none',
    alignTo: 'target',
    alignX: 'inner-left',
    offsetX: 60,
})
//=========================================================出现隐藏菜单=========================================//
$("#Webfonts").click(function(){



});

});
//=========================================================滚动置顶fixed=========================================//
$(window).scroll(function() {
    if($(window).scrollTop() >= 65){
        $("#header").css("position","fixed");
        $("#header").css("opacity",".88");
        $("#dock").css("position","fixed");
        $("#dock").css("top","48px");
        $("#Webfonts").css("position","fixed");
        $("#Webfonts").css("top","33px");

        $("#search").css("display","block");
    }else{
        $("#header").css("position","static");
        $("#header").css("opacity","1");
        $("#dock").css("position","absolute");
        $("#dock").css("top","115px");
        $("#Webfonts").css("position","absolute");
        $("#Webfonts").css("top","100px");

        $("#search").css("display","none");
    }
});
//=========================================================获得元素位置=========================================//
function getPosition(e){
  var t=e.offsetTop;
  var l=e.offsetLeft;
  while(e=e.offsetParent){
    t+=e.offsetTop;
    l+=e.offsetLeft;
  }
  return t+"&"+l;
}
//=========================================================计算等级函数=========================================//
function star_grade(){
    $("[name='data_point']").each(function(){
        var data_point = this.value;
        var star_width = data_point*20 +"px";
        $(this).parent(".card_star").css("width",star_width);
    });
}
//=========================================================邮箱hash=========================================//
var email_hash={
        'qq.com': 'http://mail.qq.com',
        'gmail.com': 'http://mail.google.com',
        'sina.com': 'http://mail.sina.com.cn',
        '163.com': 'http://mail.163.com',
        '126.com': 'http://mail.126.com',
        'yeah.net': 'http://www.yeah.net/',
        'sohu.com': 'http://mail.sohu.com/',
        'tom.com': 'http://mail.tom.com/',
        'sogou.com': 'http://mail.sogou.com/',
        '139.com': 'http://mail.10086.cn/',
        'hotmail.com': 'http://www.hotmail.com',
        'live.com': 'http://login.live.com/',
        'live.cn': 'http://login.live.cn/',
        'live.com.cn': 'http://login.live.com.cn',
        '189.com': 'http://webmail16.189.cn/webmail/',
        'yahoo.com.cn': 'http://mail.cn.yahoo.com/',
        'yahoo.cn': 'http://mail.cn.yahoo.com/',
        'eyou.com': 'http://www.eyou.com/',
        '21cn.com': 'http://mail.21cn.com/',
        '188.com': 'http://www.188.com/',
        'foxmail.coom': 'http://www.foxmail.com'
    };
//=========================================================showloading=========================================//
function showLoading(content){
    jQuery('.activity_pane').showLoading({
        'addClass': 'loading-indicator-bars'
    });
    $(".loading-indicator-bars").html(content);
}
//=========================================================QQ=========================================//
function qqLogin(){
    location.href = "https://graph.qq.com/oauth2.0/authorize?state=test&response_type=code&client_id=100486041&redirect_uri=http://www.creamnote.com/core/wxc_user_manager/qq_back_func";
}
//=========================================================renren=========================================//
function renrenLogin(){
    location.href = "https://graph.renren.com/oauth/authorize?client_id=caa721480d9c474cb6c62fbd3a59705e&redirect_uri=http://www.creamnote.com/core/wxc_user_manager/renren_back_func&response_type=code";
}
//=========================================================weibo=========================================//
function weiboLogin(){
    location.href = "https://api.weibo.com/oauth2/authorize?client_id=1899806133&response_type=code&redirect_uri=http://www.creamnote.com/core/wxc_user_manager/weibo_back_func";
}
//=========================================================弹出登录框=========================================//
function show_login_win(){
    $("#login_win").css("display","block");
    $('html,body').animate({scrollTop: '0px'}, 800);
}
//=========================================================提交投诉信息=========================================//
var g_title;
var g_link;
var g_note_name;
var g_user_email;
var g_describe;
function submit_compliant(){
    var com_title = $("#com_title").val();
    var com_link = $("#com_link").val();
    var com_note_name = $("#com_note_name").val();
    var com_user_email = $("#com_user_email").val();
    var com_user_phone = $("#com_user_phone").val();
    var com_describe = $("#com_describe").val();

    var url = $("#baseUrl").val()+"primary/wxc_feedback/report_commit";
    var params =({'com_title':com_title,'com_link':com_link,'com_note_name':com_note_name,'com_user_email':com_user_email,'com_user_phone':com_user_phone,'com_describe':com_describe});
    var retData ;
    if(check_com_title($("#com_title").val())&&
        check_com_link($("#com_link").val())&&
        check_com_note_name($("#com_note_name").val())&&
        check_com_user_email($("#com_user_email").val())&&
        check_com_describe($("#com_describe").val()))
        {
        retData = ajax_common(url,params);
    }else{

    }

    if(retData == "success"){
        if(confirm("提交成功")){
            location.href = $("#baseUrl").val()+"primary/wxc_feedback/report_page";
        }else{
            location.href = $("#baseUrl").val()+"primary/wxc_feedback/report_page";
        }
    }else{

    }
}
function check_com_title(title){
    g_title = title;
  if(title==""){
    $("#error_com_title").html("标题不能为空");
    $("#error_com_title").css("display","block");
    return false;
  }else{
    $("#error_com_title").css("display","none");
    return true;
  }
}
function check_com_link(link){
    g_link = link;
  if(link==""){
    $("#error_com_link").html("链接不能为空");
    $("#error_com_link").css("display","block");
    return false;
  }else{
    $("#error_com_link").css("display","none");
    return true;
  }
}
function check_com_note_name(note_name){
    g_note_name = note_name;
  if(note_name==""){
    $("#error_com_note_name").html("填写好正确的笔记名称");
    $("#error_com_note_name").css("display","block");
    return false;
  }else{
    $("#error_com_note_name").css("display","none");
    return true;
  }
}
function check_com_user_email(user_email){
    g_user_email = user_email;
  if(user_email==""){
    $("#error_com_user_email").html("输入你的邮箱号，便于我们联系你");
    $("#error_com_user_email").css("display","block");
    return false;
  }else{
    $("#error_com_user_email").css("display","none");
    return true;
  }
}
function check_com_describe(describe){
    g_describe = describe;
  if(describe==""){
    $("#error_com_describe").html("描述不能为空");
    $("#error_com_describe").css("display","block");
    return false;
  }else{
    $("#error_com_describe").css("display","none");
    return true;
  }
}
//=========================================================登出=========================================//
function logout(){
    alert($("#baseUrl").val()+"home/logout")
    location.href = $("#baseUrl").val()+"home/logout";
}

//=========================================================查询收藏=========================================//
function show_all_collect(){
    var url = $("#baseUrl").val()+"core/wxc_user_manager/show_collect_data";
    var params =({});
    var retData = ajax_common_json(url,params);
    var str = "";
    str+="<div class='_grgh'>收藏夹</div>";
    str+="<div class='filter _grgh' style='float:right;'>";
    str+="<div class='fl'><a href='"+$("#baseUrl").val()+"home/personal' style='cursor: pointer;'>返回</a></div>"
    str+="</div>";
    $("._data_title").html(str);
    str ="";
    str +="<div class='_card_total_common'>";
    var count=1;
    for(i in retData){
        count++;
        str+="<div id='collect_"+retData[i]['data_id']+"'>";
        str+="<div class='collect_section fl'>";
        str+="<div class='_card_page fl' style='padding-right: 10px;'>";
        if(retData[i]['data_type'] == "doc"||retData[i]['data_type'] == "docx"){
            str += "<img src='/application/frontend/views/resources/images/version/doc_icon.png'>";
          }else if(retData[i]['data_type'] == "ppt"||retData[i]['data_type'] == "pptx"){
            str += "<img src='/application/frontend/views/resources/images/version/ppt_icon.png'>";
          }else if(retData[i]['data_type'] == "xls"||retData[i]['data_type'] == "xlsx"){
            str += "<img src='/application/frontend/views/resources/images/version/xls_icon.png'>";
          }else if(retData[i]['data_type'] == "txt"){
            str += "<img src='/application/frontend/views/resources/images/version/txt_icon.png'>";
          }else if(retData[i]['data_type'] == "pdf"){
            str += "<img src='/application/frontend/views/resources/images/version/pdf_icon.png'>";
          }
        str+="</div>";
        str+="<div>";
        str+="<a target='_blank' href='"+$("#baseUrl").val()+"data/wxc_data/data_view/"+retData[i]['data_id']+"'>"+retData[i]['data_name']+"</a>";
        str+="</div>";
        str+="<div class='collect_detail'>";
        str+="<slice class='card_star fl' ><slice style='top: -3px;position: relative;' class='card_star_blod' >";
        str+="<input type='hidden' name='data_point' id='data_point' value='"+retData[i]['data_point']+"'>";
        str+="</slice></slice>|";
        str+="<span>"+retData[i]['data_price']+"</span>|<span>"+retData[i]['data_uploadtime']+"</span>|<span>共"+retData[i]['data_pagecount']+"页</span><span>作者:"+retData[i]['user_name']+"</span>";
        str+="</div>";
        str+="</div>";

        str+="<div class='uncollect_section fl'>";
        str+="<span onclick='uncollect_p("+retData[i]['data_id']+")'>取消收藏</span>";
        str+="</div>";
        str+="</div>";

    }
    str+="</div>";
    var collect_height = count*61+100;
    $("#buttons").animate({
                    height: collect_height
                });
    $("#buttons").html(str);

    $("[name='data_point']").each(function(){
    var data_point = this.value;
    var star_width = data_point*10 +"px";
    $(this).parent(".card_star_blod").css("padding-left",star_width);
    $(this).parent(".card_star_blod").css("padding-right",star_width);
});
}

//=========================================================个人页面取消收藏=========================================//
function uncollect_p(data_id){

  var url =$("#baseUrl").val()+"core/wxc_user_manager/del_collect_data";
  $.ajax({
    type:"post",
    data:({'collect_data_id': data_id}),
    url:url,
    //dataType:"json",
    success: function(result)
        {
          if(result!=''){
              if(result == "success"){
                $("#collect_"+data_id).css("display","none")
              }else{

              }
          }

        },
       error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.status);
            alert(XMLHttpRequest.readyState);
            alert(textStatus);
        }
    });

}
//=========================================================跳到资料上传页面=========================================//
function jump_to_uploadfile(){
    if(loginname!=""){
        location.href = $("#baseUrl").val()+"home/data_upload_page";
    }else{
        show_login_win();
    }
}
function jump_to_uploadimage(){
    if(loginname!=""){
        location.href = $("#baseUrl").val()+"home/image_upload_page";
    }else{
        show_login_win();
    }
}

//=========================================================收藏=========================================//
function collect(ser,data_id){
  if(loginname == ""){
      $("#login_win").css("display","block");
      $('html,body').animate({scrollTop: '0px'}, 800);
    }else{
      var url =$("#baseUrl").val()+"core/wxc_user_manager/collect_data";
      $.ajax({
        type:"post",
        data:({'collect_data_id': data_id}),
        url:url,
        //dataType:"json",
        success: function(result)
            {
              if(result!=''){
                  if(result == "success"){
                      $("#collect_"+ser+data_id).poshytip('show');
                      $("#collect_"+ser+data_id).html("<a id='col"+data_id+"' href='javascript:void(0)' onclick='uncollect("+ser+","+data_id+")'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_store_red.png' class='_card_edit fl' title='取消收藏'></a>");
                  }else{
                      $("#collect_"+ser+data_id).poshytip('show');
                      $("#collect_"+ser+data_id).html("<a id='col"+data_id+"' href='javascript:void(0)' onclick='uncollect("+ser+","+data_id+")'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_store_red.png' class='_card_edit fl' title='取消收藏'></a>");
                  }
              }

            },
           error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            }
        });
    }
}

//=========================================================取消收藏=========================================//
function uncollect(ser,data_id){
  if(loginname == ""){
      $("#login_win").css("display","block");
      $('html,body').animate({scrollTop: '0px'}, 800);
    }else{
      var url =$("#baseUrl").val()+"core/wxc_user_manager/del_collect_data";
      $.ajax({
        type:"post",
        data:({'collect_data_id': data_id}),
        url:url,
        //dataType:"json",
        success: function(result)
            {
              if(result!=''){
                  if(result == "success"){
                      $("#collect_"+ser+data_id).html("<a id='col"+data_id+"' href='javascript:void(0)' onclick='collect("+ser+","+data_id+")'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_store.png' class='_card_edit fl' title='收藏'></a>");
                  }else{
                      $("#collect_"+ser+data_id).poshytip('show');
                      $("#collect_"+ser+data_id).html("<a id='col"+data_id+"' href='javascript:void(0)' onclick='collect("+ser+","+data_id+")'><img src='/application/frontend/views/resources/images/new_version/dy_card_hover_store.png' class='_card_edit fl' title='收藏'></a>");
                  }
              }

            },
           error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            }
        });
    }
}
//=========================================================下载资料=========================================//
function download_notes(data_id){
  if(loginname == ""){
      $("#login_win").css("display","block");
      $('html,body').animate({scrollTop: '0px'}, 800);
    }else{
      location.href = $("#baseUrl").val()+"core/wxc_download_note/download_file/"+data_id;

    }
}

//=========================================================关闭弹框=========================================//
function close_message_dialog(){
    $('#box').animate({'top':'-900px'},500,function(){
            $('#overlay').fadeOut('fast');
        });
}
//=========================================================关闭留言tip=========================================//
function hidden_mess_tip(){
    $("#message_content").poshytip('hide');
}

