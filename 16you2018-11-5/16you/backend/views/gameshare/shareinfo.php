<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
     <h3>游戏分享</h3>
     <ul class="breadcrumb">
         <li><a href="<?=Url::to(['game/index'])?>">游戏</a></li>
         <li class="active"><?php echo $name; ?></li>
      </ul>
</div>
<section class="wrapper">
    <section class="panel">
        <header class="panel-heading">
            游戏记录
            <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-mail-forward" onclick="window.history.back();">返回</a>
             </span>
        </header>
        <?php if(yii::$app->session['result']): ?>
        <div class="panel-body">
            <div class="clearfix">
                <div class="btn-group">
                    <button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['toadd'])?>'"><i class="fa  fa-plus"></i> 添加分享数据</button>&nbsp;
                </div>
            </div>
        </div>
        <?php endif; ?>
    	<div class="row">
    		<?php if($share): ?>
    		<?php foreach($share as $v): ?>
            <div class="col-sm-6">
                <section class="panel">
                    <header class="panel-heading no-border">
                    	<?php 
                    		switch ($v['type']) {
    	                		case '1':
    	                			echo '<span class="label label-primary">分享给好友</span>';
    	                			break;
    	                		case '2':
    	                			echo '<span class="label label-warning">分享到朋友圈</span>';
    	                			break;
    	                		case '3':
    	                			echo '<span class="label label-success">分享到 QQ </span>';
    	                			break;
                    	} ?>
<!--                         <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                        </span> -->
                    </header>
                    <div class="panel-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th><span class="label label-danger">键名</span></th>
                                <th><span class="label label-warning">内容</span><a href="javascript:;" class="btn btn-danger btn-xs pull-right" onclick="del(<?php echo $v['id']; ?> )"><i class="fa fa-trash-o"></i> 删除</a><a href="javascript:;" class="btn btn-info btn-xs pull-right" onclick="window.location.href='/gameshare/toedit/<?php echo $v['type']; ?>.html'"><i class="fa fa-edit"></i> 编辑</a></th>
                            </tr>
                            </thead>
                            <tbody>
    	                        <tr>
    	                            <td>标题</td>
    	                            <td><?php echo $v['title']; ?></td>
    	                        </tr>
                                <tr>
                                    <td>链接</td>
                                    <td><?php echo $v['link']; ?></td>
                                </tr>
                                <tr>
                                    <td>分享描述</td>
                                    <td><?php echo $v['desc']; ?></td>
                                </tr>
                                <tr>
                                    <td>分享图标</td>
                                    <td><img src="<?php echo $v['imgUrl']; ?>" alt="" width='200'></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <?php endforeach; ?>
        	<?php endif; ?>
        </div>
    </section>
</section>
<script>
    //删除管理员
    function del(id){
        layer.confirm('确认要删除吗？',function(){
            layer.msg('正在删除中。。。');
            $.ajax({ 
                url:"/gameshare/delete.html",
                type:'post',
                data:{'id':id},
                success:function(data){
                    if(data==1){
                        layer.msg('删除成功',{icon:6,time:2000});
                        setInterval(window.location.reload(),1000);
                    }else{
                        layer.msg('删除失败',{icon:5,time:2000});
                    }
                }
            }) 
        });
    }
</script>