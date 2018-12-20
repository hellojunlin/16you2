	<title><?php echo isset(yii::$app->session['plateform']->pname)?((yii::$app->session['plateform']->punid!='16you')?yii::$app->session['plateform']->pname.'游戏':'16游-游戏中心'):'16游-游戏中心';?></title>
    <link rel="stylesheet" href="<?php echo yii::$app->params['cdn16you']; ?>/css/newcss/init.css">
    <link rel="stylesheet" href="<?php echo yii::$app->params['cdn16you']; ?>/css/newcss/adapt.css">
    <link rel="stylesheet" href="<?php echo yii::$app->params['cdn16you']; ?>/css/newcss/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/css/newcss/more.css">
<body>
     <div class="top-title">
     	<ul class="tabul">
     		<li class="tabli <?php echo ($type==0)?'tab-active':'';?>"  id="0">最热</li>
     		<li class="tabli <?php echo ($type==1)?'tab-active':'';?>" id="1">最新</li>
     		<li class="tabli <?php echo ($type==2)?'tab-active':'';?>" id="2">全部</li>
     	</ul>
     </div>
	 <div class="new-content marginbottom">
        <ul class="more-game">
	    </ul>
	    <p class="warmtips"></p>
     </div>
     <div class="toTop"><img src="<?php echo yii::$app->params['cdn16you']; ?>/images/newimg/top.png"></div>
</body>
<script>
 var backend = "<?php echo yii::$app->params['backend']?>";
 var cdn = "<?php echo yii::$app->params['cdn']?>";
 var type = "<?php echo $type?>";
</script>
<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/js/newjs/more.js"></script>
