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

$maxData = mysql_query(
			"SELECT 
				MAX(averagePoints) as maxAvgPoints, MAX(stdDevpoints) as maxStdDev
			 FROM 
				$playersTable") or die(mysql_error());

$maxEntry = mysql_fetch_array($maxData);

$entry['maxAvgPoints'] = $maxEntry['maxAvgPoints'];
$entry['maxStdDev'] = $maxEntry['maxStdDev'];

echo json_encode($entry);
?>