<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<title>新年活动</title>
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/style.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/rem.js"></script>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script> 
</head>
<script>
wx.config({
	debug:false,
	<?php $signPackage = yii::$app->session->get('signPackage');?>
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: '<?php echo $signPackage["timestamp"];?>',
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
		//所有要调用的 API 都要加到这个列表中
		'onMenuShareTimeline',
		'onMenuShareAppMessage',
		'hideMenuItems'
    ]
  });
  wx.ready(function () {
    // 在这里调用 API
	  //分享朋友圈	
	  wx.onMenuShareTimeline({//voteinfo
	    title:'16游新年活动', // 分享标题
	    link:  "<?php echo yii::$app->params['frontends'].'/rankinged/index.html?token='.rand(100000000,999999999).yii::$app->session['user']->id;?>",// 分享链接
	    imgUrl:"<?php echo yii::$app->params['cdn16yous'];?>/images/redpack/achieve.png", // 分享图标
	    success: function () { 
	       $('#share').hide();
	    },
	    cancel: function () { 
	        // 用户取消分享后执行的回调函数
	    	// alert("分享失败");
	    }
	});
	
	//分享朋友
	wx.onMenuShareAppMessage({
	    title: '16游新年活动', // 分享标题
	    desc: '新年活动，实时红包抢不停，百分百中奖，没有红包算我输', // 分享描述
	    link: "<?php echo yii::$app->params['frontends'].'/rankinged/index.html?token='.rand(100000000,999999999).yii::$app->session['user']->id;?>",// 分享链接
	    imgUrl: "<?php echo yii::$app->params['cdn16yous'];?>/images/redpack/achieve.png", // 分享图标
	    type: 'link', // 分享类型,music、video或link，不填默认为link
	    success: function () { 
	    	$('#share').hide();
	    },
	    cancel: function () { 
	    }
	}); 
	//隐藏分享QQ
	wx.hideMenuItems({
	 	    menuList: ['menuItem:share:qq'] // 要隐藏的菜单项，只能隐藏“传播类”和“保护类”按钮，所有menu项见附录3
	});
  });
  var cdn_url = "<?php echo yii::$app->params['cdn16yous']; ?>";
