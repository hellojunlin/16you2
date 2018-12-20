<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/app/css/common.css">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/app/css/startgame.css?v=3.71">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/app/js/rem.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/app/js/jquery.min.js"></script>
	<script src="/media/js/1.js?v1.0.1"></script> 
	<script src="<?php echo yii::$app->params['cdn16yous']; ?>/js/sftscan/jquery.qrcode.js" type="text/javascript"></script>
	<script src="<?php echo yii::$app->params['cdn16yous']; ?>/js/sftscan/qrcode.js" type="text/javascript"></script>
	<title><?php echo $pname.'-'.$gname; ?></title>
</head>
<script type="text/javascript"> 
var gid = "<?php echo isset($gid)?$gid:'';?>"
var gameurl = "<?php echo isset($game_url)?$game_url:'';?>";
var param = {};
param.gid = gid;
param.gameurl = gameurl;
parentlistener(param);
	
var data = {};
data.page = 'startgame';
data.title =  "<?php echo $gname;?>";
data.gameurl = "<?php echo isset($gid)?'http://wx.16you.com/start/index/'.$gid.'.html':'';?>";
data.imgurl = "<?= isset($firend_share['imgurl'])?$firend_share['imgurl']:yii::$app->params['cdn16you'].'/images/icon_mean.png';?>";
data.content = "<?= isset($firend_share['desc'])?$firend_share['desc']:'16游精品游戏';?>"
window.parent.postMessage(data, '*');

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
   	<div id="gamepage">
		<div class="adbg" <?php if(!$start_img){echo 'style="display:none"';} ?> ><img class="abimg" src="<?php echo yii::$app->params['cdn']; ?>/plateform/<?php echo $start_img;?>"></div>
	   	<div id="game-iframe-div">
	   	 	<iframe id="game-frame" name="gameFrame" frameborder="no" border="px" marginwidth="0px" marginheight="0px" scrolling="auto"  src="<?php echo $game_url;?>">
		   	</iframe>
	   	</div>
   	 	<!--按钮-->
	   	<div id="allbutton">
	   	 	<div class="game_menu_box">
	   	 		<span class="menuspan"><?php echo ($logintype==1)?'礼包':'游客'?></span>
	   	 		<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/meanself.png">
	   	 	</div>
	   	</div>
   	</div>
   	<!--游戏弹框-->
	<div id="startgamebigbox" style="display:none;">
	   <div id="startgamesmallbox">
	     <div class="game_popup_head">
	     	<div class="game_popup_top">
	     		<div class="back_home" onclick="window.location.href='/index/index!<?php echo $puid;?>.html'">
	     			<em><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/back_0.png"></em>
	     			首页
	     		</div>
	     		<div class="pop_head head_top_kong">
	     		    <?php $user = yii::$app->session['user'];?>
	     			<div class="headimg"><img src="<?php echo $user?$user->head_url: yii::$app->params["cdn16yous"].'/images/noimg.jpg';?>" class="aa"></div>.'
	     			<div class="infobox">
					    <div class="nametext">
					   		<span class="namebox"><?php echo $user?$user->username:'';?></span>
							<span id="vip-desrciption" class="vip_icon vip_icon<?php echo $user?$user->vip:0;?>"></span>
					    </div>
						<div class="infortext">ID:<span><?php echo $user?$user->Unique_ID:'';?></span></div>
					</div>
	     		</div>
	     	</div>
	     </div>
		 <div class="popup_content">
		 	<div style="clear:both;"></div>
		 	<!--内容-->
		 	<div class="game_popup_con">
		 		<!--关注-->
		 		<div id="div_code" class="game_popup_main">
		 			<h4>长按下方二维码关注哦</h4>
		 			<div class="qrcode">
		 				<img src="/media/images/code_sml.jpg">
		 				<div class="active_num">
		 					<a>386570215</a>
		 				</div>
		 				<span>进游戏QQ群领取关注礼包</span>
		 			</div>
				</div>
				<!--关注 end-->
		 		
		 	    <!--礼包-->
		 		<div id="div_gifts" class="game_popup_main">
		 			<div class="gift-list-box">
	 					<div class="title">
	 						<span></span>
	 						兑换方法
	 					</div>
		 				<div class="chargemethod"></div>
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
		 		<!--邮件-->
		 		<div id="div_email" class="game_popup_main" style="display:none">
					<div class="email_m">
						<div class="mine_email">
							<h2 class="mine_e">我的邮件</h2>
							<em class="mineImg">
								<img src="/media/images/unread.png">
							</em>
							<div class="email_num">
								(<b>0</b>/<b>0</b>)
							</div>
						</div>
						<div class="email_content">
							<ul class="email_list" id="email_list"></ul>
						</div>
						<div class="allgifts1">
							<p class="describeno" style="display:none">暂时没有邮件</p>
	 					</div>
					</div>
					<div id="wrap">
                		<div class="container">
                      		<div style="text-align:center;clear:both">
                      	</div>
                      	<div class="folder">
                          	<div class="paper">
	                          	<h1></h1>
	                          	<h2>尊敬的玩家：</h2>
	                            <p>您将获得新手礼包一份，请注意查收哦！案发后时代峻峰后大卡卡即可拿姐姐说的氨基酸看风景阿克苏觉得看见那就是卡接收到会计法阿基拉健康了健康那尽快的尽快啦卡卡了积分俺是单身美女打</p>
                          	</div>
	                        <div class="cover">
	                            <div class="title">点击展信</div>
	                        </div>
                          	<p class="code_btn">
                            	<a href="#" class="a_demo_two">关闭 </a>
                          	</p>
                      	</div>
                    </div>
                	</div>
                	<div id="servemodal_m" style="display:none">
                		<div class="servebox">
                			<h1 class="servetitle"></h1>
                			<img class="closeimg" id="closeimg" src="<?php echo yii::$app->params['cdn16yous']?>/images/close_gray.png">
                			<p class="delete_email">确定要删除吗？</p>
                			<div class="receive_btn" id="receive_btn">
                			<a>确定</a>
                			</div>
                		</div>
                	</div>
				</div>
		 		<!--游戏-->
		 		<div id="div_game" class="game_popup_main">
		 			<div id="recentlyplay">
					 </div>
					<div id="hot-game">
						<div class="title">
							<span></span><a>热门游戏</a>
						</div>
					    <div class="hotgamelist"></div>
					</div>
				</div>
				<!-- 资讯 -->
				<div id="div_info" class="game_popup_main game_list_box cons" name="1"></div>
				<!--客服-->
		 		<div id="div_server" class="game_popup_main" style="display:none">
		 			<!-- <div class="giftnum">
		 				<h5>官方QQ群独家礼包各种福利</h5>
		 				<div class="active_num">
		 					<a>386570215</a>
		 				</div>
		 				<h2><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/up_icon.png">点击添加官方QQ群</h2>
		 			</div>
		 			<div class="qrcode">
		 				<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/qq_b_default.png">
		 				<span>官方QQ群<br>独家礼包各种福利</span>
		 			</div> -->
		 			<img class="kfg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/kfg.gif">
		 		</div>
		 	</div>
		 </div>
		 <!--按钮-->
		 <!-- <div class="allbtn">
		 	<a class="game_popup_back">
		 		<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/back.png">
		 		返回
		 	</a>
		 	<a class="game_popup_refresh">
		 		<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/refresh_b.png">
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

		<!--弹框-领取礼包-->
	<div id="giftmodal" style="display:none;">
		<div class="servebox">
			<div class="get_img"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/get_img.png"></div>
			<img class="closeimg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/close_gray.png">
			<h5></h5>
			<div class="gifttxt"></div>
			<div class="notice">兑换码每个服可用一次，明日可再次领取</div>
			<div class="active_num">
				<a></a>
			</div>
			<h2><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/up_icon.png">长按上方复制激活码</h2>
			<div class="ewm_box">
				<a>悬浮提示</a>
			</div>
			<div class="receive_btn">
				<a>开始游戏</a>
			</div>
		</div>
	</div>
	<!-- //退出游戏弹框 -->
	<div class="rank_bg" style="display:none">
		<div class="ptnList">
			<h1>更多好游戏尽在<i class="neImg"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/16Ne.png"></i></h1>
			<span class="popup_close">
				<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/close_gray.png">
			</span>
			<div class="recently">
				<div class="gameatt">
					<p onclick="window.location.href='/index/index!<?php echo $puid;?>.html'" id="getup">
						<em>
							<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/meanself.png">
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
	<!-- //悬浮球内置弹框 -->
	<div class="boodrg" style="display:none">
		<div class="ptnList">
            <h1></h1>
			<span class="popup_close">
				<img src="/media/images/close_gray.png">
			</span>
            <div class="scrollList" style="overflow:scroll;"></div>
		</div>
	</div>
			<!-- 充值模态框 -->
	<div class="rechangemodal" style="display:none;">
	   <div class="rechangebox">
	     <h4>支付</h4>
	     <img class="closezfmd" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/close_gray.png">
	     <p class="pall pallfs">16游</p>
	     <p class="paybox">¥<span class="paynum"></span></p>
	     <p class="pall">请选择支付方式</p>
	     <div class="selectpay">
		      <div class="weixinzhifu zhifu" id="9" style="display:block;">
		             <ul class="zhifufs">
		             	<li class="zflione"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/images/wxzf.png"></li>
		             	<li class="zflitwo">
		             		<p>微信支付</p>
		             		<p class="zfp1">亿万用户的选择，更快更安全</p>
		             	</li>
		             	<li class="zflithree"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/images/arrowright.png"></li>
		             </ul>	 
		      </div> 
	          <div class="weixinzhifu zhifu zhifubao_zhifu" id="12" style="display:none;">
	             <ul class="zhifufs">
	             	<li class="zflione"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/images/zfb.png"></li>
	             	<li class="zflitwo">
	             		<p>支付宝</p>
	             		<p class="zfp1">亿万用户的选择，更快更安全</p>
	             	</li>
	             	<li class="zflithree"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/images/arrowright.png"></li>
	             </ul>	 
	          </div> 
	          
	          <div class="weixinzhifu  zhifu" id="5">
	             <ul class="zhifufs">
	             	<li class="zflione"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/images/kjzf.png"></li>
	             	<li class="zflitwo">
	             		<p>快捷支付</p>
	             		<p class="zfp1">全球主流的专业支付工具</p>
	             	</li>
	             	<li class="zflithree"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/images/arrowright.png"></li>
	             </ul>	 
	        </div>
	        
	        <div class="weixinzhifu  zhifu" id="7" style="display:none;">
	             <ul class="zhifufs">
	             	<li class="zflione"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/images/kjzf.png"></li>
	             	<li class="zflitwo">
	             		<p>微信扫码支付</p>
	             		<p class="zfp1">亿万用户的选择，更快更安全</p>
	             	</li>
	             	<li class="zflithree"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/images/arrowright.png"></li>
	             </ul>	 
	        </div>
	        
	        <div class="weixinzhifu  zhifu" id="8">
	             <ul class="zhifufs">
	             	<li class="zflione"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/images/youbi.png"></li>
	             	<li class="zflitwo">
	             		<p>游币支付</p>
	             		<p class="zfp1">16游平台用户最爱支付工具</p>
	             	</li>
	             	<li class="zflithree"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/images/arrowright.png"></li>
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
	     <img class="closeinfomd" src="<?php //echo yii::$app->params['cdn16yous']; ?>/pc/images/close_gray.png">
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
	   <div class="loadimg"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/images/loading.gif"></div>
	</div>
	<!-- 微信关注弹框 -->
	<div id="wxmodal" style="display:none;">
		<div class="wxbox">
		   <div class="wxsmallbox">
		   		<img class="closecodemodal" id="closecodemodal" src="http://wx.16you.com/media/images/closewhite.png">
				<img class="wxcodeimg" src="<?php echo yii::$app->params['frontend']?>/media/images/qrcode.jpg">
		   </div>
			<p class="tipcode">截图到微信扫码，关注16游微信公众号</p>
		</div>
	</div>
	<!-- 分享弹框 -->
	<div id="wxmodalShare" style="display:none;">
		<div class="wxboxShare">
		   <div class="wxsmallboxShare">
				<img class="wxcodeimg1" src="<?php echo yii::$app->params['cdn16yous']; ?>/app/images/share.png">
		   </div>
			<p class="tipcode1">爱我你就转发，不爱我就转给爱我的人</p>
		</div>
	</div>
	<!-- 客服弹框 -->
	<div id="wxmodalCustom" style="display:none;">
		<div class="wxboxCustom">
		   <div class="customimg"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/app/images/zhifucustom.jpg"></div>
		   <p class="customtips">长按识别二维码，联系客服</p>
		   <button class="ikonwbtn">我知道了</button>
		</div>
	</div>
	<!-- 选择登录方式 -->
	<?php if($logintype==2 && yii::$app->session['access_token']==''):?>
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
		  						<img src="<?php echo yii::$app->params['cdn16yous']; ?>/app/images/wechat_icon.png">
		  					</span>
		  					<p>微信登录</p>
		  				</div>
		  				<div class="mobileLogin" id="mobileLogin">
		  					<span>
		  						<img src="<?php echo yii::$app->params['cdn16yous']; ?>/app/images/phone_icon.png">
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
		  					<div class="enterGame visitTourist" >
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
	<!-- 弹框--二维码登录 -->
	<div id="pc-codeList" style="display:none">
		<div class="pc-codeListbox">
		   <div class="loadend">
			   <h1>微信扫码登录
			       <span id="close-pc-codeList">
			           <img class="closeChoseCode" src="/media/images/close_gray.png">
			       </span>
			   </h1>
			   <div id="loading" style='width: 100%;text-align: center;position: absolute;top: 1.86rem;'><img src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/images/loading.gif"></div>
			   <div><span class="codepcImg"><img src=""></span><p>扫描上方二维码进行登录</p></div>
		   </div>
		</div>
	</div>
