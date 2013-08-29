<html>
<head>
<?php
/*
   Desarrollador: Héctor Mora.
   Fecha: 04 de Febrero de 2012
   Descripción del Programa: Calcula las predicciones tarifarias usando un histórico
   Genera la tabla ce_SIMtarifas_XX donde XX es la tarifa recibida.
   El número de registros deberá ser el mismo que la tabla horasDelMes.
   El número de columnas deberá ser el mismo que la tabla ce_tarifas_XX

   La prediccion será:
	x = valor del
	    ultimo año + [ ( año en   -  Ultimo año ) *  (2*STDEV( todos los valores) / ( Ultimo año - primer año ) ]
	    capturado        cuestion    capturado                 capturados             capturado    capturado
  ------------------------------------------------------------------------------------------------------------
  MODIFICACIONES:
  ------------------------------------------------------------------------------------------------------------
  Clave: HMN01
  Autor: Héctor Mora
  Descripción: La desviación estándar se calcula en base a todos los valores del año, y no por mes.
  Fecha: 22-Noviembre-2012
  ------------------------------------------------------------------------------------------------------------

*/

  include("tarifa.php");       // Clase Tarifa para estructurar los registros en memoria
  include("simulador_bd.php"); // Gestión con la base de datos
  include("utilerias.php");    // Herramientas para operaciones.

/////////////////////// Recibe como parámetro la tarifa a procesar ///////////////
if( isset( $_GET["tarifa"] ) ) {
  $TIPO_TARIFA = $_GET["tarifa"];
} else {

  if( isset( $_POST["tarifa"] ) ) { $TIPO_TARIFA = $_POST["tarifa"]; }
  else { $TIPO_TARIFA = "1"; }
}
///////////////////////////////////////////////////////////////////////////////////////


