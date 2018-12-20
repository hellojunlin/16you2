<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<style>
td {
    word-wrap: break-word;
    word-break: break-all;
}
.inputstylebox{
	height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
	width:130px;
}
</style>
<div class="page-heading">
    <h3>app数据统计</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">数据统计</a>
        </li>
    </ul>
</div>
<section style="margin:0 15px">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel panel-primary">
	            <header class="panel-heading">
	                app数据统计
	                <span class="tools pull-right">
	                    <a href="http://mta.qq.com/"  target="_blank">腾讯统计平台</a>
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	                <section id="no-more-tables">
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>app总下载次数</th>
	                            <th>app总付费人数</th>
	                            <th>app总付费金额(元)</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        	<tr>
	                                <td><?php echo isset($payarr['downloadnum'])?$payarr['downloadnum']:0;?></td>
	                                <td><?php echo isset($payarr['paynum'])?$payarr['paynum']:0;?></td>
	                                <td><?php echo isset($payarr['payprice'])?$payarr['payprice']:0;?></td>
	                            </tr>
	                        </tbody>
	                    </table>
	                </section>
	            </div>
	        </section>
        </div>
	</div>
</section>

<section style="margin:0 15px;">
	<div class="row" >
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	                                             下载详情
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            <div class="clearfix">
	                    <div class="btn-group col-md-12" style="padding:5px 0px;">
	                    	<div class="form-group">
	                    		<form action="">
                                	<div class="col-md-2">
	                    			<label style="padding:0px;width:150px;"><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:150px;"placeholder="开始时间" value="<?php echo $start_time; ?>" name="start_time">
									</label>
									</div>
									
									<div class="col-md-2">
		                           	<label style="padding:0px;width:150px;"><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" style="width:150px;"placeholder="结束时间"value="<?php echo $end_time; ?>" name="end_time"></label>
                                    </div>
                                    <div class="col-md-2">
                                    <button class="btn btn-primary" type="submit" style="margin-left:5px;">搜索</button>
                               		</div>
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
	                         <?php if($data['data']): ?>
	                        <tr>
	                            <th>序号</th>
	                            <th>时间</th>
	                            <th>app下载数</th>
	                            <th>付费人数</th>
	                            <th>付费金额</th>
	                        </tr>
	                        </thead>
	                        
	                        <tbody>
	                        <?php $index= 1;if($istoday):$index=2;?>
	                           <tr>
	                        	    <th><?php echo 1;?></th>
	                                <td><?php echo isset($todaypayarr['downloaddate'])?date('y-m-d',$todaypayarr['downloaddate']):0;?></td>
	                                <td><?php echo isset($todaypayarr['downloadnum'])?$todaypayarr['downloadnum']:0;?></td>
	                                <td><?php echo isset($todaypayarr['paynum'])?$todaypayarr['paynum']:0;?></td>
	                                <td><?php echo isset($todaypayarr['payprice'])?$todaypayarr['payprice']:0;?></td>
	                            </tr>
	                        <?php endif;?>
	                        <?php foreach ($data['data'] as $k => $v):?> 
	                        	<tr>
	                        	    <th><?php echo $k+$index;?></th>
	                                <td><?php echo date('y-m-d',$v['createtime']);?></td>
	                                <td><?php echo $v['num'];?></td>
	                                <td><?php echo $v['pay_num'];?></td>
	                                <td><?php echo $v['pay_price'];?></td>
	                            </tr>
	                             <?php endforeach;?>
	                        </tbody>
	                       <?php endif;?>
	                    </table>
	                </section>
	            </div>
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
	</div>
</section>

