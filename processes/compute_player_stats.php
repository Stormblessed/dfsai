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
		$totalSimpleScore = 0;
		$totalWeeks = 0;
		$weeklyPoints = array();
		while($entry = mysql_fetch_array($historicPlayerData))
		{
			$totalWeeks++;
			$totalPoints += $entry['points'];
			$weeklyPoints[] = $entry['points'];
			
			if($entry['salary'] <= 0 || $entry['salary'] == NULL) $totalSimpleScore += 0;
			else $totalSimpleScore += ($entry['points'] / $entry['salary']) * 1000; 
		}
		if($totalWeeks > 0)
		{
			$averagePoints = $totalPoints / $totalWeeks;
			$averageSimpleScore = $totalSimpleScore / $totalWeeks;
		}
		else
		{
			$averagePoints = $totalPoints;
			$averageSimpleScore = $totalSimpleScore;
		}
		
		$totalVariance = 0;
		for($i = 0; $i < sizeof($weeklyPoints); $i++)
		{
			$totalVariance += ($weeklyPoints[$i] - $averagePoints) * ($weeklyPoints[$i] - $averagePoints);
		}
		
		if($totalWeeks <= 1 || $totalWeeks == NULL) $avgVariance = 0;
		else $avgVariance = $totalVariance / ($totalWeeks - 1);
		$stdDev = sqrt($avgVariance);
		
		$currentPlayer = mysql_fetch_array($currentPlayerData);
		
		/*$currentSalary = $currentPlayer['salary'];
		if($currentSalary <= 0 || $currentSalary == NULL) $simpleScore = 0;
		else $simpleScore = ($averagePoints / $currentSalary) * 1000;*/
		
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
		
		$expectedPoints = GetExpectedPoints($gid, $CurrentYear, $CurrentWeek);
		
		$currentSalary = $currentPlayer['salary'];
		if($currentSalary <= 0 || $currentSalary == NULL) $expectedSimpleScore = 0;
		else $expectedSimpleScore = ($expectedPoints / $currentSalary) * 1000;
		
		$playersTable = 'players';
		mysql_query("INSERT INTO $playersTable (gid, name, number, position, team, weeks, averagePoints, stdDevPoints, averageSimpleScore, currentExpectedPoints, currentExpectedSimpleScore) 
					 VALUES ('$gid', '$name', 'N/A', '$position', '$team', '$totalWeeks', '$averagePoints', '$stdDev', '$averageSimpleScore', '$expectedPoints', '$expectedSimpleScore')
					 ON DUPLICATE KEY UPDATE 
					 	name = '$name',
					 	weeks = '$totalWeeks',
					 	averagePoints = '$averagePoints',
						stdDevPoints = '$stdDev',
						averageSimpleScore = '$averageSimpleScore',
						currentExpectedPoints='$expectedPoints',
						currentExpectedSimpleScore='$expectedSimpleScore'");
		echo "Computed ".$name." stats. <br/>";
	}
	
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
					name, position, team, weeks, averagePoints, stdDevPoints, averageSimpleScore
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
		if($totalWeeks > 0) $averagePointsAgainstTeam = $totalPoints / $totalWeeks;
		else $averagePointsAgainstTeam = $avgPoints;
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
		if($totalWeeks > 0) $averagePointsInLocation = $totalPoints / $totalWeeks;
		else $averagePointsInLocation = $avgPoints;
		return $averagePointsInLocation - $avgPoints;
	}
	
	?>