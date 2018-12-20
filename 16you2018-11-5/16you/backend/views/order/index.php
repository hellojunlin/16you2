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
	            		<button class="btn btn-warning" onclick="download()"><i class="fa fa-cloud-download"></i> 导出Excel </button>
	                    <div class="btn-group col-md-12" style="padding:5px 0px;">
	                    	<div class="form-group">
	                    		<form action="">
	                    		    <div class="col-md-2" style="width:210px;padding:0;"><label>订单号：</label><input class="inputstylebox" name="orderID" value="<?php echo $orderID ?>"></div>
	                    		    <div class="col-md-2" style="width:210px;padding:0;"><label>用户ID：</label><input class="inputstylebox" name="Unique_ID" value="<?php echo $Unique_ID ?>"></div>
	                    		    <div class="col-md-2" style="width:210px;padding:0;"><label>区服ID：</label><input class="inputstylebox" name="districtID" value="<?php echo $districtID ?>"></div>
	                    		    <div class="col-md-2" style="width:210px;padding:0;"><label>交易编号：</label><input class="inputstylebox" name="transaction_id" value="<?php echo $transaction_id ?>"></div>
	                    			<div class="col-md-2" style="padding:0px;width:150px;">
                                        <select class="form-control" name="state" id="state">
                                        	<option value=" ">选择订单状态</option>
                                        	<option value="1">待付款</option>
                                        	<option value="2">已付款</option>
                                        	<option value="4">已退款</option>
                                        </select>
                                    </div>
                                    <?php if($game): ?>
                                   <div class="col-md-3" style="padding:0px;width:150px;">
                                        <div class="selectdivbox">
                                        	 <input type="text" class="hidden-input" value="<?php echo $gid?>" name="gid" id="gid"/>
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
                                	<?php if($plate && $managemodel->type!=0): ?>
                                    <div class="col-md-2" style="padding:0px;width:150px;">
                                        <select class="form-control" name="pid" id="pid">
                                        	<option value=" ">选择平台</option>
                                        	<?php foreach ($plate as $_p):?>
                                            <option value="<?php echo $_p['id']; ?>"><?php echo $_p['pname']; ?></option>
                                        	<?php endforeach; ?>
                                        </select>
                                    </div>
                                	<?php endif; ?>
                                	<div class="col-md-2">
	                    			<label style="padding:0px;width:150px;"><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:150px;"placeholder="开始时间" value="<?php echo $start_time; ?>" name="start_time">
									</label>
									</div>
									~
									<div class="col-md-2">
		                           	<label style="padding:0px;width:150px;"><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" style="width:150px;"placeholder="结束时间"value="<?php echo $end_time; ?>" name="end_time"></label>
                                    </div>
                                     <div class="col-md-2" style="padding:0px;width:150px;">
                                        <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入用户名称..." data-original-title="请输入用户名称" name="value" maxlength="16" value="<?php echo $value; ?>">
                                    </div>
                                    <div class="col-md-2" style="padding:0px;width:150px;">
                                        <select class="form-control" name="ptype" id="ptype">
                                        	<option value=" ">选择支付方式</option>
                                        	<option value="1">微信支付</option>
                                        	<option value="6">微信扫码</option>
                                        	<option value="2">盛-微信</option>
                                        	<option value="3">盛-支付宝</option>
                                        	<option value="4">盛-网银</option> 
                                        	<option value="5">盛-快捷</option>
                                        	<option value="7">盛-微信扫码</option>
                                        	<option value="8">游币支付</option>
                                        	<option value="9">优_微信H5支付</option>
                                        	<option value="10">优_微信扫码支付</option>
                                        	<option value="11">优_支付宝扫码</option>
                                        	<option value="12">优_支付宝app</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                    <button class="btn btn-primary" type="submit" style="margin-left:5px;">搜索</button>
                               		</div>
                                </form>
	                        </div>
	                    </div>
	                </div>
	                <section id="no-more-tables">
	                	<div style="color:red;font-weight:bold"><?php if($order){echo '总值总额：'.$order['count_p'].' 元&nbsp;&nbsp;&nbsp;&nbsp;充值人数：'.$order['count_u'];} ?></div>
	                	<?php if ($data['data']): ?> 
	                		<div class="span6">
	                			<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?=Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
	                		</div>
	                	<?php endif; ?>
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>游戏名称</th>
	                            <th>平台名</th>
	                            <th>用户ID</th>
	                            <th>用户名称</th>
	                            <th>区服ID</th>
	                            <th>道具名称</th>
	                            <th>厂商订单号</th>
	                            <th>订单金额</th>
	                            <th>订单状态</th>
	                            <th>下单时间</th>
	                            <th>交易编号</th>
	                            <th>支付方式</th>
	                            <th>后台回调状态</th>
	                            <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td><?= Html::encode($v['name'])?></td>
		                            <td><?= Html::encode($v['pname'])?></td>
		                            <td><?php echo isset($v['Unique_ID'])?$v['Unique_ID']:$v['tourid']?></td>
		                            <td><?php echo isset($v['username'])?$v['username']:'游客';?></td>
		                            <td><?= Html::encode($v['districtID'])?></td>
		                            <td><?= Html::encode($v['propname'])?></td>
		                            <td class="statval"><?php echo $v['orderID']; ?></td>
		                            <td class="statval"><?php echo $v['price']; ?></td>
		                            <td class="statval">
		                            <?php switch ($v['state']) {
		                            	case '1':
		                            		echo '<label class="label label-info">待付款</label>';
		                            		break;
		                            	case '2':
		                            		echo '<label class="label label-success">支付成功</label>';
		                            		break;
		                            	case '3':
		                            		echo '<label class="label label-warning">退款中</label>';
		                            		break;
		                            	case '4':
		                            		echo '<label class="label label-danger">已退款</label>';
		                            		break;
		                            	case '5':
		                            		echo '<label class="label label-primary">支付失败</label>';
		                            		break;
		                            }?>
		                            </td>
		                            <td><?php echo date('Y-m-d H:i:s',$v['createtime']); ?></td>
		                            <td class="statval"><?php echo $v['transaction_id']; ?></td>
		                            <td class="statval">
		                            <?php switch ($v['ptype']) {
		                            	case '1':
		                            		echo '微信支付';
		                            		break;
		                            	case '2':
		                            		echo '盛-微信';
		                            		break;
		                            	case '3':
		                            		echo '盛-支付宝';
		                            		break;
	                            		case '4':
	                            			echo '盛-网银';
	                            			break;
                            			case '5':
                            				echo '盛-H5快捷';
                            				break;
                            			case '6':
                            				echo '微信扫码';
                            				break;
                            			case '7':
                            				echo '盛-微信扫码';
                            				break;
                            			case '8':
                            				echo '游币支付';
                            				break;
                            			case '9':
                            				echo '优_微信H5支付';
                            				break;
                            			case '10':
                            				echo '优_微信扫码';
                            				break;
                            			case '11':
                            				echo '优_支付宝扫码';
                            				break;
                            			case '12':
                            				echo '优_支付宝app';
                            				break;
		                            }?>
		                            </td>
		                            <td class="statval">
		                            <?php switch ($v['type']) {
		                            	case '0':
		                            		echo '<label class="label label-warning">未调用</label>';
		                            		break;
		                            	case '1':
		                            		echo '<label class="label label-success">回调成功</label>';
		                            		break;
		                            	case '2':
		                            		echo '<label class="label label-danger">回调失败</label>';
		                            		break;
		                            }?>
		                            </td>
		                            <td>
		                            	<?php if(YII::$app->session['role']!='-1'):?> 
		                         	    <?php foreach (yii::$app->session['mdata'] as $mdata):?>
					         			<?php if($mdata['child']=='order/toedit'):?>
					         				<button class="btn btn-info btn-xs" onclick="window.location.href='/order/toedit.html?id=<?php echo $v['id']; ?>'"><i class="fa fa-edit"></i>&nbsp;详 细</button>
					         			<?php endif; ?>
					         			<?php if($mdata['child']=='order/del'):?>
					         				<!-- <button class="btn btn-danger btn-xs" onclick="del(<?php echo $v['id']; ?> )"><i class="fa fa-trash-o"></i>&nbsp;删 除</button> -->
					         			<?php endif; ?>
					         			<?php endforeach; ?>
					         			<?php else: ?>
			                                <button class="btn btn-info btn-xs" onclick="window.location.href='/order/toedit.html?id=<?php echo $v['id']; ?>'"><i class="fa fa-edit"></i>&nbsp;详 细</button>
		                                   <!--  <button class="btn btn-danger btn-xs" onclick="del(<?php echo $v['id']; ?> )"><i class="fa fa-trash-o"></i>&nbsp;删 除</button> -->
		                                    <?php if($v['state']==2 &&($v['ptype']==1 || $v['ptype']==6 || $v['ptype']==3 ||$v['ptype']==7)):?>
		                                    <button class="btn btn-warning btn-xs onstate" name="<?php echo $v['transaction_id'];?>" id="<?php echo $v['ptype']?>" style="display:none;"><i class="fa fa-mail-reply"></i>&nbsp;退 款</button>
					         				<?php endif; ?>
					         			<?php endif; ?>
		                            </td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                    	<tr><td colspan="13" align="center">暂时没有数据</td></tr>
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
	<form action="" method="post" id="forminput">
	       <span class="hiddeninput"></span>
	</form>
