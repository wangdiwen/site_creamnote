<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01al//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>网站导航</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/reset.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_footlist.css" />
    <link rel="stylesheet" href="/application/frontend/views/resources/css/wx_home.css" />

    <script type="text/javascript" src="/application/frontend/views/resources/js/jquery-1.8.3.js"></script>
    <script src="/application/frontend/views/resources/js/jquery.tipsy.js" type="text/javascript"></script>

<script type="text/javascript">
$(function(){
  //$(".content").css("display","none");
})
  function showcontent(num){
    if($("#"+num).css("display")=="none"){
      //$(".content").slideToggle(1000);
      $("#"+num).slideToggle(1000);
    }else{
      $("#"+num).slideToggle(1000);
    }

  }
</script>

</head>
<body>
  <?php include  'application/frontend/views/share/header_home.php';?>
  <div class="body article_body">
    <div class="foot_list_left" style="">
      <h1><b>帮助</b></h1>
      <ul class="foot_list_ul">
        <li class="nowli"><a href="#" title="常见问题">常见问题</a></li>
        <li><a href="<?php echo site_url('static/wxc_help/skills'); ?>" title="帮助教程">帮助教程</a></li>
        <li><a href="<?php echo site_url('static/wxc_help/sitemap'); ?>" title="网站地图">网站导航</a></li>
      </ul>
    </div>
    <div class="foot_list_right" style="padding-bottom: 40px;">
      <h2><b>常见问题</b></h2>
      <div class="text_line">
        <p style="font-size:14px" class="pointer" onclick="showcontent(1)"><b>FAQ：1、平台是干什么的？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;醍醐笔记网是一个专注于高校大学生的专业课堂学习笔记、期末考试习题、以及考研复习资料的开放、共享与展示平台。
        <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;在醍醐，你可以选择将自己在学校学习过程中的一些复习资料、学习笔记分享出来，您可以选择笔记资料是免费，还是付费，如果您是一个“学霸”，那么您肯定不希望每次期末考试结束之后，或者是在平时您辛苦记录的一些技术文档、笔记，就这么在您的电脑里面成为了“古董”，然后在一个夏日的午后，被一次垃圾清理，或者是“大扫除”，删除掉了吧！？
        </p>

        <p style="font-size:14px" class="pointer" onclick="showcontent(2)"><b>FAQ：2、浏览器的兼容性？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;我们鼓励大家在上网的时候，不要在去使用Windows自带的IE浏览器了，因为作为一个大学生，我们推荐您尝试去使用像谷歌Chrome或者是火狐Firefox这样的用户体验好的浏览器，因为使用Windows自带的IE，我们会觉得您是一个没有“品味”的屌丝青年。
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;除了上面，我们说的情感因素外，还有IE内核的浏览器对现在网站的各种UI渲染支持的都不太好，用户界面使用体验太“烂”了。
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;所以，在这里，我们也声明，醍醐不会在为了IE 的兼容性而去调试网站的UI了，如果您在IE里面发现醍醐网站的页面出现一些“奇葩”的效果，那么不要惊慌，赶紧安装谷歌Chrome或者是火狐Firefox浏览器吧~~
</p>

        <p style="font-size:14px" class="pointer" onclick="showcontent(3)"><b>FAQ：3、醍醐支持哪些文档上传？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;醍醐目前支持文档和图片文件。