<!-- 	<div>123</div>
	<div id="div_div" style="width:400px;height:400px;border:1px solid #000;">1123</div>
	<div style="display: none;" id="show_url">
	
	</div> -->
	<!-- 扫码支付 -->
	<div class="bitmap" id="bitmap">
		<div class="backmap">
			<h1>扫码快捷支付</h1>
			<img class="closebitmap" id="closebitmap" src="http://wx.16you.com/media/images/closewhite.png">
			<div id="div_div" style="width: 400px;margin: 9% 5%;"></div>
			<div id="show_url"></div>
			<p class="fine">亿万用户的选择，更快更安全</p>
			<p class="finetip">请用登录游戏的微信扫描支付</p>
		</div>
	</div>
<script>
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
			$("#pc-codeList").hide();
			$('#loginWay')
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
  	var _mtac = {"performanceMonitor":1,"senseQuery":1};
  	(function() {
  		var mta = document.createElement("script");
  		mta.src = "http://pingjs.qq.com/h5/stats.js?v2.0.4";
  		mta.setAttribute("name", "MTAH5");
  		mta.setAttribute("sid", "500504927");
  		mta.setAttribute("cid", "500504955");
  		var s = document.getElementsByTagName("script")[0];
  		s.parentNode.insertBefore(mta, s);
  	})();
