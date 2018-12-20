<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<title>抽奖活动</title>
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/redpack/luck.css">
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
	    title:'16游双节活动', // 分享标题
	    link:  "<?php echo yii::$app->params['frontends']?>/luck/index.html",// 分享链接
	    imgUrl:"<?php echo yii::$app->params['cdn16yous'];?>/images/redpack/achieve.png", // 分享图标
	    success: function () { 
	       
	    },
	    cancel: function () { 
	        // 用户取消分享后执行的回调函数
	    	// alert("分享失败");
	    }
	});
	
	//分享朋友
	wx.onMenuShareAppMessage({
	    title: '16游双节活动', // 分享标题
	    desc: '双节庆典，实时红包抢不停，百分百中奖，没有红包算我输', // 分享描述
	    link: "<?php echo yii::$app->params['frontends']?>/luck/index.html",// 分享链接
	    imgUrl: "<?php echo yii::$app->params['cdn16yous'];?>/images/redpack/achieve.png", // 分享图标
	    type: 'link', // 分享类型,music、video或link，不填默认为link
	    success: function () { 
	    },
	    cancel: function () { 
	    }
	}); 
	//隐藏分享QQ
	wx.hideMenuItems({
	 	    menuList: ['menuItem:share:qq'] // 要隐藏的菜单项，只能隐藏“传播类”和“保护类”按钮，所有menu项见附录3
	});
  });
