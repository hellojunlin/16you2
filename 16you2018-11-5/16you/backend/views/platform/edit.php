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
        <li class="active">编辑平台 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">编辑平台</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" id="signupok" method="post"  enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $platform['id'];?>" name="id">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">平台名称</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="平台名称"  name="pname" maxlength="25" required value="<?php echo $platform['pname'];?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">标识id</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="标识id"  name="punid" maxlength="50" required value="<?php echo $platform['punid'];?>"/><span></span>
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
                                    <?php foreach ($roles as $r):?>
                                        <option value="<?php echo $r->name;?>" <?php if(isset($role->name)){if($r->name==$role->name){echo 'selected';}}?>><?php echo $r->name;?></option>
                                    <?php endforeach;?>
                               </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">所属公司</label>
                            <div class="col-sm-5">
                               <select class="form-control tooltips" tabindex="3" name="cid" id="role">
							         <?php foreach ($company as $com):?>
							            <option value="<?php echo $com['id']?>" <?php echo ($com['id']==$platform['cid'])? 'selected':'';?>><?php echo $com['compname']?></option>
							          <?php endforeach;?>
					           </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 广告图片</label>
                            <div class="col-sm-5">
                                <input type="hidden" value="<?php echo $platform['start_img']; ?>" name="image"/>
                                <div id="clipArea"></div>
                                <input type="file" id="file" style="display: inline-block;">
                                <span id="clipBtn">点击截取</span>
                                <div id="view" style="height:105px;background-image:url(/media/images/plateform/<?php echo $platform['start_img']; ?>)"></div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                             <label class="col-sm-2 col-sm-2 control-label">公众号图片</label>
                             <div class="upload_btn1 col-sm-10">
                                 <div class="up_img game_logo" style="display:<?php echo ($platform['code_img'])?'none':'block';?>">
                                    <img src="/media/images/putimg.png">
                                    <input class="input_upimg" type="file" id="upload_image" name="myFile" value="图片上传"
                                         capture="camera">
                                 </div>
                                 <?php if($platform['code_img']):?>
                                 <div class="p_img delimg"><i class="fa fa-times-circle fa-2x img_circle"></i>
                                     <img src="/media/images/plateform/<?php echo $platform['code_img'];?>">
                                     <input type="hidden" name="code_img" value="<?php echo $platform['code_img'];?>">
                                 </div>
                                 <?php endif;?>
                             </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">备注</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="备注"  name="remark" maxlength="50"  value="<?php echo $platform['remark']; ?>"/><span></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">排序</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="数值越大越靠前"  name="sort" value="<?php echo $platform['sort']?>" maxlength="10"/><span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">状态</label>
                            <div class="col-sm-9 icheck ">
                                <div class="square-blue">
                                    <div class="radio ">
                                        <div class="iradio_square-blue" style="position: relative;"><input tabindex="3" type="radio" name="state" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" <?php echo ($platform['state']==1)?'checked':'';?>  value="1"></div>
                                        <label> 启用 </label>
                                    </div>
                                </div>
                                <div class="square-red">
                                    <div class="radio ">
                                        <div class="iradio_square-red" style="position: relative;"><input tabindex="3" type="radio" name="state" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" value="0" <?php echo ($platform['state']==0)?'checked':'';?>></div>
                                        <label> 禁用 </label>
                                    </div>
                                </div>
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
var pid = <?php echo $platform['id'];?>;
$("input[name='punid']").change(function(){
    var punid = $(this).val();
    if(!isNaN(punid)) {
    	$(this).next('span').html('标识id不能全是数字').css('color','#900');
    }else{
        $(this).next('span').html('');
        $.ajax({
            url:'/platform/uniqueone.html',
            type:'post',
            data:{
                'punid':punid,
                'pid':pid
                },
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
    if(punid != ''){
        $.ajax({
            url:'/platform/punid.html',
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
	    if(!isNaN(punid)) {
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
  size: [162.5,270.4],
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