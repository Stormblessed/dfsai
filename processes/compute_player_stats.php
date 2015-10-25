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
	$uniqueGids = array_unique($gids);
	
	foreach($uniqueGids as $uniqueGid)
	{
		echo $uniqueGid . "<br/>";
		ComputePlayerStats($uniqueGid);
	}
	
	function ComputePlayerStats($gid)
	{
		$CurrentYear = 2015;
		$CurrentWeek = 6;
		
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
		$averagePoints = $totalPoints / $totalWeeks;
		
		$totalVariance = 0;
		for($i = 0; $i < sizeof($weeklyPoints); $i++)
		{
			$totalVariance += ($weeklyPoints[$i] - $averagePoints) * ($weeklyPoints[$i] - $averagePoints);
		}
		
		if($totalWeeks <= 1 || $totalWeeks == NULL) $avgVariance = 0;
		else $avgVariance = $totalVariance / ($totalWeeks - 1);
		$stdDev = sqrt($avgVariance);
		
		$currentPlayer = mysql_fetch_array($currentPlayerData);
		$currentSalary = $currentPlayer['salary'];
		if($currentSalary <= 0 || $currentSalary == NULL) $simpleScore = 0;
		else $simpleScore = ($averagePoints / $currentSalary) * 1000;
		
		$gid = $currentPlayer['gid'];
		$name = $currentPlayer['name'];
		
		$splitName = explode(", ", $name);
		$reorderedName = "";
		for($j = sizeof($splitName)-1; $j >= 0; $j--)
		{
			if($j == 0) $reorderedName .= $splitName[$j];
			else $reorderedName .= $splitName[$j] . " ";
		}
		$name = $reorderedName;
		
		$position = $currentPlayer['position'];
		$team = $currentPlayer['team'];
		
		$playersTable = 'players';
		mysql_query("INSERT INTO $playersTable (gid, name, number, position, team, weeks, averagePoints, stdDevPoints, simpleScore) 
					 VALUES ('$gid', '$name', 'N/A', '$position', '$team', '$totalWeeks', '$averagePoints', '$stdDev', '$simpleScore')
					 ON DUPLICATE KEY UPDATE 
					 	name = '$name',
					 	weeks = '$totalWeeks',
					 	averagePoints = '$averagePoints',
						stdDevPoints = '$stdDev',
						simpleScore = '$simpleScore'");
		echo "Computed ".$name." stats. <br/>";
	}
?>