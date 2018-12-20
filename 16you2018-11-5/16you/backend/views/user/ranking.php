<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>
        用户管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">用户管理</a>
        </li>
        <li class="active">用户排行榜 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	用户排行榜 	
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">      
                		<form action="" style="display:inline-block;width:80%;">
                		    <?php if($plate && $managemodel->type!=0): ?>
                            <div class="col-md-2" style="padding:0px">
                                <select class="form-control m-bot15" name="pid" id="pid">
                                	<option value=" ">选择平台</option>
                                	<?php foreach ($plate as $_p):?>
                                    <option value="<?php echo $_p['id'];?>" <?php echo ($_p['id']==$pid)?'selected':'';?>><?php echo $_p['pname']; ?></option>
                                	<?php endforeach; ?>
                                </select>
                            </div>
	                        <?php endif; ?>
                			<label class="pull-left"><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="开始时间" value="<?php echo $starttime; ?>" name="starttime">
							</label> <span class="pull-left" style="padding:0px 10px"> ----</span>
                           	<label class="pull-left"><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="结束时间"value="<?php echo $endtime; ?>" name="endtime"></label>
                            <!-- <label  style="padding-left:15px;"> -->
                            <div class="col-md-2" style="padding:0px 1px">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入用户名称..." data-original-title="请输入用户名称" name="value" maxlength="16" value="<?php echo $value;?>">
                            </div>
                            <div class="col-md-2" style="padding:0px 1px">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入用户ID..." data-original-title="请输入用户ID" name="UniqueID" maxlength="8" value="<?php echo $Unique_ID;?>">
                            </div>
                            <button class="btn btn-primary" type="submit">搜索</button>
                            <!-- </label> -->
                        </form>
	                    <button class="btn btn-warning" onclick="download()"><i class="fa fa-cloud-download"></i> 导出Excel </button>    
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
	                            <th>序号</th>
	                            <th>用户ID</th>
	                            <th>用户名称</th>
	                            <th>总付款订单数
	                                <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="该用户已付款订单总数">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>总付款金额
	                                <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="该用户已付款订单的总金额">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                         	<?php if($data['data']): ?>
	                            <?php foreach ($data['data'] as $k=>$d):?>
	                            <tr>
	                               	<td><?php echo $k+1;?></td>
	                               	<td><?php echo $d['Unique_ID']; ?></td>
	                               	<td><?php echo $d['username']; ?></td>
	                                <td><?php echo $d['Cid'];?></td>
	                                <td><?php echo $d['Sprice'];?></td>
	                            </tr>
	                            <?php endforeach;?>
	                       		<?php else: ?>
	                       		<tr><td colspan="5" align="center">暂时没有数据！</td></tr>
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
$(function() {
	$('#pid').comboSelect();
});
//导出弹框，iframe层-禁滚动条
function download(){
	var starttime = $("input[name='starttime']").val();
	var endtime = $("input[name='endtime']").val();
	var Unique_ID = $("input[name='UniqueID']").val();
	var pid = $("select[name='pid'] option:selected").val();
	var value = $("input[name='value']").val();
	 window.location.href="/user/rankingoutput.html?starttime="+starttime+'&endtime='+endtime+'&Unique_ID='+Unique_ID+'&value='+value+'&pid='+pid;
}
</script>