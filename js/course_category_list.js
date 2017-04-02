var $j=jQuery.noConflict();
$j(document).ready(function(){
	getCourseList();
});

$(function(){
 $('.searchbar_wrap').searchBar({
    cancelText:"取消",
    searchText:'关键字',
    onsubmit:function(value){
    	searchCourse(value);
    },
    oncancel:function(){
    	getCourseList();
    }
});
});

/**
 * 设置获得的课程信息到列表中
 * @param {[type]} data [description]
 */
function setData(data){
	try{
        res = $j.parseJSON(data);
        if (res.status == 1) {
            resList = $j("#id_course_list");
            resList.html("");
            $j.each(res.data, function(index, el) {
            	resList.append('<a class="weui_cell " href="./ques_list.html?id=' + el.cou_id + '"><div class="weui_cell_hd"><img src="' + el.cou_img_url + '" alt="" style="width:20px;margin-right:5px;display:block"></div><div class="weui_cell_bd weui_cell_primary"><p>' + el.cou_name + '</p></div><div class="weui_cell_ft"></div></a>');
            });
            if (res.type == "student") {
                $j("#add_course_category").hide();
            }else if (res.type == "teacher") {
                $j("#add_course_category").show();
            }else{
                $j("#add_course_category").hide();
            }
            return;
        }else{
        	alert("查询数据出错");
        }
    }catch(err){
        alert("查询数据出错");
    }
}

/**
 * 获得课程信息列表
 * @return {[type]} [description]
 */
function getCourseList(){
	$j.post(
		'./php/course.class.php',
		{
			'operation': 'get_course_list',
			'num': '0'
		},
		function(data, textStatus, xhr) {
			setData(data);
	});
}

/**
 * 根据关键字搜索课程
 * @param  {[type]} key [description]
 * @return {[type]}     [description]
 */
function searchCourse(key){
	$.post(
		'./php/course.class.php',
		{
			'operation': 'search_course',
			'key': key
		},
		function(data, textStatus, xhr) {
			setData(data);
	});
}