<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<style>
	.p-fl{
		padding:0 10px 0 0;
	}
</style>
<div class="page-heading">
    <h3>积分管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">积分记录</a>
        </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	积分记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	                    <div class="pull-right col-md-12">
	                    	<div class="form-group">
	                    		<form action="">
                                   
                                    <div class="col-md-2 p-fl"><input class="inputstylebox form-control" placeholder="用户唯一ID..." data-original-title="请输入用户唯一ID"  name="value" value="<?php echo $value ?>"></div>
	                    		    <div class="col-md-2 p-fl">
	                    			<label><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" placeholder="开始时间" value="<?php echo $start_time; ?>" name="start_time">
									</label>
									</div>
									<div class="col-md-2  p-fl">
		                           	<label><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" placeholder="结束时间"value="<?php echo $end_time; ?>" name="end_time"></label>
                                    </div>
                                    <div class="col-md-2  p-fl">
                                        <select class="form-control" name="integraltype" id="integraltype">
                                        	<option value=" ">选择积分类型</option>
                                            <?php foreach (yii::$app->params['integral_type'] as $k=>$v):?>
                                        	<option value="<?php echo $k;?>"><?php echo $v;?></option>
                                        	<?php endforeach;?>
                                        </select>
                                    </div>
                                     <button class="btn btn-primary" type="submit">搜索</button>
                                  </form>
	                        </div>
	                    </div>
	                </div>
	                <section id="no-more-tables">
	               		<div style="color:red;font-weight:bold">总积分：<?php echo isset($totalintegral['totalintegral'])?$totalintegral['totalintegral']:0;?></div>
	                	<?php if ($data['data']):$data['data'] ?> 
	                		<div class="span6">
	                			<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?=Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
	                		</div>
	                	<?php endif; ?>
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>ID</th>
	                            <th>用户头像</th>
	                            <th>用户名称</th>
	                            <th>用户唯一ID</th>
	                            <th>积分类型</th>
	                            <th>积分</th>
	                            <th>创建时间</th>
	                            <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                           <td><?= Html::encode($v['id'])?></td>
		                           <td>
		                            	<?php if($v['head_url']): ?>
		                            	<img src="<?= Html::encode($v['head_url']); ?>" style="width:40px">
										<?php else: ?>
										<img src="/media/images/noimg.jpg" style="width:40px">
										<?php endif; ?>
									</td>
		                            <td><?= Html::encode($v['username']);?></td>
		                            <td><?= Html::encode($v['Unique_ID'])?></td>
		                            <td><?= Html::encode($v['type'])?></td>
		                            <td><?= Html::encode($v['integral'])?></td>
		                            <td><?php echo date('Y-m-d H:i:s',$v['createtime']); ?></td>
		                            <td>
		                                <button class="btn btn-info btn-xs" onclick="window.location.href='/integral/toedit/<?php echo $v['id']; ?>.html'"><i class="fa fa-edit"></i>&nbsp;详 情</button>
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
var integraltype = "<?php echo $integraltype; ?>";
$("#integraltype option[value='"+integraltype+"']").attr('selected',true);
</script>