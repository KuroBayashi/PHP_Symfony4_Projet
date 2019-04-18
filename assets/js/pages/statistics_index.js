'use strict';

require('../../scss/pages/statistics_index.scss');
const $ = require('jquery');

	google.charts.load('current', {packages: ['corechart', 'bar']});
	google.charts.setOnLoadCallback(getUtilNb);

	
	function getUtilNb(){
	
		$.getJSON("/api/utilization/nb_util", (data) =>
		{
	
			drawTrendLines(data);
			drawPieHole(data);


		});
	}
	function drawTrendlines(data) {
		var data = new google.visualization.DataTable();
		data.addColumn('number', "Identifiant");
		data.addColumn('number', "Nombre d'utilisations");


		data.addRows(data);

		var options = {
			title: "Utilisation des defibrillators",
			hAxis: {
				title: 'Defibrillateurs'
			},

			vAxis: {
				title: "Nombre d'utilisation"
			},

		};
		var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
		chart.draw(data, options);


	}

function drawPieHole(data) {
		var data = new google.visualization.DataTable();
		data.addColumn('number', "Identifiant");
		data.addColumn('number', "Nombre d'utilisations");


		data.addRows(data);

		var options = {
			pieHole: 0.5,
			title: "Utilisation des defibrillators",
			hAxis: {
				title: 'Defibrillateurs'
			},

			vAxis: {
				title: "Nombre d'utilisation"
			},

		};
		var chart = new google.visualization.PieChart(document.getElementById('donut_single'));
		chart.draw(data, options);


	}