<br />( 1 )文档格式
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;在静态文档上面，支持主流的所有Office系列以及WPS系列文档。
文档的格式为：doc、docx、ppt、pptx、xls、xlsx、pdf ；
<br />( 2 )图片格式
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;我们支持的图片上传，主要是在您使用醍醐平台的“图片笔记”功能的时候需要的，图片格式目前可以是：PNG、JPG、GIF；
</p>

        <p style="font-size:14px" class="pointer" onclick="showcontent(4)"><b>FAQ：4、醍醐与平台用户对付费资料的分成比例是如何定义的？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;分成比例如下（以当次笔记付费下载的费用为百分比）：
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1、你的个人收益：78.8%
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1、醍醐服务付费：20%
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1、第三方支付系统提供商流量费率：1.2%
</p>

        <p style="font-size:14px" class="pointer" onclick="showcontent(5)"><b>FAQ：5、什么是图片笔记？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;“图片笔记”是我们醍醐网站的一个特色功能，简单的来说就是，您可以把自己的一些有珍藏和使用参考价值的纸质笔记，使用您的手机逐页的把纸质笔记拍下来，然后您在登录醍醐网站后，在“个人中心”平行的导航菜单“图片笔记”功能中，上传您的手机照片，那么在经过简单的3个步骤后，通过了“图片笔记”功能的一些信息校验并且提交后，我们平台就会自动的为您生成一份带有彩色封面的PDF文档了（是不是感觉很有意思呢^_^）。
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;当然，“图片笔记”还有一个实用的功能，就是在您上传了一份完整的纸质笔记资料的图片后，如果发现图片的自然上传顺序出错了，那么不要紧，在提交之前，可以在第一步上传下方的“缩略图”预览中，通过使用鼠标单击“缩略图”卡片顶部的标题栏，然后拖曳到您需要的位置，然后释放鼠标，那么在您最后完成并成功提交后，就会使用您自定义的图片顺序生成一份精美的数字化笔记资料了（赶紧体验一下吧~~）。
</p>

        <p style="font-size:14px" class="pointer" onclick="showcontent(6)"><b>FAQ：6、如何快速的搜索？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;在使用醍醐平台查找您需要的笔记、或者是考试、考研资料的时候，我们推荐您直接通过平台首页的“搜索”功能，进行及时搜索查找，您搜索的关键词可以是您文档的通用名称，但是我们推荐您不要输入一长串文本，在搜索的时候，您可以使用【空格】隔开一些明显的关键词，比如：我想看看醍醐上面有木有“南京工程学院 通信工程专业的 通信原理这门课的期末考试试卷”？
