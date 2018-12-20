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
<div class="page-heading">
    <h3>配置管理</h3>
    <ul class="breadcrumb">	
        <li>
            <a href="<?= url::to(['index']);?>">配置记录</a>
        </li>
    </ul>
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel panel-primary">
                <header class="panel-heading">
                    配置记录
                    <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                     </span>
                </header>
                <div class="panel-body">
                    <div class="clearfix">
                        <div class="btn-group">
                            <button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['add'])?>'"><i class="fa  fa-plus"></i> 添加配置</button>&nbsp;
                        </div>
                    </div>
                    <div class="btn-group" style="float:right">
                            <div class="form-group">
                                <form action="">
                                    <?php if($game): ?>
                                   <div class="col-md-3" style="padding:0px">
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
                                    <div class="col-md-4" style="padding:0px 1px">
                                        <select class="form-control m-bot15" name="keyword" id="keyword">
                                            <option value="type_url">支付后台通知地址</option>
                                            <option value="partnerid">PartnerID</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3" style="padding:0px 1px">
                                        <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入..." name="value" maxlength="100" value="<?php echo $value; ?>">
                                    </div>
                                    <button class="btn btn-primary" type="submit">搜索</button>
                                </form>
                            </div>
                        </div>
                    <br />
                    <section id="no-more-tables">
                        <?php if ($data['data']):$data['data'] ?> 
                            <div class="span6">
                                <div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?=Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
                            </div>
                        <?php endif; ?>
                        <table class="table table-bordered table-striped table-condensed cf">
                            <thead class="cf">
                            <tr>
                                <th>ID</th>
                                <th>所属游戏</th>
                                <th>支付后台通知地址</th>
                                <th>签名密匙（key）</th>
                                <th>PartnerID</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($data['data']): ?>
                                <?php foreach ($data['data'] as $k => $v):?> 
                                <tr>
                                    <td><?= Html::encode($v['id']);?></td>
                                    <td><?= Html::encode($v['name']);?></td>
                                    <td align="center">
	                                    <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="####" data-original-title="<?= Html::encode($v['type_url'])?>">
		                            		<button class="btn btn-info btn-sm" type="button"data-clipboard-text="<?= Html::encode($v['type_url'])?>">复制链接</button>
		                            	</a>
                                    <td><?= Html::encode($v['key'])?></td>
                                    <td><?= Html::encode($v['partnerid'])?></td>
                                    <td><?php echo date('Y-m-d',$v['createtime']); ?></td>
                                    <td>
                                        <button class="btn btn-info btn-xs" onclick="window.location.href='/configuration/edit/<?php echo $v['id']; ?>.html'"><i class="fa fa-edit"></i>&nbsp;编辑</button>
                                        <button class="btn btn-danger btn-xs" onclick="del(<?php echo $v['id']; ?>)"><i class="fa fa-trash-o"></i>&nbsp;删  除</button>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            
                            <?php else: ?>
                            <tr><td colspan="7" align="center">暂时没有数据</td></tr>
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
    var gid = "<?php echo $gid; ?>";
    var keyword = "<?php echo $keyword; ?>";
    $("#gid option[value='"+gid+"']").attr('selected',true);
    $("#keyword option[value='"+keyword+"']").attr('selected',true);
    //删除
    function del(id){
        layer.confirm('确认要删除吗？',function(){
            layer.msg('正在删除中。。。');
            $.ajax({ 
                url:"/configuration/delete.html",
                type:'post',
                data:{'id':id},
                success:function(data){
                    if(data==1){
                        layer.msg('删除成功',{icon:6,time:2000});
                        setInterval(window.location.reload(),1000);
                    }else{
                        layer.msg('删除失败',{icon:5,time:2000});
                    }
                }
            }) 
        });
    }

    //复制链接
    var clipboard = new Clipboard('.btn-sm');
	clipboard.on('success', function(e) {
		layer.msg('复制链接成功');
	    e.clearSelection();
	}).on('error', function(e) {
		layer.msg('复制链接失败');
	});

	 //选择
	$('.selectbtn').click(function (event){
		$('#dropdownoption').toggle();
		$(document).on('click',function(){//对document绑定一个影藏Div方法
			$('#dropdownoption').hide();
		});
		event.stopImmediatePropagation();//阻止事件向上冒泡
	});
	$('#dropdownoption').click(function (event){
		event.stopImmediatePropagation();//阻止事件向上冒泡
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