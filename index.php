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
            	<div id="menu_toggle_wrapper">
            		<a href="#menu" id="main_menu_toggle"><i class="fa fa-bars"></i></a>
                </div>
                <div id="search_bar">
                	<input id="search_bar_input" name="search_input" type="text" placeholder="Search Fantasy Players"/>
                </div>
                <div id="search_bar_suggestions"></div>
            </div>
            <div id="logo_container">
            	<div id="logo">
                	<span id="logo_start">dfs</span><span id="logo_end">Buddy</span>
                </div>
            </div>
            <div id="content_blocks">
                <div class="content_block" id="main_profile">
                	<div class="content_block_header">Fantasy Player Profile</div>
                    <div class="content_block_main">
                        <div id="profile_primary_stats">
                            <div id="player_team_helmet">
                                
                            </div>
                            <div id="player_name">
                                
                            </div>
                            <div id="player_position">
                                
                            </div>
                            <div id="player_simple_score" class="player_stat">
                            	<div class="player_stat_name">SS</div>
                                <div class="player_stat_data"></div>
                            </div>
                            <div id="player_std_dev" class="player_stat">
                            	<div class="player_stat_name">SD</div>
                                <div class="player_stat_data"></div>   
                            </div>
                            <div id="player_avg_points" class="player_stat">
                            	<div class="player_stat_name">AP</div>
                                <div class="player_stat_data"></div>
                            </div>
                        </div>
                        <div id="profile_secondary_stats">
                        </div>
                    </div>
                </div>
                <div class="content_block" id="fantasy_stats">
                	<div class="content_block_header">Fantasy Player Fantasy Stats</div>
                    <div class="content_block_main">
                    	<div id="fantasy_score_chart"></div>
                    </div>
                </div>
                <div class="content_block" id="real_stats">
                    <div class="content_block_header">Fantasy Player Real Stats</div>
                        <div class="content_block_main">
                            <div id="fantasy_score_chart"></div>
                        </div>
                    </div>
                <div class="content_block" id="schedule">
                	<div class="content_block_header">Fantasy Player Schedule</div>
                    <div class="content_block_main">
                    	<div id="fantasy_score_chart"></div>
                    </div>
                </div>
        	</div>
        </div>
    </div>
</body>

<script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="//code.highcharts.com/highcharts.js"></script>
<script type="text/javascript" src="js/index.js"></script>

</html>

