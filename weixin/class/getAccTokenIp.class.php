<?php
require_once("../config/config.php");
/*$a = new getAccTokenIp();
$a->getWxAccessToken(APPID, APPSECRET);*/
class getAccTokenIp{
	function http_curl($url){
        //获取xinliba.cn
        //1.初始化curl
        $ch = curl_init();
        // $url = "http://www.xinliba.cn";
        //2.设置curl的参数
        curl_setopt($ch, CURLOPT_URL, $url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //3.采集
        $output = curl_exec($ch);

        //4.关闭
        curl_close($ch);

        var_dump($output);
    }

    //获取AccessToken
    function getWxAccessToken($appid, $appsecret){

        //1.请求地址
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
        
        //2.初始化
        $ch = curl_init();

        //3.设置参数
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //4.调用接口
        $res = curl_exec($ch);

        //5.关闭curl
        curl_close($ch);
        // if(curl_errno($ch)){
        //     var_dump(curl_error($ch));
        // }

        //将json数据转换为数组
        $arr = json_decode($res, true);
        return $arr;
        // var_dump($arr);
    }


    //获取微信服务器IP
    function getWxServerIp($url, $accessToken){
        $accessToken = "7BCGHw0FzcTbLTDHbkoJOX_PuDwqdPAcwL_EAtaB9nkDQtKKJWZpGKKb89BsbdNLCzd-0DHHIICCO42ly-8ss_HsNlwEosAFMwQ6NtEc-_UADUhAFAWZQ";
        $url = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=".$accessToken;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);
        if(curl_errno($ch)){
            var_dump(curl_error($ch));
        }

        $arr = json_decode($res, true);
        echo "<pre>";
        var_dump($arr);
        echo "</pre>";
    }
}


?>