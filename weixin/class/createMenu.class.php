<?php
$menu = new createMenu();

echo $menu->create($data);
class createMenu{

	public $accessToken;

	public $data;

	function __construct(){

		

		require_once("getAccTokenIp.class.php");

		require_once("../config/menu.php");

		$arr = array();

		$getAcc = new getAccTokenIp();

		$menu = new menu();

		$arr = $getAcc->getWxAccessToken(APPID, APPSECRET);

		$this->accessToken = $arr['access_token'];

		$this->data = $menu->menuData();

	}



	//创建菜单函数

	function create($data){

		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->accessToken;

		 $ch = curl_init();

		 curl_setopt($ch, CURLOPT_URL, $url);

		 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

		 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');

		 //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		 curl_setopt($ch, CURLOPT_AUTOREFERER, 1);

		 curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);

		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		 $tmpInfo = curl_exec($ch);

		 if (curl_errno($ch)) {

		  	return curl_error($ch);

		 }

		 curl_close($ch);

		 return $tmpInfo;

	}

}

?>