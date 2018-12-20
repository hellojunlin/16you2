    <!-- <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> -->
<link rel="stylesheet" href="<?php echo yii::$app->params['cdn16you']; ?>/css/newcss/init.css">
<link rel="stylesheet" href="<?php echo yii::$app->params['cdn16you']; ?>/css/swiper.min.css">
<link rel="stylesheet" href="<?php echo yii::$app->params['cdn16you']; ?>/css/newcss/adapt.css">
<link rel="stylesheet" href="<?php echo yii::$app->params['cdn16you']; ?>/css/newcss/index.css">   
<title><?php echo isset(yii::$app->session['plateform']->pname)?((yii::$app->session['plateform']->punid!='16you')?yii::$app->session['plateform']->pname.'游戏':'16游-游戏中心'):'16游-游戏中心';?></title>
<body>
    <!-- <header class="header"> -->
    <header class="header">
        <div class="user-headbox">
              <div class="u_h">
                <div class="headimg"><img src="<?php echo isset($user->head_url)?$user->head_url:yii::$app->params["cdn16you"].'/images/noimg.jpg';?>"></div>
               	<div class="infobox">
			   <div class="nametext">
			   		<span class="namebox"><?php echo isset($user->username)?$user->username:'';?></span>
			   		<div class="vipbox">
			   			<div class="vipprogress" ><span></span></div>
			   			<div class="vipgrade">
			   				<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/vips.png">
			   				<p class="vipnum">VIP<?php echo $user?$user->vip:'0'; ?></p>
			   			</div>
			   		</div>
			    </div>
				<div class="infortext">ID:<span><?php echo isset($user->Unique_ID)?$user->Unique_ID:'';?></span></div>
			</div>
		   </div>
                <!-- 以下是搜索框+关注部分 -->
             <div class="searchinputbox">
			      <div class="innerbox">
			      	 <input type="text" class="winput" placeholder="宠物小精灵">
			   		<!--  <img class="hitsearch" src="<?php echo yii::$app->params['cdn16you']; ?>/images/searchicon.png"> -->
	   		      <img class="cancelimg" id="cancelimg" src="<?php echo yii::$app->params['cdn16you']; ?>/images/closesearch.png">
	         </div>    
	  	     </div>
	  		 <a class="downbox" href="http://pc.16you.com/download/index.html"><img class="downloadimg" src="<?php echo yii::$app->params['cdn16you']; ?>/images/download01.png"></a>
	  		 <div class="searchbox" id="searchbox">
	   			 <img src="<?php echo yii::$app->params['cdn16you']; ?>/images/searchicon.png">
	   		 </div>
	  		 <div class="payattenction" id="payattenction">
	   			<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/gz.gif">
	   			<!-- <span class="gztxt">关注</span> -->
	   		 </div>
		   </div>
    </header>
    <div class="content">
        <!-- 导航begin -->
        <nav class="navtab">
            <ul>
                <li><a href="#section1">热门</a></li>
                <li><a href="#section2">新游</a></li>
                <li><a href="#section3">休闲</a></li>
                <li><a href="#section4">资讯</a></li>
            </ul>
        </nav>
        <!-- 导航end -->

        <!-- 轮播图begin -->
        <section class="top-banner">
            <div class="swiper-container myswiper-cont1" id="myswiper1">
                <div class="swiper-wrapper">
                     <?php if(is_array($carousel)): foreach ($carousel as $k=>$car):?>
                        <div class="swiper-slide carousel-btn" id="<?php echo  isset($car['url'])?$car['url']:'';?>,<?php echo $k+1;?>">
                                <img src="<?php echo yii::$app->params['cdn']; ?>/carousel/<?php echo isset($car['image'])?$car['image']:'';?>" alt="">
                        </div>
                      <?php endforeach; endif;?>
                    </div>
                <!-- 如果需要分页器 -->
                <div class="swiper-pagination"></div>
            </div>
            <?php if($newconsultarr):?>
            <div class="swiper-container myswiper-cont2" id="myswiper2">
                <div class="swiper-wrapper">
                   <?php foreach ($newconsultarr as $consult):?>
                    <div class="swiper-slide">
                        <div class="banner-msg-tip"><?php echo $consult['label'];?></div>
                        <p class="cons" name="<?php echo $consult['id'];?>"><a href="#"><?php echo $consult['title'];?></a></p>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
            <?php endif;?>
        </section>
        <!-- 轮播图end -->
         <!-- 最近在玩-->
       <?php if(isset($playgame)&&!empty($playgame)):?>
        <div id="recentlyplay">
			<div class="title">
				<span></span>最近在玩<a href="#" onclick="more('/index/nearplay.html')" class="more">更多></a>
			</div>
			<div class="game_list gamecontent">
				<ul class="listul">
					<?php  foreach ($playgame as $k=>$play):?>
						<li class="game_start gamestart" name="<?php echo isset($play['id'])?$play['id']:'';?>" id="<?php echo $k+1;?>,1">
							<img class="recentlyimg" src="<?php echo yii::$app->params['cdn']; ?>/game/<?php echo ($play['head_img'])?$play['head_img']:'notset.png';?>">
							<p><?php echo $play['name']?></p>
						</li>
					<?php endforeach;?>
				</ul>
			</div>
		</div>
		<?php endif;?>
        <!-- 热门推荐begin -->
        <section class="hot-game" id="section1">
            <div class="bar-title">
                <span><i class="title-icon-hot"></i>热门推荐</span>
                <a href="/game/moregame/0.html">更多></a>
            </div>
            <div class="hot-content">
                <ul>
                <?php foreach ($newhotgamearr as $k=>$hg):?>
                    <li>
                        <div class="hot-c-img"  onclick="window.location.href='/game/detail/<?php echo isset($hg['id'])?$hg['id']:'';?>.html'">
                                <img src="<?php echo yii::$app->params['cdn']?>/game/fgamelogo/<?php echo ($hg['f_gamelogo'])?$hg['f_gamelogo']:'notset.png';?>" alt="">
                        </div>
                        <div class="hot-c-msg">
                            <div class="hc-msg-text">
                                <div class="h-m-t-top">
                                    <div class="t-top-title"><?php echo isset($hg['name'])?$hg['name']:'';?></div>
                                    <div class="label-icon label-icon-lb t-top-label">礼包</div>
                                </div>
                                <div class="h-m-t-bottom">
                                    <img src="<?php echo yii::$app->params['cdn16you']?>/images/newimg/hot_label.png" alt="">
                                    <img src="<?php echo yii::$app->params['cdn16you']?>/images/newimg/hot_label.png" alt="">
                                    <img src="<?php echo yii::$app->params['cdn16you']?>/images/newimg/hot_label.png" alt="">
                                    <img src="<?php echo yii::$app->params['cdn16you']?>/images/newimg/hot_label.png" alt="">
                                    <img src="<?php echo yii::$app->params['cdn16you']?>/images/newimg/hot_label.png" alt="">
                                </div>
                            </div>
                            <div class="hc-msg-btn gamestart" name="<?php echo isset($hg['id'])?$hg['id']:'';?>" id="<?php echo $k+1;?>,2">马上玩</div>
                        </div>
                    </li>
                    <?php endforeach;?>
                </ul>
            </div>
        </section>
        <!-- 热门推荐end -->
        <!-- 新游尝鲜begin -->
        <section class="new-game" id="section2">
            <div class="bar-title">
                <span><i class="title-icon-new"></i>新游尝鲜</span>
                <a href="/game/moregame/1.html">更多></a>
            </div>
            <div class="new-content">
                <ul>
                  <?php foreach ($newgamearr as $k=>$ng):?>
                    <li>
                        <div class="new-c-msg" onclick="window.location.href='/game/detail/<?php echo isset($ng['id'])?$ng['id']:'';?>.html'">
                            <div class="nc-msg-titimg">
                                <img src="<?php echo yii::$app->params['cdn']?>/game/<?php echo ($ng['head_img'])?$ng['head_img']:'notset.png';?>" alt="">
                            </div>
                            <div class="nc-msg-text">
                                <div class="m-text-title">
                                    <div class="title-game-name"><?php echo isset($ng['name'])?$ng['name']:'';?></div>
                                    <div class="tags">
                                        <?php $labelarr = (!empty($ng['label']))?json_decode($ng['label'],true):array(); foreach ($labelarr as $lab){
									    switch($lab){
										case 0 : echo "<span class='newgame'>新游</span>";break;
										case 1 : echo "<span class='hot'>热门</span>";break;
										case 2 : echo "<span class='package'>礼包</span>";break;
                    					case 3 : echo "<span class='sole'>独家</span>";break;
										case 4 : echo "<span class='thsfirst'>首发</span>";break;
										case 5 : echo "<span class='exclusive'>女性专属</span>";break;
									  }
								}?>
                                    </div>
                                </div>
                                <div class="m-text-hot">
                                    <img src="<?php echo yii::$app->params['cdn16you']?>/images/newimg/hot_label.png" alt="">
                                    <img src="<?php echo yii::$app->params['cdn16you']?>/images/newimg/hot_label.png" alt="">
                                    <img src="<?php echo yii::$app->params['cdn16you']?>/images/newimg/hot_label.png" alt="">
                                </div>
                                <div class="m-text-describe"><?php echo isset($ng['descript'])?$ng['descript']:'';?></div>
                            </div>
                        </div>
                        <div class="new-c-btn gamestart" name="<?php echo isset($ng['id'])?$ng['id']:'';?>" id="<?php echo $k+1;?>,2">马上玩</div>
                    </li>
                  <?php endforeach;?>
                </ul>
            </div>
        </section>
        <!-- 新游尝鲜end -->
        <!-- 休闲游戏begin -->
        <section class="fine-game" id="section3">
            <div class="bar-title">
                <span><i class="title-icon-fine"></i>休闲游戏</span>
                <a href="/category/leisure.html">更多></a>
            </div>
             <div class="new-content">
                <ul>
                 <?php foreach ($newrelaxarr as $k=>$rg):?>
                    <li>
                        <div class="new-c-msg" onclick="window.location.href='/game/detail/<?php echo isset($rg['id'])?$rg['id']:'';?>.html'">
                            <div class="nc-msg-titimg">
                                <img src="<?php echo yii::$app->params['cdn']?>/game/<?php echo ($rg['head_img'])?$rg['head_img']:'notset.png';?>" alt="">
                            </div>
                            <div class="nc-msg-text">
                                <div class="m-text-title">
                                    <div class="title-game-name"><?php echo isset($rg['name'])?$rg['name']:'';?></div>
                                    <div class="tags">
                                        <?php $labelarr = (!empty($rg['label']))?json_decode($rg['label'],true):array(); foreach ($labelarr as $lab){
										    switch($lab){
											case 0 : echo "<span class='newgame'>新游</span>";break;
											case 1 : echo "<span class='hot'>热门</span>";break;
											case 2 : echo "<span class='package'>礼包</span>";break;
	                    					case 3 : echo "<span class='sole'>独家</span>";break;
											case 4 : echo "<span class='thsfirst'>首发</span>";break;
											case 5 : echo "<span class='exclusive'>女性专属</span>";break;
										  }
										}?>
                                    </div>
                                </div>
                                <div class="m-text-hot">
                                    <img src="<?php echo yii::$app->params['cdn16you']?>/images/newimg/hot_label.png" alt="">
                                    <img src="<?php echo yii::$app->params['cdn16you']?>/images/newimg/hot_label.png" alt="">
                                    <img src="<?php echo yii::$app->params['cdn16you']?>/images/newimg/hot_label.png" alt="">
                                </div>
                                <div class="m-text-describe"><?php echo isset($rg['descript'])?$rg['descript']:'';?></div>
                            </div>
                        </div>
                        <div class="new-c-btn gamestart" name="<?php echo isset($rg['id'])?$rg['id']:'';?>" id="<?php echo $k+1;?>,2">马上玩</div>
                    </li>
                   <?php endforeach;?>
                </ul>
            </div>
            <div class="fine-b-tab">
                <div class="game_type">
					<div class="game_type_list">
					   <a href="/category/index!0.html">
						   <i class="gametypeimg type01"></i>
					   	   <span>驰骋沙场</span>	
					   </a>
					</div>
					<div class="game_type_list">
					   <a href="/category/index!1.html">
						   <i class="gametypeimg type02"></i>
					   	   <span>交换人生</span>	
					   </a>
					</div>
					<div class="game_type_list">
					   <a href="/category/index!2.html">
						   <i class="gametypeimg type03"></i>
					   	   <span>商场老将</span>	
					   </a>
					</div>
					<div class="game_type_list">
					   <a href="/category/index!3.html">
						   <i class="gametypeimg type04"></i>
					   	   <span>棋逢对手</span>	
					   </a>
					</div>
				</div> 
            </div>
        </section>
        <!-- 精品专题end -->
        <!-- 热门资讯begin -->
      
        <section class="hots-game" id="section4">
            <div class="bar-title">
                <span><i class="title-icon-hots"></i>热门资讯</span>
                <a href="/game/moreconsult.html">更多></a>
            </div>
            <div class="hots-content1">
            <?php if($newconsultarr):foreach ($newconsultarr as $c):?>
                   <div class="game_list_box cons" name="<?php echo $c['id'];?>">
			            <ul class="news_ul">
			                <li class="game_img">
			                    <a class="ad"><?php echo $c['label'];?></a>
			                </li>
			                <li class="game_describe">
			                    <p class="game_p"><?php echo $c['title'];?></p>
			                </li>
			                <li class="game_start"><span class="time"><?php echo date('m-d',$c['starttime']);?></span></li>
			            </ul>
			        </div>
               <?php endforeach;endif;?>
            </div>
        </section>
      
        <!-- 新游资讯end -->
        <footer>
            <div class="foot-img">
                <img src="<?php echo yii::$app->params['cdn16you']?>/images/newimg/16you.png" alt="">
            </div>
            <p>页面由16游游戏平台提供</p>
        </footer>
    </div>
        <!-- 以下是搜索页面和关注页面 -->
    <!--搜索显示-->
     <div id="searchcon">
       <div class="atendBox">
       <?php if(!empty($playgame)):?>
			<div class="s_box">
				<p class="title">最近在玩</p>
				<div class="re_play_box">
				    <?php foreach ($playgame as $pg):?>
					<span onclick="window.location.href='/start/index/<?php echo isset($pg['id'])?$pg['id']:'';?>.html'"><?php echo  $pg['name'];?></span>
					<?php endforeach;?>
				</div>
			</div>
	    <?php endif;?>
       <?php if(!empty($newhotgamearr)):?>
			<div class="h_box">
				<p class="title">热门游戏</p>
				<div class="re_play_box">
				    <?php  foreach ($newhotgamearr as $hgg):?>
					<span onclick="window.location.href='/start/index/<?php echo isset($hgg['id'])?$hgg['id']:'';?>.html'"><?php echo $hgg['name']?></span>
					<?php endforeach;?>
				</div>
			</div> 
		<?php endif;?>
		</div>
        <div id="formdr"></div>
        <!-- 测试 -->
        	<ul id="listdr" style="display: none;"> 
		 <?php foreach ($newallgamearr as $ag):?>
    			<li class="game_list_box_mt show-detail" onclick="window.location.href='/game/detail/<?php echo isset($ag['id'])?$ag['id']:'';?>.html'">
                	<em>
                		<img src="<?php echo yii::$app->params['cdn']; ?>/game/<?php echo ($ag['head_img'])?$ag['head_img']:'notset.png';?>">
                	</em>
                	<a class="screening" href="#//"><?php echo $ag['name']?>
                	   <?php $glarr = (!empty($ag['label']))?json_decode($ag['label'],true):array();foreach ($glarr as $glab){
									switch($glab){
										case 0 : echo "<span class='newgame'>新游</span>";break;
										case 1 : echo "<span class='hot'>热门</span>";break;
										case 2 : echo "<span class='package'>礼包</span>";break;
										case 3 : echo "<span class='sole'>独家</span>";break;
										case 4 : echo "<span class='thsfirst'>首发</span>";break;
										case 5 : echo "<span class='exclusive'>女性专属</span>";break;
									}
						}?> 
                	</a>
                    <h2 class="slogan"><?php echo isset($ag['descript'])?$ag['descript']:'';?></h2>
    	            <div class="game_begin start-game" onclick="window.location.href='/start/index/<?php echo isset($ag['id'])?$ag['id']:'';?>.html'">
    	            	<a>开始</a>
    	            </div>
            	</li>  
            	<?php endforeach;?> 
	</ul> 
	<!-- 未搜索到时 -->
	<div class="no-result_Mt" style="display: none;">
		<p>—— 暂未搜索到任何结果 ——</p>
		<img src="<?php echo yii::$app->params['cdn16you'];?>/images/fail.png">
	</div>
		</div>
		<!-- 微信关注弹框 -->
	<div id="wxmodal">
		<div class="wxbox">
		   <div class="wxsmallbox">
		   		<img class="closecodemodal" id="closecodemodal" src="<?php echo yii::$app->params['cdn16you']; ?>/images/closewhite.png">
				<img class="wxcodeimg" src="<?php echo yii::$app->params['cdn16you']; ?>/images/qrcode.jpg">
		   </div>
			<p class="tipcode">长按识别二维码，关注16游微信公众号</p>
		</div>
	</div>
		
		<!-- 微信关注弹框 -->
	<div id="wxmodal">
		<div class="wxbox">
		   <div class="wxsmallbox">
		   		<img class="closecodemodal" id="closecodemodal" src="<?php echo yii::$app->params['cdn16you']; ?>/images/closewhite.png">
				<img class="wxcodeimg" src="<?php echo yii::$app->params['cdn16you']; ?>/images/qrcode.jpg">
		   </div>
			<p class="tipcode">长按识别二维码，关注16游微信公众号</p>
		</div>
	</div>
</body>
<script src="<?php echo yii::$app->params['cdn16you']; ?>/js/jquery.min.js"></script>
<script src="<?php echo yii::$app->params['cdn16you']; ?>/js/swiper.min.js"></script>
<script src="<?php echo yii::$app->params['cdn16you']; ?>/js/newjs/jquery.navScroll.js"></script>
<script src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/newjs/index.js"></script>
