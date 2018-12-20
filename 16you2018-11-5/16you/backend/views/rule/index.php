
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
    <h3>规则管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">规则记录</a>
        </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row"> 
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	规则记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header> 
	            <div class="panel-body">
	            	<div class="clearfix">
	            		<div class="btn-group">
	            			<button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['toadd'])?>'"><i class="fa  fa-plus"></i> 添加规则 </button>&nbsp;
	            		</div>
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
                                    <button class="btn btn-primary" type="submit">搜索</button>
                                    <div class="col-sm-6" style="padding:0px 1px">
                                        <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入规则内容..." data-original-title="请输入规则内容" name="value" maxlength="16" value="<?php echo $value;?>">
                                    </div>
                                  </form>
	                        </div>
	                    </div>
	                </div>
	                <section id="no-more-tables">
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>ID</th>
	                            <th>规则类别</th>
	                            <th style="width:46%">规则内容</th>
	                            <th>状态</th>
	                            <th>创建时间</th>
	                            <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data): ?>
	                        	<?php foreach ($data as $k => $v):?> 
								<tr>
		                            <td><?= Html::encode($v['id'])?></td>
		                            <td><?= Html::encode($v['type'])?></td>
		                            <td style="width:150px;height:100px;"><?php echo $v['content'];?></td>
		                            <?php if($v['state']==0):?>
		                            <td class="statval"><label class="label label-warning">禁用</label></td>
		                            <?php else:?>
		                            <td class="statval"><label class="label label-success">启用</label></td>
		                            <?php endif;?>
		                            <td><?php echo date('Y-m-d',$v['createtime']); ?></td>
		                            <td>
		                                <button class="btn btn-info btn-xs" onclick="window.location.href='/rule/toedit/<?php echo $v['id']; ?>.html'"><i class="fa fa-edit"></i>&nbsp;编 辑</button>
		                                <button class="btn btn-danger btn-xs del" id="<?php echo $v['id'];?>"><i class="fa fa-trash-o"></i>&nbsp;删  除</button>
		                            </td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                    	<tr><td align="center" colspan='20'>暂时没有数据</td></tr>
	                    	<?php endif; ?>
	                        </tbody>
	                    </table>
	                </section>
	            </div>
	        </section>
        </div>
	</div>
</section>
<script>
	//删除礼包
	$('.del').click(function(){
		var athis = $(this);
		 var id = $(this).attr('id');
		 if(confirm('确认要删除吗？')){
			 $.ajax({
		          url:"/rule/del.html",
		          type:'post',
		          dataType:'json',
		          data:{'id':id},
		          success:function(data){
			          if(data.errorcode==0){
			        	  window.location.reload();
				      }else{
						  layer.msg(data.info, {icon: 1,time:2000});
					  }
		        	 
		          }
		      }) 
		     }
	})
</script>