</script>
<body>
	<div class="luckbigbox">
	   <div class="bg_top">
			<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/bg_top.png" alt="">
		</div>
	   <div class="header">
			<span class="attionTip">活动时间：2.16-3.2，为期15天</span>
		</div>
		<!--整点秒抢-->
		<div class="seckill">
			<div class="sectitle">
				<div class="seck">
					<img class="titlebg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/title1.png">
				</div>
				<div class="rules-one" id="rules-one">
					<div class="sec-btn">规则</div>
				</div>
			</div>
			
			<div class="luckredbox">
				<!-- 9点开抢 -->
				 <?php if(isset($firstredarr['type']) && $firstredarr['type']==1):?>
					<div class="luckred" id="1" ><div class="as"></div><img class="achieve_rob1 " src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/dog_1.png"><p class="achieve_money1"><i class="_money"><?php echo isset($firstredarr['money'])?$firstredarr['money']:0;?></i>￥</p>
				<?php elseif (isset($firstredarr['type']) && $firstredarr['type']==2):?>
				  <div class="luckred" id="1" > <div class="as"></div><img class=" over_"src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/dog_2.png"><p class="over_tip">红包抢完啦</p>
				<?php else:?>  
				    <div class="luckred nogetluck" id="1" >
				    <img class="notime_s" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/redbox1.png">
					<p class="redbox"><i>新</i></p>
					<p class="timestar time_p" ><i class="starList timeData">10</i>点开抢</p>
				<?php endif;?> 
				</div>
				<!-- 12点开抢 -->
				<?php if(isset($secredarr['type']) && $secredarr['type']==1):?>
					<div class="luckred" id="2" ><div class="as"></div><img class="achieve_rob1 " src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/dog_1.png"><p class="achieve_money1"><i class="_money"><?php echo isset($secredarr['money'])?$secredarr['money']:0;?></i>￥</p>
				<?php elseif (isset($secredarr['type']) && $secredarr['type']==2):?>
					<div class="luckred" id="2" > <div class="as"></div><img class=" over_"src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/dog_2.png"><p class="over_tip">红包抢完啦</p>
				<?php else:?>  
					<div class="luckred nogetluck" id="2" >
				    <img class="notime_s" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/redbox1.png">
					<p class="redbox"><i>年</i></p>
					<p class="timestar time_p" ><i class="starList timeData">12</i>点开抢</p>
				<?php endif;?> 
				</div>
				<!-- 19点开抢 -->
				<?php if(isset($thiredarr['type']) && $thiredarr['type']==1):?>
					<div class="luckred" id="3" ><div class="as"></div><img class="achieve_rob1 " src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/dog_1.png"><p class="achieve_money1"><i class="_money"><?php echo isset($thiredarr['money'])?$thiredarr['money']:0;?></i>￥</p>
				<?php elseif (isset($thiredarr['type']) && $thiredarr['type']==2):?>
					<div class="luckred" id="3" > <div class="as"></div><img class=" over_"src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/dog_2.png"><p class="over_tip">红包抢完啦</p>
				<?php else:?>  
					<div class="luckred nogetluck" id="3" >
				    <img class="notime_s" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/redbox1.png">
					<p class="redbox"><i>快</i></p>
					<p class="timestar time_p" ><i class="starList timeData">19</i>点开抢</p>
				<?php endif;?> 
				</div>
				<!-- 21点开抢 -->
				<?php if(isset($fouredarr['type']) && $fouredarr['type']==1):?>
					<div class="luckred" id="4" ><div class="as"></div><img class="achieve_rob1 " src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/dog_1.png"><p class="achieve_money1"><i class="_money"><?php echo isset($fouredarr['money'])?$fouredarr['money']:0;?></i>￥</p>
				<?php elseif (isset($fouredarr['type']) && $fouredarr['type']==2):?>
					<div class="luckred" id="4" > <div class="as"></div><img class=" over_"src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/dog_2.png"><p class="over_tip">红包抢完啦</p>
				<?php else:?>  
					<div class="luckred nogetluck" id="4" >
				    <img class="notime_s" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/redbox1.png">
					<p class="redbox"><i>乐</i></p>
					<p class="timestar time_p" ><i class="starList timeData">21</i>点开抢</p>
				<?php endif;?>
				</div>
			</div>
			<div class="servemodal">
				<div class="luck_surp">
					<img class="rob" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/rob.gif">
    				<div class="achieve_box over_">
    					<div class="as_soon">
						</div>
						<img class="achieve_rob_soon " src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/dog_1.png">
	    				<p class="achieve_money_soon"><i class="_money_soon"></i>￥</p>
    				</div>
    				<div class="over_rob over_" >
	    				<div class="as_dog">
						</div>
						<img class=" over_soon" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/dog_2.png">
						<p class="over_tips">红包抢完啦</p>
					</div>
				</div>
			</div>		
<!-- 			还未到点提示 -->
			<div class="hide_modal">
				<div class="wait">
					<p></p>
					<img class="tipimg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/bk.png">
				</div>
			</div>
		</div>

		<!-- 幸运大转盘 -->
		<div class="luckR">
			<div class="sectitle">
				<div class="seck">
					<img class="titlebgTwo" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/title2.png">
