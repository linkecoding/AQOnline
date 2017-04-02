$(document).ready(function(){
	id = window.location.search.substring(4);
	$.post(
		'./php/answer.class.php',
		{
			'operation': 'get_answer',
			'que_id': id
		},
		function(data, textStatus, xhr) {
			try{
	            res = $.parseJSON(data);
	            if (res.status == 1) {
	            	data = res.data;
	            	$('#ans_time').text(data.ans_time);
	            	$('#ans_content').html(data.ans_content.replace('../', './'));
	            	$('#tea_nick_name').text(data.tea_nick_name);
		            return;
	            }
	        }catch(err){
	        	
	        }
	});
});