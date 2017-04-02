<?php
	/**
	 * 翻译类,通过对传入的字符进行识别并翻译成汉语
	 */
	class langTranslate{
		/**
		 * 将传入的字符翻译成中文
		 * @param  [type] $str [待翻译数据]
		 * @return [type]      [翻译结果]
		 */
		function toChines($str){

			// 中文、英文、日文的正则(utf-8编码)
			$china = '/^[\x{4e00}-\x{9fa5}]+/u';
			$english = '/^[a-zA-Z]+/';
			$japan = '/^[\x{0800}-\x{4e00}]+/u';

			//先对传入数据判断语言类型
			if(preg_match($china, $str)){
				// 对中文原样返回
				return $str;
			}else if(preg_match($english, $str)){
				// 对英文翻译为中文
				return $this->translate($str, 'en', 'zh');
			}else if(preg_match($japan, $str)){
				// 将日文翻译为中文
				return $this->translate($str, 'jp', 'zh');
			}else{
				// 对数字等原样返回
				return $str;
			}
		}

		/**
		 * 调用翻译接口处理数据(传入的待翻译的数据必须为utf-8且进行urlencode编码)
		 * @return [type] [description]
		 */
		function translate($query, $from, $to){
			$apikey = 'f04a61e1bf06e8761a06b5e31c64c16a';

			$ch = curl_init();
		    $url = 'http://apis.baidu.com/apistore/tranlateservice/translate?query=' . urlencode($query) . '&from=' . $from . '&to=' . $to;
		    $header = array(
		        "apikey: $apikey",
		    );
		    // 添加apikey到header
		    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    // 执行HTTP请求
		    curl_setopt($ch , CURLOPT_URL , $url);
		    $res = curl_exec($ch);

		    $res = json_decode($res, true);
		    return $res['retData']['trans_result'][0]['dst'];
		}
	}

?>