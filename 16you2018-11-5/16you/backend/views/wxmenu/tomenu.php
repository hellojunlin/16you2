<?php 
use yii\helpers\Html;
use yii\helpers\Url;
?>
<style>
    .table tbody > tr > td, .table tfoot > tr > td {
    padding: 10px 5px;
    }
</style>
<div class="page-heading">
     <h3>微信菜单列表</h3>
     <ul class="breadcrumb">
         <li><a href="<?=Url::to(['tomenu'])?>">微信管理</a></li>
         <li class="active">微信菜单列表</li>
      </ul>
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    微信菜单列表   
                    <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                    </span>     
                </header>
                <section class="panel-body">
                   <section id="no-more-tables" class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed cf">
                            <thead class="cf">
                            <tr>
                                <th>编号</th>
                                <th>微信号名称</th>
                                <th>Appid</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($data): ?>
                                <?php $i=1;foreach ($data as $k => $v):?> 
                                <tr>
                                    <td><?= Html::encode($i); ?></td>
                                    <td><?= Html::encode($v['name'])?></td>
                                    <td><?= Html::encode($v['appid']) ?></td>
                                    <td>
                                      <button class="btn btn-info btn-xs" onclick="window.location.href='/wxmenu/index.html?appid=<?php echo $v['appid']; ?>'"><i class="fa fa-eye"></i> 查看菜单</button>
                                    </td>
                                </tr>
                                <?php $i++;endforeach;?>
                            <?php else: ?>
                            <tr><td colspan="12">暂时没有数据！</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </section>
                    </div>
                </section>
        </div>
    </div>
</section>