</script>
</body>
<!-- <script type="text/javascript" src="/media/js/jquery.cookie.js"></script> -->
<!-- <script src="http://pv.sohu.com/cityjson?ie=utf-8"></script>  -->
<script type="text/javascript">
console.log(_equipment);
if(_equipment=='other'){
	$(".zhifubao_zhifu").attr('id','11');  //支付宝网页扫码支付
}
var cdnurl = "<?php echo yii::$app->params['cdns']; ?>" ;
var payurl = "<?php echo isset($payurl)?$payurl:'';?>";

var logintype = "<?php echo $logintype;?>"; //用户登录类型 1：会员  2：游客
var islogin = "<?php echo isset(yii::$app->session['user'])?'true':(isset(yii::$app->session['access_token'])?'true':'false');?>";
//广告图片
$(function(){
  	window.addEventListener('load',function(){
		setTimeout(function(){
			$('.adbg').hide();
		},2000);
	});
});
var backurl = "<?php echo yii::$app->params['backend']?>";
//选卡切换
var pagenum = 1;
var index = 0;  //选项卡下标
var databool = true;
var databool1 = true;
var datemail = true;
var datinformation = true;
var consult_boolean = '';
$('.menu_list li').click(function() {
	index = $(this).index();
	$(this).addClass('on').siblings().removeClass('on');
	$('.game_popup_con div.game_popup_main').eq(index).show().siblings().hide();
	(index == 1) && giftinfo();
	if(databool1 && index==3){
		getgameinfo(1);
		getnewplay();
	};//游戏
	(datemail && index==2) && getemail();//邮件
	(datinformation && index==4) && getinformation();//资讯
});
$('.game_popup_back').click(function() {
	$('#startgamebigbox').hide();
	setTimeout(function() {
		$('.game_menu_box').removeClass('onhit')
	}, 1000)
});
$('body').on('click', '.receive', function() {
	var obj = $(this);
	var name = obj.attr('name').split('%$#');
	$.ajax({
		url: '/gift/gift.html',
		type: 'post',
		data: {
			'number': name['0']
		},
		dataType: 'json',
		success: function(data) {
			var info = data.info;
			if (data.errorcode == 0) {
				$('.servebox h5').html(info.gift_name);
				$('.gifttxt').html(info.content);
				$('.active_num>a').html(info.CDKEY);
				$('.receive_btn>a').attr('href', '/start/index/' + info.gid + '.html');
				$('#giftmodal').show();
				obj.html('查看').css('background', '#fed134')
			} else {
				alert(info)
			}
		}
	})
});
$('.closeimg,.receive_btn').click(function() {
	$('#giftmodal').hide()
});
$('.game_popup_refresh').click(function() {
	location.reload()
});

