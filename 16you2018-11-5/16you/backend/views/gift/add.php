<?php 
use yii\helpers\Url;
?>
<link href="/media/css/common.css" rel="stylesheet">
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>礼包管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">礼包管理</a>
        </li>
        <li>
            <a href="/gift/index.html">礼包列表</a>
        </li>
        <li class="active"> 添加 </li>
    </ul> 
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">礼包管理</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" action="<?=Url::to(['create'])?>" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 礼包名称</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="礼包名称" data-original-title="礼包名称" name="gift_name" maxlength="16" required/>
                            </div>
                        </div>
 					<div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><span style="color:#e00">*</span>选择游戏</label>
                            <div class="col-sm-5">
                                <div class="selectdivbox" style="width:100%;">
                                   <input type="text" class="hidden-input" value="" name="game_name" />
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
													<li class="filter-item items" data-value="<?php echo $v['name'].'%$#'.$v['head_img'].'%$#'.$v['id'];?>"><?php echo $v['name'];?></li>
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
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 礼包内容</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="礼包内容" data-original-title="礼包内容" name="content" maxlength="100" required />
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">有效期</label>
                            <div class="col-sm-5">
                                <input class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="有效期" data-original-title="开服时间" name="validtime"onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:''})"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">礼包类型</label>
                            <div class="col-sm-5">
                               <select class="form-control tooltips" tabindex="3" name="gifttype">
                                <option value="0">新手</option>
                                <option value="1">节日</option>
                                <option value="2">活动</option>
                                <option value="3">首发</option>
                                <option value="4">入群</option>
                               </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 领取方式</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="领取方式" data-original-title="领取方式" name="payment" maxlength="150"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span>礼包兑换码</label>
                            <div class="col-sm-5">
                                <textarea rows="6" class="form-control" name="CDKEY" id="giftcode"required></textarea>
                                <span style="color:red">提示：每一行为一个激活码,当前的行数是<span class="codenum">0</span></span>
                            </div>
                        </div>
                                                <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><span style="color:#e00">*</span>用途</label>
                            <div class="col-sm-9 icheck ">
                                <div class="square-blue">
                                    <div class="radio ">
                                        <div class="iradio_square-blue" style="position: relative;"><input tabindex="3" type="radio" name="type" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" checked="checked" value="1"></div>
                                        <label> 游戏礼包 </label>
                                    </div>
                                </div>
                                <div class="square-red">
                                    <div class="radio ">
                                        <div class="iradio_square-red" style="position: relative;"><input tabindex="3" type="radio" name="type" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" value="2"></div>
                                        <label> 邮件 </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10 col-sm-offset-2 col-sm-10">
                                <button class="btn btn-primary" type="submit" id="submit">保 存</button>
                                <button class="btn btn-default" type="reset">重 写</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</section>
<script src="/media/js/layer/layer.js"></script>
<script>
$('#submit').click(function(){
    if($('input[name="gift_name"]').val()||$('input[name="CDKEY"]').val()||$('input[name="content"]').val()){
        layer.load();
    }
})

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
	
	$('#giftcode').blur(function(){
		var length = $("#giftcode").val().split("\n").length;
		$('.codenum').text(length);
    })
	
</script>