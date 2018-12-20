<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/pc/css/personal.css">
<div id="personal-center">
	<?php if(isset($user)&&$user): ?>
	<div class="personal_head"><img src="<?php echo isset($user)?$user->head_url:'';?>"></div>
	<div class="infobox">
	   <div class="nametext">
	   		<span class="username"><?php echo isset($user)?$user->username:'';?></span>
	   		<div class="vipbox">
	   			<div class="vipprogress" ><span></span></div>
	   			<div class="vipgrade">
	   				<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/vips.png">
	   				<p class="vipnum">VIP<?php echo $user->vip; ?></p>
	   			</div>
	   		</div>
	   </div>
	   <div class="infortext">ID:<span><?php echo isset($user->Unique_ID)?$user->Unique_ID:'';?></span></div>
	</div>
	<div class="currency">
	    <div class="rency">
	    游币：
	        <span><?php echo isset($usercurrency->currencynum)?$usercurrency->currencynum:0;?></span>
	    </div>
   </div>
	<?php else: ?>
	<div class="personal_head"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/noimg.jpg"></div>
	<div class="infobox">
		<div class="nametext">
			<span>未登录</span>
		</div>
	</div>
	<?php endif; ?>
</div>
<div id="menubox">
	<div class="game_list">
		<ul class="listul personalicon">
			<li class="my-pack" onclick='httpget("我的礼包","/gift/index.html")'>
				<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/icon03_v2.png">
				<p>我的礼包</p>
			</li>
			<li class="vip-introduce" onclick="location.href='/personal/alipay.html'">
				<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/icon01_v2.png">
				<p>账户安全</p>
			</li>
			<li class="serve_kefu">
				<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/kefu.png">
				<p>客服</p>
			</li>
			<li class="account-safe" onclick='location.href="/personal/information.html"'>
				<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/wsinfo.png">
				<p>完善信息</p>
			</li>
			<li class="account-safe" onclick="location.href='/personal/real.html'">
				<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/real_name_confirm.png">
				<p>实名认证</p>
			</li>
			<li class="account-safe" onclick="location.href='/personal/myemail.html'">
				<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/yoyoemil.png">
				<p>我的邮件</p>
				<?php if(!empty($email)): ?><span class="tipspoint"></span><?php endif; ?>
			</li>
			<li class="account-safe" onclick='location.href="/personal/vip.html"'>
				<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/icon02_v2.png">
				<p>VIP专属</p>
			</li>
		</ul>
</div>
</div>
<div id="task" style="display:none;">
	<div class="tasktitle">
		今日任务<span>获得的积分可以到商城兑换商品哦！</span>
	</div>
	<div class="task_list_box">
	 <?php //if(!in_array(3, $typearr)):?>
		<div class="game_list_box">
			<ul class="game_ul">
				<li class="p_game_img">
					<!-- <i class="everyimg eicon01"></i> -->
					<img src="<?php //echo yii::$app->params['cdn16you']; ?>/images/p_icon1.png">
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
		<?php //endif;?>
		 <?php //if(!in_array(1, $typearr)):?>
		<div class="game_list_box">
			<ul class="game_ul">
				<li class="p_game_img">
					<img src="<?php //echo yii::$app->params['cdn16you']; ?>/images/p_icon2.png">
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
		<?php //endif;?>
		<?php //if(!in_array(5, $typearr)):?>
		<div class="game_list_box">
			<ul class="game_ul">
				<li class="p_game_img">
					<img src="<?php //echo yii::$app->params['cdn16you']; ?>/images/p_icon3.png">
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
		<?php // endif;?>
		<?php //if(!in_array(6, $typearr)):?>
		<div class="game_list_box">
			<ul class="game_ul">
				<li class="p_game_img">
					<img src="<?php //echo yii::$app->params['cdn16you']; ?>/images/p_icon4.png">
				</li>
				<li class="game_describe">
					<p>
						<span class="game_name">实名认证</span>
					</p>
					<p class="describe">完成认证可获得500积分</p>
				</li>
				<li class="game_start realname" id="6"><a href="#" class="bind_mobile">500积分</a></li>
			</ul>
		</div>
		<?php //endif;?>
		<?php //if(!in_array(7, $typearr)):?>
		<div class="game_list_box">
			<ul class="game_ul">
				<li class="p_game_img">
					<img src="<?php //echo yii::$app->params['cdn16you']; ?>/images/p_icon5.png">
				</li>
				<li class="game_describe">
					<p>
						<span class="game_name">完善信息</span>
					</p>
					<p class="describe">完善个人信息即可获得1000积分</p>
				</li>
				<li class="game_start realname" id="7"><a href="#" class="bind_mobile">1000积分</a></li>
			</ul>
		</div>
		<?php //endif;?>
	</div>
