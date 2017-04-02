var $j=jQuery.noConflict();
$j(function(){
	getTopicList(1);
    $j('#home_page').click(function(event) {
        now = $j('#now_page').text().split('/')[0];
        total = $j('#now_page').text().split('/')[1];
        $j('#now_page').text('1/' + total);
        getTopicList(1);
    });
    $j('#pre_page').click(function(event) {
        now = $j('#now_page').text().split('/')[0];
        total = $j('#now_page').text().split('/')[1];
        if(now <= 1){
            $j('#now_page').text('1/' + total);
            getTopicList(1);
            return;
        }else{
            $j('#now_page').text((now - 1) + '/' + total);
            getTopicList(now - 1);
        }
    });

    $j('#next_page').click(function(event) {
        now = $j('#now_page').text().split('/')[0];
        total = $j('#now_page').text().split('/')[1];
        if(now >= total){
            $j('#now_page').text(total + '/' + total);
            getTopicList(total);
            return;
        }else{
            $j('#now_page').text((parseInt(now) + 1) + '/' + total);
            getTopicList(parseInt(now) + 1);
        }
    });

    $j('#end_page').click(function(event) {
        now = $j('#now_page').text().split('/')[0];
        total = $j('#now_page').text().split('/')[1];
        $j('#now_page').text(total + '/' + total);
        getTopicList(total);
    });
});

/**
 * 设置经典题目内容到List
 * @param {[type]} data [description]
 */
function setData(data){
	try{
        res = $j.parseJSON(data);
        if (res.status == 1) {
            setPage(res.num);
            resList = $j("#topic_list");
            resList.html("");
            $j.each(res.data, function(index, el) {
                el.top_content = el.top_content.replace(/<img.*\/>/ig, "");
            	resList.append('<div class="weui_media_box weui_media_text"><a href="./topic_detail.html?id=' + el.top_id + '"><h4 class="weui_media_title">' + el.top_title+ '</h4><p class="weui_media_desc">' + el.top_content + '</p><ul class="weui_media_info"><li class="weui_media_info_meta">' + el.top_time + '</li><li class="weui_media_info_meta">' + el.tea_nick_name + '</li><li class="weui_media_info_meta weui_media_info_meta_extra">' + el.cou_name + '</li></ul></a></div>');
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
function getTopicList(now){
	$j.post(
		'./php/topic.class.php',
		{
			'operation': 'get_topic_list',
			'page': now
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