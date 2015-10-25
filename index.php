<?php
	require_once('processes/connect.php')
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>DFS AI</title>
    
    <link rel="stylesheet" type="text/css" href="css/index.css"/>
    <link rel="stylesheet" href="lib/font-awesome/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Ubuntu:400,500,700' rel='stylesheet' type='text/css'/>
</head>

<body>
	<div id="site">
    	<div id="main_menu">
        </div>
        <div id="main_content">
        	<div id="main_search">
            	<a href="#menu" id="main_menu_toggle"><i class="fa fa-bars"></i></a>
                <div id="search_bar">
                	<input id="search_bar_input" name="search_input" type="text" placeholder="Search Fantasy Player"/>
                </div>
            </div>
            <div id="main_profile">
            	<div id="profile_primary_stats">
                    <div id="player_name">
                    	Tom Brady
                    </div>
                    <div id="player_number">
                    	#12
                    </div>
                    <div id="player_position">
                    	QB
                    </div>
                    <div id="player_simple_score">
                    	7.12
                    </div>
               	</div>
                <div id="profile_secondary_stats">
                </div>
            </div>
            <div id="fantasy_stats">
            	<div id="fantasy_score_chart"></div>
            </div>
            <div id="real_stats">
            </div>
            <div id="schedule">
            </div>
        </div>
    </div>
</body>

<script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="js/index.js"></script>

</html>

