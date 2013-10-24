//=========================================================jquery ui 弹出窗口 dialog=========================================//
$(function() {
    //普通确认窗口
   $( "#dialog" ).dialog({
        autoOpen: false,
        show: "blind",
        hide: "explode",
        modal: true,
        buttons: { "确定": function() { $(this).dialog("close"); } }
    });
   $( "#dialog_auto" ).dialog({
        autoOpen: false,
        show: "blind",
        hide: "explode",
    });
});
function show_dialog(title,content){
    $("#dialog_auto").attr("title",title)
    $("#dialog_auto_content").html(content);
    $( "#dialog_auto" ).dialog("open");
    setTimeout("hide_dialog()",1500);
}
function hide_dialog(){
    $( "#dialog_auto" ).dialog("close");
}
//=========================================================登录页面=========================================//

function login(){
    var url = $("#baseurl").val()+"cnadmin/home/check_login";
    var usename = $("#admin_email").val();
    var pwd = $("#admin_password").val();
    var auth_code = $("#admin_auth_code").val();
    if(usename == ""){
        $("#error_mail").html("邮箱不能为空");
    }else if(pwd == ""){
        $("#error_pwd").html("密码不能为空");
    }else{
        $.ajax({
            type:"post",
            data:({'admin_email': usename,'admin_password':pwd,'admin_auth_code':auth_code}),
            url:url,
            //dataType:"json",
            success: function(result)
            {
                if(result == "login-success"){
                    $("#loginForm").submit();
                }else if(result == "login-no-user"){
                    $("#error_mail").html("不存在该账户");
                    $("#admin_email").html("");
                }else if(result == "login-password-wrong"){
                    $("#error_pwd").html("密码错误");
                    $("#admin_password").html("");
                }else if(result == "login-authcode-wrong"){
                    $("#error_code").html("验证码错误");
                    $("#admin_auth_code").html("");
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
//=========================================================用户页面=========================================//
//查询用户
function user_search(url){
    var search_content = $("#user_email_or_name").val();
    var user_email_or_name = ({'user_email_or_name':search_content});
    ajax_common(url,user_email_or_name);
    setTimeout("show_user_ret($('#ret_common_json').val())",500);
}
function show_user_ret(data){
    var data = $.parseJSON(data);
    $("#result_dis").css("display","block");

    $("#user_name").html(data['user_name']);
    $("#user_email").html(data['user_email']);
    $("#user_reg").html(data['user_register_time']);
    if(!data['user_period']){
        $("#user_period").html("空");
    }else{
        $("#user_period").html(data['user_period']);
    }
    $("#user_status").html(data['user_status']);
    $("#user_id").attr("data-id",data['user_id']);
    if(data['user_status'] == "false"){
        $("#disable_user").attr("value","解封");
        $("#disable_user").attr("onclick","enable_user()");
    }else{
        $("#disable_user").attr("value","封号");
        $("#disable_user").attr("onclick","disable_user()");
    }
}
//用户详情
function show_user_detail(){
    var user_email = $("#user_email").text();
    var url = $("#baseUrl").val()+"cnadmin/user/get_user_detail";
    var params = ({'user_email':user_email});
    var data = ajax_common_json(url,params);
    // setTimeout("show_userdetail_ret($('#ret_common_json').val())",500);
    // var data = $.parseJSON(data);
    var str = "";
    str +="昵称:"+data['user_name']+"<br/>";
    str +="邮箱:"+data['user_email']+"<br/>";
    str +="兴趣爱好:"+data['user_hobby']+"<br/>";
    str +="ID:"+data['user_id']+"<br/>";
    str +="届:"+data['user_period']+"<br/>";
    str +="注册时间:"+data['user_register_time']+"<br/>";
    str +="学校:"+data['carea_name_shool']+"<br/>";
    str +="专业:"+data['carea_name_major']+"<br/>";
    str +="账户:"+data['user_account_name']+"<br/>";
    str +="账户类型:"+data['user_account_type']+"<br/>";
    str +="账户状态:"+data['user_account_active']+"<br/>";

    str +="微博:"+data['user_weibo_nicename']+"<br/>";
    str +="人人:"+data['user_renren_nicename']+"<br/>";
    str +="QQ:"+data['user_qq_nicename']+"<br/>";
    str +="用户状态:"+data['user_status']+"<br/>";
    $("#user_deatil").html(str);
    // alert(str)
}
//封号
function disable_user(){
    if(confirm("确定封号吗？")){
        var url = $("#baseUrl").val()+"cnadmin/user/disable_user";
        var params = ({'user_email':$("#user_email").text()});
        ajax_common(url,params);
        setTimeout("show_disable_ret($('#ret_common_json').val())",500);
    }else{
        //
    }
}
function show_disable_ret(data){
    if(data == "success"){
        alert("操作成功");
        $("#disable_user").attr("value","解封");
        $("#disable_user").attr("onclick","enable_user()");
    }else if(data == "no-permission"){
        alert("你没有权限");
    }else{
        alert("操作失败");
    }
}

//解除封号
function enable_user(){
    if(confirm("确定解除封号吗？")){
        var url = $("#baseUrl").val()+"cnadmin/user/enable_user";
        var params = ({'user_email':$("#user_email").text()});
        ajax_common(url,params);
        setTimeout("show_enable_ret($('#ret_common_json').val())",500);
    }else{
        //
    }
}
function show_enable_ret(data){
    if(data == "success"){
        alert("操作成功");
        $("#disable_user").attr("value","封号");
        $("#disable_user").attr("onclick","disable_user()");
    }else if(data == "no-permission"){
        alert("你没有权限");
    }else{
        alert("操作失败");
    }
}
//根据时间段检索用户数
function search_by_time(){
    var url = $("#baseUrl").val()+"cnadmin/user/stat_register_count";
    var start_time = $("#start_time").val();
    var end_time = $("#end_time").val();
    var params = ({'start_time':start_time,'end_time':end_time});
    var retData = ajax_common(url,params);
    $("#result_dis_time").css("display","block");
    $("#tab5").html("<hr/>用户数:"+retData);

}
//=========================================================管理员页面=========================================//
//重置密码
var admin_email;
var admin_password;
function reset_admin_pwd_ms(eamil){
    $("#user_deatil").html("新密码:<input type='text' name='admin_new_pwd' onblur='trans_pwd(this)' id='admin_new_pwd' value=''/><input type='button' onclick='reset_admin_pwd()' value='修改' class='button'/>");
    $("#message_title").html("修改密码");
    admin_email = eamil;
}
function trans_pwd(the){
    admin_password = the.value;
}
function reset_admin_pwd(){
    var url = $("#baseUrl").val()+"cnadmin/user/update_admin_password";
    // var password = $("#admin_new_pwd").val();
    var password = admin_password;
    alert(admin_email)
    alert(password)
    var params = ({'user_password':password,'user_email':admin_email});
    var retData = ajax_common(url,params);
    if(retData == "success"){
        alert("修改成功");
    }else{
        alert("修改失败");
    }
}

//删除用户
function delete_admin_user(email){
    var url = $("#baseUrl").val()+"cnadmin/user/del_admin_user?user_email="+email;
    if(confirm("确定删除账号吗？")){
        location.href = url;
    }
}

//修改用户
var g_user_name;
var g_user_token;
var g_user_status;
var g_user_type;
var g_user_email;
function update_admin_ms(user_name,user_token,user_status,user_type,user_email){
    g_user_name = user_name;
    g_user_token = user_token;
    g_user_status = user_status;
    g_user_type = user_type;
    g_user_email = user_email;
    var str = "";
    str += "昵称:<input type='text' onblur='javascript:g_user_name=this.value' name='nice_name'  value='"+user_name+"' id='nice_name'><br/>";
    str += "口令:<input type='text' onblur='javascript:g_user_token=this.value' name='user_token' value='"+user_token+"' id='user_token'><br/>";
    if(user_status == "true"){
        str += "状态:<select id='user_status' onchange='javascript:g_user_status=this.value;'><option value='true' selected>启用</option><option value='false'>停用</option></select><br/>";
    }else{
        str += "状态:<select id='user_status' onchange='javascript:g_user_status=this.value;'><option value='true'>启用</option><option value='false' selected>停用</option></select><br/>";
    }
    if(user_type == "super"){
        str += "等级:<select id='user_type' onchange='javascript:g_user_type=this.value;'><option value='super' selected>super</option><option value='admin'>admin</option><option value='common'>common</option></select><br/>";
    }else if(user_type == "admin"){
        str += "等级:<select id='user_type' onchange='javascript:g_user_type=this.value;'><option value='super'>super</option><option value='admin' selected>admin</option><option value='common'>common</option></select><br/>";
    }else if(user_type == "common"){
        str += "等级:<select id='user_type' onchange='javascript:g_user_type=this.value;'><option value='super'>super</option><option value='admin'>admin</option><option value='common' selected>common</option></select><br/>";
    }

    str += "<input type='button' onclick='update_admin()' value='修改' class='button'/>"
    $("#user_deatil").html(str);
    $("#message_title").html("用户修改");
}
function update_admin(){
    var url = $("#baseUrl").val()+"cnadmin/user/update_admin_info";
    var params = ({'user_email':g_user_email,'user_name':g_user_name,'user_token':g_user_token,'user_status':g_user_status,'user_type':g_user_type});
    var retData = ajax_common(url,params);
    if(retData=="success"){
        // alert("修改成功");
        // location.reload();
        $("#dialog").attr("title","修改管理员资料")
         $("#dialog_content").html("修改成功");
         $( "#dialog" ).dialog("open");
         $( "#dialog" ).dialog({
            close :function(event, ui){
                location.reload();
            }
         });
    }else{
        // alert("修改失败");
        $("#dialog").attr("title","修改管理员资料")
         $("#dialog_content").html("修改失败");
         $( "#dialog" ).dialog("open");
    }
}

//新增管理员
function add_new_admin(){
    g_user_name = $("#add_user_name").val();
    g_user_token = $("#add_user_token").val();
    g_user_status = $("#add_user_status").val();
    g_user_type = $("#add_user_type").val();
    g_user_email = $("#add_user_email").val();
    var user_password = $("#add_user_password").val();
    if(g_user_name == ""){
        alert("用户名不能为空")
    }
    if(g_user_email == ""){
        alert("邮箱不能为空")
    }
    if(user_password == ""){
        alert("密码不能为空")
    }
    var url = $("#baseUrl").val()+"cnadmin/user/create_admin_user";
    var params = ({'user_email':g_user_email,'admin_name':g_user_name,'user_token':g_user_token,'user_status':g_user_status,'user_type':g_user_type,'user_password':user_password});
    var retData;
    if(g_user_name!=""&&g_user_email!=""&&user_password!=""){
        retData = ajax_common(url,params);
    }

    if(retData=="success"){
        // alert("添加成功");
        //  location.reload();
        $("#dialog").attr("title","添加管理员")
         $("#dialog_content").html("添加成功");
         $( "#dialog" ).dialog("open");
         $( "#dialog" ).dialog({
            close :function(event, ui){
                location.reload();
            }
         });
    }else{
        // alert("添加失败");
        $("#dialog").attr("title","添加管理员")
        $("#dialog_content").html("添加失败");
        $( "#dialog" ).dialog("open");

    }
}

//=========================================================内容页面=========================================//
var editor;
KindEditor.ready(function(K) {
    editor = K.create('#editor_id',
            { uploadJson : $("#baseUrl").val()+"cnadmin/article/kindeditor_upload_oss"
        });

  });

//保存文章
function save_article(){
    var content = editor.html();
    var title = $("#title").val();
    var category = $("#category").val();
    var note = $("#note").val();
    var url = $("#baseUrl").val()+"cnadmin/article/create_new_article";
    var params = ({'article_content':content,'article_title':title,'article_category':category,'article_notes':note});
    var if_can_submit = false;
    if(title == ""){
        $("#error_title").html("请输入标题")
        if_can_submit = false;
    }else{
        if_can_submit = true;
    }
    if(category == ""){
        $("#error_category").html("请输入分类")
        if_can_submit = false;
    }else{
        if_can_submit = true;
    }
    if(if_can_submit == true){
         var retData = ajax_common(url,params);
         // alert(retData);
         // location.reload();
         $("#dialog").attr("title","添加每周一文")
         $("#dialog_content").html("添加成功成功");
         $( "#dialog" ).dialog("open");
         $( "#dialog" ).dialog({
            close :function(event, ui){
                location.reload();
            }
         });
    }
}
//编辑文章
function edit_article(article_id,article_category,article_title){
    var article_content;
    var article_notes;
    var url = $("#baseUrl").val()+"cnadmin/article/modify_article";
    var params = ({'article_id':article_id});
    var retData = ajax_common_json(url,params);
    // alert(retData['article_content'])

    $("#save_button").attr("onclick","save_edit_article("+article_id+")");
    $("#category").attr("value",article_category);
    $("#title").attr("value",article_title);
    $("#note").html(retData['article_notes']);
    editor.html(retData['article_content']);
    document.body.scrollTop = document.body.scrollHeight;
}
function save_edit_article(article_id){
    var content = editor.html();
    var title = $("#title").val();
    var category = $("#category").val();
    var note = $("#note").val();
    var url = $("#baseUrl").val()+"cnadmin/article/edit_save_article";
    var params = ({'article_id':article_id,'article_content':content,'article_title':title,'article_category':category,'article_notes':note});
    var if_can_submit = false;
    if(title == ""){
        $("#error_title").html("请输入标题");
        if_can_submit = false;
    }else{
        if_can_submit = true;
    }
    if(category == ""){
        $("#error_category").html("请输入分类");
        if_can_submit = false;
    }else{
        if_can_submit = true;
    }
    if(if_can_submit == true){
         retData = ajax_common(url,params);
         // alert(retData);
         // location.reload();
         $("#dialog").attr("title","修改每周一文")
         $("#dialog_content").html("修改成功");
         $( "#dialog" ).dialog("open");
         $( "#dialog" ).dialog({
            close :function(event, ui){
                location.reload();
            }
         });
    }
}

//保存公告
function save_notice(){
    var content = editor.html();
    var title = $("#title").val();
    var url = $("#baseUrl").val()+"cnadmin/content/create_notice";
    var params = ({'notice_content':content,'notice_title':title});
    var if_can_submit = false;
    if(title == ""){
        $("#error_title").html("请输入标题")
        if_can_submit = false;
    }else{
        if_can_submit = true;
    }
    if(if_can_submit == true){
         var retData = ajax_common(url,params);
         // alert(retData);
         // location.reload();
         $("#dialog").attr("title","添加公告")
         $("#dialog_content").html("添加成功");
         $( "#dialog" ).dialog("open");
         $( "#dialog" ).dialog({
            close :function(event, ui){
                location.reload();
            }
         });
    }
}

//编辑公告
function edit_notice(notice_id,notice_title){
    var article_content;
    var article_notes;
    var url = $("#baseUrl").val()+"cnadmin/content/modify_notice";
    var params = ({'notice_id':notice_id});
    var retData = ajax_common_json(url,params);
    // alert(retData['article_content'])

    $("#save_button").attr("onclick","save_edit_notice("+notice_id+")");
    $("#title").attr("value",notice_title);
    editor.html(retData['notice_content']);
    document.body.scrollTop = document.body.scrollHeight;
}

function save_edit_notice(notice_id){
    var content = editor.html();
    var title = $("#title").val();
    var url = $("#baseUrl").val()+"cnadmin/content/edit_save_notice";
    var params = ({'notice_id':notice_id,'notice_content':content,'notice_title':title});
    var if_can_submit = false;
    if(title == ""){
        $("#error_title").html("请输入标题");
        if_can_submit = false;
    }else{
        if_can_submit = true;
    }
    if(if_can_submit == true){
         retData = ajax_common(url,params);
         $("#dialog").attr("title","修改公告");
         $("#dialog_content").html("修改成功");
         $( "#dialog" ).dialog("open");
         $( "#dialog" ).dialog({
            close :function(event, ui){
                location.reload();
            }
         });
         // alert(retData);

    }
}

//=========================================================资料审核=========================================//
function show_data_detail(data_id,data_name,user_id){
  var url = $("#baseUrl").val()+"cnadmin/audit/preview_note";
  var params = ({'data_id':data_id});
  var retData = ajax_common_json(url,params);
  var path = retData['data_flash_url'];
  var swfpath;
  if(path.indexOf("http://")>=0){
      swfpath = "http://wx-flash.oss.aliyuncs.com/FlexPaperViewer.swf";
  }else{
      swfpath = "/application/frontend/views/data/FlexPaperViewer.swf";
  }
  $('#documentViewer').FlexPaperViewer(
          { config : {

              SWFFile : path,

              FlexPaperViewersrc : swfpath,
              Scale : 1,
              ZoomTransition : 'easeOut',
              ZoomTime : 0.5,
              ZoomInterval : 0.2,
              FitPageOnLoad : false,
              FitWidthOnLoad : true,
              FullScreenAsMaxWindow : false,
              ProgressiveLoading : false,
              MinZoomSize : 0.8,
              MaxZoomSize : 3,
              SearchMatchAll : true,
              InitViewMode : 'Portrait',
              RenderingOrder : 'flash',
              StartAtPage : '',

              ViewModeToolsVisible : false,
              ZoomToolsVisible : true,
              NavToolsVisible : true,
              CursorToolsVisible : true,
              SearchToolsVisible : false,
              WMode : 'Opaque',
              localeChain: 'en_US'
          }}
  );
  var offset = $("#offset").val();
  $("#data_pass").attr("href",$("#baseUrl").val()+"cnadmin/audit/pass_audit?data_id="+data_id+"&note_offset="+offset);
  $("#data_unpass").attr("href",$("#baseUrl").val()+"cnadmin/audit/unpass_audit?data_id="+data_id+"&note_offset="+offset+"&data_name="+data_name+"&user_id="+user_id);
  $("#add_to_top").attr("onclick","add_to_top("+data_id+")")
}

function add_to_top(data_id){
    var url = $("#baseUrl").val()+"cnadmin/audit/mark_goog_note";
    var params = ({'data_id':data_id});
    var retData = ajax_common(url,params);
    alert(retData)
    if(retData == "success"){
        $("#add_to_top").attr("value","已添加");
    }
}
//=========================================================指标管理页面=========================================//
function query_any_month(){
    var start_time = $("#start_year").val()+"-"+$("#start_month").val();
    var end_time = $("#end_year").val()+"-"+$("#end_month").val();
    var url = $("#baseUrl").val()+"cnadmin/general/query_any_month";
    var params = ({'start_month':start_time,'end_month':end_time});
    var retData = ajax_common_json(url,params);
    var i ;
    var str = "";
    for(i in retData){
        str += "<tr>";
        str += "<td>";
        str += " <input type='checkbox' value='"+retData[i]['site_id']+"'/>";
        str += " </td>";
        str += " <td >"+retData[i]['site_date']+"</td>";
        str += " <td >"+retData[i]['site_users']+"</td>";
        str += " <td >"+retData[i]['site_note_count']+"</td>";
        str += " <td >"+retData[i]['site_upload_count']+"</td>";
        str += " <td >"+retData[i]['site_imagenote_count']+"</td>";
        str += " <td >"+retData[i]['site_freedown_count']+"</td>";
        str += " <td >"+retData[i]['site_paydown_count']+"</td>";
        str += " <td >"+retData[i]['site_download_count']+"</td>";
        str += " <td >"+retData[i]['site_total_income']+"</td>";
        str += "</tr>";

    }
    $("#search_dis").before(str);
}
//=========================================================邮件发送页面=========================================//
//欢迎邮件
function send_welcome_email_ed(){
    $("#eamil_send").css("display","block");
    var url = $("#baseUrl").val()+"cnadmin/general/init_email_content";
    var params = ({});
    var retData = ajax_common(url,params);
    editor.html(retData);
}

function save_welcome_email(){
    var url = $("#baseUrl").val()+"cnadmin/general/save_welcome_content";
    var params = ({'email_content':editor.html()});
    var retData = ajax_common(url,params);
    if(retData == "success"){
        show_dialog("保存欢迎邮件","操作成功");
    }else{
        show_dialog("保存欢迎邮件","操作失败");
    }
}
function send_welcome_email(){
    var url = $("#baseUrl").val()+"cnadmin/general/send_welcome_email";
    var params = ({'email_content':editor.html()});
    var retData = ajax_common(url,params);
    alert(retData)
    if(retData == "success"){
        show_dialog("发送欢迎邮件","操作成功");
    }else{
        show_dialog("发送欢迎邮件","操作失败");
    }
}

var week_list = "";
var month_list = "";
//周推荐
function week_send(){
    var url = $("#baseUrl").val()+"cnadmin/general/last_week_good_note";
    var params = ({});
    var retData = ajax_common_json(url,params);
    var i ;
    var str = "";
    for(i in retData){
        str += "<tr>";
        str += "<td>";
        str += " <input type='checkbox' value='"+retData[i]['data_id']+"'/>";
        str += " </td>";
        str += " <td >"+retData[i]['data_name']+"</td>";
        str += " <td >"+retData[i]['data_type']+"</td>";
        str += " <td >"+retData[i]['data_uploadtime']+"</td>";
        str += " <td ><input type='button' class='button' value='添加到推荐列表' onclick='add_to_week(this,"+retData[i]['data_id']+")'></td>";
        str += "</tr>";

    }
    $("#week_dis").html(str);
    $("#eamil_send").css("display","none");
}

function add_to_week(the,data_id){
    if(week_list == ""){
        week_list = data_id;
    }else{
        week_list += ","+data_id;
    }
    $(the).attr("value","已添加")

}

function send_week_eamil(){
    var url = $("#baseUrl").val()+"cnadmin/general/send_week_recommend";
    var params = ({'data_id_list':week_list});
    var retData = ajax_common(url,params);
    if(retData == "success"){
        show_dialog("发送周推荐邮件","操作成功");
    }else{
        show_dialog("发送周推荐邮件","操作失败");
    }
}

//月推荐
function month_send(){
    var url = $("#baseUrl").val()+"cnadmin/general/last_month_good_note";
    var params = ({});
    var retData = ajax_common_json(url,params);
    var i ;
    var str = "";
    for(i in retData){
        str += "<tr>";
        str += "<td>";
        str += " <input type='checkbox' value='"+retData[i]['data_id']+"'/>";
        str += " </td>";
        str += " <td >"+retData[i]['data_name']+"</td>";
        str += " <td >"+retData[i]['data_type']+"</td>";
        str += " <td >"+retData[i]['data_uploadtime']+"</td>";
        str += " <td ><input type='button' class='button' onclick='add_to_month(this,"+retData[i]['data_id']+")' value='添加到推荐列表'></td>";
        str += "</tr>";

    }
    $("#month_dis").html(str);
    $("#eamil_send").css("display","none");
}

function add_to_month(the,data_id){
    if(month_list == ""){
        month_list = data_id;
    }else{
        month_list += ","+data_id;
    }
    $(the).attr("value","已添加");

}

function send_month_eamil(){
    var url = $("#baseUrl").val()+"cnadmin/general/send_month_recommend";
    var params = ({'data_id_list':month_list});
    var retData = ajax_common(url,params);
    if(retData == "success"){
        show_dialog("发送月推荐邮件","操作成功");
    }else{
        show_dialog("发送月推荐邮件","操作失败");
    }
}

//=========================================================用户反馈页面=========================================//
function get_topic_detail(feedback_id,feedback_offset){
    var url = $("#baseUrl").val()+"cnadmin/feedback/get_topic_detail";
    var params = ({'feedback_id':feedback_id});
    var retData = ajax_common_json(url,params);
    if(retData!=null){
        var str="";
        var i ;
        var result = retData;
        var user_id_list="";
        var top_content;
        var feedback_id;
        for(i in result){
            user_id_list += result[i]['user_id']+",";
            if(i==0){
                top_content = result[i]['feedback_content'];
                feedback_id = result[i]['feedback_id'];
                str+="<div class='feed_back_item'>";

                str+="<div class='feed_back_item_body'><div class='feed_back_item_head'>";
                str+=result[i]['user_name']+" 说：";//发起者名字
                str+="<input type='hidden' id='top"+result[i]['feedback_id']+"' value='"+result[i]['feedback_content']+"'></div>";
                str+="<p class='feed_back_content'>"+result[i]['feedback_content']+"</p><div class='feed_back_item_bottom'>";//内容
                str+=result[i]['feedback_time'];
                str+="<input type='hidden' id="+result[i]['feedback_id']+" >";
                str+="<a href='"+$('#baseUrl').val()+"cnadmin/feedback/delete_feedback?feedback_id="+feedback_id+"&feedback_offset="+feedback_offset+"'>";
                str+="<input type='button' style='background:red !important;border: 1px solid red !important;' class='button' value='删除'></a>";
                str+="</div>";
                str+="<div class='feed_back_comment_list' id='comment"+result[i]['feedback_id']+"'>";
                startid = result[i]['feedback_id'];
            }else{
                str+= "<div class='feed_back_comment'>";
                str+= "<p class='feed_back_comment_content'>";
                str+= result[i]['user_name']+": "+result[i]['feedback_content'];
                str+="<a href='"+$('#baseUrl').val()+"cnadmin/feedback/delete_feedback?feedback_id="+result[i]['feedback_id']+"&feedback_offset="+feedback_offset+"'>";
                str+="<input type='button' style='background:red !important;border: 1px solid red !important;' class='button' value='删除'></a>";
                str+= "</p></div>";
            }
        }

        $("#feedback_re").html(str);
        $("#feedback_submit").attr("onclick","feedback_submit("+feedback_id+",'"+user_id_list+"','"+top_content+"')");
        $("#feedback_ingore").attr("onclick","feedback_ingore("+feedback_id+")");
    }

}

var feedback_content;
function feedback_submit(feedback_id,user_id_list,top_content){
    var url = $("#baseUrl").val()+"cnadmin/feedback/admin_feedback_reply";
    // feedback_content = $("#feedback_content").val();
    user_id_list=user_id_list.substr(0,user_id_list.length-1);
    var params =({'feedback_content':feedback_content,'feedback_id':feedback_id,'user_id_list':user_id_list,'feedback_topic':top_content});
    var retData = ajax_common(url,params);
    if(retData =="success"){
        //show_dialog("反馈回复","操作成功");
        $("#dialog").attr("title","反馈回复");
        $("#dialog_content").html("操作成功");
        $( "#dialog" ).dialog("open");
        $( "#dialog" ).dialog({
            close :function(event, ui){
                location.reload();
            }
        });
    }else{
        show_dialog("反馈回复","操作失败");
    }
}

function feedback_ingore(feedback_id){
    var url = $("#baseUrl").val()+"cnadmin/feedback/feedback_pass";
    var params =({'feedback_id':feedback_id});
    var retData = ajax_common(url,params);
    if(retData =="success"){
        //show_dialog("反馈回复","操作成功");
        $("#dialog").attr("title","反馈回复");
        $("#dialog_content").html("操作成功");
        $( "#dialog" ).dialog("open");
        $( "#dialog" ).dialog({
            close :function(event, ui){
                location.reload();
            }
        });
    }else{
        show_dialog("反馈回复","操作失败");
    }
}

//=========================================================投诉页面=========================================//
function show_comdata_detail(com_link){
    $("#message_title").html("资料详情");
    var url = $("#baseUrl").val()+"cnadmin/report/query_note_detail";
    var params =({'com_link':com_link});
    var retData = ajax_common_json(url,params);
    var str="";
    if(retData!=""){
        str +="<p>名称："+retData['data_name']+"</p>";
        str +="<p>类型："+retData['data_type']+"</p>";
        str +="<p>上传时间："+retData['data_uploadtime']+"</p>";
    }else{
        str +="<p>你要查的资料不存在</p>"
    }

     $("#message_content").html(str);
}

//投诉量
function complaint_count(){
    var url = $("#baseUrl").val()+"cnadmin/report/get_report_statistic";
    var params =({});
    var retData = ajax_common_json(url,params);
    $("#disposed").html(retData['disposed']);
    $("#undisposed").html(retData['undisposed']);
    $("#total").html(retData['total']);
}

//不做处理的投诉
function pass_complaint(com_id,step){
    var url =""
    if(step == 1){
        url = $("#baseUrl").val()+"cnadmin/report/pass_this_report";
    }else{
        url = $("#baseUrl").val()+"cnadmin/report/step_two_pass";
    }

    var params =({'com_id':com_id});
    var retData = ajax_common(url,params);
    if(retData =="success"){
        $("#dialog").attr("title","投诉管理");
        $("#dialog_content").html("操作成功");
        $( "#dialog" ).dialog("open");
        $( "#dialog" ).dialog({
            close :function(event, ui){
                location.reload();
            }
        });
    }else{
        show_dialog("投诉管理","操作失败");
    }

}
//封闭资料
function close_note(com_user_email,com_note_name,com_link,com_id,com_time){
    var url = $("#baseUrl").val()+"cnadmin/report/close_this_note";
    var params =({'com_user_email':com_user_email,'com_note_name':com_note_name,'com_link':com_link,'com_id':com_id,'com_time':com_time});
    var retData = ajax_common(url,params);
    if(retData =="success"){
        $("#dialog").attr("title","投诉管理");
        $("#dialog_content").html("封闭成功");
        $( "#dialog" ).dialog("open");
        $( "#dialog" ).dialog({
            close :function(event, ui){
                location.reload();
            }
        });
    }else if(retData =="email-failed"){
        $("#dialog").attr("title","投诉管理");
        $("#dialog_content").html("封闭失败，邮件发不出去");
        $( "#dialog" ).dialog("open");
        $( "#dialog" ).dialog({
            close :function(event, ui){
                location.reload();
            }
        });
    }else{
        show_dialog("投诉管理","封闭失败，系统错误");
    }
}

//待定状态，给用户发邮件
var com_user_email;
var com_note_name;
var com_link;
var com_id;
var com_time;
var com_reason;
function send_dim_email(com_user_email1,com_note_name1,com_link1,com_id1,com_time1){
    com_user_email = com_user_email1;
    com_note_name = com_note_name1;
    com_link = com_link1;
    com_id = com_id1;
    com_time = com_time1;
    $("#message_title").html("说明违规原因");
    var str = "";
    str += "<p><textarea onblur='javascript:com_reason = this.value' id='dim_email_content' style='width: 400px;height: 100px;'></textarea>";
    str += "<input type='button' class='button' value='确定' onclick='wait_to_step_two()'></p>"
    $("#message_content").html(str);
}

//推到第二步
function wait_to_step_two(){
    var url = $("#baseUrl").val()+"cnadmin/report/wait_to_step_two";
    var params =({'com_user_email':com_user_email,'com_note_name':com_note_name,'com_link':com_link,'com_id':com_id,'com_time':com_time,'com_reason':com_reason});
    var retData = ajax_common(url,params);
    if(retData =="success"){
        $("#dialog").attr("title","投诉管理");
        $("#dialog_content").html("操作成功");
        $( "#dialog" ).dialog("open");
        $( "#dialog" ).dialog({
            close :function(event, ui){
                location.reload();
            }
        });
    }else if(retData =="email-failed"){
        $("#dialog").attr("title","投诉管理");
        $("#dialog_content").html("操作失败，邮件发不出去");
        $( "#dialog" ).dialog("open");
        $( "#dialog" ).dialog({
            close :function(event, ui){
                location.reload();
            }
        });
    }else{
        show_dialog("投诉管理","操作失败，系统错误");
    }
}

//第二步原因
function teptwo_reason_detail(com_id){
    $("#message_title").html("第二步原因");
    var url = $("#baseUrl").val()+"cnadmin/report/query_report_result";
    var params =({'com_id':com_id});
    var retData = ajax_common_json(url,params);
    var str="";
    if(retData!=""){
        str +="<p>"+retData+"</p>";
    }else{
        str +="<p>没有原因</p>"
    }

     $("#message_content").html(str);
}

//=========================================================提现=========================================//
var check_order_valid = function(draw_no,draw_user_id,draw_money){
    // draw_no = $("#hidden_draw_no").val();
    var url = $("#baseUrl").val()+"cnadmin/withdraw/check_order_valid";
    var params =({'draw_no':draw_no,'draw_user_id':draw_user_id,'draw_money':draw_money});
    var retData = ajax_common_json(url,params);
    // alert(draw_no)
    // $("#hidden_draw_no").attr("value",draw_no);
    $("#message_title").html("提现校验结果");
    var str = "";
    var is_only = retData["is_only"];
    var is_account_ok = retData["is_account_ok"];
    var is_enough = retData["is_enough"];
    var is_ten_multi = retData["is_ten_multi"];
    if(retData["is_only"] == "true"){
        str += "<p>1、提现人唯一:<span style='float: right;'>正确</span></p>";
    }else{
        str += "<p style='color:red;'>1、提现人唯一:<span style='float: right;'>错误</span></p>";
    }
    // alert(Object.prototype.toString.apply(retData["is_account_ok"]))
    if(retData["is_account_ok"] == 'true'){
        str += "<p>2、账户是否正确:<span style='float: right;'>正确</span></p>";
    }else{
        str += "<p style='color:red;'>2、账户是否正确:<span style='float: right;'>错误</span></p>";
    }
    if(retData["is_enough"] == "true"){
        str += "<p>3、账户余额是否大于零:<span style='float: right;'>正确<span></p>";
    }else{
        str += "<p style='color:red;'>3、账户余额是否大于零:<span style='float: right;'>错误</span></p>";
    }
    if(retData["is_ten_multi"] == "true"){
        str += "<p>4、提现额为10.00倍数:<span style='float: right;'>正确</span></p>";
    }else{
        str += "<p style='color:red;'>4、提现额为10.00倍数:<span style='float: right;'>错误</span></p>";
    }

    if(retData["is_only"] == "true"&&retData["is_account_ok"] == "true"&&retData["is_enough"] == "true"&&retData["is_ten_multi"] == "true"){
        str += "<p>提现者账户:<button class='button_copy'  id='"+retData["ali_account"]+"' style='color: green;float: right;cursor: pointer;' data-clipboard-text='"+retData["ali_account"]+"'>【复制】</button ><span onClick='javascript:this.focus();this.select();' contenteditable  style='color: green;float: right;padding-right: 10px;'>"+retData["ali_account"]+"</span></p>";
        str += "<p>提现者签名:<span style='color: red;font-size: 21px;padding-left: 60px;'>"+retData["ali_realname"]+"</span></p>";
        str += "<p>提现金额:<span style='color: red;font-size: 21px;padding-left: 60px;'>"+retData["ali_draw_money"]+"元</span></p>";
        str += "<p ><input type='button' onclick='accomplish_withdraw("+'"'+draw_no+'"'+")' value='完成提现申请' class='button'>";
        str += "<input style='float: right;' type='button' onclick='reject_by_realname("+'"'+draw_no+'"'+")' value='签名不一致' class='button'></p>";
    }else{
        str += "<p ><input  type='button' onclick='reject_withdraw("+'"'+draw_no+'"'+","+is_only+","+is_account_ok+","+is_enough+","+is_ten_multi+")' value='驳回提现申请' class='button'></p>";
    }
    $("#message_content").html(str);

if(true){
   var clip = new ZeroClipboard( $(".button_copy"), {
      moviePath: "/application/backend/views/js/ZeroClipboard.swf"
    } );

    clip.on( "load", function(client) {
      // alert( "movie is loaded" );

      client.on( "complete", function(client, args) {
        // `this` is the element that was clicked
        this.style.display = "none";
        alert("Copied text to clipboard: " + args.text );
      } );
    } );
}

}

var accomplish_withdraw = function(draw_no){
    // draw_no = $("#hidden_draw_no").val();
    var url = $("#baseUrl").val()+"cnadmin/withdraw/accept_withdraw_order";
    var params =({'draw_no':draw_no});
    var retData = "";
    if(confirm("确定要进行该操作吗？")){
        retData = ajax_common(url,params);
        if(retData == "success"){
            location.reload();
        }else if(retData == "failed"){
            alert("操作失败");
        }else if(retData == "no-record"){
            alert("没有该条记录");
        }

    }

}

var reject_withdraw = function(draw_no,is_only,is_account_ok,is_enough,is_ten_multi){
    // draw_no = $("#hidden_draw_no").val();
    // alert(draw_no)
    var url = $("#baseUrl").val()+"cnadmin/withdraw/reject_withdraw_order";
    var params =({'draw_no':draw_no,'is_only':is_only,'is_account_ok':is_account_ok,'is_enough':is_enough,'is_ten_multi':is_ten_multi});
    var retData = "";
    if(confirm("确定要进行该操作吗？")){
        retData = ajax_common(url,params);
        if(retData == "success"){
            location.reload();
        }else if(retData == "failed"){
            alert("操作失败");
        }else if(retData == "no-record"){
            alert("没有该条记录");
        }
    }
}

var reject_by_realname = function(draw_no){
    // draw_no = $("#hidden_draw_no").val();
    var url = $("#baseUrl").val()+"cnadmin/withdraw/reject_withdraw_order_invalid_sign";
    var params =({'draw_no':draw_no});
    var retData = "";
    if(confirm("确定要进行该操作吗？")){
        retData = ajax_common(url,params);
        if(retData == "success"){
            location.reload();
        }else if(retData == "failed"){
            alert("操作失败");
        }else if(retData == "no-record"){
            alert("没有该条记录");
        }
    }
}


