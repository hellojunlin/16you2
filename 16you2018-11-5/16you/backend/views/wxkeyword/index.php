<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<style>
    .table tbody > tr > td, .table tfoot > tr > td {
    padding: 10px 5px;
    }

    th{
      white-space:nowrap;
    }
</style>
<div class="page-heading">
     <h3>关键字自动回复</h3>
     <ul class="breadcrumb">
         <li><a href="<?=Url::to(['index'])?>">微信管理</a></li>
         <li class="active">关键字自动回复</li>
      </ul>
</div>
<section class="wrapper"> 
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    关键字自动回复   
                    <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                    </span>     
                </header>
                <section class="panel-body">
                    <div class="clearfix">
                     <?php if(YII::$app->session['role']!='-1'):?> 
                        <?php foreach (yii::$app->session['mdata'] as $mdata):?>
                          <?php if($mdata['child']=='wxkeyword/add'):?>
                            <button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['add'])?>'"><i class="fa  fa-plus"></i> 添加关键字回复 </button>&nbsp;
                          <?php endif;?>
                        <?php endforeach;?>
                     <?php else:?>
                            <button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['add'])?>'"><i class="fa  fa-plus"></i> 添加关键字回复 </button>&nbsp;
                     <?php endif;?> 
                        <div class="btn-group pull-right">
                            <div class="form-group">
                                <form action="<?=Url::to(['index'])?>">
                                    <button class="btn btn-primary" type="submit">搜索</button>
                                    <div class="col-sm-9" style="padding:0px 1px">
                                        <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入关键字..." data-original-title="内容中的关键字" name="value" maxlength="16" value='<?php echo $value; ?>'/>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                   <section id="no-more-tables" class="table-responsive">
                        <?php if ($data['data']): ?> 
                            <div class="span6">
                                <div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?= Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
                            </div>
                        <?php endif; ?>
                        <table class="table table-bordered table-striped table-condensed cf">
                            <thead class="cf">
                            <tr>
                                <th>ID</th>
                                <th>微信号</th>
                                <th>关键字</th>
                                <th>内容</th>
                                <th>类型</th>
                                <th>排序</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($data['data']): ?>
                                <?php foreach ($data['data'] as $k => $v):?> 
                                <tr>
                                    <td><?= Html::encode($v['id']) ?></td>
                                    <td><?= Html::encode($v['wxname']) ?></td>
                                    <td><?= Html::encode($v['keyword'])?></td>
                                    <?php 
                                      if($v['type']==3){
                                        echo '<td style="min-width:70%"><img src="" alt=""><img src="'.$v['filename'].'" alt="图片" style="height:40px"> </td><td>图片</td>';
                                      }elseif($v['type']==1){
                                          echo '<td style="min-width:70%">'.$v['content'].'</td><td>文本</td>';
                                      }elseif($v['type']==2){
                                          echo '<td style="min-width:70%">'.$v['content'].'</td><td>图文</td>';
                                      }else{
                                          echo '<td style="min-width:70%">'.$v['content'].'</td><td>视频</td>';
                                      } 
                                    ?>
                                    <td><?= Html::encode($v['sort']) ?></td>
                                    <td><?= Html::encode(date('Y-m-d',$v['createtime'])) ?></td>
                                    <td>
                                     <?php if(YII::$app->session['role']!='-1'):?> 
                                       <?php foreach (yii::$app->session['mdata'] as $mdata):?>
                                           <?php if($mdata['child']=='wxkeyword/edit'):?>
                                            <button class="btn btn-info btn-xs" onclick="window.location.href='/wxkeyword/edit/<?php echo $v['id']; ?>.html'"><i class="fa fa-edit"></i> 编辑</button>
                                             <?php endif;?>
                                             <?php if($mdata['child']=='wxkeyword/del'):?> 
                                            <button class="btn btn-danger btn-xs" onclick="del(<?php echo $v['id']; ?> )"><i class="fa fa-trash-o"></i> 删 除</button>
                                           <?php endif;?>
                                       <?php endforeach;?>
                                     <?php else:?>
                                        <button class="btn btn-info btn-xs" onclick="window.location.href='/wxkeyword/edit/<?php echo $v['id']; ?>.html'"><i class="fa fa-edit"></i> 编辑</button>
                                        <button class="btn btn-danger btn-xs" onclick="del(<?php echo $v['id']; ?> )"><i class="fa fa-trash-o"></i> 删 除</button>
                                     <?php endif;?>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            <?php else: ?>
                            <tr><td colspan="12">暂时没有数据！</td></tr>
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
                    <?php endif; ?>
                    </div>
                </section>
        </div>
    </div>
</section>
<script src="/media/js/layer/layer.js"></script>
<script>
    //删除
    function del(id){
        layer.msg('正在删除中。。。',{time:3000});
            $.ajax({ 
                url:"/wxkeyword/del.html",
                type:'post',
                data:{'id':id},
                success:function(data){
                    if(data==1){
                       location.href='/wxkeyword/index.html';
                    }else{
                        alert('删除失败');
                    }
                }
            }) 
    }
</script>