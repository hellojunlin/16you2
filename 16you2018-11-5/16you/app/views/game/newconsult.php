<script type="text/javascript">
	   var data = {};
	   data.page = 'homegame';
	   data.title = '16游';
	   data.state = 'start';
       window.parent.postMessage(data, '*');

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
      		    $('body').css('width',iosWidth);
      		}
</script>
    <title><?php echo isset(yii::$app->session['plateform']->pname)?((yii::$app->session['plateform']->punid!='16you')?yii::$app->session['plateform']->pname.'游戏':'16游-游戏中心'):'16游-游戏中心';?></title>
    <link rel="stylesheet" href="<?php echo yii::$app->params['cdn16you']; ?>/css/newcss/init.css">
    <link rel="stylesheet" href="<?php echo yii::$app->params['cdn16you']; ?>/css/newcss/adapt.css">
    <link rel="stylesheet" href="<?php echo yii::$app->params['cdn16you']; ?>/css/newcss/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/css/newcss/info.css">

<body>
	 <div class="info"></div>
     <p class="warmtips"></p>
     <script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/js/jquery.min.js"></script>
     <script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/app/js/newjs/info.js"></script>
</body>
</body>
