<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="ThemeBucket">
  <title>16游系统</title>
  <!--icheck-->
  <link href="/media/js/iCheck/skins/square/red.css" rel="stylesheet">
  <link href="/media/js/iCheck/skins/square/blue.css" rel="stylesheet">
  <!--dashboard calendar-->
  <link href="/media/css/clndr.css" rel="stylesheet">
  <link href="/media/css/selectstyle.css" rel="stylesheet">
  <!--common-->
<script src="/media/js/jquery-1.10.2.min.js"></script>
  <link href="/media/css/style.css" rel="stylesheet">
  <link href="/media/css/style-responsive.css" rel="stylesheet">
  <script src="/media/js/layer/layer.js"></script>
  <link rel="stylesheet" href="/media/css/combo.select.css">
  <style type="text/css">
    .combo-input {
        margin-bottom: 0;
        height: 34px;
    }
    .combo-dropdown{
        color: #333;
    }
  </style>
</head>

<body class="sticky-header">
    <section>
        <!-- left side start-->
        <div class="left-side sticky-left-side">
            <!--logo and iconic logo start-->
            <div class="logo">
                <a href="index.html"><img src="/media/images/logo.png" alt=""></a>
            </div>
            <div class="logo-icon text-center">
                <a href="index.html"><img src="/media/images/smalllogo.png" alt=""></a>
            </div>
            <!--logo and iconic logo end-->
            <div class="left-side-inner">
                <!-- visible to small devices only -->
                <div class="visible-xs hidden-sm hidden-md hidden-lg">
                    <div class="media logged-user">
          
                        <div class="media-body">
                             <h4><a href="#"><?php echo isset(yii::$app->session['tomodel']->remark)?yii::$app->session['tomodel']->remark:''; ?></a></h4>
                            <span></span>
                        </div>
                    </div>
                    <!-- <h5 class="left-nav-title">Account Information</h5> -->
                    <ul class="nav nav-pills nav-stacked custom-nav">
                      <!-- <li><a href="#"><i class="fa fa-user"></i> <span>Profile</span></a></li> -->
                      <?php if(yii::$app->session['role']==-1): ?>
                      <li><a href="#" class="pwdbutton"><i class="fa fa-cog"></i> <span> 修改密码 </span></a></li>
                      <?php endif; ?>
                      <li><a href="/login/logout.html"><i class="fa fa-sign-out"></i> <span> 注销 </span></a></li>
                    </ul>
                </div>
                <!--sidebar nav start-->
                <ul class="nav nav-pills nav-stacked custom-nav">
               <?php if(yii::$app->session['role']=='-1'):?> 
               <li>
	                    <a href="/index/index.html">
	                    	<i class="fa fa-home"></i> 
	                    	<span>首页</span>
	                    </a>
                    </li>
                    <li class="menu-list"><a><i class="fa fa-comments"></i> <span>微信管理</span></a>
                        <ul class="sub-menu-list">
                            <li><a href="/autoreply/index.html"> 关注自动回复</a></li> 
                            <li><a href="/wxkeyword/index.html"> 关键字自动回复</a></li>
                            <li><a href="/wxmenu/tomenu.html"> 微信菜单</a></li>
                            <!-- <li><a href="/tocket/index.html">唯一二维码</a></li> -->
                        </ul>
                    </li>
                    <li>
                    
                     <li class="menu-list">
	                    <a><i class="fa fa-male"></i> 
		                    <span>平台管理</span></a>
	                        <ul class="sub-menu-list">
	                            <li><a href="/consult/index.html">新闻管理</a></li>
	                            <li><a href="/game/index.html">游戏记录</a></li>
	                            <li><a href="/sgame/index.html">小游戏记录</a></li>
	                            <li><a href="/service/index.html">开服记录</a></li>
	                            <li><a href="/configuration/index.html">配置管理</a></li>
	                            <li><a href="/gift/index.html">礼包列表</a></li>
                                <li><a href="/giftreceive/index.html">礼包领取记录</a></li>
                                <li><a href="/platform/index.html">平台记录</a></li>
                                <li><a href="/carousel/index.html">轮播管理</a></li>
                                <li><a href="/company/index.html">公司管理</a></li>
                                <li><a href="/wxshare/index.html">微信分享管理</a></li>
                                <li><a href="/email/index.html">邮件管理</a></li>
                                <li><a href="/game/remarkindex.html">游戏备注</a></li>
                            </ul>
                    </li>
                    
                     <li class="menu-list">
	                    <a><i class="fa fa-bar-chart-o"></i> 
		                    <span>数据管理</span></a>
	                        <ul class="sub-menu-list">
	                        	  <li><a href="/order/sindex.html">用户支付记录</a></li>
	                        	  <li><a href="/order/index.html">订单记录</a></li>
                                  <li><a href="/count/tocount.html">汇总统计</a></li>
                                  <li><a href="/count/index.html">详细统计</a></li>
                                  <li><a href="/count/monthcount.html">月汇总统计</a></li>
                                  <li><a href="/retain/index.html">留存统计</a></li>
                                  <li><a href="/retain/gindex.html">详情留存统计</a></li>
                                  <li><a href="/continueorder/index.html">持续付费统计</a></li>
                                  <li><a href="/continueorder/detaindex.html">详情持续付费统计</a></li>
                                  <li><a href="/recycle/index.html">收回统计</a></li>
                                  <li><a href="/recycle/gindex.html">详情收回统计</a></li>
                                  <li><a href="/game/tocount.html">游戏统计</a></li>
                                  <li><a href="/instructions/index.html">表格说明</a></li>
                                  <li><a href="/user/ranking.html">充值排行榜</a></li>
                                  <li><a href="/platform/tocount.html">平台订单统计</a></li>
                                  <li><a href="/order/tocount.html">订单统计</a></li>
                                  <li><a href="/order/refund.html">订单退款记录</a></li>
                                  <li><a href="/user/index.html">用户信息</a></li>
                                  <li><a href="/bean/tocount.html">用户统计</a></li>
                                  <li><a href="/count/countfoleline.html">数据统计折线图</a></li>
                                  <li><a href="/game/referrallinks.html">游戏推广链接</a></li>
                                  <li><a href="/game/referralsuggest.html">游戏推广建议</a></li>
                                  <li><a href="/instructions/noun.html">后台名词说明</a></li>
                            </ul>
                    </li>
                    
                     <li class="menu-list">
	                    <a><i class="fa fa-th-list"></i> 
		                    <span>审核管理</span></a>
	                        <ul class="sub-menu-list">
	                            <li><a href="/user/applyindex.html">审核申请</a></li>
	                            <li><a href="/currency/index.html">游币审核</a></li>
	                            <li><a href="/rebate/index.html">返利审核</a></li>
                            </ul>
                    </li>
                    
                    
                    <li class="menu-list">
	                    <a><i class="fa fa-bar-chart-o"></i> 
		                    <span>游客数据管理</span></a>
	                        <ul class="sub-menu-list">
                                  <li><a href="/touristcount/tocount.html">游客汇总统计</a></li>
                                  <li><a href="/touristcount/index.html">游客详细统计</a></li>
                                  <li><a href="/touristcount/monthcount.html">游客月汇总统计</a></li>
                                  <li><a href="/appcount/index.html">app数据统计</a></li>
                            </ul>
                    </li>
                    
                     <li class="menu-list">
	                    <a><i class="fa fa-shopping-cart"></i> 
		                    <span>商品管理</span></a>
	                        <ul class="sub-menu-list">
	                            <li><a href="/product/index.html">商品记录</a></li>
	                            <li><a href="/rule/index.html">规则管理</a></li>
                                <li><a href="/integral/index.html">积分记录</a></li>
                                <li><a href="/exchange/index.html">兑换记录</a></li>
                                <li><a href="/exchange/tocount.html">兑换统计</a></li>
                                <li><a href="/exchange/voucher.html">代金券记录</a></li>
                                <li><a href="/integral/ranking.html">积分排行榜</a></li>
                            </ul>
                    </li>
                    
                     <li class="menu-list">
	                    <a><i class="fa fa-bullseye"></i> 
		                    <span>活动管理</span></a>
	                        <ul class="sub-menu-list">
	                          <li><a href="/winning/robredpacket.html">整点抢红包</a></li> 
                              <li><a href="/winning/index.html"> 大转盘</a></li>
                              <li><a href="/winning/rebatecurrency.html">游币返利</a></li>
                              <li><a href="/winning/totle.html"> 大转盘数据统计</a></li>
	                            <li><a href="/winning/dogfood.html"> 狗粮活动</a></li>
                            </ul>
                    </li>
               
                    <li class="menu-list">
                        <a><i class="fa fa-toggle-left"></i> <span>埋点数据管理</span></a>
                        <ul class="sub-menu-list">
                            <li><a href="/clicks/index.html">埋点数据</a></li>
                            <li><a href="/clicks/source.html">来源统计</a></li>
                            <li><a href="/clicks/user.html">用户统计</a></li>
                            <li><a href="/clicks/plat.html">渠道统计</a></li>
                            <li><a href="/clicks/ip.html">IP统计</a></li>
                        </ul>
                     </li>
               
                  <li class="menu-list">
	                    <a><i class="fa fa-bullseye"></i> 
		                    <span>权限管理</span></a>
	                        <ul class="sub-menu-list">
	                            <li><a href="/admin/index.html">后台账号管理</a></li>
	                            <li><a href="/role/index.html"> 角色管理</a></li>
	                            <li><a href="/permission/index.html"> 权限管理</a></li>
	                            <li><a href="/menu/index.html"> 菜单管理</a></li>
                            </ul>
                    </li>
               
                    <li class="menu-list">
                        <a><i class="fa fa-asterisk"></i> <span>分成比例设置</span></a>
                        <ul class="sub-menu-list">
                            <li><a href="/setting/default.html">分成默认设置</a></li>
                            <li><a href="/setting/proportion.html">重置分成比例</a></li>
                        </ul>
                    </li> 
               
              <!--    <li>
	                    <a href="/index/index.html">
	                    	<i class="fa fa-home"></i> 
	                    	<span>首页</span>
	                    </a>
                    </li>
                    <li class="menu-list">
	                    <a><i class="fa fa-bullseye"></i> 
		                    <span>权限管理</span></a>
	                        <ul class="sub-menu-list">
	                            <li><a href="/role/index.html"> 角色管理</a></li>
	                            <li><a href="/permission/index.html"> 权限管理</a></li>
	                            <li><a href="/menu/index.html"> 菜单管理</a></li>
                            </ul>
                    </li>
                    <li class="menu-list"><a><i class="fa fa-comments"></i> <span>微信管理</span></a>
                        <ul class="sub-menu-list">
                            <li><a href="/autoreply/index.html"> 关注自动回复</a></li> 
                            <li><a href="/wxkeyword/index.html"> 关键字自动回复</a></li>
                            <li><a href="/wxmenu/tomenu.html"> 微信菜单</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="/company/index.html">
                            <i class="fa fa-book"></i> 
                            <span>公司管理</span>
                        </a>
                    </li>
                    <li class="menu-list">
                        <a><i class="fa fa-user"></i> 
                            <span>用户管理</span></a>
                            <ul class="sub-menu-list">
                                <li><a href="/user/index.html">用户记录</a></li>
                                <li><a href="/user/ranking.html">用户排行板榜</a></li>
                            </ul>
                    </li>
                    <li class="menu-list">
	                    <a><i class="fa fa-male"></i> 
		                    <span>平台管理</span></a>
	                        <ul class="sub-menu-list">
	                            <li><a href="/platform/index.html">平台记录</a></li>
	                            <li><a href="/platform/tocount.html">订单统计</a></li>
	                            <li><a href="/bean/tocount.html">粉丝统计</a></li>
                            </ul>
                    </li>
                     <li class="menu-list">
	                    <a><i class="fa fa-road"></i> 
		                    <span>游戏管理</span></a>
	                       <ul class="sub-menu-list">
	                            <li><a href="/game/index.html">游戏记录</a></li>
	                            <li><a href="/game/tocount.html">统计</a></li>
                            </ul>
                    </li>
                    <li class="menu-list">
                        <a><i class="fa fa-shopping-cart"></i> <span>订单管理</span></a>
                        <ul class="sub-menu-list">
                            <li><a href="/order/index.html">订单记录</a></li>
                            <li><a href="/order/tocount.html">统计</a></li>
                            <li><a href="/order/refund.html">退款记录</a></li>
                        </ul>
                    </li>
                    <li class="menu-list">
                        <a><i class="fa fa-calendar-o"></i> 
                            <span>礼包管理</span></a>
                            <ul class="sub-menu-list">
                                <li><a href="/gift/index.html">礼包列表</a></li>
                                <li><a href="/giftreceive/index.html">礼包领取记录</a></li>
                            </ul>
                    </li>
                    <li>
                        <a href="/carousel/index.html">
                            <i class="fa fa-toggle-right"></i> 
                            <span>轮播管理</span>
                        </a>
                    </li>
                    <li>
                        <a href="/product/index.html">
                            <i class="fa fa-money"></i> 
                            <span>商品管理</span>
                        </a>
                    </li>
                    <li>
                        <a href="/consult/index.html">
                            <i class="fa fa-barcode"></i> 
                            <span>咨讯管理</span>
                        </a>
                    </li>
                    <li>
                        <a href="/integral/index.html">
                            <i class="fa fa-crosshairs"></i> 
                            <span>积分记录</span>
                        </a>
                    </li>
                    <li class="menu-list">
                        <a><i class="fa fa-random"></i> <span>积分兑换管理</span></a>
                        <ul class="sub-menu-list">
                            <li><a href="/exchange/index.html">兑换记录</a></li>
                            <li><a href="/exchange/tocount.html">统计</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="/rule/index.html">
                            <i class="fa fa-money"></i> 
                            <span>规则管理</span>
                        </a>
                    </li>
                    <li class="menu-list">
                        <a><i class="fa fa-bar-chart-o"></i> <span>数据统计</span></a>
                        <ul class="sub-menu-list">
                            <li><a href="/count/tocount.html">汇总统计</a></li>
                            <li><a href="/count/index.html">详细统计</a></li>
                        </ul>
                    </li>
                    <li class="menu-list">
                        <a><i class="fa fa-asterisk"></i> <span>分成比例设置</span></a>
                        <ul class="sub-menu-list">
                            <li><a href="/setting/default.html">分成默认设置</a></li>
                            <li><a href="/setting/proportion.html">重置分成比例</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="/configuration/index.html">
                            <i class="fa fa-bug"></i> 
                            <span>配置管理</span>
                        </a>
                    </li>
                    <li class="menu-list">
                        <a><i class="fa fa-toggle-left"></i> <span>埋点数据管理</span></a>
                        <ul class="sub-menu-list">
                            <li><a href="/clicks/index.html">埋点数据</a></li>
                            <li><a href="/clicks/source.html">来源统计</a></li>
                            <li><a href="/clicks/user.html">用户统计</a></li>
                            <li><a href="/clicks/plat.html">渠道统计</a></li>
                            <li><a href="/clicks/ip.html">IP统计</a></li>
                        </ul>
                    </li>
                     <li class="menu-list">
                        <a><i class="fa fa-question-circle"></i> <span>使用说明</span></a>
                        <ul class="sub-menu-list">
                            <li><a href="/instructions/index.html">表格说明</a></li>
                            <li><a href="/instructions/noun.html">公司名词说明</a></li>
                        </ul>
                    </li>
                     -->   
                <?php else :?>
                 <?php foreach (Yii::$app->session['menu'] as $menu):?>
	                    <li class="<?php echo isset($menu['cmenu'])?'menu-list':'';?>">
		                    <a href="/<?php if(!empty($menu['route'])) {echo $menu['route'];}?>.html"><i class="<?php echo $menu['icon'];?>"></i> 
			                    <span><?php echo $menu['name']?></span></a>
		                        <?php if(isset($menu['cmenu'])):?>
					                <ul class="sub-menu-list">
					                 <?php foreach ($menu['cmenu'] as $cmenu):?>
					                    <li>
					                        <a href="/<?php if(!empty($cmenu['route'])){echo $cmenu['route'];}?>.html<?php if($cmenu['param']!=''){echo '?state='.$cmenu['param'];}?>"> <?php echo $cmenu['name']; ?></a>
					                    </li>
					                 <?php endforeach;?>
					                </ul>
					             <?php endif;?>
	                    </li>
                     <?php endforeach;?>
	           <?php endif;?>    	  
                    </ul>
                <!--sidebar nav end-->
            </div>
        </div>
        <!-- left side end-->
        <!-- main content start-->
        <div class="main-content" >
            <!-- header section start-->
            <div class="header-section">
                <!--toggle button start-->
                <a class="toggle-btn"><i class="fa fa-bars"></i></a>
                <!--toggle button end-->
                <!--notification menu start -->
                <div class="menu-right">
                    <ul class="notification-menu">
                        <li>
                            <a href="" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                  <?php echo isset(yii::$app->session['tomodel']->remark)?yii::$app->session['tomodel']->remark:'';?>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
