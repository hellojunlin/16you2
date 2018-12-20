<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/personal.css?v=1.0.1">
<title>个人中心</title>
<div id="personal-center">
	<div class="personal_head"><img src="<?php echo isset($user->head_url)?$user->head_url:'';?>"></div>
	<div class="infobox">
	   <div class="nametext">
	   		<span class="username"><?php echo isset($user->username)?$user->username:'';?></span>
	   		<div class="vipbox">
	   			<div class="vipprogress" ><span></span></div>
	   			<div class="vipgrade">
	   				<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/vips.png">
	   				<p class="vipnum">VIP<?php echo $user->vip; ?></p>
	   			</div>
	   		</div>
	   </div>
	   <div class="infortext">ID:<span><?php echo isset($user->Unique_ID)?$user->Unique_ID:$user->id;?></span></div>
	</div>
	<div class="currency">
	    <div class="rency">
	    游币：
	        <span><?php echo isset($usercurrency->currencynum)?$usercurrency->currencynum:0;?></span>
	    </div>
   </div>
</div>
<div id="menubox">
	<div class="game_list">
		<ul class="listul personalicon">
			<li class="my-pack" onclick='httpget("我的礼包","/gift/index.html")'>
				<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/icon03_v2.png">
				<p>我的礼包</p>
			</li>
			<li class="vip-introduce" onclick="location.href='/personal/alipay.html'">
				<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/icon01_v2.png">
				<p>账户安全</p>
			</li>
			<li class="serve_kefu" id="serve_kefu">
				<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/kefu.png">
				<p>客服</p>
			</li>
			<li class="account-safe" onclick='location.href="/personal/information.html"'>
				<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/wsinfo.png">
				<p>完善信息</p>
			</li>
			<li class="account-safe" onclick="location.href='/personal/real.html'">
				<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/real_name_confirm.png">
				<p>实名认证</p>
			</li>
			<li class="account-safe" onclick='location.href="/personal/myemail.html"'>
				<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/yoyoemil.png">
				<p>我的邮件</p>
				<?php if(!empty($email)){ ?><span class="tipspoint"></span><?php } ?>
			</li>
			<li class="account-safe" onclick='location.href="/personal/vip.html"'>
				<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/icon02_v2.png">
				<p>VIP专属</p>
			</li>
	</div>
</div>
<div id="task" style="display:none;">
	<div class="tasktitle">
		今日任务<span>获得的积分可以到商城兑换商品哦！</span>
	</div>
	<div class="task_list_box">
	 <?php if(!in_array(3, $typearr)):?>
		<div class="game_list_box">
			<ul class="game_ul">
				<li class="p_game_img">
					<!-- <i class="everyimg eicon01"></i> -->
					<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/p_icon1.png">
				</li>
				<li class="game_describe">
					<p>
						<span class="game_name">每日签到</span>
					</p>
					<p class="describe">每日签到可获得10积分</p>
				</li>
				<li class="game_start"><a class="checkin">签到</a></li>
			</ul>
		</div>
		<?php endif;?>
		 <?php if(!in_array(1, $typearr)):?>
		<div class="game_list_box">
			<ul class="game_ul">
				<li class="p_game_img">
					<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/p_icon2.png">
				</li>
				<li class="game_describe">
					<p>
						<span class="game_name">每日首充</span>
					</p>
					<p class="describe">每日首次充值任意一款游戏可获得50积分</p>
				</li>
				<li class="game_start"><a class="everyday_charge">50积分</a></li>
			</ul>
		</div>
		<?php endif;?>
		<?php if(!in_array(5, $typearr)):?>
		<div class="game_list_box">
			<ul class="game_ul">
				<li class="p_game_img">
					<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/p_icon3.png">
				</li>
				<li class="game_describe">
					<p>
						<span class="game_name">充值</span>
					</p>
					<p class="describe">每日充值1元即可获得10积分</p>
				</li>
				<li class="game_start"><a href="/index/index.html" class="recharge">去完成</a></li>
			</ul>
		</div>
		<?php  endif;?>
		<?php if(!in_array(6, $typearr)):?>
		<div class="game_list_box">
			<ul class="game_ul">
				<li class="p_game_img">
					<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/p_icon4.png">
				</li>
				<li class="game_describe">
					<p>
						<span class="game_name">实名认证</span>
					</p>
					<p class="describe">完成认证可获得500积分</p>
				</li>
				<li class="game_start realname" id="6"><a href="#" class="bind_mobile realname_integral">500积分</a></li>
			</ul>
		</div>
		<?php endif;?>
		<?php if(!in_array(7, $typearr)):?>
		<div class="game_list_box">
			<ul class="game_ul">
				<li class="p_game_img">
					<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/p_icon5.png">
				</li>
				<li class="game_describe">
					<p>
						<span class="game_name">完善信息</span>
					</p>
					<p class="describe">完善个人信息即可获得1000积分</p>
				</li>
				<li class="game_start realname" id="7"><a href="#" class="bind_mobile userinfo_integral">1000积分</a></li>
			</ul>
		</div>
		<?php endif;?>
	</div>
