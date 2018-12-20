<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/rem.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/editInfo.css">
	<link rel="stylesheet" type="text/css" href="/media/css/LCalendar.css">
	<title>完善信息</title>
</head>
<body>
	<div class="user-headbox">
		  <div class="u_h">
			<div class="headimg"><img src="<?php echo $user->head_url; ?>"></div>
			<div class="infobox">
			   <div class="nametext">
			   		<span class="namebox"><?php echo $user->username; ?></span>
			   		<div class="vipbox">
			   			<div class="vipprogress"><span></span></div>
			   			<div class="vipgrade">
			   				<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/vips.png">
			   				<p class="vipnum">VIP<?php echo $user->vip; ?></p>
			   			</div>
			   		</div>
			    </div>
				<div class="infortext">ID:<span><?php echo $user->Unique_ID; ?></span></div>
			</div>
		   </div>
		   <div class="searchinputbox">
		      <div class="innerbox">
		   		 <input type="text" class="winput" placeholder="宠物小精灵">
		   		 <!-- <img class="hitsearch" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/searchicon.png"> -->
		   		 <img class="cancelimg" id="cancelimg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/closesearch.png">
		      </div>
		   </div>
		   <div class="searchbox" id="searchbox">
		   	 <img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/searchicon.png">
		   </div>
		   <!-- <div class="payattenction">
		   		<img src="/media/images/gz.gif">
		   		<span class="gztxt">关注</span>
		   </div> -->
		</div>
		<div class="infoContent">
			<div class="infoTip">
				<p>完善个人信息，方便VIP专属客服为您提供更多惊喜</p>
			</div>
			<form id="banna" action="">
				<div class="dataInfor infousername">
					<div class="strSet">真实姓名</div>
						<input id="infousername" name="realname" class="inputText inputSur" type="text" placeholder="如王XX" maxlength="16" value="<?php echo $user->realname; ?>">
				</div>
				<div class="dataInfor wechat">
					<div class="strSet">微信号</div> 
					<input id="wechat" name="wxnumber" class="inputText " type="text" placeholder="请输入微信号" maxlength="36" value="<?php echo $user->wxnumber; ?>">
				</div>
				<div class="dataInfor qq">
					<div class="strSet ">QQ号</div>
					<input id="qq" name="qq" class="inputText inputSur" type="text" placeholder="请输入QQ号码" maxlength="11" value="<?php echo $user->qq; ?>">
				</div>
				<div class="dataInfor birthday">
					<div class="strSet ">生日</div>
					<input id="birthday" name="birthday" class="inputText inputSur" data-lcalendar="2000-01-01,2028-01-29"type="text" placeholder="请输入生日"  value="<?php echo $user->birthday; ?>">
				</div>
				<div class="dataInfor tel">
					<div class="strSet ">联系方式</div>
					<input id="tel" name="phone" class="inputText" type="text" placeholder="请输入联系方式" maxlength="12" value="<?php echo $user->phone; ?>">
				</div>
				<div class="subInfor">
					<a href="#">提交</a>
				</div>
				<div class="editInfor">
					<a href="#">编辑</a>
				</div>
			</form>
		</div>
		<!-- 提交后提示 -->
		<div id="tipsfail" class="tipsmodall" style="display: none;">提交失败！请填写正确信息</div>
		<!-- 第二种模态 -->
		<div id="tipsmodall" style="display: none;" class="solod">
			<div id="tiptip">
				<em><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/flow.png"></em>
				<p>get√，保存成功等着花花来找你哦！</p>
			</div>
		</div>
	<!--搜索显示-->
		<div id="searchcon">
			<?php if(!empty($playgame)):?>
			<div class="s_box">
				<p class="title">最近在玩</p>
				<div class="re_play_box">
				    <?php foreach ($playgame as $pg):?>
					<span onclick="window.location.href='/start/index/<?php echo isset($pg['id'])?$pg['id']:'';?>.html'"><?php echo  $pg['name'];?></span>
					<?php endforeach;?>
				</div>
			</div>
			<?php endif;?>
			<?php if(!empty($hotgame)):?>
			<div class="h_box">
				<p class="title">热门游戏</p>
				<div class="re_play_box">
				    <?php $hgamearr = array_slice($hotgame,0,6); foreach ($hgamearr as $hgg):?>
					<span onclick="window.location.href='/start/index/<?php echo isset($hgg['id'])?$hgg['id']:'';?>.html'"><?php echo $hgg['name']?></span>
					<?php endforeach;?>
				</div>
			</div> 
			<?php endif;?>
		  <div id="formdr"></div>
			<!-- 测试 -->
		     <ul id="listdr" style="display: none;"> 
		     <?php foreach ($allgame as $ag):?>
    			<li class="game_list_box show-detail" onclick="window.location.href='/index/detail/<?php echo isset($ag['id'])?$ag['id']:'';?>.html'">
                	<em>
                		<img src="<?php echo yii::$app->params['cdns']; ?>/game/<?php echo ($ag['head_img'])?$ag['head_img']:'notset.png';?>">
                	</em>
                	<a class="screening" href="#"><?php echo $ag['name']?></a>
                    <h2 class="slogan"><?php echo isset($ag['descript'])?$ag['descript']:'';?></h2>
    	            <div class="game_begin start-game" onclick="window.location.href='/start/index/<?php echo isset($ag['id'])?$ag['id']:'';?>.html'">
    	            	<a>开始</a>
    	            </div>
            	</li>  
            	<?php endforeach;?>
		  </ul> 
		<!-- 未搜索到时 -->
		<div class="no-result_Mt" style="display: none;">
			<p>—— 暂未搜索到任何结果 ——</p>
			<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/fail.png">
		</div>
	</div>
