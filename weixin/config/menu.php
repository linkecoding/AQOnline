<?php
/*

	菜单配置方法(Json格式)

 */

class menu{

	function menuData(){

		$data = ' {

	    "button":

	    [

	     

	      	{

	           "name":"了解",				

	           "sub_button":

	           [

		            {

		               "type":"view",

		               "name":"随机码",

		               "url":"http://www.xinliba.cn/WeChat/html/randomnum.html"

		            },

		            {

		               "type":"click",

		               "name":"来源",

		               "key":"ly"

		            }

			   ]

	       },

		   {

	           "name":"2048",

	           "sub_button":

	           [

		            {

		               "type":"view",

		               "name":"经典版",

		               "url":"http://www.xinliba.cn/20481/"

		            },

		            {

		               "type":"view",

		               "name":"程序员版",

		               "url":"http://www.xinliba.cn/WeChat/html/randomnum.html"

		            }

	           ]

	       }

		]

	 }';

		return $data;

	}

	

	

	



}

?>