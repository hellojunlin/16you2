<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/pc/css/common.css">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/pc/css/startgame.css?v=1.412">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/rem.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/jquery.min.js"></script>
	<script src="/media/js/1.js"></script> 
	<!-- 盛付通微信扫码转码文件jquery.qrcode.js、sftscan/qrcode.js -->
	<script src="<?php echo yii::$app->params['cdn16yous']; ?>/js/sftscan/jquery.qrcode.js" type="text/javascript"></script>
	<script src="<?php echo yii::$app->params['cdn16yous']; ?>/js/sftscan/qrcode.js" type="text/javascript"></script>
</head>
<script type="text/javascript"> 
var gid = "<?php echo isset($gid)?$gid:'';?>"
var gameurl = "<?php echo isset($game_url)?$game_url:'';?>";
var param = {};
param.gid = gid;
param.gameurl = gameurl;
parentlistener(param);

//判断访问终端
      	var browser={
          versions:function(){
      	        var u = navigator.userAgent, app = navigator.appVersion;
      	        return {
      	            trident: u.indexOf('Trident') > -1, //IE内核
      	            presto: u.indexOf('Presto') > -1, //opera内核
      	            webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
      	            gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1,//火狐内核
      	            mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
      	            ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
      	            android: u.indexOf('Android') > -1 || u.indexOf('Adr') > -1, //android终端
      	            iPhone: u.indexOf('iPhone') > -1 , //是否为iPhone或者QQHD浏览器
      	            iPad: u.indexOf('iPad') > -1, //是否iPad
      	            webApp: u.indexOf('Safari') == -1, //是否web应该程序，没有头部与底部
      	            weixin: u.indexOf('MicroMessenger') > -1, //是否微信 （2015-01-22新增）
      	            qq: u.match(/\sQQ/i) == " qq" //是否QQ
      	        };
      	    }(),
      	    language:(navigator.browserLanguage || navigator.language).toLowerCase()
      	}
      		//判断是否是移动端
      		if(browser.versions.mobile||browser.versions.android||browser.versions.ios){
      		    $('.mt_head').css('display','none');
      		    $('#game-iframe-div').css('top',0);
      		}

//终端设备
var _equipment = "<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
	  echo 'IOS';
}else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){ 
	  echo 'Android';
}else{
	  echo 'other';
};?>"; //设备
</script>
<body style="height:100%">
	<header class="mt_head">
        <span class="mt_goback"><img class="mt-dow" src="<?php echo yii::$app->params['cdn16you']; ?>/images/back.png"></span>
        <h1 class="mt_h">16游-<?php echo $gname; ?></h1>
    </header>
   	<div id="gamepage">
		<div class="adbg" <?php if(isset($start_img)&&!$start_img){echo 'style="display:none"';} ?> >
			<?php if(isset($start_img)&&$start_img): ?>
			<img class="abimg" src="<?php echo yii::$app->params['cdn']; ?>/plateform/<?php echo $start_img;?>">
			<?php endif; ?>
		</div>
	   	<div id="game-iframe-div">
	   	 	<iframe id="game-frame" name="gameFrame" frameborder="no" border="px" marginwidth="0px" marginheight="0px" scrolling="auto"  src="<?php echo $game_url;?>">
		   	</iframe>
	   	</div>
   	 	<!--按钮-->
	   	<div id="allbutton">
	   	 	<div class="game_menu_box">
	   	 	    <span class="menuspan"><?php echo ($logintype==1)?'礼包':'游客'?></span>
	   	 		<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/meanself.png">
	   	 	</div>
	   	</div>
   	</div>
    <!--游戏弹框-->
	<div id="startgamebigbox" style="display:none;">
	   <div id="startgamesmallbox">
	     <div class="game_popup_head">
	     	<div class="game_popup_top">
	     		<div class="back_home" onclick="window.location.href='/game/list.html'">
	     			<em><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/back_0.png"></em>
	     			首页
	     		</div>
	     		<div class="pop_head head_top_kong">
	     		    <?php $user = yii::$app->session['user'];?>
	     			<div class="headimg"><img src="<?php echo isset($user->head_url)?$user->head_url:'/media/images/noimg.jpg';?>" class="aa"></div>
	     			<div class="infobox">
					   <div class="nametext">
					   		<span class="namebox"><?php echo isset($user->username)?$user->username:'';?></span>
					   		<span id="vip-desrciption" class="vip_icon vip_icon<?php echo isset($user->vip)?$user->vip:''; ?>"></span>
					   </div>
					   <div class="infortext">ID:<span><?php echo isset($user->Unique_ID)?$user->Unique_ID:'';?></span></div>
					</div>
	     		</div>
	     		<a class="downbox" href="http://pc.16you.com/download/index.html"><img class="downloadimg" src="<?php echo yii::$app->params['cdn16you']; ?>/images/download02.png"></a>
	     	</div>
	     </div>
		 <div class="popup_content">
