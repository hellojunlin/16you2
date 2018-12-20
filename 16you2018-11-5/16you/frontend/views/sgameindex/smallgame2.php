<script>
wx.config({
	debug:false,
	<?php $signPackage = yii::$app->session->get('signPackage');?>
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: '<?php echo $signPackage["timestamp"];?>',
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
		//所有要调用的 API 都要加到这个列表中
		'onMenuShareTimeline',
		'onMenuShareAppMessage',
		'hideMenuItems'
    ]
  });
/*var indexnum = '';
 var thisId = window.location.hash;
if(thisId != "" && thisId != undefined){
	indexnum = thisId.substring(1);
} */
var indexnum = "<?php echo isset($_GET['id'])?$_GET['id']:''?>";
var title = "16游小游戏";
var desc = '精品小游戏';
var imgurl = "<?php echo yii::$app->params['frontends'];?>/media/images/icon_mean.png";
share(title,imgurl,desc,indexnum);
function share(title,imgurl,desc,indexnum){
	  var sgmameurl = indexnum?"https://wx.16you.com/sgameindex/index/"+indexnum+".html":'';
	  wx.ready(function () {
	    // 在这里调用 API
		  //分享朋友圈	
		  wx.onMenuShareTimeline({//voteinfo
		    title:title, // 分享标题
		    link: sgmameurl,// 分享链接
		    imgUrl:imgurl, // 分享图标
		    success: function () { 
		       alert('分享成功');
		    },
		    cancel: function () { 
		        // 用户取消分享后执行的回调函数
		    	alert("分享失败");
		    }
		});
		
		//分享朋友
		wx.onMenuShareAppMessage({
		    title: title, // 分享标题
		    desc: desc, // 分享描述
		    link: sgmameurl,// 分享链接
		    imgUrl: imgurl, // 分享图标
		    type: 'link', // 分享类型,music、video或link，不填默认为link
		    success: function () { 
		    	 alert('分享成功');
		    },
		    cancel: function () { 
		    	alert("分享失败");
		    }
		}); 
		//隐藏分享QQ
		wx.hideMenuItems({
		 	    menuList: ['menuItem:share:qq'] // 要隐藏的菜单项，只能隐藏“传播类”和“保护类”按钮，所有menu项见附录3
		});
	  });
}
  </script>
<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/rem.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/smallgame.css">
	<title>小游戏</title>
<body>
<div class="All">
		<div class="user-headbox">
		  <div class="u_h">
			<div class="headimg"><img src="<?php echo isset($user->head_url)?$user->head_url:yii::$app->params["cdn16yous"].'/images/noimg.jpg';?>"></div>
			<div class="infobox">
			   <div class="nametext">
			   		<span class="namebox"><?php echo isset($user->username)?$user->username:'';?></span>
			   		<!-- <div class="vipbox">
			   			<div class="vipprogress" ><span></span></div>
			   			<div class="vipgrade">
			   				<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/vips.png">
			   				<p class="vipnum">VIP<?php echo $user->vip; ?></p>
			   			</div>
			   		</div> -->
			    </div>
				<!-- <div class="infortext">ID:<span><?php echo isset($user->Unique_ID)?$user->Unique_ID:'';?></span></div> -->
			</div>
		   </div>
		   <!-- 以下是搜索框+关注部分 -->
		   <div class="searchinputbox">
		      <div class="innerbox">
		      	 <input type="text" class="winput_mt" placeholder="火柴人">
		   		 <img class="hitsearch" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/searchicon.png">
		   		 <img class="cancelimg" id="cancelimg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/closesearch.png">
		      </div>
		   </div>
		   <div class="searchbox" id="searchbox">
		   	 <img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/searchicon.png">
		   </div>
		   <div class="payattenction" id="payattenction">
		   		<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/gz.gif">
		   		<!-- <span class="gztxt">关注</span> -->
		   </div>
		</div>
	<div class="contentgameList_mt" id="contentgameList_mt">
		<div id="formdr_mt"></div>
			<!-- 测试 -->
		<ul id="listdr_mt" style="margin-bottom: 17%;"> 
			<?php foreach ($sgamearr as $k=>$sgame):?>
			<li class="game_list_box_mt show-detail">
            	<em>
            		<img src="<?php echo yii::$app->params['cdns'].'/sgame/'.$sgame['head_img']; ?>">
            	</em>
            	<a class="screening_mt" href="#//"><?php echo $sgame['name']?></a>
				<p>
					<span class="col_2mt_dr colcommon_mt"><?php echo $sgame['descript']?></span>
					<span class="col_4mt_dr colcommon_mt"><b class="colNum"><?php echo $sgame['gamenum']?></b>人玩过</span>
				</p>
	            <div class="game_begin start-game gamestart" id="<?php echo $k;?>"　name="<?php echo $sgame['game_url'];?>" src="<?php echo $sgame['game_url'];?>">
	            	<a>开始</a>
	            </div>
        	</li>  
        	<?php endforeach;?>
		</ul> 
		<div class="no-result_mt" style="display: none;">
			<p>—— 暂未搜索到任何结果 ——</p>
			<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/fail.png">
		</div>
	</div>
		<!--微信关注弹框-->
		<div id="wxmodal">
			<div class="wxbox">
			   <div class="wxsmallbox">
			   		<img class="closecodemodal" id="closecodemodal" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/closewhite.png">
					<img class="wxcodeimg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/qrcode.jpg">
			   </div>
				<p class="tipcode">长按识别二维码，关注16游微信公众号</p>
			</div>
		</div>