</script>
<body>
	<div class="luckbigbox">
	    <div class="topbg"><img class="topimg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/topbg.png"></div>
	    <!--整点秒抢-->
		<div class="seckill">
			<div class="sectitle">
				<img class="titlebg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/ban1.png">
				<p class="sectxt">整点秒抢</p>
			</div>
			<div class="luckredbox">
			   <?php $type = 3;if(isset($firstredarr['type']) && $firstredarr['type']==1):?>
			   <div class="luckred"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/achieve.png"><p class="m_c"><span class="moneycount"><?php echo isset($firstredarr['money'])?$firstredarr['money']:0;?></span>¥</p></div>
				<?php elseif (isset($firstredarr['type2']) && $firstredarr['type2']==2):?>
				<div class="luckred"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/overcard.gif"></div>
				<?php else:?>
				<div class="luckred nogetluck" id='1'>
				    <img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/notime.png">
					<p class=" timestar"><i class="starList">10</i>点开抢</p>
				</div>
				<?php endif;?>
				
				<?php if(isset($secredarr['type']) && $secredarr['type']==1):?>
			   <div class="luckred"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/achieve.png"><p class="m_c"><span class="moneycount"><?php echo isset($secredarr['money'])?$secredarr['money']:0;?></span>¥</p></div>
				<?php elseif(isset($secredarr['type2']) && $secredarr['type2']==2):?>
				<div class="luckred"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/overcard.gif"></div>
				<?php else:?>
				<div class="luckred nogetluck" id='2'>
					<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/notime.png">
					<p class=" timestar"><i class="starList">12</i>点开抢</p>
				</div>
				<?php endif;?>
				 
				<?php if(isset($thiredarr['type']) && $thiredarr['type']==1):?>
			   <div class="luckred"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/achieve.png"><p class="m_c"><span class="moneycount"><?php echo isset($thiredarr['money'])?$thiredarr['money']:0;?></span>¥</p></div>
				<?php elseif(isset($thiredarr['type2']) && $thiredarr['type2']==2):?>
				<div class="luckred"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/overcard.gif"></div>
				<?php else:?>
				<div class="luckred nogetluck " id='3'>
					<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/notime.png">
					<p class="timestar"><i class="starList">19</i>点开抢</p>
				</div>
				<?php endif;?>
				
				<?php if(isset($fouredarr['type']) && $fouredarr['type']==1):?>
			   <div class="luckred"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/achieve.png"><p class="m_c"><span class="moneycount"><?php echo isset($fouredarr['money'])?$fouredarr['money']:0;?></span>¥</p></div>
				<?php elseif(isset($fouredarr['type2']) && $fouredarr['type2']==2):?>
				<div class="luckred"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/overcard.gif"></div>
				<?php else:?>
				<div class="luckred nogetluck" id='4'>
					<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/notime.png">
					<p class="timestar"><i class="starList">21</i>点开抢</p>
				</div>
				<?php endif;?>
				
			</div>
			<!-- <div class="servemodal">
				<div class="luck_surp">
    				<img class="rob" src="/media/images/rob.gif">
    				<div class="achieve_box over_">
    					<img class="achieve_rob " src="/media/images/achieve.png">
    					<p class="achieve_money"><i class="_money">5</i>￥</p>
    				</div>
    				<img class="over_rob over_"src="/media/images/overcard.gif">
				</div>
			</div>	
			<div class="hide_modal">
				<div class="wait">
					<p>别着急！整点才开抢</p>
				</div>
			</div> -->
		</div>
		<!--幸运百分百-->
		<div class="seckill">
			<div class="sectitle">
				<img class="titlebg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/ban1.png">
				<p class="sectxt">幸运百分百</p>
			</div>
			<div class="treasurebox">
				<div class="clickbox"><img class="tbox" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/chest.png"></div>
				<div class="showbox">
					<div class="chestcon">
					    <span class="arrow"></span>
						<p class="chesttitle">我获得的奖品</p>
						<div class="listbox"></div>
					</div>
				</div>
			</div>
			<div class="turntable">
				<div class="rotate"><img id="rotate" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/body.png"></div>
				<div class="pointer">
				  	<div class="pointersbox">
						<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/pointer.png">
						<div class="pointertxt">
							<?php if($num==0): ?>							
								充值任意一款游戏才可以进行抽奖哦
							<?php elseif($num==-1): ?><!--不在活动时间内-->
								&nbsp;活动时间为 <br/>9月30~10月8号
							<?php else: ?>
						   <p>您拥有的抽奖机会<span id="timecount" class="time"><?php echo $num; ?></span>次</p>
						   <span class="startbtn">开始</span>
						   <?php endif; ?>
						</div>
				  	</div>
				    
			   </div>
			</div>
		</div>
		 <!--活动规则-->
		<div class="seckill">
			<div class="sectitle">
				<img class="titlebg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/ban2.png">
				<p class="sectxt">活动规则</p>
			</div>
			<div class="rulebox">
				<p>1、活动期间，玩家在每日单笔充值任意金额，均可参与单日的整点秒杀活动；</p>
				<p>2、每天10点、12点、19点、21点四轮整点秒杀，红包多多，中奖几率暴涨；</p>
				<p>3、活动期间，每日累计充值每满20元，可获得1次抽奖机会，满40元可获得2次，以此类推，不设上限，百分百中奖，多充多得，获得次数仅限当天有效；</p>
				<p>4、获得iphone8、小米Max2、京东购物卡、话费卡等奖励，请联系16游VIP客服-花花领取（微信：VIN7390、QQ：3243987127）；</p>
				<p>5、获得红包将根据您微信号自动发放到账，领取后请在参与活动的微信号中【钱包】查看；</p>
				<p>6、由于不可抗拒因素、网络、作弊等原因导致活动异常，最终解释权归16游h5游戏平台所有；</p>
			</div>
		</div>
	</div>
	<!--整点秒抢模态框-->
	<div class="skmol">
		<div class="secmolbox">
			<img class="robimg imgsize" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/rob.gif">
			<div class="getimg">
				<img class="imgsize" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/achieve.png">
				<p class="getm_c"><span class="getmoneycount">5</span>¥</p>
			</div>
			<div class="noget"><img class="imgsize" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/overcard.gif"></div>
			<div class="tiptxt">
		    	<img class="tipimg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/bk.png">
		     	<p class="tipp"></p>
		    </div>  
		</div>
	</div>
	<!--转盘显示-->
	<div class="rotatermol">
		<div class="rotatetip">
			<img class="roimgtip" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/achieve.png">
			<p class="rtip"><span class="tipmoney">5</span>¥</p>
		</div>
	</div>
