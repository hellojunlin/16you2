<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<link rel="stylesheet" href="/media/css/combo.select.css">
<style type="text/css">
	.combo-input {
	    margin-bottom: 0;
	    height: 34px;
	}
</style>
<div class="page-heading">
    <h3>
       游客 数据统计
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">游客数据统计</a>
        </li>
        <li class="active">游客详细统计 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel panel-primary">
	            <header class="panel-heading">
	               	游客详细统计
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	                 <?php if (is_array($plate) && $managertype!=0):?>
	                	 <form action="" style="width:150px;float:right;margin:-7px 5px 0 0" >
	                        <select class="form-control m-bot15" name="pid" id="pid">
	                           <option value=''>选择平台</option>
	                        	<?php foreach ($plate as $vp) {
	                        		echo '<option value="'.$vp['id'].'">'.$vp['pname'].'</option>';
	                        	}?>
	                        </select>
	                	</form>
	                	<?php else:?>
	                	<span style="width:74px;float:right;margin:3px 23px 0 0"><?php //echo $plate;?></span>
	                <?php endif; ?>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">     
	            				<?php if(!yii::$app->session->get('pid')): ?>
		            			<button class="btn btn-warning" onclick="download()" style="margin-right:10px;float:left"><i class="fa fa-cloud-download"></i> 导出Excel </button>
		            			<?php endif; ?>
	                    		<form action="<?=Url::to(['index'])?>">
	                    		 <?php if($game): ?>
                                   <div class="pull-left" style="padding:0 10px 0 0">
                                        <div class="selectdivbox">
                                        	 <input type="text" class="hidden-input" value="<?php echo $gid?>" name="gid" />
                                        	 <input type="hidden" class="hidden-inputvalue" value="<?php echo $gname?>" name="gname" />
											<button type="button" class="btn selectbtn">
												<span class="btntxt"><?php echo ($gname)?$gname:'选择游戏'?></span>
												<span class="caret"></span>
											</button>
											<div id="dropdownoption" class="dropdown-menu">
												<div class="live-filtering">
													<div class="searchinput">
														<input id="searchname" type="text" class="form-control live-search" autocomplete="off">
													</div>
													<div class="list-to-filter">
														<ul class="list-unstyled">
														 <?php if($game): ?>
														            <li class="filter-item items" data-value="">选择游戏</li>
						                                            <?php foreach ($game as $_g):?>
						                                            <li class="filter-item items" data-value="<?php echo $_g['id']; ?>"><?php echo $_g['name']; ?></li>
						                                            <?php endforeach; ?>
						                                    <?php endif; ?>
														</ul>
														<div class="no-search-results">搜索不到结果</div>
													</div>
												</div>
										   </div>
										</div>
                                    </div>
                                    <?php endif; ?>
	                    		<!--  
	                    			<div class="col-lg-2">
                                        <select  class="form-control" name="gid" id="gid">
                                            <option value="">筛选游戏</option>
                                            <?php //if($game): ?>
                                            	<?php //foreach ($game as $v):?> 
                                            		<option value="<?php //echo $v['id']; ?>" <?php //if($gid==$v['id']){echo 'selected';} ?>><?php //echo $v['id'].'--'.$v['name']; ?></option>
                                            	<?php //endforeach; ?>
                                            <?php //endif; ?>
                                        </select>
                                    </div>-->
	                    			<label class="pull-left"><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="开始时间" value="<?php echo $starttime; ?>" name="starttime">
									</label> <span class="pull-left" style="padding:0px 10px"> ----</span>
		                           	<label class="pull-left"><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="结束时间"value="<?php echo $endtime; ?>" name="endtime"></label>
                                    <label  style="padding-left:15px;">
                                    <input type="hidden" value="<?php echo $pid; ?>" name="pid">
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
	                	<span style="color:red;">(显示当天记录，可通过搜索查询之前记录)</span>
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th width="100px">日期</th>
	                            <th>游戏名称</th>
	                            <th>日DAU
									<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当日玩游戏的用户数量">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>付费人数
	                           		<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当日充值的总用户数">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>总付费金额(元)
	                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当日所有用户充值的总金额">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>付费次数
	                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当日用户总付费次数">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>ARPU(元)
	                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="充值总流水占活跃用户数的比例">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>ARPPU(元)
	                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="充值流水占付费用户数的比例">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>付费率
	                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当日付费用户占活跃用户的比率">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                        </tr>
	                        </thead>
	                        
	                        <tbody>
	                         <?php if($data['data']): ?>
	                           <?php foreach ($data['data'] as $d):?>
	                             <tr>
	                               	 <td><?php echo date('Y-m-d',$d['count_time']);?></td>
	                                 <td><?php echo $d['gamename'];?></td>
	                                 <td><?php echo $d['play_user'];?></td>
	                                 <td><?php echo $d['pay_user'];?></td>
	                                 <td><?php echo $d['pay_sum'];?></td>
	                                 <td><?php echo $d['pay_num']?$d['pay_num']:0;?></td>
	                                 <td><?php echo ($d['play_user']==0)?0:round($d['pay_sum']/$d['play_user'],2);?></td>
	                                 <td><?php echo ($d['pay_user']==0)?0:round($d['pay_sum']/$d['pay_user'],2);?></td>
	                                 <td><?php echo ($d['play_user']==0)?0.00:round($d['pay_user']/$d['play_user'],2);?></td>
	                               </tr>
	                           <?php endforeach;?>
	                         <?php else: ?>
	                    	<tr><td colspan="13" style="text-align: center;">暂时没有数据！</td></tr>
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
<script src="/media/js/jquery.combo.select.js"></script>
<script>
	$(function() {
		$('#gid').comboSelect();
	});
	$('#pid').change(function(){
		var pid = $(this).find("option:selected").val();
		if(pid!=-1){
			layer.load();
			window.location.href='/count/index.html?pid='+pid;
		}
	});
	var ppid = "<?php echo $pid ?>";
	$("#pid option[value='"+ppid+"']").attr('selected',true);


	 //选择
	$('.selectbtn').click(function (event){
		$('#dropdownoption').toggle();
		$(document).on('click',function(){//对document绑定一个影藏Div方法
			$('#dropdownoption').hide();
		});
		event.stopImmediatePropagation();
	});
	$('#dropdownoption').click(function (event){
		event.stopImmediatePropagation();
	})
	//选择选项
	$('.items').click(function(){
		var lival = $(this).text();
		var dataval = $(this).attr('data-value');
		$('.btntxt').text(lival);
		$('.hidden-input').attr('value',dataval);
		$('.hidden-inputvalue').attr('value',lival);
		$('.btntxt').text(lival);
		$('#dropdownoption').hide();
	})

		//搜索匹配
	function funsearch(){
		var searchname = $.trim($('#searchname').val());
		if(searchname ==""){
			$('.list-unstyled li').show();
			$('.no-search-results').hide();
		}else{
			$('.list-unstyled li').each(function(){
				var litxt = $(this).text();
				if(litxt.indexOf(searchname) != -1){
					$(this).attr('class','showli').show()
					var lilen =  $('.list-unstyled').find('.showli').length;
					console.log(lilen);
					if(lilen > 0 ){
						$('.no-search-results').hide();
					}
				}else{
					$(this).removeAttr('class').hide();
					var lilen1 = $('.list-unstyled').find('.showli').length;
					if(lilen1 <= 0 ){
						$('.no-search-results').show();
					}
					
				}
			})
		}
	} 
	$('#searchname').bind('input propertychange',function(){
		funsearch();
	})

	//导出弹框，iframe层-禁滚动条
	function download(){
		layer.load(0, {time: 3*1000});
		var start_time = $("input[name='starttime']").val();
        var end_time = $("input[name='endtime']").val();
        if(start_time=='undefined') {start_time = '';}
        if(end_time=='undefined') {end_time = '';}
        var pid = $("select[name='pid']").val();
        var gid = $("input[name='gid']").val();
		window.location.href="/touristcount/detailsoutput.html?start_time="+start_time+'&end_time='+end_time+'&pid='+pid+'&gid='+gid;
	}
</script>