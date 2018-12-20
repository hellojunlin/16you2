<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<style>
td {
    word-wrap: break-word;
    word-break: break-all;
}
</style>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>游戏管理</h3>
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
	                    <div class=" btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
		                    		<?php if ($companyarr && $managemodel->type!=0): ?>
										<div class="col-md-3" style="padding:0px;width:12.8%">
                                        <div class="selectdivbox">
                                        	 <input type="hidden" class="hidden-input hidden-input-0" value="<?php echo $cid?>" name="cid" id="cid"/>
                                        	 <input type="hidden" class="hidden-inputvalue hidden-inputvalue-0" value="<?php echo $cname?>" name="cname" />
											<button type="button" class="btn selectbtn selectbtn-0">
												<span class="btntxt btntxt-0"><?php echo ($cname)?$cname:'选择游戏商'?></span>
												<span class="caret"></span>
											</button>
											<div id="dropdownoption" class="dropdown-menu dropdownoption-0">
												<div class="live-filtering">
													<div class="searchinput">
														<input id="searchname" type="text" class="form-control live-search" autocomplete="off">
													</div>
													<div class="list-to-filter">
														<ul class="list-unstyled found">
														 <?php if($companyarr): ?>
														    <li class="filter-item items items-0" data-value="">选择游戏商</li>
						                                    <?php foreach ($companyarr as $_g):?>
						                                    <li class="filter-item items-0" data-value="<?php echo $_g['id']; ?>"><?php echo $_g['compname']; ?></li>
						                                    <?php endforeach; ?>
						                                <?php endif; ?>
														</ul>
														<div class="no-search-results no-found">搜索不到结果</div>
													</div>
												</div>
										   </div>
										</div>
                                    </div>
					                <?php endif ?>
		                    	    <?php if ($plate && $managemodel->type!=0): ?>
				                        <div class="col-md-3" style="padding:0px;width:12.8%">
                                        <div class="selectdivbox">
                                        	 <input type="text" class="hidden-input hidden-input-1" value="<?php echo $pid?>" name="pid" id="pid"/>
                                        	 <input type="hidden" class="hidden-inputvalue hidden-inputvalue-1" value="<?php echo $pname?>" name="pname" />
											<button type="button" class="btn selectbtn selectbtn-1">
												<span class="btntxt btntxt-1"><?php echo ($pname)?$pname:'选择平台'?></span>
												<span class="caret"></span>
											</button>
											<div id="dropdownoption" class="dropdown-menu dropdownoption-1">
												<div class="live-filtering">
													<div class="searchinput">
														<input id="searchname1" type="text" class="form-control live-search" autocomplete="off">
													</div>
													<div class="list-to-filter">
														<ul class="list-unstyled found1">
														 <?php if($plate): ?>
														            <li class="filter-item items items-1" data-value="">选择平台</li>
						                                            <?php foreach ($plate as $_g):?>
						                                            <li class="filter-item items items-1" data-value="<?php echo $_g['id']; ?>"><?php echo $_g['pname']; ?></li>
						                                            <?php endforeach; ?>
						                                    <?php endif; ?>
														</ul>
														<div class="no-search-results no-found1">搜索不到结果</div>
													</div>
												</div>
										   </div>
										</div>
                                    </div>
					                <?php endif ?>
		                            <label class="pull-left"><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="开始时间" value="<?php echo $starttime; ?>" name="starttime">
									</label> <span class="pull-left" style="padding:0px 10px"> ----</span>
		                           	<label class="pull-left"><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="结束时间"value="<?php echo $endtime; ?>" name="endtime"></label>
                                    <label  style="padding-left:15px;">
                                    <input type="text" class="form-control tooltips"   title="" placeholder="请输入游戏名称..."  name="keyword" maxlength="16" value='<?php echo $search;?>' id="keyword"/>
		                            </label>
		                             <button class="btn btn-primary" type="submit">搜索</button>
		                             <button class="btn btn-warning" type="button" id="output">导出</button>
	                            </form>
	                        </div>
	                    </div>
	                </div>
	                <section id="no-more-tables">
	                <div style="color:red;font-weight:bold"><?php if($order){echo '总值总额(包括退款金额)： '.$order['count_p'].'元&nbsp;&nbsp;&nbsp;&nbsp;充值人数：'.$order['count_o'].'';} ?></div>
	                	<?php if ($data['data']): ?> 
	                		<div class="span6">
	                			<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?=Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
	                		</div>
	                	<?php endif; ?>
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>ID</th>
	                            <th>游戏名称</th>
	                            <th>总订单量</th>
	                            <th>平台总交易金额(元)</th>
	                            <th>退款金额(元)</th>
	                            <th>测试费</th>
	                            <th>游戏商总交易金额(元)</th>
	                            <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td><?= $k+1;?></td>
		                            <td><?= Html::encode($v['name']);?></td>
		                            <td><?= Html::encode($v['num']);?></td>
		                            <td><?= isset($testorderarr[$v['gid']]['price'])?($v['price']-$testorderarr[$v['gid']]['price']):$v['price'];?></td>
		                            <td><?= isset($refundarr[$v['gid']]['price'])?$refundarr[$v['gid']]['price']:0;?></td>
		                            <td><?= isset($testorderarr[$v['gid']]['price'])?$testorderarr[$v['gid']]['price']:0;?></td>
		                            <td><?= $v['price'];?></td>
		                            <td>
		                              <button class="btn btn-info btn-xs" onclick="window.location.href='/game/detacount/<?php  echo $v['gid']; ?><?php echo ($pid)?'*'.$pid:'';?>.html'"><i class="fa fa-eye"></i>&nbsp;查看详细</button>
		                              <button class="btn btn-warning btn-xs statebtn" onclick="window.location.href='/game/orderc/<?php echo $v['gid'] ?>.html'"><i class="fa fa-shopping-cart"></i>&nbsp;订单流水</button>
		                            </td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                    	<!-- 暂时没有数据 -->
	                    	<tr><td colspan="11" align="center">暂时没有数据！</td></tr>
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
<script src="/media/js/clipboard.min.js"></script>
<script>
$('#output').click(function(){
	var pid = $('#pid').val();
	var starttime = $('#logmin').val();
    var endtime = $('#logmax').val();
	var keyword = $('#keyword').val();
	var cid = $('#cid').val();
	window.location.href='/game/output.html?pid='+pid+'&starttime='+starttime+'&endtime='+endtime+'&keyword='+keyword+'&cid='+cid;
});

