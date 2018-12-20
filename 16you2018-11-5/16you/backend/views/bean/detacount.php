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
	            <form action="/bean/detacount.html">
	              <input type="hidden" value="<?php echo $uid?>" name="id">
	              <label class="control-label">年月选择：</label>
	              <input id="starttime" class="input-text Wdate"
						type="text" value="<?php echo $year;?>"
						name="starttime" style="height:34px;padding:6px 12px;border:1px solid #ccc;color: #555;border-radius: 4px;"
						onfocus="WdatePicker({dateFmt:'yyyy',maxDate:''})">
	               <select name="month" id="time1" style="height:34px;padding:6px 12px;border:1px solid #ccc;border-radius: 4px;">
	                <?php for ($j=1; $j <= 12; $j++):?>
	                  <option value="<?php echo $j; ?>"><?php echo $j; ?> 月</option>
	                <?php endfor; ?>
	              </select>
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
var month = "<?php echo $month; ?>";
month = (month.substring(0,1)==0)?month.substring(1,2):month;
$("#time1 option[value='"+month+"']").attr("selected",true);
var udata = "<?php echo $uarr;?>";
var darrdata = "<?php echo $darr;?>";
var urderdata = eval("[" + udata + "]");
var darr = eval("[" + darrdata + "]");
$(function () {
	 $('#container').highcharts({
	      title: {
	          text: "平台粉丝统计详情",
	          x: -20 //center
	      },
	      subtitle: {
	          text: "统计用户关注情况",
	          x: -20
	      },
	      xAxis: {
	          categories: darr
	      },
	      yAxis: {
	          title: {
	              text: ' 单位(/人)'
	          },
	          plotLines: [{
	              value: 0,
	              width: 1,
	              color: '#808080'
	          }]
	      },
	      tooltip: {
	          valueSuffix: '个'
	      },
	      legend: {
	          layout: 'vertical',
	          align: 'right',
	          verticalAlign: 'middle',
	          borderWidth: 0
	      },
	      credits:{
	        enabled:false, //是否显示版权信息
	      },
	      series: [{
	          name: '关注数',
	          data: urderdata
	      }]
	  });
});
</script>