<?php
	require_once('../config/config.php');
	require_once("./HandleMysql.class.php");

	$openid = "142062410122";
	$handleMysqlObj = new HandleMysql(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_CODING);
	$sql1 = "select * from student where stu_code=" . $openid;
	var_dump($handleMysqlObj->getOne($sql1));