</div>	
</div>
<!-- <div class="quit">切换账号</div> -->
<!--底部菜单-->
<!-- <div id="bottom_menu"> -->
<!-- 	<a class="menu_game"> -->
<!-- 		<i class="game_icon"></i> -->
<!-- 		<em>游戏</em> -->
<!-- 	</a> -->
<!-- 	<a class="menu_rank"> -->
<!-- 		<i class="ranking_icon"></i> -->
<!-- 		<em>游戏金榜</em> -->
<!-- 	</a> -->
<!-- 	<a class="menu_mall"> -->
<!-- 		<i class="mall_icon"></i> -->
<!-- 		<em>商城</em> -->
<!-- 	</a> -->
<!-- 	<a class="menu_personal on"> -->
<!-- 		<i class="center_icon"></i> -->
<!-- 		<em>个人中心</em> -->
<!-- 	</a> -->
<!-- </div> -->
<!--弹框-客服-->
<div id="servemodal" style="display:none">
	<div class="servebox">
		<h1 class="servetitle">联系客服</h1>
		<img class="closeimg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/close_gray.png">
		<h5>官方QQ群独家礼包各种福利</h5>
		<div class="active_num">
			<a href="#">598452957</a>
		</div>
		<h2><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/up_icon.png">点击添加官方QQ群</h2>
		<div class="ewm_box">
			<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/qq_b_default.png">
			<p>官方QQ群<br>独家礼包各种福利</p>
		</div>
		<div class="receive_btn">
			<a>我知道了</a>
		</div>
	</div>
</div>
<!--签到成功-->
<div id="successmodal" style="display:none;">
	<div class="successtips">签到成功</div>
	<div class="integralbox">
		<span class="intergral-num">+<e class='intergralnum'>10</e></span>积分
	</div>
</div>
<!--提示框-->
<div id="messigemodal" style="display:none;">
	<div class="hintmessige">
		
	</div>
</div>
<!--弹框-每日首充-->
<div id="chargemodal"  style="display:none;">
	<div class="servebox">
		<h1 class="servetitle">首次充值</h1>
		<img class="closeimg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/close_gray.png">
		<h5>每日首次充值任意一款游戏<br>即可完成任务</h5>
		<div class="active_num">
		   <img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/first_img.png">
		</div>
		<?php if(yii::$app->session['playtime']): ?>
		<div class="recently-play">
		   <p class="re_title">最近在玩</p>
		   <div class="gamelist">
		   		<ul class="g_l_ul">
		   			<?php $playtime = isset(yii::$app->session['playtime'])?yii::$app->session['playtime']:array();foreach ($playtime as $pt):?>
						<?php $playgame = isset(yii::$app->session['playgame'])?yii::$app->session['playgame']:array(); foreach ($playgame as $play):?>
							<?php if($pt == $play['id'] ):?>
							<li class="game_start" name="<?php echo isset($play['id'])?$play['id']:'';?>,<?php echo isset($play['game_url'])?$play['game_url']:'';?>">
								<img src="<?php echo yii::$app->params['cdns']; ?>/game/<?php echo $play['head_img']?>">
								<p><?php echo $play['name']?></p>
							</li>
							<?php endif;?>
						<?php endforeach;?>
					<?php endforeach;?>	
		   		</ul>
		   </div>
		</div>
		<?php endif; ?>
		<div class="receive_btn">
			<a>我知道了</a>
		</div>
	</div>
