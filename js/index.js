$(document).ready(function(){
	$.post(
		'./php/course.class.php',
		{
			'operation': 'get_course_list',
			'num':'16'
		},
		function(data, textStatus, xhr) {
			try{
	            res = $.parseJSON(data);
	            if (res.status == 1) {
	                resGrid = $("#id_course_grid");
	                resGrid.html("");
	                $.each(res.data, function(index, el) {
	                	resGrid.append('<a href="./ques_list.html?id=' + el.cou_id + '" class="grid"><div class="weui_grid_icon"><img src="' + el.cou_img_url + '" alt="" style="width:100%;height:100%;"></div><p class="weui_grid_label">' + el.cou_name + '</p></a>');
	                });
	                return;
	            }else{
	            	alert("查询数据出错");
	            }
	        }catch(err){
	            alert(err);
	            alert("查询数据出错");
	        }
	});
});