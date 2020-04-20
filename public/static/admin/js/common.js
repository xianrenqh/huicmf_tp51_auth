
//图片预览
function hui_img_preview(id, src) {
    if (src == '') return;
    layer.tips('<img src="' + htmlspecialchars(src) + '" height="100">', '#' + id, {
        tips: [1, '#fff']
    });
}

//删除多文件上传
function remove_li(obj) {
    $(obj).parent().remove();
}

function posttips(url, data,p=1) {
    $.post(url, data, function (res) {
        if (res.status == 1) {
            layer.msg(res.msg, {
                icon: 1,
                time: 200
            }, function () {
                if(p==1){
                    parent.location.reload();
                }else{
                    location.reload();
                }
            });
        } else {
            layer.msg(res.msg);
        }
    });
}
//上传附件
function hui_upload_att(url) {
    layer.open({
        type: 2,
        title: '上传附件',
        area: ['500px', '430px'],
        content: url
    });
}

//删除多条记录
function hui_dels(name) {
    if ($("input[name='" + name + "[]']:checked").length < 1) {
        layer.alert('请勾选信息！');
        return false;
    }
    layer.confirm('确认要删除吗？', function (index) {
        document.getElementById('myform').submit();
    });
}

//图像裁剪
function hui_img_cropper(cid, url) {
    var str = $('#' + cid).val();
    if (str == '') {
        layer.msg('请先上传或选择图片！');
        return false;
    }
    if (url.indexOf('?') != -1) {
        url = url + '&f=' + window.btoa(unescape(encodeURIComponent(str))) + '&cid=' + cid;
    } else {
        url = url + '?f=' + window.btoa(unescape(encodeURIComponent(str))) + '&cid=' + cid;
    }
    layer.open({
        type: 2,
        title: '图像裁剪',
        area: ['750px', '510px'],
        content: url
    });
}


//选择图片并返回url到父级输入框
function selectImg(parentInputId,url) {
    var index = parent.layer.getFrameIndex(window.name);
    parent.$(parentInputId).val(url);
    parent.$(parentInputId+"_src").attr("src",url);
    parent.layer.close(index);
}

//关闭弹出层
function hui_close(){
    var index = parent.layer.getFrameIndex(window.name);
    parent.layer.close(index);
}

/*textarea 字数限制*/
function textarealength(obj,maxlength){
    var v = $(obj).val();
    var l = v.length;
    if( l > maxlength){
        v = v.substring(0,maxlength);
        $(obj).val(v);
    }
    $(obj).parent().find(".textarea-length").text(v.length);
}

/*
    //多图片上传
    upload.render({
        elem: '#upload_imagess'
        ,url: 'https://httpbin.org/post' //改成您自己的上传接口
        ,multiple: true
        ,number:number
        ,before: function(obj){
            //预读本地文件示例，不支持ie8
            obj.preview(function(index, file, result){
                $('#demo2').append('<img src="'+ result +'" alt="'+ file.name +'" class="layui-upload-img">')
            });
        }
        ,done: function(res){
            //上传完毕
        }
    });*/