<br />那么您可以这样在搜索框中输入：
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;“南工程 通信 通信原理 期末考试 试卷”
<br />这样，系统就会更加精准的匹配您需要的东西了，其实只要您自己用空格把一些搜索的信息关键词分开，就可以熟练的使用搜索功能啦~~
<br />醍醐目前支持3种方式浏览与查找您需要的笔记资料：
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1、使用通用搜索功能，这也是最为常用，快速的手段；
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2、通过点击某个用户，平台会为您呈现您所查看的用户都上传了哪些个笔记资料；
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3、通过“笔记展示”功能左侧的分类模式，选择您当前需要的资料是哪个类别的，那么系统就会把您选定类别下面关联的、并且排名靠前的笔记资料推荐给您；
<br />您看，搜索与查找资料是不是灰常的方便、简单啊~~还等什么，赶紧自己亲手试试看吧^_^
</p>

        <p style="font-size:14px" class="pointer" onclick="showcontent(7)"><b>FAQ：7、上传的步骤？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="7">登录醍醐网后，进入“个人中心”，单击“分享干货”导航菜单，只需要3个简单的步骤，就可以分享您的笔记资料了：
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;第一步：上传资料文件；
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;第二步：为资料文件选择合适的分类信息；
<br />第三步：添加资料文件的描述，这样更方便其他人快速的搜索到您分享的干货；
</p>

        <p style="font-size:14px" class="pointer" onclick="showcontent(8)"><b>FAQ：8、注意的一些事项？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="8">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.您分享的笔记资料，务必在上传完毕后完善资料属性信息，否则，对于没有任何属性信息的资料文件，平台很有可以在维护清理任务中自动删除这些资料文件；
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2、在填写您分享的笔记资料时候，请您仔细为笔记资料选择并填写正确的信息，这样您的资料就会更好的被其他用户看到，在帮助他人的同时，也会提高您的收益，不是一举两得的好事情么？！
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3、在分享您的笔记干货的时候，醍醐目前只支持我们日常通用的doc、docx、ppt、pptx、xls、xlsx、pdf格式文件，对于一些奇葩格式的文件，我们暂时不做支持；
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4、在您使用我们“图片笔记”功能的时候，我们根据调研，手机拍照的照片存储格式为JPG格式，除了手机拍照的图片，我们目前仍然可以支持PNG和GIF格式图片，除了JPG、PNG、GIF这3个通用格式外，对于其他的奇葩格式，我们暂时不做支持；
</p>

        <p style="font-size:14px" class="pointer" onclick="showcontent(9)"><b>FAQ：9、关于用户等级的评定？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="9">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;用户等级的判定是一个复杂的事情，我们的系统会根据算法分析您使用醍醐网站的方方面面，得出一个比较综合的等级；
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;对于等级的划分，我们分成10个等级，最开始的一级也叫初级，最高为10级，但是用户升级至10及满级后，就不会升级了，您会变成醍醐的“资深用户”，享受自定义分享的笔记资料价格，以及免费下载任何付费笔记资料的各种优惠功能~~
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;等级判定的因素有哪些呢？：
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;使用醍醐网站的频率、分享笔记干货的数量、您的资料被关注的次数、被下载的次数、被浏览的次数、您的收益多少、关于您发起、参与的反馈、各项评论与留言数量和质量…
<br />这些细微的各种因素都会成为系统等级判定算法参考的数据，所以，如果您想快速的升级到满级，就像玩“魔兽世界”一样，那么就多多参与到醍醐吧~~
</p>

        <p style="font-size:14px" class="pointer" onclick="showcontent(10)"><b>FAQ：10、笔记资料的等级如何评定的？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;笔记资料的等级评定，是通过网站后台评定算法计算得出的综合因素评分。影响等级评定的因素来自方方面面：
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;比如：您的笔记是否被其他用户下载使用过、下载的次数多少、被浏览的次数多少、被收藏的次数多少、其他用户的打分、以及笔记资料的评论等；
</p>

        <p style="font-size:14px" class="pointer" onclick="showcontent(11)"><b>FAQ：11、笔记资料如何交易？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="11">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;醍醐平台提供2种方式的笔记资料使用方式：免费、付费。笔记资料的买卖是针对付费这种方式而言的。
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;当平台的用户发现某个笔记资料是自己迫切需要的，但是这份笔记资料又是付费的，那么用户完成一次付费下载操作后，我们就说完成了一次笔记资料的交易。
</p>

        <p style="font-size:14px" class="pointer" onclick="showcontent(12)"><b>FAQ：12、用户如何获取收益？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="12">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;当您在醍醐上的账号中，分享了至少1篇付费的笔记资料后，并且有醍醐平台其他的用户对您上传分享的其中1篇笔记资料发生了交易后，那么在这个操作事件通过验证有效后，你就会从其他用户的付费中获得收益。
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;醍醐会在当次发生的笔记资料交易产生的交易费用中，抽取20%的服务费用；第三方支付系统服务提供商（例如：支付宝）会有1.2%的资金流量费率；剩余的78.8%就是你获得的个人收益；系统自动审核付费交易成功后，在你的个人收益账户中，会自动打入相应的资金余额。
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;【你的个人收益78.8%】==【笔记购买费用】-【醍醐平台服务费用20%】-【第三方支付系统流量费率1.2%】。

        <p style="font-size:14px" class="pointer" onclick="showcontent(13)"><b>FAQ：13、醍醐支持通过哪种渠道进行付费？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="13">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;目前，通过我们的调研发现，大学生人群中使用支付宝进行电子网络资金交易的比例高达80%，其他的第三方电子交易账号平台所占比例很少，比如：信用卡交易比例为 5%，普通银行卡的比例为：7 %，而像财付通这样的新兴平台以及其他支付平台约占10%左右。
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;根据上面的数据，醍醐目前只支持通过更加广泛的支付宝进行资金的交易，使用的地方有3个：对付费的笔记资料进行下载使用、对您的醍醐收益账户进行充值、对您的收益账户提取收益。
</p>

        <p style="font-size:14px" class="pointer" onclick="showcontent(14)"><b>FAQ：14、用户如何从醍醐的收益账户中提取收益到支付宝？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;如果您是醍醐平台的用户，您可以到自己的“个人中心”->“收益账户”中查看您的收益是多少，醍醐给出的数字金额是按照人民币的元为单位的，假如您的收益账户中，显示收益为￥10.00，那么就表示您在醍醐上面已经通过付费的学习笔记资料取得了10.00元人民币的收入了。
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;如果您的收益显示金额大于等于￥10.00元，那么您就可以通过“收益账户”中的“提现到支付宝”功能，把您在醍醐网平台取得的收益，通过醍醐的系统收益平台提取等值的收益金额到支付宝，但是“提现到支付宝”功能是醍醐给您提供的一个申请提现，我们通过您提交的提现申请，经过醍醐人工核实后，在5个工作日内会将您申请的提现金额返还到您申请提现给出的支付宝账号中。
</p>

        <p style="font-size:14px" class="pointer" onclick="showcontent(15)"><b>FAQ：15、提取收益的一些说明？什么是最低限额？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="15">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;醍醐在您的“收益账户”中记录的金额，就是您实际通过醍醐平台取得的合法等值收益，这其中醍醐系统已经自动从您的付费笔记资料的下载使用费用中抽取了20%的平台使用费了。
