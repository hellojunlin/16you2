<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
    <h3>
        分成比例设置
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">分成比例设置</a>
        </li>
        <li class="active">默认设置 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	分成比例设置
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
		            <div class="clearfix">
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
                                    <div class="col-lg-4" style="padding:0px">
                                        <select class="form-control m-bot15" name="keyword" id="keyword">
                                            <!-- <option value="name">游戏名称</option> -->
                                            <option value="C.compname">公司名称</option>
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
	                		<div class="span6">
	                			<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?= Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
	                		</div>
	                	<?php endif; ?>
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>游戏名称</th>
	                            <th>所属公司</th>
	                            <th>游戏方分成比例（%）</th>
	                            <th>创建时间</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td>
		                            	<?php if($v['gname']): ?>
		                            		<?php foreach ($v['gname'] as $key => $val) {
		                            			echo $val.'、';
		                            		} ?>
		                            	<?php else: ?>
		                            		暂时没有游戏
		                            	<?php endif; ?>
		                            </td>
		                            <td><?= Html::encode($v['compname']) ?></td>
		                            <td><?= Html::encode($v['proportion']) ?></td>
		                            <td class="numeric" data-title="High"><?= Html::encode(date('Y-m-d H:i:s',$v['createtime'])) ?></td>
		                        </tr>
	                        	<?php endforeach;?>
	                    	<?php else: ?>
	                    	<tr><td colspan="4">暂时没有数据！</td></tr>
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