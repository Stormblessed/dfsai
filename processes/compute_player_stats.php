<?php
	require_once('connect.php');
	
	$fantasyStatsTable = 'player_entries';
	$data = mysql_query(
				"SELECT 
					* 
				 FROM 
				 	$fantasyStatsTable") or die(mysql_error());
?>