<br />最低限额：
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;醍醐通过第三方的支付宝支付平台，为您在醍醐上面进行付费笔记资料交易提供支付手段，而醍醐在使用第三方的支付平台过程中，会给第三方的支付平台支付一定的平台手续费用。当您在从醍醐的“收益账户”中，申请提取收益到支付宝的操作中，醍醐会给支付宝支付一定的手续费，由于小额的收益单笔提现到支付宝，需要醍醐人工的一一审核“收益账户”，增加了我们处理提现操作的复杂度和人力成本，并且由于醍醐还需要为第三方的支付宝支付一定的支付手续费，导致醍醐每次对小额的提现操作“入不敷出”，所以，醍醐暂时规定只有您的“收益账户”中的资金大于等于￥10.00的时候，您在使用“提现到支付宝”这个功能申请提现的过程中，我们醍醐才会受理您的提现申请；如果你的“收益账户”中的收益低于￥10.00，那么我们会在网站上给出提示“您的收益账户资金低于￥10.00，无法完成申请提现到支付宝”。
</p>

        <p style="font-size:14px" class="pointer" onclick="showcontent(16)"><b>FAQ：16、关于笔记资料的审核声明？审核的状态？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="16">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;当您通过醍醐的账号，分享了自己的学习笔记、或者是考试、考研方面的学习资料后，在您的“个人中心”页面中，会查看到您共享了哪些笔记和资料，但是您上传的笔记资料不会第一时间被醍醐的其他用户搜索到，因为醍醐会对用户上传的每一份笔记资料进行人工的审核，验证您分享的笔记资料是有一定价值的，我们个人审核的正常周期是2个工作日，及从您分享笔记资料开始计算的2天内，醍醐会对您分享的内容进行审核。
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;审核如果没有通过，我们会给您在醍醐账号中的“个人中心”页面的左侧通知栏给出系统通知，在通知发出的2个工作日内，如果您未对审核未通过的笔记资料进行手工删除操作的话，醍醐将会在自动化维护的清理工作中自动删除您的审核未通过的笔记资料。
</p>

        <p style="font-size:14px" class="pointer" onclick="showcontent(17)"><b>FAQ：17、哪些笔记资料会审核不通过？审核的标准有哪些？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="17">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;醍醐推荐您分享下面3个分类的笔记资料：