</body>
<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/redpack/awardRotate.js"></script>
<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/redpack/rotate.js"></script>
<script>
    //打开百宝箱
    $('.tbox').click(function(){
    	if($(".listbox").html().length==0){
    		$(".listtxt").append('<p class="tmodel1" style="text-align: center;color: red;font-size: 0.2rem; padding: 0.1rem 0;">正在加载...</p>');
	    	$.ajax({
	    		url:'/luckredpack/mywinning.html',
	    		dataType:'json',
	    		success:function(data){
	    			if(data.errorcode==0){
	    				var winfo = data.info;
	    				$(winfo).each(function(k,v){
		    				$(".listbox").append('<div class="clist"><span class="listtxt">'+v.content+'</span><span class="viewbtn">'+v.createtime+'</span></div>');
	    				})
	    			}else{ 
	    				$('.listbox').append('<div class="clist"><span class="listtxt">'+data.info+'</span></div>');
	    			}
	    		}
	    	})
	    	$(".tomodel").remove();
    	}else{
    		$(".listbox").html('');
        }
    	$('.showbox').toggle();
    })
   
    //整点秒抢
    $('.luckredbox').on('click','.nogetluck',function(){
	   $('.skmol').show(); 
       var that = $(this);
       var type = that.attr('id');
       var date = 0;
       var numcache1 = "<?php echo yii::$app->cache->get($time.'1');?>";
       var numcache2 = "<?php echo yii::$app->cache->get($time.'2');?>";
       var numcache3 = "<?php echo yii::$app->cache->get($time.'3');?>";
       var numcache4 = "<?php echo yii::$app->cache->get($time.'4');?>";
       var num = '';
       var limitnum = 30; //限制的人数
       switch(type){
           case '1': date = 10;num=numcache1;limitnum=30;break;
           case '2': date = 12;num=numcache2;break;
           case '3': date = 19;num=numcache3;break;
           case '4': date = 21;num=numcache4;break;
           default: date = 0;
       }
       var myDate= new Date(new Date().toLocaleDateString()).getTime()+date*60*60*1000;
       var timestamp=new Date().getTime();
      if(timestamp<1518710400000){
    	   $('.tipp').text('您好，该活动9月30号开始，请耐心等待');
    	   $('.robimg').hide();
    	   $('.skmol').addClass('clickmol').show();
    	   $('.tiptxt').show();
           return false;
       }  

        if(timestamp>1520006400000){
    	   $('.tipp').text('您好，该活动已结束，请等下一期活动');
    	   $('.robimg').hide();
    	   $('.skmol').addClass('clickmol').show();
    	   $('.tiptxt').show();
           return false; 
       } 
        
   /*  if(timestamp<myDate){
           $('.tipp').text('您好,时间还未到，请稍等！');
           $('.robimg').hide();
    	   $('.skmol').addClass('clickmol').show();
    	   $('.tiptxt').show();
           return false;
       }    */
     if(num>=limitnum){
    	   setTimeout(function(){ 
    	   	   $('.skmol').addClass('clickmol');
    		   $('.robimg').hide();
          	   $('.noget').show();
               $(that).removeClass('nogetluck').html('');
               $('<img>').attr('src','<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/overcard.gif').appendTo(that);
      		},2000);
      	 	return false;
       }
        
       $.ajax({
      		url:'/luckredpack/robredpacket.html',
      		type:'post',
      		data:{'type':type},
      		dataType:'json',
      		success:function(data){
      			setTimeout(function(){
	     			 $('.robimg').hide();
		      		 if(data.errorcode==0){
		      		 	    $('.skmol').addClass('clickmol');
		     				$('.getimg').show();
		       			    $(that).removeClass('nogetluck').html('');
		       			    var newimg = $('<img>').attr("src","<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/achieve.png").appendTo(that);
		       			    var newp = $('<p>').attr('class','m_c').appendTo(that);
		       			    $('<span>').attr('class','moneycount').append(data.money).appendTo(newp);
		       			    newp.append('¥');  
		       			    $('.getmoneycount').text(data.money);
		  			}else if(data.errorcode==1002){
		  				  $('.skmol').addClass('clickmol');
		  				  $('.tipp').text(data.msg);
		  				  $('.tiptxt').show();
			  	    	  //$('.tipmodal').show();
			  	    	  /*setTimeout(function(){
			  	     	      //$('.tipmodal').hide();
			  	     	      $('.tiptxt').hide();
			  	     	      $('.skmol').removeClass('clickmol').hide();
			  	     	      $('.robimg').show();
			  	     	  },2000);*/
			  		}else{ 
			  			$('.skmol').addClass('clickmol');
		  				$('.noget').show();
		  	       	 	$(that).removeClass('nogetluck').html('');
		  	       	 	$('<img>').attr("src","<?php echo yii::$app->params['cdn16yous']; ?>/images/redpack/overcard.gif").appendTo(that);
		      		}
      			},2000);
      		}
      	})		
    });
    //关闭提示
    /*$('.tipmodal').click(function(){
    	$('.tipmodal').hide();
     })*/
    //关闭模态框
    $('body').on('click','.clickmol',function(){
      $('.skmol').removeClass('clickmol').hide();
      $('.robimg').show();
	  $('.getimg').hide();
	  $('.noget').hide();
	  $('.tiptxt').hide();
    });
</script>
</html>