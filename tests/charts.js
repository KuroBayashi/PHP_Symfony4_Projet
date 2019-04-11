
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawTrendlines);

var plop = [[0,2],[1,0],[2,6],[3,4],[4,2],[5,1],[6,0],[7,3],[8,7],[9,1],[10,4] ];

function drawTrendlines(){
	var data = new google.visualization.DataTable();
	data.addColumn('number', "Identifiant");
	data.addColumn('number', "Nombre d'utilisations");


	data.addRows(plop);

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