

<script type="text/javascript">
//     //从页面收集OpenAPI必要的参数。get_user_info不需要输入参数，因此paras中没有参数
//     var paras = {};
//     var open_id ;
//     var access_token ;
//     var qq_name;
//     if(QC.Login.check()){
//         //用JS SDK调用OpenAPI
//         QC.api("get_user_info", paras)
//         //指定接口访问成功的接收函数，s为成功返回Response对象
//         .success(function(s){
//             //成功回调，通过s.data获取OpenAPI的返回数据
//             qq_name = s.data.nickname;

//             QC.Login.getMe(function(openId, accessToken){
//                 open_id = openId;
//                 access_token = accessToken;
//                 //creamnote跳转判断
//                 var url ="<?php echo site_url('core/wxc_user_manager/check_qq_bind'); ?>";
//                 $.ajax({
//                 type:"post",
//                 data:({'qq_open_id': open_id,'qq_nice_name':qq_name}),
//                 url:url,
//                 success: function(result)
//                     {
//                         if(result=="true"){
//                             location.href='<?php echo site_url('home/personal'); ?>';
//                         }else{
//                             location.href='<?php echo site_url('core/wxc_user_manager/third_party_login'); ?>';
//                             //location.href='<?php echo site_url('home/index'); ?>';
//                         }
//                     },
//                      error: function(XMLHttpRequest, textStatus, errorThrown) {
//                         alert(XMLHttpRequest.status);
//                         alert(XMLHttpRequest.readyState);
//                         alert(textStatus);
//                     }
//                 });
//             });

//         })
//         //指定接口访问失败的接收函数，f为失败返回Response对象
//         .error(function(f){
//             //失败回调
//             alert("获取用户信息失败！");
//         })
//         //指定接口完成请求后的接收函数，c为完成请求返回Response对象
//         .complete(function(c){
//             //完成请求回调
//             //alert("获取用户信息完成！");
//         });

//     }


 </script>
