<?php
	session_start();
	$operation = @$_POST['operation'];
	$commentObj = new Comment();
	if ($operation == "add_comment") {
		$comment = @$_POST['comment'];
		$commentObj->addComment($comment);
		return;
	}else if ($operation == "get_comment_list") {
		$page = @$_POST['page'];
		$id = @$_POST['que_id'];
		$commentObj->getCommentList($id, $page);
		return;
	}


/**
* 评论类
*/
class Comment{
	
	private $res;
	private $handleMysqlObj;

	function __construct(){
		require_once("util/HandleMysql.class.php");
		require_once("config/config.php");
		$this->res['status'] = "-1";
		$this->handleMysqlObj = new HandleMysql(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_CODING);
	}

	/**
	 * 得到评论列表
	 * @return [type] [description]
	 */
	function getCommentList($id, $page){
		if ($page != "") {
			$sql = "select * from comments where que_id = " . $id ." limit " . (($page - 1) * 10) . ", 10";
			$resArray = array();
			$query = $this->handleMysqlObj->query($sql);
			if (!is_bool($query)) {

				//查看session获得类型
				$type = "";
				if(@$_SESSION['teacher'] != null){
					$type = 'teacher';
				}else if (@$_SESSION['student'] != null) {
					$type = 'student';
				}
				if($type == "teacher"){
					$this->res['type'] = "teacher";
				}else if ($type == "student") {
					$this->res['type'] = "student";
				}else{
					$this->res['type'] = "";
				}

				while($result = $this->handleMysqlObj->fetchArray($query)){
					array_push($resArray, $result);
				}

				$numSql = "select count(*) as num from comments";
				$num = $this->handleMysqlObj->getOne($numSql);
				$num = $num['num'];
				if (!is_bool($num)) {
					$this->res['num'] = floor(($num / 10) + 1);
				}else{
					$this->res['num'] = '1';
				}
				$this->res['status'] = "1";
				$this->res['data'] = $resArray;
				$resJson = json_encode($this->res);
				echo $resJson;
				return;
			}
		}

		$type = "";
		if(@$_SESSION['teacher'] != null){
			$type = 'teacher';
		}else if (@$_SESSION['student'] != null) {
			$type = 'student';
		}
		if($type == "teacher"){
			$this->res['type'] = "teacher";
		}else if ($type == "student") {
			$this->res['type'] = "student";
		}else{
			$this->res['type'] = "";
		}

		$this->res['status'] = "-1";
		$this->res['data'] = $resArray;
		$resJson = json_encode($this->res);
		echo $resJson;
	}

	/**
	 * 添加评论
	 * @param [type] 
	 */
	function addComment($comment){
		$commentArray = json_decode($comment, true);
		if ($commentArray != "") {
			//通过session获得stu_id
			$student = $_SESSION['student'];
			$stu_id = $student['stu_id'];
			$com_time = date("Y-m-d H:i");;
			$comm = array(
				'stu_id' => $stu_id,
				'que_id' => $commentArray['que_id'],
				'com_time' => $com_time,
				'com_content' => $commentArray['com_content']
			);
			$insertRes = $this->handleMysqlObj->insert('comment', $comm);
			if ($insertRes) {
				$this->res['status'] = "1";
				$resJson = json_encode($this->res);
				echo $resJson;
				return;
			}
		}
		$this->res['status'] = "-1";
		$resJson = json_encode($this->res);
		echo $resJson;
	}
}
