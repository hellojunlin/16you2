<?php use yii\helpers\Url;
?>
<link rel="stylesheet" type="text/css" href="/media/css/bootstrap-fileupload.min.css" />
<link rel="stylesheet" type="text/css" href="/media/js/jquery-multi-select/css/multi-select.css" />
<style>
.btn-width{
    width:150px !important;
}
.tagbox span{
    position:relative;
    display:block;
    margin-bottom: 10px;
}
label.dellabel {
    position: absolute;
    top: 5px;
    left: 40%;
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
.inputwid{
   margin-top: 5px;
   border-radius: 4px; 
   border: 1px solid #ccc; 
   line-height: 24px;
}
.btn-width{
    width:150px !important;
}
.tagbox span{
    position:relative;
    display:block;
    margin-bottom: 10px;
}
label.dellabel {
    position: absolute;
    top: 5px;
    left: 40%;
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
.must{
    color:#FF0000;
}
</style>
<div class="page-heading">
    <h3>
  小游戏管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">小游戏记录</a>
        </li>
        <li class="active">添加小游戏</li>
    </ul>
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">添加小游戏</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" id="signupok" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $game->id;?>" name="id">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">小游戏名称<span class="must">*</span></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="小游戏名称"  name="name" maxlength="15" value="<?php echo $game->name;?>" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">小游戏logo</label>
                             <div class="upload_btn1 col-sm-10">
                                 <div class="up_img game_logo">
                                    <img src="/media/images/putimg.png">
                                    <input class="input_upimg" type="file" id="upload_logo" name="myFile" value="图片上传"
                                         capture="camera">
                                 </div>
                                 <?php if($game->head_img):?>
                                 <div class="p_logo delimg"><i class="fa fa-times-circle fa-2x img_circle"></i>
                                     <img src="/media/images/sgame/<?php echo $game->head_img;?>">
                                     <input type="hidden" name="logo" value="<?php echo $game->head_img;?>">
                                 </div>
                                 <?php endif;?>
                             </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">小游戏唯一标识<span class="must">*</span></label>
                               <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="游戏唯一标识"  name="unique" maxlength="100"  value="<?php echo $game->unique;?>" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">小游戏链接<span class="must">*</span></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="游戏链接"  name="game_url" maxlength="200" value="<?php echo $game->game_url;?>" required />
                            </div>
                        </div>
                        
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">描述</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="描述"  name="descript" maxlength="50" value="<?php echo $game->descript;?>" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">游戏人数<span class="must">*</span></label>
                               <div class="col-sm-5">
                                <input type="number" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="游戏人数"  name="gamenum" maxlength="100" value="<?php echo $game->gamenum;?>" required />
                            </div>
                       </div>
                       
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">排序</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="排序，数值越大，越靠前"  name="sort" maxlength="10" value="<?php echo $game->sort?>"/>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">状态<span class="must">*</span></label>
                            <div class="col-sm-9 icheck ">
                                <div class="square-blue">
                                    <div class="radio ">
                                        <div class="iradio_square-blue" style="position: relative;"><input tabindex="3" type="radio" name="state" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" <?php echo ($game->state==1)?'checked':'';?> value="1"></div>
                                        <label> 启用 </label>
                                    </div> 
                                </div>
                                <div class="square-red">
                                    <div class="radio ">
                                        <div class="iradio_square-red" style="position: relative;"><input tabindex="3" type="radio" name="state" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" <?php echo ($game->state==0)?'checked':'';?> value="0"></div>
                                        <label> 禁用 </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <input type="button" name="signup" id="submit" class="btn btn-primary" value="保存">
                                <button type="button" name="signup" id="submitload" class="btn btn-primary" style="width:54px;display:none;"><i class="fa fa-spinner fa-pulse"></i></button>
                                <button class="btn btn-default" type="reset">重 写</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
       
</section>
<script type="text/javascript" src="/media/js/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="/media/js/LocalResizeIMG.js"></script>  
<script type="text/javascript" src="/media/js/mobileBUGFix.mini.js"></script>
<script>
//异步提交数据
$('#submit').click(function(){
    var index_ = layer.load();
       $.ajax({     
        type:'post',
        dataType:'json',
        data:$('#signupok').serialize(),
        url:'/sgame/add.html',
        success:function(data){
            if(data.errorcode==0){
                 layer.msg(data.info, {icon: 1,time:2000});
                 setTimeout(function (){
                     location.href="/sgame/index.html";
                },1000);
            }else if(data.errorcode==1001){
                   layer.msg(data.info, {icon: 1,time:2000});
                }else{
                   layer.msg('添加失败', {icon: 1,time:2000});
                }
        }
        });
        setTimeout(function (){
            layer.close(index_);
        },2000);
        
});

if($(".btn-width").length>2){
     $('.addlabel').hide();
}
if ($('.p_img').length >= 8) { //上传成功，并达到上传的张数，则隐藏
       $('.up_img').css('display','none');
}else{
    $('.up_img').css('display','inline-block');
}

if ($('.p_logo').length >= 1) { //上传成功，并达到上传的张数，则隐藏
    $('.game_logo').css('display','none');
}
/**
 *  异步上传游戏logo
 */
$("#upload_logo").change(function(){
    var this_l = $(this);
    var fd = new FormData(document.getElementById('signupok'));
    var xmlobj = new XMLHttpRequest();
    xmlobj.open('post','/sgame/filephoto.html');
    xmlobj.send(fd);
    xmlobj.onload = function(){
        var div = $('<div>').addClass('p_logo delimg').appendTo($('.upload_btn1'));
        $('.game_logo').css('display','none'); //上传成功则隐藏
        $('<i>').addClass('fa fa-times-circle fa-2x img_circle ').appendTo(div);
        var data = JSON.parse(xmlobj.responseText);
        if(data[0]){
            var url = window.URL.createObjectURL(this_l['0'].files['0']);
            div.append('<img src="'+url+'"/>');
            div.append('<input type="hidden"  name="logo" value="'+data['1']+'"/>');
        }else{
            alert('上传失败');
        }    
    }
});
    
/**
 *  异步上传活动图片
 */
$(function () {
    $('#upload_image').localResizeIMG({
        quality: 0.3,
        success: function (result) {
            var img = new Image();
            var status = true;
            var div = $('<div>').addClass('p_img').appendTo($('.upload_btn'));
            img.src = '/media/images/loading.gif';
            $(img).appendTo(div);
//          console.log(imgnum);
            if ($('.p_img').length >= 8) { //上传成功，并达到上传的张数，则隐藏
                 $('.up_img').css('display','none');
            }
            if (status) {
                var data = {imgbase64:result.clearBase64};
                $.ajax({        //异步提交数据
                    type:'post',
                    dataType:'json',
                    data:data,
                    url:'/sgame/subimg.html',
                    async:true,
                    beforeSend:function(){},
                    success:function(data){
                        //成功
                        if(data.errorcode==0){
                            //图片右上角叉叉
                            $('<i>').addClass('fa fa-times-circle fa-2x img_circle ').appendTo(div);
                            var save = $(img).appendTo(div);
                            img.src = '/media/images/sgame/'+data.imgurl;
                            div.append('<input type="hidden"  name="image[]" value="'+data.imgurl+'"/>');
                        }else{//上传失败
                            alert(data.info);
                            $('.up_img').css('display','block');
                        }
                    }
                });
            }
        }
    });
    //图片删除事件
    $('body').on('click','.delimg .img_circle',function(){
      var parent = $(this).parent();
      var imgsrc = parent.children('img').attr('src');
      if(confirm('确定删除该照片吗？')){
          $.ajax({
              url:'/sgame/delimg.html',
              type:'post',
              dataType:'json',
              data:{'imgsrc':imgsrc},
              success:function(data){
                  //成功
                  if(data.errorcode==0){
                        $(parent).remove();//删除页面上的图片
                        if ($('.p_img').length <= 8) { //上传成功，并达到上传的张数，则隐藏
                            $('.up_img').css('display','block');
                       }
                  }else if(data.errorcode==1002){
                        alert(data.info);
                  }else{
                     $(parent).remove();//删除页面上的图片
                     if ($('.p_img').length <= 8) {
                         $('.up_img').css('display','block');   
                         $('.previewbtn').css('display','none'); 
                         $('.preview').css('display','none');
                     }
                  }
              }
          })   
      }
    })
 });
</script>