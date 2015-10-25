<?php
require_once('../processes/connect.php');

$playersTable = 'players';
$data = mysql_query(
			"SELECT 
				gid, name, position, team, weeks, averagePoints, stdDevPoints, averageSimpleScore, currentExpectedPoints, currentExpectedSimpleScore
			 FROM 
				$playersTable
			 ORDER BY position, currentExpectedSimpleScore DESC") or die(mysql_error());

$allPositions = array();

$currentPos = "";
$position = array();
$players = array();
while($entry = mysql_fetch_array($data))
{
	if($currentPos != $entry['position'])
	{
		$position[] = $currentPos;
		$position[] = $players;
		$allPositions[] = $position;
		$players = array();
		$position = array();
		$currentPos = $entry['position'];
	}
	if($entry['weeks'] < 6) continue;
	if(($entry['averageSimpleScore'] * 1.5) < $entry['currentExpectedSimpleScore']) continue;
	
	$players[] = $entry;
}

$position[] = $currentPos;
$position[] = $players;
$allPositions[] = $position;

echo json_encode($allPositions);

?>