$(function(){
	getCommentList(1);
    $('#home_page').click(function(event) {
        now = $('#now_page').text().split('/')[0];
        total = $('#now_page').text().split('/')[1];
        $('#now_page').text('1/' + total);
        getCommentList(1);
    });
    $('#pre_page').click(function(event) {
        now = $('#now_page').text().split('/')[0];
        total = $('#now_page').text().split('/')[1];
        if(now <= 1){
            $('#now_page').text('1/' + total);
            getCommentList(1);
            return;
        }else{
            $('#now_page').text((now - 1) + '/' + total);
            getCommentList(now - 1);
        }
    });

    $('#next_page').click(function(event) {
        now = $('#now_page').text().split('/')[0];
        total = $('#now_page').text().split('/')[1];
        if(now >= total){
            $('#now_page').text(total + '/' + total);
            getCommentList(total);
            return;
        }else{
            $('#now_page').text((parseInt(now) + 1) + '/' + total);
            getCommentList(parseInt(now) + 1);
        }
    });

    $('#end_page').click(function(event) {
        now = $('#now_page').text().split('/')[0];
        total = $('#now_page').text().split('/')[1];
        $('#now_page').text(total + '/' + total);
        getCommentList(total);
    });
});

/**
 * 获得评论信息列表
 * @return {[type]} [description]
 */
function getCommentList(now){
    id = window.location.search.substring(4);
	$.post(
		'./php/comment.class.php',
		{
			'operation': 'get_comment_list',
            'que_id' : id,
			'page': now
		},
		function(data, textStatus, xhr) {
			setData(data, id);
	});
}


/**
 * 设置评论内容到List
 * @param {[type]} data [description]
 */
function setData(data, id){
    try{
        res = $.parseJSON(data);
        if (res.status == 1) {
            setPage(res.num);
            if(res.type == "teacher"){
                $("#comment_ques").hide();
            }else if(res.type == "student"){
                $("#comment_ques").show().attr('href', './comment_ques.html?id=' + id);
            }else{
                $("#comment_ques").hide();    
            }

            resList = $("#comment_list");
            resList.html("");
            $.each(res.data, function(index, el) {
                resList.append('<li class="weui-comment-item"><a href="./comment_detail.html?id=' + el.que_id + '"><div class="userinfo"><strong class="nickname">' + el.stu_nick_name + '</strong> <img class="avatar" src="' + el.stu_avatar_url + '"/></div><div class="weui-comment-msg"> <span class="status"></span> ' + el.com_content + ' </div><p class="time">' + el.com_time + '</p></a></li><hr>');
            });
            return;
        }else{
            alert("查询数据出错");
        }
    }catch(err){
        //alert("查询数据出错");
    }
}


/**
 * 设置页码
 * @param {[type]} page [description]
 */
function setPage(page){
    total = page;
    now = $('#now_page').text().split('/')[0];
    $("#now_page").text(now + "/" + total);
}