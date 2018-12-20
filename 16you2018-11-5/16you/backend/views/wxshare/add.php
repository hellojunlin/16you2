<?php use yii\helpers\Url;
?>
<style>
.p_img {
    position: relative;
    width: 100px;
    height: 100px;
    display:inline-block;
    margin-right: 20px;
    margin-top: 15px;
}
.p_img img{
    width:100%;
    height:100%;
}
.p_img .img_circle{
    color: #F98203;
    position: absolute;
    top: -7px;
    right: -5px;
}
.up_img{
    position:relative;
    vertical-align: top;
    display:inline-block;
}
.up_img img{
    width:150px;
    height:150px;
}
.up_img input{
    position: absolute;
    width: 100px !important;
    height: 100px !important;
    left: 0;
    opacity: 0;
    top: 0px;
}
</style>
<div class="page-heading">
    <h3>
   微信分享管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">微信分享记录</a>
        </li>
        <li class="active">添加微信分享 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">添加微信分享</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" id="signupok" enctype="multipart/form-data">
	                    <div class="form-group">
	                            <label class="col-sm-2 col-sm-2 control-label">选择游戏</label>
	                            <div class="col-sm-5">
	                                <div class="selectdivbox" style="width:100%;">
	                                   <input type="text" class="hidden-input" value="" name="gid" />
										<button type="button" class="btn selectbtn" style="text-align:left;">
											<span class="btntxt">选择游戏</span>
											<span class="caret"></span>
										</button>
										<div id="dropdownoption" class="dropdown-menu" style="width:100%;">
											<div class="live-filtering">
												<div class="searchinput">
													<input id="searchname" type="text" class="form-control live-search" autocomplete="off" placeholder="搜索关键字">
												</div>
												<div class="list-to-filter">
													<ul class="list-unstyled">
													<?php if($game): ?>
													  <?php foreach ($game as $v):?>
	                                						<?php if(isset($v['id'])): ?>
														<li class="filter-item items" data-value="<?php echo $v['id'].'%@!'.$v['name'];?>"><?php echo $v['name'];?></li>
														 <?php endif; ?>
	                               					 <?php endforeach;?>
	                               					  <?php endif; ?>
													</ul>
													<div class="no-search-results">搜索不到结果</div>
												</div>
											</div>
										</div>
									</div>	
	                            </div>
	                        </div>
                       <!-- <div class="form-group">
                            <label class="col-sm-2 control-label">所属游戏</label>
                            <div class="col-sm-5">
                                <select class="form-control m-bot15" name="gid">
                                    <?php foreach ($game as $v) {
                                        echo '<option value="'.$v['id'].'%@!'.$v['name'].'">'.$v['id'].'---'.$v['name'].'</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">标题</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="标题"  name="title" maxlength="25" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">描述</label>
                            <div class="col-sm-5">
                                <textarea rows="4" class="form-control" name="desc"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">跳转的链接</label>
                            <div class="col-sm-5">
                                <input type="url" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="跳转的链接"  name="link"  />
                                <span>http://www.baidu.com</span>
                            </div>
                        </div>
                       <!--  <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">分享图标</label>
                             <div class="upload_btn col-sm-10">
                                 <div class="up_img">
                                    <img src="/media/images/putimg.png">
                                    <input class="input_upimg" type="file" id="upload_image" name="myFile" value="图片上传"
                                         capture="camera">
                                 </div>
                             </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">好友分享成功回调</label>
                            <div class="col-sm-5">
                                <textarea rows="4" class="form-control" name="success"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">好友分享取消回调</label>
                            <div class="col-sm-5">
                                <textarea rows="4" class="form-control" name="cancel"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">朋友圈分享成功回调</label>
                            <div class="col-sm-5">
                                <textarea rows="4" class="form-control" name="psuccess"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">朋友圈分享取消回调</label>
                            <div class="col-sm-5">
                                <textarea rows="4" class="form-control" name="pcancel"></textarea>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <input type="button" name="signup" id="submit" class="btn btn-primary" value="保存">
                                <button class="btn btn-default" type="reset">重 写</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
	</div>
</section>
<script src="/media/js/layer/layer.js"></script><!--下面的js有用到--> 
<script type="text/javascript" src="/media/js/LocalResizeIMG.js"></script>  
<script>
//异步提交数据
$('#submit').click(function(){
  $.ajax({		
	  type:'post',
	  dataType:'json',
	  data:$('#signupok').serialize(),
	  url:'/wxshare/create.html',
	  success:function(data){
			if(data.errorcode==0){
				 layer.msg(data.info, {icon: 1,time:2000});
				 setTimeout(function (){
					 location.href="/wxshare/index.html";
				},1000);
			}else if(data.errorcode==1001){
				   layer.msg(data.info, {icon: 1,time:2000});
	    		}else{
	    		 layer.msg('添加失败', {icon: 1,time:2000});
    	}
	  }
	});
});

/**
 *  异步上传游戏logo
 */
/* $(function () {
    $('#upload_image').localResizeIMG({
        quality: 0.3,
        success: function (result) {
            var img = new Image();
            var status = true;
            var div = $('<div>').addClass('p_img delimg').appendTo($('.upload_btn'));
            img.src = '/media/images/loading.gif';
            $(img).appendTo(div);
            $('.up_img').css('display','none');
            if (status) {
                var data = {imgbase64:result.clearBase64};
                $.ajax({        //异步提交数据
                    type:'post',
                    dataType:'json',
                    data:data,
                    url:'/wxshare/subimg.html',
                    async:true,
                    beforeSend:function(){},
                    success:function(data){
                        //成功
                        if(data.errorcode==0){
                          //图片右上角叉叉
                          $('<i>').addClass('fa fa-times-circle img_circle ').appendTo(div);
                          var save = $(img).appendTo(div);
                          img.src = '/media/images/wxshare/'+data.imgurl;
                          div.append('<input type="hidden"  name="image" value="'+data.imgurl+'"/>');
                        }else{//上传失败
                          layer.msg(data.info);
                          $('.up_img').css('display','block');
                        }
                    }
                });
            }
        }
    })
 */
  //图片删除事件
 /*  $('body').on('click','.delimg .img_circle',function(){
      var parent = $(this).parent();
      var imgsrc = parent.children('img').attr('src');
      if(confirm('确定删除该照片吗？')){
          $.ajax({
              url:'/wxshare/delimg.html',
              type:'post',
              dataType:'json',
              data:{'imgsrc':imgsrc},
              success:function(data){
                  //成功
                  if(data.errorcode==0){
                        $(parent).remove();//删除页面上的图片
                        if ($('.p_img').length < 1) { //上传成功，并达到上传的张数，则隐藏
                          $('.up_img').css('display','block');
                        }
                  }else if(data.errorcode==1002){
                        alert(data.info);
                  }else{
                     $(parent).remove();//删除页面上的图片
                     if ($('.p_img').length < 1) {
                         $('.up_img').css('display','block');   
                         $('.previewbtn').css('display','none');
                         $('.preview').css('display','none');
                     }
                  }
              }
          })   
      }
  })
}); */

//选择
$('.selectbtn').click(function (event){
	$('#dropdownoption').toggle();
	$(document).on('click',function(){//对document绑定一个影藏Div方法
		$('#dropdownoption').hide();
	});
	event.stopImmediatePropagation();//阻止事件向上冒泡
});
$('#dropdownoption').click(function (event){
	event.stopImmediatePropagation();//阻止事件向上冒泡
})
//选择选项
$('.items').click(function(){
	var lival = $(this).text();
	var dataval = $(this).attr('data-value');
	$('.hidden-input').attr('value',dataval);
	$('.btntxt').text(lival);
	$('#dropdownoption').hide();
})

//搜索匹配
function funsearch(){
	var searchname = $.trim($('#searchname').val());
	if(searchname ==""){
		$('.list-unstyled li').show();
		$('.no-search-results').hide();
	}else{
		$('.list-unstyled li').each(function(){
			var litxt = $(this).text();
			if(litxt.indexOf(searchname) != -1){
				$(this).attr('class','showli').show()
				var lilen =  $('.list-unstyled').find('.showli').length;
				console.log(lilen);
				if(lilen > 0 ){
					$('.no-search-results').hide();
				}
			}else{
				$(this).removeAttr('class').hide();
				var lilen1 = $('.list-unstyled').find('.showli').length;
				if(lilen1 <= 0 ){
					$('.no-search-results').show();
				}
				
			}
		})
	}
}
$('#searchname').bind('input propertychange',function(){
	funsearch();
})
</script>