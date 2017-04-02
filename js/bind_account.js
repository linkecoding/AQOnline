$(document).ready(function(){
 	$("#id_bind_account").click(function(event) {
 		var values = {};
 		//获得类型
 		type = window.location.search.substring(4);
 		code = $("#id_code").val();
 		password = $("#id_password").val();
 		if(code == ""){
 			alert("请输入账号")
 		}else if (password == "") {
 			alert("请输入密码");
 		}else{
 			values['type'] = type;
 			values['code'] = code;
 			values['password'] = password;
 			userinfo = JSON.stringify(values);
 			bindAccount(userinfo);
 		}
 	});
});

/**
 * 绑定函数请求
 * @param  {[type]} userinfo [description]
 * @return {[type]}          [description]
 */
function bindAccount(userinfo) {
	$.post(
		'../php/account.class.php',
		{
			'operation': 'bind_account',
			'userinfo': userinfo
		},
		function(data, textStatus, xhr) {
			try{
	            res = $.parseJSON(data);
	            if (res.status == 1) {
	            	alert("绑定账号成功");
	            }
	        }catch(err){
	        	alert("绑定账号失败,请检查账号和密码是否正确");
	        }
	});
}