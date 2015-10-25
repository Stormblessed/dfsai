<?php
	$db_host="localhost";
	$db_username="root";
	$db_password="";
	$db_name="dailyfantasystats";
		
	mysql_connect("$db_host", "$db_username", "$db_password")or die("Can’t Connect to the Server");
	mysql_select_db("$db_name")or die("Can’t Select the Database");
	
	session_start();
?>