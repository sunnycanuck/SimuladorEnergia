<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

	// Load the Visualization API and the piechart package.
	google.load('visualization', '1.0', {'packages':['corechart']});
	
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(drawChart);
	
	// Callback that creates and populates a data table, 
	// instantiates the pie chart, passes in the data and
	// draws it.
	function drawChart() {

	// Create the data table.
	var data = new google.visualization.DataTable();
			data.addColumn('string', 'Fecha');
			data.addColumn('number', 'Consumo en kWh');
			<?php
				//$table = $_REQUEST['tid'];
				$table = "ce_cfe_consumohistorico_".$_REQUEST['tid']."t";
				extract(mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total FROM ".$table."")));					
			?>
			data.addRows(<?php echo $total;?>);
			<?php
				$row = mysql_query("SELECT ano, mes, dia, consumo FROM ".$table."");			
				$i=0;
				while($linea = mysql_fetch_array($row)){
					$fecha = $linea['dia'].'-'.$linea['mes'].'-'.$linea['ano'];
					$date = date("M-Y", strtotime($fecha));
					echo "data.setValue(".$i.", 0, '".$date."');"."\r";
					echo "data.setValue(".$i.", 1, ".$linea['consumo'].");"."\r";
					$i++;
				}
			?>
			
			
	// Set chart options
	var options = {
								 'title':'Historico CFE',
								 'width':940,
								 'height':500,
								 'backgroundColor':'#FFF',
								 'pointSize': 10,
								 'lineWidth': 3,
								 'fontSize': 10
								};

	// Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.LineChart(document.getElementById('grafica'));
	chart.draw(data, options);
}
</script>
<div><a href="javascript: history.go(-1)">Regresar</a></div>
<div id="grafica"></div>