<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

 <script type="text/javascript">
		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawChart);

		function drawChart() {

			var data = google.visualization.arrayToDataTable([
					['Estado', 'Cantidad'],
					['ENVIADOS',	<?=$estadisticas->enviados?>],
					['EN COLA',		<?=$estadisticas->enviadoColas?>],
					['LISTA NEGRA',	<?=$estadisticas->blacklists?>],
					['FALLADOS',	<?=$estadisticas->fallados?>],
					['RECHAZADOS',	<?=$estadisticas->errorPendientes?>]
				]);

			var options = {
				title: 'Estado de la tarea',
				sliceVisibilityThreshold: 0
			};

			var chart = new google.visualization.PieChart(document.getElementById('piechart'));

			chart.draw(data, options);
		}
	</script>
<div id="piechart" style="width: 900px; height: 500px;"></div>