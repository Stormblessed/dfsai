<?php
require_once('../processes/connect.php');
$gid = $_REQUEST['gid'];
	
$fantasyStatsTable = 'player_entries';
$data = mysql_query(
			"SELECT 
				year, week, points
			 FROM 
				$fantasyStatsTable
			 WHERE gid='$gid'") or die(mysql_error());

$weeklyData = array();	 
while($entry = mysql_fetch_array($data))
{
	$playerStats = array();
	$playerStats[] = $entry['year'];
	$playerStats[] = $entry['week'];
	$playerStats[] = $entry['points'];
	$weeklyData[] = $playerStats;
}

echo json_encode($weeklyData);
?>