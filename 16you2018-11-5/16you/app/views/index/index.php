<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>16游官网</title>
    <script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/js/rem.js"></script>
    <link rel="stylesheet" type="text/css" href="/media/css/reset.css">
    <link rel="stylesheet" type="text/css" href="/media/css/index.css">
    <link rel="icon" href="/media/images/logo.ico" type="image/x-icon"/> 
</head>
<body>
    <div id="warp_main" >
      <div class="warp_mainbox">
        <div class="dr_header_box">
            <div class="dr_header">
                <div class="logo" onclick="location.href='/index/index.html'">
                    <img src="/media/images/yoLogo.png" class="di-img">
                </div>
                <div class="header_right">
                    <?php if(!isset($user)): ?>
                    <div id="loginPcBtn" class="login mar_r10">
                        <a href="">
                            <em>
                                <img src="/media/images/icon_01.png">
                            </em>
                            一键登录
                        </a>
                    </div>
                    <?php endif; ?>
                    <div id="pc-collection" class="collection mar_l10">
                        <a href="">
                            <em>
                                <img src="/media/images/icon_02.png">
                            </em>
                            一键收藏
                        </a>
                    </div>
                    <?php if(isset($user)): ?>
                    <div id="qrcode_logout" class="sign_name fl ">
                        <span style="margin-left:20px"><?php echo isset($user->username)?$user->username:''; ?></span>
                        <a href="/index/logout.html">退出登录</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="dr_contanier">
            <div class="warm_bg"></div>
            <div class="content">
                <div class="content_left">
                    <div class="connection">
                        <div class="service">
                            <span>
                                <img src="/media/images/ewm.png">
                            </span>
                            <p>
                                关注 16游<br/>千万好玩游戏<br/>等你来
                                <b><img src="/media/images/img.png" style="width: 90px;margin-left: 10px;margin-top: -7px;"></b>
                            </p>
                        </div>
                        <div class="top"></div>
                    </div>
                    <?php if(isset($playgame)&&$playgame): ?>
                    <div id="user-game-container" class="game_play">
                        <h1>最近在玩</h1>
                        <ul class="clearfix">
                            <?php foreach($playgame AS $play): ?>
                            <li onclick="playgame('<?php echo $play['id'];?>')" style="">
                                <a data-game="csmhj" href="#"><img src="<?php echo yii::$app->params['cdn']; ?>/game/<?php echo ($play['head_img']);?>"></a>
                                <b><?php echo isset($play['name'])?$play['name']:'';?></b>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="content_right">
                    <div class="right_code">
                        <span>
                            <?php if(isset($user)): ?>
                            <img src="/media/images/ewm.png">
                            <?php else: ?>
                            <img src="/media/images/code/<?php echo $filename; ?>">
                            <?php endif; ?>
                        </span>
                        <p>微信扫描二维码登录</p>
                    </div>
                    <!-- 热门推荐 -->
                    <div class="recommended">
                        <?php if(isset($game)&&$game): ?>
                        <div class="re_list">
                            <div class="re_title">
                                热门推荐
                            </div>
                            <div class="re_matter">
                                <?php foreach ($game as $hg):?>
                                <div class="matterList" onclick="playgame('<?php echo $hg['id'];?>')">
                                    <div class="listImg">
                                        <span>
                                            <img src="<?php echo yii::$app->params['cdn']; ?>/game/<?php echo ($hg['head_img']);?>">
                                        </span>
                                    </div>
                                    <div class="major">
                                        <h3><?php echo isset($hg['name'])?$hg['name']:'';?></h3>
                                        <p><?php echo isset($hg['descript'])?$hg['descript']:'';?></p>
                                    </div>
                                    <div class="start_game">
                                        <a href="#">
                                            开始游戏
                                        </a>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="reconTip">
                            <h4>登录小贴士</h4>
                            <p>游戏登录办法：1、用手机扫描二维码；
                                2、关注16游公众号，点击游戏大厅，在
                                个人中心的账户安全里有个人的网页端登陆密码，
                                输入密码即可进入游戏啦！</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="hery"></div>
        <div id="phone_S" class="main_phone main_po">
            <div class="full_screen">
            	<a class="openbtn" id="openbtn" href="#fullscreen"><em></em><span class="fulltxt">全屏模式</span></a>
            	<a class="closebtn" id="closebtn" href='#xiuli'><em></em><span class="fulltxt">关闭全屏</span></a>
            </div>
            <div class="main_list"> 
                <!-- 16游内容部分 -->
                <iframe id="yoyo" src="/game/list.html" frameborder="0" border="0px" marginwidth="0px" marginheight="0px" scrolling="auto" ></iframe>
            </div>
        </div> 
     </div>
        <div class="footer">
            <div class="foot">
                <div class="banner">
                    <ul>
                        <li class="wallow">
                            <a target="_blank" href="">防沉迷系统</a>
                        </li>
                        <li class="monitor">
                            <a target="_blank" href="">家长监控</a>
                        </li>
                        <li class="appeal">
                            <a target="_blank" href="">用户申诉</a>
                        </li>
                        <li class="service_btn">
                            <a target="_blank" href="">服务条款</a>
                        </li>
                    </ul>
                    <p>Copyright © 2017   广州野人网络科技有限公司
  网站备案号: 粤ICP备17019274号-1</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/js/jquery.min.js"></script>	