$('#closebitmap').click(function() {
	$('#bitmap').hide()
});
var threegame_s = true;
$(function() {
	pushHistory();
	window.addEventListener('load', function() {
		setTimeout(function() {
			window.addEventListener('popstate', function(e) {
				if (threegame_s == true) {
					$.ajax({
						type: 'post',
						data: {
							'gid': gid
						},
						url: '/start/threegame.html',
						dataType: 'json',
						success: function(data) {
							if (data.errorcode == 0) {
								var str = '';
								$.each(data.info, function(k, v) {
									var head_img = (v.head_img) ? v.head_img : 'notset.png';
									str += '<p class="gamestart"  onclick="httpget('+v.id+')"><em><img src="' + cdnurl + '/game/' + v.head_img + '"></em><span>' + v.name + '</span></p>'
								});
								$('#getup').before(str)
							}
						}
					});
					threegame_s = false
				}
				$('.rank_bg').css('display', 'block');
				$('.knowBtn').click(function() {
					if (document.referrer == 0) {
						window.location.href = '/index/index!<?php echo $puid;?>.html'
					} else {
						window.history.back()
					}
				});
				$('.popup_close').click(function() {
					$('.rank_bg').css('display', 'none');
					pushHistory()
				})
			})
		}, 0)
	});

	function pushHistory() {
		var state = {
			title: "title",
			url: "#"
		};
		window.history.pushState(state, "title", "#")
	}
});
$('#div_game').on('click', '.gamestart', function() {
	var _this = $(this);
	var gid = _this.attr('name');
	if (window.navigator.onLine == false) {
		alert('网络异常,请确保网络畅通')
	}
	window.location.href = '/start/index/' + gid + '.html';
});

