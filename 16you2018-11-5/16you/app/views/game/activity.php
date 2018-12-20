<!DOCTYPE html>
<!-- saved from url=(0042)http://pc.16you.com/consult/detail/10.html -->
<html lang="en" style="font-size: 58.5938px;"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="format-detection" content="telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="/media/temp/css/common.css">
	<link rel="stylesheet" type="text/css" href="/media/temp/css/swiper.min.css">
	<link rel="stylesheet" type="text/css" href="/media/temp/css/notice.css">
	<script type="text/javascript" src="/media/temp/js/rem.js"></script>
	<script type="text/javascript" src="/media/temp/js/jquery.min.js"></script>
	<script type="text/javascript" src="/media/temp/js/swiper.min.js"></script>
	<title>活动</title>
</head>
<body>
	<div id="noticebox" style="margin: 0.25rem 0.12rem 1.2rem;">
		<div class="notice-title">
			<div class="n_title" id="afficheTitle">《口袋妖怪联盟》</div>
			<div class="n_time">
				<span class="n_img">
					<em><img src="/media/temp/images/meanself.png"></em>
				</span>
			</div>
		</div> 
		<div class="informationboard">
			<p ><p id="affiche">《口袋妖怪联盟》“清明时节”活动于2017年4月2日~2017年4月5日开启，更多精彩活动等你参与！活动1：神宠降临-盖欧卡-洛奇亚-帕路奇犽（半价）每充值1元即可获得两次抽奖机会，抽奖消耗12钻石！3只超强神宠等你带走，赶紧行动吧！（每天首次免费）（限时特惠活动）活动2：撩妹大作战活动期间累计好感度达到800即可领取奖励！更可在好感商城兑换奖励。(100钻石对应1好感度)活动3：特惠礼盒活动期间，累计充值达到28元，即可1钻购买超值礼包（每期活动仅限1次活动4：每日首充活动期间，每天首次1000元以下的单笔充值将会额外获得2倍的钻石奖励（奖励通过邮件发送，一/三元礼包不计入奖励）活动5：打造神钻活动期间，每充值1元,即可获得1积分。打磨宝钻有概率提升宝钻星级，最高可打磨至7星宝钻。（宝钻原石免费10颗）活动6：宝藏巡游活动期间，消耗钻石掷骰子，每次前进100%可获得奖励，完成巡游轮数目标还可获得额外奖励！</p>	
		</div>
	</div>
	<script>
		//近期热门滑动
	   var tabsSwiper = new Swiper('#tabs_ulcontent',{
	      speed:500,
	      slidesPerView : 5,
	    })
	   //返回-
		$("#mt_goback").click(function(){
			history.go(-1);
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
		   $('.mt_head').css('display','none');
			 //通知app该页面 
		   window.onload=function(){
			   var data = {};
			   data.page = 'consult';
			   data.title =  "资讯";
		       window.parent.postMessage(data, '*');
		   }	
		}

		window.onload = function(){
			//数据传递
		    var affList=JSON.parse(localStorage.getItem('skey'));
		    var affindex=JSON.parse(localStorage.getItem('affindex'));
		    //标题
		    document.getElementById("afficheTitle").innerHTML=affList[affindex-10].title;
		    // 文本
		    document.getElementById("affiche").innerHTML=affList[affindex-10].contents;
		}
	</script>

</body></html>