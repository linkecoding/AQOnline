var $j=jQuery.noConflict();
$(function(){
    //上传文件处理
    var f2 = document.querySelector('#answer_img');
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
                        $('#img2').append('<li onclick="var delimg=$(this);$.confirm(\'您确定要删除吗?\', \'确认删除?\', function() {delimg.remove();},function(){$.toast(\'取消操作\', \'cancel\');});" class="weui_uploader_file weui_uploader_status" style="background-image:url(../' + obj.path + ')"><div class="weui_uploader_status_content"><i class="weui_icon_cancel"></i></div></li>'); 
                        $j('#answer_textarea').val($j('#answer_textarea').val() + '\r\n<img src="../' + obj.path + '"/>');
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

    //回答按钮事件
    $j('#answer_ques').click(function(event) {
    	answerQues();
    });
});


/**
 * 回答问题
 * @return {[type]} [description]
 */
function answerQues(){
	que_id = window.location.search.substring(4);
	ans_content = $j('#answer_textarea').val();

	if (ans_content == "") {
		alert("答案不能为空");
		return;
	}

	var values = {};
	values['que_id'] = que_id;
	values['ans_content'] = ans_content;
	answer = JSON.stringify(values);
	$j.post(
		'../php/answer.class.php',
		{
			'operation': 'add_answer',
			'answer': answer
		},
		function(data, textStatus, xhr){
			try{
		        res = $j.parseJSON(data);
		        if (res.status == 1) {
		            alert("回答问题成功");
		            return;
		        }else{
		            alert("回答问题出错");
		        }
		    }catch(err){
		        alert(err);
		        alert("回答问题出错");
		    }
	});
}