function httpget(gid) {
	window.location.href = '/start/index/' + gid + '.html'
}
var newplayboolean = true;

function getnewplay() {
	if (newplayboolean) {
		$.ajax({
			url: '/start/getnewplay.html',
			dataType: 'json',
			type: 'post',
			success: function(data) {
				var info = data.info;
				if (info) {
					var div = $('<div>').addClass('title').appendTo($('#recentlyplay'));
					$('<span>').appendTo(div);
					div.append('最近在玩');
					var div2 = $('<div>').addClass('game_list').appendTo($('#recentlyplay'));
					var ul = $('<ul>').addClass('listul').appendTo(div2);
					$.each(info, function(k, v) {
						var li = $('<li>').addClass('game_start gamestart').attr('name', v.id).appendTo(ul);
						$('<img>').addClass('recentlyimg').attr('src', cdnurl + '/game/' + v.head_img).appendTo(li);
						$('<p>').append(v.name).appendTo(li)
					});
					newplayboolean = false
				}
			}
		})
	}
}
function getgameinfo(page) {
	databool1 = false;
	$("#hotgame").append('<p class="tmodel" style="text-align: center;color: #666;font-size: 0.2rem; padding: 0.1rem 0;">正在加载...</p>');
	$.ajax({
		url: '/start/gethotgame.html',
		data: {
			'page': page
		},
		dataType: 'json',
		type: 'post',
		success: function(data) {
			var info = data.info;
			if (data.errorcode == 0) {
				$.each(info, function(k, v) {
					var div = $('<div>').addClass('game_list_box').appendTo($('.hotgamelist'));
					var ul = $('<ul>').addClass('game_ul').appendTo(div);
					var li1 = $('<li>').addClass('game_img ').appendTo(ul);
					var head_img = (v.head_img) ? v.head_img : 'notset.png';
					$('<img>').attr('src', cdnurl + "/game/" + v.head_img).appendTo(li1);
					var li2 = $('<li>').addClass('game_describe').appendTo(ul);
					var p1 = $('<p>').appendTo(li2);
					$('<span>').addClass('game_name').append(v.name).appendTo(p1);
					$('<p>').addClass('describe').append(v.descript).appendTo(li2);
					var li3 = $('<li>').addClass('game_start gamestart').attr('name', v.id).appendTo(ul);
					$('<a>').addClass('start').append('开始').appendTo(li3)
				});
				pagenum++;
				databool1 = true
			} else {
				$("#hotgame").append('<div class="nodata" align="center">数据加载完成</div>')
			}
			$(".tmodel").remove()
		}
	})
};

