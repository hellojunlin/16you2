<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
    <h3>使用说明</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">后台名词说明</a>
        </li>
    </ul>
</div>
<div class="wrapper">
	<div class="row">
        <div class="col-sm-12">
            <section class="panel panel-success">
                <header class="panel-heading">
	               	后台名词说明
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
                <div class="panel-body" style="color:#333">
				    <h3 id="数据表格使用说明">查看表头解释</h3>
				    <p>汇总统计内，鼠标放置在问号图标上，即可显示该数据名词的解释（如下图）
				        <br><img src="/media/images/1234567890.png" class="thumbnail" style="width: 100%">
				    </p>
				    <h3 id="系统内数据名词解释">名词详细解释</h3>

				    <ol>
				        <li>激活数 <br>
				            当日玩游戏的用户数量（只要打开任意一款游戏即算活跃用户，并进行去重，同一个用户当天打开多款游戏只计一次）</li>
				        <li>新增用户数 <br>
				            当日新增的注册用户数。微信里点击游戏链接进行微信网页授权即成为注册用户，浏览器里需用户进行第三方授权或手机号登陆才算作注册用户。</li>
				        <li>老用户活跃数 <br>
				            当日老用户激活数。</li>
				        <li>付费人数 <br>
				            当日充值的总用户数（已去重，即同一个用户充值多笔只计一次）</li>
				        <li>总付费金额(元) <br>
				            当日所有用户充值的总金额</li>
				        <li>新增付费人数 <br>
				            当日新增充值的总用户数（已去重，即同一个用户充值多笔只计一次）</li>
				        <li>新增总付费金额(元) <br>
				            当日新增用户充值的总金额</li>
				       	<li>付费次数<br>
				            当日用户总付费次数</li>
				        <li>ARPU（元） <br>
				            Average Revenue Per User, 即游戏内活跃用户贡献的平均收入，ARPU=充值总流水÷日活跃用户数，主要用来衡量活跃用户的质量</li>
				        <li>ARPPU（元） <br>
				            Average Revenue Per Paying User, 即游戏内付费用户贡献的平均收入，ARPPU=即充值流水÷付费用户数，主要用来衡量付费用户的质量</li>
				        <li>付费率 <br>
				            用于查看每日付费用户占活跃用户的比率，即付费率=付费用户数÷活跃用户数</li>
				    </ol>
                </div>
            </section>
        </div>
    </div>
</div>