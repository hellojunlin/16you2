<?php use yii\helpers\Url;
?>
<style>
#clipArea {
    width:250px;
    height: 416.67px;
}
#file,
#clipBtn {
    margin: 20px;
}
#clipBtn {
    background: #2b7dbc;
    color: #fff;
    padding: 5px;
}
.p_logo {
    position: relative;
    width: 150px;
    height: 150px;
    display:inline-block;
    margin-right: 20px;
    margin-top: 15px;
}
.p_logo img{
    width:100%;
    height:100%;
}
.p_logo .img_circle{
    color: #F98203;
    position: absolute;
    top: -7px;
    right: -5px;
}
.p_img {
    position: relative;
    width: 150px;
    height: 150px;
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
    width: 150px !important;
    height: 150px !important;
    left: 0;
    opacity: 0;
    top: 0px;
}
</style>
<div class="page-heading">
    <h3>
   平台管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">平台记录</a>
        </li>
        <li class="active">添加平台 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">添加平台</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" id="signupok" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">平台名称</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="平台名称"  name="pname" maxlength="25" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">标识id</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="标识id （必须包含字母）"  name="punid" maxlength="50" required/><span></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">密码</label>
                            <div class="col-sm-5">
                                <input type="password" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="密码"  name="password" maxlength="20" required/><span></span>
                            </div>
                        </div>
                          <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">角色</label>
                            <div class="col-sm-5">
                               <select class="form-control tooltips" tabindex="3" name="role" id="role">
                                <?php foreach ($role as $r):?>
                                <option value="<?php echo $r->name;?>"><?php echo $r->name;?></option>
                                <?php endforeach;?>
                               </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">所属公司</label>
                            <div class="col-sm-5">
                               <select class="form-control tooltips" tabindex="3" name="cid" id="cid">
							           <?php foreach ($company as $com):?>
							            <option value="<?php echo $com['id']?>"><?php echo $com['compname']?></option>
							          <?php endforeach;?>
					           </select>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 广告图片</label>
                            <div class="col-sm-5">
                                <input type="hidden" value="" name="image" value=""/>
                                <div id="clipArea"></div>
                                <input type="file" id="file" style="display: inline-block;">
                                <span id="clipBtn">点击截取</span>
                                <div id="view"></div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">公众号二维码</label>
                             <div class="upload_btn1 col-sm-10">
                                 <div class="up_img game_logo">
                                    <img src="/media/images/putimg.png">
                                    <input class="input_upimg" type="file" id="upload_image" name="myFile" value="图片上传"
                                         capture="camera">
                                 </div>
                             </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">状态</label>
                            <div class="col-sm-9 icheck ">
                                <div class="square-blue">
                                    <div class="radio ">
                                        <div class="iradio_square-blue" style="position: relative;"><input tabindex="3" type="radio" name="state" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" checked="checked" value="1"></div>
                                        <label> 启用 </label>
                                    </div>
                                </div>
                                <div class="square-red">
                                    <div class="radio ">
                                        <div class="iradio_square-red" style="position: relative;"><input tabindex="3" type="radio" name="state" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" value="0"></div>
                                        <label> 禁用 </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">备注</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="备注"  name="remark" maxlength="100"/><span></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">排序</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="数值越大越靠前"  name="sort" maxlength="10"/><span></span>
                            </div>
                        </div>
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
<script src="/media/js/iscroll-zoom.js"></script>
<script src="/media/js/lrz.all.bundle.js"></script>
<script src="/media/js/jquery.photoClip.js"></script> 
<script type="text/javascript" src="/media/js/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="/media/js/LocalResizeIMG.js"></script>  
<script type="text/javascript" src="/media/js/mobileBUGFix.mini.js"></script>
<script>
$("input[name='punid']").change(function(){
    var punid = $(this).val();
    if(punid == ''){
        $(this).next('span').html('标识id不能为空').css('color','#900');
     }else if(!isNaN(punid)) {
     	$(this).next('span').html('标识id不能全是数字').css('color','#900');
     }else{
         $(this).next('span').html('');
         $.ajax({
       	     url:'/platform/uniqueone.html',
             type:'post',
             data:{'punid':punid},
             success:function(data){
                 if(data == 1){
                   $("input[name='punid']").val("");
                     $("input[name='punid']").next('span').html('唯一标识 '+punid+' 已存在!').css('color','#900');
                 }else{
                   $("input[name='punid']").next('span').html('');
                 }
             }
         });
    }
})