<!-- 					<p class="sectxtTwo">幸运大转盘</p> -->
				</div>
				<div class="rules-Two" id="rules-Two">
					<div class="sec-btnTwo">仓库</div>
				</div>
			</div>
			<div class="Lucky-draw">
				<div id="luck"><!-- luck -->
				<table>
					<tr>
						<td class="luck-unit luck-unit-0"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/m1.png"></td>
						<td class="luck-unit luck-unit-1"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/1.png"></td>
						<td class="luck-unit luck-unit-2"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/hohX.png"></td>
						<td class="luck-unit luck-unit-3"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/2.png"></td>
						<td class="luck-unit luck-unit-4"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/m4.png"></td>
					</tr>
					<tr>
						<td class="luck-unit luck-unit-11"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/hoh1.png"></td>
						<!-- <td rowspan="2" colspan="2" class="cjBtn" id="btn"> -->
						<td colspan="3" class="cjBtn" id="btn">
							<p class="free">免费</p>
							<p class="frenum">免费次数<text class="lotterynumber"><?php echo $num; ?></text>次</p>
						</td>
						<td class="luck-unit luck-unit-5"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/jqp1.png"></td>
					</tr>
					<tr>
						<td class="luck-unit luck-unit-10"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/car.png"></td>
						<td class="luck-unit luck-unit-9"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/3.png"></td>
						<td class="luck-unit luck-unit-8"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/m2.png"></td>
						<td class="luck-unit luck-unit-7"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/4.png"></td>
						<td class="luck-unit luck-unit-6"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/m3.png"></td>
					</tr>
				</table>
				</div><!-- luckEnd -->
			</div>
			<div class="share">
				<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/share.png">
			</div>
		</div>

		<!-- 好游推荐 -->
		<div class="recommended">
			<div class="retitle">
				<img class="titlebgThree" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/title3.png">
			</div>
			<div class="hotGame">
				<div class="hot" style="border: none;">
					<div class="hotList">
						<a href="">
							<div class="info">
								<div class="intro">《热血归来》突破型H5传奇游戏，靠实力说话</div>
								<div class="gameImg">
									<img class="gaImg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/yo2.png">
								</div>
							</div>
						</a>
					</div>
					<div class="hotList">
						<a href="">
							<div class="info">
								<div class="gameImg1">
									<img class="gaImg1" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/yo.jpg">
								</div>
								<div class="intro1">《天天塔克大战》指尖MOBA，随时开战，跟小伙伴一起互怼到天明~</div>
							</div>
						</a>
					</div>
					<div class="hotList">
						<a href="">
							<div class="info">
								<div class="intro">《开心王国》修身齐家，我的国家我做主</div>
								<div class="gameImg">
									<img class="gaImg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/yo1.png">
								</div>
							</div>
						</a>
					</div>
					<div class="hotList">
						<a href="">
							<div class="info">
								<div class="gameImg1">
									<img class="gaImg1" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/yo4.png">
								</div>
								<div class="intro1">《泡泡军团》团队协作的战局把控，畅玩竞技</div>
							</div>
						</a>
					</div>

				</div>
			</div>
		</div>
		<!-- 底部花纹 -->
		<div class="bg_bottom">
			<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/bg_bottom.png" alt="">
		</div>
		<!-- 弹框//规则 -->
		<div class="rules" id="envelope" style="display:none">
			<div class="scles">
				<div class="tip">
					<p>1、活动期间，玩家在每日单笔充值任意金额，均可参与单日的整点秒抢活动；</p>
					<p>2、每天12点、19点、20点、21点四轮整点秒抢，红包多多，中奖几率暴涨；</p>
					<p>3、活动期间，玩家每日登陆都可以获得一次免费的抽奖机会，邀请好友也可以获得抽奖机会，每人每天最多3次;</p>
					<p>4、集齐“恭喜发财”四个字可以可兑换88.88元大红包；</p>
					<p>5、获得iphoneX、小米平衡车、iPad mini4、500元京东购物卡，5888元大红包等奖励，请在正月十五后，五个工作日内联系16游VIP客服-花花领取（微信：VIN7390、QQ：3243987127）；</p>
					<p>6、获得红包将根据您微信号自动发放到账，领取后请在参与活动的微信号中【钱包】查看；</p>
					<p>7、由于不可抗拒因素、网络、作弊等原因导致活动异常，最终解释权归16游h5游戏平台所有；</p>
				</div>
			</div>
		</div>
		<!-- 弹框 仓库-->
		<div class="rules" id="warehouse" style="display:none">
			<div class="warehouse">
				<div class="allware">
					<div class="up">
						<div class="puzzle">
							<div class="puzzleList">
								<li><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/1.png" alt=""><span class="solantext">0</span></li>
								<li><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/2.png" alt=""><span class="solantext">0</span></li>
								<li><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/3.png" alt=""><span class="solantext">0</span></li>
								<li><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/4.png" alt=""><span class="solantext">0</span></li>
							</div>
							<div id="synthetic" class="synthetic"><a>合成</a></div>
						</div>
					</div>
					<div class="close">
						<div class="closeBtn">
							<a href="javascript:void(0);">关闭</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		 <!-- 合成 -->
		 <div class="rules" id="each" style="display:none">
		 	<div class="each">
 				<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/88.png">
 				<p>请在钱包里查看</p>
 			</div>
		 </div>
		<!--  弹框-分享给好友 -->
		<div class="rules" id="share" style="display:none">
			<div class="shareG">
				<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/sharep.png">
			</div>
				<p class="sharetext">转发分享给好友</p>
		</div>
		<!--转盘提示弹框-->
		<div class="rotatetips">
		   <div class="rotatebox">
		   	 <img class="prizeimg" src="">
		   	 <p class="moneybox"><text class="money"></text>元</p>
			 <p class="tipstxt">恭喜您获得<text class="gettips"></text></p>
		   </div>
		</div>
	</div>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['frontends']; ?>/media/js/lottery.js"></script>
	<script type="text/javascript">
	var timestamp = new Date().getTime();//获取当前时间戳
	var state = true;

	// if((timestamp<1518710400000) || (timestamp>1520006400000)){
	// 	$('.frenum').html('活动时间：2月16号-3月2号');
	// 	$('#rules-Two').hide();
	// }

	$('#rules-one').click(function(){
		$('#envelope').css('display','block');//规则显示
	});
	$('#envelope').click(function(){
		$('#envelope').css('display','none');//规则隐藏
	});
	//还未开始弹框提示
	$('.hide_modal').click(function(){
		$('.hide_modal').hide();
	});
	//打开仓库
    $('#rules-Two').click(function(){
    	$('#synthetic').hide();//隐藏合成按钮
    	$.ajax({
    		url:'/rankinged/warehouse.html',
      		type:'post',
      		dataType:'json',
      		success:function(data){
      			if(data.errorcode == 0){
      				state = data.state;
      				if(state){//显示合成按钮
      					$('#synthetic').show();
      				}
      				$.each(data.info,function(k,v){
      					$('.solantext').eq(k).html(v);
      				});
      			}else{
      				alert(data.info);
      			}
      		}
    	});
    	$('#warehouse').css('display','block');
    })
    $('.closeBtn').click(function(){
    	$('#warehouse').hide();
    	$('.solantext').eq(k).html(0);//清空
    	$('#synthetic').hide();//隐藏合成按钮
    })

    //合成
    $('#synthetic').click(function(){
    	if(state){
	    	$.ajax({
	    		url:'/rankinged/warehousesynthesis.html',
	      		type:'post',
	      		dataType:'json',
	      		success:function(data){
	      			if(data.errorcode == 0){
	      				$('#each').show();
	      				$('.solantext').html(0);//清空
	      				$("#warehouse").hide();
    					$('#synthetic').hide();//隐藏合成按钮
	      			}else{
	      				alert(data.info);
	      			}
	      		}
	    	});
    	}
    })
    $('.each').click(function(){
    	$('#each').hide();
    })
    // 分享给好友
    $(".share").click(function(){
    	$('#share').show();
    })
    $("#share").click(function(){
    	$('#share').hide();
    })
	
	//整点秒抢
    $('.luckredbox').on('click','.nogetluck',function(){
   	    var that = $(this);
        var type = that.attr('id');
        var numcache1 = "<?php echo yii::$app->cache->get($time.'1');?>";
        var numcache2 = "<?php echo yii::$app->cache->get($time.'2');?>";
        var numcache3 = "<?php echo yii::$app->cache->get($time.'3');?>";
        var numcache4 = "<?php echo yii::$app->cache->get($time.'4');?>";
        var num = 0;
        var limitnum = 80;
        switch(type){
	        case '1': date = 10;num=numcache1;limitnum=60;break;
	        case '2': date = 12;num=numcache2;break;
	        case '3': date = 19;num=numcache3;break;
	        case '4': date = 21;num=numcache4;break;
	        default: date = 0;
	    }
	
	   if(num>limitnum){
		  $('.robimg').hide();
	      $('.noget').show();
          $(that).removeClass('nogetluck').html('');
          $('<div>').addClass('as').appendTo(that);
          $('<img>').addClass('over_').attr('src','<?php //echo yii::$app->params['cdn16yous']; ?>/images/newyears/dog_2.png').appendTo(that);
          $('<p>').addClass('over_tip').append('红包抢完啦').appendTo(that);
	   	 	return false;
	    }   
	    var myDate= new Date(new Date().toLocaleDateString()).getTime()+date*60*60*1000;

	    // 还未到点
	  if(timestamp<1518710400000){
        	$('.wait p').text('您好,该活动时间为 2月16号-3月2号');
        	$('.hide_modal').show();
        		// setTimeout(function(){
      	     // $('.hide_modal').hide();
      	   // },4000);
            return false;
        }
	    if(timestamp>1520006400000){
        	$('.wait p').text('您好，该活动已结束，请等下一期活动');
        	$('.hide_modal').show();
        	// setTimeout(function(){
      	     	// $('.hide_modal').hide();
      	   	// },4000);
            return false;
	    }

	    if(timestamp<myDate){
        	$('.wait p').text('您好,时间还未到，请稍等！');
        	$('.hide_modal').show();
        	// setTimeout(function(){
      	     	$('.hide_modal').hide();
      	   	// },2000);
            return false;
	     }  

	       
	    $('.servemodal').show();
    	$('.rob').show();
    	$.ajax({
    		url:'/rankinged/robredpacket.html',
    		type:'post',
    		data:{'type':type},
    		dataType:'json',
    		success:function(data){
    		$('.rob').hide();
    		if(data.errorcode==0){
    			 $('.achieve_box').show();
    			 $('._money_soon').text(data.money);
                 $(that).removeClass('nogetluck').html('');
                 $('<div>').addClass('as').appendTo(that);
                 $('<img>').addClass('achieve_rob1 ').attr('src','<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/dog_1.png').appendTo(that);
                 (that).append('<p class="achieve_money1"><i class="_money">'+data.money+'</i>'+"￥"+'</p>');
                 setTimeout(function(){
            	   	$('.achieve_box').hide();
        	      	$('.servemodal').hide();	
            	 },2000);
    		}else if(data.errorcode=='1002'){
    			$('.wait p').text(data.msg);
    	       	$('.hide_modal').show();
    	       		setTimeout(function(){
    	     	    $('.hide_modal').hide();
     	     	    $('.servemodal').hide();
    	     	},2000);
	    	}else{ 
    			 $('.over_rob').show();
                 $(that).removeClass('nogetluck').html('');
                 $('<div>').addClass('as').appendTo(that);
                 $('<img>').addClass('over_').attr('src','<?php echo yii::$app->params['cdn16yous']; ?>/images/newyears/dog_2.png').appendTo(that);
                 $('<p>').addClass('over_tip').append('红包抢完啦').appendTo(that);
                 setTimeout(function(){
            	   	 $('.achieve_box').hide();
        	      	 $('.servemodal').hide();	 
            	 },2000);
    		}
    		}
    	})	   			
    });   
	</script>
</body>
</html>