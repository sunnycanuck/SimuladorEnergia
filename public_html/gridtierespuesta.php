<?php

/*HMN- Función para crear la respuesta a n grid ties
 *
 *Parametros recibidos:
 *$con       - Conexion a la base de datos
 *$idterreno - ID del terreno
 *$idcaso    - Número de caso
 */
function crear_tabla_gtrespuesta( $idterreno, $idcaso) {

	// Obtener con un Query sobre la tabla de casos solo los que sean de tipo 4 (gridTie)

	$gridties    = getGridTies(  $idterreno, $idcaso );
	$efectividad = Array();

	$total_gridties = count ($gridties);
	$total_fvs      = 0;
	$idfv           = "";
	$anyo_fvr       = getAnyoCamino_solar(  $idterreno );
	$minMaxAnyo     = getMinMaxAnyoHorasDelMes();
	$deltaT         = getDeltaT(  $idterreno );
	$cantidad_fvs   = 0;

	$minAnyo = $minMaxAnyo["min"];
	$masAnyo = $minMaxAnyo["max"];
	$tabla_salida = "";
;
	for( $i = 0; $i < $total_gridties; $i ++ ) {

			$mesTotalCS = Array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
			$mesTotalCL = Array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
			$respuesta_sumas = Array();
			$tabla_salida = "ce_gridtie_" . $idterreno . "t_" . $gridties[$i]["consecutivo"]. "g";

			// Hacer una tabla ce_gridtie_XXc que tenga la misma longitud y fecha/tiempo que ce_horasDelMes
			crearTabla(  $tabla_salida );

			// leer el factor de efectividad de tabla ce_dispositivo, el 2do factor que es porcentaje y guardarlo en una variable
			$efectividad  = getEfectividad(  $efectividad, $gridties[$i]["marca"] );// leer el factor de efectividad
			$fotoVoltList = $gridties[$i]["fvs"];
			$total_fvs    = count($fotoVoltList);

			for( $j = 0; $j < $total_fvs; $j ++ ) {

				$idfv = $fotoVoltList[$j];

				$cantidad_fvs = getCantidadFVs( $idterreno, $idfv );

				if(!respuesta_fv( $idterreno, $idfv ) ) {
					crear_tabla_fvrespuesta( $idterreno, $idcaso );
				}

				for( $mes = 1; $mes <=13; $mes ++){ // Ciclar por cada mes, empezando desde enero

					if($mes==13){ // caso cuando sea leap year, sumar febrero pero con el 28 contado 2 veces
						$respuesta_sumas = getSumaValoresFVR_LY(  $idterreno, $idcaso, $idfv, $anyo_fvr, $efectividad[$gridties[$i]["marca"] ], $deltaT, $cantidad_fvs );
					} else {
						$respuesta_sumas = getSumaValoresFVR(  $idterreno, $idcaso, $idfv, $mes, $anyo_fvr, $efectividad[$gridties[$i]["marca"] ], $deltaT, $cantidad_fvs );
					}

					$mesTotalCS[ $mes ] += $respuesta_sumas["cs"];
					$mesTotalCL[ $mes ] += $respuesta_sumas["cl"];


				} // Fin for mes

			} // Fin for fvs


			for( $gridAnyo = $minAnyo; $gridAnyo <= $masAnyo; $gridAnyo ++ ) {

		    	for( $gridMes = 1; $gridMes <= 12; $gridMes ++ ) { // cycle through all months and set the same production values for each.

					if($gridMes==2){//leave in only leap years (2012,2016,2020,2024,2028,2032,2036,2040,2044,2048,2052,2056,2060,2064,2068,2072,2076,2080,2084,2088,2092,2096)

						if( esBisiesto( $gridAnyo ) ) {
							insertaRegistroGT( $tabla_salida, $gridAnyo, $gridMes, $mesTotalCS[13], $mesTotalCL[13] );
						} else {
							insertaRegistroGT( $tabla_salida, $gridAnyo, $gridMes, $mesTotalCS[$gridMes], $mesTotalCL[$gridMes] );

						}

				    } else {
				    	insertaRegistroGT( $tabla_salida, $gridAnyo, $gridMes, $mesTotalCS[$gridMes], $mesTotalCL[$gridMes]  );
				    }
				}

			}


	} // Fin for grids

}


function esBisiesto( $anyo ) {

	switch( $anyo ) {

			case 2012:
			case 2016:
			case 2020:
			case 2024:
			case 2028:
			case 2032:
			case 2036:
			case 2040:
			case 2044:
			case 2048:
			case 2052:
			case 2056:
			case 2060:
			case 2064:
			case 2068:
			case 2072:
			case 2076:
			case 2080:
			case 2084:
			case 2088:
			case 2092:
			case 2096: return true;
	}

	return false;
}

