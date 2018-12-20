<div id="game-listbox">
	<div id="content-list">
		<div class="contentbox asdtend">
		  <!--热门-->
		  <div id="hotgame" class="gamecontent">
		  <?php $playgame = isset(yii::$app->session['playgame'])?yii::$app->session['playgame']:array();if(!empty($playgame)):?>
				<?php foreach ($playgame as $play):?>
				  	<div class="game_list_box">
						<ul class="game_ul ulwidth" onclick="window.location.href='/game/detail/<?php echo isset($play['id'])?$play['id']:'';?>.html'">
							<li class="game_img liwidth1">
								<img src="<?php echo yii::$app->params['cdn']; ?>/game/<?php echo ($play['head_img'])?$play['head_img']:'notset.png';?>">
							</li>
							<li class="game_describe liwidth2">
								<p>
									<span class="game_name"><?php echo isset($play['name'])?$play['name']:'';?></span>
									<?php $labelarr = (!empty($play['label']))?json_decode($play['label'],true):array(); foreach ($labelarr as $lab){
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
								<p class="describe"><?php echo isset($play['descript'])?$play['descript']:'';?></p>
							</li>
						</ul>
						<div class="game_start" name="<?php echo isset($play['id'])?$play['id']:'';?>"><a class="start">开始</a></div>
					</div>
				<?php endforeach;?>
				<?php endif;?>
		  </div>
		</div>
	</div>
	<div class="about">客服QQ：178749290</div>
</div>

<script>		
//开始
$('.contentbox').on('click','.game_start',function(){
    var _this = $(this);
    var gid = _this.attr('name');
    window.location.href='/start/index/'+gid+'.html'; 
})
</script>