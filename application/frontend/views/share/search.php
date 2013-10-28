 <link rel="stylesheet" href="/application/frontend/views/resources/css/poshytip/tip-darkgray/tip-darkgray.css" type="text/css" />
 <div id="search" style="display:none;">
  <form id="search_form" method="post" action="<?php echo site_url('primary/wxc_search/public_search'); ?>" onsubmit="return search()">
      <fieldset>
      <input x-webkit-speech autofocus  type="text" name="search" onclick="hiddenTip()" id="search-text" size="15" maxlength="20" placeholder="输入关键词(以学校、专业、资料等为关键词)"/>
      <div class="Webfonts fl" class="search-submit" id="search-submit">L</div>
      </fieldset>
  </form>
</div>
<script type="text/javascript">
    $("#search-submit").click(function(){
        var form = $("#search_form")[0];
        if(form.search.value == ""){
            $("#search-text").poshytip('show');
        }else{
            form.submit();
        }

    });
    $(".search-submit-head").click(function(){
        var form = $("#search_form_head")[0];
        if(form.search.value == ""){
            // $("#search-text").poshytip('show');
        }else{
            form.submit();
        }

    });

    function hiddenTip(){
       $("#search-text").poshytip('hide');
    }
</script>