function insertaRegistroGT( $nombre_tabla, $anyo, $mes, $cs, $cl ) {

	$sql = "INSERT INTO " . $nombre_tabla . " (ano, mes, potenciaCS, potenciaCL) VALUES (" .
			$anyo . ", " . $mes . ", " . $cs . ", " . $cl .")";

	mysql_query($sql);
}

function getCantidadFVs( $idterreno, $idfv ) {

	$sql = "SELECT dispositivos FROM ce_casos_" . $idterreno . "t where id = " . $idfv;
	$cantidad = 0;

 	$resultado = mysql_query( $sql );
 	$respuesta = Array();

 	if( $resultado ) {

 		if( $registro = mysql_fetch_array( $resultado ) ) {
			$cantidad = $registro["dispositivos"];
		}

		mysql_free_result( $resultado );
 	}

 	return $cantidad;
}

function getMinMaxAnyoHorasDelMes() {
	$sql = "SELECT MIN(ano) as min, MAX(ano) as max FROM ce_horasDelMes";

 	$resultado = mysql_query( $sql );
 	$respuesta = Array();

 	if( $resultado ) {

 		if( $registro = mysql_fetch_array( $resultado ) ) {
				$respuesta["min"] = $registro["min"];
				$respuesta["max"] = $registro["max"];
		}

		mysql_free_result( $resultado );
 	}

	return $respuesta;
}

function getSumaValoresFVR_LY( $idterreno, $idcaso, $idfv, $anyo_fvr, $eff, $deltaT, $cantidad_fvs ) {

	$nombre_tabla   = "ce_fotovoltaico_respuesta_t" . $idterreno . "c" . $idcaso . "fv" . $idfv;

	$tiempo_inicial = $anyo_fvr . "-02-01 00:00:00";
	$tiempo_final   = $anyo_fvr . "-02-28 23:59:59";

 	$sql = "SELECT sum(potenciaCS) * ". $eff . " * " . $deltaT . " * " . $cantidad_fvs . " as cs, " .
 	              "sum(potenciaCL) * ". $eff . " * " . $deltaT . " * " . $cantidad_fvs . " as cl FROM " . $nombre_tabla .
 	              " WHERE tiempo BETWEEN '". $tiempo_inicial . "' AND '" . $tiempo_final . "'";

 	$resultado = mysql_query( $sql );

 	$respuesta = Array();

 	if( $resultado ) {

			if( $registro = mysql_fetch_array( $resultado ) ) {
				$respuesta["cs"] = $registro["cs"];
				$respuesta["cl"] = $registro["cl"];
			}

			mysql_free_result( $resultado );
 	}


	$tiempo_inicial = $anyo_fvr . "-02-28 00:00:00";
	$tiempo_final   = $anyo_fvr . "-02-28 23:59:59";

 	$sql = "SELECT sum(potenciaCS) * ". $eff . " * " . $deltaT . " * " . $cantidad_fvs . " as cs, " .
 	              "sum(potenciaCL) * ". $eff . " * " . $deltaT . " * " . $cantidad_fvs . " as cl FROM " . $nombre_tabla .
 	              " WHERE tiempo BETWEEN '". $tiempo_inicial . "' AND '" . $tiempo_final . "'";

 	$resultado = mysql_query( $sql );

 	if( $resultado ) {

			if( $registro = mysql_fetch_array( $resultado ) ) {
				$respuesta["cs"] = $respuesta["cs"] + $registro["cs"];
				$respuesta["cl"] = $respuesta["cl"] + $registro["cl"];
			}

			mysql_free_result( $resultado );
 	}

	return $respuesta;
}


function getDeltaT( $idterreno ) {

		$t1 = 0;
		$t2 = 0;
		$deltaT = 0;
		$sql  = "SELECT hour(tiempo) + minute(tiempo)/60 + second(tiempo)/3600 as t FROM ce_camino_solar_" . $idterreno. "t limit 2";

		$resultado = mysql_query( $sql );

		 	if( $resultado ) {

					if( $registro = mysql_fetch_array( $resultado ) ) { $t1 = $registro["t"]; }
					if( $registro = mysql_fetch_array( $resultado ) ) {	$t2 = $registro["t"]; }

					mysql_free_result( $resultado );

					$deltaT = $t2 - $t1;
		 	}


		return $deltaT;
}