</div>
<!--弹框-每日首兑-->
<div id="exchagemodal" style="display:none;">
	<div class="servebox">
		<h1 class="servetitle">首次兑换</h1>
		<img class="closeimg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/close_gray.png">
		<div class="eximg">
		   <img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/exchange_img.png">
		</div>
		<h5>每日首次兑换任意一款商品<br>即可完成任务</h5>
		<div class="receive_btn">
			<a>前往商城</a>
		</div>
	</div>
</div>
<script>
	//跳转我的礼包、我的游戏
	function httpget(name,url){
		_source = '个人中心-'+name;//来源  
		postmessage();
		window.location.href = url;
	}
	//客服
	$('#serve_kefu').click(function(){
		$('#servemodal').show();
	})
	//关闭客服弹框
	$('.closeimg,.receive_btn').click(function(){
		$('#servemodal').hide();
		$('#chargemodal').hide();
		$('#exchagemodal').hide();
	})
		//实名认证
	$('.realname').click(function(){
		var _this = $(this);
		var id = $(this).attr('id');
		if(!$(this).hasClass('realname')){
            return false;
	    }
		$.ajax({
			type:'post',
     		dataType:'json',
     		data:{'type':id},
 			url:'/personal/realnameauth.html',
 			success:function(data){
                if(data.errorcode==0){
                	_this.removeClass("realname");
                	$('#successmodal').show();
            		$('.realname').text('已完成');
            		$('.intergralnum').text(data.integral);
            		setTimeout(function(){//自动关闭
            			$('#successmodal').hide();
            		},3000);
                 }else if(data.errorcode==1003){
                     if(id==6){
                    	 window.location.href = '/personal/real.html';
                     }else if(id==7){
                    	 window.location.href = '/personal/information.html';
                     }
                 }else{
                	 $('#messigemodal').show();
                	 $('.hintmessige').text(data.info);
                	 setTimeout(function(){//自动关闭
              			$('#messigemodal').hide();
              		},3000);
                 }
 		    }
 		}) 
	});
	
	//签到成功
	$('.checkin').click(function(){
		var _this = $(this);
 	 	if(!$(this).hasClass('checkin')){
            return false;
	    }
		$.ajax({
			type:'post',
     		dataType:'json',
 			url:'/personal/usersign.html',
 			success:function(data){
                if(data.errorcode==0){
                	$('#successmodal').show();
            		$('.checkin').text('已完成');
            		$('.intergralnum').text(data.integral);
            		_this.removeClass("checkin");
            		setTimeout(function(){//自动关闭
            			$('#successmodal').hide();
            		},3000);
                 }else if(data.errorcode==1003){
                	 $('#messigemodal').show();
                	 $('.hintmessige').text(data.info);
                	 setTimeout(function(){//自动关闭
              			$('#messigemodal').hide();
              		},3000);
                 }else{
                     alert(data.info);
                 }
 		    }
 		}) 
	}) 
	
	//每日首充
	$('.everyday_charge').click(function(){
		$('#chargemodal').show();
	})
	//每日首兑
	$('.everyday_exchage').click(function(){
		$('#exchagemodal').show();
	})
	//菜单切换
    $('#bottom_menu a').click(function(){
    	$(this).addClass('on').siblings().removeClass('on');
    })
    //点击最近玩 
    /*$('.game_start').click(function(){
	    var _this = $(this);
	    var game = _this.attr('name');
	    var garr = game.split(",");
        $.ajax({
			type:'post',
    		dataType:'json',
			data:{'gid':garr[0]},
			url:'/index/play.html',
			success:function(data){
                if(data.errorcode==0){
                	window.location.href=garr[1];
                }else{
                    alert(data.info);
                }
		    }
		}) 
	})*/
</script>