var pagenum = 1;      //页数
var isboolean = true;  //true 可加载   false  禁止加载
$(document).ready(function(){
	//获取更多资讯数据
	var loaddata = function(page){
		 $.ajax({
				url:'/game/getmoreconsult.html',
				type:'post',
				data:{
					  'page':page,
				     },
				dataType:'json',
				success:function(data){
					if(data.errorcode==0){
						  var consultarr = data.consultarr;
						  $.each(consultarr,function(k,v){
							    var div = $('<div>').addClass('game_list_box cons').attr('name',v.id).appendTo($('.info'));
								var ul = $('<ul>').addClass('news_ul').appendTo(div);
								var li1 = $('<li>').addClass('game_img').appendTo(ul);
								$('<a>').addClass('ad').append(v.label).appendTo(li1);
								var li2 = $('<li>').addClass('game_describe').appendTo(ul);
								$('<p>').addClass('game_p').append(v.title).appendTo(li2);
								var li3 = $('<li>').addClass('game_start').appendTo(ul);
								$('<span>').addClass('time').append(formatDate(v.createtime)).appendTo(li3);
						   	});
						  pagenum++;
						  isboolean = true;  //请求成功，重新设置可读取
					}else if(data.errorcode=='1002'){//数据加载完
						$('.warmtips').text('没有更多数据');
						return false;
					}else{
						$('.warmtips').text(data.msg);
						return false;
					}
				}
			})
	}
	loaddata(1);
	//上拉加载
	$(document).scroll(function(){
		if($(document).scrollTop() < $(document).height() - $(window).height() - 150 ){
			return false;
		}
		if(isboolean){
			isboolean = false;
			loaddata(pagenum);
		}
		
	})
	
	//跳转资讯详情页
	$("body").on('click','.cons',function(){
			var id = $(this).attr('name');
			window.location.href='/consult/detail/'+id+'.html';
	});
	
	//时间戳格式化
	var formatDate = function (time){
		var now = new Date(time*1000);
		var month = now.getMonth() + 1;
		var date = now.getDate();
	      　return  month + "-" + date ;
	}
})