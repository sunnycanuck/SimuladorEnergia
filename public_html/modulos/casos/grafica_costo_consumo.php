<?php
	$tabla = "ce_costodeconsumo_".$_REQUEST['tid']."t";
	$result = mysql_query("SELECT * FROM `$tabla`;");
	$fields_num = mysql_num_fields($result);
	for($i=0; $i<$fields_num; $i++)
	{
		$field = mysql_fetch_field($result);
		$campos[] = $field->name;
	}
	$cuantos = count($campos);
	$columnas = "";
	$campos2 = "";
	for($j=3;$j<=$cuantos;$j++){
		$x = $j-2;
		$columnas.= $campos[$j];
		if($j<$cuantos){
			$columnas.=", ";
			$campos2 .="'Caso ".$x."',";
		}
	}
	$campos2 = substr($campos2, 0, -1);
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

// Load the Visualization API and the piechart package.
google.load('visualization', '1.0', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawChart);
function drawChart() {
		var data = google.visualization.arrayToDataTable([
			['Fecha', <?php echo $campos2; ?>],
			<?php
				extract(mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total FROM `$tabla`")));
				//$total = $total - 1;
				$qry = mysql_query("SELECT ".$columnas."anyo  FROM `$tabla` ORDER BY id ASC;");	
				$l = 1;
				while($row = mysql_fetch_array($qry)){
					echo "['".$row['anyo']."',";
					$peticion = "";
					for($k=3;$k<$cuantos;$k++){
						$peticion .= $row[$campos[$k]].",";
														
					}// for
					$peticion = substr($peticion, 0, -1); 
					echo $peticion;					
					if($l<$total)
						echo "],"."\r";
					else
						echo "]"."\r";
					
					$l++;
				}//while
			?>
			
		]);

		var options = {
								 'width':940,
								 'height':500,
								 'backgroundColor':'none',
								 'pointSize': 5,
								 'lineWidth': 3,
								 'fontSize': 10,
								 'title': 'Costo de Consumo',
								 'animation.easing': 'in',
								 'animation.duration': 5000,
								 'legend.position': 'bottom'
								};

		var chart = new google.visualization.LineChart(document.getElementById('grafica'));
		chart.draw(data, options);
	}


</script>
<div><a href="javascript: history.go(-1)">Regresar</a></div>
<div id="grafica"></div>