function giftinfo() {
	databool = false;
	if (($('.describeno').css('display') == 'none') && !$('.allgifts>.tmodel').length) {
		var page1 = $(".allgifts").attr('name');
		$(".allgifts").append('<p class="tmodel">正在加载...</p>');
		$.ajax({
			url: '/start/getgift.html',
			data: {
				'page': page1,
				'gid': gid
			},
			dataType: 'json',
			type: 'post',
			success: function(data) {
				var info = data.info;
				if (data.errorcode == 0) {
					$.each(info, function(kg, vg) {
						if (($.trim($(".chargemethod").html()).length == 0) && (vg.payment != '')) {
							$(".chargemethod").html(vg.payment)
						}
						if (vg.num.length > 2) {
							vg.num1 = vg.num / (Math.pow(10, vg.num.length - 2))
						} else {
							vg.num1 = vg.num
						}
						$(".allgifts>.tmodel").before('<div class="game_list_box"><ul class="game_ul"><li class="game_img"><img src="' + cdnurl + '/game/' + vg.game_image + '"/></li><li class="game_describe"><p><span class="game_name">' + vg.game_name + ':<i>' + vg.gift_name + '</i></span></p><p class="describe">' + vg.content + '</p><div class="package_num_img_box"><div class="package_num_img"><span style="width: ' + vg.num1 + '%;"></span></div><div class="package_num_tip">剩余 ' + vg.num + ' 个</div></div></li><li class="game_start"><a class="receive" name="' + vg.number + '%$#' + vg.gift_name + '%$#' + vg.game_name + '%$#' + vg.gid + '" href="javascript:void(0)">领取</a></li></ul></div>')
					});
					page1++;
					$(".allgifts").attr('name', page1);
					databool = true
				} else {
					if (page1 == 1) {
						$(".describeno").css('display', 'block')
					}
				}
				if (($.trim($(".chargemethod").html()).length == 0)) {
					$(".chargemethod").html('进入游戏——菜单“福利”——激活码——输入激活码——领取奖励')
				}
				$(".tmodel").remove()
			}
		})
	}
}

//获取邮件
function getemail(){
	if(datemail){
		datemail = false;
		$.ajax({
			url:'/start/getemail.html',
			dataType:'json',
			type:'post',
			success:function(data){
				var info = data.info;
				$(".email_num > b").eq(0).html(data.type0);
				$(".email_num > b").eq(1).html(data.type1);
				if(data.errorcode==0){
					$.each(info,function(ke,ve){
						if(ve.type==0){
							var str = 'read';
						}else{
							var str = 'unread';
						}
						$("#email_list").append('<li class="read" data="'+ve.id+'"><em class="readList"><img src="/media/images/'+str+'.png"></em><div class="em_title"><h3>'+ve.title+'</h3></div><div class="emTime_data">'+ve.createtime+'</div></li>');
					})
				}else{
					$(".allgifts1>.describeno").show();
				}
			}
		});
	}
}

//获取资讯
function getinformation(){
    datinformation = false;
	if(!$('#_nodata1').length && !$('#div_info>.tmodel').length){
		var page = $("#div_info").attr('name');//资讯的页数
		$("#div_info").append('<p class="tmodel">正在加载...</p>');
		$.ajax({
			url:'/consult/getconsult.html',
			data:{'page':page,
				'consult_boolean':consult_boolean},
			dataType:'json',
			type:'post',
			success:function(data){
				var infoc = data.info;
				if(data.errorcode==0){
					$.each(infoc,function(kc,vc){
						var _type = '';
						if(vc.type==1){
							_type +='<span style="color: #f50;vertical-align: middle;">[置顶]</span>&nbsp;';
						}
						$("#div_info>.tmodel").before('<ul class="news_ul" name="'+vc.id+'"><li class="game_img"><a class="ad">'+vc.label+'</a></li><li class="game_describe">'+_type+'<p class="game_p">'+vc.title+'</p></li><li class="game_start"><span class="time">'+vc.createtime+'</span></li></ul>');
					})
					page++; 
					$("#div_info").attr('name',page);
					datinformation = true;
				}else{
					$("#div_info").append('<div class="nodata" id="_nodata1" align="center">'+infoc+'</div>');
				}
				$(".tmodel").remove();
			}
		});
	}
}

