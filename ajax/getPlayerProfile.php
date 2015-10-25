<?php
require_once('../processes/connect.php');

$gid = $_REQUEST['gid'];
	
$playersTable = 'players';
$data = mysql_query(
			"SELECT 
				name, position, team, weeks, averagePoints, stdDevPoints, simpleScore
			 FROM 
				$playersTable
			 WHERE gid='$gid'") or die(mysql_error());
 
$entry = mysql_fetch_array($data);

echo json_encode($entry);
?>