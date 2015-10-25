<?php
	require_once('connect.php');
	
	$CurrentYear = '2015';
	$CurrentWeek = '6';
	
	$fantasyStatsTable = 'player_entries';
	$data = mysql_query(
				"SELECT 
					gid
				 FROM 
				 	$fantasyStatsTable") or die(mysql_error());
	
	$gids = array();		
	while($player = mysql_fetch_array($data))
	{
		$gids = $player['gid'];
	}
	array_unique($gids);
	
	for($i = 0; $i < sizeof($gids); $i++)
	{
		ComputePlayerStats($gids[$i]);
	}
	
	function ComputePlayerStats($gid)
	{
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
		
		$avgVariance = $totalVariance / ($totalWeeks - 1);
		$stdDev = sqrt($avgVariance);
		
		$currentPlayer = mysql_fetch_array($currentPlayerData);
		$currentSalary = $currentPlayer['salary'];
		$simpleScore = $averagePoints / $currentSalary;
	}
?>