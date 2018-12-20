 var menuindex = 0; //菜单坐标
 var pagearr = [1,1,1];  //游戏页数  :0=>热门游戏  1=》最新游戏   2=》全部游戏
 var isbooleanarr = [1,1,1];  //1：表示可读取数据  0：不可读取

$(document).ready(function(){
   var loaddata = function(index,page){
	   $('.warmtips').text('加载中...');
      $.ajax({
			url:'/index/getmoregame.html',
			type:'post',
			data:{
				  'type':index,
				  'page':page,
			     },
			dataType:'json',
			success:function(data){
				if(data.errorcode==0){
					  var gamearr = data.gamearr;
					  $.each(gamearr,function(k,v){
					   	   var li = $('<li>').appendTo($('.more-game'));
					   	   var div1 = $('<div>').addClass('new-c-msg gamedetail').attr('name',v.id).appendTo(li);
					   	   var div2 = $('<div>').addClass('nc-msg-titimg').appendTo(div1);
					   	   $('<img>').attr('src',cdn+'/game/'+v.head_img).appendTo(div2);
					   	   var div3 = $('<div>').addClass('nc-msg-text').appendTo(div1);
					   	   var div4 = $('<div>').addClass('m-text-title').appendTo(div3);
					   	   $('<div>').append(v.name).addClass('title-game-name').appendTo(div4);
					   	   var div5 = $('<div>').addClass('tags').appendTo(div4);
					       $.each(v.label,function(key,va){
					   		    switch(va){
					   		      case '0' : $('<span>').addClass('newgame').append('新游').appendTo(div5); break;
					   		      case '1' : $('<span>').addClass('hot').append('热门').appendTo(div5); break;
					   		      case '2' : $('<span>').addClass('package').append('礼包').appendTo(div5); break;
					   		      case '3' : $('<span>').addClass('sole').append('独家').appendTo(div5); break;
					   		      case '4' : $('<span>').addClass('thsfirst').append('首发').appendTo(div5); break;
					   		      case '5' : $('<span>').addClass('exclusive').append('女性专属').appendTo(div5); break;
					   		    }
					       });
					   	   $('<div>').append(v.descript).addClass('m-text-describe').appendTo(div3);
					   	   $('<div>').append('马上玩').addClass('new-c-btn gamestart').attr('name',v.id).appendTo(li);
					   	});
					  pagearr[index] = page+1;
					  isbooleanarr[index] = 1;  //请求成功，重新设置可读取
					  $('.warmtips').text('');
				}else if(data.errorcode=='1002'){//数据加载完
					$('.warmtips').text('我可是有底线的');
				}else{
					 $('.warmtips').text('网络异常，请稍后再试');
				}
			}
		})
    
   }
   
   loaddata(type,1);
   
   
   //菜单切换
   $('.tabul li').on('click',function(){
      menuindex = $(this).index();
      $('.more-game').empty();
      $(this).addClass('tab-active').siblings().removeClass('tab-active');
      if(menuindex =='0'){ //最热游戏
          loaddata(0,1);
      }else if(menuindex =='1'){//最新游戏
          loaddata(1,1);  
      }else if(menuindex=='2'){//全部
    	  loaddata(2,1);  
      }
   })

   //上拉加载
   $(document).scroll(function(){
      if($(window).scrollTop() > 500){//返回顶部
         $('.toTop').fadeIn(1500);
      }else{
         $('.toTop').fadeOut(1500);
      }
      var id = $('.tab-active').attr('id');
      if($(document).scrollTop() < $(document).height() - $(window).height() - 150){
         return false;
      }
      if(isbooleanarr[id]==1){//可读取状态
    	  isbooleanarr[id] = 0;  //设置不可读取
    	  loaddata(id,pagearr[id]);
      }
     
   });

   //返回顶部
   $('.toTop').click(function(){
      $('body,html').animate({
         scrollTop:0
      },1000)
   })
})

 //开始
 $("body").on('click','.gamestart',function(){
	    var _this = $(this);
	    var gid = _this.attr('name');
	    if(window.navigator.onLine==false){
				alert('网络异常,请确保网络畅通');
		}
	    window.location.href='/start/index/'+gid+'.html';
	    var id = _this.attr('id');
	    if(id){
		    idarr = id.split(',');    
		    if(idarr[1]==1){
		    	_source = '最近在玩第'+idarr[0]+'个';//来源
			}else if(idarr[1]==2){
				_source = '热门游戏第'+idarr[0]+'个';//来源
			}else if(idarr[1]==3){
				_source = '非热门游戏第'+idarr[0]+'个';//来源
		    }  
		    postmessage();
		}
	})
	
  //跳转详情页
 $("body").on('click','.gamedetail',function(){
	    var _this = $(this);
	    var gid = _this.attr('name');
	    if(window.navigator.onLine==false){
				alert('网络异常,请确保网络畅通');
		}
	    window.location.href='/index/detail/'+gid+'.html';
 })