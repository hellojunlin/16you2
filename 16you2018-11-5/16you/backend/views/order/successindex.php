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
    <h3>用户支付订单</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">订单记录</a>
        </li>
    </ul>
</div>
<section style="margin:0 15px">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel panel-primary">
	            <header class="panel-heading">
	               	游戏总览
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	                <section id="no-more-tables">
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>总用户数</th>
	                            <th>总付费用户数</th>
	                            <th>总付费金额(元)</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        	<tr>
	                                <td><?php echo $cplay;?></td>
	                                <td><?php echo $zcount['zuid']?$zcount['zuid']:0;?></td>
	                                <td><?php echo $zcount['zprice']?$zcount['zprice']:0;?></td>
	                            </tr>
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
            <section class="panel panel-primary">
	            <header class="panel-heading">用户支付记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	                 <?php if (is_array($plate) && $managertype!=0): ?>
                	<form action="" style="width:150px;float:right;margin:-7px 5px 0 0" >
                        <select class="form-control m-bot15" name="pid" id="pid">
                            <option value=''>选择平台</option>
                        	<?php foreach ($plate as $vp) {
                        		echo '<option value="'.$vp['id'].'">'.$vp['pname'].'</option>';
                        	} ?>
                        </select>
                	</form>
                	<?php else:?>
                		<span style="width:74px;float:right;margin:3px 23px 0 0">	</span>
	                <?php endif ?>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	                    <div class="btn-group col-md-12" style="padding:5px 0px;">
	                    	<div class="form-group">
	                    		<form action="">
                                	<div class="col-md-2" >
	                    			<label style="padding:0px;width:150px;"><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:150px;"placeholder="开始时间" value="<?php echo $start_time; ?>" name="start_time">
									</label>
									</div>
									
									<div class="col-md-2" >
		                           	<label style="padding:0px;width:150px;"><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" style="width:150px;"placeholder="结束时间"value="<?php echo $end_time; ?>" name="end_time"></label>
                                    </div>
                                    
                                    <?php if ($managertype!=0): ?> 
                                     <div class="col-md-2" style="padding:0px;width:150px;">
                                        <select class="form-control" name="ptype" id="ptype">
                                        	<option value=" ">选择支付方式</option>
                                        	<option value="1">微信支付</option>
                                        	<option value="6">微信扫码</option>
                                        	<option value="2">盛-微信</option>
                                        	<option value="3">盛-支付宝</option>
                                        	<option value="4">盛-网银</option> 
                                        	<option value="5">盛-快捷</option>
                                        	<option value="7">盛_微信扫码</option>
                                        	<option value="8">游币支付</option>
                                        	<option value="9">优_微信H5支付</option>
                                        	<option value="10">优_微信扫码支付</option>
                                        	<option value="11">优_支付宝扫码</option>
                                        	<option value="11">优_支付宝app</option>
                                        </select>
                                    </div>
                                     <?php endif ?>
                                     
                                      <?php if ($managertype!=0): ?> 
                                     <div class="col-md-2" style="padding-left:10px;width:150px;">
                                        <select class="form-control" name="payclient" id="payclient">
                                        	<option value=" ">选择支付端</option>
                                        	<option value="1">微信端</option>
                                        	<option value="2">pc端</option>
                                        	<option value="3">app端</option>
                                        </select>
                                    </div>
                                     <?php endif ?>
                                      <input type="hidden" value="<?php echo $pid; ?>" name="pid">
                                    <div class="col-md-2">
                                    <button class="btn btn-primary" type="submit" style="margin-left:5px;">搜索</button>
                               		</div>
                                </form>
	                        </div>
	                    </div>
	                </div>
	                <section id="no-more-tables">
	                	<?php if ($data['data']): ?> 
	                		<div class="span6">
	                			<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?=Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
	                		</div>
	                	<?php endif; ?>
	                	<span style="color:red;">(显示当天订单，可通过搜索查询之前订单)</span>
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>平台名称</th>
	                            <th>用户名称</th>
	                            <th>游戏名称</th>
	                            <th>支付金额</th>
	                            <th>支付环境</th>
	                            <th>支付时间</th>
	                            <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
								    <td><?= Html::encode($v['pname'])?></td>
								    <td><?php echo isset($v['username'])?$v['username']:'游客'.$v['tourid'];?></td>
		                            <td><?= Html::encode($v['name'])?></td>
		                            <td class="statval"><?php echo $v['price']; ?></td>
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
                            				echo '盛_微信扫码';
                            				break;
                            		    case '8':
                            				echo '游币支付';
                            				break;
                            			case '9':
                            				echo '优_微信H5支付';
                            				break;
                            			case '10':
                            			    echo '优_微信扫码支付';
                            				break;
                            			case '11':
                            				echo '优_支付宝扫码';
                            				break;
                            			case '12':
                            				echo '优_支付宝app';
                            				break;
		                            }?>
		                            </td>
		                            <td><?php echo date('Y-m-d H:i:s',$v['createtime']); ?></td>
		                            <td>
		                            	<?php if(($v['role']=='平台管理者')&&(!yii::$app->session->get('pid'))): ?>
		                            	<?php if($v['is_hide']==1): ?>
	                            		<button class="btn btn-warning btn-xs statebtn" id="<?php echo $v['id']?>" name="2" time="<?php echo date('Y-m-d H:i:s',$v['createtime']); ?>"><i class="fa fa-times"></i>&nbsp;隐 藏</button>
	                            		<?php else: ?>
		                            	<button class="btn btn-success btn-xs statebtn" id="<?php echo $v['id']?>" name="1" time="<?php echo date('Y-m-d H:i:s',$v['createtime']); ?>"><i class="fa fa-check"></i>&nbsp;显 示</button>
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
</section>
<script>
$(function() {
	$('#pid').comboSelect();
});
var ptype = "<?php echo $ptype; ?>";
var payclient = "<?php echo $payclient; ?>";
$("#ptype option[value='"+ptype+"']").attr('selected',true);
$("#payclient option[value='"+payclient+"']").attr('selected',true);
	$('#pid').change(function(){
		pid = $(this).find("option:selected").val();
		if(pid!=-1){
			layer.load();
			window.location.href='/order/sindex.html?pid='+pid;
		}
	});
	var ppid = "<?php echo $pid ?>";
	$("#pid option[value='"+ppid+"']").attr('selected',true);
	$('body').on('click','.statebtn',function(){
		var btntext = $(this).html('<i class="fa fa-spinner fa-pulse" style="width:51.44px;"></i>');
		var _this = $(this);
		var id = _this.attr('id');
		var state = _this.attr('name');
		var time = _this.attr('time');
		$.ajax({
	        url:"/order/changehide.html",
	        type:'post',
	        dataType:'json',
	        data:{'state':state,'id':id,'time':time},
	        success:function(data){
	            if(data.errorcode==0){
					if(state==2){//隐藏
				    	_this.removeClass('btn-warning').addClass('btn-success').attr('name','1').html('<i class="fa fa-check "></i>&nbsp;显 示');
					}else{
						_this.removeClass('btn-success').addClass('btn-warning').attr('name','2').html('<i class="fa fa-times"></i>&nbsp;隐 藏');
					}
	            }else{
	            	alert(data.info);
	            }
	        }
	    }) 
	})
</script>
