if(_equipment!='other'){
	$(".mt_head").hide();
	$("div#game-iframe-div").css('top','0');
	$(".paycodebox>p").eq(1).html("注：长按保存二维码 》在微信扫一扫打开")
}
var _source = '';
function postmessage() {           
	// var ga = document.createElement('script');   
	// ga.type = 'text/javascript';   
	// ga.charset='gbk';  
	// ga.async = true;//ga.async = true 异步调用外部js文件，即不阻塞浏览器的解析  
	// ga.src = 'http://wx.16you.com/media/js/ana.js';    
	// var s = document.getElementsByTagName('script')[0];    //取得第一个tag名为script的元素  
	// s.parentNode.insertBefore(ga, s);             //在s前添加元素ga  
};
//广告图片
	$(function(){
  window.addEventListener('load',function(){
		setTimeout(function(){
			$('.adbg').hide();
		},3000);
	});
})
//选卡切换
var pagenum = 1;
var index = 0;  //选项卡下标
var databool = true;
var databool1 = true;
$('.menu_list li').click(function(){
    index = $(this).index();
	$(this).addClass('on').siblings().removeClass('on');
	$('.game_popup_con div.game_popup_main').eq(index).show().siblings().hide();
	(index==1) && giftinfo();//礼包
	if(databool1 && index==2){
		getgameinfo(1)
	};//游戏
})
$('.game_menu_box').click(function(){
	_source = '开始游戏页-16游隐藏框';//来源  
	postmessage();
	$('#startgamebigbox').show();
	$('.game_menu_box').addClass('onhit');
})
$('.game_popup_back').click(function(){
	$('#startgamebigbox').hide();
	setTimeout(function(){
		$('.game_menu_box').removeClass('onhit');
	},1000);
})

//领取礼包弹框
$('body').on('click','.receive',function(){
	var obj = $(this);
	var name = obj.attr('name').split('%$#');	
	$.ajax({
		url:'/gift/gift.html',
		type:'post',
		data:{'number':name['0']},
		dataType:'json',
		success:function(data){
			var info = data.info;
			if(data.errorcode==0){
				$('.servebox h5').html(info.gift_name);
				$('.gifttxt').html(info.content);
				$('.active_num>a').html(info.CDKEY);
				$('.receive_btn>a').attr('href','/start/index/'+info.gid+'.html');
				$('#giftmodal').show(); 
				obj.html('查看').css('background','#fed134');
			}else{
				alert(info);
			}
		}
	})
})

//关闭领取礼包弹框
$('.closeimg,.receive_btn').click(function(){
	$('#giftmodal').hide();
})

$('.game_popup_refresh').click(function(){
	 location.reload(); 
})

//返回上一个历史页面
var threegame_s = true;
	$(function(){
	pushHistory();
	window.addEventListener('load', function() {     
        setTimeout(function() {       
           window.addEventListener('popstate', function(e) {    
           		if(threegame_s == true){
               		$.ajax({
						type:'post',
						data:{'gid':gid},
						url:'/start/threegame.html',
						dataType:'json',
						success:function(data){
							if(data.errorcode == 0){
								var str = '';
								$.each(data.info,function(k,v){
									var head_img = (v.head_img)?v.head_img:'notset.png';
									str += '<p class="gamestart"  onclick="httpget(\''+(k+1)+'\',\''+v.id+'\')"><em><img src="'+backurl+'/media/images/game/'+v.head_img+'"></em><span>'+v.name+'</span></p>';
								})
               					$('#getup').before(str);
							}
							_source = '开始游戏页-返回上一个页面';//来源  
							postmessage();
						}
					});
           			threegame_s = false;
           		}
        	   	$('.rank_bg').css('display', 'block'); 
				$('.knowBtn').click(function() {
					if(document.referrer==0){
						window.location.href='/game/list.html';
					}else{
						window.history.back();
					}
				}); 
				$('.popup_close').click(function() {
		            $('.rank_bg').css('display', 'none');
		            pushHistory();
		        });     
           });     
        }, 0);   
     })			

	function pushHistory() {
			var state = {
			    title: "title",
			    url: "#"
			};
		window.history.pushState(state, "title", "#");
	}
});

 //开始
$('#div_game').on('click','.gamestart',function(){
    var _this = $(this);
    var gid = _this.attr('name');
    if(window.navigator.onLine==false){
			alert('网络异常,请确保网络畅通');
	}
    window.location.href='/start/index/'+gid+'.html';
    _source = '隐藏框开始';
    postmessage();
})
	
function httpget(knum,gid){
	_source = '开始游戏页-返回框第'+knum+'个游戏';//来源  
	postmessage();
	window.location.href='/start/index/'+gid+'.html';
}

//获取热门游戏
function getgameinfo(page){
	databool1 = false;
	$("#hotgame").append('<p class="tmodel" style="text-align: center;color: #666;font-size: 0.2rem; padding: 0.1rem 0;">正在加载...</p>');
	$.ajax({
		url:'/start/gethotgame.html',
		data:{'page':page},
		dataType:'json',
		type:'post',
		success:function(data){
			var info = data.info;
			if(data.errorcode==0){
				$.each(info,function(k,v){
					var div = $('<div>').addClass('game_list_box').appendTo($('.hotgamelist')); 
                    var ul = $('<ul>').addClass('game_ul').appendTo(div); 
                    var li1 = $('<li>').addClass('game_img ').appendTo(ul);
                    var head_img = (v.head_img)?v.head_img:'notset.png';
                    $('<img>').attr('src',backurl+"/media/images/game/"+v.head_img).appendTo(li1);
                    var li2 = $('<li>').addClass('game_describe').appendTo(ul);
					var p1 = $('<p>').appendTo(li2);
					$('<span>').addClass('game_name').append(v.name).appendTo(p1);
					$('<p>').addClass('describe').append(v.descript).appendTo(li2);
				    var li3 = $('<li>').addClass('game_start gamestart').attr('name',v.id).appendTo(ul);
				    $('<a>').addClass('start').append('开始').appendTo(li3); 
				})
				pagenum++;
				databool1 = true;
			}else{
				$("#hotgame").append('<div class="nodata" align="center">数据加载完成</div>');
			}
			$(".tmodel").remove();
		}
	});
}