<!-- 		 	<div class="popup_menu">
		 		<ul class="menu_list">
		 			<li class="menu_server on">
		 				<a><i class="i_server"></i>
		 				<span>客服</span></a>
		 			</li>
		 			<li class="menu_gifts">
		 			  <a><i class="i_gifts"></i>
		 				<span>礼包</span> </a>
		 			</li>
		 			<li class="menu_game">
		 				<a><i class="i_game"></i>
		 				<span>游戏</span></a>
		 			</li>
		 		</ul>
		 	</div> -->
		 	<div style="clear:both;"></div>
		 	<!--内容-->
		 	<div class="game_popup_con">
		 		<!--客服-->
		 		<div id="div_server" class="game_popup_main">
		 			<div class="giftnum">
		 				<h5>游戏QQ群独家礼包各种福利</h5>
		 				<div class="active_num">
		 					<a>598452957</a>
		 				</div>
		 				<h2><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/up_icon.png">点击添加游戏QQ群</h2>
		 			</div>
		 			<div class="qrcode">
		 				<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/qq_b_default.png">
		 				<span>游戏QQ群<br>独家礼包各种福利</span>
		 			</div>
		 		</div>
		 	    <!--礼包-->
		 		<div id="div_gifts" class="game_popup_main">
		 			<div class="gift-list-box">
	 					<div class="title">
	 						<span></span>
	 						兑换方法
	 					</div>
		 				<div class="chargemethod">领取方式：主城-福利-礼包码兑换-输入激活码</div>
		 			</div>
		 			<div class="gift-list-box-1">
		 				<div class="title">
	 						<span></span>
	 						游戏礼包
	 					</div>
	 					<div class="allgifts" name='1'>
							<p class="describeno" style="display:none">暂时没有礼包</p>
	 					</div>
		 			</div>
		 		</div>
		 		<!--游戏-->
		 		<div id="div_game" class="game_popup_main">
		 		   <?php if(isset($playgame)&&!empty($playgame)):?>
		 			<div id="recentlyplay">
						<div class="title">
							<span></span>最近在玩</a>
						</div>
						<div class="game_list">
							<ul class="listul">
									<?php  foreach ($playgame as $play):?>
									<li class="game_start gamestart" name="<?php echo isset($play['id'])?$play['id']:'';?>">
										<img class="recentlyimg" src="<?php echo yii::$app->params['cdn']; ?>/game/<?php echo ($play['head_img'])?$play['head_img']:'notset.png';?>">
										<p><?php echo $play['name']?></p>
									</li>
								<?php endforeach;?>
						</div> 
					 </div>
					<?php endif;?> 
					<div id="hot-game">
						<div class="title">
							<span></span>热门游戏</a>
						</div>
					    <div class="hotgamelist"></div>
					</div>
				</div>
		 	</div>
		 </div>
		 <!--按钮-->
		<!--  <div class="allbtn">
		 	<a class="game_popup_back">
		 		<img src="<?php //echo yii::$app->params['cdn16you']; ?>/images/back.png">
		 		返回
		 	</a>
		 	<a class="game_popup_refresh">
		 		<img src="<?php //echo yii::$app->params['cdn16you']; ?>/images/refresh_b.png">
		 		刷新
		 	</a>
		 </div> -->
		 		 <!-- 侧边导航 star-->
		 <div class="sidebar">
	       <div class="popup_menu">
		 		<ul class="menu_list">
		 			<li class="menu_attention on">
		 				<a><i class="i_addt"></i>
		 				<span>关注</span></a>
		 			</li>
		 			<li class="menu_gifts">
		 			  <a><i class="i_gifts"></i>
		 				<span>礼包</span> </a>
		 			</li>
		 			<li class="menu_jinbang">
		 				<a><i class="i_jinbang"></i>
		 				<span>邮件</span></a>
		 				<span class="point" id="point"></span>
		 			</li>
		 			<li class="menu_game">
		 				<a><i class="i_game"></i>
		 				<span>游戏</span></a>
		 			</li>
		 			<li class="menu_info">
		 				<a><i class="i_info"></i>
		 				<span>资讯</span></a>
		 			</li>
		 			<li class="menu_server">
		 				<a><i class="i_server"></i>
		 				<span>客服</span></a>
		 			</li>
		 			<li class="menu_refresh game_popup_refresh">
		 				<a><i class="i_refresh"></i>
		 				<span>刷新</span></a>
		 			</li>
		 			<li class="menu_back game_popup_back">
		 				<a><i class="i_back"></i>
		 				<span>返回</span></a>
		 			</li>
		 		</ul>
		 	</div>
        </div>
       <!-- 侧边导航 end-->
		</div>
	   </div>
	</div>
		<!--弹框-领取礼包-->
	<div id="giftmodal" style="display:none;">
		<div class="servebox">
			<div class="get_img"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/get_img.png"></div>
			<img class="closeimg" src="<?php echo yii::$app->params['cdn16you']; ?>/images/close_gray.png">
			<h5></h5>
			<div class="gifttxt"></div>
			<div class="notice">兑换码每个服可用一次，明日可再次领取</div>
			<div class="active_num">
				<a href="#"></a>
			</div>
			<h2><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/up_icon.png">长按上方复制激活码</h2>
			<div class="ewm_box">
				<a>悬浮提示</a>
			</div>
			<div class="receive_btn">
				<a href='#'>开始游戏</a>
			</div>
		</div>
	</div>
	<!-- //退出游戏弹框 -->
	<div class="rank_bg" style="display:none">
		<div class="ptnList">
			<h1>更多好游戏尽在<i class="neImg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/16Ne.png"></i></h1>
			<span class="popup_close">
				<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/close_gray.png">
			</span>
			<div class="recently">
				<div class="gameatt">
					<p onclick="window.location.href='/game/list!<?php echo $puid;?>.html'" id="getup">
						<em>
							<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/meanself.png">
						</em>
						<span>更多游戏</span>
					</p>
				</div>
			</div>
			<div class="knowBtn">
				<a>离开游戏</a>
			</div>
		</div>
	</div>
	<!-- 弹框--二维码登录 -->
	<div id="pc-codeList" style="display:none">
		<div class="pc-codeListbox">
		   <div class="loadend">
			   <h1>微信扫码登录
			       <span id="close-pc-codeList">
			           <img class="closeChoseCode" src="/media/images/close_gray.png">
			       </span>
			   </h1>
			   <div id="loading" style='width: 100%;text-align: center;position: absolute;top: 1.86rem;'><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/loading.gif"></div>
			   <div><span class="codepcImg"><img src=""></span><p>扫描上方二维码进行登录</p></div>
		   </div>
		</div>
	</div>
	<!--支付二维码弹框 -->
	<div id="paycode" style="display: none">
		<div class="paycodebox">
			<span class="close_pay" style="color:#333;position:absolute;top:-0.1rem;right:0.1rem;font-size:0.6rem;">×</span>
			<img src="/media/images/codejiazai.gif"> 
			<p>亿万用户的选择，更快更安全</p>
			<p style="color:red">注：请用登录游戏的微信扫描支付</p>
		</div>
	</div>
			<!-- 充值模态框 -->
	<div class="rechangemodal" style="display:none;">
	   <div class="rechangebox">
	     <h4>支付</h4>
	     <img class="closezfmd" src="<?php echo yii::$app->params['cdn16you']; ?>/images/close_gray.png">
	     <p class="pall pallfs">16游</p>
	     <p class="paybox">¥<span class="paynum"></span></p>
	     <p class="pall">请选择支付方式</p>
	     <div class="selectpay">
	     <?php if($logintype==1): //游客态无法使用微信支付?> 
	          <!-- <div class="weixinzhifu zhifu" id="6">
	             <ul class="zhifufs">
	             	<li class="zflione"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/wxzf.png"></li>
	             	<li class="zflitwo">
	             		<p>微信支付</p>
	             		<p class="zfp1">亿万用户的选择，更快更安全</p>
	             	</li>
	             	<li class="zflithree"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/arrowright.png"></li>
	             </ul>	 
	          </div> -->
	             <?php //if(!empty(yii::$app->session->get('user'))):?>
		          <?php  //$user = yii::$app->session->get('user'); if($user->id==485421 || $user->id==236 || $user->id==237):?>
		          <div class="weixinzhifu zhifu zhifu_ptype" id="9"> <!-- id=9 -->
		             <ul class="zhifufs">
		             	<li class="zflione"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/wxzf.png"></li>
		             	<li class="zflitwo">
		             		<p>微信支付</p>
		             		<p class="zfp1">亿万用户的选择，更快更安全</p>
		             	</li>
		             	<li class="zflithree"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/arrowright.png"></li>
		             </ul>	 
		          </div> 
		          <?php //endif;?>
		           <?php //endif;?>
	          <?php endif;?>
	          <div class="weixinzhifu zhifu zhifubao_zhifu" id="12" style="display:none;">
	             <ul class="zhifufs">
	             	<li class="zflione"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/zfb.png"></li>
	             	<li class="zflitwo">
	             		<p>支付宝</p>
	             		<p class="zfp1">亿万用户的选择，更快更安全</p>
	             	</li>
	             	<li class="zflithree"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/arrowright.png"></li>
	             </ul>	 
	          </div> 
	          <div class="weixinzhifu  zhifu" id="5">
	             <ul class="zhifufs">
	             	<li class="zflione"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/kjzf.png"></li>
	             	<li class="zflitwo">
	             		<p>快捷支付</p>
	             		<p class="zfp1">全球主流的专业支付工具</p>
	             	</li>
	             	<li class="zflithree"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/arrowright.png"></li>
	             </ul>	 
	        </div>
	        <div class="weixinzhifu  zhifu" id="8">
	             <ul class="zhifufs">
	             	<li class="zflione"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/youbi.png"></li>
	             	<li class="zflitwo">
	             		<p>游币支付</p>
	             		<p class="zfp1">16游平台用户最爱支付工具</p>
	             	</li>
	             	<li class="zflithree"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/arrowright.png"></li>
	             </ul>	 
	        </div>
	        <div class="weixinzhifu  custom">
	             <ul class="zhifufs">
	             	<li class="zflione"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/images/custom.png"></li>
	             	<li class="zflitwo">
	             		<p>人工支付</p>
	             		<p class="zfp1">VIP-花花，联系微信VIN7390</p>
	             	</li>
	             	<li class="zflithree"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/images/arrowright.png"></li>
	             </ul>	 
	        </div>
	     </div>
	    <form action="" method="post" id="forminput">
	       <span class="hiddeninput"></span>
	    </form>
	   </div>
	</div>
	<!-- 资料填写 -->