//资讯详情
$("#div_info").on('click','.news_ul',function(){
	var id = $(this).attr('name');
	$.ajax({
		url:'/start/getconsult.html',
		data:{'id':id},
		dataType:'json',
		type:'post',
		success:function(data){
			var infoc = data.info;
			if(data.errorcode==0){
				$(".boodrg>.ptnList>h1").html(infoc.title);
				$(".boodrg .scrollList").html(infoc.content);
			}else{
				$(".boodrg>.ptnList>h1").html(infoc);
			}
			$(".boodrg").show();
		}
	});
});

//邮件详情
$("#email_list").on('click','.read',function(){
	var id = $(this).attr('data');
	$.ajax({
		url:'/start/getemaildata.html',
		data:{'id':id},
		dataType:'json',
		type:'post',
		success:function(data){
			var infoc = data.info;
			if(data.errorcode==0){
				$(".boodrg>.ptnList>h1").html(infoc.title);
				$(".boodrg .scrollList").html(infoc.content);
			}else{
				$(".boodrg>.ptnList>h1").html(infoc);
			}
			$(".boodrg").show();
		}
	});
});
<!-- //关闭悬浮球内置弹框 -->
$(".popup_close").click(function(){
	$(".boodrg").hide();
});

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
$('.game_popup_con').scroll(function() {
	if (checkScrollSlide() && databool) {
		(index == 1) && giftinfo();
		if (databool1 && index == 2) {
			getgameinfo(pagenum)
		};
	}
});

function checkScrollSlide() {
	var lastBox = $('.game_list_box').last();
	var lastBoxDis = lastBox.offset().top + Math.floor(lastBox.outerHeight(true) / 2);
	var scrollTop = $(window).scrollTop();
	var documentH = $(window).height();
	return (lastBoxDis < scrollTop + documentH) ? true : false
}
	//菜单移动
	/*设置一个长按的计时器，如果点击这个图层超过2秒则触发*/
var timeout=undefined;var x=0;var y=0;var state=0;var lastTime=null;var nowTime=null;var h=window.innerHeight||document.documentElement.clientHeight||document.body.clientHeight;var w=window.innerWidth||document.documentElement.clientWidth||document.body.clientWidth;$(function(){$('.game_menu_box').on('touchmove',function(event){event.preventDefault();if(state==1){start_x=0;var start_x=event.originalEvent.targetTouches[0].clientX+document.body.scrollLeft-document.body.clientLeft;var start_y=event.originalEvent.targetTouches[0].clientY+document.body.scrollTop-document.body.clientTop;x=start_x;y=start_y;$('.game_menu_box').css('left',x);$('.game_menu_box').css('top',y);var top=$('.game_menu_box').css('top').substring(0,$('.game_menu_box').css('top').length-2);var left=$('.game_menu_box').css('left').substring(0,$('.game_menu_box').css('left').length-2);if(top<=10){$('.game_menu_box').css('top',10);return false}if(top>=(h-10-$('.game_menu_box').height())){$('.game_menu_box').css('top',h-10-$('.game_menu_box').height())}if(left<=10){$('.game_menu_box').css('left',10);return false}if(left>=(w-20-$('.game_menu_box').width())){$('.game_menu_box').css('left',w-20-$('.game_menu_box').width())}}});$('.game_menu_box').on('touchstart',function(event){lastTime=new Date().getTime();event.preventDefault();clearTimeout(timeout);state=0;timeout=setTimeout(function(){state=1},1000)});$('.game_menu_box').on('touchend',function(event){event.preventDefault();clearTimeout(timeout);state=0;nowTime=new Date().getTime();var timeLength=nowTime-lastTime;if(timeLength<1000){$('#startgamebigbox').show();$('.game_menu_box').addClass('onhit')}})});



//关闭支付弹框
$('.closeinfomd').click(function(){
  $('.infomodal').hide();
  $('.infoname').val('');
  $('.infotel').val('');
})

/**
*隐藏支付弹框
*/
$('.closezfmd').click(function(){
 $('.rechangemodal').hide();
})


