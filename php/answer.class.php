<?php
	session_start();
	$operation = @$_POST['operation'];
	$answerObj = new Answer();
	if($operation == "add_answer"){
		$answer = @$_POST['answer'];
		$answerObj->addAnswer($answer);
	}else if($operation == "get_answer"){
		$id = @$_POST['que_id'];
		$answerObj->getAnswer($id);
	}
	/**
	* 答案类
	*/
	class Answer{

		private $res;
		private $handleMysqlObj;
		
		function __construct(){
			require_once("util/HandleMysql.class.php");
			require_once("config/config.php");
			$this->res['status'] = "-1";
			$this->handleMysqlObj = new HandleMysql(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_CODING);
		}

		/**
		 * 添加答案
		 */
		function addAnswer($answer){
			$ansArray = json_decode($answer, true);
			//通过session获得教师id
			$teacher = $_SESSION['teacher'];
			$tea_id = $teacher['tea_id'];
			$ans_time = date("Y-m-d H:i");
			$ans = array(
				'tea_id' => $tea_id,
				'que_id' => $ansArray['que_id'],
				'ans_time' =>$ans_time,
				'ans_content' => $ansArray['ans_content']
			);

			$insertRes1 = $this->handleMysqlObj->insert('answer_question', $ans);
			if($insertRes1){
				$this->res['status'] = '1';
				$resJson = json_encode($this->res);
				echo $resJson;
				return;
			}
			$this->res['status'] = "-1";
			$resJson = json_encode($this->res);
			echo $resJson;
		}


		/**
		 * 获取问题的答案
		 * @return [type] [description]
		 */
		function getAnswer($id){
			if ($id != "") {
				$sql = "select * from answers where que_id = " . $id;
				$result = $this->handleMysqlObj->getOne($sql);
				if(!is_bool($result)){
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