function getSumaValoresFVR( $idterreno, $idcaso, $idfv, $mes, $anyo_fvr, $eff, $deltaT, $cantidad_fvs ) {

	$nombre_tabla   = "ce_fotovoltaico_respuesta_t" . $idterreno . "c" . $idcaso . "fv" . $idfv;
	$mes = getMes( $mes );

	$tiempo_inicial = $anyo_fvr . "-". $mes ."-01 00:00:00";
	$tiempo_final   = $anyo_fvr . "-". $mes ."-31 23:59:59";

 	$sql = "SELECT sum(potenciaCS) * ". $eff . " * " . $deltaT . " * " . $cantidad_fvs . " as cs, " .
 	              "sum(potenciaCL) * ". $eff . " * " . $deltaT . " * " . $cantidad_fvs . " as cl FROM " . $nombre_tabla .
 	              " WHERE tiempo BETWEEN '". $tiempo_inicial . "' AND '" . $tiempo_final . "'";

 	$resultado = mysql_query( $sql );

 	$respuesta = Array();

 	if( $resultado ) {

			if( $registro = mysql_fetch_array( $resultado ) ) {
				$respuesta["cs"] = $registro["cs"];
				$respuesta["cl"] = $registro["cl"];
			}

			mysql_free_result( $resultado );

 	}

	return $respuesta;
}

function getMes( $mes ) {

  if ( $mes < 10 ) { return "0" . $mes; }

  return $mes;
}

/// Revisa que este creada fotovolt respuesta
function respuesta_fv( $idterreno, $idfv ) {

	$respuesta = false;
	$sql = "SELECT secuencia FROM ce_casos_" . $idterreno . "t WHERE id = " . $idfv;

	$resultado = mysql_query( $sql );

	if( $resultado ) {

		if( $registro = mysql_fetch_array( $resultado ) ) {
			$respuesta = strlen( $registro["secuencia"] ) > 0;
		}

		mysql_free_result( $resultado );
	}

	return $respuesta;
}


// Selecciona los gridties de un caso, y los regresa en una matriz.
function getGridTies( $idterreno, $idcaso ) {

	$gridties = Array();
	$sql = "SELECT id, id_dispositivo, dispositivos_variables FROM ce_casos_" .  $idterreno . "t WHERE caso = " . $idcaso . " AND id_tipo = 4";

	$resultado = mysql_query($sql);

 	if( $resultado ) {

		$i = 0;
		while( $registro = mysql_fetch_array( $resultado ) ){
		    $gridties[$i]["consecutivo"] = $registro["id"];
		    $gridties[$i]["marca"]       = $registro["id_dispositivo"];
		    $gridties[$i]["fvs"]         = getFVS($registro["dispositivos_variables"]);
		    $i ++;
		}

		mysql_free_result( $resultado );
	}

	return $gridties;
}

function getAnyoCamino_solar( $idterreno ) {
	$anyo = "0";
	$sql  = "SELECT YEAR(tiempo) as anyo FROM ce_camino_solar_" . $idterreno. "t limit 1";

	$resultado = mysql_query($sql);

 	if( $resultado ) {

		if( $registro = mysql_fetch_array( $resultado ) ){
		    $anyo = $registro["anyo"];
		}

		mysql_free_result( $resultado );
	}

	return $anyo;

}

function getFVS( $dispositivos_str ) {

	$fvs = Array();


	if( strlen($dispositivos_str) > 0 ) {

		$tok = strtok($dispositivos_str, ";");

		$i = 0;
		while( $tok !== false ) {
			$fvs[$i] = $tok;

			$tok = strtok(";");
			$i ++;
		}

	}

	return $fvs;
}


function crearTabla( $nombre_tabla ) {

	$sql = "DROP TABLE IF EXISTS ". $nombre_tabla;
	mysql_query($sql);


	$sql = "CREATE TABLE ". $nombre_tabla .
	   "(
			 id INT PRIMARY KEY AUTO_INCREMENT,
		     ano int(11) NOT NULL,
		     mes int(11) NOT NULL,
			 potenciaCS FLOAT(9,3),
			 potenciaCL FLOAT(9,3))";

		mysql_query($sql);
}

// leer el factor de efectividad de tabla ce_dispositivo, el 2do factor que es porcentaje y guardarlo en una variable
function getEfectividad( $efectividad, $iddispositivo ) {

	if( !isset( $efectividad[ $iddispositivo ] ) ) {
		$sql = "SELECT factores FROM ce_dispositivos WHERE id_dis = " . $iddispositivo;

		$resultado = mysql_query($sql);

		if( $resultado ) {

			$registro = mysql_fetch_array( $resultado );

			$factor = $registro["factores"];

			$i = strpos( $factor, ";" );

			if ( $i > 0 ) {
				$efectividad[ $iddispositivo] = substr( $factor, $i + 1 ) / 100;
			}

			mysql_free_result( $resultado );
		}
	}

	return $efectividad;
}

?>