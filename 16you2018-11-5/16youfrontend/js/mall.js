$(document).ready(function(){

	//随机折扣
/*	var arr = ['7','9','8'];
	function generateMixed(n) {
	     var res = "";
	     for(var i = 0; i < n ; i ++) {
	         var id = Math.ceil(Math.random()*2);
	         res += arr[id];
	     }
	     return res
	}
	var num = generateMixed(1);
	//$('.discountmodal').show();
	$('.discounttxt').text(num);*/
	//openModal();

    //计算折扣价
   /* var ticketlen = ticketval.length;
    var costarr = [];
    for(var i=0;i<ticketlen;i++){
    	var discountcost = ticketval[i].cost*num/10;//折扣价
    	costarr.push(discountcost);
    }*/
    //alert('test');
    //console.log('测试测试');
	//代金券
	var ticketfun = function(){
        //console.log('xxxxx');
        //alert('xxxxx');
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

    //积分兑换 type=2为异步加载
    var isload = true; //true是可以加载
    var exchangefun = function(page,type){
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
    	
    }
    
   
    
    //记录数据-积分获取
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
    }
    
    
  //记录数据-购买兑换
    var b_isload = true;
    var buyrecord = function(page,brecordtype){
    	if(b_isload){
    		$('.recordtips').show().text('加载中...');
    		$('.recordtips').show();
    		b_isload = false;
	    	$.ajax({
				url:'/integralshop/integralbug.html',
				type:'post',
				dataType:'json',
				data:{'page':page},
				success:function(data){
					$('.recordtips').show().text('');
					b_isload = true;
					if(data.errorcode==0){
                        $('.norecord').hide();
						$('.recordtips').hide();
						var integral_type = data.integral_type;
						$.each(data.msg,function(k1,v1){
							var ul = $('<ul>').addClass('gaintype buybox').appendTo($('.gainbox'));
					    	var li1 = $('<li>').addClass('ex_img').appendTo(ul);
					    	$('<h4>').attr('name',v1.getcode).append(v1.product_name).appendTo(li1);
					    	$('<p>').addClass('gaintime').append(v1.creeatetime).appendTo(li1);
					    	var li2 = $('<li>').addClass('gainintegral').append('-').appendTo(ul);
					    	$('<span>').addClass('gain_num').append(v1.integral).appendTo(li2);
					    	li2.append('积分');
		    	 			/*var div = $('<div>').addClass('pur_ex').appendTo($('.gainbox'));
					    	$('<h4>').append(v1.product_name).appendTo(div);
					    	$('<p>').addClass('gaintime').append(timestampToTime(v1.creeatetime)).appendTo(div);*/
		    	 		})
						brecordtype==2 && buypage ++;   //异步加载  页数加1
					}else if(data.errorcode==1003){//数据已加载完
						$('.recordtips').show().text('没有更多数据');
						r_isload = false;
						page==1 && $('.norecord').show();
					}else{//网络异常
						$('.recordtips').show().text('网络异常，请重新加载！');
					}
				}
	    	})
    	}
    }
    //把折扣后的价格显示出来
/*    var discountfun = function(){
	    $.each($('.goodslist .discount-money'),function(k,v){
	       $(this).text(costarr[k]);
	    })
    };
    discountfun();*/
    
    
    var index = 0;
    var sourcepage = 2;
    var buypage = 2;
    
    ticketfun();//进入页面默认显示代金券数据
    //一级菜单切换
    //$('body').on('click','.tabul li',function(){
    var tabindex ;
      $('.tabul li').click(function(){
    	$(this).addClass('tab_on').siblings().removeClass('tab_on');
    	tabindex = $(this).index();
    	$('.tabbigcon .tabconlist').eq(tabindex).show().siblings().hide();
    	if(tabindex ==0){//代金券
    	//	discountfun();
    		$('.goodsbox').empty();//清空数据
    		ticketfun();
    	}
    	if(tabindex ==1){//积分兑换
    		$('.exchangebox').empty();//清空数据
    		isload=true;
    		exchangepage = 2;
    		exchangefun(1,1);
    	}
    	if(tabindex ==2){//记录 
    		r_isload = true;
    		sourcepage = 2;
    	    buypage = 2;
    		$('.gainbox').empty();
    		index ==0 && recordfun(1,1);
    		index ==1 && buyrecord(1,1);
    	}
    });
    //(二级)记录的积分获取与购买兑换切换
   // $('body').on('click','.record_tab_ul li',function(){
      $('.record_tab_ul li').click(function(){
    	$(this).addClass('record_tab_on').siblings().removeClass('record_tab_on');
    	index = $(this).index();
    	$('.gainbox').empty();
    	if(index==0){//积分获取
    		r_isload = true;
    		sourcepage = 2;
    		recordfun(1,1);
    	}else if(index==1){//购买兑换
    		b_isload = true;
    		buypage = 2;
    		buyrecord(1,1);
    	}
    	
    });
  
   
	//关闭模态框
	$('body').on('click','.colsediscount,.surebtn',function(){
		$('.discountmodal').hide();
		closeModal();
	//	discountfun();
	});

	//兑换
	var need_num,myintegral;
	$('body').on('click','.exbtn',function(){
	   myintegral = $('.integral').text();//获取我的积分    
       var parent = $(this).parent().parent();//获取父级
       var imgsrc = parent.children('.ex_img').children().attr('src');
       var name = parent.children('.ex_info').children('h4').text();
       need_num = parent.children('.ex_info').children('.need_integral').children('.need_num').text();
       var sur_num = parent.children('.ex_info').children('.surplus').children('.sur_num').text(); 
       var pid = $(this).attr('id');
      /* myintegral = $('.integral').text();//获取我的积分    
       var parent = $(this).parent().parent().parent();//获取最大父级
       var index = parent.index();
       var imgsrc = exchangeval[index].src;
       var name = exchangeval[index].title;//兑换列表名
       need_num = exchangeval[index].integral;//兑换积分
       var sur_num = exchangeval[index].surplus;//剩余数量
*/
       if(confirm('确定兑换吗？')){
    	   $.ajax({
				url:'/integralshop/exchange.html',
				type:'post',
				dataType:'json',
				data:{'pid':pid},
				success:function(data){
					$('.recordtips').show().text('');
					b_isload = true;
					if(data.errorcode==0){
					       if(parseInt(myintegral) >= parseInt(need_num)){
					    	   $('.exchangecode').text(data.getcode);
					       	   $('.exchangemodal').show();
						       $('.goodsshow').attr('src',imgsrc);
						       $('.goodsname h3').text(name);
						       openModal();
					       }else{
					    	   showwarmtipsboxmodel(data.msg);
					           $(this).removeClass('exbtn').addClass('hadexchange');
					       }
					       
					}else{//网络异常
						alert(data.msg);
					}
				}
	   		})   
       }
     
	});
	//关闭兑换框
	$('body').on('click','.colseexchange',function(){
		$('.exchangemodal').hide();
		closeModal();
	});
	//确定兑换
	$('body').on('click','.exchange_btn',function(){
		$('.exchangemodal').hide();
		var sur_integral = myintegral - need_num;
		$('.integral').text(sur_integral);
		$.each($('.exchangebox .exchangelist'),function(){
			var $this = $(this);
			var goods_integral = $this.children().children('.ex_info').children('.need_integral').children('.need_num').text();
			if(parseInt(sur_integral)< parseInt(goods_integral)){//积分少于商品所需积分时，按钮变化
				$this.children().children('.ex_button').children().removeClass('exbtn').addClass('hadexchange');
			}
		})
        closeModal();
	});
	
	var exchangepage = 2;
	var sourcepage = 2;
	var buypage = 2;
	//上拉加载
    $(window).scroll(function(){
    	var scrollTop = $(this).scrollTop();//滚动条距离顶部的高度
    	var scrollHeight = $(document).height();//当前页面的总高度
    	var clientHeight = $(this).height();    //当前可视的页面高度
    	if(scrollTop+clientHeight >= scrollHeight){//距离顶部+当前高度 >=文档总高度 即代表滑动到底部 count++;//每次滑动count加1
    		if(tabindex==1){//积分兑换
    			exchangefun(exchangepage,2);
    		}
    		if(tabindex==2){//记录
    		 if(index==0){ //积分获取
    	    	 recordfun(sourcepage,2);
    		 }else{//购买记录
    			buyrecord(buypage,2);
    		 }
    		}
    	}
    }) ;
    
    //查看购买兑换记录
    $('body').on('click','.buybox',function(){
    	var txt = $(this).find('h4').text();
    	var getcode = $(this).find('h4').attr('name');
    	$('.r-title').text(txt);
    	$('.codebox').text(getcode);
    	$('.recordmoadl').fadeIn(300);
    });
    
    //关闭购买兑换弹框
    $('.colserecord').on('click',function(){
        $('.recordmoadl').fadeOut(300);
    });
    
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
})

//alert('test test');

	/**
	 * 提示框显示
	 * @param msg
	 */
	function showwarmtipsboxmodel(msg){
		 $('.warmtipsboxcontent').html(msg);
    	 $('.warmtipsbox').show();
    	 setTimeout(function(){$('.warmtipsbox').hide()},3000);
	}