</div>	
<!--弹框-客服-->
<div id="servemodal" style="display:none">
	<div class="servebox">
		<h1 class="servetitle">联系客服</h1>
		<img class="closeimg" src="<?php echo yii::$app->params['cdn16you']; ?>/images/close_gray.png">
		<h5>官方QQ群独家礼包各种福利</h5>
		<div class="active_num">
			<a>598452957</a>
		</div>
		<h2><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/up_icon.png">点击添加官方QQ群</h2>
		<div class="ewm_box">
			<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/code_sml.jpg">
			<p>关注16游公众号<br>领取关注礼包</p>
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
		<img class="closeimg" src="<?php echo yii::$app->params['cdn16you']; ?>/images/close_gray.png">
		<h5>每日首次充值任意一款游戏<br>即可完成任务</h5>
		<div class="active_num">
		   <img src="<?php echo yii::$app->params['cdn16you']; ?>/images/first_img.png">
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
								<img src="<?php echo yii::$app->params['cdn']; ?>/game/<?php echo $play['head_img']?>">
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
		<img class="closeimg" src="<?php echo yii::$app->params['cdn16you']; ?>/images/close_gray.png">
		<div class="eximg">
		   <img src="<?php echo yii::$app->params['cdn16you']; ?>/images/exchange_img.png">
		</div>
		<h5>每日首次兑换任意一款商品<br>即可完成任务</h5>
		<div class="receive_btn">
			<a>前往商城</a>
		</div>
	</div>
</div>
	<!-- 弹框--选择登录方式 -->
<div id="pc-LoginChose" style="display:none">
	<div class="Chosebox" style="margin-top:-135px">
	   <h1 class="Chosetitle">请选择登录方式
	       <span id="close-dialogP">
	           <img class="closeChoseCode" src="<?php echo yii::$app->params['cdn16you']; ?>/images/close_gray.png">
	       </span>
	    </h1>
          <div class="Chose">
                <ul class="clearfix">
                    <li>
                        <span id="m-wechat-login" class="wechat_icon" ><img src="/media/images/wechat_icon.png"></span>
                        <p>微信登录</p>
                    </li>
                    <!-- <li>
                        <span id="m-qq-login" class="QQ_icon"><img src="/media/images/QQ_icon.png"></span>
                        <p>QQ登录</p>
                    </li>
                    <li>
                        <span id="m-weibo-login" class="weibo_icon"><img src="/media/images/wechat_icon.png"></span>
                        <p>微博登录</p>
                    </li> -->
                    <!-- <li>
                        <span id="m-mobile-login" class="phone_icon"><img src="/media/images/phone_icon.png"></span>
                        <p>手机登录</p>
                    </li> -->
                    <li class="tlpc" style="width:100%;float:left;margin-top:-8px;margin-bottom: 16px;">
                         <p id="persLogin">
                            <a id="pc-sign">账号登录</a>
                         </p>
                    </li>
                </ul>
         </div>
	</div>
</div>
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
	   <div class="loadbox"><img src="/media/images/load.gif"></div>
	   <div class="loadend">
		   <h1>微信扫码登录
		       <span id="close-pc-codeList">
		           <img class="closeChoseCode" src="/media/images/close_gray.png">
		       </span>
		   </h1>
		   <div><span class="codepcImg"><img alt="" src="/media/images/codeImgList1.png"></span><p>扫描上方二维码进行登录</p></div>
	   </div>
	</div>
