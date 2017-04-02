<?php
	$handleImgObj = new HandleImg();
	$folderName = "question";
	$handleImgObj->uploadImg($folderName);

/**
* 图片处理相关类
*/
class HandleImg{
	private $res;
	function __construct(){
		$this->res['status'] = "-1";
	}
	/**
	 * 存储图片,返回图片路径
	 * @return [type] [description]
	 */
	function uploadImg($folderName){
		if ((($_FILES['file1']["type"] == "image/gif")
		  || ($_FILES['file1']["type"] == "image/jpeg")
		  || ($_FILES['file1']["type"] == "image/pjpeg")
		  || ($_FILES['file1']["type"] == "image/png"))
		    && ($_FILES['file1']["size"] < 2000000)){
		    if ($_FILES['file1']["error"] > 0){
		      echo $_FILES['file1']["error"];
		    }else{
		      $path = "../../images/" . $folderName . "/" . $_FILES['file1']["name"];
		      if (file_exists($path)){
		        echo $_FILES['file1']["name"] . "早已经存在";
		      }else{
				$filename = basename($path);
				$extpos = strrpos($filename,'.');
				$ext = substr($filename, $extpos+1);
				$filename = md5(time()) . "." . $ext;
				move_uploaded_file($_FILES['file1']["tmp_name"],
				"../../images/" . $folderName . "/" . $filename);
		        $resm['error'] = '0';
		        $resm['path'] = 'images/' . $folderName . '/' . $filename;
		        echo json_encode($resm);
		      }
		    }
		  }else{
		  	echo "文件格式不正确，只能上传图片";
		  }
	}
}