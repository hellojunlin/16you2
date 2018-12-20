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
	                    <?php if($managemodel->type!=0):?>
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
	                    			<label class="col-md-2" style="padding:0px;width:150px;"><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:150px;"placeholder="开始时间" value="<?php echo $start_time; ?>" name="start_time">
									</label>
		                           	<label class="col-md-2" style="padding:0px;width:150px;"><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" style="width:150px;"placeholder="结束时间"value="<?php echo $end_time; ?>" name="end_time"></label>
		                            <button class="btn btn-primary" type="submit">搜索</button>
		                            <div class="col-sm-3" style="padding:0px 1px">
		                                <input type="text" class="form-control tooltips"   title="" placeholder="请输入平台名称..."  name="keyword" maxlength="16" value='<?php echo $search;?>' />
		                            </div>
	                            </form>
	                        </div>
	                    </div>
	                    <?php endif;?>
	                </div>
	                <section id="no-more-tables">
	                	<?php if ($data['data']): ?> 
	                	<?php if($managertype!=0):?><div style="color:red;font-weight:bold"><?php echo' 用户量：'.$cuser; ?></div><?php endif;?>
	                		<div class="span6">
	                			<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?=Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
	                		</div>
	                	<?php endif; ?>
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>ID</th>
	                            <th>平台名称</th>
	                            <th>用户量</th>
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
		                             <td>
		                              <button class="btn btn-info btn-xs" onclick="window.location.href='/bean/detacount/<?php echo $v['pid']; ?>.html'"><i class="fa fa-eye"></i>&nbsp;查看详细</button>
		                            </td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                       		<tr><td colspan="4" align="center">暂时没有数据！</td></tr>
	                        <?php endif;?>
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