<!--  	<div class="infomodal">
	  <div class="fillinfobox">
	     <img class="closeinfomd" src="<?php //echo yii::$app->params['cdn16you']; ?>/pc/images/close_gray.png">
	     <div class="nameinfo">
	       <p>姓名：</p>
	       <input type="text" class="infoname" placeholder="请输入你的真实姓名" / >
	     </div>
	     <div class="nameinfo">
	       <p>手机：</p>
	       <input type="text" class="infotel" placeholder="请输入手机号码" / >
	     </div>
	     <p class="errortip">请填写完整信息！！！</p>
	     <button class="surebtn zhifu" id="5">确定</button>
	  </div>
	</div> -->
	<!-- load -->  
	<div class="loadbox">
	   <div class="loadimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/loading.gif"></div>
	</div>
	<!-- 微信关注弹框 -->
	<div id="wxmodal" style="display:none;">
		<div class="wxbox">
		   <div class="wxsmallbox">
		   		<img class="closecodemodal" id="closecodemodal" src="http://wx.16you.com/media/images/closewhite.png">
				<img class="wxcodeimg" src="<?php echo yii::$app->params['frontend']?>/media/images/qrcode.jpg">
		   </div>
			<p class="tipcode">微信扫码，关注16游微信公众号</p>
		</div>
	</div>
	<!-- 分享弹框 -->
	<div id="wxmodalShare" style="display:none;">
		<div class="wxboxShare">
		   <div class="wxsmallboxShare">
				<img class="wxcodeimg1" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/share.png">
		   </div>
			<p class="tipcode1">爱我你就转发，不爱我就转给爱我的人</p>
		</div>
	</div>
	<!-- 人工支付弹框 -->
	<div id="wxmodalCustom" style="display:none;">
		<div class="wxboxCustom">
		   <div class="customimg"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/images/zhifucustom.jpg"></div>
		   <p class="customtips">长按识别二维码，联系客服</p>
		   <button class="ikonwbtn">我知道了</button>
		</div>
	</div>
	<?php if($logintype==2 && yii::$app->session['access_token']==''):?>
	<!-- 选择登录方式 -->
	  <div class="login_bg" id="loginWay">
	  	<div class="summation">
	  		<div class="text_header">
		  		<ul class="hd_content">
		  			<li class="active_login">登录</li>
		  			<li>游客</li>
		  		</ul>
		  	</div>
		  	<div class="wayContent">
		  		<ul class="bd_c">
		  			<li class="way_two">
		  				<div class="wechat_icon" id="wechatLogin">
		  					<span>
		  						<img src="<?php echo yii::$app->params['cdn16you']; ?>/app/images/wechat_icon.png">
		  					</span>
		  					<p>微信登录</p>
		  				</div>
		  				<div class="mobileLogin" id="mobileLogin">
		  					<span>
		  						<img src="<?php echo yii::$app->params['cdn16you']; ?>/app/images/phone_icon.png">
		  					</span>
		  					<p>账号登录</p>
		  				</div>
		  			</li>
		  		</ul>
		  		<ul class="bd_f" style="display:none;">
		  			<div class="tourist">
		  				<p>
		  					以游客形式进入游戏，将不会保存您的任何游戏记录
		  				</p>
		  				<div class="loBtn">
		  					<div class="enterGame visitTourist">
		  						<a href="#">进入游戏</a>
		  					</div>
		  				</div>
		  			</div>
		  		</ul>
		  	</div>
	  	</div>
	  </div>
	  <?php endif;?>
    <!-- 弹框--选择账号登录 -->
	<div id="pc-account" style="display:none">
		<div class="accountbox">
		    <h1>登录
		       <span id="close-pc-account">
		           <img class="closeChoseCode" src="/media/images/close_gray.png">
		       </span>
		    </h1>
		    <div class="pc-userNumber pc-userInput">
		    	<span>账号</span>
		    	<input type="text" name="Unique_ID" placeholder="请输入用户ID/手机号码" maxlength="11" />
		    </div>
		    <div class="pc-passWord pc-userInput">
		    	<span>密码</span>
		    	<input type="password" name="password" placeholder="请输入密码"/>
		    </div>
		    <div id="sign_bot_box">
		   		<a class="sign_btn" href="#">登录</a>
		   	</div>
		</div>
	</div>
	<!-- 盛付通扫码充值 -->
	<div class="bitmap" id="bitmap">
		<div class="backmap">
			<!-- <h1>微信扫码支付</h1> -->
			<img class="closebitmap" id="closebitmap" src="http://cdn16you.zqqgl.com/pc/images/icon_close-2.png">
			<div id="div_div" style="width: 400px;margin: 7% 6.5%;"></div>
			<div id="show_url"></div>
			<p class="fine">亿万用户的选择，更快更安全</p>
			<p class="finetip">请用登录游戏的微信扫描支付</p>
		</div>
	</div>