<script type="text/javascript">
<?php if(isset($verify)):?>
var verify = "<?php echo $verify; ?>";
function chlink() {
    $.ajax({
    	url:'/index/verifyuser.html',
    	type:'post',
    	dataType:'json',
    	data:{'verify':verify},
    	success:function(data){
            if(data.errorcode==0){
                clearInterval(timeID);//清除定时
                window.location.reload();//刷新页面
            }else{
    		    
            }
    	}
    });
}
/* $(function() {
    timeID = setInterval('chlink()', 2000);  //注意:执行的函数需要加引号,否则会报错的       
}); */
<?php endif;?>
	 var w=window.innerWidth;
	 var h=window.innerHeight;
	 var phoneHei=h-60;
	 $('.warp_mainbox,.warm_bg,.main_phone').css('height',h);
     $main_phone = $('.main_phone');
	 $main_phone.css({'width':h/1.8 ,'marginLeft':-(h/1.8/2)});
	 $main_phone.css('height',phoneHei+'px');
	 $yoyo = $("#yoyo");
     //开始游戏
     function playgame(id){
        $yoyo.attr('src','/start/index/'+id+'.html');
        return false;
     }
     //热门推广
     $(".matterList").mouseover(function(){
        $(this).css("background","#FFEABE");
     }).mouseout(function(){
        $(this).css("background",'');
     });
	//全屏模式
	 var thisid =window.location.hash;
     $warp_main = $('#warp_main');
     $openbtn = $('#openbtn');
     $closebtn = $("#closebtn");
	 if(thisid =='#fullscreen'){
		 $main_phone.css('height',h); 
		 $warp_main.addClass('full_screen_box');
		 $openbtn.hide();
		 $closebtn.show();
         $yoyo.css("width","105%");
	 }
     else if (thisid =='#xiuli') {
        $yoyo.css("width","100%");
     };
	 $openbtn.click(function(){
		$main_phone.css('height',h);
		$warp_main.addClass('full_screen_box');
		$openbtn.hide();
		$closebtn.show();
        $yoyo.css("width","105%");
	})
	$closebtn.click(function(){
		$main_phone.css('height',phoneHei+'px');
		$warp_main.removeClass('full_screen_box');
		$closebtn.hide();
		$openbtn.show();
        $yoyo.css("width","100%");
	})
	//一键登录
	$("#loginPcBtn").click(function(){
		//选择登录方式弹框
        $yoyo.attr('src','/personal/index.html');
        $(this).hide();
        return false;
    });	
	$("#pc-collection").click(function(){
		var $coll = $yoyo.contents().find("#collectionList");
		$coll.show();
		var $kownList = $yoyo.contents().find("#konwpc");
		$kownList.click(function(){
			$coll.hide();
		})
		return false;
	})

var _mtac = {};
(function() {
    var mta = document.createElement("script");
    mta.src = "http://pingjs.qq.com/h5/stats.js?v2.0.2";
    mta.setAttribute("name", "MTAH5");
    mta.setAttribute("sid", "500506326");
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(mta, s);
})();
</script>
