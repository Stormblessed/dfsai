<?php
	ini_set('max_execution_time', 4800);
	error_reporting(E_ALL);
	include_once('../lib/simplehtmldom/simple_html_dom.php');
	require_once('connect.php');
	
	echo "Data is being scraped from Weekly Football Points, but will eventually be directly pulled from a Fan Duel Kimono API <br/> <br/>";
	
	$baseUrl = 'http://rotoguru1.com/cgi-bin/fyday.pl?';
	$urlFooter = '&game=fd&scsv=1';
	
	$years = array('2011', '2012', '2013', '2014', '2015');
	
	foreach($years as $year)
	{
		for($i = 1; $i <= 17; $i++)
		{
			if($year == '2015' && $i > 6) break;
			ScrapeStats($baseUrl, $urlFooter, $i, $year);
		}
	}
	
	function ScrapeStats($baseUrl, $urlFooter, $week, $year)
	{
		$html = file_get_html($baseUrl . "&week=" . $week . "&year=" . $year . $urlFooter);
		
		foreach($html->find('pre') as $element)
		{
			$cleanedElement = str_replace("Week;Year;GID;Name;Pos;Team;h/a;Oppt;FD points;FD salary", "", $element->innertext);
			$cleanedElement = str_replace("0 ", "0;", $cleanedElement);
			$cleanedElement = str_replace("; ", "; ;", $cleanedElement);
			$cleanedElement = str_replace("'", "\\'", $cleanedElement);
			$items = explode(";", $cleanedElement);
			$lines = array();
			$line = array();
			for($i = 0; $i < sizeof($items); $i++)
			{
				$line[] = $items[$i];
				if(sizeof($line) >= 10)
				{
					$lines[] = $line;
					$line = array();
				}
			}
			
			$fantasyStatsTable = 'player_entries';
			foreach($lines as $singleLine)
			{
				if($singleLine[9] == '') $singleLine[9] = 'N\A';
				mysql_query("INSERT INTO $fantasyStatsTable 
								 	(week, 
								 	 year,
								 	 gid, 
								 	 name,
									 position,
									 team,
									 home_away,
									 opponent,
									 points,
									 salary,
									 draft)
								 VALUES 
								   ('$singleLine[0]', 
									'$singleLine[1]', 
									'$singleLine[2]', 
									'$singleLine[3]',
									'$singleLine[4]', 
									'$singleLine[5]', 
									'$singleLine[6]', 
									'$singleLine[7]',
									'$singleLine[8]', 
									'$singleLine[9]',
									'fd')") or die("Error in query : ".mysql_error());
			}
			echo "Inserted data from ".$year.", week ".$week."<br/>";
		}
	}
?>