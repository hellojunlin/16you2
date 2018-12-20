<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>
              平台管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">统计记录</a>
        </li>
    </ul> 
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	统计记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	                    <div class="btn-group">
	                    </div>
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
		                             <label><input id="start_time" class="form-control tooltips input-text Wdate" type="text"  value="<?php  echo isset($start_time)?$start_time:'';?>" name="start_time" style="width: 170px;"
											onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',maxDate:''})" placeholder="开始时间"/>
										   </label>
		                            	   <label><input id='end_time' class="form-control tooltips input-text Wdate" type="text"  value="<?php echo isset($end_time)?$end_time:'';?>" name="end_time" style="width: 170px;"
											onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',maxDate:''})" placeholder="结束时间"/></label>
		                          	<label class="pull-right">
		                          	<?php if($managemodel->role==-1):?>
		                            <div class="col-sm-9" style="padding:0px 1px">
		                                <input type="text" class="form-control tooltips"   title="" placeholder="请输入平台名称..."  name="keyword" maxlength="16" value='<?php echo $search;?>' />
		                            </div>
		                            <?php endif;?>
		                            <button class="btn btn-primary" type="submit">搜索</button>
		                            </label>
	                            </form>
	                        </div>
	                    </div>
	                </div>
	                <section id="no-more-tables">
	                	<?php if ($data['data']): ?> 
	                		<div class="span6">
	                			<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?=Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
	                		</div>
	                	<?php endif; ?>
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>ID</th>
	                            <th>平台名称</th>
	                            <th>订单量</th>
	                            <th>交易金额(元)</th>
	                            <th>备注</th>
	                            <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td><?php echo $k+1;?></td>
		                            <td><?= Html::encode($v['pname'])?></td>
		                            <td><?= Html::encode($v['num'])?></td>
		                            <td><?= Html::encode($v['price'])?></td>
		                            <td><?= Html::encode($v['remark'])?></td>
		                             <td>
		                              <button class="btn btn-info btn-xs" onclick="window.location.href='/platform/detacount/<?php echo $v['pid']; ?>.html'"><i class="fa fa-eye"></i>&nbsp;查看详细</button>
		                              <button class="btn btn-warning btn-xs statebtn" onclick="window.location.href='/platform/orderc/<?php echo $v['pid'] ?>.html'"><i class="fa fa-shopping-cart"></i>&nbsp;订单流水</button>
		                            </td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                    	<!-- 暂时没有数据 -->
	                    	<tr><td colspan="20" align="center">暂时没有数据！</td></tr>
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


</script>