<br />一、大学期间某门课程的学习笔记，或者是您自己学习的一门技术的技术文档整理记录等；
<br />二、大学期间期末/期中考试的历年院系试卷，或者是关于您的某一门课程的期末考试复习资料等；
<br />三、考研相关的一些学习资料等；
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;一些硬性标准：
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1、您分享的笔记资料是有意义和价值的；
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;不能随便上传一份很“烂”的东西，这样与醍醐倡导的“珍藏一份大学时代的学习经历、分享并创造价值”的理念背道而驰，我们会在网站自动化维护的时候清理掉一些“垃圾”笔记资料；
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2、上传的笔记资料最好是自己整理的，内容详实、正确；
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;对于滥竽充数的的笔记资料，醍醐是不允许分享的；醍醐的笔记资料审核是人工方式进行的，我们的工作人员会发现您的“投机取巧”，对于未通过的资料我们会自动清理掉，并且在清理的同时会给您推送一条关于审核未通过的系统通知；
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3、通过“图片笔记”生成并分享的笔记资料，要求PDF文档的页数不能低于5页；
</p>
        <p style="font-size:14px" class="pointer" onclick="showcontent(18)"><b>FAQ：18、关于平台对长期未完善笔记资料信息的处理策略？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="18">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;醍醐会定期执行网站的自动化维护与垃圾文件的清理工作，类似于“春季大扫除”，垃圾文件的清理工作没2个工作日会清理一次，对于平台上面留存的一些没有详细属性信息的笔记资料，我们会自动在清理工作删除。
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;但是在清理之前，我们会预留1天的时间，通过系统通知的方式告知您您的哪些笔记资料没有任何可用的属性信息，您可以在“个人中心”页面的笔记展示区域查看“未完善的笔记资料”，通过点击“完善信息”来对此笔记资料进行及时的更新属性信息。
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;如果您在系统通知完善笔记资料信息的1天候，任然没有对未完善的笔记资料进行信息完善工作，那么我们会在清理工作实际生效的时候，自动删除未完善的笔记资料在醍醐上面的所有信息。
</p>

        <p style="font-size:14px" class="pointer" onclick="showcontent(19)"><b>FAQ：19、如何自定义、调整生成数字化PDF文档的图片顺序？</b></p>
        <p style="font-size:13px;display:none;padding-left: 16px;" class="content" id="19">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;通过您在醍醐的账户进入“图片笔记”功能页面，上传您用手机拍照好的纸质笔记本每页的内容的图片，在每上传一个图片的同时，您会在上传功能的下方区域会自动生成您刚才上传图片的一个内容缩略图。
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;我们考虑到一般您的纸质学习笔记肯定会有几十张纸，那么手机拍照一次大多只能拍一张，这样您就会上传几十张图片，由于图片的数量很多，您可能不会记得按照纸质笔记的页面自然顺序上传图片到醍醐平台，那么不要紧，在“缩略图”区域，您可以在上传完一份完整的纸质笔记的图片后，通过鼠标单击一份缩略图区域顶部的标签区域，拖动这个缩略图（其实真是上传的图片）到您想要的位置，然后释放鼠标，这样醍醐就会及时的记录生成这份图片笔记的顺序为您刚才调整过图片位置（按照从上到下，从左到右的自然顺序）。
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;这样操作后，当您完善了一些此份图片笔记的属性信息后，就可以在完善属性信息的第二步的“查看生成的图片笔记”中，打开“预览PDF图片笔记”来查看醍醐刚才为您自动化生成的数字版纸质图片笔记啦~~
</p>
      </div>
    </div>
  </div>
  <?php include  'application/frontend/views/share/footer.php';?>
</body>

</html>
