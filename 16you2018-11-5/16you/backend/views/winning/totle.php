<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
    <h3>中奖管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['totle']);?>">大转盘数据统计</a>
        </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <header class="panel-heading no-border">
                    大转盘数据统计
                </header>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="text-align: center;"><i class="fa fa-date"></i> 日期 </th>
                            <th style="text-align: center;">每天转的次数</th>
                        </tr>
                        </thead>
                        <tbody>
                        	<?php $i=0;foreach ($data as $v) {?>
	                        <tr align="center">
	                            <td><span class="label label-success">&nbsp; <?php echo $v['date'] ?> &nbsp;</span></td>
	                            <td><?php echo $v['ucount'];$i = $i+$v['ucount']; ?></td>
	                        </tr>
	                        <?php } ?>
	                        <tr align="center">
	                            <td><span class="label label-danger">&nbsp; 总计 &nbsp;</span></td>
	                            <td><?php echo $i; ?></td>
	                        </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</section>