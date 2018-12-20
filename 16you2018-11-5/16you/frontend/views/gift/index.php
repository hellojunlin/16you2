<title>我的礼包</title>
<div id="content-list">
	<div class="contentbox">
	  	<div id="hotgame" class="gamecontent" name="2">
		  	<?php if($gift): ?>
		  	<?php foreach ($gift as $k => $v):?>
		  	<div class="game_list_box">
				<ul class="game_ul">
					<li class="game_img">
						<img src="<?php echo yii::$app->params['cdns']; ?>/game/<?php echo $v['game_image']; ?> "/>
					</li>
					<li class="game_describe">
						<p>
							<span class="game_name"><?php echo $v['game_name'].':'.$v['gift_name'] ?></span>
						</p>
						<p class="describe"><?php echo $v['content']; ?></p>
						<p class="tips">注意：兑换码每个服可用一次，每个礼包只能领一次</p>
					</li>
					<li class="game_start"><a class="start" name="<?php echo $v['gid'].'%$#'.$v['gift_name'].'%$#'.$v['CDKEY'].'%$#'.$v['content']; ?>">查看</a></li>
				</ul>
			</div>
			<?php endforeach; ?>
			<?php else:?>
			<p class="describeno">暂时没有礼包</p>
			<?php endif; ?>
	  	</div>
	</div>
</div>
<!--弹框-领取礼包-->
<div id="giftmodal" style="display:none;">
	<div class="servebox">
		<div class="get_img"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/get_img.png"></div>
		<img class="closeimg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/close_gray.png">
		<h5></h5>
		<div class="gifttxt"></div>
		<div class="notice">兑换码每个服可用一次，每个礼包只能领一次</div>
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
<span id="span_backend" class="hid"><?php echo yii::$app->params['cdn']; ?></span>
<script>
	//领取礼包弹框
	$('.gamecontent').on('click','.start',function(){
		var name = $(this).attr('name').split('%$#');
		$('.servebox>h5').html(name['1']);
		$('.servebox>.gifttxt').html(name['3']);
		$('.servebox>.active_num>a').html(name['2']);
		$(".receive_btn>a").attr('href','/start/index/'+name['0']+'.html');
		$('#giftmodal').show();
	})
	//关闭领取礼包弹框
	$('.closeimg,.receive_btn').click(function(){
		$('#giftmodal').hide();
	})

	databool = true;
	$(window).scroll(function(){
		if(checkScrollSlide()&&databool){	//判断页面滚动条往下拖 
	    	databool = false;
	    	if(!$('#_nodata').length && !$('#hotgame>.tmodel').length){
	    		var page = $("#hotgame").attr('name');//礼包的页数
	    		$("#hotgame").append('<p class="tmodel">正在加载...</p>');
				$.ajax({
					url:'/gift/index.html',
					data:{'page':page},
					dataType:'json',
					type:'post',
					success:function(data){
						var info = data.info;
						if(data.errorcode==0){
							$.each(info,function(kg,vg){
								$("#hotgame>.tmodel").before('<div class="game_list_box"><ul class="game_ul"><li class="game_img"><img src="'+$('#span_backend').html()+'/game/'+vg.game_image+'"/></li><li class="game_describe desc_mt"><p><span class="game_name">'+vg.game_name+':<i>'+vg.gift_name+'</i></span></p><p class="describe">'+vg.content+'</p><p class="tips">注意：兑换码每个服可用一次，每个礼包只能领一次</p></li><li class="game_start"><a class="start" name="'+vg.gid+'%$#'+vg.gift_name+'%$#'+vg.CDKEY+'%$#'+vg.content+'">查看</a></li></ul></div>');
							});
							page++; 
							$("#hotgame").attr('name',page);
							databool = true;
						}else{
							$("#hotgame").append('<div class="nodata" id="_nodata" align="center">'+info+'</div>');
						}
						$(".tmodel").remove();
					}
				});
			}
		}
	});
    function checkScrollSlide(){
		var lastBox = $('.game_list_box').last();
		var lastBoxDis = lastBox.offset().top+Math.floor(lastBox.outerHeight(true)/2);	//即当拖到最后一张的一半时加载
		var scrollTop = $(window).scrollTop();	//滚动条滚动高度
		var documentH = $(window).height();	//页面可视区高度
		return (lastBoxDis<scrollTop+documentH)?true:false;
	}
</script>