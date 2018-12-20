<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/pc/css/present.css">
</head>
<body>
	<div class="packPage">
	    <div class="searchgift">
	    	<form action="/gift/togift.html" method="post">
		    	<div class="sgiftbox">
		    		<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/searchicon.png">
		    		<input class="s_gamename" type="text" placeholder="请输入要搜索的游戏名称" value="<?php echo $game_name; ?>" name="gamename">
		    	</div>
		    	<button class="giftsearchbtn">搜索</button>
	    	</form>
	    </div>
		<div class="gameImgSt gameImgSt1">
			<?php if($gift): ?>
			<?php foreach ($gift as $k => $v):?>
			<div class="gameList">
				<div class="gameListItem">
					<div class="startGame">
						<div class="vipPresent">
							<div class="list_item_1">
								<div class="pagemain">
									<img src="<?php echo yii::$app->params['cdn']; ?>/game/<?php echo $v['game_image']; ?>" alt="">
								</div>
								<div class="personal_Present">
									<h1><?php echo $v['game_name'];?></h1>
									<h4><?php echo $v['descript']; ?></h4>
								</div>
								<div class="present_start">
									<a href="#" class="gamestart" name="<?php echo $v['gid']; ?>">开始</a>
								</div>
							</div>
							<div class="list_item_2">
								<div class="item_smart_2">
									<h1><?php echo $v['gift_name']; ?></h1>
									<h2><?php echo $v['content']; ?></h2>
									<div class="progress_box">
	                                    <?php if(strlen($v['num'])>2){
	                                    	$v['num1'] = $v['num']/(pow(10,strlen($v['num'])-2));
	                                    }else{$v['num1']=$v['num'];} ?>
	                                    <div class="skill progress dib">
	                                        <div class="progress_jindu expand skin" style="width:<?php echo $v['num1']/2;?>%"></div>
	                                    </div>
	                                    <i class="deep_red new">剩余 <?php echo $v['num']; ?> 个</i><!-- /<i class="all">2000</i> -->
                                	</div>
                                	<div class="<?php echo ($v['gifttype']==4)?'entryground':'receive-now';?>">
                                		<a href="#" name="<?php echo $v['number'].'%$#'.$v['gift_name'].'%$#'.$v['game_name'].'%$#'.$v['gid']; ?>">领取</a>
                                	</div>
								</div>
								<div class="term-of-validity">有效期：<span class="start-time"><?php echo ($v['validtime'])?date('Y-m-d',$v['validtime']):'永久';?></span></div>
							</div>
							<?php if($v['packet']>1): ?>
							<div class="list_item_3" name="<?php echo $v['gid'].'!@%'.$v['number']; ?>">
								<p>查看更多礼包(<?php echo $v['packet']-1; ?>)</p>
							</div> 
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
			<?php else: ?>
				<!--搜索不到结果-->
			    <div class="no-find">
			    	<p>—— 暂未搜索到任何结果 ——</p>
			    	<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/fail.png">
			    </div>
			<?php endif; ?>
		</div>
	</div>
	<div class="popup_bg gameImgSt" id="pack-info-dialog" >
		<div class="receive_success" style=" margin-top: -202px;">	<div class="get1_img">
			<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/libao1.png">
		</div>
		<em class="close">
			<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/close_gray.png">
		</em>
		<div class="novice_bag">
			<h5></h5>
			<div class="novice_bag_detail"></div>
			<div class="novice_bag_rule">兑换码每个服可用一次，每个礼包只能领一次</div>
		</div>
		<div class="novice_bag_num">
			<div class="active_num">
				<p></p>
			</div>
			<h2>
				<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/up_icon.png">长按上方复制激活码
			</h2>
			<div class="levitate_tip in-other-view" data-game="lmybl" data-desktop="1" data-packcode="PuheC7t">
				<a>悬浮提示</a>
			</div>
		</div>
		<div class="receive_btn start-game" data-game="lmybl" data-desktop="1">
			<a class="gamestart" href="#">开始游戏</a>
		</div></div>
	</div>
				<!--弹框-客服-->
	<div id="servemodal" style="display:none;">
		<div class="servebox">
			<h1 class="servetitle">联系客服</h1>
			<img class="rqcloseimg" src="<?php echo yii::$app->params['cdn16you']; ?>/images/close_gray.png">
			<h5>官方QQ群独家礼包各种福利</h5>
			<div class="active_num">
				<a href="#">598452957</a>
			</div>
			<h2><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/up_icon.png">点击添加官方QQ群</h2>
			<div class="ewm_box">
				<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/qq_b_default.png">
				<p>官方QQ群<br>独家礼包各种福利</p>
			</div>
			<div class="receive_btn">
				<a>我知道了</a>
			</div>
		</div>
	</div>
	<!-- 一键收藏 -->
