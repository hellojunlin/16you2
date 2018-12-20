<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>
       用户订单
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">订单流水</a>
        </li>
        <li class="active">用户订单 </li>
    </ul>
</div>
<section style="margin:0 15px">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	订单总览
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	                <section id="no-more-tables">
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>用户名称</th>
	                            <th>总订单</th>
	                            <th>总付费数</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                           <?php if($order):?>
	                        	<tr>
	                                <td><?php echo $username;?></td>
	                                <td><?php echo $order['allnum'];?></td>
	                                <td><?php echo ($order['allprice'])?$order['allprice']:0;?></td>
	                            </tr>
	                            <?php endif;?>
	                        </tbody>
	                    </table>
	                </section>
	            </div>
	        </section>
        </div>
	</div>
</section>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	用户订单	
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                </span>
	                <?php if ($plate): ?>
                	<form action="" style="width:150px;float:right;margin:-7px 5px 0 0" >
                        <select class="form-control m-bot15" name="pid" id="pid">
                            <option value="">请选择平台</option>
                        	<?php foreach ($plate as $vp) {
                        		echo '<option value="'.$vp['id'].'">'.$vp['pname'].'</option>';
                        	} ?>
                        </select>
                	</form>
	                <?php endif ?>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">      
	                    		<form action="">
	                    			<label class="pull-left"><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="开始时间" value="<?php echo $starttime; ?>" name="starttime">
									</label><span class="pull-left" style="padding:0px 10px"> ----</span>
		                           	<label class="pull-left"><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d 23:59:59' })" id="logmax" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="结束时间"value="<?php echo $endtime; ?>" name="endtime"></label>
                                    <label style="padding-left:15px;">
		                            <button class="btn btn-primary" type="submit">搜索</button>
		                            </label>
	                            </form>
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
	                          <th width="100px">游戏名</th>
	                            <th>序号</th>
	                            <th>平台名</th>
	                            <th>价格</th>
	                            <th>数量</th>
	                            <th>总金额</th>
	                            <th>订单时间</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        	<?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k=>$v):?>
	                        	<tr>
	                        	    <td><?php echo $k+1;?></td>
	                               	<td><?php echo $v['name'];?></td>
	                                <td><?php echo $v['pname'];?></td>
	                                <td><?php echo $v['price'];?></td>
	                                <td><?php echo $v['num'];?></td>
	                                <td><?php echo ($v['price']*$v['num']);?></td>
	                                <td><?php echo date('Y-m-d',$v['createtime']);?></td>
	                            </tr>
	                            <?php endforeach;?>
	                          <?php else: ?>
	                    	<tr><td colspan="11">暂时没有数据！</td></tr>
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
<script src="/media/js/layer/layer.js"></script>
<script>
	$('#pid').change(function(){
		var pid = $(this).find("option:selected").val();
		/* if(pid!=-1){*/
			layer.load(); 
			window.location.href= "/user/touserorder/<?php echo $uid;?>.html?name=<?php echo $username;?>&pid="+pid;
		//}
	});
	var ppid = "<?php echo $pid ?>";
	$("#pid option[value='"+ppid+"']").attr('selected',true);
</script>