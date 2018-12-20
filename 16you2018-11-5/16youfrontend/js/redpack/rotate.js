$(function(){
	var rotateTimeOut = function(){
		$('#rotate').rotate({
			angle:0,
			animateTo:2160,
			duration:8000,
			callback:function(){
				alert('网络超时，请检查您的网络设置！');
			}
		});
	};
	var bRotate = false;
	var rotateFn = function(awards, angles, txt,url){
		$('#rotate').stopRotate();
		$('#rotate').rotate({
			angle:0,
			animateTo:angles+1800,
			duration:8000,
			callback:function(){
				if(url!=0){
					bRotate = !bRotate;
					if(awards==0){
						$('.roimgtip').attr("src",url+"/images/redpack/sp.png");
						$('.rtip').hide();
						$('.rotatermol').show();
						$('.rotatetip').animate({
							opacity:1	
						},2000);
					}else if(awards==3){
						$('.roimgtip').attr("src",url+"/images/redpack/achieve.png");
						$('.rtip').show();
						$('.tipmoney').text('2');
						$('.rotatermol').show();
						$('.rotatetip').animate({
							opacity:1	
						},2000);
					}else if(awards==7){
						$('.roimgtip').attr("src",url+"/images/redpack/achieve.png");
						$('.rtip').show();
						$('.tipmoney').text('5');
						$('.rotatermol').show();
						$('.rotatetip').animate({
							opacity:1	
						},2000);
					}
				}
			}
		})
	};

	$('.pointer').click(function(){ 
		if(!bRotate){
			var timetxt = $('#timecount').text();
		    if(timetxt >= 1){
				bRotate = !bRotate;
				rotateFn(0,0,0,0);
		    	$('.pointertxt').removeClass('retop');
		    	$.ajax({
		    		url:'/luck/lucky.html',
		    		dataType:'json',
		    		type:'post',
		    		success:function(data){
		    			if(data.errorcode == 0){
			    			var info = data.info;
			    			rotateFn(info.num,info.angle,info.content,info.url);
			    			timetxt--;
					        $('#timecount').text(timetxt);
					        if(timetxt==0){
					        	$('.pointertxt').addClass('retop').text('抽奖机会已用完，充值后继续抽奖哦');
					        }
		    			}else if(data.errorcode == 1001){
		    				alert(data.info);
		    			}else{
		    				alert(data.info);
		    				//window.location.reload(); 
		    			}
		    		}
		    	})
		    }
		}
	});
});
//关闭转盘提示
$('.rotatermol').click(function(){
	$('.rotatermol').hide();
	$('.rotatetip').css('opacity','0.1');
});