<!-- 	</div> -->
	<div id="smallgame">
		<div class="adbg"style="display:none;"><img class="abimg"  src="<?php echo yii::$app->params['cdns']; ?>/plateform/5992c58e99c73.jpeg"></div>
		<div id="game-iframe-div">
			<iframe id="game-frame" name="gameFrame" frameborder="no" border="px" marginwidth="0px" marginheight="0px" scrolling="auto" src="">
			</iframe>
		</div>
	</div>
	<!-- //退出游戏弹框 -->
	<div class="rank_bg" >
		<div class="ptnList">
			<h1>更多好游戏尽在<i class="neImg"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/16Ne.png"></i></h1>
			<span class="popup_close">
				<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/close_gray.png">
			</span>
			<div class="recently">
				<div class="gameatt">
					<p onclick="window.location.href='/index/index!16you.html'" id="getup">
						<em>
							<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/meanself.png">
						</em>
						<span>更多游戏</span>
					</p>
				</div>
			</div>
			<div class="knowBtn">
				<a>离开游戏</a>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<script src="<?php echo yii::$app->params['cdn16yous']; ?>/js/jquery.min.js"></script>
<script type="text/javascript">
	//关注公众号弹框
		$payattenction = $('.payattenction');
		$wxmodal = $('#wxmodal');
	    $payattenction.click(function(){
	    	$wxmodal.show();
	    })
	    //关闭公众号弹框
	    $('.closecodemodal').click(function(){
	    	$wxmodal.hide();
	    })
		 //点击搜索按钮
		 	$searchbox = $('#searchbox');
		    $searchbox.click(function(){
		    	$('.infobox').hide();//个人信息
		    	$('.contentgameList_mt').show();//首页内容
		    	// $('#contentgameList_mt').show();//显示搜索内容
		    	$('.searchinputbox').show();//搜索框
		    	$(".winput_mt").show();
		    	$searchbox.hide();//搜索按钮
		    	$payattenction.hide();//隐藏关注按钮
		    	$(".hitsearch").hide();
		    })
	    //关闭搜索页面
		   $('#cancelimg').click(function(){
		    	$('.infobox').show();//个人信息
		    	$('.contentgameList_mt').show();//首页内容
		         $('#listdr_mt').show();//显示搜索内容
		    	$('.searchinputbox').hide();//搜索框
		    	$(".winput_mt").hide();
		    	$searchbox.show();//搜索按钮
		    	$payattenction.show();//显示关注按钮
		    	$(".All").show();
		    	$(".no-result_mt").hide();
		    	$(".hitsearch").hide();
		    	$(".game_list_box_mt").removeAttr("style");
		    })
	    /* 搜索功能*/
			   jQuery.expr[':'].Contains = function(a,i,m){
			       return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
			   };
			 
			   function filterList(header, list) { 
			     var form = $("<form>").attr({"class":"filterform","action":"#"}),
			         input = $(".winput_mt");
			     $(form).append(input).appendTo(header);
			     $(input).change( function () {
			         var filter = $(this).val();
			         $(list).show();
			         if(filter) { 
				 	      $matches = $(list).find('a:Contains(' + filter + ')').parent();
				 	      $('li', list).not($matches).slideUp();
				      
				 	      $matches.slideDown();
				 	      if ($('li', list).is($matches)) {//判断搜索的内容是否是匹配的元素
				 	      	$(".no-result_mt").hide();
				 	      	// $('.contentgameList_mt').hide();
				 	      }
				 	      else{
					      	$(".no-result_mt").show();
					      	// $('.contentgameList_mt').show();
				 	      }
			         } else {
			            $(list).find("li").slideDown();
			            $(".no-result_mt").hide();
			         }
			         return false;
			    })
			     .keyup( function () {
			         $(this).change();
			     });
			   } 
			   $(function () {
			     filterList($("#formdr_mt"), $("#listdr_mt"));
			   });


  		 //开始
	    $('#listdr_mt').on('click','.gamestart',function(){
		    var _this = $(this);
		    var url = _this.attr('src');
		    var indexnum = _this.attr('id');
		    if(window.navigator.onLine==false){
					alert('网络异常,请确保网络畅通');
			} 
			$('.adbg').show();
			setTimeout(function(){
				$('.adbg').hide();
			},3000);
			$("#smallgame").css('display','block');
			// $(".All").css('display','block');
			$("#listdr_mt").css('display','none');
			$('#game-frame').attr('src',url);
			var imgurl = _this.parent().find('img').attr('src');
            var title = _this.parent().find('.screening_mt').text();
            var desc = _this.parent().find('.colcommon_mt').text();
            share(title,imgurl,desc,indexnum);
		})
		//高度自适应
		// var iframe = $("#game-frame");
		// function iframeHeight() {    
		//     var hash = window.location.hash.slice(1), h;
		//     if (hash && /height=/.test(hash)) {
		//         h = hash.replace("height=", "");
		//         iframe.height = h;
		//     }
		//     setTimeout(iframeHeight, 200);
		// };
		// iframeHeight();
	//返回上一个历史页面
	var threegame_s = true;
   	$(function(){
		pushHistory();
		window.addEventListener('load', function() {     
            setTimeout(function() {       
               window.addEventListener('popstate', function(e) {    
            	   	// $('.rank_bg').css('display', 'block'); 
            	   	// $("#smallgame").css('display','block');
						if(document.referrer==0){
							$("#game-frame").attr('src') = "";
							$(".All").css('display','block');
						}else{
							// window.history.back();
							//window.location.reload();
							$("#game-frame").attr('src') = "";
							$(".All").css('display','block');
							// window.location.href="<?php echo yii::$app->params['frontend'];?>/sgameindex/index.html";
						}    
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
   
	//分享开始
	var name = "#"+indexnum;
   	if($(name).length>0)
	{   var _this = $(this);
	    var url = $(name).attr('src');
	    var indexnum = _this.attr('id');
	    if(window.navigator.onLine==false){
				alert('网络异常,请确保网络畅通');
		} 
		$('.adbg').show();
		setTimeout(function(){
			$('.adbg').hide();
		},3000);
		$("#smallgame").css('display','block');
		// $(".All").css('display','block');
		// $("#listdr_mt").css('display','none');
		$(".user-headbox").css('display','none');
		$("#contentgameList_mt").css('display','none');
		$('#game-frame').attr('src',url);
		var imgurl = $(name).parent().find('img').attr('src');
        var title = $(name).parent().find('.screening_mt').text();
        var desc = $(name).parent().find('.colcommon_mt').text();
        share(title,imgurl,desc,indexnum);
	}
</script>