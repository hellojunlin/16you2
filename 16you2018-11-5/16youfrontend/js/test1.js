$(document).ready(function(){
	console.log(ticketval);
	//alert(ticketval);
	//代金券
	var ticketfun = function(){
		$.each(ticketval,function(k,v){
			var discountprice = v.cost*num/10;//折扣价
	    	var div1 = $('<div>').addClass('goodslist').appendTo($('.goodsbox'));
	    	var div2 = $('<div>').addClass('goodslistbox').appendTo(div1);
	    	$('<img>').addClass('goodsimg').attr('src','http://cdn16you.zqqgl.com'+v.src).appendTo(div2);
	    	var ul = $('<ul>').addClass('goods-ul').appendTo(div2);
	    	var li1 = $('<li>').addClass('priceli').appendTo(ul);
	    	var p1 = $('<p>').addClass('cost-price').append('原价：').appendTo(li1);
	    	$('<span>').addClass('cost-moeny').append(v.cost).appendTo(p1);
	    	p1.append('¥');
	    	var p2 = $('<p>').addClass('discount').append('折扣价：').appendTo(li1);
	    	$('<span>').addClass('discount-money').append(discountprice).appendTo(p2);
	    	//$('<span>').addClass('discount-money').append(v.discount).appendTo(p2);
	    	p2.append('¥');
	    	var li2 = $('<li>').addClass('buyli').appendTo(ul);
	    	$('<a>').addClass('buybtn').attr('name',k+1).append('购买').appendTo(li2);
	    });
	}
	ticketfun();//进入页面默认显示代金券数据

    /*//积分兑换 type=2为异步加载
    var isload = true; //true是可以加载
    var exchangefun = function(page,type=1){
    	var myintegral = $('.integral').text();//获取我的积分  
    	if(isload){
    		$('.exchangetips').show().text('加载中');
    		$('.exchangetips').show();
    		isload = false;
    		$.ajax({
    			url:'/integralshop/integralgoods.html',
    			type:'post',
    			dataType:'json',
    			data:{'page':page},
    			success:function(data){
    				isload = true;
    				if(data.errorcode==0){
    					$('.exchangetips').show().text('');
    					$('.exchangetips').hide();
    					$.each(data.msg,function(k,v){
    				    	var div1 = $('<div>').addClass('exchangelist').appendTo($('.exchangebox'));
    				    	var ul = $('<ul>').addClass('exchangeul').appendTo(div1);
    				    	var li1 = $('<li>').addClass('ex_img').appendTo(ul);
    				    	$('<img>').attr('src',backend+'http://cdn16you.zqqgl.com/images/product/'+v.image_url).appendTo(li1);
    				        var li2 = $('<li>').addClass('ex_info').appendTo(ul);
    				    	$('<h4>').append(v.product_name).appendTo(li2);
    				    	var p1 = $('<p>').addClass('need_integral').append('积分：').appendTo(li2);
    				    	$('<span>').addClass('need_num').append(v.integral).appendTo(p1);
    				    	var p2 = $('<p>').addClass('surplus').append('剩余：').appendTo(li2);
    				    	$('<span>').addClass('sur_num').append(v.number).appendTo(p2);
    				    	var li3 = $('<li>').addClass('ex_button').appendTo(ul);
    				    	if(parseInt(myintegral) >= parseInt(v.integral)){
    				    		$('<a>').addClass('exbtn').attr('id',v.id).append('兑换').appendTo(li3);
    				    	}else{  
    							$('<a>').addClass('hadexchange').append('兑换').appendTo(li3);
    				    	}
    				    });
    					type==2 && exchangepage ++;
    				}else if(data.errorcode==1003){//数据已加载完
    					$('.exchangetips').show().text('没有更多数据');
    					isload = false;
    				}else{//网络异常
    					$('.exchangetips').show().text('网络异常，请重新加载！');
    				}
    			}
    		})
    	}
    }*/
    /*    //记录数据-积分获取
    var r_isload = true;
    var recordfun = function(page,recordtype){
    	if(r_isload){
    		$('.recordtips').show().text('加载中...');
    		$('.recordtips').show();
    		r_isload = false;
	    	$.ajax({
				url:'/integralshop/integralsource.html',
				type:'post',
				dataType:'json',
				data:{'page':page},
				success:function(data){
					$('.recordtips').show().text('');
					r_isload = true;
					if(data.errorcode==0){
                        $('.norecord').hide();
						$('.recordtips').hide();
						var integral_type = data.integral_type;
						$.each(data.msg,function(k1,v1){
		    	 			var ul = $('<ul>').addClass('gaintype').appendTo($('.gainbox'));
					    	var li1 = $('<li>').addClass('ex_img').appendTo(ul);
					    	$('<h4>').append(integral_type[v1.type]).appendTo(li1);
					    	$('<p>').addClass('gaintime').append(timestampToTime(v1.createtime)).appendTo(li1);
					    	var li2 = $('<li>').addClass('gainintegral').append('+').appendTo(ul);
					    	$('<span>').addClass('gain_num').append(v1.integral).appendTo(li2);
					    	li2.append('积分');
		    	 		})
						recordtype==2 && sourcepage ++;   //异步加载  页数加1
					}else if(data.errorcode==1003){//数据已加载完
						$('.recordtips').show().text('没有更多数据');
						r_isload = false;
					}else{//网络异常
						$('.recordtips').show().text('网络异常，请重新加载！');
					}
				}
	    	})
    	}
    }*/
});//ready

    /**
	时间戳转日期
	*/
	function timestampToTime(timestamp) {
        var date = new Date(timestamp * 1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
        Y = date.getFullYear() + '-';
        M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
        D = date.getDate()+ ' ';
        h = date.getHours() + ':';
        m = date.getMinutes() ;
        return Y+M+D+h+m;
    }
   /**
	 * 提示框显示
	 * @param msg
	 */
	function showwarmtipsboxmodel(msg){
		 $('.warmtipsboxcontent').html(msg);
    	 $('.warmtipsbox').show();
    	 setTimeout(function(){$('.warmtipsbox').hide()},3000);
	}
