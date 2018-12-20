<?php
use yii\helpers\Url;
?>
<link href="/media/css/common.css" rel="stylesheet">
<link rel="Stylesheet" type="text/css" href="/media/css/jPicker-1.1.6.min.css" />
<link rel="Stylesheet" type="text/css" href="/media/css/jPicker.css" />
<script src="/media/js/jpicker-1.1.6.js" type="text/javascript"></script>
<style type="text/css">
 .jPicker.Container {
    top: 15% !important;
    left: 50% !important;
    margin-left: -272px;
    position: fixed !important;
 }
 .hideinput{
 	opacity: 0;
 	width: 0;
 }
</style>
<div class="page-heading">
    <h3>消息管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">消息管理</a>
        </li>
        <li>
            <a href="/company/index.html">模板记录</a>
        </li>
        <li class="active"> 添加 </li>
    </ul>
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="wrapper">	
    	<div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" id='signupok' enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">模板类型</label>
                            <div class="col-sm-5">
                                <select class="form-control m-bot15" name="temp" id="temp">
                                 <?php $sendtmp = yii::$app->params['sendTmp']; foreach ($sendtmp as $k=>$temp):?>
                                    <option value='<?php echo $k;?>'><?php echo $temp;?></option>
                                 <?php endforeach;?>
                                </select> 
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 control-label">标题</label><span style="color:#e00">（只做识别，不会显示在微信模板消息上，可不填）</span> 
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="标题" data-original-title="标题" name="title" maxlength="16" /><span></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 control-label">跳转链接</label><span style="color:#e00">（不填则不跳转）</span> 
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="链接" data-original-title="链接" name="url" maxlength="255" /><span></span>
                            </div>
                       </div>
                        <div class="group form-group">
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <input type="button" name="signup" id="submit" class="btn btn-primary" value="添加">
                               <!--  <button class="btn btn-primary" type="submit">保 存</button> -->
                                <button class="btn btn-default" type="reset">重 写</button>
                            </div>
                        </div>
                    </form>
                </div>
    		</section>
        </div>
    </div>
</section>
<script src="/media/js/layer/layer.js"></script>
<!--<script type="text/javascript" src="/media/js/area.js"></script>
<script type="text/javascript">_init_area();</script>-->
<script>
$(document).ready(function(){
	 var dataarr = ['内容','订单号','商品信息','订单金额','备注'] ;  
	 var index = 0;
	 $.each(dataarr, function(k,v){   
		     var form = 'form'+index; 
			 var form = $('<div>').addClass('form-group').appendTo($('.group'));
			 $('<label>').addClass('col-sm-2 control-label').append(v).appendTo(form);
			 var div = 'div'+index;
			 var div= $('<div>').addClass('col-sm-5').appendTo(form);
			 $('<input type="text" name="keyword[]" maxlength="100 required">').attr('placeholder',v).addClass('form-control tooltips').appendTo(div);
			 $('<span>').appendTo(div);
			 $('<input>').addClass('select-color btn btn-primary').attr({'id':'AlterColors'+index,'value':'000000','type':'text'}).appendTo(form);
			 var hiddeninput = $('<input>').attr({'type':'text','class':'hideinput AlterColors'+index+'','value':'000000','name':'color[]'}).appendTo(form);
			 $.fn.jPicker.defaults.images.clientPath='/media/images/selectcolor/';
             $('#AlterColors'+index).jPicker(
             	{window:{title:'Color Interaction Example'}},
             	function(color, context)
	          	{
		            //var all = color.val('all');
                    var inputcolor = $(this)[0].value;
                    hiddeninput.attr('value',inputcolor);
	          	},
              );
             $('<span>').addClass('wramtpis').appendTo(form);
             index++; 
		 });
	
	$('#temp').change(function(){
		 var selected = $(this).val();
		 if(selected=='Xh9z-YEgvGauhwWxgZrdKAA9ET-ow6PZdEv39bdcKgk'){//下单成功通知
			 $('.group').empty();//删除之前内容
			 var dataarr = ['内容','订单号','商品信息','订单金额','备注'] ;  
			 var index = 0;
			 $.each(dataarr, function(k,v){   
				     var form = 'form'+index; 
					 var form = $('<div>').addClass('form-group').appendTo($('.group'));
					 $('<label>').addClass('col-sm-2 control-label').append(v).appendTo(form);
					 var div = 'div'+index;
					 var div= $('<div>').addClass('col-sm-5').appendTo(form);
					 $('<input type="text" name="keyword[]" maxlength="100" required>').attr('placeholder',v).addClass('form-control tooltips').appendTo(div);
					 $('<span>').appendTo(div);
					 $('<input>').addClass('select-color btn btn-primary').attr({'id':'AlterColors'+index,'value':'000000','type':'text','name':'color[]'}).appendTo(form);
		            var hiddeninput = $('<input>').attr({'type':'text','class':'hideinput AlterColors'+index+'','value':'000000'}).appendTo(form);
					 $.fn.jPicker.defaults.images.clientPath='/media/images/selectcolor/';
		             $('#AlterColors'+index).jPicker(
		             	{window:{title:'Color Interaction Example'}},
		             	function(color, context)
			          	{
		                    var inputcolor = $(this)[0].value;
		                    hiddeninput.attr('value',inputcolor);
			          	},
		              );
		             $('<span>').addClass('wramtpis').appendTo(form);
					 index++; 
				 }); 
	     }else if(selected=='4-zekSNoywhQa537wZuSjPcZ4n3QBNjsA6ATPVdc4YY'){ //退款通知
		    	 $('.group').empty();//删除之前内容
		    	 var dataarr = ['内容','退款原因','退款金额','提醒'] ;  
				 var index = 0;
				 $.each(dataarr, function(k,v){   
					     var form = 'form'+index; 
						 var form = $('<div>').addClass('form-group').appendTo($('.group'));
						 $('<label>').addClass('col-sm-2 control-label').append(v).appendTo(form);
						 var div = 'div'+index;
						 var div= $('<div>').addClass('col-sm-5').appendTo(form);
						 $('<input type="text" name="keyword[]" maxlength="100">').attr('placeholder',v).addClass('form-control tooltips').appendTo(div);
						 $('<span>').appendTo(div);
						 $('<input>').addClass('select-color btn btn-primary').attr({'id':'AlterColors'+index,'value':'000000','type':'text'}).appendTo(form);
						 var hiddeninput = $('<input>').attr({'type':'text','class':'hideinput AlterColors'+index+'','value':'000000','name':'color[]'}).appendTo(form);
						 $.fn.jPicker.defaults.images.clientPath='/media/images/selectcolor/';
			             $('#AlterColors'+index).jPicker(
			             	{window:{title:'Color Interaction Example'}},
			             	function(color, context)
				          	{
			                    var inputcolor = $(this)[0].value;
			                    hiddeninput.attr('value',inputcolor);
				          	},
			              );
			             $('<span>').addClass('wramtpis').appendTo(form);
						 index++; 
					 }); 
		 }
		})
   

		//异步提交数据
		$('#submit').click(function(){
			   $.ajax({		 
				type:'post',
				dataType:'json',
				data:$('#signupok').serialize(),
				url:'/sendmsg/add.html',
				success:function(data){
					if(data.errorcode==0){
						 layer.msg(data.info, {icon: 1,time:2000});
						 setTimeout(function (){
							 location.href="/sendmsg/index.html";
						},1000);
					}else if(data.errorcode==1001){
						   layer.msg(data.info, {icon: 1,time:2000});
			    		}else{
			    		   layer.msg('添加失败', {icon: 1,time:2000});
			    		}
				}
			    });
		});
})

</script>