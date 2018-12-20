
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/common.css">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/swiper.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/hot.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/rem.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/swiper(jspacker).js"></script>
	<title><?php echo isset($game->name)?$game->name:'';?></title>
</head>
<body>
	<div id="game-listbox">
	    <div id="content-list">
			  <!--热门-->
			  <div id="hotgame" class="gamecontent">
			  	<div class="game_list_box">
					<ul class="game_ul">
						<li class="game_img">
							<img src="<?php echo yii::$app->params['cdns']; ?>/game/<?php echo ($game->head_img)?$game->head_img:'notset.png';?>"> 
						</li> 
						<li class="game_describe">
							<p>
								<span class="game_name"><?php echo isset($game->name)?$game->name:'';?></span>
							</p>
							<p class="describe"><?php echo isset($game->descript)?$game->descript:'';?></p>
						</li>
						<li class="game_start gstart" name="<?php echo isset($game->id)?$game->id:'';?>" ><a class="start">开始</a></li>
					</ul>
				</div>
			  </div>
		</div>
		<?php  $imgarr = (isset($game->image))?json_decode($game->image,true):array();if(is_array($imgarr)):?>
		<div class="detailbox">
			<div id="tabs-container" class="swiper-container">
				<div class="swiper-wrapper detail-swiper">
				   <?php foreach ($imgarr as $img):?>
					<div class="swiper-slide">
						<img class="detialimg" src="<?php echo yii::$app->params['cdns']; ?>/game/<?php echo isset($img)?$img:'';?>">
					</div>
					<?php endforeach;?>
				</div>
			</div>
		</div>
		<?php endif;?>
			<?php if(isset($game->intro)):if($game->intro!=''):?>
		<div id="introduction">
			<div class="title"><span></span>游戏简介</div>
			<div class="intro_text">
				<?php echo isset($game->intro)?$game->intro:'';?>
			</div>
		</div>
		<?php endif;endif;?>
		<?php if($hotgame):?>
		<div id="recentlyplay">
			<div class="title"><span></span>近期热门</div>
			<div id="tabs_ulcontent" class="game_list swiper-container">
				<ul class="listul swiper-wrapper">
				  <?php foreach ($hotgame as $host):?>
					<li  class="swiper-slide gstart" name="<?php echo isset($host['id'])?$game['id']:'';?>">
						<img class="recentlyimg" src="<?php echo yii::$app->params['cdns']; ?>/game/<?php echo ($host['head_img'])? $host['head_img']:'notset.png';;?>">
						<p><?php echo isset($host['name'])?$host['name']:'';?></p>
					</li>
				  <?php endforeach;?>
				</ul>
			</div>
		</div>
		<?php endif;?>
		<div id="footer">
			<a class="start-game gstart" href="#" name="<?php echo isset($game->id)?$game->id:'';?>">开始游戏</a>
		</div>
	</div>
	</body>
</html>
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
	//大图介绍滑动
	   var tabsSwiper = new Swiper('#tabs-container',{
	      speed:500,
	      slidesPerView : 3,
	      spaceBetween : 10,
	    })
		//近期热门滑动
	   var tabsSwiper = new Swiper('#tabs_ulcontent',{
	      speed:500,
	      slidesPerView : 5,
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
	</script>