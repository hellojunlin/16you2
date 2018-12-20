<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>活动管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['dogfood']);?>">狗粮记录</a>
        </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	狗粮记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	                 <p style="color:red;font:bold;margin-top:5px;margin-bottom:-5px">已兑换总金额：<?php echo $price; ?> 元</p>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="<?=Url::to(['dogfood'])?>">
                    				<div class="col-md-4" style="padding:0px 1px">
                                    	<input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入用户ID" data-original-title="请输入用户ID" name="keyword" maxlength="16" value="<?php echo $value;?>">
                                	</div>
	                    			<label class="pull-left"><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="开始时间" value="<?php echo $starttime; ?>" name="starttime">
									</label>
		                           	<label class="pull-left"><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="结束时间"value="<?php echo $endtime; ?>" name="endtime"></label>
		                            <button class="btn btn-primary" type="submit">搜索</button>
	                            </form>
	                        </div>
	                    </div>
	                </div>
	                <section id="no-more-tables">
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>用户ID</th>
	                            <th>用户名称</th>
	                            <th>邀请奖励</th>
	                            <th>充值奖励</th>
	                            <th>完善信息奖励</th>
	                            <th>奖励合计</th>
	                            <th>已兑换金额</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($res): ?>
	                        	<?php foreach ($res as $k => $v):?> 
								<tr>
		                            <td><?= Html::encode($v['Unique_ID'])?></td>
		                            <td><?= Html::encode($v['username'])?></td>
		                            <td><?= Html::encode(!$v['type']?0:(isset($v['type']['1'])?$v['type']['1']:0))?></td>
		                            <td><?= Html::encode(!$v['type']?0:(isset($v['type']['3'])?$v['type']['3']:0))?></td>
		                            <td><?= Html::encode(!$v['type']?0:(isset($v['type']['2'])?$v['type']['2']:0))?></td>
		                            <td><?= Html::encode($v['sumnum'])?></td>
		                            <td><?= Html::encode($v['price'])?></td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                    	<tr><td colspan="7"  align="center">暂时没有数据！</td></tr>
	                    	<?php endif; ?>
	                        </tbody>
	                    </table>
	                </section>
	            </div>
	        </section>
        </div>
	</div>
</section>