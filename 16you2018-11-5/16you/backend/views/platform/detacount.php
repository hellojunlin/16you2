<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>
       平台管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['tocount'])?>">统计</a>
        </li>
        <li class="active">详细统计 </li>
    </ul>
</div>

<section class="wrapper">
  <div class="row">
    <div class="col-lg-12">
      	<section class="panel">
	        <header class="panel-heading no-border">
	            <form action="/platform/detacount.html">
	              <input type="hidden" value="<?php echo $pid?>" name="id">
	              <label class="control-label">年月选择：</label>
	              <input id="starttime" class="input-text Wdate"
						type="text" value="<?php echo $year;?>"
						name="starttime" style="height:34px;padding:6px 12px;border:1px solid #ccc;color: #555;border-radius: 4px;"
						onfocus="WdatePicker({dateFmt:'yyyy',maxDate:''})">
	              <input type="submit" value="查询" class="btn btn-primary"/>
	            </form>
	        </header>
	        <div class="panel-body">
	          <div class="col-sm-12 ">
	          <section id="no-more-tables">
	            <div class="portlet-body">
	              <div id="site_statistics_content">
	                <div id="container" style="min-width:700px;height:400px"></div>
	              </div>
	            </div>
	          </section>
	          </div>
	        </div>
      	</section>
    </div>
  </div>
</section>
<script type="text/javascript" src="/media/js/Highcharts/4.1.7/js/highcharts.js"></script>
<script type="text/javascript">
var odata = "<?php echo $orderdata;?>";
var pdata = "<?php echo $pricedata;?>";
var orderdata = eval("[" + odata + "]");
var pricedata = eval("[" + pdata + "]");
$(function () {
    $('#container').highcharts({
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: '平台订单统计详情'
        },
        subtitle: {
            text: '统计每月订单情况'
        },
        xAxis: [{
            categories: ['1', '2', '3', '4', '5', '6',
                         '7', '8', '9', '10', '11', '12'],
            crosshair: true
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value}单',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            title: {
                text: '订单量',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }
        }, { // Secondary yAxis
            title: {
                text: '交易金额',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            labels: {
                format: '{value} 元',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            opposite: true
        }],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            x: 120,
            verticalAlign: 'top',
            y: 100,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        series: [{
            name: '交易金额',
            type: 'column',
            yAxis: 1,
            data: pricedata,
            tooltip: {
                valueSuffix: ' 元'
            }
        }, {
            name: '订单量',
            type: 'spline',
            data: orderdata,
            tooltip: {
                valueSuffix: '单'
            }
        }]
    });
});
</script>