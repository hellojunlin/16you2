<?php 
use yii\helpers\Url;
?>
<link href="/media/css/common.css" rel="stylesheet">
<div class="page-heading">
    <h3>订单管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">订单管理</a>
        </li>
        <li>
            <a href="/order/index.html">订单列表</a>
        </li>
        <li class="active"> 添加 </li>
    </ul> 
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">订单管理</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" action="<?=Url::to(['create'])?>" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 道具名称</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="道具名称" data-original-title="道具名称" name="propname" maxlength="16" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">游戏名称</label>
                            <div class="col-sm-5">
                               <select class="form-control tooltips" tabindex="3" name="gid" id="role">
                                <?php if($game){foreach ($game as $res):?>
                                <option value="<?php echo $res->id;?>"><?php echo $res->name;?></option>
                                <?php endforeach;}?>
                               </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">用户名称</label>
                            <div class="col-sm-5">
                               <select class="form-control tooltips" tabindex="3" name="uid" id="role">
                                <?php if($user){foreach ($user as $r):?>
                                <option value="<?php echo $r->id;?>"><?php echo json_decode($r->username);?></option>
                                <?php endforeach;}?>
                               </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 总价格</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="总价格" data-original-title="总价格" name="price"  oninput="if(value.length>16)value=value.slice(0,16)" required /><span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 数量</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="数量" data-original-title="数量" name="num" oninput="if(value.length>16)value=value.slice(0,16)" required /><span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">订单状态</label>
                            <div class="col-sm-5">
                               <select class="form-control tooltips" tabindex="3" name="state" id="role">
                                <option value="1">待付款</option>
                                <option value="2">付款成功</option>
                                <option value="3">退款中</option>
                                <option value="4">已退款</option>
                                <option value="5">付款失败</option>
                               </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 厂商订单号</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="订单号" data-original-title="订单号" name="orderID" maxlength="50" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 交易订单号</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="交易订单号" data-original-title="交易订单号" name="transaction_id" maxlength="50" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10 col-sm-offset-2 col-sm-10">
                                <button class="btn btn-primary" type="submit">保 存</button>
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
<script>
//判断添加的订单号是否已存在
$("input[name='transaction_id']").change(function(){
    var orderID = $(this).val();
    if(orderID == ''){
        $("input[name='transaction_id']").next('span').html('交易订单号不能为空').css('color','#900');
    }else{
        $.ajax({
            url:'/order/orderid.html',
            type:'post',
            data:{'transaction_id':transaction_id},
            success:function(data){
                if(data == 1){
                  $("input[name='transaction_id']").val("");
                    $("input[name='transaction_id']").next('span').html(orderID+' 已存在!').css('color','#900');
                }else{
                  $("input[name='transaction_id']").next('span').html('');
                }
            }
        });
    }
})
</script>