<?php
require_once('../processes/connect.php');

$gid = $_REQUEST['gid'];

$year = 2015;
$week = 6;

GetExpectedPoints($gid, $year, $week);

function GetExpectedPoints($gid, $year, $week)
{
	$CurrentYear = $year;
	$CurrentWeek = $week;
	
	$fantasyStatsTable = 'player_entries';
	$historicPlayerData = mysql_query("SELECT * FROM $fantasyStatsTable WHERE (gid='$gid' AND year<'$CurrentYear') OR (gid='$gid' AND year='$CurrentYear' AND week<'$CurrentWeek')");
	$currentPlayerData = mysql_query("SELECT * FROM $fantasyStatsTable WHERE gid='$gid' AND year='$CurrentYear' AND week='$CurrentWeek'");
	
	$totalPoints = 0;
	$totalWeeks = 0;
	$weeklyPoints = array();
	while($entry = mysql_fetch_array($historicPlayerData))
	{
		$totalWeeks++;
		$totalPoints += $entry['points'];
		$weeklyPoints[] = $entry['points'];
	}	
}

function GetExpectedPointsDiffAgainstTeam($gid, $opponent)
{
}

function GetExpectedPointsDiffPerLocation($gid, $homeAway)
{
}

?>