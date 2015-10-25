function getPlayerData(gid, name)
{
	request = $.ajax({
				url: "ajax/getPlayerWeeklyFantasyPoints.php",
				type: "POST",
				dataType: "json",
				data: {"gid" : gid}
			});
			
	request.done(function (response, textStatus, jqXHR) {
	data = getRecentData(response);
	makeChart(data, name);
	});
	
	request.fail(function (jqXHR, textStatus, errorThrown){
		console.error(
			"The following error occured: "+
			textStatus, errorThrown
		);
	});
}


function getRecentData(allData)
{
	var first;

	if(allData.length < 12)
		first = allData.length;
	else
		first = allData.length - 12;
	return allData.slice(first,allData.length);
}

function makeChart(data, name)
{
	var titleString = "Points Scored by " + name + " by Week";
	$('#fantasy_score_chart').highcharts({
		chart: {
			type: 'column'
		},
		title: {
			text: titleString
		},
		xAxis: {
			type: 'category',
			title: {
				text: 'Year, Week'
			}
		},
		yAxis: {
			title: {
				text: 'Points'
			}
		},
		legend: {
			enabled: false
		},
		plotOptions: {
			series: {
				borderWidth: 0,
				dataLabels: {
					enabled: true,
					format: '{point.y}'
				}
			}
		},

		tooltip: {
			headerFormat: '{series.name}: <br>',
			pointFormat: '{point.y}'
		},

		series: [{
			name: "Weekly Points",
			colorByPoint: false,
			data: getChartSeries(data)
		}]

	});
}

function getChartSeries(data)
{
	var array = [];
	for(i=0; i < data.length; i++)
	{
		weekStr = data[i][0] + ", " + data[i][1];
		dataObj = {
			name:  weekStr, 
			y: parseInt(data[i][2])
		};
		array.push (dataObj);
	}
	return array;
}