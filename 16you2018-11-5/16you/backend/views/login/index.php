<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="http://www.sucaihuo.com/templates" type="image/png">
    <title>游戏系统 -- 登 录</title>
    <link href="/media/css/style.css" rel="stylesheet">
    <link href="/media/css/style-responsive.css" rel="stylesheet">
</head>

<body class="login-body">

<div class="container">

    <form class="form-signin">
        <div class="form-signin-heading text-center">
            <h1 class="sign-title"> 登 录 </h1>
            <img src="/media/images/logo.jpg" alt=""/>
        </div>
        <div class="login-wrap">
            <input type="text" class="form-control" placeholder="管理员账号" autofocus name="phone" maxlength="11">
            <input type="password" class="form-control" placeholder="密码"  name="password" maxlength="16">
            <div id="did" style="color:#900"></div>
            <button class="btn btn-lg btn-login btn-block" id="submit">
                <i class="fa fa-check"></i>
            </button>
    </form>

</div>



<!-- Placed js at the end of the document so the pages load faster -->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="/media/js/jquery-1.10.2.min.js"></script>
<script src="/media/js/bootstrap.min.js"></script>
<script src="/media/js/layer/layer.js"></script>
<script>
    $('#submit').click(function(){
        var phone = $('input[name="phone"]').val();
        var password = $('input[name="password"]').val();
        if(phone.length==0) {
            $('#did').html('请输入管理员账号');
        }else if(password.length == 0) {
            $('#did').html('请输入输入密码');
        }else{
            $.ajax({
                url:'/login/index.html',
                type:'post', 
                data:{phone:phone,password:password},
                success:function(data){  
                    if(data==0){
                        layer.msg('正在登录...', {icon: 6,time:1000});
                        window.location.href="/index/index.html";
                    }else{
                        $('#did').html(data);
                    }         
                }
            })
        } 
        return false;// 
    })        
</script>
</body>
</html>
