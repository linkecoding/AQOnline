<?php
class responseMsg{
	var $fromUsername;
	var $toUsername;
	var $time;
	public function __construct($postObj){
		$this->fromUsername = $postObj->FromUserName;
        $this->toUsername = $postObj->ToUserName;
        $this->time = time();
	}


	//回复单文本
    public function responseText($contentStr){
      	$msgType = "text";
        $textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					</xml>";
        	$resultStr = sprintf($textTpl, $this->fromUsername, $this->toUsername, $this->time, $msgType, $contentStr);
        	echo $resultStr;
    }


    //回复 单/多 图文
    public function responsePicText($arr){
    	$msgType = "news";
    	$textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <ArticleCount>".count($arr)."</ArticleCount>
                    <Articles>";
                foreach($arr as $k=>$v){
                $textTpl .="<item>
                            <Title><![CDATA[".$v['title']."]]></Title> 
                            <Description><![CDATA[".$v['description']."]]></Description>
                            <PicUrl><![CDATA[".$v['picUrl']."]]></PicUrl>
                            <Url><![CDATA[".$v['url']."]]></Url>
                            </item>";
                }
                
                $textTpl .="</Articles>
                            </xml> ";
                $resultStr = sprintf($textTpl, $this->fromUsername, $this->toUsername, $this->time, $msgType);
                echo $resultStr;  
    }


    //关键字回复
    public function responseKey($key){

			switch($key){
				case "游戏":
					$contentStr = '您输入的数字是1';
				break;
				case "2048":
					$contentStr = '您输入的数字是2';
				break;
				case "社区":
					$contentStr = '您输入的数字是3';
				break;
			}	
				$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>";
				
				$msgType  = 'text';
				echo sprintf($textTpl, $this->fromUsername, $this->toUsername, $this->time, $msgType, $contentStr);
    }
}
?>