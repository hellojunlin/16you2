<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>订单管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">订单记录</a>
        </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">订单记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
	                    			<label><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="开始时间" value="<?php echo $start_time; ?>" name="start_time">
									</label>
		                           	<label><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="结束时间"value="<?php echo $end_time; ?>" name="end_time"></label>
		                          	<label>
                                    <div class="col-lg-4" style="padding:0px">
                                        <select class="form-control m-bot15" name="keyword" id="keyword">
                                            <option value="name">游戏名称</option>
                                            <option value="username">用户名称</option>
                                        </select>
                                    </div>
                                    <button class="btn btn-primary" type="submit">搜索</button>
                                    <div class="col-sm-6" style="padding:0px 1px">
                                        <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入关键字..." data-original-title="请输入关键字" name="value" maxlength="16" value="<?php echo $value; ?>">
                                    </div>
                                </form>
	                        </div>
	                    </div>
	                </div>
	                <section id="no-more-tables">
	                	<?php if ($data['data']): ?> 
	                		<div style="color:red;font-weight:bold"><?php if($count_p){echo '总值总额：'.$count_p.' 元';}?></div>
	                		<div class="span6">
	                			<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?=Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
	                		</div>
	                	<?php endif; ?>
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                       <tr>
	                            <th>平台名</th>
	                            <th>游戏名称</th>
	                            <th>用户名称</th>
	                            <th>道具名称</th>
	                            <th>价格</th>
	                            <th>订单号</th>
	                            <th>下单时间</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
									<td><?= Html::encode($v['pname'])?></td>
		                            <td><?= Html::encode($v['name'])?></td>
		                            <td><?= Html::encode($v['username'])?></td>
		                            <td><?= Html::encode($v['propname'])?></td>
		                            <td><?= Html::encode($v['price'])?></td>
		                            <td><?= Html::encode($v['orderID']) ?></td>
		                            <td><?php echo date('Y-m-d H:i:s',$v['createtime']); ?></td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                    	<tr><td colspan="9" align="center">暂时没有数据</td></tr>
	                    	<?php endif; ?>
	                        </tbody>
	                    </table>
	                </section>
	                <?php if ($data['data']):?>  
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