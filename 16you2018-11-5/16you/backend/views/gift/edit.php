<?php 
use yii\helpers\Url;
?>
<link href="/media/css/common.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/media/css/bootstrap-fileupload.min.css" />
<div class="page-heading">
    <h3>礼包管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">礼包管理</a>
        </li>
        <li>
            <a href="/gift/index.html">礼包列表</a>
        </li>
        <li class="active"> 编辑 </li>
    </ul> 
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">礼包管理</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" action="<?=Url::to(['update'])?>" enctype="multipart/form-data">
                        <input type="hidden" name="number" value="<?php echo $model->number; ?>">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 礼包名称</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="礼包名称" data-original-title="礼包名称" name="gift_name" maxlength="16" value="<?php echo $model->gift_name; ?>" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">选择游戏</label>
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
													<li class="filter-item items" data-value="<?php echo $v['name'].'%$#'.$v['head_img'].'%$#'.$v['id'];?>" <?php if($v['id']==$model->gid){echo 'id="selectedgame"';}?>><?php echo $v['name'];?></li>
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
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="礼包内容" data-original-title="礼包内容" name="content" maxlength="100" value="<?php echo $model->content; ?>" required />
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">有效期</label>
                            <div class="col-sm-5">
                                <input class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="有效期" data-original-title="开服时间" name="validtime"onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:''})" value="<?php echo ($model->validtime)?date('Y-m-d',$model->validtime):'';?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">礼包类型</label>
                            <div class="col-sm-5">
                               <select class="form-control tooltips" tabindex="3" name="gifttype">
                                <option value="0" <?php echo ($model->gifttype==0)?'selected':'';?>>新手</option>
                                <option value="1" <?php echo ($model->gifttype==1)?'selected':'';?>>节日</option>
                                <option value="2" <?php echo ($model->gifttype==2)?'selected':'';?>>活动</option>
                                <option value="3" <?php echo ($model->gifttype==3)?'selected':'';?>>首发</option>
                                <option value="4" <?php echo ($model->gifttype==4)?'selected':'';?>>入群</option>
                               </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 领取方式</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="领取方式" data-original-title="领取方式" name="payment" maxlength="150" value="<?php echo $model->payment; ?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 激活码</label>
                            <div class="col-sm-5">
                                <textarea rows="6"  id="giftcode" class="form-control" name="CDKEY" required><?php if(is_array($model->CDKEY)): ?><?php foreach($model->CDKEY AS $cc){if($cc['state']==0){echo $cc["CDKEY"]."\r\n";}} ?><?php endif; ?></textarea>
                                <span style="color:red">提示：每一行为一个激活码,当前的行数是<span class="codenum">0</span></span>
                                <button class="btn btn-info btn-xs delt">查看礼包详情</button></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><span style="color:#e00">*</span>用途</label>
                            <div class="col-sm-9 icheck ">
                                <div class="square-blue">
                                    <div class="radio ">
                                        <div class="iradio_square-blue" style="position: relative;"><input tabindex="3" type="radio" name="type" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"  <?php if($model->type=='1'){echo 'checked="checked"';} ?> value="1"></div>
                                        <label> 游戏礼包 </label>
                                    </div>
                                </div>
                                <div class="square-red">
                                    <div class="radio ">
                                        <div class="iradio_square-red" style="position: relative;"><input tabindex="3" type="radio" name="type" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" value="2" <?php if($model->type=='2'){echo 'checked="checked"';} ?>></div>
                                        <label> 邮件 </label>
                                    </div>
                                </div>
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
<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="display:none;background:rgba(0,0,0,0.5);">
    <div class="modal-dialog" style="width:40%;overflow-y:scroll">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">礼包领取详情</h4>
            </div>
            <div class="modal-body row">
                <div class="panel-body">
                    <div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px"></div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>激活码</th>
                            <th>状态</th>
                        </tr>
                        </thead>
                        <tbody class="tbod">
                            <?php foreach($model->CDKEY AS $cc):?>
                                <tr>
                                    <td><?php echo $cc['CDKEY']; ?></td>
                                    <td>
                                        <?php if($cc['state']==1):?>
                                        <button class="btn btn-success btn-xs statebtn" name="1">&nbsp;已领用</button>
                                        <?php else:?>
                                        <button class="btn btn-warning btn-xs statebtn" name="0">&nbsp;未领用</button>
                                        <?php endif;?> 
                                    </td>
                                    <td></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/media/js/bootstrap-fileupload.min.js"></script>
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
 
    $('.delt').click(function(){
        $('#myModal').show();
        return false;
    })
    $('.close').click(function(){
        $('#myModal').hide();
        return false;
    })
    //启用  禁用
    $('.modal').on('click','.statebtn',function(){
        var _this = $(this);
        var state = _this.attr('name');
        var CDKEY = _this.parents('tr').children().eq(0).html();
        $.ajax({
            url:"/gift/changestate.html",
            type:'post',
            data:{'CDKEY':CDKEY},
            success:function(data){
                if(data==1){
                    if(state==0){//
                        _this.html('已领用').removeClass('btn-warning').addClass('btn-success').attr('name',1);
                    }else{
                        _this.html('未领用').removeClass('btn-success').addClass('btn-warning').attr('name',0);
                    }
                }else{
                    layer.msg('修改失败');
                }
            }
        }) 
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
	
	var length = $("#giftcode").val().split("\n").length;
	$('.codenum').text(length-1);
		
	$('#giftcode').blur(function(){
		var length = $("#giftcode").val().split("\n").length;
		$('.codenum').text(length);
    })
</script>