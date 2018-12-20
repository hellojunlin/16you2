<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo isset(yii::$app->session['plateform']->pname)?((yii::$app->session['plateform']->punid!='16you')?yii::$app->session['plateform']->pname.'游戏':'16游-游戏中心'):'16游-游戏中心';?></title>
    <link rel="stylesheet" href="<?php echo yii::$app->params['cdn16you']; ?>/css/newcss/init.css">
    <link rel="stylesheet" href="<?php echo yii::$app->params['cdn16you']; ?>/css/newcss/adapt.css">
    <link rel="stylesheet" href="<?php echo yii::$app->params['cdn16you']; ?>/css/newcss/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/css/newcss/info.css">
</head>
<body>
	 <div class="info"></div>
     <p class="warmtips"></p>
     <script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/js/jquery.min.js"></script>
     <script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/newjs/info.js"></script>
</body>
</body>
