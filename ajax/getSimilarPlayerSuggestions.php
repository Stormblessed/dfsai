<?php
require_once('../processes/connect.php');
$searchName = $_REQUEST['search_name'];

$playersTable = 'players';
$data = mysql_query(
			"SELECT 
				gid, name, position
			 FROM 
				$playersTable
			 WHERE name LIKE '$searchName%'") or die(mysql_error());

$players = array();		
while($entry = mysql_fetch_array($data))
{
	$player = array();
	$player['gid'] = $entry['gid'];
	$player['name'] = $entry['name'];
	$player['position'] = $entry['position'];
	$players[] = $player;
}

for($i = 0; $i < sizeof($players); $i++)
{		
	echo '<a class="player_suggestion_item" href="#" data-name="'.$players[$i]['name'].'" data-gid="'.$players[$i]['gid'].'">'.$players[$i]['name'].'</a>';
}
?>