if( $conexion = abre_conexion_servidor() ) { // Conectando al servidor de bases de datos

	if( selecciona_base_datos() ) { // Seleccionando base de datos

		///  HACE SELECT A LA TABLA DE LA TARIFA Y LLENA UN ARREGLO CON TODOS LOS REGISTROS INDEXADO POR MES ///
		$todos_los_meses_capturados = lee_tabla_tarifas( $conexion, $TIPO_TARIFA );
		////////////////////////////////////////////////////////////////////////////////////////////////////////

        ///////// EXTRAE UN REGISTRO DEL ARREGLO, PARA SABER LOS NOMBRES DE LAS COLUMNAS DE LA TABLA LEIDA /////////////
		$nombres_de_las_columnas = getDatosTabla( $todos_los_meses_capturados );
		$tarifas_capturadas_todas= lee_tarifas_todas( $conexion, $TIPO_TARIFA, $nombres_de_las_columnas ); //HMN01
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		////////////////////////// CREA NUEVA TABLA ACORDE AL TIPO DE TARIFA ///////////////////////////////
		borra_tabla_SIM( $TIPO_TARIFA );                                     // Elimina la tabla anterior (si existia)
		crea_tabla_SIM( $conexion, $TIPO_TARIFA, $nombres_de_las_columnas ); // Crea tabla acorde al tipo de tarifa
		////////////////////////////////////////////////////////////////////////////////////////////////////

		//////////////////////////// OBTIENE LA CANTIDAD DE REGISTROS QUE SE VAN A CREAR ///////////////////////
		$horasDelMes = get_horasDelMes( $conexion );       // CONSULTA AL ARCHIVO horasDelMes
		////////////////////////////////////////////////////////////////////////////////////////////////////////

		/////////////// CREA ESTRUCTURA DE LA TABLA SIM, Y ADEMAS REGRESA ARREGLO INDEXADO POR MES ///////////////
		$todos_los_meses_a_predecir  = genera_registros_SIM( $horasDelMes, $TIPO_TARIFA );
		////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$valores_columnas = array();
		$total_columnas   = count( $nombres_de_las_columnas );

		/////////////////////////////////HMN01 CALCULO DE LAS DESVIACIONES ESTANDAR ////////////////////////////////////
		$desviaciones_est = Array();
		for( $i = 0; $i < $total_columnas; $i ++ ) {
			$desviaciones_est[$i] = getDesviacion_estandar( $tarifas_capturadas_todas[ $i ], getMedia_aritmetica( $tarifas_capturadas_todas[$i] ) );
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		////////// ESTE CICLO RECORRERA TODOS LOS MESES, PREDICIENDO LOS VALORES PARA TODOS LOS AÑOS ///////////
        for( $mes = 1; $mes <=12; $mes ++ ) { //<---------- Modificado

			$tarifas_capturadas_solo_1_mes = $todos_los_meses_capturados[ $mes ]; // Lista filtrada de tarifas capturadas por el mes a predecir
			$tarifas_a_predecir_solo_1_mes = $todos_los_meses_a_predecir[ $mes ]; // Lista filtrada por el mes a predecir

			$primer_anyo_capturado         = $tarifas_capturadas_solo_1_mes[0]->getAnyo();
			$ultimo_anyo_capturado         = $tarifas_capturadas_solo_1_mes[ count($tarifas_capturadas_solo_1_mes)-1 ]->getAnyo();

			///////////// ESTE CICLO RECORRERA TODOS LOS AÑOS DE CADA MES QUE SE QUIERA PREDECIR /////////////////
			for( $i = 0; $i < count ($tarifas_a_predecir_solo_1_mes) ; $i ++ ) {
				$anyo_a_predecir = $tarifas_a_predecir_solo_1_mes[$i];
				unset( $valores_columnas );

				////////////// ESTE CICLO RECORRERA POR CADA TIPO DE CONSUMO (BasicoBajo, Alto, etc... ) /////////////////////
				for( $j = 0; $j < $total_columnas; $j ++ ) {
						$columna_tarifa = $nombres_de_las_columnas[$j];

						$valor_ultimo_anyo_capturado = $tarifas_capturadas_solo_1_mes[ count($tarifas_capturadas_solo_1_mes)-1]->getTarifa( $columna_tarifa );

						// HMN01 $desviacion_estandar = STDEV( $tarifas_capturadas_solo_1_mes, $columna_tarifa );
						$desviacion_estandar  = $desviaciones_est[$j];// HMN01

						$valores_columnas[$j] = $valor_ultimo_anyo_capturado +
						                        ( ($anyo_a_predecir-$ultimo_anyo_capturado) * (2*$desviacion_estandar)/($ultimo_anyo_capturado-$primer_anyo_capturado) );
				}

				actualizaSIM( $TIPO_TARIFA, $anyo_a_predecir, $mes, $nombres_de_las_columnas, $valores_columnas );

			} // Fin de For por año


        } // fin de for por mes

///////////////////////////////////////// GRAFICAR /////////////////////////////////////////////////

?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'fecha');
		        <?php

					for( $i = count ($nombres_de_las_columnas)-1; $i >=0 ; $i -- ) {
        				echo "		        data.addColumn('number', '". $nombres_de_las_columnas[$i] ."');\n";
       				 }

					 $salida = "data.addRows([\n";

					///// SE AGREGAN LOS DATOS YA ESTABLECIDOS
					$resultado = mysql_query( "SELECT * FROM ce_tarifas_" . $TIPO_TARIFA, $conexion );

					 if( $resultado ) {
					 	while( $registro = mysql_fetch_array( $resultado ) ) {
					 		$salida.= "		          ['".$registro[ "fecha"]."', ". $registro[ $nombres_de_las_columnas[count ($nombres_de_las_columnas)-1] ];
					 		for( $i = count ($nombres_de_las_columnas)-2; $i >= 0; $i -- ) {
								$salida.= "," . $registro[ $nombres_de_las_columnas[$i] ];
							}
							$salida.= "],\n";
					 	}



					 	mysql_free_result( $resultado );

					 }


					 /// SE AGREGAN LOS DATOS DE LA PREDICCION
					 $resultado = mysql_query( "SELECT * FROM ce_SIMtarifas_" . $TIPO_TARIFA, $conexion );
					  if( $resultado ) {

					  		while( $registro = mysql_fetch_array( $resultado ) ) {

								$salida.= "		          ['".$registro[ "fecha"]."', ". $registro[ $nombres_de_las_columnas[count ($nombres_de_las_columnas)-1] ];


													for( $i = count ($nombres_de_las_columnas)-2; $i >=0; $i -- ) {
														$salida.= "," . $registro[ $nombres_de_las_columnas[$i] ];
													}

													$salida.= "],\n";
							}

							$salida = substr( $salida, 0, strlen( $salida) -2 ). "\n";
							mysql_free_result( $resultado );
							echo $salida;

					  }



		          ?>
        ]);


		var options = {
          width: 1000, height: 1000,
          title: 'Tarifas Simuladas'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>


<?php

	} // Fin if selecciona_base_datos


  	cierra_conexion($conexion);
} else {
	imprime_error_conexion_servidor();
} // Fin else

?>

</head>
<body>
v1.1.4
<div id="chart_div"></div>
</body>
</html>