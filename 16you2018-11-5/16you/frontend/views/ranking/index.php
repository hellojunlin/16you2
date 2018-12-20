<link rel="stylesheet" type="text/css" href="/media/css/css.css">
<title>游戏金榜</title>
<body>
	<div class="gameContent">
		<div class="gameBanner">
			<img src="/media/images/rank_banner_v2.png">
		</div>
		<div class="gameList">
			<div class="serial">
				<p>
					第<span><?php echo $data['rule']; ?></span>期
					(<span><?php echo $data['now']; ?></span>-<span><?php echo $data['last'];?></span>)
				</p>
			</div>
				<div class="gameRank-data">
					<a href="/ranking/index.html?state=<?php echo $data['state'];?>"><?php if($data['state']==0):?>上期排名<?php else:?>下期排名<?php endif;?></a>
				</div>
				<div class="ranking cl">
					<div class="rankFirst">
					<?php if(isset($res3['1'])): ?>
						<div class="rankHead">
							<em>
								<img src="<?php echo $res3['1']['head_url']; ?>">
							</em>
							<p>300元红包</p>
						</div>
						<div class="rankName">
							<i class="name_box"><?php echo $res3['1']['username']; ?></i>
						</div>
					<?php else: ?>
						<div class="rankHead">
							<em>
								<img src="/media/images/noimg.jpg">
							</em>
							<p>300元红包</p>
						</div>
						<div class="rankName">
							<i class="name_box">充值上榜</i>
						</div>
					<?php endif; ?>
					</div>
					<div class="rankSecond">
					<?php if(isset($res3['0'])): ?>
						<div class="rankHead">
							<em>
								<img src="<?php echo $res3['0']['head_url']; ?>">
							</em>
							<p>500元红包</p>
						</div>
						<div class="rankName rankName-1">
							<i class="name_box"><?php echo $res3['0']['username']; ?></i>
						</div>
					<?php else: ?>
						<div class="rankHead">
							<em>
								<img src="/media/images/noimg.jpg">
							</em>
							<p>500元红包</p>
						</div>
						<div class="rankName">
							<i class="name_box">充值上榜</i>
						</div>
					<?php endif; ?>
					</div>
					<div class="rankThird">
					<?php if(isset($res3['2'])): ?>
						<div class="rankHead">
							<em>
								<img src="<?php echo $res3['2']['head_url']; ?>">
							</em>
							<p>200元红包</p>
						</div>
						<div class="rankName">
							<i class="name_box"><?php echo $res3['2']['username']; ?></i>
						</div>
					<?php else: ?>
						<div class="rankHead">
							<em>
								<img src="/media/images/noimg.jpg">
							</em>
							<p>200元红包</p>
						</div>
						<div class="rankName">
							<i class="name_box">充值上榜</i>
						</div>
					<?php endif; ?>
					</div>
				</div>
				<div class="surplus cl">
					<?php if($res7): ?>
					<?php $i=100;foreach($res7 as $k=>$v): ?>
					<ul class="surList">
						<div class="sur_Number"><?php echo $k+4; ?></div>
						<li class="sur_vip">
							<i class="sur_size"><?php echo $v['username']; ?></i>
						</li>
						<li class="integral">
							<p class="integral_top"><?php echo $i-$k*10; ?>元红包</p>
							<p class="integral_bottom"><?php echo $data['integral'][$k]; ?>积分</p>
						</li>
					</ul>
					<?php endforeach; ?>
					<?php endif; ?>
				</div>
		</div>
		<div class="gameInfo clearfix">
			<div class="infoLeft">
				<em>
					<img src="<?php echo $user->head_url; ?>" alt="">
				</em>
				<div class="infoName">
					<?php echo $user->username; ?>
				</div>
			</div>
			<div class="infoRight">
				
				<div class="btn_list">
					<a href="#">我要上榜</a>
				</div>
				<p class="rankingList">
				<?php if($data['orderby']): ?>
					<span>荣获第 <?php echo $data['orderby']; ?> 名</span>
				<?php else: ?>
					<span>暂未上榜</span>
				<?php endif; ?>
					<a href="/ranking/toorderby.html">往期信息</a>
				</p>
			</div>
		</div>
		<div class="gameRank bg_white cl">
			<div class="serial">
				<p>
					活动详情
				</p>
			</div>
			<?php echo $data['kr']; ?>
		</div>
		<div class="gameShare">
			<a href="#">告诉好友</a>
		</div>
	</div>
	<!-- //我要上榜弹框 -->
	<div class="rank_bg">
		<div class="ptnList">
			<h1>我要上榜</h1>
			<div class="myselfInfo">
				<h4>我的信息</h4>
				<p>
					<em>本期增长值：</em>
					<?php echo $data['sprice']; ?>
				</p>
				<p><?php echo $data['orderby']?'荣获第 '.$data['orderby'].' 名':'暂未上榜'; ?></p>
				<p>
					<em>注：玩家需要在游戏内充值金额才可上榜</em>
				</p>
			</div>
			<?php if($playname): ?>
			<div class="recently">
				<h4>最近在玩</h4>
				<div class="gameatt">
					<?php  foreach ($playname as $play):?>
					<p>
						<em name="<?php echo $play['id'];?>" class="gamestart">
							<img src="<?php echo yii::$app->params['backend']?>/media/images/game/<?php echo $play['head_img']?>">
						</em>
						<span>盛世霸业</span>
					</p>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif;?>
			<div class="knowBtn">
					<a href="#">我知道了</a>
				</div>
		</div>
	</div>
	<!-- //分享给好友弹框 -->
	<div class="share_fr">
		<em>
			<img src="/media/images/hand.png">
		</em>
		<p class="share_dear">
			马上分享给小伙伴
		</p>
	</div>
</body>
<script>
	//我要上榜弹框
	$('.btn_list a').click(function() {
		$('.rank_bg').show();
	}); 
	$('.knowBtn').click(function() {
		$('.rank_bg').css('display', 'none');
	});
	//分享给好友
	$('.gameShare a').click(function() {
		$('.share_fr').show().delay(5000).fadeOut();
	});
	 $('.share_fr').click(function() {
		$('.share_fr').css('display', 'none');
	 });

	//开始
    $('body').on('click','.gamestart',function(){
	    var _this = $(this);
	    var gid = _this.attr('name');
        $.ajax({
				type:'post',
	    		dataType:'json',
				data:{'gid':gid},
				url:'/index/play.html',
				success:function(data){
                        if(data.errorcode==0){
                        	//window.location.href=garr[1];
                        	window.location.href='/start/index/'+gid+'.html';
                        }else{
                            alert(data.info);
                        }
			    }
    		}) 
	})
</script>
</html>