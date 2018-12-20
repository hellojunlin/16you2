<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/css/common.css">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/css/mall.css">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/css/swiper.min.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/js/rem.js"></script>
	<title>商城</title>
</head>
<script type="text/javascript">
var data = {};
data.page = 'integralshop';
data.title = '16游';
data.state = 'start';
window.parent.postMessage(data, '*');
</script>
<body>
	<div class="integralbox" style="display:none;">
		<span class="integralspan"><img class="integralimg" src="<?php echo yii::$app->params['cdn16you']; ?>/images/integral.png"></span>
		<span class="myintegral">我的积分：<span class="integral"><?php echo isset($user->integral)?$user->integral:0;?></span></span>
		<a class="task" href="/personal/index.html">做任务</a>
	</div>
	<div class="tabmenu">
		<ul class="tabul">
			<li class="tab_on">代金券</li>
			<li style="display:none;">积分兑换</li>
			<li style="display:none;">记录</li>
		</ul>
	</div> 
	<div class="notice" style="display:none;">
		<div class="n_img"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/notice.png"></div>
		<div class="detailbox swiper-container" id="noticediv">
		  <div class="swiper-wrapper">
		    <?php if($noticearr):foreach ($noticearr as $n):?>
			    <div class="swiper-slide">
			    	<span class="username"><?php echo $n['username']?></span>
			    	<span class="purchase">兑换了<?php echo $n['product_name']?></span>
			    </div>
		    <?php endforeach;endif;?>
	<!-- 	    <div class="swiper-slide">
		    	<span class="username">丽丽丽丽22</span>
		    	<span class="purchase">购买<span class="money">80</span>元代金券</span>
		    </div>
		    <div class="swiper-slide">
				<span class="username">丽丽丽丽333</span>
		    	<span class="purchase">购买<span class="money">100</span>元代金券</span>
		    </div> -->
		  </div>
		</div>
	</div>
	<div class="tabbigcon">
		<!--代金券-->
		<div class="goodscon tabconlist">
			<div class="goodsbox"></div>
			<div class="rulesbox">
				<h3>代金券使用规则</h3>
				<p>1、每周一0点之后，进入商店将获得当周的专属折扣；</p>
				<p>2、每张代金券，每周只能购买一次，成功购买后，将会折算为等价游戏币并打入玩家账户；</p>
				<p>3、代金券购买之日起永久有效；</p>
				<p>4、代金券不可交易，买定离手不可退款；</p>
				<p>5、任何问题请咨询16游客服，最终解释权归16游平台所有。</p>
			</div>
		</div>
		<!--积分兑换-->
		<div class="exchangecon tabconlist">
			<div class="exchangebox"></div>
			<div class="loadtips exchangetips">加载中...</div> 
			<div class="rulesbox">
				<h3>积分商城兑换规则</h3>
				<p>1、成功兑换实物商品后，请添加客服QQ：1901567493，联系领取；</p>
				<p>2、商品一经兑换，一律不退还积分；</p>
				<p>3、积分可通过充值以及每日任务获得；</p>
				<p>4、一切商品以实物为准；</p>
				<p>5、通过非法途径与手段进行的正常兑换，平台有权不提供服务；</p>
				<p>6、任何问题请咨询16游客服，最终解释权归16游平台所有。</p>
			</div>
		</div>
		<!--记录-->
		<div class="recordcon tabconlist">
			<div class="record_tab">
				<ul class="record_tab_ul">
					<li class="record_tab_li record_tab_on">积分获取</li>
					<li class="record_tab_li">购买兑换</li>
				</ul>
			</div> 
			<div class="record_tabcon">
				<div class="gainbox"></div>
				<!--加载提示-->
				<div class="loadtips recordtips">加载中...</div> 
				<!--没有记录-->
				<div class="norecord" style="display:none;"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/default01.png"></div>
			</div>
		</div>
	</div>
	<!--折数弹框-->
	<?php if($isshowdiscount):?>
	<div class="discountmodal">
		<div class="discountbox">
			<h4>本周折扣抽取</h4>
			<p class="discount-tips">你本周的折扣是：<span class="discounttxt"><?php echo $discount;?></span>折</p>
			<a class="surebtn">确定</a>
		</div>
	</div>
	<?php endif;?>
	<!--兑换-->
	<div class="exchangemodal">
		<div class="exchangemodalbox">
		   <!--  <img class="colseexchange" src="/media/images/close_gray.png"> -->
			<img class="goodsshow" src="<?php echo yii::$app->params['cdn16you']; ?>/images/default01.png">
			<div class="goodsname"><h3>【粒上皇-财运福禄礼盒】</h3>兑换成功</div>
			<div class="active_num"><a class="exchangecode"></a></div>
			<h2 class="ex-tips"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/up_icon.png">长按复制上方兑换码<br/>并联系客服领取</h2>
			<div class="exchange_btn">
				<a>确定</a>
			</div>
		</div>
	</div>
	<!-- 购买模态框 -->
	<div class="rechangemodal">
	   <div class="rechangebox">
	     <h4>支付</h4>
	     <img class="closezfmd" src="<?php echo yii::$app->params['cdn16you']; ?>/images/close_gray.png">
	     <p class="pall pallfs">16游</p>
	     <p class="paybox">¥<span class="paynum"></span></p>
	     <p class="pall">请选择支付方式</p>
	     <div class="selectpay">
	          <div class="weixinzhifu zhifu" id='9'>
	             <ul class="zhifufs">
	             	<li class="zflione"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/wxzf.png"></li>
	             	<li class="zflitwo">
	             		<p>微信支付</p>
	             		<p class="zfp1">亿万用户的选择，更快更安全</p>
	             	</li>
	             	<li class="zflithree"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/arrowright.png"></li>
	             </ul>	 
	          </div> 	          
	          
	          <div class="weixinzhifu  zhifu" style="display: none;">
	             <ul class="zhifufs">
	             	<li class="zflione"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/kjzf.png"></li>
	             	<li class="zflitwo">
	             		<p>快捷支付</p>
	             		<p class="zfp1">亿万用户的选择，更快更安全</p>
	             	</li>
	             	<li class="zflithree"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/arrowright.png"></li>
	             </ul>	 
	        </div>
	     </div>
	   </div>
	</div>
	<div class="loadbox">
	   <div class="loadimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/loading.gif"></div>
	</div>
	<!--购买提示-->
	<div class="warmtipsbox"><span class="warmtipsboxcontent"></span></div>
	<!--记录提示-->
    <div class="recordmoadl">
    	<div class="record-con">
    	    <img class="colserecord" src="<?php echo yii::$app->params['cdn16you']; ?>/images/close_gray.png">
    		<h3 class="r-title">粒上皇-财运坚果礼盒A款红包</h3>
    		<div class="codebox"></div>
    		<p class="record-box-tips"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/up_icon.png">长按复制上方兑换码<br/>并联系客服领取</p>
    	</div> 
    </div>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/js/swiper.min.js"></script>
	<script>
	var num = <?php echo ($discount)?$discount:10;?>;
	var backend = "<?php echo yii::$app->params['backend'];?>";
	</script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/modal.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/mall.js?v=1.0"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/datamall.js"></script>
	<script type="text/javascript">
	var payurl = "<?php echo isset($payurl)?$payurl:'';?>";
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
  		//判断是否是移动端
  		if(browser.versions.mobile||browser.versions.android||browser.versions.ios){
  		}
		if(_equipment=='other'){//9：优赋微信H5支付
			//	$(".zhifu_ptype").attr('id','10');  //优赋微信扫码支付
		}
         var mySwiper = new Swiper('#noticediv', {
			autoplay: 2000,//可选选项，自动滑动
			direction:'vertical',
			loop:true,
		});
         //购买
         var p_data = {};
         $('body').on('click','.buybtn',function(){
           openModal();
           p_data.vtype = $(this).attr('name');
           var thisul = $(this).parent().parent();//获取父级ul
           var thispay =  thisul.find('.discount-money').text();//获取需要支付的金额
           $('.paynum').text(thispay);
           $('.rechangemodal').show();
           p_data.price = thispay;
         });
     
		
		//隐藏支付弹框
		$('.closezfmd').on("click",function(){
			closeModal();
		 	$('.rechangemodal').hide();
		});


		 //选择支付方式
		$(".zhifu").on("click",function(){
			$('.loadbox').show();
			 var id = $(this).attr('id');
		})
		/*
		 * 调起支付方式页面
		 */
		var s_data = '';
		var s_gid = '';
		function selectpay(gid,data){
			$('.paynum').text(data.total_fee/100);
			s_gid = gid;
			s_data = data;
			$('.rechangemodal').show();
		}

		/**
		 * 支付
		 */
		$(".zhifu").on("click",function(){
			$('.loadbox').show();
			 var id = $(this).attr('id');
			 var formurl = '';//盛付通支付链接
			 var url = '/shoppay/allsftpay.html';  //请求参数链接
			 var PayChannel ='';
			 var username = '';
			 var phone = '';
			 if(id=='5'){//盛付通H5快捷支付
		 		  $('.loadbox').show();
				  $('.infomodal').hide();
		  		  $('.infoname').val('');
		    	  $('.infotel').val('');
		     }
			 $.ajax({
					type:'post',
					dataType:'json',  
					data:{
						     'vtype': p_data.vtype, //代金券类型  
						     'price': p_data.price,
						    'ptype' : id,        //支付类型
						    'payurl': payurl,     //前端跳转页面
						  },
					url:url,
					success:function(data){
						if(data.errorcode==0){
							var msg = data.msg;
							$.each(msg,function(k,v){
							    $('.hiddeninput').after("<input type='hidden' name='"+k+"' value='"+v+"'>");
							});
							 if(id==9  || id==12 ){ //优赋微信H5支付  优赋支付宝支付
								window.location.href=data.requesturl;
							    return false;
							}else if(id==10){//优赋微信扫码支付
								parent.location.href=data.requesturl;
								return false;
							}else{
								$('#forminput').attr('action',data.requesturl);
								$('#forminput').submit();
						    }
						}else{
							    $('.rechangemodal').css('display','none');
							    $('.loadbox').css('display','none');
                                alert(data.msg);
					    }
					}
				}) 
		  });

	</script>
</body>
</html>