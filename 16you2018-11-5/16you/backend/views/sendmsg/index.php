<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
    <h3>
        消息管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">消息管理</a>
        </li>
        <li class="active">模板记录</li>
    </ul>
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                   消息管理
                    <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
                </header>
                <div class="panel-body">
                    <div class="clearfix">
                            <button class="btn btn-primary" onclick="window.location.href='/sendmsg/toadd.html'"><i class="fa  fa-plus"></i> 添加模板 </button>&nbsp;
                            <div class="btn-group pull-right">
                                <div class="form-group">
                                    <form action="<?=Url::to(['index'])?>">
                                        <button class="btn btn-primary" type="submit">搜索</button>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入标题..." data-original-title="请输入标题" name="title" maxlength="50" value='<?php echo $title; ?>'/>
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
                                <th>ID</th>
                                <th>标题</th>
                                <th>模板</th>
                                <th>创建时间</th>
                                <th class="numeric">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($data['data']): ?>
                                <?php foreach ($data['data'] as $k => $v):?>
                                    <tr>
                                        <td data-title="Code"><?= $k+1; ?></td>
                                        <td><?= Html::encode($v['title']) ?></td>
                                        <td><?php
                                                   switch ($v['t_id']){
                                                   	case 'Xh9z-YEgvGauhwWxgZrdKAA9ET-ow6PZdEv39bdcKgk': echo '下单成功通知';break;
                                                   	case '4-zekSNoywhQa537wZuSjPcZ4n3QBNjsA6ATPVdc4YY': echo '退款通知';break;
                                                   }
                                             ?>
                                         </td>
                                        <td class="numeric" data-title="High"><?= Html::encode(date('Y-m-d H:i:s',$v['createtime'])) ?></td>
                                        <td class="numeric" data-title="Low">
                                                <button class="btn btn-info btn-xs" onclick="window.location.href='/sendmsg/toedit/<?php echo $v['id']; ?>.html'"><i class="fa fa-edit"></i> 查看</button>
                                                <button class="btn btn-danger btn-xs del"  id="<?php echo $v['id'];?>"><i class="fa fa-trash-o"></i> 删 除</button>
                                                <button class="btn btn-warning btn-xs sendmsg" id="<?php echo $v['id'];?>"><i class="fa fa-user"></i> 群发</button>
                                                <button class="btn btn-primary btn-xs sendmsgself" id="<?php echo $v['id'];?>"><i class="fa fa-user"></i> 内测</button>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            <?php else: ?>
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
<script src="/media/js/layer/layer.js"></script>
<script>
    //删除游戏
	$('body').on('click','.del',function(){
		var athis = $(this);
		 var id = $(this).attr('id');
		 layer.confirm('确认要删除吗？',function(){
			 $.ajax({
		          url:"/sendmsg/del.html",
		          type:'post',
		          dataType:'json',
		          data:{'id':id},
		          success:function(data){
			          if(data.errorcode=='0'){
			        	  layer.msg('删除成功', {icon: 1,time:2000});
			           	  athis.parent().parent('tr').remove();
				      }else if(data.errorcode=='1001'){
				    	  layer.msg('删除失败', {icon: 1,time:2000});
					  }else{
						  layer.msg('删除失败', {icon: 1,time:2000});
					  }
		        	 
		          }
		      }) 
		     })
	})


    $('.sendmsg').click(function(){
        var id = $(this).attr('id');
    	layer.confirm('确认要群发消息吗？',function(){
            layer.msg('正在群发中。。。');
            $.ajax({
                url:"/sendmsg/send.html",
                type:'post',
                dataType:'json',
                data:{'id':id},
                success:function(data){
                    if(data.errorcode==0){
                        layer.msg('群发成功',{icon:6,time:1000});
                        setInterval(window.location.reload(),900);
                    }else{
                  	  layer.msg('群发失败',{icon:6,time:1000});
                    }
                }
            })
        });
    })
    
    
     $('.sendmsgself').click(function(){
        var id = $(this).attr('id');
    	layer.confirm('确认要内测消息吗？',function(){
            layer.msg('正在发送中。。。');
            $.ajax({
                url:"/sendmsg/sendself.html",
                type:'post',
                dataType:'json',
                data:{'id':id},
                success:function(data){
                    if(data.errorcode==0){
                        layer.msg('发送成功',{icon:6,time:1000});
                        setInterval(window.location.reload(),900);
                    }else{
                  	  layer.msg('发送失败',{icon:6,time:1000});
                    }
                }
            })
        });

    })
</script>