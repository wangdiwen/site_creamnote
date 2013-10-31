
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
//=========================================================滚动至顶部=========================================//
$("#updown").css("top",window.screen.availHeight/2+50+"px");
$(window).scroll(function() {
        if($(window).scrollTop() >= 100){
            $('#updown').fadeIn(300);
        }else{
            $('#updown').fadeOut(300);
        }
    });
$('#updown .up').click(function(){$('html,body').animate({scrollTop: '0px'}, 800);});
$('#updown .down').click(function(){$('html,body').animate({scrollTop: document.body.clientHeight+'px'}, 800);});
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
    $(".buy_section").live({
        mouseenter:function(){
            $(this).find('._buy_history').css("display","block");
        },
        mouseleave:function(){
            $(this).find('._buy_history').css("display","none");
        }
    });
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
//=========================================================用户信息tips=========================================//
    $('.hoveruser').poshytip({
        className: 'nothing',
        offsetY: 30,
        offsetX: -140,
        content: function(updateCallback) {
          var user_id = this.id;
          var url =$("#baseUrl").val()+"primary/wxc_personal/personal_base_tips";
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
    warnMes("需要先登录的，亲！");
}
//=========================================================用户卡片函数=========================================//
function hover_user(){
    $('.hoveruser').poshytip({
        className: 'nothing',
        offsetY: 30,
        offsetX: -140,
        content: function(updateCallback) {
          var user_id = this.id;
          var url =$("#baseUrl").val()+"primary/wxc_personal/personal_base_tips";
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
        var title = "投诉举报";
        var content = "你的投诉内容已经提交给管理员<br/>两秒后自动关闭该窗口";
        var url = $("#baseUrl").val()+"primary/wxc_feedback/report_page";
        showDialog(title,content,url);
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
                  }else if(result == "failed"){
                    $("#login_win").css("display","block");
                    $('html,body').animate({scrollTop: '0px'}, 800);
                    warnMes("请先登录");
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
      warnMes("亲，要先登录哦！");
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
//=========================================================提示框message=========================================//
function successMes(mes,px,py){
    var suc = '<table width="100%" height="100%"><tr><td width="10%"><img src="/application/frontend/views/resources/images/success.png"/></td><td><b style="font-size:16pt;">'+mes+'</b></td></tr><table>';
    message(suc,px,py);
}

function errorMes(mes,px,py){
    var error = '<table width="100%" height="100%"><tr><td width="10%"><img src="/application/frontend/views/resources/images/error.png"/></td><td><b style="font-size:16pt;">'+mes+'</b></td></tr><table>';
    message(error,px,py);
}

function warnMes(mes,px,py){
    var warn = '<table width="100%" height="100%"><tr><td width="10%"><img src="/application/frontend/views/resources/images/warning.png"/></td><td><b style="font-size:16pt;">'+mes+'</b></td></tr><table>';
    message(warn,px,py);
}

function message(mes,px,py){
    if(!px){
        px = ($(window).width() - 600) /2 + 'px';
    }
    if(!py){
        py = '73px';
    }
    $.blockUI({
            message: mes,
            fadeIn: 700,
            fadeOut: 700,
            timeout: 5000,
            showOverlay: false,
            centerY: false,
            css: {
                top: py,
                left: px,
                width: '530px' ,
                border: 'none',
                padding: '5px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                'border-radius': '10px',
                opacity: .7,
                color: '#fff'
            }
        });
}
//=========================================================提示框替代alert=========================================//

function showDialog(header,content,url){
    var btnFn = function( e ){
        location.href = url;
        return true;
    };
    easyDialog.open({
        container : {
            header : header,
            content : content,
            yesFn : btnFn,
            noFn : false
        },
        fixed : true,
        autoClose : 2000,
        callback : btnFn
    });
}

//=========================================================学校=========================================//
//弹出窗口
var comm_pop = function(){
  //将窗口居中
  comm_makeCenter();

  //初始化省份列表
  comm_initProvince();

  //默认情况下, 给第一个省份添加choosen样式
  $('[province-id="1"]').addClass('choosen');

  //初始化大学列表
  comm_initSchool(1);
}
//隐藏窗口
function hide_home()
{
  $('#choose-box-wrapper').css("display","none");
  $("#search-text").focus();
}

function comm_initProvince()
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
      comm_initSchool(province);
    }
  );
}
var wx_school='';
function comm_initSchool(provinceID)
{
  var schools = schoolList[provinceID-1].school;
  $('#choose-a-school').html("");
  for(i=0;i<schools.length;i++)
  {
    $('#choose-a-school').append('<a class="school-item" school-id="'+schools[i].id+'">'+schools[i].name+'</a>');
  }
     //添加大学列表项的click事件
  $('.school-item').bind('click', function(){
      var item=$(this);
      wx_school=item.text()
      $("#search-text").attr({
        "value": wx_school
      });
      //关闭弹窗
      hide_home();
      $("#search-text").focus();
    }
  );
  // return wx_school;
}

function comm_makeCenter()
{
  $('#choose-box-wrapper').css("display","block");
  $('#choose-box-wrapper').css("position","absolute");
  $('#choose-box-wrapper').css("top", Math.max(0, (($(window).height() - $('#choose-box-wrapper').outerHeight()) / 2) + $(window).scrollTop()) + "px");
  $('#choose-box-wrapper').css("left", Math.max(0, (($(window).width() - $('#choose-box-wrapper').outerWidth()) / 2) + $(window).scrollLeft()) + "px");
}

function init_home_school(){
  var wx_school= comm_pop();

}
//=========================================================token=========================================//
var set_token_input = function(){
  $("#account_token").css("display","inline-block");
  $("#token_button").attr("onclick","account_set_token()");
  $("#token_button").attr("value","提交");
  $("#account_token_tip").css("display","none");
}
function account_set_token(){
  var url = $("#baseUrl").val()+"core/wxc_user_account/require_withdraw_token";
  var params =({'withdraw_token':$("#account_token").val()});
  var retData ="";
  if(!isNaN( $("#account_token").val() )&&$("#account_token").val().length == 6){
     retData = ajax_common(url,params);
  }else{
    errorMes("口令需要为6位的纯数字");
  }


  if(retData == "invalid-token"){
    errorMes("口令需要为6位的纯数字");
  }else if(retData == "failed"){
    errorMes("口令设置失败");
  }else if(retData == "success"){
    // successMes("验证码已发送到您注册邮箱，请验证");
    // $("#token_ver").css("display","block");
    var title = "验证码已发送到您注册邮箱，请验证";
    var content = "验证码：<input type='password' id='token_ver_value' value='' onblur='ver_token(this)' style='width: 216px;'>";
    var btnFn = function( e ){
        ver_token_frommail();
          // easyDialog.close();
      };
    easyDialog.open({
          container : {
              header : title,
              content : content,
              noColse : "no_close",
              yesFn : btnFn,
              noFn : false
          },
          fixed : true,
      });
  }
}

var ver_parm = "";
function ver_token_frommail(){
  ver_parm = $("#token_ver_value").val();
  var url = $("#baseUrl").val()+"core/wxc_user_account/record_withdraw_token";
  var params =({'auth_code_token':ver_parm});
  var retData ="";
  if(ver_parm != ''){
    retData = ajax_common(url,params);
  }else{
    errorMes("验证码不能为空")
  }
  if(retData == "success"){
    successMes("验证成功");
    $("#token_ver").css("display","none");
    $("#token_button").attr("value","更改口令");
    $("#account_token").css("display","none");
    $("#token_button").attr("onclick","set_token_input()");
    $("#account_token_tip").html("口令已设置");
    $("#account_token_tip").css("display","inline-block");
    easyDialog.close();
  }else if(retData == "failed"){
    errorMes("验证失败");
  }
}

function ver_token(obj){
  ver_parm = obj.value;
}

var start_withdraw = function(){
  var url = $("#baseUrl").val()+"core/wxc_user_account/require_withdraw_order";
  var withdraw_count = $("#withdraw_count").val();
  var withdraw_token = $("#withdraw_token").val();
  var params =({'withdraw_money':withdraw_count*10,'withdraw_token':withdraw_token});
  var retData ="";
  if(!isNaN(withdraw_count)){
    retData = ajax_common(url,params);
  }else{
    errorMes("请选择提现金额")
  }
  if(retData == "failed"){
    errorMes("提现申请失败");
  }else if(retData == "has-withdraw"){
    errorMes("已经处于提现受理阶段，无法重复提现");
  }else if(retData == "no-money"){
    errorMes("账户余额不足，大于10.00RMB才可以提现");
  }else if(retData == "no-actived"){
    errorMes("账户没有激活，不可使用提现功能");
  }else if(retData == "no-token"){
    errorMes("没有设置提醒口令");
  }else if(retData == "wrong-money"){
    errorMes("你提交申请的金额不合法");
  }else if(retData == "wrong-token"){
    errorMes("口令出错");
  }else{
    successMes("申请成功，我们会尽快处理您的这次提现");
    $("#user_account_money_leave").html("￥"+retData);
    $("#user_account_name").attr('disabled','disabled');
    $("#update_account").attr("onclick","update_account(3)");
    $("#user_account_status").html("提现受理中");
  }
}
var show_token_window = function(){
  if(isNaN($("#withdraw_count").val())){
    errorMes("请选择提现金额");
    return;
  }
  var title = "申请提现";
  var content = "口令：<input type='password' id='withdraw_token' value='' style='width: 220px;'>";
  var btnFn = function( e ){
      start_withdraw();
        // easyDialog.close();
    };
  easyDialog.open({
        container : {
            header : "申请提现",
            content : content,
            yesFn : btnFn,
            noFn : false
        },
        fixed : true,
    });
}
//=========================================================buy=========================================//
var buy_one_note = function(type){
  var url = $("#baseUrl").val()+"core/wxc_download/pay_download_file";
  var note_id = $("#note_id").val();
  var note_price = $("#note_price").val();
  var diff_money = $("#diff_money").val();
  var note_name = $("#note_name").val();
  var note_own_user_id = $("#note_own_user_id").val();
  var user_account_money = $("#user_account_money").val();
  // var params =({'note_id':note_id,'note_price':note_price,'diff_money':diff_money,'note_name':note_name,'user_account_money':user_account_money,'note_own_user_id':note_own_user_id});
  // var retData = ajax_common(url,params);
  if(type==1){
    // window.close();
    $("#pay_form").attr("target","_self")
  }
  $("#pay_form").submit();
  if(type==2){
    // easyDialog.close();
    //window
    var content = "<div style='text-align:center;'>请在新打开页面完成支付</div>";
    var btnFn_y = function( e ){
        pay_success();
      };
    var btnFn_n = function( e ){
      pay_problem();
    };
    easyDialog.open({
        container : {
            header : "笔记购买",
            content : content,
            yesFn : btnFn_y,
            noColse : "no_close",
            yesText : "支付完成",
            noFn : btnFn_n,
            noText : "支付遇见问题"
        },
        fixed : true,
    });
  }

}

function buy_one_again(){
  $("#pay_again_form").submit();

  // easyDialog.close();
  //window
  var content = "<div style='text-align:center;'>请在新打开页面完成支付</div>";
  var btnFn_y = function( e ){
      pay_success();
    };
  var btnFn_n = function( e ){
    pay_problem();
  };
  easyDialog.open({
      container : {
          header : "笔记购买",
          content : content,
          yesFn : btnFn_y,
          noColse : "no_close",
          yesText : "支付完成",
          noFn : btnFn_n,
          noText : "支付遇见问题"
      },
      fixed : true,
  });

}

var pay_problem = function(){
  window.open($('#baseUrl').val()+"static/wxc_help/skills#buy_notes","_blank");
}

var pay_success = function(){
  var url = $("#baseUrl").val()+"core/wxc_alipay/check_pay_ok";
  var note_id = $("#note_id").val();
  var params =({'note_id':note_id});
  var retData = ajax_common(url,params);
  // var retData = "failed";
  if(retData == "not-pay-over"){
    // easyDialog.close();
    //window
    var content = "<div style='text-align:center;'>支付未完成，请重新支付</div>";
    var btnFn_y = function( e ){
        buy_one_again();
      };
    var btnFn_n = function( e ){
      pay_problem();
    };
    easyDialog.open({
        container : {
            header : "笔记购买",
            content : content,
            noColse : "no_close",
            yesFn : btnFn_y,
            yesText : "重新支付",
            noFn : btnFn_n,
            noText : "支付遇见问题"
        },
        fixed : true,
    });
  }else if(retData == "pay-over"){
    location.href = $('#baseUrl').val()+"core/wxc_alipay/require_download_direct";
  }else if(retData == "has-downloaded"){
    location.href = $('#baseUrl').val()+"core/wxc_alipay/fast_pay_download_fail";
  }
}

var order_history = function(){
    var url = $("#baseUrl").val()+"core/wxc_download_note/pay_order_history";
    var params =({});
    var retData = ajax_common_json(url,params);
    var str = "";
    str+="<div class='_grgh'>订单记录</div>";
    str+="<div class='filter _grgh' style='float:right;'>";
    str+="<div class='fl'><a href='"+$("#baseUrl").val()+"home/personal' style='cursor: pointer;'>返回</a></div>"
    str+="</div>";
    var str2 = "";
    str2+="<div class='_grgh'>购买记录</div>";
    str2+="<div class='filter _grgh' style='float:right;'>";
    str2+="<div class='fl'><a href='"+$("#baseUrl").val()+"home/personal' style='cursor: pointer;'>返回</a></div>"
    str2+="</div>";
    $("._data_title").html(str);
    str ="";
    str +="<div class='_card_total_common'>";
    str +="<div style='border-bottom: 1px dotted #000'>成功订单</div>";
    str2 ="";
    str2 +="<div class='_card_total_common'>";
    str2 +="<div style='border-bottom: 1px dotted #000'>未完成订单</div>";
    var count=1;
    if(retData == "no-record"){
          str+="";
        }else if(retData == "disconnected"){
          str+="";
        }else{
          for(i in retData){
            count++;

            if(retData[i]['pay_status'] =="true"){
              str+="<div id='buy_"+retData[i]['pay_id']+"'>";
              str+="<div class='collect_section buy_section fl' style='margin-right: 20px;min-width: 350px;'>";
              // str+="<div class='_buy_history'>";
              // str+="<a href='"+$("#baseUrl").val()+"'>";
              // str+="<img src='/application/frontend/views/resources/images/new_version/dy_card_hover_down.png'>";
              // str+="</a>";
              // str+="</div>";
              str+="<div>";
              str+="<a target='_blank' href='"+retData[i]['pay_show_url']+"'>"+retData[i]['pay_body']+"</a>";
              str+="</div>";
              str+="<div class='collect_detail'>";
              str+="<span>￥"+retData[i]['pay_total_fee']+"</span>|";
              if(retData[i]['pay_way'] == 0){
                str+="<span>余额支付</span>"
              }else if(retData[i]['pay_way'] == 1){
                str+="<span>支付宝全额支付</span>"
              }else if(retData[i]['pay_way'] == 2){
                str+="<span>余额+支付宝支付</span>"
              }
              str+="|<span>"+retData[i]['pay_timestamp']+"</span>";
              str+="</div>";
              str+="</div>";
              str+="</div>";
            }else{
              str2+="<div id='collect_"+retData[i]['pay_id']+"'>";
              str2+="<div class='collect_section buy_section fl' style='margin-right: 20px;min-width: 350px;'>";
              // str2+="<div class='_buy_history'>";
              // str2+="<a href='"+$("#baseUrl").val()+"'>";
              // str2+="<img src='/application/frontend/views/resources/images/new_version/dy_card_hover_down.png'>";
              // str2+="</a>";
              // str2+="</div>";
              str2+="<div>";
              str2+="<a target='_blank' href='"+retData[i]['pay_show_url']+"'>"+retData[i]['pay_body']+"</a>";
              str2+="</div>";
              str2+="<div class='collect_detail'>";
              str2+="<span>￥"+retData[i]['pay_total_fee']+"</span>|";
              if(retData[i]['pay_way'] == 0){
                str2+="<span>余额支付</span>"
              }else if(retData[i]['pay_way'] == 1){
                str2+="<span>支付宝全额支付</span>"
              }else if(retData[i]['pay_way'] == 2){
                str2+="<span>余额+支付宝支付</span>"
              }
              str2+="|<span>"+retData[i]['pay_timestamp']+"</span>";
              str2+="</div>";
              str2+="</div>";
              str2+="</div>";
            }

          }
      }
    str+="</div>";
    var collect_height = count*61/2+100;
    $("#buttons").animate({
                    height: collect_height
                });
    $("#buttons").html(str+str2);

}

var free_history = function(){
    var url = $("#baseUrl").val()+"core/wxc_download_note/free_download_history";
    var params =({});
    var retData = ajax_common_json(url,params);
    var str = "";
    str+="<div class='_grgh'>免费下载记录</div>";
    str+="<div class='filter _grgh' style='float:right;'>";
    str+="<div class='fl'><a href='"+$("#baseUrl").val()+"home/personal' style='cursor: pointer;'>返回</a></div>"
    str+="</div>";
    $("._data_title").html(str);
    str ="";
    str +="<div class='_card_total_common fl'>";
    var count=1;
    if(retData == "no-record"){
          str+="";
        }else if(retData == "disconnected"){
          str+="";
        }else{
           for(i in retData){
            count++;

            str+="<div id='free_"+retData[i]['data_id']+"'>";
            str+="<div class='collect_section fl' style='margin-right: 20px;min-width: 266px;'>";
            str+="<div>";
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
            str+="<div style='width: 260px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;'>";
            str+="<a target='_blank' href='"+$("#baseUrl").val()+"data/wxc_data/data_view/"+retData[i]['data_id']+"'>"+retData[i]['data_name']+"</a>";
            str+="</div>";
            str+="</div>";
            str+="<div class='collect_detail'>";
            str+="<span style='padding:0'>上传时间："+retData[i]['data_uploadtime']+"</span>";
            str+="</div>";
            str+="</div>";
            str+="</div>";
          }
        }

    str+="</div>";
    var collect_height = count*61/2+100;
    $("#buttons").animate({
                    height: collect_height
                });
    $("#buttons").html(str);

}

var buy_history = function(){
      var url = $("#baseUrl").val()+"core/wxc_download_note/pay_download_history";
    var params =({});
    var retData = ajax_common_json(url,params);
    var str = "";
    str+="<div class='_grgh'>购买记录</div>";
    str+="<div class='filter _grgh' style='float:right;'>";
    str+="<div class='fl'><a href='"+$("#baseUrl").val()+"home/personal' style='cursor: pointer;'>返回</a></div>"
    str+="</div>";
    $("._data_title").html(str);
    str ="";
    str +="<div class='_card_total_common fl'>";
    var count=1;
    if(retData == "no-record"){
          str+="";
        }else if(retData == "disconnected"){
          str+="";
        }else{
           for(i in retData){
            count++;

            str+="<div id='free_"+retData[i]['data_id']+"'>";
            str+="<div class='collect_section buy_section fl' style='margin-right: 20px;min-width: 266px;'>";
            str+="<div class='_buy_history'>";
            str+="<a href='"+$("#baseUrl").val()+"core/wxc_download_note/download_have_payed_note?note_id="+retData[i]['data_id']+"'>";
            str+="<img src='/application/frontend/views/resources/images/new_version/dy_card_hover_down.png'>";
            str+="</a>";
            str+="</div>";
            str+="<div>";
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
            str+="<div style='width: 260px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;'>";
            str+="<a target='_blank' href='"+$("#baseUrl").val()+"data/wxc_data/data_view/"+retData[i]['data_id']+"'>"+retData[i]['data_name']+"</a>";
            str+="</div>";
            str+="</div>";
            str+="<div class='collect_detail'>";
            str+="<span style='padding:0'>上传时间："+retData[i]['data_uploadtime']+"</span>";
            str+="</div>";
            str+="</div>";
            str+="</div>";
          }
        }

    str+="</div>";
    var collect_height = count*61/2+100;
    if(collect_height>180){
       $("#buttons").animate({
                    height: collect_height
                });
    }

    $("#buttons").html(str);

}

//=========================================================checkeamil=========================================//
var com_check_eamil = function(strEmail){
  if (strEmail.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1){
    return true;
  }else{
    return false;
  }
}
