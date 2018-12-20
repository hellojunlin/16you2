<title><?php echo isset($cate_name)?$cate_name.'-游戏分类':'休闲游戏';?></title>
<div id="content-list">
	<div class="contentbox" style="margin:0">
	  <!--热门-->
	  <div id="hotgame" class="gamecontent">
	  	<?php if($game): ?>
	  	<?php foreach($game as $v): ?>
	  	<div class="game_list_box">
			<ul class="game_ul ulwidth" onclick="window.location.href='/index/detail/<?php echo isset($v['id'])?$v['id']:'';?>.html'">
				<li class="game_img liwidth1">
					<img src="<?php echo yii::$app->params['cdns']?>/game/<?php echo ($v['head_img'])?$v['head_img']:'notset.png';?>">
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
						}?>
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
		//开始
	    $('.gamecontent').on('click','.game_start',function(){
		    var _this = $(this);
		    var gid = _this.attr('name');
		    window.location.href='/start/index/'+gid+'.html';
		})
</script>