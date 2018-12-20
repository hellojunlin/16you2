<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/swiper.min.css">
<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/swiper(jspacker).js"></script>
<title><?php echo isset(yii::$app->session['plateform']->pname)?((yii::$app->session['plateform']->punid!='16you')?yii::$app->session['plateform']->pname.'游戏':'16游-游戏中心'):'16游-游戏中心';?></title>
	<div id="game-listbox">
		<div class="user-headbox">
		  <div class="u_h">
			<div class="headimg"><img src="<?php echo isset($user->head_url)?$user->head_url:yii::$app->params["cdn16yous"].'/images/noimg.jpg';?>"></div>
			<div class="infobox">
			   <div class="nametext">
			   		<span class="namebox"><?php echo isset($user->username)?$user->username:'';?></span>
			   		<div class="vipbox">
			   			<div class="vipprogress" ><span></span></div>
			   			<div class="vipgrade">
			   				<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/vips.png">
			   				<p class="vipnum">VIP<?php echo $user->vip; ?></p>
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
		   		 <img class="cancelimg" id="cancelimg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/closesearch.png">
		      </div>
		   </div>
		   <a class="downbox" href="http://pc.16you.com/download/index.html"><img class="downloadimg" src="<?php echo yii::$app->params['cdn16you']; ?>/images/download01.png"></a>
		   <div class="searchbox" id="searchbox">
		   	 <img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/searchicon.png">
		   </div>
		   <div class="payattenction" id="payattenction">
		   		<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/gz.gif">
		   		<!-- <span class="gztxt">关注</span> -->
		   </div>
		</div>
	<div id="indexallconbox">
		<div class="sliderbox">
			<div id="tabs-container" class="swiper-container">
				<div class="swiper-wrapper">
				   <?php if(is_array($carousel)): foreach ($carousel as $k=>$car):?>
					<div class="swiper-slide carousel-btn"  id="<?php echo  isset($car['url'])?$car['url']:'';?>,<?php echo $k+1;?>">
						<img src="<?php echo yii::$app->params['cdns']; ?>/carousel/<?php echo isset($car['image'])?$car['image']:'';?>">
					</div>
					<?php endforeach; endif;?>
				</div>
				<div class="swiper-pagination tabpoint"></div>
			</div>
			<!-- <div class="tabs"> -->
				<ul class="tabsul">
				<?php if(is_array($carousel)): foreach ($carousel as $car):?>
					<li></li>
				<?php endforeach; endif;?>
				</ul>
			<!-- </div> -->
		</div>
		<?php if(!empty($playgame)):?>
		<div id="recentlyplay">
			<div class="title">
				<span></span>最近在玩<a href="#" onclick="more('/index/nearplay.html')" class="more">更多></a>
			</div>
			<div class="game_list gamecontent">
				<ul class="listul">
					<?php  foreach ($playgame as $k=>$play):?>
						<li class="game_start gamestart" name="<?php echo isset($play['id'])?$play['id']:'';?>" id="<?php echo $k+1;?>,1">
							<img class="recentlyimg" src="<?php echo yii::$app->params['cdns']; ?>/game/<?php echo ($play['head_img'])?$play['head_img']:'notset.png';?>">
							<p><?php echo $play['name']?></p>
						</li>
					<?php endforeach;?>
				</ul>
			</div>
		</div>
		<?php endif;?>
		<div id="content-list">
			<div class="contenttabs">
				<ul class="c_tabsul">
					<li class="c_active"><a  href="#0">热门</a></li>
					<li><a href="#1">新游</a></li>
                    <li><a href="#2">新开服</a></li>
					<li id="consult">
						<a a href="#3">资讯</a>
						<span class="" id="point"></span>
					</li>
				</ul>
			</div>
			<div class="contentbox">
			  <!--热门-->
			 <div id="hotgame" class="gamecontent">
			    <?php foreach ($hotgame as $k=>$hg):?>
			  	<div class="game_list_box" style="display:<?php echo ($hg['id']==10192)?'none':'block';?>">
					<ul class="game_ul ulwidth"  onclick="window.location.href='/index/detail/<?php echo isset($hg['id'])?$hg['id']:'';?>.html'">
						<li class="game_img liwidth1">
							<img src="<?php echo yii::$app->params['cdns']; ?>/game/<?php echo ($hg['head_img'])?$hg['head_img']:'notset.png';?>">               
						</li>
						<li class="game_describe liwidth2">
							<p>
								<span class="game_name"><?php echo isset($hg['name'])?$hg['name']:'';?></span>
								<?php $labelarr = (!empty($hg['label']))?json_decode($hg['label'],true):array(); foreach ($labelarr as $lab){
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
							<p class="describe"><?php echo isset($hg['descript'])?$hg['descript']:'';?></p>
						</li>
					</ul>
					<div  class="game_start gamestart" name="<?php echo isset($hg['id'])?$hg['id']:'';?>" id="<?php echo $k+1;?>,2"><a class="start">开始</a></div>
				</div>
				<?php endforeach;?>	
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
				
				<?php foreach ($gamearr as $k=>$g):?>
				<div class="game_list_box">
					<ul class="game_ul ulwidth"  onclick="window.location.href='/index/detail/<?php echo isset($g['id'])?$g['id']:'';?>.html'">
						<li class="game_img liwidth1">
							<img src="<?php echo yii::$app->params['cdns']; ?>/game/<?php echo ($g['head_img'])?$g['head_img']:'notset.png';?>">
						</li>
						<li class="game_describe liwidth2">
							<p class="p_gamename">
								<span class="game_name"><?php echo isset($g['name'])?$g['name']:'';?></span>
								<?php $glabelarr = (!empty($g['label']))?json_decode($g['label'],true):array();foreach ($glabelarr as $glab){
									switch($glab){
										case 0 : echo "<span class='newgame'>新游</span>";break;
										case 1 : echo "<span class='hot'>热门</span>";break;
										case 2 : echo "<span class='package'>礼包</span>";break;
										case 3 : echo "<span class='sole'>独家</span>";break;
										case 4 : echo "<span class='thsfirst'>首发</span>";break;
										case 5 : echo "<span class='exclusive'>女性专属</span>";break;
									}
								}?>
							</p>
							<p class="describe"><?php echo isset($g['descript'])?$g['descript']:'';?></p>
						</li>
					</ul>
					<div  class="game_start gamestart" name="<?php echo isset($g['id'])?$g['id']:'';?>" id="<?php echo $k+1;?>,3"><a class="start">开始</a></div>
				</div>
				<?php endforeach;?>
			  </div>
         <!-- 新游 -->
			  <div id="newgames" class="gamecontent" name="1"></div>
			  <!-- 新开服 -->
			  <div id="newservices" class="gamecontent">
			  	 <div class="nser">
					  <ul class="newservice">
					  	<li class="borderright newacitve"><a>已开新服</a></li>
					  	<li><a>新服预告</a></li>
					  </ul>
					  <div class="gamecontentbox">
					    <div id="hadopen" class="n_slist"  name="1"></div>
						<div  id="herald" class="n_slist"  name="1"></div>
					  </div>
					</div>	
			  </div>
			 	<!--资讯-->
			  	<div id="information" class="gamecontent" name="1"></div>
			</div>
		</div>
	</div>
	<!--弹框-领取礼包-->
	<div id="giftmodal" class="hid">
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
			<div class="receive_btn gamecontent">
				<a href='#' class="gamestart">开始游戏</a>
			</div>
		</div>
	</div>
	<span class="hid" id="span_backend"><?php echo yii::$app->params['backends']; ?></span>
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
		<?php if(!empty($hotgame)):?>
			<div class="h_box">
				<p class="title">热门游戏</p>
				<div class="re_play_box">
				    <?php $hgamearr = array_slice($hotgame,0,6); foreach ($hgamearr as $hgg):?>
					<span onclick="window.location.href='/start/index/<?php echo isset($hgg['id'])?$hgg['id']:'';?>.html'"><?php echo $hgg['name']?></span>
					<?php endforeach;?>
				</div>
			</div> 
		<?php endif;?>
		</div>
		<div id="formdr"></div>
		<!-- 测试 -->
	<ul id="listdr" style="display: none;"> 
		 <?php foreach ($allgame as $ag):?>
    			<li class="game_list_box_mt show-detail" onclick="window.location.href='/index/detail/<?php echo isset($ag['id'])?$ag['id']:'';?>.html'">
                	<em>
                		<img src="<?php echo yii::$app->params['cdns']; ?>/game/<?php echo ($ag['head_img'])?$ag['head_img']:'notset.png';?>">
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
		<img src="<?php echo yii::$app->params['cdn16yous'];?>/images/fail.png">
	</div>
		</div>
		<!-- 微信关注弹框 -->
	<div id="wxmodal">
		<div class="wxbox">
		   <div class="wxsmallbox">
		   		<img class="closecodemodal" id="closecodemodal" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/closewhite.png">
				<img class="wxcodeimg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/qrcode.jpg">
		   </div>
			<p class="tipcode">长按识别二维码，关注16游微信公众号</p>
		</div>
	</div>
    <!--抢红包弹框-->
    <?php if(false):?>
	<div class="actpromoal">
		<div class="actprobox">
			<img class="imporimg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/wsimg.png">
			<img class="colseactpro" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/closeimg.png">
			<a class="intobtn" href="/lucked/index.html">马上进入</a>
		</div>
	</div>
	<?php endif;?>
	<script type="text/javascript" src="//pingjs.qq.com/h5/hotclickurl.js?v1.0" name="mtah5hoturl" sid="500504927" hid="ht5ae303eee75ee"></script>
	<script type="text/javascript" src="//pingjs.qq.com/h5/hotclick.js?v2.0" name="mtah5hotclick" sid="500504927" hid="5ae30560d1a89"></script>
	<script type="text/javascript">
	//关闭抢红包弹框
	$('.colseactpro,.intobtn').click(function(){
		$('.actpromoal').hide();
	}); 
	var backurl = "<?php echo yii::$app->params['cdns']; ?>";
    var thisId = window.location.hash;
	if(thisId != "" && thisId != undefined){
		thisId = thisId.substring(1);
		(thisId==1 ) && getnewgame(); //新游
    	(thisId==2 ) && getnewservice(1); //已开新服
    	(thisId==3) && getconsult();	
	}
    var consult_boolean = "";
	var _pname = ''; //渠道名称
	var _source = '首页';//来源
	getpcdata();
	//获取平台名称，咨询是否有红点
    function getpcdata(){
	   $.ajax({
			url:'/index/getcpdata.html',
			type:'post',
			dataType:'json',
			success:function(data){
				if(data.errorcode==0){
					var info = data.info;
					consult_boolean = info.consult_boolean;
					if(info.consult_boolean==1){
                          $('#point').addClass('point');
				    }
					_pname = info.pname;
					_source = '首页';
					postmessage();
				}
				
			}
		})
      }
	
		//滑动
	    $('.tabsul li:first').addClass('active');
	    var tabsSwiper = new Swiper('#tabs-container',{
	      speed:500,
	      autoplay:5000,
	      loop:true,
    	  autoplayDisableOnInteraction : false,    //注意此参数，默认为true
    	  pagination : '.swiper-pagination',
	    //   onSlideChangeStart:function(){
	    //     $('.tabsul .active').removeClass('active');
	    //     $('.tabsul li').eq(tabsSwiper.activeIndex).addClass('active');
	    //   }
	    // })
	    // $('.tabsul li').on('touchstart mousedown',function(e){
	    //   e.preventDefault();
	    //   $('.tabsul .active').removeClass('active');
	    //   $(this).addClass('active');
	    //   tabsSwiper.slideTo($(this).index());
	    // })
	    // $('.tabsul li').click(function(e){
	    //   e.preventDefault();
	     })
	    //选卡切换
	    var databool = true;
		  var pagenum = 1;
	    var index = (thisId)?thisId:0;  <?php // echo ($type==0)?0:1;?>;
	    if(index =='0'){
	    	$('.c_tabsul li').eq(0).addClass('c_active').siblings().removeClass('c_active');
	    	$('.contentbox div.gamecontent').eq(0).show().siblings().hide();
		}else if(index=='1'){
			$('.c_tabsul li').eq(1).addClass('c_active').siblings().removeClass('c_active');
			$('.contentbox div.gamecontent').eq(1).show().siblings().hide();
	    }else if(index=='2'){//新开服
	    	$('.c_tabsul li').eq(2).addClass('c_active').siblings().removeClass('c_active');
			$('.contentbox div.gamecontent').eq(2).show().siblings().hide();
		}else if(index=='3'){//咨询
			$('.c_tabsul li').eq(3).addClass('c_active').siblings().removeClass('c_active');
			$('.contentbox div.gamecontent').eq(3).show().siblings().hide();
		}	  
      //新开服选卡切换
	    var index2 = 0;
		$('.newservice li').click(function(){
			$(this).addClass('newacitve').siblings().removeClass('newacitve');
			index2 = $(this).index();
			(index2==0 ) && getnewservice(1); //已开新服
			(index2==1 ) && getnewservice(2); //新服预告
			$('.gamecontent .n_slist').eq(index2).show().siblings().hide();
		 });

	    $('.c_tabsul li').click(function(){
	    	$(this).addClass('c_active').siblings().removeClass('c_active');
	    	index = $(this).index();
	    	(index==1 ) && getnewgame(); //新游
	    	(index==2 && index2 ==0) && getnewservice(1); //已开新服
	    	(index==2 && index2 ==1) && getnewservice(2); //新服预告
	    	(index==3) && getconsult();
	    	databool = true;
	    	$('.contentbox div.gamecontent').eq(index).show().siblings().hide();
	    })
	    
		$(window).scroll(function(){
			if(checkScrollSlide()&&databool){	//判断页面滚动条往下拖 
				if(index==0){
					getgameinfo(pagenum);
				}else if(index==1){ //新游
					getnewgame();
			   }else if(index=2){
				    (index2 ==0) && getnewservice(1); //已开新服
			    	(index2 ==1) && getnewservice(2); //新服预告
				}else if(index==3){
					getconsult();
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

		 //获取新游
	    function getnewgame(){
	    	databool = false;
	    	if(!$('#_nodata2').length && !$('#newgames>.tmodel').length){
	    		var page = $("#newgames").attr('name');//新游的页数
	    		$("#information").append('<p class="tmodel">正在加载...</p>');
				$.ajax({
					url:'/newgame/getnewgame.html',
					data:{'page':page},
					dataType:'json',
					type:'post',
					success:function(data){
						var infog = data.info;
						if(data.errorcode==0){ 
							$.each(infog,function(k,v){
								var div = $('<div>').addClass('game_list_box').appendTo($('#newgames'));     
                                var ul = $('<ul>').addClass('game_ul ulwidth').attr('onclick',"window.location.href='/index/detail/"+v.id+".html'").appendTo(div); 
                                var li1 = $('<li>').addClass('game_img liwidth1').appendTo(ul);
                                $('<img>').attr('src',backurl+"/game/"+v.head_img).appendTo(li1);
                                var li2 = $('<li>').addClass('game_describe liwidth2').appendTo(ul);
								var p1 = $('<p>').addClass('p_gamename').appendTo(li2);
								$('<span>').addClass('game_name').append(v.name).appendTo(p1);
								$.each(v.label,function(k,v){	
									switch(v){
									case '0':	$('<span>').addClass('newgame').append('新游').appendTo(p1);break;
									case '1':	$('<span>').addClass('hot').append('热门').appendTo(p1);break;	
									case '2':	$('<span>').addClass('package').append('礼包').appendTo(p1);break;	
									case '3':	$('<span>').addClass('sole').append('独家').appendTo(p1);break;
									case '4':	$('<span>').addClass('thsfirst').append('首发').appendTo(p1);break;
									case '5':	$('<span>').addClass('exclusive').append('女性专属').appendTo(p1);break;
									}
								}) 
								$('<p>').addClass('describe').append(v.descript).appendTo(li2);
							    var div2 = $('<div>').addClass('game_start gamestart').attr('name',v.id).appendTo(div);
							    $('<a>').addClass('start').append('开始').appendTo(div2); 
							})
							page++; 
							$("#newgames").attr('name',page);
							databool = true;
						}else{
							$("#newgames").append('<div class="nodata" id="_nodata2" align="center">'+infog+'</div>');
						}
						$(".tmodel").remove();
					}
				});
			}
	    }

        //type 1:已开新服数据 2:新服预告
        function getnewservice(type){
        	databool = false;
        	if(type==1){//已开新服
		    	var idstr = 'hadopen';
		    	var nodata = '_nodata3';
    	    }else{//新服预告
    	    	var idstr = 'herald';
    	    	var nodata = '_nodata4';
	    	}
	    	if(!$('#'+nodata).length && !$('#'+idstr+'>.tmodel').length){
	    	var page = $("#"+idstr).attr('name');//页数
	    		$("#"+idstr).append('<p class="tmodel">正在加载...</p>');
				$.ajax({
					url:'/newgame/getnewopen.html',
					data:{'page':page,
						  'type':type},
					dataType:'json',
					type:'post',
					success:function(data){
						var infog = data.info;
						if(data.errorcode==0){ 
							$.each(infog,function(k,v){
								var div = $('<div>').addClass('game_list_box').appendTo($('#'+idstr));
								var ul = $('<ul>').addClass('game_ul').attr('onclick',"window.location.href='/index/detail/"+v.gid+".html'").appendTo(div);
								var li1 = $('<li>').addClass('game_img').appendTo(ul);
								$('<img>').attr('src',backurl+"/game/"+v.head_img).appendTo(li1);
								var li2 = $('<li>').addClass('game_describe').appendTo(ul);
								var p = $('<p>').appendTo(li2); 
								$('<span>').addClass('game_name').append(v.name).appendTo(p);
								var p2 = $('<p>').addClass('describe').append(v.service_code).appendTo(li2);
								var span = $('<span>').addClass('opentime');
								var span2 = $('<span>').addClass('alltime').append(v.open_time).appendTo(span);
							        span.appendTo(p2);
							    var li3 = $('<li>').addClass('game_start gamestart').attr('name',v.gid).appendTo(ul);
							    $('<a>').addClass('start').append('开始').appendTo(li3);
							})
							page++; 
							$("#"+idstr).attr('name',page);
							databool = true;
						}else{
							$("#"+idstr).append('<div class="nodata" id="'+nodata+'" align="center">'+infog+'</div>');
						}
						$(".tmodel").remove();
					}
				});
			}
        } 
	    //获取资讯
	    function getconsult(){
    	  $('.point').remove();
	    	databool = false;
	    	if(!$('#_nodata1').length && !$('#information>.tmodel').length){
	    		var page = $("#information").attr('name');//资讯的页数
	    		$("#information").append('<p class="tmodel">正在加载...</p>');
				$.ajax({
					url:'/consult/getconsult.html',
					data:{'page':page,
         				  'consult_boolean':consult_boolean},
					dataType:'json',
					type:'post',
					success:function(data){
						var infoc = data.info;
						if(data.errorcode==0){
								$.each(infoc,function(kc,vc){
									var _type = '';
									if(vc.type==1){
										_type +='<span style="color: #f50;vertical-align: middle;">[置顶]</span>&nbsp;';
									}
									$("#information>.tmodel").before('<div class="game_list_box cons" name="'+vc.id+'"><ul class="news_ul"><li class="game_img"><a class="ad">'+vc.label+'</a></li><li class="game_describe">'+_type+'<p class="game_p">'+vc.title+'</p></li><li class="game_start"><span class="time">'+vc.createtime+'</span></li></ul></div>');
								})
							page++; 
							$("#information").attr('name',page);
							databool = true;
						}else{
							$("#information").append('<div class="nodata" id="_nodata1" align="center">'+infoc+'</div>');
						}
						$(".tmodel").remove();
					}
				});
			}
	    }

	    //获取其他游戏
	    function getgameinfo(page){
	    	databool = false;
	    	$("#hotgame").append('<p class="tmodel">正在加载...</p>');
			$.ajax({
				url:'/index/getpro.html',
				data:{'page':page},
				dataType:'json',
				type:'post',
				success:function(data){
					var info = data.info;
					if(data.errorcode==0){
							$.each(info,function(k,v){
								var div = $('<div>').addClass('game_list_box').appendTo($('#hotgame'));     
                                var ul = $('<ul>').addClass('game_ul ulwidth').attr('onclick',"window.location.href='/index/detail/"+v.id+".html'").appendTo(div); 
                                var li1 = $('<li>').addClass('game_img liwidth1').appendTo(ul);
                                $('<img>').attr('src',backurl+"/game/"+v.head_img).appendTo(li1);
                                var li2 = $('<li>').addClass('game_describe liwidth2').appendTo(ul);
							  var p1 = $('<p>').addClass('p_gamename').appendTo(li2);
								$('<span>').addClass('game_name').append(v.name).appendTo(p1);
								$.each(v.label,function(v){		
									switch(v){
							  	case 0:	$('<span>').addClass('newgame').append('新游').appendTo(p1);break;
									case 1:	$('<span>').addClass('hot').append('热门').appendTo(p1);break;	
									case 2:	$('<span>').addClass('package').append('礼包').appendTo(p1);break;	
									case 3:	$('<span>').addClass('sole').append('独家').appendTo(p1);break;
									case 4:	$('<span>').addClass('thsfirst').append('首发').appendTo(p1);break;
									case 5:	$('<span>').addClass('exclusive').append('女性专属').appendTo(p1);break;
								 }
								}) 
								$('<p>').addClass('describe').append(v.descript).appendTo(li2);
							    var div2 = $('<div>').addClass('game_start gamestart').attr('name',v.id).appendTo(div);
							    $('<a>').addClass('start').append('开始').appendTo(div2); 
							})
						pagenum++;
						databool = true;
					}else{
						$("#hotgame").append('<div class="nodata" align="center">数据加载完成</div>');
						setInterval(function(){
							$('#hotgame>.nodata').remove();
						}, 3000);
					}
					$(".tmodel").remove();
				}
			});
		}
		//跳转资讯详情页
		$("body").on('click','.cons',function(){
			var id = $(this).attr('name');
			window.location.href='/consult/detail/'+id+'.html';
		});

		$('.carousel-btn').click(function (){
			var id = $(this).attr('id');
			if(id){
				 var idarr = id.split(',');
				 window.location.href = idarr[0];
				 _source = '轮播图第'+idarr[1]+'张';//来源
				 postmessage();
				}
			
	    });
		
	    //菜单切换
	    $('#bottom_menu a').click(function(){
	    	$(this).addClass('on').siblings().removeClass('on');
	    })
	    
	    //开始
	    $('.gamecontent').on('click','.gamestart',function(){
		    var _this = $(this);
		    var gid = _this.attr('name');
		    if(window.navigator.onLine==false){
					alert('网络异常,请确保网络畅通');
			}
		    window.location.href='/start/index/'+gid+'.html';
		    var id = _this.attr('id');
		    if(id){
			    idarr = id.split(',');    
			    if(idarr[1]==1){
			    	_source = '最近在玩第'+idarr[0]+'个';//来源
				}else if(idarr[1]==2){
					_source = '热门游戏第'+idarr[0]+'个';//来源
				}else if(idarr[1]==3){
					_source = '非热门游戏第'+idarr[0]+'个';//来源
			    }  
			    postmessage();
			}
		})
		
		//更多
		function more(url){
			window.location.href = url;
			_source = '更多';
	    	 postmessage();
		}
		//关注公众号弹框
		$payattenction = $('.payattenction');
		$wxmodal = $('#wxmodal');
	    $payattenction.click(function(){
	    	$wxmodal.show();
	    })
	    //关闭公众号弹框
	    $('#closecodemodal').click(function(){
	    	$wxmodal.hide();
	    })
		 //点击搜索按钮
		 	$searchbox = $('#searchbox');
		    $searchbox.click(function(){
		    	$('.infobox').hide();//个人信息
		    	$('#indexallconbox').hide();//首页内容
		    	$('#searchcon').show();//显示搜索内容
		    	$('.searchinputbox').show();//搜索框
		    	$searchbox.hide();//搜索按钮
		    	$payattenction.hide();//隐藏关注按钮
		    	$('.downbox').hide();//app下载
		    })
	    //关闭搜索页面
		   $('#cancelimg').click(function(){
		    	$('.infobox').show();//个人信息
		    	$('#indexallconbox').show();//首页内容
		    	$('#searchcon').hide();//显示搜索内容
		    	$('.searchinputbox').hide();//搜索框
		    	$searchbox.show();//搜索按钮
		    	$payattenction.show();//显示关注按钮
		    	$('.downbox').show();//app下载
		    })
	    /* 搜索功能*/
			   jQuery.expr[':'].Contains = function(a,i,m){
			       return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
			   };
			 
			   function filterList(header, list) { 
			     var form = $("<form>").attr({"class":"filterform","action":"#"}),
			     input = $(".winput");
			    //$(form).append(input).appendTo(header);
			     $(input).change( function () {
			         var filter = $(this).val();
			         $(list).show();
			         if(filter) {
				 	      $matches = $(list).find('a:Contains(' + filter + ')').parent();
				 	      $('li', list).not($matches).slideUp();
				      
				 	      $matches.slideDown();
				 	      if ($('li', list).is($matches)) {//判断搜索的内容是否是匹配的元素
				 	      	$(".no-result_Mt").hide();
				 	      	$(".atendBox").hide();
				 	      }
				 	      else{
					      	$(".no-result_Mt").show();
					      	$(".atendBox").show();
				 	      }
			         } else {
			            $(list).find("li").slideDown();
			            $(".no-result_Mt").hide();
			         }
			         return false;
			    })
			     .keyup( function () {
			         $(this).change();
			     });
			   } 
			   $(function () {
			     filterList($("#formdr"), $("#listdr"));
			   });
</script>
