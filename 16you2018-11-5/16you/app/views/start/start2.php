<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/pc/css/common.css">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/pc/css/startgame.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/rem.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/jquery.min.js"></script>
	<script src="/media/js/1.js"></script> 
</head>
<script type="text/javascript"> 
var data = {};
data.page = 'startgame';
data.title =  "<?php echo $gname;?>";
window.parent.postMessage(data, '*');


var gid = "<?php echo isset($gid)?$gid:'';?>"
var gameurl = "<?php echo isset($game_url)?$game_url:'';?>";
var param = {};
param.gid = gid;
param.gameurl = gameurl;
parentlistener(param);
</script>
<body style="height:100%">
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
	   	 	    <span class="menuspan">礼包</span>
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
	     			<div class="headimg"><img src="<?php echo isset($user)?$user->head_url:'/media/images/noimg.jpg';?>" class="aa"></div>
	     			<div class="infobox">
					   <div class="nametext">
					   		<span class="namebox"><?php echo isset($user)?$user->username:'';?></span>
					   		<span id="vip-desrciption" class="vip_icon vip_icon<?php echo $user->vip; ?>"></span>
					   </div>
					   <div class="infortext">ID:<span><?php echo isset($user->Unique_ID)?$user->Unique_ID:'';?></span></div>
					</div>
	     		</div>
	     	</div>
	     </div>
		 <div class="popup_content">
		 	<div class="popup_menu">
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
		 	</div>
		 	<div style="clear:both;"></div>
		 	<!--内容-->
		 	<div class="game_popup_con">
		 		<!--客服-->
		 		<div id="div_server" class="game_popup_main">
		 			<div class="giftnum">
		 				<h5>官方QQ群独家礼包各种福利</h5>
		 				<div class="active_num">
		 					<a>178749290</a>
		 				</div>
		 				<h2><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/up_icon.png">点击添加官方QQ群</h2>
		 			</div>
		 			<div class="qrcode">
		 				<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/qq_b_default.png">
		 				<span>官方QQ群<br>独家礼包各种福利</span>
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
		 <div class="allbtn">
		 	<a class="game_popup_back">
		 		<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/back.png">
		 		返回
		 	</a>
		 	<a class="game_popup_refresh">
		 		<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/refresh_b.png">
		 		刷新
		 	</a>
		 </div>
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
	          <div class="weixinzhifu zhifu" id="6">
	             <ul class="zhifufs">
	             	<li class="zflione"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/wxzf.png"></li>
	             	<li class="zflitwo">
	             		<p>微信支付</p>
	             		<p class="zfp1">亿万用户的选择，更快更安全</p>
	             	</li>
	             	<li class="zflithree"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/arrowright.png"></li>
	             </ul>	 
	          </div> 
	          <div class="weixinzhifu zhifu" id="2" style="display:none;">
	             <ul class="zhifufs">
	             	<li class="zflione"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/wxzf.png"></li>
	             	<li class="zflitwo">
	             		<p>微信支付</p>
	             		<p class="zfp1">亿万用户的选择，更快更安全</p>
	             	</li>
	             	<li class="zflithree"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/arrowright.png"></li>
	             </ul>	 
	          </div> 
	          <div class="weixinzhifu zhifu" id="3">
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
	             		<p class="zfp1">亿万用户的选择，更快更安全</p>
	             	</li>
	             	<li class="zflithree"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/arrowright.png"></li>
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
</body>
<!-- <script type="text/javascript" src="http://wx.16you.com/media/js/jquery.cookie.js"></script>
<script src="http://pv.sohu.com/cityjson?ie=utf-8"></script>  -->
<script type="text/javascript"> 
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



</script>

<script src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/start.js?v1.0.5"></script>
</html>