</div>
<!-- 弹框--手机登录 -->
<div id="pc-phoneList" style="display:none">
	<div class="pc-phoneListbox">
	   <h1>手机号登录
	       <span id="close-pc-phoneList">
	           <img class="closeChosePhone" src="<?php echo yii::$app->params['cdn16you']; ?>/images/close_gray.png">
	       </span>
	   </h1>
        <div class="phone_login">
            <div class="phone_num">
                <input id="phone-input" placeholder="请输入手机号" type="tel">
            </div>
            <div class="phone_num codeNum">
                <input id="verification_code" placeholder="请输入图形验证码">
                <span id="checkCode" class="code_dream" style="display:block"></span>
            </div>
            <div class="phone_num voice_phone">
                <input id="verifycode" placeholder="请输入验证码">
                <input type="button" id="get-verifycode" value="获取短信验证码" onclick="phonesubmit(this)" /> 
            </div>
            <div class="textPhone_tip">温馨提示：输入手机号以及正确的图形验证码之后，点击获取语音验证码后，会以电话的形式为您播放验证码，请留意接听来电。</div>
        </div>
        <div class="login_phone_btn">
            <a href="#">登录</a>
        </div>
	</div>
</div>
<!-- 手机号码验证弹框 -->
<div class="sign_popup" id="msg-dialog" style="display:none">
    <h2></h2>
</div>
<!-- 一键收藏 -->
<div id="collectionList" style="display:none">
	<div class="collectionbox">
	   <p>请使用<span>Ctrl+D</span>进行一键收藏</p>
	   <a id="konwpc">我知道了</a>
	</div>
