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
			data.addColumn('string', 'Mes');
			data.addColumn('number', 'Predicción de consumo: Caso 1');
			data.addColumn('number', 'Predicción de consumo: Caso <?php echo $_REQUEST['cid']; ?>');			
			<?php
				$ano_actual = date("Y");
				$tablaCaso1 = "ce_medidorCFE_".$_REQUEST['tid']."t1c";
				$tabla_medidor = "ce_medidorCFE_".$_REQUEST['tid']."t".$_REQUEST['cid']."c";
				extract(mysql_fetch_array(mysql_query("SELECT COUNT(*) AS todo FROM ".$tabla_medidor." WHERE anyo = $ano_actual;")));					
			?>
			data.addRows(<?php echo $todo;?>);
			<?php
				//$query = mysql_query("SELECT mes, anyo, consumo FROM `".$tabla_medidor."` WHERE anyo = $ano_actual ORDER BY id ASC;");
				//$query = mysql_query("SELECT * FROM ".$tabla_medidor." INNER JOIN ".$tablaCaso1." WHERE ".$tabla_medidor.".anyo = ".$ano_actual." AND ".$tablaCaso1.".id = ".$tabla_medidor.".id;");
				$query = mysql_query("
					SELECT ".$tablaCaso1.".consumo AS consumo1, ".$tabla_medidor.".consumo AS consumo2, ".$tablaCaso1.".mes, ".$tablaCaso1.".anyo 
					FROM ".$tabla_medidor."
					INNER JOIN ".$tablaCaso1."
					WHERE ".$tabla_medidor.".anyo = $ano_actual AND ".$tablaCaso1.".id = ".$tabla_medidor.".id
				");
				$i=0;
				while($row = mysql_fetch_array($query)){
					echo "data.setValue(".$i.", 0, '".$row['mes']."-".$row['anyo']."');"."\r";
					echo "data.setValue(".$i.", 1, ".$row['consumo1'].");"."\r";
					echo "data.setValue(".$i.", 2, ".$row['consumo2'].");"."\r";
					$i++;
				}
			?>
			
			
	// Set chart options
	var options = {
								 'width':940,
								 'height':500,
								 'backgroundColor':'none',
								 'pointSize': 10,
								 'lineWidth': 3,
								 'fontSize': 10,
								 'title': 'Consumo de energía eléctrica',
								 'animation.easing': 'in',
								 'animation.duration': 5000,
								 'legend.position': 'bottom',
								 'vAxis': {title: 'kWh/mes', fontSize:14},
								 'hAxis': {title: 'Fecha', fontSize:14}
								};

	// Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.LineChart(document.getElementById('grafica'));
	chart.draw(data, options);
}
</script>
<div><a href="javascript: history.go(-1)">Regresar</a></div>
<div id="grafica"></div>
