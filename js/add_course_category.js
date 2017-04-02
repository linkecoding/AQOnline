var $j=jQuery.noConflict();      
$(function(){
var f = document.querySelector('#course_icon');
f.onchange = function () {
    lrz(this.files[0],{width:640,fieldName:"file1"}).then(function (rst) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../php/util/HandleImg.class.php');
        xhr.onload = function () {
            if (xhr.status === 200) {
                var obj = $j.parseJSON(xhr.responseText);
                $('#img').html('<li onclick="var delimg=$(this);$.confirm(\'您确定要删除吗?\', \'确认删除?\', function() {delimg.remove();},function(){$.toast(\'取消操作\', \'cancel\');});" class="weui_uploader_file weui_uploader_status" style="background-image:url(../' + obj.path + ')"><div class="weui_uploader_status_content"><i class="weui_icon_cancel"></i></div></li>');
                $j("#course_icon_h").val(obj.path);
            } else {
                // 处理其他情况
            }
        };

        xhr.onerror = function () {
            // 处理错误
        };

        xhr.upload.onprogress = function (e) {
            // 上传进度
            var percentComplete = ((e.loaded / e.total) || 0) * 100;
        }

        // 添加参数
        rst.formData.append('size', rst.fileLen);
        rst.formData.append('base64', rst.base64);
        // 触发上传
        xhr.send(rst.formData);
        return rst;
        })

        .catch(function (err) {
            alert(err);
        });
}

//添加课程类别按钮的事件
addCourseCategory();
});

/**
 * 添加课程分类
 */
function addCourseCategory(){

    $j("#add_course_category").click(function(event) {
        cou_img_url = $j("#course_icon_h").val();
        cou_name = $j("#cou_name").val();
        if(cou_img_url == ""){
            alert("请上传课程图标");
        }else if(cou_name == ""){
            alert("请输入课程类别名");
        }else{
            var values = {};
            values['cou_name'] = cou_name;
            values['cou_img_url'] = cou_img_url;
            course_category = JSON.stringify(values);
            $j.post(
                '../php/course.class.php',
                {
                    'operation': 'add_course_category',
                    'course_category': course_category
                },
                function(data, textStatus, xhr){
                    try{
                        res = $j.parseJSON(data);
                        if (res.status == 1) {
                            alert("添加课程分类成功");
                        }
                    }catch(err){
                        alert("添加课程分类失败,请尝试重新添加");
                    }
            });
        }
    });
}