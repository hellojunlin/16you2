<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<style>
th {
    white-space: nowrap;
}
td{
	word-break:break-all;
}

</style>
<div class="page-heading">
    <h3>
        微信分享管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">微信分享管理</a>
        </li>
        <li class="active">微信分享记录 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	微信分享管理
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>	
	            <div class="panel-body">
	            	<div class="clearfix">    
	            		<div class="btn-group">
		                    <button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['toadd'])?>'"><i class="fa  fa-plus"></i> 添加分享记录 </button>&nbsp;
	                    </div>
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="<?=Url::to(['index'])?>">
		                            <button class="btn btn-primary" type="submit">搜索</button>
		                            <input type="text" class="hidden-input" value="<?php echo $gid?>" name="gid" />
                                    <input type="hidden" class="hidden-inputvalue" value="<?php echo $gname?>" name="gname" />
		                            <div class="col-md-10" style="padding:0px;">
			                            <?php if($game): ?>
	                                    <div class="col-md-6" style="padding:0px">
	                                    	<div class="selectdivbox">
												<button type="button" class="btn selectbtn">
													<span class="btntxt"><?php echo ($gname)?$gname:'选择游戏'?></span>
													<span class="caret"></span>
												</button>
												<div id="dropdownoption" class="dropdown-menu">
													<div class="live-filtering">
														<div class="searchinput">
															<input id="searchname" type="text" class="form-control live-search" autocomplete="off">
														</div>
														<div class="list-to-filter">
															<ul class="list-unstyled">
																 <?php foreach ($game as $_g):?>
						                                            <li class="filter-item items" data-value="<?php echo $_g['id']; ?>"><?php echo $_g['name']; ?></li>
						                                         <?php endforeach; ?>
															</ul>
															<div class="no-search-results">搜索不到结果</div>
														</div>
													</div>
												</div>
											</div>
	                                        <!-- <select class="form-control m-bot15" name="pid" id="pid">
	                                        	<option value=" ">选择游戏</option>
	                                        	<?php foreach ($game as $_p):?>
	                                            <option value="<?php echo $_p['id']; ?>"><?php echo $_p['name']; ?></option>
	                                        	<?php endforeach; ?>
	                                        </select> -->
	                                    </div>
	                                	<?php endif; ?>
		                                <div class="col-md-4" style="padding:0px"><input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入微信分享标题..." data-original-title="请输入微信分享标题" name="keyword" maxlength="16" value='<?php echo $value; ?>'/></div>
		                            </div>
	                            </form>
	                        </div>
	                    </div>
	                </div>
	                <section id="no-more-tables">
	                	<?php if ($data['data']): ?> 
	                		<div class="span6">
	                			<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?= Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
	                		</div>
	                	<?php endif; ?>
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>游戏名称</th>
	                            <th>标题</th>
	                            <th>描述</th>
	                            <!-- <th>图标</th> -->
	                            <th>创建时间</th>
	                            <th>操作 </th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td><?= Html::encode($v['gamename']) ?></td>
		                            <td><?= Html::encode($v['title']) ?></td>
		                            <td><?= Html::encode($v['desc']) ?></td>
		                            <td class="numeric" data-title="High"><?= Html::encode(date('Y-m-d H:i:s',$v['createtime'])) ?></td>
		                        	<td>
		                        	    <button class="btn btn-info btn-xs" onclick="window.location.href='/wxshare/toedit/<?php echo $v['id']?>.html'"><i class="fa fa-edit"></i>&nbsp;编 辑</button>
			                            <button class="btn btn-danger btn-xs del" id="<?php echo $v['id']?>"><i class="fa fa-trash-o"></i>&nbsp;删 除</button>
		                        	</td>
		                        </tr>
	                        	<?php endforeach;?>
	                    	<?php else: ?>
	                    	<tr><td colspan="11" align="center">暂时没有数据！</td></tr>
	                    	<?php endif; ?>
	                        </tbody>
	                    </table>
	                </section>
	                <?php if ($data['data']): ?>  
	                	<div class="span6">
	                		<div class="dataTables_paginate paging_bootstrap pagination" style="margin:0;padding:0;">
	                    		<?php echo LinkPager::widget(['pagination' => $pages]);?>
	                    	</div>
	                    </div>
	                </div>
	                <?php endif; ?>
	            </div>
	        </section>
        </div>
	</div>
</section>
<script>
	//删除平台
	$('.del').click(function(){
		var athis = $(this);
		 var id = $(this).attr('id');
		 layer.confirm('确认要删除吗？',function(){
			$.ajax({
		        url:"/wxshare/del.html",
		        type:'post',
		        dataType:'json',
		        data:{'id':id},
		        success:function(data){
			        if(data.errorcode=='0'){
			        	layer.msg(data.info, {icon: 1,time:2000});
			           	athis.parent().parent('tr').remove();
				    }else if(data.errorcode=='1001'){
				    	layer.msg(data.info, {icon: 1,time:2000});
					}else{
					    layer.msg(data.info, {icon: 1,time:2000});
					}
		        	 
		        }
		    }) 
		})
	})
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
		var searchname =  $.trim($('#searchname').val());
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