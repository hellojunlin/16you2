$('.navtab ul').navScroll({
        mobileDropdown: true,
        mobileBreakpoint: 768,
        scrollSpy: true,
        navHeight:100
 });

//最近在玩更多
function more(url){
		window.location.href = url;
		_source = '更多';
    	 postmessage();
}
	 
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
	
	
//轮播图跳转开始页面
$('.carousel-btn').click(function (){
		var id = $(this).attr('id');
		if(id){
			 var idarr = id.split(',');
			 window.location.href = idarr[0];
			 _source = '轮播图第'+idarr[1]+'张';//来源
			 postmessage();
		}	
});

//跳转资讯详情页
$("body").on('click','.cons',function(){
		var id = $(this).attr('name');
		window.location.href='/consult/detail/'+id+'.html';
});
 

var mySwiper1 = new Swiper('#myswiper1', {
        direction: 'horizontal',
        pagination: '.swiper-pagination',
        autoplay: 3000,
        autoplayDisableOnInteraction: false,
        loop: true,
        //roundLengths: true,
        initialSlide: 2,
        speed: 600,
        slidesPerView: "auto",
        spaceBetween: 10,
        centeredSlides: true,
        //followFinger: false,
        observer:true,
        observeParents:true
    });
    var mySwiper2 = new Swiper('#myswiper2', {
        // direction: 'horizontal',
        direction: 'vertical',
        loop: true,

        // 自动播放时间
        autoplay: true,

        // 播放的速度
        speed: 1000,
        //observer:true,
        //observeParents:true
    });

$(".navtab").hide();

// 获取导航节点
var nav = $(".navtab");

//获取轮播图的高度
var bannerH = $(".top-banner").outerHeight();

$(window).on('scroll', function() {
    // 滚动条距离顶部距离
    var winTop_2 = $(window).scrollTop();

    if (winTop_2 > bannerH) {
        nav.show();
    } else {
        nav.hide();
    }
})


//换一批
$(".fine-b-tab span a:last").on('click', function() {
    console.log('111');
})

//关注公众号弹框
$payattenction = $('.payattenction');
$wxmodal = $('#wxmodal');
$payattenction.click(function(){
	$wxmodal.show();
})
//关闭公众号弹框
$('#closecodemodal').click(function(){
	$wxmodal.hide();
})
//点击搜索按钮
$payattenction = $('.payattenction');
$searchbox = $('#searchbox');
$searchbox.click(function(){
	$('.infobox').hide();//个人信息
	$('.content').hide();//首页内容
	$('#searchcon').show();//显示搜索内容
	$('.searchinputbox').show();//搜索框
	$searchbox.hide();//搜索按钮
	$payattenction.hide();//隐藏关注按钮
	$('.downbox').hide();//app下载
	mySwiper1.stopAutoplay();
})
//关闭搜索页面
$('#cancelimg').click(function(){
	$('.infobox').show();//个人信息
	$('.content').show();//首页内容
	$('#searchcon').hide();//显示搜索内容
	$('.searchinputbox').hide();//搜索框
	$searchbox.show();//搜索按钮
	$payattenction.show();//显示关注按钮
	$('.downbox').show();//app下载
	$('.winput').val('');
	$('.no-result_Mt').hide();
	mySwiper1.startAutoplay();
	//mySwiper1.init();
	mySwiper2.init();
});

/* 搜索功能*/
jQuery.expr[':'].Contains = function(a,i,m){
    return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
};

	function filterList(header, list) { 
	  console.log(header,list);
	  var form = $("<form>").attr({"class":"filterform","action":"#"}),
	      input = $(".winput");
	 //$(form).append(input).appendTo(header);
	  $(input).change( function () {
	      var filter = $(this).val();
	      $(list).show();
	      if(filter) {
		 	      $matches = $(list).find('a:Contains(' + filter + ')').parent();
		 	      $('li', list).not($matches).slideUp();
		      
		 	      $matches.slideDown();
		 	      if ($('li', list).is($matches)) {//判断搜索的内容是否是匹配的元素
		 	      	$(".no-result_Mt").hide();
		 	      	$(".atendBox").hide();
		 	      }
		 	      else{
			      	$(".no-result_Mt").show();
			      	$(".atendBox").show();
		 	      }
	      } else {
	         $(list).find("li").slideDown();
	         $(".no-result_Mt").hide();
	      }
	      return false;
	 })
	  .keyup( function () {
	      $(this).change();
	  });
	} 
	$(function () {
		  filterList($("#formdr"), $("#listdr"));
		});