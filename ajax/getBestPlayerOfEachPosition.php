<?php
require_once('../processes/connect.php');

$playersTable = 'players';
$data = mysql_query(
			"SELECT 
				name, position, team, weeks, averagePoints, stdDevPoints, simpleScore
			 FROM 
				$playersTable") or die(mysql_error());

$year = 2015;
$week = 6;

echo GetExpectedPoints($gid, $year, $week);

function GetExpectedPoints($gid, $year, $week)
{
	$CurrentYear = $year;
	$CurrentWeek = $week;
	
	$fantasyStatsTable = 'player_entries';
	$currentPlayerData = mysql_query("SELECT * FROM $fantasyStatsTable WHERE gid='$gid' AND year='$CurrentYear' AND week='$CurrentWeek'");
	
	$currentPlayer = mysql_fetch_array($currentPlayerData);
	
	$playersTable = 'players';
	$data = mysql_query(
			"SELECT 
				name, position, team, weeks, averagePoints, stdDevPoints, simpleScore
			 FROM 
				$playersTable
			 WHERE gid='$gid'") or die(mysql_error());
	
	$player = mysql_fetch_array($data);
	
	$expectedDiffPerTeam = GetExpectedPointsDiffAgainstTeam($gid, $player['averagePoints'], $currentPlayer['opponent'], $CurrentYear, $CurrentWeek);
	$expectedDiffPerLocation = GetExpectedPointsDiffPerLocation($gid, $player['averagePoints'], $currentPlayer['home_away'], $CurrentYear, $CurrentWeek);
	
	return $player['averagePoints'] + $expectedDiffPerTeam + $expectedDiffPerLocation;
}

function GetExpectedPointsDiffAgainstTeam($gid, $avgPoints, $opponent, $year, $week)
{
	$CurrentYear = $year;
	$CurrentWeek = $week;
	
	$fantasyStatsTable = 'player_entries';
	$historicPlayerData = mysql_query("SELECT 
										points 
									   FROM $fantasyStatsTable 
									   WHERE 
									   (gid='$gid' AND opponent='$opponent' AND year<'$CurrentYear') 
									   OR (gid='$gid' AND opponent='$opponent' AND year='$CurrentYear' AND week<'$CurrentWeek')");
	
	$totalPoints = 0;
	$totalWeeks = 0;
	$weeklyPoints = array();
	while($entry = mysql_fetch_array($historicPlayerData))
	{
		$totalWeeks++;
		$totalPoints += $entry['points'];
		$weeklyPoints[] = $entry['points'];
		
	}
	$averagePointsAgainstTeam = $totalPoints / $totalWeeks;
	return $averagePointsAgainstTeam - $avgPoints;
}

function GetExpectedPointsDiffPerLocation($gid, $avgPoints, $homeAway, $year, $week)
{
	$CurrentYear = $year;
	$CurrentWeek = $week;
	
	$fantasyStatsTable = 'player_entries';
	$historicPlayerData = mysql_query("SELECT 
										points 
									   FROM $fantasyStatsTable 
									   WHERE 
									   (gid='$gid' AND home_away='$homeAway' AND year<'$CurrentYear') 
									   OR (gid='$gid' AND home_away='$homeAway' AND year='$CurrentYear' AND week<'$CurrentWeek')");
									   
	$totalPoints = 0;
	$totalWeeks = 0;
	$weeklyPoints = array();
	while($entry = mysql_fetch_array($historicPlayerData))
	{
		$totalWeeks++;
		$totalPoints += $entry['points'];
		$weeklyPoints[] = $entry['points'];
		
	}
	$averagePointsInLocation = $totalPoints / $totalWeeks;
	return $averagePointsInLocation - $avgPoints;
}

?>