</section>
<script>
$(function() {
	$('#pid').comboSelect();
});
	var pid = "<?php echo $pid; ?>";
	var gid = "<?php echo $gid; ?>";
	var state = "<?php echo $state; ?>";
	var ptype = "<?php echo $ptype; ?>";
	$("#pid option[value='"+pid+"']").attr('selected',true);
	$("#gid option[value='"+gid+"']").attr('selected',true);
	$("#state option[value='"+state+"']").attr('selected',true);
	$("#ptype option[value='"+ptype+"']").attr('selected',true);
	//删除订单
	function del(id){
	    layer.confirm('确认要删除吗？',function(){
	    	layer.msg('正在删除中。。。');
	    	$.ajax({ 
	          	url:"/order/delete.html",
	          	type:'post',
	          	data:{'id':id},
	          	success:function(data){
	          		if(data==1){
	          			layer.msg('删除成功',{icon:6,time:2000});
	              		setInterval(window.location.reload(),900);
	          		}else{
	          			layer.msg('删除失败',{icon:5,time:2000});
	          		}
	          	}
	     	}) 
	    });
	}

	//确认退款 
	$(".onstate").click(function(){
		var id = $(this).attr('id');
		var t_id = $(this).attr('name');
		layer.confirm('确认要退款吗？',function(){
			if(id=='1' || id=='6' || id=='7'){//微信支付和扫码支付
			      	$.ajax({
			          	url:"/order/state.html",
			          	type:'post',
			          	data:{
				          	'transaction_id':t_id,
				          	'ptype':id,
				          	},
			          	success:function(data){
				            if(data == 1){
				            	layer.msg('退款成功',{icon:6,time:2000});
		 		                window.location.href='/order/refund.html';
				            }else{
				                layer.msg(data,{icon:5,time:2000});
				            }      
				        }
			      	}) 
			}else if(id=='3'){//盛付通支付宝支付
				 var formurl = "http://cardpay.shengpay.com/api-acquire-channel/services/refundService";
				 $('#forminput').attr('action',formurl);
					 $.ajax({
							type:'post',
							dataType:'json', 
							data:{'transaction_id':t_id},
							url:"/order/getrefunddata.html", 
							success:function(data){
								if(data.errorcode==0){
									var msg = data.msg;
									$.each(msg,function(k,v){
									    $('.hiddeninput').after("<input type='hidden' name='"+k+"' value='"+v+"'>");
									});
									$('#forminput').submit();
								}else{
									layer.msg(data.msg,{icon:5,time:2000});
								}
							}
					}) 
		    }
		})
	});

	
	//导出弹框，iframe层-禁滚动条
	function download(){
		var orderID = $("input[name='orderID']").val();
		var districtID = $("input[name='districtID']").val();
		var Unique_ID = $("input[name='Unique_ID']").val();
		var transaction_id = $("input[name='transaction_id']").val();
		var state = $("select[name='state'] option:selected").val();
		var gid = $("#gid").val();
		var username = $("input[name='value']").val();
		var pid = $("input[name='pid']").val();
		var start_time = $("input[name='start_time']").val();
		var end_time = $("input[name='end_time']").val();
		 window.location.href="/order/output.html?start_time="+start_time+'&end_time='+end_time+'&gid='+gid+'&orderID='+orderID+'&districtID='+districtID+'&Unique_ID='+Unique_ID+'&transaction_id='+transaction_id+'&state='+state+'&username='+username;
	}


	 //选择
	$('.selectbtn').click(function(){
		$('#dropdownoption').toggle();
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
</script>