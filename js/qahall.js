var $j=jQuery.noConflict();
$j(function(){
    //Tab相关设置
    $j("#ques_tab").find('a').first().addClass('bg_green');

	$j("#newest_ques").click(function(event) {
	    $j("#ques_tab").find('a').first().addClass('bg_green');
	    $j("#ques_tab").find('a').last().removeClass('bg_green');
	    getQuestionList($j("#ques_tab").find('a').index(), 1);
	});
	$j("#hot_ques").click(function(event) {
	    $j("#ques_tab").find('a').last().addClass('bg_green');
	    $j("#ques_tab").find('a').first().removeClass('bg_green');
	    getQuestionList($j("#ques_tab").find('a').index(), 1);
	});

	//加载问题列表
	getQuestionList($j("#ques_tab").find('a').index(), 1);

    $j('#home_page').click(function(event) {
        now = $j('#now_page').text().split('/')[0];
        total = $j('#now_page').text().split('/')[1];
        $j('#now_page').text('1/' + total);
        getQuestionList($j("#ques_tab").find('a').index(), 1);
    });
    $j('#pre_page').click(function(event) {
        now = $j('#now_page').text().split('/')[0];
        total = $j('#now_page').text().split('/')[1];
        if(now <= 1){
            $j('#now_page').text('1/' + total);
            getQuestionList($j("#ques_tab").find('a').index(), 1);
            return;
        }else{
            $j('#now_page').text((now - 1) + '/' + total);
            getQuestionList($j("#ques_tab").find('a').index(), now - 1);
        }
    });

    $j('#next_page').click(function(event) {
        now = $j('#now_page').text().split('/')[0];
        total = $j('#now_page').text().split('/')[1];
        if(now >= total){
            $j('#now_page').text(total + '/' + total);
            getQuestionList($j("#ques_tab").find('a').index(), total);
            return;
        }else{
            $j('#now_page').text((parseInt(now) + 1) + '/' + total);
            getQuestionList($j("#ques_tab").find('a').index(), parseInt(now) + 1);
        }
    });

    $j('#end_page').click(function(event) {
        now = $j('#now_page').text().split('/')[0];
        total = $j('#now_page').text().split('/')[1];
        $j('#now_page').text(total + '/' + total);
        getQuestionList($j("#ques_tab").find('a').index(), total);
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
            return;
        }else{
        	alert("查询数据出错");
        }
    }catch(err){
        //alert("查询数据出错");
    }
}

/**
 * 获得经典题目信息列表
 * @return {[type]} [description]
 */
function getQuestionList(type, now){
	$j.post(
		'./php/question.class.php',
		{
			'operation': 'get_question_list',
			'page': now,
			'type':type
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