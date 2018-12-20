<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/app/css/common.css">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/app/css/swiper.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/app/css/vip.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/app/js/rem.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/app/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/app/js/swiper.min.js"></script>
</head>
<script>
var data = {};
data.page = 'vip';
data.title =  "vip";
data.state='end';
window.parent.postMessage(data, '*');
</script>
<body>
	<div class="vipbox" style="margin-top:-0.3rem">
		<div class="vipuser">
			<div class="vip_line">
				<div id="vip-level-container" class="swiper-container gallery-thumbs swiper-head">
					<div class="swiper-wrapper allviplist">
						<?php for($i=0;$i<=12;$i++): ?>
						<?php if($i==$num): ?>
						<div class="swiper-slide swiper-slide-active">
							<div id="top-vip-level-avatar" class="vip_head">
								<span><img src="<?php  echo  $headurl?$headurl:yii::$app->params["cdn16you"].'/images/noimg.jpg';?>"></span>
								<em>VIP<?php echo $i; ?></em>
							</div>
						</div>
						<?php else: ?>
						<div class="swiper-slide">
							<div class="vip_grade">VIP<?php echo $i; ?></div>
						</div>
						<?php endif; ?>
						<?php endfor; ?>
					</div>
				</div>
			</div>
			<div class="vip-experience"><?php echo ($price)?round($price):0; ?>/<?php echo $num1; ?></div>
			<div class="arrowbox"><span class="arrow"></span></div>
		</div>
		<!--vip特权内容-->
		<div id="vip-text" class="swiper-container swiper_boxlist vip_privilege_box">
			<div id="viptxt-box" class="swiper-wrapper">
			   <!--vip0-->
				<div class="swiper-slide <?php if($num==0){echo 'swiper-slide-active';} ?>">
					<div class="no-privilege">
						<img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/neit.png">
						<p>该等级没有会员特权</p>
					</div>
				</div>
				<!--vip1-->
				<div class="swiper-slide <?php if($num==1){echo 'swiper-slide-active';} ?>">
					<div class="vip-privilege">
						<!-- <div class="notext">
											    	<img src="<?php //echo yii::$app->params['cdn16you']; ?>/images/action.png">
											    	<p>敬请期待</p>
											    </div> -->
					<h1>会员特权</h1>
					<div class="vip-privilege-list">
						<ul>
							<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/jifen.png"></li>
							<li class="detail-info">
								<h2>加赠2%积分</h2>
								<p>在16游内充值任意金额，即可获得充值金额的2%的等值积分（例如充值100元，即可获得1002积分）积分可在商城内兑换商品
								</p>
							</li>
						</ul>
					</div>
				 </div>
				</div>
				<!--vip2-->
				<div class="swiper-slide <?php if($num==2){echo 'swiper-slide-active';} ?>">
					 <div class="vip-privilege">
					<h1>会员特权</h1>
					<div class="vip-privilege-list">
						<ul>
							<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/jifen.png"></li>
							<li class="detail-info">
								<h2>加赠2%积分</h2>
								<p>在16游内充值任意金额，即可获得充值金额的2%的等值积分（例如充值100元，即可获得1002积分）积分可在商城内兑换商品
								</p>
							</li>
						</ul>
					</div>
				 </div>
				</div>
				<!--vip3-->
				<div class="swiper-slide <?php if($num==3){echo 'swiper-slide-active';} ?>">
					<div class="vip-privilege">
					<h1>会员特权</h1>
					<div class="vip-privilege-list">
						<ul>
							<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/jifen.png"></li>
							<li class="detail-info">
								<h2>加赠2%积分</h2>
								<p>在16游内充值任意金额，即可获得充值金额的2%的等值积分（例如充值100元，即可获得1002积分）积分可在商城内兑换商品
								</p>
							</li>
						</ul>
					</div>
				  </div>
				</div>
				<!--vip4-->
				<div class="swiper-slide <?php if($num==4){echo 'swiper-slide-active';} ?>">
					<div class="vip-privilege">
					    <h1>会员特权</h1>
					    <div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/jifen.png"></li>
								<li class="detail-info">
									<h2>加赠5%积分</h2>
									<p>在16游内充值任意金额，即可获得充值金额的5%的等值积分（例如充值100元，即可获得1005积分）积分可在商城内兑换商品
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list exclusiveserver">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/kefu.png"></li>
								<li class="detail-info">
									<h2>专属客服</h2>
									<p>达到VIP4及以上，即可享有VIP专属客服，随时为您一对一服务
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/xiufu.png"></li>
								<li class="detail-info">
									<h2>账号修复优先权</h2>
									<p>达到VIP4及以上，即可享有账号修复优先处理特权</p>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!--vip5-->
				<div class="swiper-slide <?php if($num==5){echo 'swiper-slide-active';} ?>">
					<div class="vip-privilege">
					    <h1>会员特权</h1>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/jifen.png"></li>
								<li class="detail-info">
									<h2>加赠5%积分</h2>
									<p>在16游内充值任意金额，即可获得充值金额的5%的等值积分（例如充值100元，即可获得1005积分）积分可在商城内兑换商品
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list exclusiveserver">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/kefu.png"></li>
								<li class="detail-info">
									<h2>专属客服</h2>
									<p>达到VIP4及以上，即可享有VIP专属客服，随时为您一对一服务
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/xiufu.png"></li>
								<li class="detail-info">
									<h2>账号修复优先权</h2>
									<p>达到VIP4及以上，即可享有账号修复优先处理特权
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/fanli_03.png"></li>
								<li class="detail-info">
									<h2>生日礼物</h2>
									<p>达到vip5，赠送88个游戏币作为生日礼物（1游戏币=1元，需提前联系您的专属客服登记领取，逾期不补发）</p>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!--vip6-->
				<div class="swiper-slide <?php if($num==6){echo 'swiper-slide-active';} ?>">
					<div class="vip-privilege">
					    <h1>会员特权</h1>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/jifen.png"></li>
								<li class="detail-info">
									<h2>加赠5%积分</h2>
									<p>在16游内充值任意金额，即可获得充值金额的5%的等值积分（例如充值100元，即可获得1005积分）积分可在商城内兑换商品
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list exclusiveserver">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/kefu.png"></li>
								<li class="detail-info">
									<h2>专属客服</h2>
									<p>达到VIP4及以上，即可享有VIP专属客服，随时为您一对一服务
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/xiufu.png"></li>
								<li class="detail-info">
									<h2>账号修复优先权</h2>
									<p>达到VIP4及以上，即可享有账号修复优先处理特权</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/fanli_03.png"></li>
								<li class="detail-info">
									<h2>生日礼物</h2>
									<p>达到vip6，赠送108个游戏币作为生日礼物（1游戏币=1元，需提前联系您的专属客服登记领取，逾期不补发）
									</p>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!--vip7-->
				<div class="swiper-slide <?php if($num==7){echo 'swiper-slide-active';} ?>">
					<div class="vip-privilege">
					    <h1>会员特权</h1>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/jifen.png"></li>
								<li class="detail-info">
									<h2>加赠8%积分</h2>
									<p>在16游内充值任意金额，即可获得充值金额的5%的等值积分（例如充值100元，即可获得1008积分）积分可在商城内兑换商品
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list exclusiveserver">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/kefu.png"></li>
								<li class="detail-info">
									<h2>专属客服</h2>
									<p>达到VIP4及以上，即可享有VIP专属客服，随时为您一对一服务</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/xiufu.png"></li>
								<li class="detail-info">
									<h2>账号修复优先权</h2>
									<p>达到VIP4及以上，即可享有账号修复优先处理特权</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/fanli_03.png"></li>
								<li class="detail-info">
									<h2>生日礼物</h2>
									<p>达到vip7，赠送128个游戏币作为生日礼物（1游戏币=1元，需提前联系您的专属客服登记领取，逾期不补发）
									</p>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!--vip8-->
				<div class="swiper-slide <?php if($num==8){echo 'swiper-slide-active';} ?>">
					<div class="vip-privilege">
					    <h1>会员特权</h1>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/jifen.png"></li>
								<li class="detail-info">
									<h2>加赠8%积分</h2>
									<p>在16游内充值任意金额，即可获得充值金额的5%的等值积分（例如充值100元，即可获得1008积分）积分可在商城内兑换商品
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list exclusiveserver">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/kefu.png"></li>
								<li class="detail-info">
									<h2>专属客服</h2>
									<p>达到VIP4及以上，即可享有VIP专属客服，随时为您一对一服务</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/xiufu.png"></li>
								<li class="detail-info">
									<h2>账号修复优先权</h2>
									<p>达到VIP4及以上，即可享有账号修复优先处理特权</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/fanli_03.png"></li>
								<li class="detail-info">
									<h2>生日礼物</h2>
									<p>达到vip8，赠送138个游戏币作为生日礼物（1游戏币=1元，需提前联系您的专属客服登记领取，逾期不补发）
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/fanli_04.png"></li>
								<li class="detail-info">
									<h2>节日礼物</h2>
									<p>达到vip8，重大节日期间（春节，端午节，中秋节，国庆节），赠送16游戏币作为节日礼物（需提前联系您的专属客服登记领取，逾期不补发）
									</p>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!--vip9-->
				<div class="swiper-slide <?php if($num==9){echo 'swiper-slide-active';} ?>">
					<div class="vip-privilege">
					    <h1>会员特权</h1>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/jifen.png"></li>
								<li class="detail-info">
									<h2>加赠8%积分</h2>
									<p>在16游内充值任意金额，即可获得充值金额的5%的等值积分（例如充值100元，即可获得1008积分）积分可在商城内兑换商品
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list exclusiveserver">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/kefu.png"></li>
								<li class="detail-info">
									<h2>专属客服</h2>
									<p>达到VIP4及以上，即可享有VIP专属客服，随时为您一对一服务
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/xiufu.png"></li>
								<li class="detail-info">
									<h2>账号修复优先权</h2>
									<p>达到VIP4及以上，即可享有账号修复优先处理特权</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/fanli_03.png"></li>
								<li class="detail-info">
									<h2>生日礼物</h2>
									<p>达到vip9，赠送168个游戏币作为生日礼物（1游戏币=1元，需提前联系您的专属客服登记领取，逾期不补发）
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/fanli_04.png"></li>
								<li class="detail-info">
									<h2>节日礼物</h2>
									<p>达到vip9，重大节日期间（春节，端午节，中秋节，国庆节），赠送18游戏币作为节日礼物（需提前联系您的专属客服登记领取，逾期不补发）
									</p>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!--vip10-->
				<div class="swiper-slide <?php if($num==10){echo 'swiper-slide-active';} ?>">
					<div class="vip-privilege">
					    <h1>会员特权</h1>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/jifen.png"></li>
								<li class="detail-info">
									<h2>加赠12%积分</h2>
									<p>在16游内充值任意金额，即可获得充值金额的5%的等值积分（例如充值100元，即可获得1012积分）积分可在商城内兑换商品
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list exclusiveserver">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/kefu.png"></li>
								<li class="detail-info">
									<h2>专属客服</h2>
									<p>达到VIP4及以上，即可享有VIP专属客服，随时为您一对一服务
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/xiufu.png"></li>
								<li class="detail-info">
									<h2>账号修复优先权</h2>
									<p>达到VIP4及以上，即可享有账号修复优先处理特权</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/fanli_03.png"></li>
								<li class="detail-info">
									<h2>生日礼物</h2>
									<p>达到vip10，赠送188个游戏币作为生日礼物（1游戏币=1元，需提前联系您的专属客服登记领取，逾期不补发）
									</p>
								</li>
							</ul>
						</div> 
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/fanli_04.png"></li>
								<li class="detail-info">
									<h2>节日礼物</h2>
									<p>达到vip10，重大节日期间（春节，端午节，中秋节，国庆节），赠送20游戏币作为节日礼物（需提前联系您的专属客服登记领取，逾期不补发）
									</p>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!--vip11-->
				<div class="swiper-slide <?php if($num==10){echo 'swiper-slide-active';} ?>">
					<div class="vip-privilege">
					    <h1>会员特权</h1>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/jifen.png"></li>
								<li class="detail-info">
									<h2>加赠12%积分</h2>
									<p>在16游内充值任意金额，即可获得充值金额的5%的等值积分（例如充值100元，即可获得1012积分）积分可在商城内兑换商品
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list exclusiveserver">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/error_net.png"></li>
								<li class="detail-info">
									<h2>专属客服</h2>
									<p>达到VIP4及以上，即可享有VIP专属客服，随时为您一对一服务
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/xiufu.png"></li>
								<li class="detail-info">
									<h2>账号修复优先权</h2>
									<p>达到VIP4及以上，即可享有账号修复优先处理特权</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/fanli_03.png"></li>
								<li class="detail-info">
									<h2>生日礼物</h2>
									<p>达到vip11，赠送208个游戏币作为生日礼物（1游戏币=1元，需提前联系您的专属客服登记领取，逾期不补发）
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/fanli_04.png"></li>
								<li class="detail-info">
									<h2>节日礼物</h2>
									<p>达到vip11，重大节日期间（春节，端午节，中秋节，国庆节），赠送22游戏币作为节日礼物（需提前联系您的专属客服登记领取，逾期不补发）
									</p>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!--vip12-->
				<div class="swiper-slide <?php if($num==10){echo 'swiper-slide-active';} ?>">
					<div class="vip-privilege">
					    <h1>会员特权</h1>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/jifen.png"></li>
								<li class="detail-info">
									<h2>加赠12%积分</h2>
									<p>在16游内充值任意金额，即可获得充值金额的5%的等值积分（例如充值100元，即可获得1012积分）积分可在商城内兑换商品
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list exclusiveserver">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/error_net.png"></li>
								<li class="detail-info">
									<h2>专属客服</h2>
									<p>达到VIP4及以上，即可享有VIP专属客服，随时为您一对一服务
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/xiufu.png"></li>
								<li class="detail-info">
									<h2>账号修复优先权</h2>
									<p>达到VIP4及以上，即可享有账号修复优先处理特权</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/fanli_03.png"></li>
								<li class="detail-info">
									<h2>生日礼物</h2>
									<p>达到vip12，赠送228个游戏币作为生日礼物（1游戏币=1元，需提前联系您的专属客服登记领取，逾期不补发）
									</p>
								</li>
							</ul>
						</div>
						<div class="vip-privilege-list">
							<ul>
								<li class="iconimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/fanli_04.png"></li>
								<li class="detail-info">
									<h2>节日礼物</h2>
									<p>达到vip12，重大节日期间（春节，端午节，中秋节，国庆节），赠送24游戏币作为节日礼物（需提前联系您的专属客服登记领取，逾期不补发）
									</p>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- 专属客服 -->
		<div id="vipservermodal">
			<div class="servermbox">
				<h4>专属客服</h4>
				<p>扫描微信二维码</p>
				<div class="wxkfimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/wxkf.png" /></div>
				<p class="bluetip">游戏问题解答、礼包派送，专属服务</p>
				<p>添加QQ：16游VIP客服-花花</p>
				<a class="qqnumber">3243987127</a>
				<p class="uptp"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/up_icon.png">长按可复制</p>
				<button class="serverkonwbtn">我知道了</button>
			</div>
		</div>
	</div>
	<script>
	var num = "<?php echo $num; ?>";
	 	//滑动vip
		var tabsSwiper = new Swiper('#vip-level-container',{
	      speed:300,
	      slidesPerView : 3,
		  centeredSlides : true,
		  onSlideChangeStart:function(){
		  	//获取当前激活的tab的索引，用来判断是那个tab在显示
		  	 tabsSwipertxt.slideTo(tabsSwiper.activeIndex);
		  	 if(tabsSwiper.activeIndex != num){
		  	 	$('.vip-experience').hide();
		  	 }else{
		  	 	$('.vip-experience').show();
		  	 }
		  }
	    })
	    $('.allviplist .swiper-slide').on('touchstart mousedown',function(e){
		      e.preventDefault();
		      tabsSwipertxt.slideTo($(this).index());
		    })
		    $('.allviplist .swiper-slide').click(function(e){
		      e.preventDefault();
		 })
	   //滑动vip内容
	    var tabsSwipertxt =new Swiper('#vip-text',{
	    	speed:300,
	    	slidesPerView : 1,
	    	onSlideChangeStart:function(){
	    		tabsSwiper.slideTo(tabsSwipertxt.activeIndex);
	    		if(tabsSwiper.activeIndex != num){
			  	 	$('.vip-experience').hide();
			  	 }else{
			  	 	$('.vip-experience').show();
			  	 }
	    	}
	    }) 
	    tabsSwiper.slideTo(num);
	    tabsSwipertxt.slideTo(num);
		//返回-1
	    $(".mt_goback").click(function(){
	    	history.go(-1);
	    })

	    //专属客服
	    var vip = $('#top-vip-level-avatar').children('em').text();
	    var vipnum = vip.substring(3);//从第四个字符开始截取
	    if(vipnum >='4'){
	    	$('.exclusiveserver').click(function(){
				 $('#vipservermodal').show();
			})
	    }
	    //关闭专属客服
	    $('.serverkonwbtn').click(function(){
		   $('#vipservermodal').hide(); 
		})
		
	 //通知app该页面
	   window.onload=function(){
		   var data = {};
		   data.page = 'vip';
		   data.title =  "vip";
		   data.state='end';
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
		    $('body').css('max-width',iosWidth);
		    $('p').css('font-size','0.15rem');
		}	
	</script>
</body>
</html>