<div id="collectionList" style="display:none">
	<div class="collectionbox">
	   <p>请使用<span>Ctrl+D</span>进行一键收藏</p>
	   <a id="konwpc">我知道了</a>
	</div>
</div>
</body>
<script src="<?php echo yii::$app->params['cdn16you']; ?>/js/jquery.min.js"></script>
<script>
	var backurl = "<?php echo yii::$app->params['cdn']; ?>";
	//查看更多礼包
	$(".gameImgSt").on('click',".list_item_3",function(){
		var _obj = $(this);
		_obj.after('<p class="tmodel" style="text-align: center;color: #666;font-size: 0.2rem; padding: 0.1rem 0;">正在加载...</p>');
		$.ajax({
			url:'/gift/moregift.html',
			type:'post',
			data:{'data':_obj.attr('name')},
			dataType:'json',
			success:function(data){
				var info = data.info;
				if(data.errorcode==0){
					$.each(info,function(kg,vg){
						if(vg.num.length>2){
							vg.num1 = vg.num/(Math.pow(10,vg.num.length-2));
						}else{
							vg.num1 = vg.num;
						}
						var classname = (vg.gifttype==4)?'entryground':'receive-now';
						_obj.before('<div class="list_item_2"><div class="item_smart_2"><h1>'+vg.gift_name+'</h1><h2>'+vg.content+'</h2><div class="progress_box"><div class="skill progress dib"><div class="progress_jindu expand skin" style="width:'+vg.num1/2+'%"></div></div><i class="deep_red new">&nbsp;剩余 '+vg.num+' 个</i></div><div class="'+classname+'"><a href="#" name="'+vg.number+'%$#'+vg.gift_name+'%$#'+vg.game_name+'%$#'+vg.gid+'">领取</a></div></div></div>');
					});
				}else{
					alert(info);
				}
			}
		});
		$(this).siblings(".clearFixed").show();
		$(this).hide();
		$(".tmodel").remove();
	});

	//领取或查看礼包
	$(".gameImgSt").on('click',".receive-now>a",function(){
		var obj = $(this);
		if(obj.html()=='领取'){
			var name = obj.attr('name').split('%$#');
			$.ajax({
				url:'/gift/gift.html',
				type:'post',
				data:{'number':name['0']},
				dataType:'json',
				success:function(data){
					var info = data.info;
					if(data.errorcode==0){
						$('.novice_bag h5').html(name['1']);
						$('.novice_bag_detail').html(info.content);
						$('.active_num>p').html(info.CDKEY);
						$(".receive_btn>a").attr('name',name['3']);
						obj.html('查看');
						$(".popup_bg").show();
					}else if(data.errorcode==1003){
						if(_equipment!='other'){//手机端
							window.location.href="/personal/index.html";
						}else{
							$('#loginPcBtn', window.parent.document).show();
							$('#yoyo', window.parent.document).attr('src','/personal/index.html');
						}
					}
				}
			});
			return false;
		}else{
			$(".popup_bg").show();
		}
		
	});
	$(".popup_bg .close").click(function(){
		$(".popup_bg").css("display","none");
	})
	var databool = true;
	var page = 1;
	$(window).scroll(function(){
		if(checkScrollSlide()&&databool){	//判断页面滚动条往下拖 
			databool = false;
	    	if(!$('#_nodata').length && !$('.tmodel1').length){
				$(".gameImgSt1").append('<p class="tmodel1" style="text-align: center;color: #666;font-size: 0.2rem; padding: 0.1rem 0;">正在加载...</p>');
				var sgamename = $(".s_gamename").val();
				$.ajax({//获取礼包
					url:'/gift/togift.html',
					data:{'page':page,'gamename':sgamename},
					dataType:'json',
					type:'post',
					success:function(data){
						var info = data.info;
						if(data.errorcode==0){
							$.each(info,function(kg,vg){
								if(vg.num.length>2){
									vg.num1 = vg.num/(Math.pow(10,vg.num.length-2));
								}else{
									vg.num1 = vg.num;
								}
								if(vg.packet>1){
									vg.packet1 = '<div class="list_item_3" name="'+vg.gid+'!@%'+vg.number+'"><p>查看更多礼包('+(vg.packet-1)+')</p></div>';
								}else{
									vg.packet1 = '';
								}
								var vtime = '永久';
								if(vg.validtime){
									 vtime = UnixToDate(vg.validtime,false,8);
								}
								var classname = (vg.gifttype==4)?'entryground':'receive-now';
								$(".gameImgSt1").append('<div class="gameList"><div class="gameListItem"><div class="startGame"><div class="vipPresent"><div class="list_item_1"><div class="pagemain"><img src="'+backurl+'/game/'+vg.game_image+'" alt=""></div><div class="personal_Present"><h1>'+vg.game_name+'</h1><h4>'+vg.descript+'</h4></div><div class="present_start"><a href="#" class="gamestart" name="'+vg.gid+'">开始</a></div></div><div class="list_item_2"><div class="item_smart_2"><h1>'+vg.gift_name+'</h1><h2>'+vg.content+'</h2><div class="progress_box"><div class="skill progress dib"><div class="progress_jindu expand skin" style="width:'+vg.num1/2+'%"></div></div><i class="deep_red new">剩余 '+vg.num+' 个</i></div><div class="'+classname+'"><a href="#" name="'+vg.number+'%$#'+vg.gift_name+'%$#'+vg.game_name+'%$#'+vg.gid+'">领取</a></div></div><div class="term-of-validity">有效期：<span class="start-time">'+vtime+'</span></div></div>'+vg.packet1+'</div></div></div></div>');
							})
							page++; 
							databool = true;
						}else{
							$(".gameImgSt1").append('<div class="nodata" id="_nodata" align="center">'+info+'</div>');
						}
						$(".tmodel1").remove();
					}
				});
			}
		}
	});
    function checkScrollSlide(){
		var lastBox = $('.gameList').last();
		var lastBoxDis = lastBox.offset().top+Math.floor(lastBox.outerHeight(true)/2);	//即当拖到最后一张的一半时加载
		var scrollTop = $(window).scrollTop();	//滚动条滚动高度
		var documentH = $(window).height();	//页面可视区高度
		return (lastBoxDis<scrollTop+documentH)?true:false;
	}

	 //开始
    $('.gameImgSt').on('click','.gamestart',function(){
	    var _this = $(this);
	    var gid = _this.attr('name');
	    if(window.navigator.onLine==false){
				alert('网络异常,请确保网络畅通');
		}
	    window.location.href='/start/index/'+gid+'.html';
	    // _source = '游戏礼包--开始';//来源
		// postmessage();
	})
	
	
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
    if(browser.versions.android || browser.versions.ios){
    		$('.present_start').hide();
    		$('.receive_btn').hide();
   		 //通知app该页面
 		   window.onload=function(){
 			   var data = {};
 			   data.page = 'gift';
 			   data.title =  "游戏礼包";
 		       window.parent.postMessage(data, '*');
 		   }
    }

  	//入群
	$('.entryground').on('click',function(){
        $('#servemodal').show(); 
	});
	//关闭入群
	$('body').on('click','.rqcloseimg,.receive_btn',function(){
       $('#servemodal').hide(); 
	});
    /**              
     * 时间戳转换日期              
     * @param <int> unixTime    待时间戳(秒)              
     * @param <bool> isFull    返回完整时间(Y-m-d 或者 Y-m-d H:i:s)              
     * @param <int>  timeZone   时区              
     */ 		
      function UnixToDate(unixTime, isFull, timeZone) {
          if (typeof (timeZone) == 'number'){
              unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
          }
          var time = new Date(unixTime * 1000);
          var ymdhis = "";
          ymdhis += time.getUTCFullYear() + "-";
          ymdhis += (time.getUTCMonth()+1) + "-";
          ymdhis += time.getUTCDate();
          if (isFull === true){
              ymdhis += " " + time.getUTCHours() + ":";
              ymdhis += time.getUTCMinutes() + ":";
              ymdhis += time.getUTCSeconds();
          }
          return ymdhis;
      }
</script>
</html>