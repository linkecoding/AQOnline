$(document).ready(function(){
	id = window.location.search.substring(4);
	$('#answer_ques').attr('href', './teacher/answer_question.html?id=' + id);
	$.post(
		'./php/question.class.php',
		{
			'operation': 'get_ques_detail',
			'que_id': id
		},
		function(data, textStatus, xhr) {
			try{
	            res = $.parseJSON(data);
	            if (res.status == 1) {
	            	if (res.type == 'teacher') {
	            		$('#answer_ques').show();
	            	}else if(res.type == 'student'){
	            		$('#answer_ques').hide();
	            	}
	            	data = res.data;
	            	$('#que_title').text(data.que_title);
	            	$('#ask_time').text(data.ask_time);
	            	$('#que_content').html(data.que_content.replace('../', './'));
	            	$('#stu_nick_name').text(data.stu_nick_name);
	            	$('#look_answer').attr('href', './answer_detail.html?id=' + id);
	            	$('#look_comment').attr('href', './comment_list.html?id=' + id);
		            return;
	            }
	        }catch(err){
	        	
	        }
	});
});