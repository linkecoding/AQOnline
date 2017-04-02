$(function() {
	//判断是学生还是老师
	id = window.location.search.substring(4);
	if (id != null) {
		getData(id);
	}else{
		window.location.href = '../error.html';
	}
});

/**
 * 获取学生/老师数据
 * @return {[type]} [description]
 */
function getData(type) {
	$.post(
		'../php/account.class.php',
		{
			'operation': 'get_userinfo',
			'type': type
		},
		function(data, textStatus, xhr){
			try{
	            res = $.parseJSON(data);
	            if (res.status == 1) {
	            	if (type == 'student') {
	            		setStudentData(res.data);
	            	}else if (type == 'teacher') {
	            		setTeacherData(res.data);
	            	}else{
	            		window.location.href = '../error.html';
	            	}
	            }
			}catch(err){
				alert(err);
				window.location.href = '../error.html';
			}
	});
}

/**
 * 设置学生个人中心的数据
 * @param {[type]} data [description]
 */
function setStudentData(data){
	if(data != null){
		data = $.parseJSON(data);
		$('#avatar_img').attr('src', data.stu_avatar_url);
		$('#nick_name').text(data.stu_nick_name);
		$('#code').text(data.stu_code);
		$('#wechat_code').text(data.stu_wechat_code.substring(0, 10));
		$('#asked_ques').attr('href', './asked_question.html?id=' + data.stu_id);
		$('#commented_ques').attr('href', './commented_question.html?id=' + data.stu_id);
		$("#setting").attr('href', '../setting.html?id=' + data.stu_id);
	}else{
		window.location.href = '../error.html';
	}
}


/**
 * 设置教师个人中心的数据
 * @param {[type]} data [description]
 */
function setTeacherData(data){
	if (data != null) {
		data = $.parseJSON(data);
		$('#avatar_img').attr('src', data.tea_avatar_url);
		$('#nick_name').text(data.tea_nick_name);
		$('#code').text(data.tea_code);
		$('#wechat_code').text(data.tea_wechat_code.substring(0, 10));
		$('#asked_ques').attr('href', './asked_question.html?id=' + data.tea_id);
		$('#commented_ques').attr('href', './commented_question.html?id=' + data.tea_id);
		$("#setting").attr('href', '../setting.html?id=' + data.tea_id);
	}else{
		window.location.href = '../error.html';
	}
}
