$(function(){
	//获得问题的id
	id = window.location.search.substring(4);
	$('#comment_btn').click(function(event) {
		comment_content = $('#comment_textarea').val();
		if (comment_content == "") {
			alert("请填写评论内容");
			return;
		}
		values = {};
		values['que_id'] = id;
		values['com_content'] = comment_content;
		comment = JSON.stringify(values);
		$.post(
			'../php/comment.class.php',
			{
				'operation': 'add_comment',
				'comment': comment
			},
			function(data, textStatus, xhr) {
				try{
			        res = $.parseJSON(data);
			        if (res.status == 1) {
			           	alert("评论成功");
			            return;
			        }else{
			            alert("评论失败");
			        }
			    }catch(err){
			        alert(err);
			        alert("评论失败");
			    }
		});
	});
});