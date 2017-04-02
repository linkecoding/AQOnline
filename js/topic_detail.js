$(document).ready(function(){
	id = window.location.search.substring(4);
	$.post(
		'./php/topic.class.php',
		{
			'operation': 'get_topic_detail',
			'top_id': id
		},
		function(data, textStatus, xhr) {
			try{
	            res = $.parseJSON(data);
	            if (res.status == 1) {
	            	data = res.data;
	            	$('#top_title').text(data.top_title);
	            	$('#top_time').text(data.top_time);
	            	$('#top_content').html(data.top_content.replace('../', './'));
	            	$('#tea_nick_name').text(data.tea_nick_name);
	            	$('#top_answer').html(data.top_answer.replace('../', './'));
		            return;
	            }
	        }catch(err){
	        	
	        }
	});
});