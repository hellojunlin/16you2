<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>
        订单管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">订单管理</a>
        </li>
        <li class="active">订单统计 </li>
    </ul>
</div>
<input type="hidden" name="valdata" id="valdata" value="<?= $valdata['valdata'];?>">
<input type="hidden" name="valdata1" id="valdata1" value="<?= $valdata['valdata1'];?>">
<input type="hidden" name="valdata2" id="valdata2" value="<?= $valdata['valdata2'];?>">
<input type="hidden" name="valdata3" id="valdata3" value="<?= $valdata['valdata3'];?>">
<input type="hidden" name="valdata4" id="valdata4" value="<?= $valdata['valdata4'];?>">
<input type="hidden" name="darr" id="darr" value="<?= $darr;?>">
<section class="wrapper">
  <div class="row">
    <div class="col-lg-12">
      	<section class="panel">
	        <header class="panel-heading no-border">
	            <form action="/order/tocount.html">
	              <label class="control-label">年月选择：</label>
	              <input id="starttime" class="input-text Wdate"
						type="text" value="<?php echo $time;?>"
						name="time" style="height:34px;padding:6px 12px;border:1px solid #ccc;color: #555;border-radius: 4px;"
						onfocus="WdatePicker({dateFmt:'yyyy',maxDate:''})">
	              <select name="time1" id="time1" style="height:34px;padding:6px 12px;border:1px solid #ccc;border-radius: 4px;">
	                <?php for ($j=1; $j <= 12; $j++):?>
	                  <option value="<?php if($j<10){$j = '0'.$j;} echo $j; ?>"><?php echo $j; ?> 月</option>
	                <?php endfor; ?>
	              </select>
	              <input class="btn btn-primary" type="submit" value="查询"/>
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
$(function(){
  var time_s1 = "<?php echo $time1; ?>";
  $("#time1 option[value='"+time_s1+"']").attr("selected",true);
  var valdata = eval("[" + $("#valdata").val() + "]");
  var valdata1 = eval("[" + $("#valdata1").val() + "]");
  var valdata2 = eval("[" + $("#valdata2").val() + "]");
  var valdata3 = eval("[" + $("#valdata3").val() + "]");
  var ndata = eval("[" + $("#data").val() + "]");
  var darr = eval("[" + $("#darr").val() + "]");

  $('#container').highcharts({
      title: {
          text: "订单统计",
          x: -20 //center
      },
      subtitle: {
          text: "统计用户订单情况",
          x: -20
      },
      xAxis: {
          categories: darr
      },
      yAxis: {
          title: {
              text: ' 单位(/个)'
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
          name: '待付款订单总数',
          data: valdata
      },{ 
          name: '付款成功订单总数',
          data: valdata1
      },{ 
          name: '退款中订单总数',
          data: valdata2
      },{ 
          name: '已退款订单总数',
          data: valdata3
      },{ 
          name: '付款失败订单总数',
          data: valdata4
      }]
  });
});
</script>