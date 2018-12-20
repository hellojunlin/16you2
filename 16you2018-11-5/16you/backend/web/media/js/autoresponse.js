var i =1;
/*判断选的回复类型*/
$("input[name='type']").change(function(){
    var rideo_val = $(this).val();
    switch(rideo_val){
        case '1':
            $("#news").hide();
            $("#images").hide();
            $("#text").show();
            $("#video").hide();
            $(".btn-success").hide().next().hide();
            break;
        case '2':
            $("#news").show();
            $("#images").hide();
            $("#text").hide();
            $("#video").hide();
            $(".btn-success").show();
            break;
        case '3':
            $("#news").hide();
            $("#images").show();
            $("#text").hide();
            $("#video").hide();
            $(".btn-success").hide().next().hide();
            break;
        case '4':
            $("#news").hide();
            $("#images").hide();
            $("#text").hide();
            $("#video").show();
            $(".btn-success").hide().next().hide();
            break;
    }
})
/*添加图文*/
$(".btn-success").click(function(){
    if(i<9){
        $('#news').append('<div class="news_html"><div class="form-group"><label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> 标题</label><div class="col-sm-5"><input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="标题" data-original-title="标题：不要超过16个字符" name="title[]" maxlength="16" /><span></span></div></div><div class="form-group" style="display:none"><label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> 描述</label><div class="col-sm-5"><input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="描述" data-original-title="仅限文字" name="description[]" maxlength="20"/><span></span></div></div><div class="form-group"><label class="control-label col-sm-2">图片上传</label><div class="col-sm-5"><div class="fileupload fileupload-new" data-provides="fileupload"><div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="/media/images/img-text.png" alt=""></div><div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div><div><span class="btn btn-default btn-file"><span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片 </span><span class="fileupload-exists"><i class="fa fa-undo"></i> 重 选</span><input type="file" class="default" name="file[]" /></span><a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> 移 除</a></div></div></div></div><div class="form-group"><label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> URL</label><div class="col-sm-5"><input type="url" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="要加 http:// 或 fttp" data-original-title="http://www.baidu.com" name="url[]" maxlength="100"/><span></span></div></div></div>');
        i++;
        if(i>=9){
            $('.btn-success').hide();
        }
    }
    $('.dan-btn').show();
    return false;
})
/*删除图文*/
$(".dan-btn").click(function(){
    if(i>1){
        $('.news_html:last').remove();
        i--;
        if(i<=1){
            $('.dan-btn').hide();
        }
    }
    $('.btn-success').show();
    return false;
})

//上传视频
$("input[name='vfile']").change(function(){
    layer.load(0, {time:3000}); //0代表加载的风格，支持0-2
    var obj = $(this);
    if(obj['0']['files']['length']){
      //实例化FormDat对象时传入form表单对象
        var formobj = new FormData(document.getElementById('myform'));
        //XMLHttpRequest对象
        var xmlobj = new XMLHttpRequest();
        //指定提交类型和选择要发送的地址
        xmlobj.open('post','/autoreply/tovideo.html');
        //发送数据
        xmlobj.send(formobj);
        xmlobj.onload = function(){
            var data = xmlobj.responseText;//获取后台返回的数据
            var dataarr = data.split('_');
            if(dataarr['0']){
                if(obj.next().length==1){
                  obj.next().remove();
                  obj.next().remove();
                  obj.next().remove();
                }
                obj.after('<br/><input type="hidden" name="vefile" value="'+dataarr['1']+'"/><video width="320" height="240" controls><source src="'+dataarr['1']+'" type="video/mp4"><source src="#" type="video/ogg">您的浏览器不支持 video 标签。</video>');
            }else{
                layer.msg('上传失败',{icon:5,time:2000});
            }    
        }
    }
  })