/**
* 支付
*/
$(".zhifu").on("click",function(){
 var id = $(this).attr('id');
 var formurl = '';//盛付通支付链接
 var url = '/pay/allsftpay.html';  //请求参数链接
 var PayChannel ='';
 var username = '';
 var phone = '';
 if(id=='5'){//盛付通H5快捷支付
	  $('.infomodal').hide();
      $('.infoname').val('');
	  $('.infotel').val('');
 }else if(id==8){ //游币支付
	 if(!confirm('确认支付吗？')){
        return false;
	}
}
 $('.loadbox').show();
 $.ajax({
		type:'post',
		dataType:'json',  
		data:{
			gid:s_gid,
			out_trade_no: s_data.out_trade_no, //'厂商订单编号',
			  product_id: s_data.product_id, //'商品id',
			   total_fee: s_data.total_fee,//'支付总金额	以分为单位 必须大于0',
			        body: s_data.body, //订单或商品的名称',
			      detail: s_data.detail, //订单或商品的详情',
			      attach: s_data.attach, //	后台通知时原样返回
			        sign: s_data.sign, //'请求参数签名'
			      ptype : id,        //支付类型
			      payurl: payurl,     //前端跳转页面
			}, 
		url:url,
		success:function(data){
			if(data.errorcode==0){
				var msg = data.msg;
				$.each(msg,function(k,v){
				    $('.hiddeninput').after("<input type='hidden' name='"+k+"' value='"+v+"'>");
				});
				/* if(id==5){
					$('#forminput').attr('action',data.requesturl);
					$('#forminput').submit();
				}else  */
				if(id==7){//盛付通微信扫码弹框
					$('#bitmap').show();
					$('.loadbox').hide();
					$("#div_div").html("");
					$("#div_div").qrcode(utf16to8(msg.payUrl));
					setpay = setInterval(function(){
						$.ajax({//检测是否已经付款
							url:'/start/detection.html',
							type:'post',
							dataType:'json',
							data:{'transaction_id':data.transaction_id},
							success:function(sres){
								if(sres.errorcode==0){
									alert("付款成功");
									$("#bitmap").hide();
									$('.rechangemodal').hide();
									clearInterval(setpay);
								}else{
									console.log(sres.info);
								}
							}
						});
					}, 3000);
				}else if(id==8){
					   $('.loadbox').hide();
					   $('.rechangemodal').hide();
                       alert('支付成功');
				}else if(id==9 || id==11 || id==12){//优赋H5支付，支付宝扫码支付 、支付宝手机网页
					window.location.href=data.requesturl;
				}else{
					$('#forminput').attr('action',data.requesturl);
					$('#forminput').submit();
			    }
			}else{
				$('.loadbox').hide();
				if(id==8&&data.errorcode==1002){
					alert("游币不足");
				}else{
               		 alert("网络异常，稍后再试");
				}
		    }
		}
	}) 
});



//弹框--微信二维码登录
function loginwechat(){
	$.ajax({
	url:'/personal/code.html',
	type:'post',
	dataType:'json',
	success:function(data){
        if(data.errorcode==0){
        	$('#loading').hide();
        	/* if(_equipment!="other"){
        		window.location.href="/personal/codelogin.html?code="+data.verify+'!*%@'+data.info;
        	}else{ */
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
					            	alert(data.info);
					            }
					    	}
					    });
					}, 2000);    
        	/* } */
        }else{
		    alert(data.info);
        }
	}
});
}


//游客登陆
$('.visitTourist').click(function(){
	$.ajax({
		url:'/start/touristlogin.html',
		type:"post",
		dataType:'json',
		success:function(data){
			if(data.errorcode==0){
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

//微信登陆
// $('#wechatLogin').click(function(){
//     //app登录
// 	   var data = {};
// 	   data.page = 'logingame';
// 	   data.title =  "个人中心";
//     window.parent.postMessage(data, '*');
// 	   // loginwechat();
// })
$('#wechatLogin').click(function(){
	//$("#pc-codeList").show();
	//$('.loadend').show();
	//$('#loading').show();
	 //app登录
	   var data = {};
	   data.page = 'logingame';
	   data.title =  "个人中心";
	   window.parent.postMessage(data, '*')
	  // loginwechat();
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
                		parent.location.reload();
				}else{
					alert(data.info);
				}
			}
		});
	}
})

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
</html>