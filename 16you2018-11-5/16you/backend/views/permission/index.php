<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
    <h3>
    	   权限管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index'])?>">权限管理</a>
        </li>
        <li class="active"> 所有记录 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	   权限管理
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
		            	 <?php if(YII::$app->session['role']!='-1'):?> 
				               <?php foreach (yii::$app->session['mdata'] as $mdata):?>
						          <?php if($mdata['child']=='permission/toadd'):?>
			               			<button class="btn btn-primary" onclick="window.location.href='<?= url::to(['toadd'])?>'"><i class="fa  fa-plus"></i> 添加权限 </button>&nbsp;
		                   	      <?php endif;?>
							    <?php endforeach;?>
						<?php else :?>
						            <button class="btn btn-primary" onclick="window.location.href='<?= url::to(['toadd'])?>'"><i class="fa  fa-plus"></i> 添加权限 </button>&nbsp;
		                <?php endif;?>
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="<?=Url::to(['index'])?>">
		                            <button class="btn btn-primary" type="submit">搜索</button>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入权限名..." data-original-title="请输入权限名" name="name" maxlength="50" value='<?php echo $search; ?>'/>
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
	                            <th>所属菜单</th>
	                            <th>权限名称</th>
	                            <th>描述</th>
	                            <th class="numeric">更新时间</th>
	                            <th class="numeric">操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if(!empty($data['data'])): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
									<tr>
			                            <td data-title="Code"><?= $k+1; ?></td>
			                             <td>
			                             <?php foreach ($fmenu as $menu):?> 
				                             <?php  if($v['m_id']==(isset($menu['id'])?$menu['id']:'')):?>
				                               <?php echo isset($menu['name'])?$menu['name']:'' ?>
				                             <?php endif;?>
			                             <?php endforeach;?>
			                             </td>
			                            <td><?= Html::encode($v['name']) ?></td>
			                            <td><?= Html::encode($v['description']) ?></td>
			                            <td class="numeric" data-title="High"><?= Html::encode(date('Y-m-d H:i:s',$v['updated_at'])) ?></td>
			                            <td class="numeric" data-title="Low">
			                              <?php if(YII::$app->session['role']!='-1'):?> 
		                         	   		 <?php foreach (yii::$app->session['mdata'] as $mdata):?>
					         			 		<?php if($mdata['child']=='manage/state'):?>
					                           		 <a href="<?=Url::to(['toedit','name'=>$v['name'],'mid'=>$v['m_id'],'id'=>$v['id']])?>" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> 编辑</a>
					                                 <a href="javascript:void(0);"  class="btn btn-danger btn-xs del" id="<?php echo $v['name'].','.$v['id'] ?>"><i class="fa fa-trash-o"></i> 删除</a>
			                                    <?php endif;?>
			                                 <?php endforeach;?>
			                               <?php else:?>
			                                     <a href="<?=Url::to(['toedit','name'=>$v['name'],'mid'=>$v['m_id'],'id'=>$v['id']])?>" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> 编辑</a>
					                             <a href="javascript:void(0);"  class="btn btn-danger btn-xs del" id="<?php echo $v['name'].','.$v['id'] ?>"><i class="fa fa-trash-o"></i> 删除</a>
			                              <?php endif;?>     
			                            </td>
			                        </tr>
	                        	<?php endforeach;?>
	                    	<?php else: ?>
	                    	<tr><td colspan="9">暂时没有数据！</td></tr>
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
//删除权限
$('body').on('click','.del',function(){
	 var athis = $(this);
	 var name = $(this).attr('id');
	 var temparr = name.split(",");
	 if(confirm('确认要删除该权限吗？')){
		 $.ajax({
	          url:"/permission/del.html",
	          type:'post',
	          dataType:'json',
	          data:{
		          'name':temparr[0],
		          'mid':temparr[1],
		          },
	          success:function(data){
		          if(data.errorcode=='0'){
		        	  layer.msg(data.info, {icon: 1,time:1300});
		           	  athis.parent().parent('tr').remove();
			      }else if(data.errorcode=='1001'){
			    	  layer.msg(data.info, {icon: 1,time:1300});
				  }else{
					  layer.msg(data.info, {icon: 1,time:1300});
				  }
	        	 
	          }
	      }) 
	     }
})

</script>