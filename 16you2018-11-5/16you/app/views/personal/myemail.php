<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/js/rem.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/css/my_email.css">
	<title>我的邮件</title>
</head>
<body>
	<div id="personal-center">
		<div class="personal_head"><img src="<?php echo isset($user->head_url)?$user->head_url:'';?>"></div>
		<div class="infobox">
		   <div class="nametext">
		   		<span class="username"><?php echo isset($user->username)?$user->username:'';?></span>
		   		<div class="vipbox">
		   			<div class="vipprogress" ><span></span></div>
		   			<div class="vipgrade">
		   				<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/vips.png">
		   				<p class="vipnum">VIP<?php echo $user->vip; ?></p>
		   			</div>
		   		</div>
		   </div>
		   <div class="infortext">ID:<span><?php echo isset($user->Unique_ID)?$user->Unique_ID:$user->id;?></span></div>
		</div>
	</div>
</div>
	<div class="email_m">
		<div class="mine_email">
			<h2 class="mine_e">我的邮件</h2>
			<em class="mineImg">
				<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/unread.png">
			</em>
			<div class="email_num">
				(<b><?php echo $type0; ?></b>/<b><?php echo $type1; ?></b>)
			</div>
		</div>
		<div class="email_content">
			<ul class="email_list" id="email_list">
				<?php if($email): ?>
				<?php foreach ($email as $v) {?>
				<li class="read" data="<?php echo $v['id']; ?>">
					<em class="readList">
						<?php if($v['type']==0): ?>
						<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/unread.png">
						<?php else: ?>
						<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/read.png">
						<?php endif; ?>
					</em>
					<div class="em_title">
						<h3><?php echo $v['title']; ?></h3>
					</div>
					<div class="emTime_data">
						<?php echo date('Y/m/d',$v['createtime']); ?>
					</div>
					<?php if($v['state']==1): ?>
					<div class="em_gift">
						<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/gift.png">
					</div>
					<?php endif; ?>
				</li>
				<?php };endif; ?>
			</ul>
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
            <a href="#" class="a_demo_two">
                关闭
            </a>
          </p>
      </div>
    </div>
	</div>
	<div id="servemodal_m" style="display:none">
		<div class="servebox">
			<h1 class="servetitle"></h1>
			<img class="closeimg" id="closeimg" src="http://cdn16you.zqqgl.com/images/close_gray.png">
			<p class="delete_email">确定要删除吗？</p>
			<div class="receive_btn" id="receive_btn">
			<a>确定</a>
			</div>
		</div>
	</div>
</body>
<script src="/media/js/jquery.min.js"></script>
<script>
$(".read").click(function(){
	var _obj = $(this);
	var id = _obj.attr('data');
	$.ajax({
		url:'/personal/reademail.html',
		data:{'id':id},
		type:'post',
		dataType:'json',
		success:function(data){
			if(data.errorcode==0){
				var info = data.info;
				$(".folder>.paper").find('p').html(info.content);
				$(".folder>.paper").find('h1').html(info.title);
				if(info.type==1){
					$(".folder").addClass('opened');
				}else{
					$(".email_num").find('b:first').html($(".email_num").find('b:first').html()-1);
				}
				_obj.find('img:first').attr('src',"<?php echo yii::$app->params['cdn16you']; ?>/images/read.png");
			}else{
				$(".folder>.paper").find('p').html(data.info);
			}
			$("#wrap").show();
		}
	})
});
$(".a_demo_two").click(function(){
	$("#wrap").hide();
})
$('.folder').click(function() {
	$(this).toggleClass('opened');
});
$.fn.longPress = function(fn) {
    var timeout = undefined;
    var $this = this;
    for(var i = 0;i<$this.length;i++){
        $this[i].addEventListener('touchstart', function(event) {
            timeout = setTimeout(fn, 800);
            }, false);
        $this[i].addEventListener('touchend', function(event) {
            clearTimeout(timeout);
            }, false);
    }
}
$('#email_list').on('touchstart',"li",function(){
    _list = $(this);
	_list.longPress(function(){
	   console.log(_list);
	   $('#servemodal_m').show();
	   $("#receive_btn").click(function(){
	   		$('#servemodal_m').css('display','none');
	   		_list.remove();
	   });
	   $('#closeimg').click(function(){
	   	 	$('#servemodal_m').css('display','none');
	   });
	});
});
</script>
</html>