<!--                            <li><a href="#"><i class="fa fa-user"></i>  个人信息</a></li> -->
                                <?php if(yii::$app->session['role']==-1): ?>
                                <li><a href="#" class="pwdbutton"><i class="fa fa-cog"></i> 修改密码</a></li>
                                <?php endif; ?>
                                <li><a href="/login/logout.html"><i class="fa fa-sign-out"></i> 注 销</a></li>
                            </ul>
                        </li>

                    </ul>
                </div>
                <!--notification menu end -->
            </div>
            <!-- header section end-->
            <!--body wrapper start-->
            <?=$content ?>
            <!--body wrapper end-->
            <!--footer section start-->
        </div>
        <footer>
            2016 &copy; 16游
        </footer>
        <!--footer section end-->
        <!-- main content end-->
    </section>
    <div class="modal fade in" id="my-Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="display:none; background: rgba(0, 0, 0, 0.5) none repeat scroll 0% 0%;">
<div class="modal-dialog">
   <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">修改密码</h4>
          </div>
          <div class="modal-body row">
           <form class="form-horizontal adminex-form" id="pwdform">
               <div class="form-group"  style="border-bottom:none;">
                    <label class="col-sm-3 control-label">原密码</label>
                    <div class="col-sm-5"> 
                        <input id="oldpwd" type="password" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" maxlength="16" name="pwd" placeholder="输入6~16位密码">
                    </div>
                </div> 
                <div class="form-group"  style="border-bottom:none;">
                    <label class="col-sm-3 control-label">新密码</label>
                    <div class="col-sm-5" >
                        <input id="newpwd" type="password" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" maxlength="16" name="newpwd" placeholder="输入6~16位密码">
                    </div>
                </div>
                <div class="form-group" style="border-bottom:none;">
                    <label class="col-sm-3 control-label">确认密码</label>
                    <div class="col-sm-5" >
                        <input id="checkpwd" type="password" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" maxlength="16" name="respwd" placeholder="输入的密码必须跟新密码一致">
                    </div>
                </div>
                <div class="form-group" style="border-bottom:none;">
                    <div class="col-md-12 col-sm-12 col-md-offset-3 col-sm-offset-3"><button type="button" id="subpwd" class=" btn btn-success">确定</button></div>
                </div>
          </form>
         </div>
       </div>
    </div>
    <!-- Placed js at the end of the document so the pages load faster -->
    <script src="/media/js/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="/media/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="/media/js/bootstrap.min.js"></script>
    <script src="/media/js/jquery.nicescroll.js"></script>
    <script src="/media/js/iCheck/jquery.icheck.js"></script>
    <script src="/media/js/icheck-init.js"></script>
    <!--common scripts for all pages-->
    <script src="/media/js/scripts.js"></script>
    <script src="/media/js/jquery.combo.select.js"></script>
    <script>
    var localfirsturl = "<?php echo  yii::$app->session['localfirsturl'];?>"; //一级链接
    var localsecondurl = "<?php echo  yii::$app->session['localsecondurl'];?>"; //一级链接
    $('.pwdbutton').click(function(){//显示修改密码
      $('#my-Modal').css('display','block');
    })
    $('.close').click(function(){//关闭修改密码
      $('#pwdform')[0].reset();//清空表单内容
      $('#my-Modal').css('display','none');
    })
    $('#subpwd').click(function(){//执行修改密码
      if(($("#oldpwd").val().length>5)&&($("#newpwd").val().length>5)&&($("#checkpwd").val().length>5)){
        $.ajax({
          url:'/company/topwd.html',
          type:'post',
          data:$('#pwdform').serialize(),
          dataType:'json',
          success:function(data){
            if(data.errorcode == 0){
              layer.msg(data.info, {icon: 6,time:2000});
              $('#pwdform')[0].reset();//清空表单内容
              $('#my-Modal').css('display','none');
            }else{
              layer.msg(data.info,{icon:4,time:2000});
            }
          }
        });
      }else{
        layer.msg('密码为6~16位有效字符',{icon:4,time:2000});
      }
      return false;
    })
    //保证点击一级菜单之后跳转到的页面能保持让菜单在打开状态
     $('.nav').children('li').children('a').each(function(){
    	 var t_h = this.href; 
    	 if(t_h !=='' && (location.href.toLowerCase().indexOf(this.href.toLowerCase())!=-1 || localfirsturl.toLowerCase().indexOf(this.href.toLowerCase())!=-1)){
    		 $(this).parent().addClass('active nav-hover');
       	}
     });
    //保证点击二级菜单之后跳转到的页面能保持让菜单在打开状态
    $('.sub-menu-list li a').each(function(){
   	    var local_url = this.href.toLowerCase().split('?')[0]; 
        if(location.href.toLowerCase().indexOf(this.href.toLowerCase())!=-1 || localsecondurl.toLowerCase().indexOf(local_url)!=-1){
            $(this).parent().addClass('active').parent().parent().addClass('nav-active');
        }
    });
    </script>
</body>
</html>
