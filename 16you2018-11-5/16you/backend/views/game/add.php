<?php use yii\helpers\Url;
?>
<link rel="stylesheet" type="text/css" href="/media/css/bootstrap-fileupload.min.css" />
<link rel="stylesheet" type="text/css" href="/media/js/jquery-multi-select/css/multi-select.css" />
<style>
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
.must{
    color:#FF0000;
}
.f_gamelogo input{
	 width: 300px !important;
    height: 150px !important;
}
.f_logo img{
	width:300px;
}
.f_logoimg{
	height:150px;
	width:300px;
}
</style>
<div class="page-heading">
    <h3>
  游戏管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">游戏记录</a>
        </li>
        <li class="active">添加游戏</li>
    </ul>
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">添加游戏</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" id='signupok' enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">游戏名称<span class="must">*</span></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="游戏名称"  name="name" maxlength="15" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">游戏logo</label>
                             <div class="upload_logo col-sm-10">
                                 <div class="up_img game_logo">
                                    <img src="/media/images/putimg.png">
                                    <input class="input_upimg" type="file" id="upload_logo" name="gamelogo" value="图片上传"
                                         capture="camera">
                                 </div>
                             </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">新版首页游戏logo</label>
                             <div class="col-sm-10 f_gamelogo_btn">
                                 <div class="up_img f_logo f_gamelogo">
                                    <img src="/media/images/putimg.png">
                                    <input class="input_upimg" type="file" id="f_gamelogo" name="mygamelogo" value="图片上传"
                                         capture="camera">
                                 </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">游戏唯一标识<span class="must">*</span></label>
                               <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="游戏唯一标识"  name="unique" maxlength="100" required />
                            	<span style="color:#FF0000" class="unique"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">游戏链接<span class="must">*</span></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="游戏链接"  name="game_url" maxlength="200" required />
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">游戏类型<span class="must">*</span></label>
                            <div class="col-sm-5">
                               <select class="form-control tooltips" tabindex="3" name="gametype" >
                                       <?php $gametype = yii::$app->params['gametype'];foreach ($gametype as $k=>$game):?> 
                                        <option value="<?php echo $k;?>"><?php echo $game;?></option>
                                        <?php endforeach;?>
                               </select>
                            </div>
                        </div>
                      <!--   <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">所属公司<span class="must">*</span></label>
                            <div class="col-sm-5">
                               <select class="form-control tooltips" tabindex="3" name="cid" id="role">
                                      <?php //foreach ($company as $com):?>
                                        <option value="<?php //echo $com['id']?>"><?php //echo $com['compname']?></option>
                                      <?php //endforeach;?>
                               </select>
                            </div>
                        </div>
                         -->
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">所属公司</label>
                            <div class="col-sm-5">
                                <div class="selectdivbox" style="width:100%;">
                                   <input type="text" class="hidden-input" value="" name="cid" />
									<button type="button" class="btn selectbtn" style="text-align:left;">
										<span class="btntxt">选择公司</span>
										<span class="caret"></span>
									</button>
									<div id="dropdownoption" class="dropdown-menu" style="width:100%;">
										<div class="live-filtering">
											<div class="searchinput">
												<input id="searchname" type="text" class="form-control live-search" autocomplete="off" placeholder="搜索关键字">
											</div>
											<div class="list-to-filter">
												<ul class="list-unstyled">
												<li class="filter-item items" data-value="">选择公司</li>
												  <?php if($company): foreach ($company as $v):?>
                                						<?php if(isset($v['id'])): ?>
													<li class="filter-item items" data-value="<?php echo $v['id'];?>"><?php echo $v['compname'];?></li>
													 <?php endif; ?>
                               					 <?php endforeach;endif;?>
												</ul>
												<div class="no-search-results">搜索不到结果</div>
											</div>
										</div>
									</div>
								</div>	
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">研发公司</label>
                               <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="研发公司"  name="r_company" maxlength="100"/>
                                 <span style="color:#FF0000" class="unique"></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">文网游备字</label>
                               <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="游戏唯一标识字"  name="article" maxlength="100"/>
                                 <span style="color:#FF0000" class="unique"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">类别<span class="must">*</span></label>
                            <div class="col-sm-5">
                               <select class="form-control tooltips" tabindex="3" name="type" >
                                        <option value="0">普通</option>
                                        <option value="1">热门</option>
                                        <option value="2">休闲</option>
                               </select>
                               <span style="color:red">(显示游戏页面热门位置，只显示50条，剩余则与其它游戏一起显示)</span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">是否为新游<span class="must">*</span></label>
                            <div class="col-sm-9 icheck ">
                                <div class="square-blue">
                                    <div class="radio ">
                                        <div class="iradio_square-blue" style="position: relative;"><input tabindex="3" type="radio" name="new_game" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" checked="checked" value="1"></div>
                                        <label> 是 </label>
                                    </div>
                                </div>
                                <div class="square-red">
                                    <div class="radio ">
                                        <div class="iradio_square-red" style="position: relative;"><input tabindex="3" type="radio" name="new_game" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" value="0"></div>
                                        <label> 否 </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">描述</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="描述"  name="descript" maxlength="50" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">标签</label>
                            <div class="col-sm-5 tagbox">
                                <span>
                                     <?php $label = yii::$app->params['label'];foreach ($label as $k=>$la):?>
                                     <input type="checkbox" name="label[]" value="<?php echo $k;?>" /><?php echo $la;?> 
                                     <?php endforeach;?>
                                 </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">简介</label>
                            <div class="col-sm-5">
                                <textarea rows="10" cols="60" name="intro"></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">详情图片</label>
                             <div class="upload_btn col-sm-10">
                                 <div class="up_img detailimg">
                                    <img src="/media/images/putimg.png">
                                    <input class="input_upimg" type="file" id="upload_image" name="myFile" value="图片上传"
                                         capture="camera">
                                 </div>
                             </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">排序</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="排序,数值越大，越靠前"  name="sort" maxlength="10" />
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">备注</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips"  placeholder="备注"  name="remark" maxlength="255" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">状态<span class="must">*</span></label>
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
        url:'/game/add.html',
        success:function(data){
            if(data){
                if(data.errorcode==0){
                     layer.msg(data.info, {icon: 1,time:2000});
                     setTimeout(function (){
                         location.href="/game/index.html";
                    },1000);
                }else if(data.errorcode==1001){
                       layer.msg(data.info, {icon: 1,time:2000});
                }else{
                       layer.msg('添加失败', {icon: 1,time:2000});
                }
            }
        }
      });
    setTimeout(function (){
        layer.close(index_);
    },2000);
});

