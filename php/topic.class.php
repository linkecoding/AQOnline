<?php
	session_start();
	$operation = @$_POST['operation'];
	$topicObj = new Topic();
	if($operation == "add_topic"){
		$topic = @$_POST['topic'];
		$topicObj->addTopic($topic);
	}else if ($operation == "get_topic_list") {
		$page = @$_POST['page'];
		$topicObj->getTopicList($page);
	}else if($operation == "get_total_num") {
		$topicObj->getTotalNum();
	}else if($operation == "get_topic_detail"){
		$id = @$_POST['top_id'];
		$topicObj->getTopicDetail($id);
	}
	/**
	* 题目类经典
	*/
	class Topic{

		private $res;
		private $handleMysqlObj;
		
		function __construct(){
			require_once("util/HandleMysql.class.php");
			require_once("config/config.php");
			$this->res['status'] = "-1";
			$this->handleMysqlObj = new HandleMysql(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_CODING);
		}

		/**
		 * 添加(提出经典题目)
		 */
		function addTopic($topic){
			$topArray = json_decode($topic, true);
			// 通过session获得tea_id
			$tea_id = $_SESSION['teacher']['tea_id'];
			$top_time = date("Y-m-d H:i");
			$top = array(
				'top_title' => $topArray['top_title'],
				'top_content' => $topArray['top_content'],
				'top_answer' => $topArray['top_answer'],
				'cou_id' => $topArray['cou_id'],
				'tea_id' => $tea_id,
				'top_time' => $top_time
			);

			$insertRes1 = $this->handleMysqlObj->insert('good_topic', $top);
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
		 * 得到经典题目列表
		 * @return [type] [description]
		 */
		function getTopicList($page){
			if ($page != "") {
				$sql = "select * from topics limit " . (($page - 1) * 10) . ", 10";
				$resArray = array();

				$query = $this->handleMysqlObj->query($sql);
				if (!is_bool($query)) {
					while($result = $this->handleMysqlObj->fetchArray($query)){
						array_push($resArray, $result);
					}

					$numSql = "select count(*) as num from topics";
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
			$this->res['status'] = "-1";
			$this->res['data'] = $resArray;
			$resJson = json_encode($this->res);
			echo $resJson;
		}

		/**
		 * 获取topic的详情
		 * @return [type] [description]
		 */
		function getTopicDetail($id){
			if ($id != "") {
				$sql = "select * from topics where top_id = " . $id;
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