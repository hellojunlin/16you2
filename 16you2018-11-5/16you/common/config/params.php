<?php
return [
		'backend1' => 'http://countsystem.16you.com',
		'backend' => 'http://admingame.16you.com',
		'frontend' => 'http://wx.16you.com',
		'app'=>'http://app.16you.com',
		'backends' => 'https://admingame.16you.com',
		'frontends' => 'https://wx.16you.com',
		'apps'=>'https://app.16you.com',
		'cdn' => 'http://cdn.zqqgl.com',
		'cdn16you' => 'http://cdn16you.zqqgl.com',
		'cdns' => 'https://cdn.zqqgl.com',
		'cdn16yous' => 'https://cdn16you.zqqgl.com',
		'pagenum'=>7,
	    'limitnum'=>10,
		'wxinfo'=>[ 'appid'=>'wx063d4062ae521385',
					'secret'=>'940196cfd20bc04d497f8cfdcf0f75fe',
					'token'=>'HV57P60HhO2z5oV90BoHjP95o20O9Uao',
					'curl_timeout'=>'30',		//curl超时设置
					'key'=>'yerenwangluokejiyouxiangongsi16y',	//支付密匙
				    'mch_id'=>'1441609702',//商户号
				    'cert_path'=>'/home/www/16you/common/pay/wxcert/apiclient_cert.pem',
				    'key_path'=>'/home/www/16you/common/pay/wxcert/apiclient_key.pem',
				    'notify_url'=>'http://admingame.16you.com/notify/wxnotify.html',
				  ],
		'replyVideo'=>[
			'16you'=>
				[	'name'=>'16游',
					'appid'=>'wx063d4062ae521385',
					'token'=>'HV57P60HhO2z5oV90BoHjP95o20O9Uao',
					'secret'=>'940196cfd20bc04d497f8cfdcf0f75fe',
					'curl_timeout'=>'30'		//curl超时设置
				],
		],
		'sendTmp'=>[//模板消息id
	        'TfTGUxIsCWmo1pXxPBH4QFCsa-JRSR2zy8guC8_CCD8'=>'员工会议通知',
	        'sdiwE8bl9DZZ3LQgCJsAq0nVEEr9OfALcmhB6L4zw0s'=>'员工活动通知 ',
        ],
        'state'=>'lianshang',	//设置网页获取用户信息的state值
        'gametype'=>['挂机装置','角色扮演','经营策略','创意休闲'],
        'sgametype'=>['动作','体育','益智','射击','冒险','策略','休闲'],
        'label'=>['新游','热门','礼包','独家','首发','女性专属'],
		'integral_type' =>['2'=>'商品兑换','3'=>'用户签到','4'=>'每日首充','5'=>'充值','6'=>'实名认证','7'=>'完善信息'],
		'integral'=>['20000','10000','5000','5000','5000','5000','5000'],
		'integralrule'=>['1'=>0.02,'2'=>0.02,'3'=>0.02,'4'=>0.05,'5'=>0.05,'6'=>0.05,'7'=>0.08,'8'=>0.08,'9'=>0.08,'10'=>0.12,'11'=>0.12,'12'=>0.12],   //积分规则
		'rule'=>['积分规则','游戏金榜规则'],
		'getintegral'=>['shop'=>'300','rank'=>'150'],
		'vip'=>['5','100','500','1000','5000','10000','20000','50000','100000','200000','500000','1000000'],
		'redpackwinfo'=>[
			'appid'=>'wx1874a10fb8e2bf85',
			'secret'=>'ece73aac0ca1908b7d68642f961d0960',
			'token'=>'HV57P60HhO2z5oV90BoHjP95o20O9Uao',
			'curl_timeout'=>'30',		//curl超时设置
			'key'=>'16you16you16you16you16you16you16',	//支付密匙
			'mch_id'=>'1309855101',//商户号
			'cert_path'=>'/home/www/16you/common/redpackpay/cert/apiclient_cert.pem',
			'key_path'=>'/home/www/16you/common/redpackpay/cert/apiclient_key.pem',
		],
		'sftpay'=>[//盛付通支付参数
		'MsgSender'=>'11134716', //商户号
		'NotifyUrl'=>'/notify/sfthnotify.html',   //后台通知地址
		'HnotifyUrl'=>'/notify/sfthnotify.html', //h5快捷支付
		'refundnotifyUrl'=>'/order/sfthnotify.html', //微信支付宝退款地址
		'key'=>'16youjiushizheme6',
		'private_key_path'=>'/home/www/16you/common/sftpay/private_key_shengpay.pem',
		'public_key_path'=>'/home/www/16you/common/sftpay/public_key_shengpay.pem',
		],
		'sendTmp'=>[//模板消息id
		'Xh9z-YEgvGauhwWxgZrdKAA9ET-ow6PZdEv39bdcKgk'=>'下单成功通知 ',
		'4-zekSNoywhQa537wZuSjPcZ4n3QBNjsA6ATPVdc4YY'=>'退款通知 ',
		],
		'unpay'=>[//优赋支付参数
		'MsgSender'=>'300957', //商户号
		'key'=>'475CC020A2DC98E1BD061DDFDF162FDC',           //密匙
		'unpayhnotifyUrl'=>'/notify/unpayhnotify.html', //h5支付后台通知地址
		'djqunpayhnotifyUrl'=>'/notify/djqunpayhnotify.html', //商城代金券h5支付后台通知地址
		],
		'signintegral'=>['1'=>10,'2'=>15,'3'=>20,'4'=>25,'5'=>30,'6'=>35,'7'=>40],  //签到积分
		'vouchernum'=>['1'=>5,'2'=>15,'3'=>25,'4'=>35,'5'=>40,'6'=>50], //代金券币值  1:5元   2:15元  3:25元  4:35元   5:40元  6:50元
];
