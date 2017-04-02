var $j=jQuery.noConflict();
$(function(){
    //先获取课程列表(问题所属的课程)
    getCourseList();
    uploadImg('#ques_img', '#img1', '#ques_textarea');
    uploadImg('#answer_img', '#img2', '#answer_textarea');

    //发表经典题目按钮事件
    postArticle();
});

/**
 * 获得课程信息列表
 * @return {[type]} [description]
 */
function getCourseList(){
    $j.post(
        '../php/course.class.php',
        {
            'operation': 'get_course_list',
            'num': '0'
        },
        function(data, textStatus, xhr) {
            setData(data);
    });
}

/**
 * 设置课程列表给下拉列表
 */
function setData(data){
    try{
        res = $j.parseJSON(data);
        if (res.status == 1) {
            quesCategory = $j("#ques_category");
            quesCategory.html("");
            $j.each(res.data, function(index, el) {
                quesCategory.append('<option value="' + el.cou_id + '">' + el.cou_name + '</option>');
            });
            return;
        }else{
            alert("查询数据出错");
        }
    }catch(err){
        alert(err);
        alert("查询数据出错");
    }
}

/**
 * 发表经典题目
 * @return {[type]} [description]
 */
function postArticle(){
    $j('#post_article').click(function(event) {
        quesTitle = $j('#ques_title').val();
        quesContent = $j('#ques_textarea').val();
        quesCategory = $j('#ques_category').val();
        answerContent = $j('#answer_textarea').val();
        if(quesTitle == ""){
            alert("问题标题不能为空");
        }else if(quesContent == ""){
            alert("问题内容不能为空");
        }else if(answerContent == ""){
            alert("答案内容不能为空");
        }else{
            var values = {};
            values['top_title'] = quesTitle;
            values['top_content'] = quesContent;
            values['cou_id'] = quesCategory;
            values['top_answer'] = answerContent;
            topic = JSON.stringify(values);
            $j.post(
                '../php/topic.class.php',
                {
                    'operation': 'add_topic',
                    'topic': topic
                },
                function(data, textStatus, xhr) {
                    try{
				        res = $j.parseJSON(data);
				        if (res.status == 1) {
				            alert("发布经典题目成功");
				            return;
				        }else{
				            alert("发布经典题目出错");
				        }
				    }catch(err){
				        alert(err);
				        alert("发布经典题目出错");
				    }
			});
        }
    });
}


function uploadImg(imgId, dialogId, textareaId){
    var f2 = document.querySelector(imgId);
    f2.onchange = function (e) {

    var files = e.target.files;
    var len = files.length;
    for (var i=0; i < len; i++) {
        lrz(files[i],{width:640,fieldName:"file1"}).then(function (rst) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '../php/util/HandleImg.class.php');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var obj = $j.parseJSON(xhr.responseText);
                        $(dialogId).append('<li onclick="var delimg=$(this);$.confirm(\'您确定要删除吗?\', \'确认删除?\', function() {delimg.remove();},function(){$.toast(\'取消操作\', \'cancel\');});" class="weui_uploader_file weui_uploader_status" style="background-image:url(../' + obj.path + ')"><div class="weui_uploader_status_content"><i class="weui_icon_cancel"></i></div></li>'); 
                        $j(textareaId).val($j(textareaId).val() + '\r\n<img src="../' + obj.path + '"/>');
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
                };

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
    }//for end
    }
}