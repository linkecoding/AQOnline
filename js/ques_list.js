var $j=jQuery.noConflict();
$j(function(){
    //搜索框
    $('.searchbar_wrap').searchBar({
    cancelText:"取消",
    searchText:'关键字',
    onfocus: function (value) {
       
    },
    onblur:function(value) {

    },
    oninput: function(value) {

    },
    onsubmit:function(value){
    },
    oncancel:function(){

    },

    onclear:function(){

    }
    });

	//加载问题列表
	getQuestionList(1);

    $j('#home_page').click(function(event) {
        now = $j('#now_page').text().split('/')[0];
        total = $j('#now_page').text().split('/')[1];
        $j('#now_page').text('1/' + total);
        getQuestionList(1);
    });
    $j('#pre_page').click(function(event) {
        now = $j('#now_page').text().split('/')[0];
        total = $j('#now_page').text().split('/')[1];
        if(now <= 1){
            $j('#now_page').text('1/' + total);
            getQuestionList(1);
            return;
        }else{
            $j('#now_page').text((now - 1) + '/' + total);
            getQuestionList(now - 1);
        }
    });

    $j('#next_page').click(function(event) {
        now = $j('#now_page').text().split('/')[0];
        total = $j('#now_page').text().split('/')[1];
        if(now >= total){
            $j('#now_page').text(total + '/' + total);
            getQuestionList(total);
            return;
        }else{
            $j('#now_page').text((parseInt(now) + 1) + '/' + total);
            getQuestionList(parseInt(now) + 1);
        }
    });

    $j('#end_page').click(function(event) {
        now = $j('#now_page').text().split('/')[0];
        total = $j('#now_page').text().split('/')[1];
        $j('#now_page').text(total + '/' + total);
        getQuestionList(total);
    });
});

/**
 * 设置问题内容到List
 * @param {[type]} data [description]
 */
function setData(data){
	try{
        res = $j.parseJSON(data);
        if (res.status == 1) {
            setPage(res.num);
            resList = $j("#ques_list");
            resList.html("");
            $j.each(res.data, function(index, el) {
                el.que_content = el.que_content.replace(/<img.*\/>/ig, "");
            	resList.append('<div class="weui_media_box weui_media_text"><a href="./ques_detail.html?id=' + el.que_id + '"><h4 class="weui_media_title">' + el.que_title+ '</h4><p class="weui_media_desc">' + el.que_content + '</p><ul class="weui_media_info"><li class="weui_media_info_meta">' + el.ask_time + '</li><li class="weui_media_info_meta">' + el.stu_nick_name + '</li><li class="weui_media_info_meta weui_media_info_meta_extra">' + el.cou_name + '</li></ul></a></div>');
            });
            if (res.type == "teacher") {
                $j("#ques").hide();
            }else if (res.type == "student") {
                $j("#ques").show();
            }else{
                $j("#ques").hide();
            }
            return;
        }else{
        	alert("查询数据出错");
        }
    }catch(err){
        //alert("查询数据出错");
    }
}

/**
 * 获得题目信息列表
 * @return {[type]} [description]
 */
function getQuestionList(now){
    type = '0';
    id = window.location.search.substring(4);
	$j.post(
		'./php/question.class.php',
		{
			'operation': 'get_question_list',
			'page': now,
			'type': type,
            'cou_id': id
		},
		function(data, textStatus, xhr) {
			setData(data);
	});
}

/**
 * 设置页码
 * @param {[type]} page [description]
 */
function setPage(page){
    total = page;
    now = $j('#now_page').text().split('/')[0];
    $j("#now_page").text(now + "/" + total);
}