</div>
<script>
	$('#m-wechat-login').click(function(){
			  loginwechat();
	})


	<?php if(isset($user)): ?>
	var _pname = '<?php echo $pname?$pname->pname:'';?>'; //渠道名称
	var _source = '个人中心页';//来源 
	postmessage();
	<?php endif; ?>
	//客服
	$('.serve_kefu').click(function(){
		_source = '个人中心-客服';//来源  
		postmessage();
		$('#servemodal').show();
	})
	//关闭客服弹框
	$('.closeimg,.receive_btn').click(function(){
		$('#servemodal').hide();
		$('#chargemodal').hide();
		$('#exchagemodal').hide();
		$('#bindmodal').hide();
	})
	<?php if(!isset($user)):  ?>
	//弹框-选择登录方式
	$(document).ready(function(){ 
		$("#pc-LoginChose").show();
		$("#close-dialogP").click(function(){
			// $("#pc-LoginChose").hide();//关闭登录框
			if(_equipment!='other'){//手机端
				window.location.href="/game/list.html";
			}else{
				$('#loginPcBtn', window.parent.document).show();
				$('#yoyo', window.parent.document).attr('src','/game/list.html');
			}
		});
	}); 
	<?php endif; ?>
	//弹框--选择账号登录
    $("#pc-sign").click(function(){
    	$("#pc-LoginChose").hide();
     	$("#pc-account").show();
    })
	//关闭账号登录
	$("#close-pc-account").click(function(){
		$("#pc-account").hide();
		$("#pc-LoginChose").show();
	})
	//弹框--微信二维码登录
	function loginwechat(){
		$.ajax({
	    	url:'/personal/code.html',
	    	type:'post',
	    	dataType:'json',
	    	success:function(data){
	            if(data.errorcode==0){
	            	$('.loadend').show();
	            	$('.loadbox').hide();
	            	if(_equipment!="other"){
	            		window.location.href="/personal/codelogin.html?code="+data.verify+'!*%@'+data.info;
	            	}else{
		                $("#pc-codeList").show();
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
					                	parent.location.reload();
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
	$("#close-pc-codeList").click(function(){
		clearInterval(timeID1);//清除定时
		$("#pc-codeList").hide();
	})
	//选择手机登录
	$("#m-mobile-login").click(function(){
		$("#pc-LoginChose").hide();
		$("#pc-phoneList").show();
	})
	//关闭账号登录
	$("#close-pc-phoneList").click(function(){
		$("#pc-phoneList").hide();
		$("#pc-LoginChose").show();
	})
	//验证手机号码
	function phoneinput() {
        if(!(/^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/i).test($("#phone-input").val())){
        	dialog = $('#msg-dialog');
       		dialog.children('h2').html('请输入正确手机号码');
       		dialog.show().delay(2000).slideUp(1);
            return false;
	 	}
	};
	//图形验证码
    var code;
    //生成图形验证码
    $("#checkCode").click(function(){
    	var phonestate = phoneinput();
    	if(phonestate!=false){
		    checkCode();
    	}
  	});
  	function checkCode(){
  		code = "";
	    var codeLength = 4; //验证码的长度
	    var codeChars = new Array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'); //所有候选组成验证码的字符，当然也可以用中文的
	    for(var i = 0; i < codeLength; i++) {
	     var charNum = Math.floor(Math.random() * 52);
	     code += codeChars[charNum];
	    }
		$("#checkCode").css("background","none").html(code);//图形验证码
  	}
	//语音验证码
	var countdown=60;
	function ef(val2) {
	    if(countdown ==0){
			val2.removeAttribute("disabled");
			val2.value = '获取验证码';
			countdown = 60;
			$("#get-verifycode").css("color",'#333').css('borderColor','#333');
		}else{
			$("#get-verifycode").css("color",'#aaa').css('borderColor','#aaa');
			val2.setAttribute("disabled",true);
			val2.value = countdown +'s后可重发';
			countdown--;
			setTimeout(function(){
				ef(val2)
			},1000)
		}
	}
	//获取短信验证码
	function phonesubmit(val3){
	    var inputCode = $("#verification_code").val();
	    dialog = $('#msg-dialog');
	    if((inputCode.length <= 0)||(inputCode.toUpperCase() != code.toUpperCase())){//验证图形验证码
       		dialog.children('h2').html('图形验证码输入错误');
       		dialog.show().delay(2000).slideUp(1);
       		checkCode();
	    }else{//获取短信验证码
	    	var _phone = $("#phone-input").val();
	    	$.ajax({
	    		url:'/personal/tosms.html',
	    		dataType:'json',
	    		data:{'phone':_phone},
	    		success:function(data){
	    			if(data.errorcode==0){
	    				ef(val3);
	    			}else{
						dialog.children('h2').html(data.info);
       					dialog.show().delay(2000).slideUp(1);
	    			}
	    		}
	    	})
	    }  
	    return false;
	};
	//手机号码登录
	$(".login_phone_btn").click(function(){
		dialog = $('#msg-dialog');
		var phonestate1 = phoneinput();
		inputCode = $("#verification_code").val();
    	if((inputCode.length <= 0)||(inputCode.toUpperCase() != code.toUpperCase())){//验证图形验证码
       		dialog.children('h2').html('图形验证码输入错误');
       		dialog.show().delay(2000).slideUp(1);
       		checkCode();
	    }else{
	    	code = $("#verifycode").val();//验证码
	    	num = $("#phone-input").val();//手机号码
		    $.ajax({
				url:'/personal/phoneinput.html',
				data:{'phone':num,'codenum':code},
				type:'post',
				dataType:'json',
				success:function(data){
					dialog.children('h2').html(data.info);
       				dialog.show().delay(2000).slideUp(1);
					if(data.errorcode==0){
						parent.location.reload();
					}else if(data.errorcode==1001){
						loginwechat();
						$("#close-pc-phoneList").trigger("click");
					}
				}
			})
	    }
		return false;
	});
	//跳转我的礼包、我的游戏
	function httpget(name,url){
		_source = '个人中心-'+name;//来源  
		postmessage();
		window.location.href = url;
	}
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
	                	if(_equipment!="other"){
		            		window.location.href="/game/list.html";
		            	}else{
	                		parent.location.reload();
	                	}
					}else{
						alert(data.info);
					}
				}
			});
		}
	})
		//实名认证
	$('.realname').click(function(){
		var id = $(this).attr('id');
		$.ajax({
			type:'post',
     		dataType:'json',
     		data:{'type':id},
 			url:'/personal/realnameauth.html',
 			success:function(data){
                if(data.errorcode==0){
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
		$.ajax({
			url:'/personal/usersign.html',
			type:'post',
			dataType:'json',
			success:function(data){
				if(data.errorcode==0){
					$('.checkin').text('已完成');
					$('.checkin').unbind();//接触事件绑定
					var intergral = parseInt($(".intergral-num>e").html());
					if(intergral<100){
						intergral1 = intergral+10;
					}else{
						intergral1 = 100;
					}
					$(".describe_p").html('明天再来即可得'+intergral1+'积分');
					var score = parseInt($.trim($(".i_score").html()));
					$(".i_score").html(score+intergral);
				}else{
					$(".successtips").html(data.info);
					$(".integralbox").html();
				}
				$('#successmodal').show();
				setTimeout(function(){//自动关闭
					$('#successmodal').hide();
				},3000);
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

</script>