//选择
$('.selectbtn-0').click(function (event){
	$('.dropdownoption-0').toggle();
	$(document).on('click',function(){//对document绑定一个影藏Div方法
		$('.dropdownoption-0').hide();
	});
	event.stopImmediatePropagation();//阻止事件向上冒泡
});
$('.dropdownoption-0').click(function (event){
	event.stopImmediatePropagation();//阻止事件向上冒泡
})
//选择选项
$('.items-0').click(function(){
	var lival = $(this).text();
	var dataval = $(this).attr('data-value');
	$('.btntxt-0').text(lival);
	$('.hidden-input-0').attr('value',dataval);
	$('.hidden-inputvalue-0').attr('value',lival);
	$('.btntxt-0').text(lival);
	$('.dropdownoption-0').hide();
})

//选择
$('.selectbtn-1').click(function (event){
	$('.dropdownoption-1').toggle();
	$(document).on('click',function(){//对document绑定一个影藏Div方法
		$('.dropdownoption-1').hide();
	});
	event.stopImmediatePropagation();//阻止事件向上冒泡
});
$('.dropdownoption-1').click(function (event){
	event.stopImmediatePropagation();//阻止事件向上冒泡
})
//选择选项
$('.items-1').click(function(){
	var lival = $(this).text();
	var dataval = $(this).attr('data-value');
	$('.btntxt-1').text(lival);
	$('.hidden-input-1').attr('value',dataval);
	$('.hidden-inputvalue-1').attr('value',lival);
	$('.btntxt-1').text(lival);
	$('.dropdownoption-1').hide();
})

	//搜索匹配
function funsearch(){
	var searchname = $.trim($('#searchname').val());
	if(searchname ==""){
		$('.found li').show();
		$('.no-found').hide();
	}else{
		$('.found li').each(function(){
			var litxt = $(this).text();
			if(litxt.indexOf(searchname) != -1){
				$(this).attr('class','showli').show()
				var lilen =  $('.found').find('.showli').length;
				console.log(lilen);
				if(lilen > 0 ){
					$('.no-found').hide();
				}
			}else{
				$(this).removeAttr('class').hide();
				var lilen1 = $('.found').find('.showli').length;
				if(lilen1 <= 0 ){
					$('.no-founds').show();
				}
				
			}
		})
	}
} 
$('#searchname').bind('input propertychange',function(){
	funsearch();
})

//平台搜索匹配
function funsearch1(){
	var searchname = $.trim($('#searchname1').val());
	if(searchname ==""){
		$('.found1 li').show();
		$('.no-found1').hide();
	}else{
		$('.found1 li').each(function(){
			var litxt = $(this).text();
			if(litxt.indexOf(searchname) != -1){
				$(this).attr('class','showli').show()
				var lilen =  $('.found1').find('.showli').length;
				console.log(lilen);
				if(lilen > 0 ){
					$('.no-found1').hide();
				}
			}else{
				$(this).removeAttr('class').hide();
				var lilen1 = $('.found1').find('.showli').length;
				if(lilen1 <= 0 ){
					$('.no-founds1').show();
				}
				
			}
		})
	}
} 
$('#searchname1').bind('input propertychange',function(){
	funsearch1();
})
</script>