//获取礼包
function giftinfo(){
	databool = false;
	if(($('.describeno').css('display')=='none') && !$('.allgifts>.tmodel').length){
		var page1 = $(".allgifts").attr('name');//礼包的页数
		$(".allgifts").append('<p class="tmodel">正在加载...</p>');
		$.ajax({
			url:'/start/getgift.html',
			data:{'page':page1,'gid':gid},
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
						$(".allgifts>.tmodel").before('<div class="game_list_box"><ul class="game_ul"><li class="game_img"><img src="'+backurl+'/media/images/game/'+vg.game_image+'"/></li><li class="game_describe"><p><span class="game_name">'+vg.game_name+':<i>'+vg.gift_name+'</i></span></p><p class="describe">'+vg.content+'</p><div class="package_num_img_box"><div class="package_num_img"><span style="width: '+vg.num1+'%;"></span></div><div class="package_num_tip">剩余 '+vg.num+' 个</div></div></li><li class="game_start"><a class="receive" name="'+vg.number+'%$#'+vg.gift_name+'%$#'+vg.game_name+'%$#'+vg.gid+'" href="javascript:void(0)">领取</a></li></ul></div>');
					})
					page1++; 
					$(".allgifts").attr('name',page1);
					databool = true;
				}else{
					if(page1==1){
						$(".describeno").css('display','block');
					}
				}
				$(".tmodel").remove();
			}
		});
	}
}

$('.game_popup_con').scroll(function(){
	if(checkScrollSlide()&&databool){	//判断页面滚动条往下拖 
		(index==1) && giftinfo();//礼包
		if(databool1 && index==2){
    		getgameinfo(pagenum)
    	};//游戏
	}
});
function checkScrollSlide(){
	var lastBox = $('.game_list_box').last();
	var lastBoxDis = lastBox.offset().top+Math.floor(lastBox.outerHeight(true)/2);	//即当拖到最后一张的一半时加载
	var scrollTop = $(window).scrollTop();	//滚动条滚动高度
	var documentH = $(window).height();	//页面可视区高度
	return (lastBoxDis<scrollTop+documentH)?true:false;
}
$(".mt_goback").click(function(){
	window.history.go(-1);
})
$(".close_pay").click(function(){//关闭二维码支付
	$('#paycode').hide();
	clearInterval(setpay);
	$(".paycodebox>img").attr('src','');
})


/*---填写资料---*/
	 $('.databtn').click(function(){
		 $('.infomodal').show();
	});
	//关闭
	$('.closeinfomd').click(function(){
      $('.infomodal').hide();
      $('.infoname').val('');
	  $('.infotel').val('');
	})

/**
  *隐藏支付弹框
  */
 $('.closezfmd').click(function(){
     $('.rechangemodal').hide();
 })
	
	
/**
 * 支付
 */
$(".zhifu").on("click",function(){
	$('.loadbox').show();
	 var id = $(this).attr('id');
	 var formurl = '';//盛付通支付链接
	 var url = '/pay/sftpay.html';  //请求参数链接
	 var PayChannel ='';
	 var username = '';
	 var phone = '';
	 if(id=='6'){//微信扫码支付
		  pay(s_gid,s_data);
		  return false;
     }else if(id=='2'){//盛付通微信支付
     	  formurl = 'https://cardpay.shengpay.com/mobile-acquire-channel/cashier.htm';
    	  PayChannel = 'hw';
     }else if(id=='3'){//盛付通支付宝支付
     	  formurl = 'https://cardpay.shengpay.com/mobile-acquire-channel/cashier.htm';
    	  PayChannel = 'ha';
     }else if(id=='5'){//盛付通H5快捷支付
 		  $('.loadbox').show();
		  formurl = 'https://api.shengpay.com/html5-gateway/express.htm?page=mobile';
   	      url = '/pay/sfthpay.html';
		  $('.infomodal').hide();
  		  $('.infoname').val('');
    	  $('.infotel').val('');
     }
	 $('#forminput').attr('action',formurl);
	 $.ajax({
			type:'post',
			dataType:'json',  
			data:{
				gid:s_gid,
				out_trade_no: s_data.out_trade_no, //'厂商订单编号',
				  product_id: s_data.product_id, //'商品id',
				   total_fee: s_data.total_fee,//'支付总金额	以分为单位 必须大于0',
				        body: s_data.body, //订单或商品的名称',
				      detail: s_data.detail, //订单或商品的详情',
				      attach: s_data.attach, //	后台通知时原样返回
				        sign: s_data.sign, //'请求参数签名'
				  PayChannel: PayChannel,  //
				      ptype : id,        //支付类型
				     payurl : payurl,   //前端跳转页面
				}, 
			url:url,
			success:function(data){
				if(data.errorcode==0){
					var msg = data.msg;
					$.each(msg,function(k,v){
					    $('.hiddeninput').after("<input type='hidden' name='"+k+"' value='"+v+"'>");
						});
					$('#forminput').submit();
				}else{
                    alert("网络异常，稍后再试");
			    }
			}
		}) 
  });