/**
 *  异步上传游戏logo
 */
$("#upload_logo").change(function(){
    var this_l = $(this);
    var fd = new FormData(document.getElementById('signupok'));
    var xmlobj = new XMLHttpRequest();
    xmlobj.open('post','/game/filephoto.html?type=1');
    xmlobj.send(fd);
    xmlobj.onload = function(){
        var div = $('<div>').addClass('p_logo delimg').appendTo($('.upload_logo'));
        $('.game_logo').css('display','none'); //上传成功则隐藏
        $('<i>').addClass('fa fa-times-circle fa-2x img_circle ').attr('name','1').appendTo(div);
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


$("[name='unique']").blur(function(){
	   var value = $(this).val();
	   if(value==''){
	      $('.unique').text('唯一标识不能为空');
	   }else{
		   $('.unique').text('');
		   $.ajax({       
		        type:'post',
		        data:{
	                'unique':value,
			        },
		        url:'/game/checkunique.html',
		        success:function(data){
		            if(data==1){
		            	 $('.unique').text('该唯一标识已存在');
			        }
		        }
		    });
	   }
	})
	
$("#f_gamelogo").change(function(){
    var this_l = $(this);
    var fd = new FormData(document.getElementById('signupok'));
    var xmlobj = new XMLHttpRequest();
    xmlobj.open('post','/game/filephoto.html?type=2');
    xmlobj.send(fd);
    xmlobj.onload = function(){
        var div = $('<div>').addClass('p_img f_logoimg delimg').appendTo($('.f_gamelogo_btn'));
        $('.f_gamelogo').css('display','none'); //上传成功则隐藏
        $('<i>').addClass('fa fa-times-circle fa-2x img_circle ').attr('name','3').appendTo(div);
        var data = JSON.parse(xmlobj.responseText);
        if(data[0]){
            var url = window.URL.createObjectURL(this_l['0'].files['0']);
            div.append('<img src="'+url+'"/>');
            div.append('<input type="hidden"  name="fgamelogo" value="'+data['1']+'"/>');
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
            var div = $('<div>').addClass('p_img delimg').appendTo($('.upload_btn'));
            img.src = '/media/images/loading.gif';
            $(img).appendTo(div);
            if ($('.delimg').length >= 1) { //上传成功，并达到上传的张数，则隐藏
                 $('.detailimg').css('display','none');
            }
            if (status) {
                var data = {imgbase64:result.clearBase64};
                $.ajax({        //异步提交数据
                    type:'post',
                    dataType:'json',
                    data:data,
                    url:'/game/subimg.html',
                    async:true,
                    beforeSend:function(){},
                    success:function(data){
                        //成功
                        if(data.errorcode==0){
                            //图片右上角叉叉
                            $('<i>').addClass('fa fa-times-circle img_circle ').attr('name','2').appendTo(div);
                            var save = $(img).appendTo(div);
                            img.src = '/media/images/game/'+data.imgurl;
                            
                            div.append('<input type="hidden"  name="detailimg" value="'+data.imgurl+'"/>');
                            }else{//上传失败
                            layer.msg(data.info);
                            $('.detailimg').css('display','block');
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
  var name = $(this).attr('name');
  var imgsrc = parent.children('img').attr('src');
  var classname = '';
  var imgname = '';
  switch(name){
	case '1': classname = ".game_logo"; imgname = $("input[name='logo']").val();break;      //删除游戏logo
	case '2': classname = ".detailimg"; imgname = $("input[name='detailimg']").val();break;  //删除详情页图片
	case '3': classname = ".f_gamelogo"; imgname = $("input[name='fgamelogo']").val();break;  //删除新版首页游戏logo图片
  }
  if(confirm('确定删除该照片吗？')){
      $.ajax({
          url:'/game/delimg.html',
          type:'post',
          dataType:'json',
          data:{
              'imgsrc':imgname,
              'type':name,
              },
          success:function(data){
              //成功
              if(data.errorcode==0){
                    $(parent).remove();//删除页面上的图片
                    if (parent.length <= 1) { //上传成功，并达到上传的张数，则隐藏
                        $(classname).css('display','block');
                   }
              }else if(data.errorcode==1002){
                    alert(data.info);
              }else{
                 $(parent).remove();//删除页面上的图片
                 if (parent.length <= 1) {
                     $(classname).css('display','block');   
                 }
              }
          }
      })   
  }
})


/**
 * 统计字数
 * @param  字符串
 * @return 数组[当前字数, 最大字数]
 */
function check (str) {
    var num = [0, 50];
    for (var i=0; i<str.length; i++) {
        //字符串不是中文时
        if (str.charCodeAt(i) >= 0 && str.charCodeAt(i) <= 255){
            num[0] = num[0] + 0.5;//当前字数增加0.5个
            num[1] = num[1] + 0.5;//最大输入字数增加0.5个
        } else {//字符串是中文时
            num[0]++;//当前字数增加1个
        }
    }
    return num;
}



//选择
$('.selectbtn').click(function (event){
	$('#dropdownoption').toggle();
	$(document).on('click',function(){//对document绑定一个影藏Div方法
		$('#dropdownoption').hide();
	});
	event.stopImmediatePropagation();
});
$('#dropdownoption').click(function (event){
	event.stopImmediatePropagation();
})
//选择选项
$('.items').click(function(){
	var lival = $(this).text();
	var dataval = $(this).attr('data-value');
	$('.btntxt').text(lival);
	$('.hidden-input').attr('value',dataval);
	$('.hidden-inputvalue').attr('value',lival);
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