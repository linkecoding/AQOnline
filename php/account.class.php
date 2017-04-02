<?php
	session_start();
	$operation = @$_POST['operation'];
	$accountObj = new Account();
	if ($operation == 'bind_account') {
		$userinfo = @$_POST['userinfo'];
		$accountObj->bindAccount($userinfo);
	}else if ($operation == 'get_userinfo') {
		$type = @$_POST['type'];
		$accountObj->getUserinfo($type);
	}
/**
* 绑定账号类
*/
class Account{
	
	private $res;
	private $handleMysqlObj;

	function __construct(){
		require_once("util/HandleMysql.class.php");
		require_once("config/config.php");
		$this->res['status'] = "-1";
		$this->handleMysqlObj = new HandleMysql(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_CODING);
	}

	/**
	 * 绑定账号
	 * @param  [type] $id [description]
	 * @return [type]           [description]
	 */
	function bindAccount($userinfo){
		
		$userinfo = json_decode($userinfo, true);
		$userType = $userinfo['type'];
		if ($userType == 'student') {
			$user = $_SESSION['student'];
			$sql = "select stu_id, stu_password from student where stu_code = " . $userinfo['code'];
			$result = $this->handleMysqlObj->getOne($sql);
			if(!is_bool($result)){
				if ($result['stu_password'] == $userinfo['password']) {
					$student = array(
						'stu_wechat_code' => $user['openid'],
						'stu_nick_name' => $user['nickname'],
						'stu_avatar_url' => $user['headimgurl']
					);
					$where = 'stu_id = ' . $result['stu_id'];
					$updateRes = $this->handleMysqlObj->update('student', $student, $where);
					if($updateRes){
						$this->res['status'] = "1";
						$this->res['data'] = '';
						$resJson = json_encode($this->res);
						echo $resJson;
						return;
					}
				}
			}
		}else if ($userType == 'teacher') {
			$user = $_SESSION['teacher'];
			$sql = "select tea_id, tea_password from teacher where tea_code = " . $userinfo['code'];
			$result = $this->handleMysqlObj->getOne($sql);
			if(!is_bool($result)){
				if ($result['tea_password'] == $userinfo['password']) {
					$teacher = array(
						'tea_wechat_code' => $user['openid'],
						'tea_nick_name' => $user['nickname'],
						'tea_avatar_url' => $user['headimgurl']
					);
					$where = 'tea_id = ' . $result['tea_id'];
					$updateRes = $this->handleMysqlObj->update('teacher', $teacher, $where);
					if($updateRes){
						$this->res['status'] = "1";
						$this->res['data'] = '';
						$resJson = json_encode($this->res);
						echo $resJson;
						return;
					}
				}
			}
		}
		$this->res['status'] = "-1";
		$this->res['data'] = '';
		$resJson = json_encode($this->res);
		echo $resJson;
	}


	/**
	 * 得到session中的用户信息
	 * @param  [type] $type [description]
	 * @return [type]       [description]
	 */
	function getUserinfo($type){
		if ($type != null) {
			if ($type == 'student') {
				if (@$_SESSION['student'] != null) {
					$result = $_SESSION['student'];
					$this->res['status'] = "1";
					$this->res['data'] = json_encode($result);
					$resJson = json_encode($this->res);
					echo $resJson;
					return;
				}
			}else if ($type == 'teacher') {
				$result = $_SESSION['teacher'];
				if (@$_SESSION['teacher'] != null) {
					$this->res['status'] = "1";
					$this->res['data'] = json_encode($result);
					$resJson = json_encode($this->res);
					echo $resJson;
					return;
				}
			}
		}

		$this->res['status'] = "-1";
		$this->res['data'] = '';
		$resJson = json_encode($this->res);
		echo $resJson;
	}
}
