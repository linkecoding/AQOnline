<?php
class externalApi{
	public $response;
	public function __construct($postObj){
		require_once("responseMsg.class.php");
		$this->response = new responseMsg($postObj);

	}

	public function responseTuLing($keyword){
		$ch = curl_init();
	    $url = 'http://www.tuling123.com/openapi/api?key=452e4eff544a14f59cda43e45704e08f&info='.$keyword;
	    $header = array(
	        'apikey: 3d0ad68d6e94349beef0baa7d52c7467',
	    );
	    // 添加apikey到header
	    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    // 执行HTTP请求
	    curl_setopt($ch , CURLOPT_URL , $url);
	    $res = curl_exec($ch);
	    $arr = json_decode($res, true);

	    $code = $arr['code'];
	    //$array = array();
	    switch($code){
	    	//纯文本
	    	case 100000:$content = $arr['text'];
	    	$this->response->responseText($content);
	    	break;

	    	//图片
	    	case 200000:$content = $arr['text'].$arr['url'];
	    	$this->response->responseText($content);
	    	break;

	    	//新闻
	    	case 302000:
			$array = array(
				array(
					'title'=>$arr['list'][0]['article'],
					'description'=>$arr['list'][0]['source'],
					'picUrl'=>$arr['list'][0]['icon'],
					'url'=>$arr['list'][0]['detailurl'],
				),
				);
	    	$this->response->responsePicText($array);
	    	break;

	    	//列车
	    	case 305000:
	    	if(!is_null($arr['list'][0]['trainnum'])){
		    	$content = $arr['text']."\n列车：".$arr['list'][0]['trainnum']."\n始发地：".$arr['list'][0]['start']."\n目的地：".$arr['list'][0]['terminal'].
		    	"\n发车时间：".$arr['list'][0]['starttime']."\n到站时间：".$arr['list'][0]['endtime']."\n详情地址：".$arr['list'][0]['detailurl'];
		    }else{
		    	$content = "对不起，没有找到相关车次";
		    }
		    $this->response->responseText($content);
	    	break;

	    	//航班
	    	case 306000:
	    	if(!is_null($arr['list']['flight'])){
	    		$content = $arr['text']."\n航班：".$arr['list']['flight']."\n起飞时间：".$arr['list']['starttime']."\n到达时间：".$arr['list']['endtime'];
	    	}else{
	    		$content = "对不起，没有找到相关航班";	
	    	}
	    	$this->response->responseText($content);
	    	break;

	    	//菜谱
	    	case 308000:
	    	if(!is_null($arr['list'][0]['name'])){
	    		$array1 = array(
				array(
					'title'=>$arr['list'][0]['name'],
					'description'=>$arr['list'][0]['info'],
					'picUrl'=>$arr['list'][0]['icon'],
					'url'=>$arr['list'][0]['detailurl'],
				)
				);
	    	$this->response->responsePicText($array1);
	    	}else{
	    		$content = "对不起，没有找到相关的菜谱";
	    		$this->response->responseText($content);
	    	}
	    	break;
	    }
    }
}

?>