</body>
<!-- <script type="text/javascript" src="http://wx.16you.com/media/js/jquery.cookie.js"></script>
<script src="http://pv.sohu.com/cityjson?ie=utf-8"></script>  -->
<script type="text/javascript"> 
if(_equipment=='other'){
	$(".zhifu_ptype").attr('id','10');  //6微信扫码支付  10优赋扫码支付
	$(".zhifubao_zhifu").attr('id','11');
}
var logintype = "<?php echo $logintype;?>";//用户登录类型 1：会员  2：游客
var islogin = "<?php echo isset(yii::$app->session['user'])?'true':(isset(yii::$app->session['access_token'])?'true':'false');?>";
	// 选择登录方式选项卡
		$(function(){
	        var $div_li =$(".text_header ul li");
	        $div_li.click(function(){
	            $(this).addClass("active_login")
	                   .siblings().removeClass("active_login");  
	            var index =  $div_li.index(this); 
	            $(".wayContent > ul")       
	                    .eq(index).show()   
	                    .siblings().hide(); 
	        })
	    })
	//弹框--选择账号登录
	    $("#mobileLogin").click(function(){
	    	$("#loginWay").hide();
	     	$("#pc-account").show();
	    })
		//关闭账号登录
		$("#close-pc-account").click(function(){
			$("#pc-account").hide();
			$("#loginWay").show();
		})
	    //关闭公众号弹框
	    $('#closecodemodal').click(function(){
	    	$('#wxmodal').hide();
	    })
	    //关闭分享弹框
	    $('#wxmodalShare').click(function(){
	    	$('#wxmodalShare').hide();
	    })
	    // 关闭微信登录弹框
	    $("#close-pc-codeList").click(function(){
			clearInterval(timeID1);//清除定时
			$("#pc-codeList").hide();
			$('.codepcImg').find('img').attr('src','');
		})
		 //打开人工客服
		 $('.custom').on('click',function(){
		 	$('.rechangemodal').hide();
		 	$('#wxmodalCustom').show();
		 })
		 //关闭客服弹框
		 $('.ikonwbtn').click(function(){
		 	$('#wxmodalCustom').hide();
		 	$('.rechangemodal').show();
		 })
	var payurl = "<?php echo isset($payurl)?$payurl:'';?>";
	var _equipment = "<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
					echo 'IOS';
				}else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
	   				 echo 'Android';
				}else{
	 			     echo 'other';        
				};?>"; //设备
	var backurl = "<?php echo yii::$app->params['backend'];?>";
