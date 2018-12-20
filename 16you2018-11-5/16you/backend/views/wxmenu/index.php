<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
     <h3>微信菜单设置</h3>
     <ul class="breadcrumb">
         <li><a href="<?=Url::to(['tomenu'])?>">微信管理</a></li>
         <li><a href="<?=Url::to(['tomenu'])?>">微信菜单</a></li>
         <li class="active">设置</li>
      </ul>
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    微信菜单设置   
                </header>
                <section class="panel-body">
                    <div class="clearfix">
                        <?php if(count($model)<3): ?>
                        <button class="btn btn-primary" onclick="window.location.href='/wxmenu/add.html?appid=<?php echo $data['wxappid']; ?>'"><i class="fa  fa-plus"></i> 添加微信菜单设置 </button>&nbsp;  
                        <?php endif; ?>
                        <div class="btn-group pull-right">
                            <div class="form-group">
                                <a href='javascript:void(0)' class="btn btn-info btn_model" >生成微信菜单设置</a>
                                <a href='javascript:void(0)' class="btn btn-danger btn_del" >删除菜单接口</a>
                            </div>
                        </div>
                    </div>
                    <header class="panel-heading custom-tab turquoise-tab">
                        <?php if($data): ?>
                        <ul class="nav nav-tabs">
                            <?php $i=1;foreach ($model as $key => $val) {
                                echo "<li id=".$i."><a href='#tab_3_".$i."' data-toggle='tab'>".$val['name']."</a></li>";$i++;
                            };?>
                        </ul>
                        <?php endif; ?>
                    </header>
                    <div class="panel-body">
                        <div class="tab-content">
                            <?php if($data): ?>
                            <?php $j=1;foreach ($model as $k => $v):?>
                            <div id="tab_3_<?php echo $j;$j++?>" class="tab-pane"><?php if(isset($v['type'])): ?>
                            <p>类型：<?php echo $v['type']; ?></p>
                            <p>响应的内容：<?= Html::encode(isset($v['key'])?$v['key']:$v['content']);?></p>
                            <?php else: ?>
                              <?php foreach ($v['sub_button'] as $ke => $va):?>
                                <span style="float:right">
                                  <a href="<?=Url::to(['toeditmenu','findex'=>$k,'tindex'=>$ke]);?>" class="btn yellow mini"><i class="icon-pencil"></i> 编辑</a>
                                  <a href="javascript:void(0)" class="btn red mini" onclick="menu_del(<?php echo $k;?>,<?php echo $ke; ?>)"><i class="icon-trash icon-white"></i> 删除</a>
                                </span>
                                <p>子菜单名：<?php echo $va['name'];?></p>
                                <p>类型：<?php echo $va['type'];?> </p>
                                <p>响应的内容：<?= Html::encode(isset($va['key'])?$va['key']:$va['content']);?></p>
                                <hr>
                              <?php endforeach; ?>
                            <?php endif; ?>
                           <div class="btn-group">
                              <a class="btn green" href="#" data-toggle="dropdown">
                              <i class="icon-cog"></i> 操作
                              <i class="icon-angle-down"></i>
                              </a>
                              <ul class="dropdown-menu bottom-top">
                                <?php if(isset($v['sub_button'])): ?>
                                <li><a href="<?=Url::to(['toeditmenu','findex'=>$k,'jump'=>0]);?>"><i class="icon-pencil"></i> 编辑一级菜单</a></li>
                                <?php endif; ?>
                                <?php if(!isset($v['sub_button'])||count($v['sub_button'])<5): ?>
                                <li><a href="<?=Url::to(['add','findex'=>$k,'name'=>$v['name'],'appid'=>$v['wxappid']])?>"><i class="icon-plus"></i> 添加子菜单</a></li>
                                <?php endif; ?>
                                <?php if(!isset($v['sub_button'])): ?>
                                <li><a href="<?=Url::to(['toeditmenu','findex'=>$k])?>"><i class="icon-trash"></i> 编辑菜单</a></li>
                                <?php endif; ?>
                                <li><a href="javascript:void(0)" onclick="menu_del(<?php echo $k;?>,'-1')"><i class="icon-remove"></i> 删除菜单</a></li>
                              </ul>
                            </div>
                          </div>
                          <?php endforeach; ?>
                        </div>

                      </div>
                      <?php else: ?>
                        暂时没有数据
                      <?php endif; ?>
                        </div>
                    </div>
                </section>
            </section>
        </div>
    </div>
</section>
<script src="/media/js/layer/layer.js"></script>
<script>
    $('#1').addClass('active');
    $("#tab_3_1").addClass('active');

    /*---生成微信菜单设置---*/
  $(".btn_model").click(function(){
    var model = '<?php echo serialize($model); ?>';
    $.ajax({
      url:'/wxmenu/creatmenutowx.html',
      type:'post',
      dataType:'json',
      data:{'menudata':model},
      success:function(data){ 
          layer.msg(data.info);
      }
    })
  })

  /*---删除菜单接口---*/
  $(".btn_del").click(function(){
    layer.confirm("删除菜单接口吗 ？",function(){
      $.ajax({
        url:'/wxmenu/delallmenu.html',
        type:'post',
        success:function(data){ 
            layer.msg(data);
        }
      })
    })
  })

  /*---删除菜单---*/
  function menu_del(findex,tindex){
    layer.confirm("删除菜单接口吗 ？",function(){
      $.ajax({
        url:'/wxmenu/delmenu.html',
        type:'post',
        data:{'findex':findex,'tindex':tindex},
        success:function(data){ 
          if(data==1){
            window.location.reload();
          }else{
            layer.msg(data);
          }
        }
      })
    })
  }
</script>