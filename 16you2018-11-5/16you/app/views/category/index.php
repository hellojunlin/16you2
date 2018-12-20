<script type="text/javascript">
var data = {};
data.page = 'relax';
data.title = '休闲游戏';
data.state = 'start';
window.parent.postMessage(data, '*');


</script>
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/css/common.css">
	<title><?php echo isset($cate_name)?$cate_name.'-游戏分类':'休闲游戏';?></title>
<div id="content-list">
	<div class="contentbox">
	  <!--热门-->
	  <div id="hotgame" class="gamecontent" style="background:#fff">
	  	<?php if(isset($game)&&$game): ?>
	  	<?php foreach($game as $v): ?>
	  	<div class="game_list_box">
			<ul class="game_ul ulwidth" onclick="window.location.href='/game/detail/<?php echo isset($v['id'])?$v['id']:'';?>.html'">
				<li class="game_img liwidth1">
					<img src="<?php echo yii::$app->params['backend']?>/media/images/game/<?php echo isset($v['head_img'])?$v['head_img']:'';?>">
				</li>
				<li class="game_describe liwidth2">
					<p>
						<span class="game_name"><?php echo isset($v['name'])?$v['name']:'';?></span>
						<?php $labelarr = (!empty($v['label']))?json_decode($v['label'],true):array(); foreach ($labelarr as $lab){
								switch($lab){
									case 0 : echo "<span class='newgame'>新游</span>";break;
									case 1 : echo "<span class='hot'>热门</span>";break;
									case 2 : echo "<span class='package'>礼包</span>";break;
									case 3 : echo "<span class='sole'>独家</span>";break;
									case 4 : echo "<span class='thsfirst'>首发</span>";break;
									case 5 : echo "<span class='exclusive'>女性专属</span>";break;
								}
							}
						?>
					</p>
					<p class="describe"><?php echo isset($v['descript'])?$v['descript']:'';?></p>
				</li>
			</ul>
			<div class="game_start gamestart" name="<?php echo isset($v['id'])?$v['id']:'';?>"><a class="start">开始</a></div>
		</div>
		<?php endforeach; ?>
		<?php else:?>
		<p class="describeno">暂无数据</p> 
		<?php endif; ?>
	  </div>
	</div>
</div>
<script>
var _equipment = "<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
				echo 'IOS';
			}else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
   				 echo 'Android';
			}else{
 			     echo 'other';
			};?>"; //设备
if(_equipment!='other'){
	$(".mt_head").hide();
	$("div#game-iframe-div").css('top','0');
}
//返回-
$(".mt_goback").click(function(){
	window.history.go(-1);
})
//开始
$('.gamecontent').on('click','.game_start',function(){
    var _this = $(this);
    var gid = _this.attr('name');
    window.location.href='/start/index/'+gid+'.html';
})


//通知app该页面
window.onload=function(){
	   var data = {};
	   data.page = 'relax';
	   data.title = '休闲游戏';
	   data.state = 'end';
    window.parent.postMessage(data, '*');
}

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
      	
      		if(browser.versions.ios){
      			var iosWidth = window.screen.availWidth;
      		    // $('body').css('margin','0');
      		    $('body').css('width',iosWidth);
      		}
</script>