var _mtac = {};
(function() {
    var mta = document.createElement("script");
    mta.src = "http://pingjs.qq.com/h5/stats.js?v2.0.2";
    mta.setAttribute("name", "MTAH5");
    mta.setAttribute("sid", "500506326");
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(mta, s);
})();

$('#wechatLogin').click(function(){
	$("#pc-codeList").show();
	$('#loginWay').hide();
	$('.loadend').show();
	$('#loading').show();
	  loginwechat();
})

//弹框--微信二维码登录
function loginwechat(){
	$.ajax({
    	url:'/personal/code.html',
    	type:'post',
    	dataType:'json',
    	success:function(data){
            if(data.errorcode==0){
            	$('#loading').hide();
            	if(_equipment!="other"){
            		window.location.href="/personal/codelogin.html?code="+data.verify+'!*%@'+data.info;
            	}else{
                	console.log(data.info);
	                $(".codepcImg>img").attr('src','/media/images/code/'+data.info);
	                var verify = data.verify;
				     timeID1 = setInterval(function(){
					    $.ajax({
					    	url:'/index/verifyuser.html',
					    	type:'post',
					    	dataType:'json',
					    	data:{'verify':verify},
					    	success:function(data){
					            if(data.errorcode==0){
				                	// parent.location.reload();
				                	// 刷新游戏ifram
									// $('#game-frame').location.reload(true);
									 $("#loginWay").hide();
					            	 $("#pc-codeList").hide();
					            	 self.location.reload();
					            }else{
					    		    console.log(data.info);
					            }
					    	}
					    });
					}, 2000);     
            	}
            }else{
    		    alert(data.info);
            }
    	}
    });
}

