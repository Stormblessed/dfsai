<?php
	ini_set('max_execution_time', 4800);
	require_once('connect.php');
	
	$fantasyStatsTable = 'player_entries';
	$data = mysql_query(
				"SELECT 
					gid
				 FROM 
				 	$fantasyStatsTable") or die(mysql_error());
	
	$gids = array();		
	while($player = mysql_fetch_array($data))
	{
		$gids[] = $player['gid'];
	}
	array_unique($gids);
	
	for($i = 0; $i < sizeof($gids); $i++)
	{
		ComputePlayerStats($gids[$i]);
	}
	
	function ComputePlayerStats($gid)
	{
		$CurrentYear = '2015';
		$CurrentWeek = '6';
		
		$fantasyStatsTable = 'player_entries';
		$historicPlayerData = mysql_query("SELECT * FROM $fantasyStatsTable WHERE gid='$gid' AND year<='$CurrentYear' AND week<'$CurrentWeek'");
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
		$averagePoints = $totalPoints / $totalWeeks;
		
		$totalVariance = 0;
		for($i = 0; $i < sizeof($weeklyPoints); $i++)
		{
			$totalVariance += $weeklyPoints[$i] - $averagePoints;
		}
		
		if($totalWeeks <= 1 || $totalWeeks == NULL) $avgVariance = 0;
		else $avgVariance = $totalVariance / ($totalWeeks - 1);
		$stdDev = sqrt($avgVariance);
		
		$currentPlayer = mysql_fetch_array($currentPlayerData);
		$currentSalary = $currentPlayer['salary'];
		if($currentSalary <= 0 || $currentSalary == NULL) $simpleScore = 0;
		else $simpleScore = $averagePoints / $currentSalary;
		
		$gid = $currentPlayer['gid'];
		$name = $currentPlayer['name'];
		$position = $currentPlayer['position'];
		$team = $currentPlayer['team'];
		
		$playersTable = 'players';
		mysql_query("INSERT INTO $playersTable (gid, name, number, position, team, averagePoints, stdDevPoints, simpleScore) 
					 VALUES ('$gid', '$name', 'N/A', '$position', '$team', '$averagePoints', '$stdDev', '$simpleScore')
					 ON DUPLICATE KEY UPDATE 
					 	averagePoints = '$averagePoints',
						stdDevPoints = '$stdDev',
						simpleScore = '$simpleScore'");
		echo "Computed ".$currentPlayer['name']." stats. <br/>";
	}
?>