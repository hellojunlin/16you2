<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
    <h3>使用说明</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">表格使用说明</a>
        </li>
    </ul>
</div>
<div class="wrapper">
	<div class="row">
        <div class="col-sm-12">
            <section class="panel panel-success">
                <header class="panel-heading">
	               	表格使用说明
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
                <div class="panel-body">
		            <div id="editor-container">
			            <div id="columns">
			            	<div id="column2">
		                        <div contenteditable="true">
		                        	<h3>
		                        		<strong> 查看表头解释</strong>
		                            </h3>
		                            <p>汇总统计内，鼠标放置在问号上，即可显示该数据名词的解释（如下图）</p>
		                             <table class="table table-bordered table-striped table-condensed cf">
				                        	<thead class="cf">
						                        <tr>
						                            <th>日期</th>
						                            <th>新增用户数
						                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当日新增的注册用户数">
	                            							<i class="fa fa-question-circle"></i>
	                            						</a>
	                            					</th>
						                            <th>日活跃用户数
						                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当日玩游戏的用户数量">
	                            							<i class="fa fa-question-circle"></i>
	                            						</a>
						                            </th>
						                            <th>付费用户数
						                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当日充值的总用户数">
	                            							<i class="fa fa-question-circle"></i>
	                            						</a>
						                            </th>
						                            <th>付费率
						                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当日付费用户占活跃用户的比率">
	                            							<i class="fa fa-question-circle"></i>
	                            						</a>
						                            </th>
						                            <th>充值流水
						                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当日所有用户充值的总金额">
	                            							<i class="fa fa-question-circle"></i>
	                            						</a>
						                            </th>
						                            <th>ARPU(元)
						                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="游戏内活跃用户贡献的平均收入">
	                            							<i class="fa fa-question-circle"></i>
	                            						</a>
						                            </th>
						                            <th>ARPPU(元)
						                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="游戏内付费用户贡献的平均收入">
	                            							<i class="fa fa-question-circle"></i>
	                            						</a>
						                            </th>
						                             <th>当日收益(元)
						                             	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当日所获得的分成收益">
	                            							<i class="fa fa-question-circle"></i>
	                            						</a>
						                             </th>
						                        </tr>
				                        	</thead>
				                        </table>
		                           <h3><strong>
		                                名词详细解释</strong>
		                            </h3>
		                              <p>1、新增用户数</p>
		                              <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;当日新增的注册用户数。微信里点击游戏连接进行微信网页授权即成为注册用户，浏览器里用户进行第三方授权才算做注册用户</p>
		                              <p>2、日活跃用户数</p>
		                              <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;当日玩游戏的用户数量（只要打开任意一款游戏即算活跃用户，并进行去重，同一个用户当天打开多款有限只计一次）</p>
		                              <p>3、付费用户数</p>
		                              <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;当日充值的总用户数（已去重，即可一个用户充多笔只记一次）</p>
		                              <p>4、付费率</p>
		                              <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;用于查看每日付费用户占活跃用户的比率，即付费率=付费用户数÷活跃用户数</p>
		                              <p>5、充值流水（元）</p>
		                              <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;当日所有用户充值的总金额</p>
		                              <p>6、ARPU（元）</p>
		                              <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Average Revenus User，即游戏内活跃用户贡献的平均收入，ARPU=充值总流水÷日活跃用户数，主要用来衡量活跃用户的质量</p>
		                              <p>7、ARPPU（元）</p>
		                              <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Average Revenus Paying User，即游戏内付费用户贡献的平均收入，ARPPU=即充值流水÷付费用户数，主要用来衡量付费用户的质量</p>
		                              <p>8、当日收益（元）</p>
		                              <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;当日所获得的分成收益</p>
		                        </div>
		                    </div>
			            </div>
					</div>
                </div>
            </section>
        </div>
    </div>
</div>