//游客登陆
$('.visitTourist').click(function(){
	$('#loading').show();
	$.ajax({
		url:'/start/touristlogin.html',
		type:"post",
		dataType:'json',
		success:function(data){
			if(data.errorcode==0){
            	// parent.location.reload();
            	$("#loginWay").hide();
            	$('#loading').hide();
            	$('.rank_bg').hide();
            	// 刷新游戏ifram
				self.location.reload();
			}else{
				alert(data.info);
				$("#loginWay").show();
				$('#loading').hide();
			}
		}
	});
})

//账号登录 
$("#sign_bot_box").click(function(){
	var id = $("input[name='Unique_ID']").val();
	var pwd = $("input[name='password']").val();
	if(!id || !pwd){
		alert("账户或密码不能为空");
		return false;
	}else{
		$.ajax({
			url:'/personal/userlogin.html',
			type:"post",
			data:{'id':id,'pwd':pwd},
			dataType:'json',
			success:function(data){
				if(data.errorcode==0){
					 self.location.reload();
				}else{
					alert(data.info);
				}
			}
		});
	}
})

if(islogin=='true'){
	saveplayuser();
}


function saveplayuser() {
	$.ajax({
		url: '/start/saveplayuser.html',
		type: 'post',
		data: {
			'gid': gid,
			'usertype' : logintype,
		},
		dataType: 'json',
		success: function(data) {
			console.log(data);
		}
	})
} 

/**
 * 转码
 */
function utf16to8(str) { 
	var out, i, len, c;
	out = "";
	len = str.length;
	for (i = 0; i < len; i++) {
		c = str.charCodeAt(i);
		if ((c >= 0x0001) && (c <= 0x007F)) {
			out += str.charAt(i);
		} else if (c > 0x07FF) {
			out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));
			out += String.fromCharCode(0x80 | ((c >> 6) & 0x3F));
			out += String.fromCharCode(0x80 | ((c >> 0) & 0x3F));
		} else {
			out += String.fromCharCode(0xC0 | ((c >> 6) & 0x1F));
			out += String.fromCharCode(0x80 | ((c >> 0) & 0x3F));
		}
	}
	return out;
}
</script>
<script src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/start.js?v1.4.0"></script>

</html>