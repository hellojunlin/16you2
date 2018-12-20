<?php use yii\helpers\Url;
?>
<script type="text/javascript" charset="utf-8" src="/media/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/media/js/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/media/js/ueditor/lang/zh-cn/zh-cn.js"></script>
<div class="page-heading">
    <h3>
       编辑资讯
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">资讯管理</a>
        </li>
        <li class="active"> 编辑资讯 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">编辑资讯</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" action="/consult/create.html" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $model->id; ?>">
                            <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">选择游戏</label>
                            <div class="col-sm-5">
                                <div class="selectdivbox" style="width:100%;">
                                   <input type="text" class="hidden-input" value="0&%#16游平台公告" name="gid" />
									<button type="button" class="btn selectbtn" style="text-align:left;">
										<span class="btntxt">16游平台公告</span>
										<span class="caret"></span>
									</button>
									<div id="dropdownoption" class="dropdown-menu" style="width:100%;">
										<div class="live-filtering">
											<div class="searchinput">
												<input id="searchname" type="text" class="form-control live-search" autocomplete="off" placeholder="搜索关键字">
											</div>
											<div class="list-to-filter">
												<ul class="list-unstyled">
												<li class="filter-item items" data-value="0&%#16游平台公告">16游平台公告</li>
												 <?php if($game): ?>
												  <?php foreach ($game as $v):?>
                                						<?php if(isset($v['id'])): ?>
													<li class="filter-item items" data-value="<?php echo $v['id'].'&%#'.$v['name'];?>" <?php if($v['id']==$model->gid){echo 'id="selectedgame"';}?>><?php echo $v['name'];?></li>
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
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">标题</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="标题" data-original-title="标题" name="title" maxlength="16" value="<?php echo $model->title; ?>" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">标签</label>
                            <div class="col-sm-9 icheck ">
                                <div class="radio ">
                                    <input tabindex="3" type="radio" name="label"  value="公告" <?php if($model->label=='公告'){echo 'checked';} ?>>
                                    <label>公告 </label>
                                </div>
                                <div class="radio ">
                                    <input tabindex="3" type="radio" name="label" value="攻略" <?php if($model->label=='攻略'){echo 'checked';} ?>>
                                    <label>攻略 </label>
                                </div>
                                <div class="radio ">
                                    <input tabindex="3" type="radio" name="label" value="活动" <?php if($model->label=='活动'){echo 'checked';} ?>>
                                    <label>活动 </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"> 资讯内容 </label>
                            <div class="col-sm-9">
                                <textarea name="content" id="editor" style="height:300px" required><?php echo $model->content; ?></textarea>            
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">开始显示时间</label>
                            <div class="col-sm-5">
                                <input class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="开始显示时间" data-original-title="开始显示时间" name="starttime"onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',maxDate:''})" value="<?php echo date('Y-m-d H:i',$model->starttime); ?>"/>
                                <span style="color:red">默认为当前时间</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">排序</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="排序" data-original-title="排序" name="sort" maxlength="5" value="<?php echo $model->sort; ?>" required />
                                <span style="color:red">填数字，数字越大排序越靠前</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">是否置顶</label>
                            <div class="col-sm-9 icheck ">
                                <div class="square-blue">
                                    <div class="radio ">
                                        <div class="iradio_square-blue" style="position: relative;"><input tabindex="3" type="radio" name="type" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" value="1"<?php if($model->type==1){echo 'checked';} ?>></div>
                                        <label> 是 </label>
                                    </div>
                                </div>
                                <div class="square-red">
                                    <div class="radio ">
                                        <div class="iradio_square-red" style="position: relative;"><input tabindex="3" type="radio" name="type" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" value="0" <?php if($model->type==0){echo 'checked';} ?>></div>
                                        <label> 否 </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">状态</label>
                            <div class="col-sm-9 icheck ">
                                <div class="square-blue">
                                    <div class="radio ">
                                        <div class="iradio_square-blue" style="position: relative;"><input tabindex="3" type="radio" name="state" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" <?php if($model->state==1){echo 'checked';} ?> value="1"></div>
                                        <label> 启用 </label>
                                    </div>
                                </div>
                                <div class="square-red">
                                    <div class="radio ">
                                        <div class="iradio_square-red" style="position: relative;"><input tabindex="3" type="radio" name="state" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"<?php if($model->state==0){echo 'checked';} ?> value="0"></div>
                                        <label> 禁用 </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button class="btn btn-primary" type="submit">保 存</button>
                                <button class="btn btn-default" type="reset">重 写</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
	</div>
</section>
<script>
      //选择游戏
     if($("#selectedgame").length>0){
    	var datavalue = $("#selectedgame").attr('data-value');
    	var gname = $("#selectedgame").text();
    	console.log(datavalue);
    	$('.btntxt').html(gname);
    	//$("input[name='gid']").val(datavalue);
    	$('.hidden-input').attr('value',datavalue);
      }

     
    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
     var ue = UE.getEditor('editor',{
    // initialFrameWidth :800,//设置编辑器宽度
    // initialFrameHeight:250,//设置编辑器高度
    scaleEnabled:true
    });
    $("select[name='gid']").change(function(){
        $("input[name='title']").val('《'+($(this).find("option:selected").val().split('&%#'))['1']+'》');
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