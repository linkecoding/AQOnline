<?php
	session_start();
	$operation = @$_POST['operation'];
	$courseObj = new Course();
	if ($operation == "get_course_list") {
		$num = @$_POST['num'];
		$courseObj->getCourseList($num);
		return;
	}else if ($operation == "search_course") {
		$key = $_POST['key'];
		$courseObj->searchCourse($key);
		return;
	}else if ($operation == "add_course_category") {
		$courseCategory = $_POST['course_category'];
		$courseObj->addCourseCategory($courseCategory);
		return;
	}


/**
* 课程类
*/
class Course{
	
	private $res;
	private $handleMysqlObj;

	function __construct(){
		require_once("util/HandleMysql.class.php");
		require_once("config/config.php");
		$this->res['status'] = "-1";
		$this->handleMysqlObj = new HandleMysql(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_CODING);
	}

	/**
	 * 得到课程列表
	 * @return [type] [description]
	 */
	function getCourseList($num){
		if($num == 0){
			$sql = "select * from course_category";
		}else{
			$sql = "select * from course_category limit 0, " . $num;
		}

		$resArray = array();

		$query = $this->handleMysqlObj->query($sql);
		if (!is_bool($query)) {
			while($result = $this->handleMysqlObj->fetchArray($query)){
				array_push($resArray, $result);
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

	/**
	 * 根据关键字查询课程分类
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	function searchCourse($key){
		if($key != ""){
			$sql = "select * from course_category where cou_name like '%" . $key . "%'";
			$resArray = array();
			$query = $this->handleMysqlObj->query($sql);
			if (!is_bool($query)) {
				while($result = $this->handleMysqlObj->fetchArray($query)){
					array_push($resArray, $result);
				}
				$this->res['status'] = "1";
				$this->res['data'] = $resArray;
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
	 * 添加课程分类
	 * @param [type] $courseCategory [description]
	 */
	function addCourseCategory($courseCategory){
		$course_category = json_decode($courseCategory, true);
		if ($course_category != "") {
			$insertRes = $this->handleMysqlObj->insert('course_category', $course_category);
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
