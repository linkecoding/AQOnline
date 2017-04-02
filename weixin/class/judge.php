<?php
	header("Content-type: text/html; charset=utf-8"); 
	session_start();
	require_once('../config/config.php');
	$openid = getOpenId();
	if(!getTeacherOrStudent($openid)){
		echo "<script>alert('请先绑定账号');</script>";
	}else{
		echo "<script>window.location.href = '../../index.html';</script>";
	}


	function http_curl($url){
        //1.初始化curl
        $ch = curl_init();
        //2.设置curl的参数
        curl_setopt($ch, CURLOPT_URL, $url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        //3.采集
        $output = curl_exec($ch);

        //4.关闭
        curl_close($ch);
        return $output;
    }

    /**
	 * 获得code
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	function getCode(){
		$code = null;
		if(@isset($_GET['code'])){
			$code = $_GET['code'];
		}
		return $code;
	}

	/**
	 * 获得openid(静默授权)
	 * @return [type] [description]
	 */
    function getOpenId(){
    	$code = getCode();
    	$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . APPID . '&secret=' . APPSECRET . '&code=' . $code . '&grant_type=authorization_code';
    	$access = json_decode(http_curl($url), true);
    	return $access['openid'];
    }


    /**
	 * 使用openid去学生表和教师表查询
	 * @param  [type] $openid [description]
	 * @return [type]         [description]
	 */
	function getTeacherOrStudent($openid){
		if($openid != null){
			require_once('../../php/config/config.php');
			require_once('../../php/util/HandleMysql.class.php');
			$handleMysqlObj = new HandleMysql(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_CODING);
			$sql1 = "select * from student where stu_wechat_code='" . $openid . "'";
			$sql2 = "select * from teacher where tea_wechat_code='" . $openid . "'";
			$flag1 = $handleMysqlObj->getOne($sql1);
			$flag2 = $handleMysqlObj->getOne($sql2);

			if (!is_bool($flag1)) {
				$_SESSION['student'] = $flag1;
			}

			if (!is_bool($flag2)) {
				$_SESSION['teacher'] = $flag2;
			}

			if ($flag1 || $flag2) {
				return true;
			}else{
				return false;
			}
		}
		return false;
	}