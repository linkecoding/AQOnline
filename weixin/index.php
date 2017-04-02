<?php
require_once("class/check.class.php");
require_once("class/responseMsg.class.php");
require_once("class/dealWechat.class.php");
$check = new check();

$deal = new dealWechat();
if($_GET["echostr"])
{
	$check->valid();
}
else
{
	$deal->deal();
}

?>