<?php 
use yii\helpers\Url;
?>
<link href="/media/css/common.css" rel="stylesheet">
<div class="page-heading">
    <h3>邮件管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">邮件管理</a>
        </li>
        <li>
            <a href="/gift/index.html">邮件列表</a>
        </li>
        <li class="active"> 添加 </li>
    </ul> 
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">邮件管理</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" action="<?=Url::to(['create'])?>" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span>用户ID</label>
                            <div class="col-sm-5">
                                <textarea rows="4" class="form-control" name="uid" required placeholder="提示：每一行为一个用户ID，如需发给所有用户，则在输入框中输入 1 即可"></textarea>
                                <span style="color:red">提示：每一行为一个用户ID，如需发给所有用户，则在输入框中输入 1 即可</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 邮件标题</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="邮件标题" data-original-title="邮件标题" name="title" maxlength="12" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span>邮件内容</label>
                            <div class="col-sm-5">
                                <textarea rows="3" class="form-control" name="content" required maxlength="72" placeholder="邮件内容"></textarea>
                                <span style="color:red">提示：72个字以内</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><span style="color:#e00">*</span>是否有礼包</label>
                            <div class="col-sm-5" style="margin-top:5px">
                                <input type="radio" name="state" checked="checked" value="2" style="margin-left:10px">
                                <label> 否 </label>
                                <?php if(!empty($gift)): ?>
                                <input type="radio" name="state" value="1" style="margin-left:10px">
                                <label> 是 </label>
                                <?php else: ?>
                                <p style="color:red">如需选择礼包，请先到礼包管理添加邮件礼包</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group hide" id="hide_val">
                            <label class="col-sm-2 col-sm-2 control-label"><span style="color:#e00">*</span>选择礼包</label>
                            <div class="col-sm-5">
                                <div class="selectdivbox" style="width:100%;">
                                   <input type="text" class="hidden-input" value="" name="gift_content"/>
                                    <button type="button" class="btn selectbtn" style="text-align:left;">
                                        <span class="btntxt">选择礼包</span>
                                        <span class="caret"></span>
                                    </button>
                                    <div id="dropdownoption" class="dropdown-menu" style="width:100%;">
                                        <div class="live-filtering">
                                            <div class="searchinput">
                                                <input id="searchname" type="text" class="form-control live-search" autocomplete="off" placeholder="搜索礼包名称">
                                            </div>
                                            <div class="list-to-filter">
                                                <ul class="list-unstyled">
                                                <?php if($gift): ?>
                                                  <?php foreach ($gift as $v):?>
                                                    <li class="filter-item items" data-value="<?php echo $v['number'];?>"><?php echo $v['gift_name'];?></li>
                                                 <?php endforeach;?>
                                                  <?php endif; ?>
                                                </ul>
                                                <div class="no-search-results">搜索不到结果</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10 col-sm-offset-2 col-sm-10">
                                <button class="btn btn-primary" type="submit" id="submit">保 存</button>
                                <button class="btn btn-default" type="reset">重 写</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</section>
<script>
$("input:radio[name='state']").change(function (){
    var _val = $(this).val();
    if(_val==1){
        $("#hide_val").removeClass('hide');
    }else{
        $("#hide_val").addClass('hide');
    }
})
//选择
$('.selectbtn').click(function (event){
    $('#dropdownoption').toggle();
    $(document).on('click',function(){//对document绑定一个影藏Div方法
        $('#dropdownoption').hide();
    });
    event.stopImmediatePropagation();//阻止事件向上冒泡
});
$('#dropdownoption').click(function (event){
    event.stopImmediatePropagation();//阻止事件向上冒泡
})
//选择选项
$('.items').click(function(){
    var lival = $(this).text();
    var dataval = $(this).attr('data-value');
    $('.hidden-input').attr('value',dataval);
    $('.btntxt').text(lival);
    $('#dropdownoption').hide();
})
//搜索匹配
function funsearch(){
    var searchname = $.trim($('#searchname').val());
    if(searchname ==""){
        $('.list-unstyled li').show();
        $('.no-search-results').hide();
    }else{
        $('.list-unstyled li').each(function(){
            var litxt = $(this).text();
            if(litxt.indexOf(searchname) != -1){
                $(this).attr('class','showli').show()
                var lilen =  $('.list-unstyled').find('.showli').length;
                if(lilen > 0 ){
                    $('.no-search-results').hide();
                }
            }else{
                $(this).removeAttr('class').hide();
                var lilen1 = $('.list-unstyled').find('.showli').length;
                if(lilen1 <= 0 ){
                    $('.no-search-results').show();
                }
                
            }
        })
    }
}
$('#searchname').bind('input propertychange',function(){
    funsearch();
})
</script>