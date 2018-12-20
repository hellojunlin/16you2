<?php use yii\helpers\Url;
?>
<link rel="stylesheet" href="/media/css/combo.select.css">
<link rel="stylesheet" type="text/css" href="/media/css/bootstrap-fileupload.min.css" />
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<style>
.must{
    color:#FF0000;
}
</style>
<div class="page-heading">
    <h3>
开服记录管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">开服记录</a>
        </li>
        <li class="active">添加记录</li>
    </ul>
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">添加记录</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" id='signupok' enctype="multipart/form-data">
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
													<li class="filter-item items" data-value="<?php echo $v['id'];?>"><?php echo $v['name'];?></li>
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
                       <!--  
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">选择游戏</label>
                            <div class="col-sm-5">
                               <select class="form-control tooltips" tabindex="3" name="gid">
                                <?php foreach ($game as $v):?>
                                <?php if(isset($v['id'])): ?>
                                <option value="<?php echo $v['id'];?>"><?php echo  $v['id'].'---'.$v['name'];?></option>
                               <?php endif; ?>
                                <?php endforeach;?>
                               </select>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">区号<span class="must">*</span></label>
                               <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="区号"  name="service_code" maxlength="100" required />
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">开服时间<span class="must">*</span></label>
                            <div class="col-sm-5">
                                <input class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="开服时间" data-original-title="开服时间" name="opentime"onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',maxDate:'',isShowClear:false,isShowToday:false})"/>
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
<script src="/media/js/jquery.combo.select.js"></script>
<script>  
    $(function() {
        $('select[name="gid"]').comboSelect();
    });  
    //异步提交数据
    $('#submit').click(function(){
        var index_ = layer.load();
         $.ajax({       
                type:'post',
                dataType:'json',
                data:$('#signupok').serialize(),
                url:'/service/add.html',
                success:function(data){
                    if(data){
                            if(data.errorcode==0){
                                 layer.msg(data.info, {icon: 1,time:2000});
                                 setTimeout(function (){
                                     location.href="/service/index.html";
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