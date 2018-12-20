<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8"
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<!-- <link rel="stylesheet" type="text/css" href="<?php //echo yii::$app->params['cdn16you']; ?>/css/common.css"> -->
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/gamedetail.css">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/swiper.min.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/rem.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/swiper(jspacker).js"></script>
	<title><?php echo isset($gameinfo['name'])?$gameinfo['name']:'';?></title>
</head>
<body>
	<div id="game-listbox">
	   <div class="gbgbox">
		<div class="gamebackground">
		   <?php if($gameinfo['detailimg']):?>
		   <img class="backgroundimg" src="<?php echo yii::$app->params['cdns'];?>/game/<?php echo $gameinfo['detailimg'];?>">
		   <?php else:?>
		    <img class="backgroundimg" src="http://cdn16you.zqqgl.com/images/defaultbg.png">
		   <?php endif;?>
		   <div class="blurbg"><img src="<?php echo yii::$app->params['cdn16yous'];?>/images/blurd.png"></div>
           <div class="gamelogobox">
           	  <img class="gamelogo" src="<?php echo yii::$app->params['cdns']; ?>/game/<?php echo ($gameinfo['head_img'])?$gameinfo['head_img']:'notset.png';?>">
           	  <p class="gamename"><?php echo isset($gameinfo['name'])?$gameinfo['name']:'';?></p>
           	  <p class="gamebrief"><?php echo isset($gameinfo['descript'])?$gameinfo['descript']:'';?></p>
           </div>
		 </div>
         <!-- <a class="startbtn" href="">开始游戏</a> -->
         <a class="start-game gstart startbtn" href="#" name="<?php echo isset($gameinfo['id'])?$gameinfo['id']:'';?>">开始游戏</a>
	    </div>
		<div class="introduction">
			<div class="title"><span></span>游戏简介</div>
			<div class="intro_text">
				<p class="gameinfo_txt"><?php echo isset($gameinfo['intro'])?$gameinfo['intro']:'';?></p><a class="viewall">全文</a>
			</div>
		</div>
		<?php if($gift):foreach ($gift as $g):?>
		<div class="introduction">
			<div class="title"><span></span>游戏礼包</div>
			<div class="intro_text giftbag">
			   <div class="inro-left">
			   	  <p class="intro-title"><span class="flag <?php echo ($g['gifttype']==4)?'entry-ground':'';?>">
			   	  <?php switch($g['gifttype']){
			   	  	      case 0: echo '新手';break;
			   	  	      case 1: echo '节日';break;
			   	  	      case 2: echo '活动';break;
			   	  	      case 3: echo '首发';break;
			   	  	      case 4: echo '入群';break;
			   	  	      default: echo '礼包';
			   	  }?>
			   	  </span><span class="game-title"><?php echo $g['gift_name']?></span></p>
			   	  <p class="intro-info"><?php echo $g['content'];?></p>
			   	  <p class="intro-term">有效期：<span class="intro-time"><?php echo ($g['validtime'])? date('Y-m-d',$g['validtime']):'永久';?></span></p>
			   </div>
			   <div class="intro-right"><a class="receivebtn" href="#"  name="<?php echo $g['number'].'%$#'.$g['game_name'].'%$#'.$g['gid']?>" >领取</a></div>
			</div>
		</div>
		<?php endforeach;endif;?>
		<div class="introduction">
			<div class="title"><span></span>游戏信息</div>
			<div class="intro_text intro-information">
			   <?php if($gameinfo['r_company']):?>
			   <p class="p-developer">研发商：<span class="developer"><?php echo $gameinfo['r_company'];?></span></p>
			   <?php endif;?>
			    <?php if($gameinfo['compname']):?>
			   <p class="p-developer">发行商：<span class="developer"><?php echo $gameinfo['compname'];?></span></p>
			    <?php endif;?>
			    <?php if($gameinfo['article']):?>
			   <p class="p-developer">文网游备字：<span class="developer"><?php echo $gameinfo['article'];?></span></p>
			    <?php endif;?>
			</div>
		</div>
		<?php if($hotgame):?>
		<div id="recentlyplay" class="introduction">
			<div class="title"><span></span>近期热门</div>
			<div id="tabs_ulcontent" class="game_list swiper-container">
				<ul class="listul swiper-wrapper">
				  <?php foreach ($hotgame as $host):?>
					<li  class="swiper-slide gstart" name="<?php echo isset($host['id'])?$gameinfo['id']:'';?>">
						<img class="recentlyimg" src="<?php echo yii::$app->params['cdns']; ?>/game/<?php echo ($host['head_img'])? $host['head_img']:'notset.png';;?>">
						<p><?php echo isset($host['name'])?$host['name']:'';?></p>
					</li>
				  <?php endforeach;?>
				</ul>
			</div>
		</div>
		<?php endif;?>
	</div>
	<!--弹框-领取礼包-->
	<div id="giftmodal" style="display:none;">
		<div class="servebox">
			<div class="get_img"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/get_img.png"></div>
			<img class="closeimg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/close_gray.png">
			<h5>元旦礼包</h5>
			<div class="gifttxt"></div>
			<div class="notice">兑换码每个服可用一次，明日可再次领取</div>
			<div class="active_num">
				<a></a>
			</div>
			<h2><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/up_icon.png">长按上方复制激活码</h2>
			<div class="ewm_box">
				<a>悬浮提示</a>
			</div>
		</div>
	</div>
			<!--弹框-客服-->
	<div id="servemodal" style="display:none;">
		<div class="servebox">
			<h1 class="servetitle">联系客服</h1>
			<img class="rqcloseimg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/close_gray.png">
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
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/jquery.cookie.js"></script>
	<script src="http://pv.sohu.com/cityjson?ie=utf-8"></script>  
	<script type="text/javascript">
	<?php  $user = isset(yii::$app->session['user'])?yii::$app->session['user']:'';?>
	var _uid = "<?php echo isset($user)?$user->id:'';?>";//获取用户ID  
	var _username = "<?php echo isset($user)?$user->username:'';?>";//用户名称
	var _pid = "<?php echo isset($user)?$user->pid:''?>"; // 渠道唯一标识
	var _ip = returnCitySN["cip"]+'/'+returnCitySN["cname"]; //ip/地域
	var _pname = '<?php echo isset($pname->pname)?$pname->pname:'';?>'; //渠道名称
	var _equipment = "<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
					echo 'IOS';
				}else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
	   				echo 'Android';
				}else{
	 			    echo 'other';
				};?>"; //设备
	var _source = '';
	function postmessage() {           
		var ga = document.createElement('script');   
		ga.type = 'text/javascript';   
		ga.charset='gbk';  
		ga.async = true;//ga.async = true 异步调用外部js文件，即不阻塞浏览器的解析  
		ga.src = '/media/js/ana.js';    
		var s = document.getElementsByTagName('script')[0];    //取得第一个tag名为script的元素  
		s.parentNode.insertBefore(ga, s);             //在s前添加元素ga  
	};
	
	 /**
	   * ModalHelper helpers resolve the modal scrolling issue on mobile devices
	   * http://github.com/twbs/bootstrap/issues/15852
	   * requires document.scrollingElement polyfill http://github.com/yangg/scrolling-element
	   */
	  var ModalHelper = (function(bodyCls) {//弹框时禁止页面滚动
	    var scrollTop;
	    return {
	      afterOpen: function() {
	        scrollTop = document.scrollingElement.scrollTop;
	        document.body.classList.add(bodyCls);
	        document.body.style.top = -scrollTop + 'px';
	      },
	      beforeClose: function() {
	        document.body.classList.remove(bodyCls);
	        // scrollTop lost after set position:fixed, restore it back.
	        document.scrollingElement.scrollTop = scrollTop;
	      }
	    };
	  })('modal-open');
	  function openModal() {//打开模态框时
	    ModalHelper.afterOpen();
	  }
	  function closeModal() {//关闭模态框时
	    ModalHelper.beforeClose();
	  }
	//查看全文
	$('.viewall').on('click',function(){
        $(this).hide();
        $('.gameinfo_txt').removeClass('hiddentxt');
	});
	//全文显示
	var txtnum = $('.gameinfo_txt').text().length;
	if(txtnum > 100){
		$('.gameinfo_txt').addClass('hiddentxt');
		$('.viewall').css('display','block');
	}
	//领取礼包弹框
	/* $('.receivebtn').click(function(){
		$('#giftmodal').show();
	}) */
	//关闭领取礼包弹框
	$('.closeimg').click(function(){
		$('#giftmodal').hide();
		closeModal();
	})
	
	$('.gstart').click(function(){
		    var _this = $(this);
		    var gid = _this.attr('name');
		    window.location.href='/start/index/'+gid+'.html';
		    var id = _this.attr('id');
		    if(id){
		    	 _source = '详情页开始';//来源
		    	postmessage(); 
			}
     }) 
     
     /**
     	礼包领取
     **/
     $('body').on('click', '.receivebtn', function() {
     	 openModal();
		var obj = $(this);
		if(obj.html()=='领取'){
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
						obj.html('查看').css('background', '#fed134');
					} else {
						alert(info)
					}
				}
		})
		return false;
	}else{
		$('#giftmodal').show();
    }
});
     //入群
	$('.entry-ground').on('click',function(){
        $('#servemodal').show(); 
        openModal();
	});
	//关闭入群
	$('body').on('click','.rqcloseimg,.receive_btn',function(){
       $('#servemodal').hide(); 
       closeModal()
	});
		//近期热门滑动
   var tabsSwiper = new Swiper('#tabs_ulcontent',{
      speed:500,
      slidesPerView : 5
    })
	</script>
</body>
</html>