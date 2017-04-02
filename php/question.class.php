<?php
	session_start();
	$operation = @$_POST['operation'];
	$questionObj = new Question();
	if($operation == "add_question"){
		$question = @$_POST['question'];
		$questionObj->addQuestion($question);
	}else if($operation == "get_question_list"){
		$page = @$_POST['page'];
		$type = @$_POST['type'];
		$id = @$_POST['cou_id'];
		$questionObj->getQuestionList($page, $type, $id);
	}else if($operation == "get_ques_detail"){
		$id = @$_POST['que_id'];
		$questionObj->getQuesDetail($id);
	}
	/**
	* 问题类
	*/
	class Question{

		private $res;
		private $handleMysqlObj;
		
		function __construct(){
			require_once("util/HandleMysql.class.php");
			require_once("config/config.php");
			$this->res['status'] = "-1";
			$this->handleMysqlObj = new HandleMysql(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_CODING);
		}

		/**
		 * 添加(提出问题)
		 */
		function addQuestion($question){
			$queArray = json_decode($question, true);
			$ques = array(
				'que_title' => $queArray['que_title'],
				'que_content' => $queArray['que_content'] 
			);

			$insertRes1 = $this->handleMysqlObj->insert('question', $ques);
			if($insertRes1){
				//通过session获得stu_id
				$student = $_SESSION['student'];
				$stu_id = $student['stu_id'];
				$que_id = $this->handleMysqlObj->insertId();
				$cou_id = $queArray['cou_id'];
				$ask_time = date("Y-m-d H:i");

				$askQuestion = array(
					'stu_id' => $stu_id,
					'que_id' => $que_id,
					'cou_id' => $cou_id,
					'ask_time' => $ask_time 
				);

				$insertRes2 = $this->handleMysqlObj->insert('ask_question', $askQuestion);

				if($insertRes2){
					$this->res['status'] = '1';
					$resJson = json_encode($this->res);
					echo $resJson;
					return;
				}
			}
			$this->res['status'] = "-1";
			$resJson = json_encode($this->res);
			echo $resJson;
		}

		/**
		 * 得到问题的列表
		 * @param  [type] $page [description]
		 * @return [type]       [description]
		 */
		function getQuestionList($page, $type, $id = null){
			if ($page != null && $type != null) {
				if($type == "0"){
					if($id != null){
						$sql = "select * from questions where cou_id=" . $id . " order by que_id desc limit " . (($page - 1) * 10) . ", 10";
					}else{
						//最新问题
						$sql = "select * from questions order by que_id desc limit " . (($page - 1) * 10) . ", 10";
					}
				}else if($type == "1"){
					//热门问题(根据阅读量排名)
					$sql = "select * from questions limit " . (($page - 1) * 10) . ", 10";
				}
				$resArray = array();

				$query = $this->handleMysqlObj->query($sql);
				if (!is_bool($query)) {
					while($result = $this->handleMysqlObj->fetchArray($query)){
						array_push($resArray, $result);
					}
					//统计总记录数
					$numSql = "select count(*) as num from questions";
					$num = $this->handleMysqlObj->getOne($numSql);
					$num = $num['num'];
					if (!is_bool($num)) {
						$this->res['num'] = floor(($num / 10) + 1);
					}else{
						$this->res['num'] = '1';
					}

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
					$this->res['status'] = "1";
					$this->res['data'] = $resArray;
					$resJson = json_encode($this->res);
					echo $resJson;
					return;
				}

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
				$this->res['status'] = "-1";
				$this->res['data'] = $resArray;
				$resJson = json_encode($this->res);
				echo $resJson; 
			}
		}

		/**
		 * 获取question的详情
		 * @return [type] [description]
		 */
		function getQuesDetail($id){
			if ($id != "") {
				$sql = "select * from questions where que_id = " . $id;
				$result = $this->handleMysqlObj->getOne($sql);
				if(!is_bool($result)){
					//判断session是否是老师
					if(@$_SESSION['teacher'] != null){
						$session = 'teacher';
					}else if (@$_SESSION['student'] != null) {
						$session = 'student';
					}
					if($session == "teacher"){
						$this->res['type'] = 'teacher';
					}else if ($session == 'student') {
						$this->res['type'] = 'student';
					}
					$this->res['data'] = $result;
					$this->res['status'] = '1';
					echo json_encode($this->res);
					return;
				}
			}

			$this->res['status'] = '-1';		
			echo json_encode($this->res);
		}
	}