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

$splitName = explode(", ", $entry['name']);
$reorderedName = "";
for($j = sizeof($splitName)-1; $j >= 0; $j--)
{
	$reorderedName .= $splitName[$j] . " ";
}
$entry['name'] = $reorderedName;

echo json_encode($entry);
?>