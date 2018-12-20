<?php 
use yii\helpers\Url;
?>
<div class="page-heading">
    <h3>配置管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">配置管理</a>
        </li>
        <li class="active"> 添加 </li>
    </ul>
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">配置管理</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" action="<?=Url::to(['create'])?>" enctype="multipart/form-data">
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
                       
                       <!--<div class="form-group">
                            <label class="col-sm-2 control-label">所属游戏</label>
                            <div class="col-sm-5">
                                <select class="form-control m-bot15" name="gid">
                                    <?php foreach ($game as $v) {
                                        echo '<option value="'.$v['id'].'">'.$v['id'].'---'.$v['name'].'</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>  --> 
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 支付通知地址</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="支付通知地址" data-original-title="支付通知地址" name="type_url" required/><span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 有效域名</label>
                            <div class="col-sm-5">
                                <input type="url" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="有效域名" data-original-title="有效域名" name="api_url"/><span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10 col-sm-offset-2 col-sm-10">
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