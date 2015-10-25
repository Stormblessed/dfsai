<?php
	$db_host="localhost";
	$db_username="root";
	$db_password="";
	$db_name="dailyfantasystats";
	
	//$db_host    ="raleuenbergercom.ipagemysql.com";
	//$db_username="umn_dfsai_admin";
	//$db_password="WonderfulSandwich!7";
	//$db_name    ="dfsai";
	
	mysql_connect("$db_host", "$db_username", "$db_password")or die("Can’t Connect to the Server");
	mysql_select_db("$db_name")or die("Can’t Select the Database");
	
	session_start();
?>