<?php

/*
------------------------------------------------------
MODIFICACIONES:
------------------------------------------------------
Clave: HMN01
Autor: Héctor Mora
Descripción: Se blindó el código para el mes 0
Fecha: 29/Noviembre/2012
-------------------------------------------------------
*/


  /*****Seccion de prueba *******
  require("../../conexion.php");

  demanda_promedio("237");
  mysql_close($conn);********/

function demanda_promedio( $tid ) {

				$nombre_tabla = "ce_demandapromedio_t".$tid."_c1";

				mysql_query("DROP TABLE IF EXISTS ". $nombre_tabla) or die("Error al borrar la tabla.");
				mysql_query(
						"CREATE TABLE ".$nombre_tabla."(
						 id INT PRIMARY KEY AUTO_INCREMENT,
						 mes INT,
						 demanda_promedio FLOAT,
						 demanda_pico_promedio FLOAT
						 );") or die("Error al crear la tabla.");




			$i = 1;
			$bimestral = False;
			$demanda = Array();

			/////////////////////////////////////////// REVISA SI ES MENSUAL o BIMESTRAL ////////////////////////////////////

			$query    = mysql_fetch_array( mysql_query("SELECT B.bimestral FROM ce_consumo A, ce_tarifas B WHERE A.secuencia = 'ce_cfe_consumohistorico_". $tid . "t' AND A.tipo = B.id_tarifa") );

			if( $query["bimestral"] == 1 ) {
				$bimestral = True;
			}
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			///////////////////////////////////// INICIALIZA SUMATORIAS ///////////////////////////////////////
			$suma_meses = 0;
			$cantidad_meses = 0;
			for( $i = 1; $i <= 12; $i ++ ) { $demanda[$i] = 0; }
			///////////////////////////////////////////////////////////////////////////////////////////////////


			////////////////////////////////// CALCULA CONSUMO PARA CADA MES ///////////////////////////////////////////////
			for( $i = 1; $i <= 12; $i ++ ) {

				if( $bimestral && (($i%2) == 0) ) {
					continue;
				}

				$demanda[$i] = obtenerConsumo( $tid, $i, $bimestral );

				if( $bimestral ) {

					if( $i == 1 ) {
						$demanda[ 12 ]     = $demanda[ $i]; // SI es enero, tambien diciembre toma este valor


					} else {
						$demanda[ $i - 1 ] = $demanda[ $i]; // Si no es enero, se pone el valor obtenido al mes anterior.
					}
				}

				$suma_meses += $demanda[$i];
				if( $demanda[$i] > 0 ) {
					$cantidad_meses ++;
				}
			}
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////

			/////////////////// REVISION TARIFA BIMESTRAL CON MESES PARES CAPTURADOS ///////////////////////////////////////

			if( $bimestral && $suma_meses == 0 ) {

				$cantidad_meses = 0;
				for( $i = 2; $i <= 12; $i ++ ) {

					if( (($i%2) != 0) ) {
						continue;
					}

					$demanda[$i] = obtenerConsumo( $tid, $i, $bimestral );
					$demanda[ $i - 1 ] = $demanda[ $i];

					$suma_meses += $demanda[$i];

					if( $demanda[$i] > 0 ) {
						$cantidad_meses ++;
					}
				}

			}
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			$promedio = (float) $suma_meses / $cantidad_meses;


			for( $i = 1; $i <= 12; $i ++ ) {
				if( $demanda[$i] == 0 ) {
					$demanda[$i] = $promedio;
				}
			}


			for( $i = 1; $i <= 12; $i ++ ) {

				if( isset( $demanda[ $i ] ) ){

					mysql_query( "INSERT INTO ".$nombre_tabla." (mes, demanda_promedio) VALUES( ".$i.", ".$demanda[ $i ].")" )
							         or die ("Error al guardar en la tabla: ".$nombre_tabla);
				}
			}
			////////// TOMA LOS DATOS DE LA COLUMNA demanda  DE LA TABLA ce_cfeconsumohistorico Y HACE EL PROMEDIO DE LA DEMANDA DE CADA MES Y LO GUARDA EN LA TABLA ce_demandapromedio EN LA COLUMNA demanda_pico_promedio/////////////////////
			extract(mysql_fetch_array(mysql_query("SELECT ce_tarifas.demanda AS tiene_demanda FROM ce_consumo JOIN ce_tarifas ON ce_consumo.tipo = ce_tarifas.id_tarifa WHERE ce_consumo.secuencia = 'ce_cfe_consumohistorico_".$tid."t';")));
			
			if($tiene_demanda == 1){
				for($i = 1; $i <= 12; $i++){
					
					promedio_demanda($i, $tid);
					
				}
			}
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}

function obtenerConsumo( $tid, $i, $bimestral ) {

	$a = 0;
	$j = 0;
	$consumo = 0;
	$salida = 0;
	$horas_mes = 0;
	$anyo  = Array();

	///////////////// obtiene cosumo //////////////////////////

	$query = mysql_query("SELECT * FROM ce_cfe_consumohistorico_".$tid."t WHERE mes = $i");
//echo "-[$i]: ". "SELECT * FROM ce_cfe_consumohistorico_".$tid."t WHERE mes = $i" . " <br>";
	if( $query ) {
		while($row = mysql_fetch_array($query)){

			$consumo  = $consumo + $row["consumo"];
			$anyo[$a] = $row["ano"];
			$a ++;
		}
	}


	if( $a ) { // Si por lo menos hubo un recibo capturado de ese mes

		if( $bimestral ) {


				$sql = "(mes = $i AND ano IN (" . $anyo[0];

				for( $j = 1; $j < $a; $j ++ ) {
					$sql .= ", ". $anyo [$j];
				}


				$sql .= ")) OR ";



				if( $i == 1 ) {
					$sql .= "(mes = 12 AND ano IN ( ". ($anyo[0] - 1 ) ;

					for( $j = 1; $j < $a; $j ++ ) {
							$sql .= ", ". ($anyo [$j]-1);
					}




				} else {

					$sql .= "(mes = " . ($i-1) . " AND ano IN (" . $anyo[0]  ;

					for( $j = 1; $j < a; $j ++ ) {
						$sql .= ", " . $anyo[$j];
					}
				}


				$sql .= "))";


		} else { // MENSUAL

			$sql = "mes = $i AND ano IN (" . $anyo[0] ;

			for( $j = 1; $j < $a; $j ++ ) {
				$sql .= ", ". $anyo [$j];
			}

			$sql .= ")";

		}


	$resultado = mysql_fetch_array(mysql_query("SELECT sum(num_horas) as suma FROM ce_horasDelMes WHERE ". $sql ));
	$horas_mes = $resultado["suma"];

	$salida = (float) $consumo / $horas_mes;

	} // No hubo resultados

	return $salida;
}
function promedio_demanda($i, $tid){
	
	extract(mysql_fetch_array(mysql_query("SELECT SUM(demanda) / COUNT(demanda) AS total FROM `ce_cfe_consumohistorico_".$tid."t` WHERE mes = ".$i.";")));
	mysql_query("UPDATE ce_demandapromedio_t".$tid."_c1 SET demanda_pico_promedio = ".$total." WHERE mes = ".$i.";") or die("Error al guardar la tabla 'ce_cfe_consumohistorico_".$tid."t'");
	
	unset($total_demanda, $cuantos, $promedio_demanda);
}


?>