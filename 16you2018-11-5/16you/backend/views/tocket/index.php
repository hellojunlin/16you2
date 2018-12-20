<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
     <h3>微信二维码</h3>
     <ul class="breadcrumb">
         <li><a href="<?=Url::to(['index'])?>">微信管理</a></li>
         <li class="active">唯一二维码</li>
      </ul>
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    微信二维码   
                </header>
                <section class="panel-body">
                    <div class="clearfix">
                        <button class="btn btn-primary" onclick="window.location.href='/tocket/add.html'"><i class="fa  fa-plus"></i> 添加微信二维码 </button>
                    </div>
                    <header class="panel-heading custom-tab turquoise-tab"></header>
                    <div class="panel-body">
                      <div class="tab-content">    
                      </div>
                    </div>
                  </div>
                </div>
              </section>
            </section>
        </div>
    </div>
</section>