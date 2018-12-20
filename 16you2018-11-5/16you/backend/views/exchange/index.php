<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
    <h3>兑换管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">兑换记录</a>
        </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	兑换记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
                                    <button class="btn btn-primary" type="submit">搜索</button>
                                    <div class="col-md-2 box-r-margin" style="width:210px;padding:2;"><input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入用户名称..." data-original-title="请输入用户名称" name="value" maxlength="16" value="<?php echo $value;?>"></div>
	                    		    <div class="col-md-2 box-r-margin" style="width:210px;padding:2;">       <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入兑换码.." data-original-title="请输入兑换码" name="getcode" maxlength="16" value="<?php echo $getcode;?>"></div>
                                </form>
	                        </div>
	                    </div>
	                </div>
	                <section id="no-more-tables">
	                	<?php if ($data['data']):$data['data'] ?> 
	                		<div class="span6">
	                			<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?=Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
	                		</div>
	                	<?php endif; ?>
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>ID</th>
	                            <th>用户名称</th>
	                            <th>商品名称</th>
	                            <th>积分</th>
	                            <th>兑换码</th>
	                            <th>是否处理</th>
	                            <th>创建时间</th>
	                            <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td><?= Html::encode($v['id'])?></td>
		                            <td><?= Html::encode($v['username'])?></td>
		                            <td><?= Html::encode($v['product_name'])?></td>
		                            <td><?= Html::encode($v['integral'])?></td>
		                            <td><?= Html::encode($v['getcode'])?></td>
		                            <?php if($v['isdispose']==0):?>
		                            <td class="statval"><label class="label label-warning">未处理</label></td>
		                            <?php else:?>
		                            <td class="statval"><label class="label label-success">已处理</label></td>
		                            <?php endif;?>
		                            <td><?php echo date('Y-m-d',$v['createtime']); ?></td>
		                            <td>
		                                <button class="btn btn-info btn-xs" onclick="window.location.href='/exchange/toedit/<?php echo $v['id']; ?>.html'" style="display:none;"><i class="fa fa-edit"></i>&nbsp;详 情</button>
		                                <?php if($v['isdispose']==0):?>
				                        <button class="btn btn-success btn-xs statebtn" id="<?php echo $v['id']?>" name="1"><i class="fa fa-check"></i>&nbsp;已处理</button>
				                        <?php else:?>
				                        <button class="btn btn-warning btn-xs statebtn" id="<?php echo $v['id']?>" name="0"><i class="fa fa-times"></i>&nbsp;未处理</button>
				                        <?php endif;?>
		                            </td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                    	<tr><td align="center" colspan='20'>暂时没有数据</td></tr>
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
	$('body').on('click','.statebtn',function(){
		var btntext = $(this).html('<i class="fa fa-spinner fa-pulse" style="width:51.44px;"></i>');
		var _this = $(this);
		var id = _this.attr('id');
		var state = _this.attr('name');
		$.ajax({
	        url:"/exchange/changeisdispose.html",
	        type:'post',
	        dataType:'json',
	        data:{'state':state,'id':id},
	        success:function(data){
	            if(data.errorcode==0){
					if(state==0){//禁用
				    	_this.removeClass('btn-warning').addClass('btn-success').attr('name','1').html('<i class="fa fa-check "></i>&nbsp;已处理');
						_this.parents('tr').find('.statval').html('<span class="label label-warning">未处理</span>');
					}else{
						_this.removeClass('btn-success').addClass('btn-warning').attr('name','0').html('<i class="fa fa-times"></i>&nbsp;未处理');
						_this.parents('tr').find('.statval').html('<span class="label label-success">已处理</span>');
					}
	            }else{
	            	alert(data.info);
	            }
	        }
	    }) 
	})
</script>