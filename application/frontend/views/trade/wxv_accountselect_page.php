<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Creamnote收银台</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/reset.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_home.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/style.css" />
    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery-1.8.3.js"></script>

<script type="text/javascript">
</script>

</head>

<body>
    <?php include  'application/frontend/views/share/header_home.php';?>

    <div class="body article_body" style="border-top: 8px solid #839acd;">
        <div class="" style="background-image:none;">
            <div class="reg_frame _feedback_frame _accountselect_frame" style="">
                <div class="_accountselect_title"><span>笔记信息</span></div>
                <div style="border-bottom: 1px dashed rgb(185, 194, 197);padding: 10px 0;">
                    笔记名称：
                    <?php echo "<a target='_blank' href=".base_url()."data/wxc_data/data_view/".$note_id.">".str_replace(array(" ","\r","\n"), array("","",""), $note_name)."</a>";
                    ?>

                </div>
                <input type="hidden" id="note_id" value="<?php echo $note_id;?>">
                <input type="hidden" id="note_price" value="<?php echo $note_price;?>">
                <input type="hidden" id="diff_money" value="<?php echo $diff_money;?>">
                <input type="hidden" id="note_name" value="<?php echo $note_name;?>">
                <div style="padding-top: 5px;">价格:￥<?php echo $note_price;?></div>
            </div>

            <div class="reg_frame _feedback_frame _accountselect_frame" style="">
                <div class="_accountselect_title"><span>支付</span></div>
                <div style="border-bottom: 1px dashed rgb(185, 194, 197);padding: 10px 0;">
                账户余额：￥<?php echo $user_account_money;?></br>
                <?php if($diff_money > 0){?>
                    您还需支付￥<?php echo $diff_money;?>来购买这份笔记
                    </div>
                    <div style="padding: 10px 0 10px 20px;">
                        实付价格:￥<?php echo $diff_money;?>
                    </div>
                    <div style="padding: 10px 0 10px 20px;">
                        <input type="radio" checked="checked"/>
                        <image style="border: 1px solid rgb(185, 194, 197);" src="/application/frontend/views/resources/images/version/pay_by_zhifubao.png">
                    </div>
                    <div style="padding: 10px 0 10px 20px;">
                        <input type="button" onclick="buy_one_note()" class="button_c" value="支付" name="pay_by_aili" >
                    </div>
                <?php }else{?>
                    您有足够的余额来直接购买这份笔记,我们将从您的账户余额中扣除￥<?php echo $note_price;?>
                    </div>
                    <div style="padding: 10px 0 10px 20px;">
                        实付价格:￥<?php echo $note_price;?>
                    </div >
                    <div style="padding: 10px 0 10px 20px;">
                        <input type="button" onclick="buy_one_note()" class="button_c" value="支付" name="pay_by_creamnote" >
                    </div>
                <?php }?>



            </div>
            <div  style="text-align:left;width:780px;margin: 0 auto;">
                <div style="margin: 10px 0;">常见问题</div>
                <div style="margin: 10px 0;">1、支付时网站优先使用您的支付余额，如果不足的话需要使用支付宝来补足差额。</div>
                <div style="margin: 10px 0;">2、支付成功后会给您下载这份笔记的链接，如果您不小心丢失该链接，可以到个人中心里“购买笔记”中下载。</div>
            </div>

        </div>
        <div class="clear"></div>

    </div>
<?php include  'application/frontend/views/share/footer.php';?>

</body>

</html>