</body>
<script src="<?php echo yii::$app->params['cdn16yous']; ?>/js/jquery.min.js"></script>
<script src="/media/js/LCalendar.js"></script>
<script>
		var myDate = new Date();    
		var year = myDate.getFullYear();
        var month = myDate.getMonth() + 1;
        var strDate = myDate.getDate();
		var calendar = new LCalendar();

		calendar.init({
		    'trigger': '#birthday',//标签id
		    'type': 'date',//date 调出日期选择 datetime 调出日期时间选择 time 调出时间选择 ym 调出年月选择
		    'minDate':'1949-10-1',//最小日期 注意：该值会覆盖标签内定义的日期范围
		    'maxDate':year+'-'+month+'-'+strDate//最大日期 注意：该值会覆盖标签内定义的日期范围
		});

	$(document).ready(function(e) {
	 	$(":input").blur(function(e) {
                $parent=$(this).parent();
                $success="输入正确";
                $parent.find(".error").remove();
                //用户名验证
             if($(this).is("#infousername")){
                    if($(this).val()=="")
                    {
                        $parent.append("<p class='error'><img class='errorInfo' src='<?php echo yii::$app->params['cdn16yous']; ?>/images/errorInfo.png'><span>用户名不能为空</span></p>");
                        return false;
                    }
                }
                //微信号验证
                if($(this).is('#wechat')){
                	if($(this).val()=="")
                    {
                        $parent.append("<p class='error'><img class='errorInfo' src='<?php echo yii::$app->params['cdn16yous']; ?>/images/errorInfo.png'><span>微信号不能为空</span></p>");
                        return false;
                    }
                }
                //QQ号验证
                if($(this).is('#qq')){
                    if(!(/^[1-9][0-9]{4,9}$/).test($(this).val())){
                    	$parent.append("<p class='error'><img class='errorInfo' src='<?php echo yii::$app->params['cdn16yous']; ?>/images/errorInfo.png'><span>QQ输入有误！</span></p>");
                        return false;
                    }
                }
                 var reg = /((^((1[8-9]\d{2})|([2-9]\d{3}))([-\/\._])(10|12|0?[13578])([-\/\._])(3[01]|[12][0-9]|0?[1-9])$)|(^((1[8-9]\d{2})|([2-9]\d{3}))([-\/\._])(11|0?[469])([-\/\._])(30|[12][0-9]|0?[1-9])$)|(^((1[8-9]\d{2})|([2-9]\d{3}))([-\/\._])(0?2)([-\/\._])(2[0-8]|1[0-9]|0?[1-9])$)|(^([2468][048]00)([-\/\._])(0?2)([-\/\._])(29)$)|(^([3579][26]00)([-\/\._])(0?2)([-\/\._])(29)$)|(^([1][89][0][48])([-\/\._])(0?2)([-\/\._])(29)$)|(^([2-9][0-9][0][48])([-\/\._])(0?2)([-\/\._])(29)$)|(^([1][89][2468][048])([-\/\._])(0?2)([-\/\._])(29)$)|(^([2-9][0-9][2468][048])([-\/\._])(0?2)([-\/\._])(29)$)|(^([1][89][13579][26])([-\/\._])(0?2)([-\/\._])(29)$)|(^([2-9][0-9][13579][26])([-\/\._])(0?2)([-\/\._])(29)$))/ig;
                //生日验证

                if($(this).is('#birthday')){
                    if(!(reg).test($(this).val())){
                        	$parent.append("<p class='error'><img class='errorInfo' src='<?php echo yii::$app->params['cdn16yous']; ?>/images/errorInfo.png'><span>生日输入有误！</span></p>");
                        return false;
                    }
                }
                //联系方式验证
                if($(this).is('#tel')){
                    if(!(/^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/i).test($(this).val())){
                        $parent.append("<p class='error'><img class='errorInfo' src='<?php echo yii::$app->params['cdn16yous']; ?>/images/errorInfo.png'><span>手机号码格式不正确！</span></p>");
                    	return false;
                    } 
                }
         });
		$("div[class='subInfor']").show();
		$("div[class='editInfor']").hide();
		//提交
		$(".subInfor a").click(function(){
			var i=0;
	　　　　$(".inputText").each(function(){  //遍历input标签，判断是否有内容未填写
	　　　　　　var vl=$(this).val();
	　　　　　　if(vl==""){
	　　　　　　　　i=1;
	　　　　　　}
	　　　　});
	　　　　if (i==1) { 
				$("#tipsfail").show();
				setTimeout(function(){
					$("#tipsfail").hide();
				},3000);
			}
	　　　　else if ($("#banna").find('.error').length!==0) {
				$("#tipsfail").show();
				setTimeout(function(){
					$("#tipsfail").hide();
				},3000);
			}
			else{
				var userN = $("#infousername").val();
				var wechatN = $("#wechat").val();
				var qqN = $("#qq").val();
				var briN = $("#birthday").val();
				var telN = $("#tel").val();
				$.ajax({
					url:'/personal/createinformation.html',
					type:'post',
					data:{'realname':userN,'wxnumber':wechatN,'qq':qqN,'birthday':briN,'phone':telN},
					dataType:'json',
					success:function(data){
						if(data.errorcode==0){
							$("#tipsmodall").show();
		    				setTimeout(function(){
		    					$("#tipsmodall").hide();
		    				},2000);
		    				$(".infousername").append("<p name='x1' class='userN'>"+userN+"</p>");
		    				$(".wechat").append("<p name='x3' class='wechatN'>"+wechatN+"</p>");
		    				$(".qq").append("<p name='x4' class='qqN'>"+qqN+"</p>");
		    				$(".birthday").append("<p name='x5' class='briN'>"+briN+"</p>");
		    				$(".tel").append("<p name='x6' class='telN'>"+telN+"</p>");
		    				$("input[name='realname']").hide();
		            		$("input[name='wxnumber']").hide();
		            		$("input[name='qq']").hide();
		            		$("input[name='birthday']").hide();
		            		$("input[name='phone']").hide();
		            		$("div[class='subInfor']").hide();
		            		$("div[class='editInfor']").show();
						}else{
							alert(data.info);
						}
					}
				})
				
			}
			return;
		}) 
		//编辑
		$(".editInfor").click(function(){
			var userN = $("#infousername").val();
			var wechatN = $("#wechat").val();
			var qqN = $("#qq").val();
			var briN = $("#birthday").val();
			var telN = $("#tel").val(); 
			$("input[name='realname']").show();
    		$("input[name='wxnumber']").show();
    		$("input[name='qq']").show();
    		$("input[name='birthday']").show();
    		$("input[name='phone']").show();
    		$("p[name='x1']").hide();
    		$("p[name='x2']").hide();
    		$("p[name='x3']").hide();
    		$("p[name='x4']").hide();
    		$("p[name='x5']").hide();
    		$("p[name='x6']").hide();
    		$("div[class='subInfor']").show();
    		$("div[class='editInfor']").hide();
			return false;
		}) 
		//点击搜索按钮
		$searchbox = $('.searchbox');
        $searchbox.click(function(){
        	$('.infobox').hide();//个人信息
        	$('.infoContent').hide();//首页内容
        	$('#searchcon').show();//显示搜索内容
        	$('.searchinputbox').show();//搜索框
        	$searchbox.hide();//搜索按钮
        })
        //关闭搜索页面
        $('#cancelimg').click(function(){
        	$('.infobox').show();//个人信息
        	$('.infoContent').show();//首页内容
        	$('#searchcon').hide();//显示搜索内容
        	$('.searchinputbox').hide();//搜索框
        	$searchbox.show();//搜索按钮
        })
    
    		/* 搜索功能*/
			  jQuery.expr[':'].Contains = function(a,i,m){
			      return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
			  };
			  function filterList(header, list) { 
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
					      }
					      else{
					      	$(".no-result_Mt").show();
					      }
			        } else {
			           $(list).find("li").slideDown();
			           $(".no-result_Mt").hide();
			        }
			        return false;
			   })
			    .keyup( function () {
			        // fire the above change event after every letter
			        $(this).change();
			    });
			  } 
			  //ondomready
			  $(function () {
			    filterList($("#formdr"), $("#listdr"));
			  });
	 });
</script>
</html>