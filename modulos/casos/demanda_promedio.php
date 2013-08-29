<?php
$anyo_inicio = date("Y");
$tid = $_REQUEST['tid'];

include_once( "demanda_promedio_fn.php");
include_once("medidor.php");

demanda_promedio($tid);
medidor($tid, 1, $anyo_inicio );

?>
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
			data.addColumn('number', 'Predicción de cosumo: Caso 1');
			<?php
				$tabla_medidor = "ce_medidorCFE_".$tid."t1c";
				$ano_actual = date("Y");
				extract(mysql_fetch_array(mysql_query("SELECT COUNT(*) AS todo FROM ".$tabla_medidor." WHERE anyo = $ano_actual AND consumo<>0;")));
				extract(mysql_fetch_array(mysql_query("SELECT SUM(consumo) AS demanda_total FROM ".$tabla_medidor." WHERE anyo = $ano_actual AND consumo<>0;")));
				$promedio = $demanda_total/$todo;
			?>
			data.addRows(12);
			<?php
				$query = mysql_query("SELECT mes, anyo, consumo FROM `".$tabla_medidor."` WHERE anyo = $ano_actual ORDER BY anyo ASC;");

				$i=0;
				while($row = mysql_fetch_array($query)){
					//echo  $row['consumo'].'<br>';
					if($row['consumo']==0){
						echo "data.setValue(".$i.", 0, '".$row['mes']."-".$row['anyo']."');"."\r";
						echo "data.setValue(".$i.", 1, ".$promedio.");"."\r";
					}else{
						echo "data.setValue(".$i.", 0, '".$row['mes']."-".$row['anyo']."');"."\r";
						echo "data.setValue(".$i.", 1, ".$row['consumo'].");"."\r";
					}
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
<?php
/*If recibo every 2 months
	The value is for the month marked and the month before */


	/*$mes_anterior = $mes - 1;
	echo $mes_anterior.'<br>';

	if(extract(mysql_fetch_array(mysql_query("SELECT COUNT(mes) AS meses FROM ce_cfe_consumohistorico_prueba1 WHERE mes = $mes;")))){
		extract(mysql_fetch_array(mysql_query("SELECT SUM(consumo) AS total FROM ce_cfe_consumohistorico_prueba1 WHERE mes = $mes")));
	}else{
		continue;
	}


	extract(mysql_fetch_array(mysql_query("SELECT num_horas AS horas_actual FROM ce_horasdelmes WHERE mes = $mes AND ano=2010;")));
	extract(mysql_fetch_array(mysql_query("SELECT num_horas AS horas_anterior FROM ce_horasdelmes WHERE mes = $mes_anterior AND ano=2010;")));

	//extract(mysql_fetch_array(mysql_query("SELECT SUM()")));
	$total_horas = $horas_actual + $horas_anterior;
	echo $total_horas.'<br>';
	echo $consumo_promedio = $total / $meses.'<br>';
	$demanda = $consumo_promedio / $total_horas;
	echo $demanda."<br>";*/

/*$mes=1;
while($mes<=12){
$horas="";//horas de ese mes
if() {//si no existen datos del mes en ese momento del ciclo
  Mes=Mes+1;
  Hours=Hours+ ;// horas de ese mes
}
else{
  Hours=Hours+ ;//horas del mes anterior
}
EngAvg=el consumo promedio para cada mes incluido
DemandaProm=EngAvg/Hours;
//si hay varios anios, encuentra la DemandaProm para cada anio, y despues el promedio de esos valores.
// grabar este valor para el mes actual y el mes anterior en ce_demanadaPromedio
Mes=Mes+1;
}

//si no hay un historial completo de recibos, copiar el ultimo valor y llenar todos con el mismo valor.*/

?>