<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
    <h3>
        用户管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">用户列表</a>
        </li>
        <li class="active"> 用户资料</li>
    </ul>
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel panel-info">
                <header class="panel-heading">
                    用户资料
                    <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                     </span>
                </header>   
                <div class="panel-body">
                    <div class="tools pull-left col-md-2 col-sm-2">
                        <center><strong>用户ID : </strong> <?php echo $model['Unique_ID']; ?></center>
                        <br /><img src="<?php echo $model['head_url']; ?> " width='200' alt="">
                    </div>
                    <div class="pull-left col-md-9 col-sm-9">
                        <form class="form-horizontal" role="form">
                            <div class="form-group">
                                <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">用户编号:</label>
                                <div class="col-lg-10">
                                    <label class="control-label"><?php echo $model['id']; ?></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">用户名称:</label>
                                <div class="col-lg-10">
                                    <label class="control-label"><?php echo $model['username']; ?></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">性别:</label>
                                <div class="col-lg-10">
                                    <label class="control-label"><?php echo ($model['sex']==1)?'男':(($model['sex']==2)?'女':'未知'); ?></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">地区:</label>
                                <div class="col-lg-10">
                                    <label class="control-label"><?php echo $model['province']."&nbsp;&nbsp;&nbsp;".$model['city']; ?></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">手机号码:</label>
                                <div class="col-lg-10">
                                    <label class="control-label"><?php echo $model['phone']; ?></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">QQ:</label>
                                <div class="col-lg-10">
                                    <label class="control-label"><?php echo $model['qq']; ?></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">微信号:</label>
                                <div class="col-lg-10">
                                    <label class="control-label"><?php echo $model['wxnumber']; ?></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">VIP等级:</label>
                                <div class="col-lg-10">
                                    <label class="control-label"><?php echo $model['vip']; ?></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">积分:</label>
                                <div class="col-lg-10">
                                    <label class="control-label"><?php echo $model['integral']; ?></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">真实姓名:</label>
                                <div class="col-lg-10">
                                    <label class="control-label"><?php echo $model['realname']; ?></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">身份证号码:</label>
                                <div class="col-lg-10">
                                    <label class="control-label"><?php echo $model['IDcard']; ?></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">支付宝姓名:</label>
                                <div class="col-lg-10">
                                    <label class="control-label"><?php echo $model['alipayname']; ?></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">支付宝账号:</label>
                                <div class="col-lg-10">
                                    <label class="control-label"><?php echo $model['alipayaccount']; ?></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">OpenID:</label>
                                <div class="col-lg-10">
                                    <label class="control-label"><?php echo $model['openid']; ?></label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>