//异步提交数据
$('#submit').click(function(){
		var punid = $("input[name='punid']").val();
	    if(punid == ''){
	       $(this).next('span').html('标识id不能为空').css('color','#900');
	       layer.msg('标识id不能为空');
	       return false;
	    }else if(!isNaN(punid)) {
	    	$(this).next('span').html('标识id不能全是数字').css('color','#900');
	    	layer.msg('标识id不能全是数字');
	    	return false;
	    }else{
	        $(this).next('span').html('');
	   }
	   $.ajax({		
		type:'post',
		dataType:'json',
		data:$('#signupok').serialize(),
		url:'/platform/add.html',
		success:function(data){
			if(data.errorcode==0){
				 layer.msg(data.info, {icon: 1,time:2000});
				 setTimeout(function (){
					 location.href="/platform/index.html";
				},1000);
			}else if(data.errorcode==1001){
				   layer.msg(data.info, {icon: 1,time:2000});
	    		}else{
	    		   layer.msg('添加失败', {icon: 1,time:2000});
	    		}
		}
	    });
});
//图片截取
var clipArea = new bjj.PhotoClip("#clipArea", {
  size: [125,208],
  outputSize: [450, 750],
  file: "#file",
  view: "#view",
  ok: "#clipBtn",
  clipFinish: function(dataURL) {//截取完返回
    if($("#view img").length!=0){
      $("#view img").remove();
    }
    layer.load(0, {time:500}); //0代表加载的风格，支持0-2
    $.ajax({
      //异步提交数据
      type:'post',
      dataType:'json',
      data:{'imgbase64':dataURL.substr(23)},
      url:'/platform/subimg.html',
      success:function(data){
        layer.msg(data.info);
        if(data.errorcode==0){
          $("input[name='image']").val(data.imgurl);
          $('#clipArea').hide();
        }
      }
    })
  }
});



/**
 *  异步上传二维码图片
 */
$(function () {
    $('#upload_image').localResizeIMG({
        quality: 0.5,
        success: function (result) {
            var img = new Image();
            var status = true;
            var div = $('<div>').addClass('p_img delimg').appendTo($('.upload_btn1'));
            img.src = '/media/images/loading.gif';
            $(img).appendTo(div);
            if ($('.p_img').length >= 1) { //上传成功，并达到上传的张数，则隐藏
                 $('.up_img').css('display','none');
            }
            if (status) {
                var data = {imgbase64:result.clearBase64};
                $.ajax({        //异步提交数据
                    type:'post',
                    dataType:'json',
                    data:data,
                    url:'/platform/subimg.html',
                    async:true,
                    beforeSend:function(){},
                    success:function(data){
                        //成功
                        if(data.errorcode==0){
                            //图片右上角叉叉
                            $('<i>').addClass('fa fa-times-circle img_circle ').appendTo(div);
                            var save = $(img).appendTo(div);
                            img.src = '/media/images/plateform/'+data.imgurl;
                            div.append('<input type="hidden"  name="code_img" value="'+data.imgurl+'"/>');
                            }else{//上传失败
                            layer.msg(data.info);
                            $('.up_img').css('display','block');
                        }
                    }
                });
            }
        }
    })
});

//图片删除事件
$('body').on('click','.delimg .img_circle',function(){
  var parent = $(this).parent();
  var imgsrc = parent.children('img').attr('src');
  if(confirm('确定删除该照片吗？')){
      $.ajax({
          url:'/platform/delimg.html',
          type:'post',
          dataType:'json',
          data:{'imgsrc':imgsrc},
          success:function(data){
              //成功
              if(data.errorcode==0){
                    $(parent).remove();//删除页面上的图片
                    $('.up_img').css('display','block');
              }else if(data.errorcode==1002){
                    alert(data.info);
              }else{
                 $(parent).remove();//删除页面上的图片
                 $('.up_img').css('display','block');   
                 $('.previewbtn').css('display','none');
                 $('.preview').css('display','none');    
              }
          }
      })   
  }
})
</script>