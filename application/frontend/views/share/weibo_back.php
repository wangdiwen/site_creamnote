
<script src=" http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=1899806133" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
//=========================================================weibo=========================================//
// 验证是否登入成功
// WB2.login(function(){
//     location.href='<?php echo site_url('core/wxc_user_manager/third_party_login'); ?>';
//   });

  if(WB2.checkLogin()){
    // api 入口
    //alert("1");
    // WB2.anyWhere(function(W){
    // // 调用 account/get_uid 接口，获取用户信息
    //   W.parseCMD('/account/get_uid.json', function(oResult, bStatus){
    //     alert(bStatus)
    //     if(bStatus){
    //       alert(oResult.uid);

    //       var url ="<?php echo site_url('core/wxc_user_manager/check_weibo_bind'); ?>";
    //       $.ajax({
    //       type:"post",
    //       data:({'weibo_open_id': oResult.uid,'weibo_nice_name':qq_name}),
    //       url:url,
    //       success: function(result)
    //           {
    //               if(result=="true"){
    //                   location.href='<?php echo site_url('home/personal'); ?>';
    //               }else{
    //                   location.href='<?php echo site_url('core/wxc_user_manager/third_party_login'); ?>';
    //                   //location.href='<?php echo site_url('home/index'); ?>';
    //               }
    //           },
    //            error: function(XMLHttpRequest, textStatus, errorThrown) {
    //               alert(XMLHttpRequest.status);
    //               alert(XMLHttpRequest.readyState);
    //               alert(textStatus);
    //           }
    //       });

    //     }
    // }
    // }, {}, {
    //   method : 'get',
    //   cache_time : 30
    //   });
  }else{
    //alert("2")
    //location.href="https://api.weibo.com/oauth2/access_token?client_id=1899806133&client_secret=051f07adc64242feef0a05e18087febd&grant_type=authorization_code&redirect_uri=http://www.creamnote.com/core/wxc_user_manager/qq_back_func&code=CODE"
    // var url ="<?php echo site_url('core/wxc_user_manager/'); ?>";
    // $.ajax({
    // type:"post",
    // data:({}),
    // url:url,
    //  dataType:"json",
    //  success: function(result)
    //     {
    //         alert(result)
    //     },
    //  error: function(XMLHttpRequest, textStatus, errorThrown) {
    //     alert(XMLHttpRequest.status);
    //     alert(XMLHttpRequest.readyState);
    //     alert(textStatus);
    // }
    // });

  }
</script>
