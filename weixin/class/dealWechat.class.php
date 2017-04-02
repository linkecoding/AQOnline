<?php
header("Content-type:text/html;charset=utf8");
class dealWechat{
    public $postObj;
    public $response;
    public $externalApi;
    public function __construct(){

        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            libxml_disable_entity_loader(true);
            $this->postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        }

        // 引入消息处理类
        require_once("responseMsg.class.php");
    	$this->response = new responseMsg($this->postObj);

    	// 引入图灵接口
    	require_once("externalApi.class.php");
    	$this->externalApi = new externalApi($this->postObj);

    	// 引入翻译类
    	require_once("langTranslate.class.php");
    	$this->langTranslate = new langTranslate();

    }

    public function deal(){

    	//订阅回复
    	$resString = "欢迎关注，我是一个聪明的机器人，我可以提供以下服务
		\n1.陪你聊天,你可以问我任何问题
		\n2.搜索图片,例:XX图片
		\n3.查看新闻,例:我想看新闻
		\n4.查询火车,例:北京到拉萨的火车
		\n5.查询航班,例:北京到南京的飞机
		\n6.查菜谱,例:辣子鸡
    	";			//回复的文字
    	if( strtolower( $this->postObj->MsgType) == 'event'){
			//如果是关注 subscribe 事件
			if( strtolower($this->postObj->Event == 'subscribe') ){
				$this->response->responseText($resString);
			}
		}

		// 消息类型处理
		$msgType = strtolower($this->postObj->MsgType);

		switch ($msgType) {
			// 文字消息处理
			case 'text':
				$content = trim($this->postObj->Content);
				// 先经过翻译处理
				$content = $this->langTranslate->toChines(trim($this->postObj->Content));
				// 图灵接口处理
				$this->externalApi->responseTuLing($content);
				break;
			case 'image':
				// 图片消息处理
				$this->response->responseText("已收到");
				break;
			case 'voice':
				// 语音消息处理
				$voiceContent = $this->postObj->Recognition;
				$this->externalApi->responseTuLing($voiceContent);
				break;
			default:
				
				break;
		}
    }   
}

?>