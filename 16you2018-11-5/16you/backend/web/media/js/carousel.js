//图片截取
var clipArea = new bjj.PhotoClip("#clipArea", {
  size: [428.57, 134.28],
  outputSize: [750,235],
  file: "#file",
  view: "#view",
  ok: "#clipBtn",
  clipFinish: function(dataURL) {//截取完返回
    if($("#view img").length!=0){
      $("#view img").remove();
    }
    layer.load(0, {time:500}); //0代表加载的风格，支持0-2
    $.ajax({
      //异步提交数据
      type:'post',
      dataType:'json',
      data:{'imgbase64':dataURL.substr(23)},
      url:'/carousel/subimg.html',
      success:function(data){
        layer.msg(data.info);
        if(data.errorcode==0){
          $("input[name='image']").val(data.imgurl);
        }
      }
    })
  }
});

//删除
function del(id){
    layer.confirm('确认要删除吗？',function(){
        $.ajax({
            url:"/carousel/delete.html",
            type:'post',
            data:{'id':id},
            success:function(data){
              if(data==1){
                  window.location.reload();
              }else{
                alert('删除失败');
              }
            }
        }) 
    })
}

/*---提交表单---*/
$("#submit").click(function(){
  layer.load(0, {time:1000}); //0代表加载的风格，支持0-2
  $.ajax({
      url:'/carousel/create.html',
      type:'post',
      dataType:'json',
      data:$("form").serialize(),
      success:function(data){   
          if(data.errorcode==0){
            window.location.href ='/carousel/index.html';
          }else{
            layer.msg(data.info,{icon:2,time:2000});
          }           
      }
  });
  return false;
})