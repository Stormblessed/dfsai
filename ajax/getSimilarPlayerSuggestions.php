<?php
require_once('../processes/connect.php');
$searchName = $_REQUEST['search_name'];
$splitName = explode(" ", $searchName);

$reorderedName = "";
for($i = sizeof($splitName)-1; $i >= 0; $i--)
{
	if($i != 0) $reorderedName .= $splitName[$i] . ", ";
	else $reorderedName .= $splitName[$i];
}

	
$playersTable = 'players';
$data = mysql_query(
			"SELECT 
				gid, name, position
			 FROM 
				$playersTable
			 WHERE name LIKE '$reorderedName'") or die(mysql_error());

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
	$splitName = explode(", ", $players[$i]['name']);
	$reorderedName = "";
	for($j = sizeof($splitName)-1; $j >= 0; $j--)
	{
		$reorderedName .= $splitName[$j] . " ";
	}
		
	echo '<a class="player_suggestion_item" href="#" data-name="'.$reorderedName.'" data-gid="'.$players[$i]['gid'].'">'.$reorderedName.'</a>';
}
?>