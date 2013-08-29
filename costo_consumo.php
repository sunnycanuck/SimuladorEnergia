<?php
/* A program to calculate value of all costs for consumpiton for all the cases listed in ce_casos,
including CFE, Water, Gas LP, CO2 etc... for the calculado_energetico.

  Project Leader Rodger Evans, 2011-11-09
  sunnycanuck@gmail.com
  LEARS de CICESE
  revans@cicese.mx
  Collaborators Voxel Soluciones
  http://www.voxelsoluciones.com
  info@voxelsoluciones.com

  Published under the Creative Commons Attribution-ShareAlike 2.5 Generic (CC BY-SA 2.5) licence
  http://creativecommons.org/licenses/by-sa/2.5/

  Publicado bajo la Licencia Creative Commons Atribuci??n-CompartirIgual 2.5 M?Â©xico (CC BY-SA 2.5)
  http://creativecommons.org/licenses/by-sa/2.5/mx/
  -----------------------------------
  Modificaciones
  -----------------------------------
  Clave: HMN1
  Hecha por: Héctor Mora.
  Fecha: 19/Julio/2012
  Descripción: No generaba el registro1 de los costos de los aparatos.
  			   Se requirió que fuera acomulativo a partir de los costos.
  ------------------------------------
  Clave: HMN2
  Hecha por: Héctor Mora.
  Fecha: 18/Octubre/2012
  Descripción: Se agrego a la consulta de getPrecios(), la columna dispositivos
  para multiplicar por el numero de ellos.
  ------------------------------------
  Clave: HMN3
  Hecha por: Héctor Mora.
  Fecha: 05/Noviembre/2012
  Descripción: Se modificó la fórmula para obtener el pago.
  ---------------------------------------
  Clave: RE01
  Hecha por: Rodger Evans.
  Fecha: 06/Noviembre/2012
  Descripción: Verificando los equaciones para pagos.
  ---------------------------------------
  Clave: HMN04
  Hecha por: Héctor Mora
  Fecha: 08/Noviembre/2012
  Descripción: Implementación de tarifa DAC
  -------------------------------------------
    Clave: RE01
  Hecha por: R. Evans
  Fecha: 10/Junio/2013
  Descripción: buscando error
  -------------------------------------------
*/

function costo_de_consumo( $idterreno, $idcaso, $anyo_inicio ) {

	$insertar = False;
  $casos    = Array();
  $casos_1  = Array(); // HMN04 Almacena el caso 1, para efectos de promediar en tarifa DAC
  $casos[0] = $idcaso;
  $casos_1[0] = "1";
  $tabla_existe = consulta_costoconsumo_existe( $idterreno );

  if( $tabla_existe == 0 ) { // La tabla no existe
  		crea_tabla_costodeconsumo(  $idterreno );
  		$insertar = True;
  } else {

	 if( $tabla_existe == 1 ) {
     	   $insertar = True;
		 }

     	nivelarCasosEnTabla(  $idterreno, $idcaso );

  }

  if( $idcaso != 1 && $tabla_existe != 2 ) {
  		$casos[1] = 1;
  }


  $tarifa    = getTarifa(  $idterreno );
  $estaciones= getEstaciones( $tarifa );


  $campo_demanda = getDemanda( $tarifa );
  $limiteDAC = getLimiteDAC( $tarifa ); //HMN04 Obtiene el Limite DAC correspondiente a la tarifa del recibo.
  $nombre_tarifa = getNombreTarifa( $tarifa );

  $medidor = getMedidor(  $idterreno, $casos, $anyo_inicio, $nombre_tarifa, $campo_demanda );
  $medidor_caso1 = getMedidor( $idterreno, $casos_1, $anyo_inicio, $nombre_tarifa, $campo_demanda ); // Obtiene el medidor del caso1,

  $t_tipo  = getTarifasTipo(  $tarifa );
  $f_ini   = getFechaInicial(  $idterreno, $idcaso );
  $f_fin   = getFechaFinal  (  $idterreno, $idcaso );

 	$total_registros = count( $medidor );
	$estacion_anyo = "";
	$anyo_hoy = date("Y");
	$mes_hoy  = date("m");
	//////////

	$resultadosp = getPrecios( $idterreno, $casos, $idcaso );

	//////////////////// HMN1 Se inicializa la sumatoria con el costo de los aparatos //////////////////////

	$resultados[ $casos[0] ] = $resultadosp[ $casos[0]];

	if( count($casos) > 1 ) {
		$resultados[ $casos[1] ] = $resultadosp[ $casos[1]];
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////


	for( $i = 0; $i < $total_registros; $i ++ ) {

		$estacion_anyo = getEstacion( $estaciones, $medidor[$i] -> mes );

		$consumo = $medidor[$i]->v_consumo[ $casos[0] ];
		$demanda = $medidor[$i]->v_demanda[ $casos[1] ];

		$resultados[ $casos[0] ] += getPago( $medidor, $t_tipo, $consumo, $demanda, $estacion_anyo, $i, $limiteDAC, $casos[0], $medidor_caso1, $campo_demanda ); //HMN03 Calcula el pago.

		if( count($casos) > 1 ) {
			$consumo = $medidor[$i]->v_consumo[ $casos[1] ];
			$demanda = $medidor[$i]->v_demanda[ $casos[1] ];
			$resultados[ $casos[1] ] +=
			getPago( $medidor, $t_tipo, $consumo, $demanda, $estacion_anyo, $i, $limiteDAC, $casos[1], $medidor_caso1, $campo_demanda ); //HMN03 Calcula el pago.

		}

		if( $insertar ) {
			inserta_costodeconsumo  ( $idterreno, $medidor[$i]->anyo, $medidor[$i]->mes, $casos, $resultados );
		} else {
			actualiza_costodeconsumo( $idterreno, $medidor[$i]->anyo, $medidor[$i]->mes, $casos, $resultados );
		}


	} // Fin For


}

// Si el promedio de consumo de los últimos 12 meses es igual o excede al limite DAC para su tarifa, se cambia la tarifa a DAC
function esDAC( $limiteDAC, $i, $medidor, $medidor_caso1, $idcaso ) {

    $promedio = getPromedio($i, $medidor, $medidor_caso1, $idcaso);

    if( $promedio < $limiteDAC ) { //o $limiteDAC==0 **
    	return False;
    }

	return True;
}

// Obtiene el promedio de consumo de los ultimos 12 meses, en caso de no tener los meses de consumo, se toman los valores del caso 1
function getPromedio( $i, $medidor, $medidor_caso1, $idcaso ) {
	$promedio = 0;
	$suma = 0;
	$meses = Array( -1 => 11, -2 => 10, -3 => 9, -4 => 8, -5 => 7, -6 => 6, -7 => 5, -8 => 4, -9 => 3, -10 => 2, -11 => 1, -12 => 0 );

    for( $j = 1; $j <= 12; $j ++ ) {

       if( ($i - $j) < 0 ) {
            $suma += $medidor_caso1[ $meses[ ($i-$j)] ]->v_consumo["1"];
       } else{
       	   $suma += $medidor[$i-$j]->v_consumo[$idcaso];
       }
    }

    $promedio = (float) $suma / 12;
	return $promedio;
}

//HMN04 Calcula el pago con tarifa DAC.
function getPagoDAC($i, $estacion, $tarifa_dac, $consumo) {
    $pago = $tarifa_dac[ $i ]->tarifaDAC[$estacion]  * $consumo + $tarifa_dac[$i]->cargafijoDAC;

	return $pago;
}

//HMN03 Función para calcular el pago, de acuerdo a la fórmula de máximos y mínimos.
function getPago( $medidor, $t_tipo, $consumo, $demanda, $estacion_anyo, $i, $limiteDAC, $idcaso, $tarifa_dac, $campo_demanda ) {

		$pago = 0;
		//** if tarifa 2 carga_fijo viene de ce_tarifa_2
		// $consumo=carga_fijo**
		$carga_fijo = $t_tipo[$estacion_anyo]["carga_fijo"] ;

		if( $consumo < $carga_fijo ) {
			$consumo = $carga_fijo;
		}

		if( esDAC($limiteDAC, $i, $medidor, $tarifa_dac, $idcaso) ) { /// HMN04 Si aplica tarifa DAC, se interrumpe el proceso, calculando el pago de distinta forma.

			return getPagoDAC($i, $estacion_anyo, $tarifa_dac, $consumo );
		}


		if( $campo_demanda == 0 )  { // Si demanda = 0 (ce_tarifas)
			$Pbb = $medidor[$i]->basicoBajo ;
			$Pib = $medidor[$i]->intermedioBajo ;
			$Pia = $medidor[$i]->intermedioAlto;
			$Pea = $medidor[$i]->exedenteAlto;

			$Bb  = $t_tipo[$estacion_anyo]["lim_basico_B"];
			$Ib  = $t_tipo[$estacion_anyo]["lim_int_B"];
			$Ia  = $t_tipo[$estacion_anyo]["lim_int_A"];

			// FORMULA ORIGINAL: $pago=$Pbb*min($valor-$Bb)+$Pib*max(0,min($valor, $Ib)-$Bb)+$Peb*max(0,min($valor,$Eb)-$Ib);

			if( $Ia == 0 ) { // Si no hay limite Excedente Bajo 1-1D o 1F, 1E, 1C, 1D invierno
				$pago = $Pbb * min( $consumo, $Bb ) + $Pib * max( 0, min($consumo, $Ib ) - $Bb ) + $Pea*max(0, $consumo - $Ib); //new tarifa formula

				//** if( es tarfia 2){
				//$CF= $medidor[$i]->carga_fijo;
				//$pago=$pago+$CF;
				//}  **

			} else { //esta 1F, 1E, 1C, 1D verano
				$pago = $Pbb * min( $consumo, $Bb ) + $Pib * max( 0, min($consumo, $Ib ) - $Bb ) + $Pia * max( 0, min($consumo, $Ia)- $Ib ) + $Pea*max(0, $consumo - $Ia); //new tarifa formula //RE01 change $Ea to $Ia
			}

		} else {

			if( $campo_demanda == 1 ) {
				
				$C = $medidor[$i]->sim_consumo;  //consumo
				$D = $medidor[$i]->sim_demanda;	//demanda
				$pago = ($C * $consumo) + ($D * $demanda);
								
			} else {

				if( $campo_demanda == 2 ) {
					$Pbb = $medidor[$i]->basicoBajo ;
					$Pib = $medidor[$i]->intermedioBajo ;
					$Pia = $medidor[$i]->intermedioAlto;

					$Bb  = $t_tipo[$estacion_anyo]["lim_basico_B"];
					$Ib  = $t_tipo[$estacion_anyo]["lim_int_B"];

					$pago = $Pbb * min( $consumo, $Bb ) + $Pib * max( 0, min($consumo, $Ib ) - $Bb ) + $Pia * max(0, $consumo - $Ib);
				}

			}


		}

		//echo "[$i] NO DAC: " . $pago . "<br>";

		return $pago;
}


function actualiza_costodeconsumo( $idterreno, $anyo, $mes, $casos, $resultados ){
	$sql = "UPDATE ce_costodeconsumo_" . $idterreno . "t SET consumo" . $casos[0] . "=" . $resultados[$casos[0]].
	       " WHERE anyo = " . $anyo . " AND mes = " . $mes ;

	 mysql_query( $sql );
}


function nivelarCasosEnTabla(  $idterreno, $idcaso ) {

//	$casos = getCasos_cc(  $idterreno );

	$result = mysql_query("SELECT * FROM ce_costodeconsumo_" . $idterreno . "t" );
	$fields_num = mysql_num_fields($result);
	$c = 'N' ;

	for($i=0; $i<$fields_num; $i++)
	{
		$field = mysql_fetch_field($result);


		if( $field->name == ("consumo". $idcaso) ) {

			$c = 'S';
			break;
		}
	}

if( $c == 'N' ) {
		$sql = "ALTER TABLE ce_costodeconsumo_" . $idterreno . "t ADD consumo" . $idcaso . " FLOAT(14,3) NOT NULL";

		mysql_query($sql);
	}

}

function inserta_costodeconsumo(  $idterreno, $anyo, $mes, $casos, $resultados ) {

  if( $mes > 0 ) {

	$sql = "INSERT INTO ce_costodeconsumo_". $idterreno  . "t (anyo, mes, ";

	$total_casos = getCasos_cc( $idterreno );

	for( $i = 0; $i < count($total_casos); $i ++ ) {
		$sql.= "consumo" . $total_casos[$i] .", ";
	}

    $sql = substr( $sql, 0, strlen( $sql ) - 2 );
    $sql .= ") VALUES (" . $anyo . ", " . $mes . ", ";

    for( $i = 1; $i <= count($total_casos); $i ++ ) {

    	if( $i == $casos[0] || (count($casos) > 1 && $i == $casos[1] ) ) {
    		if( $i == $casos[0] ) {  $sql .= $resultados[$casos[0]] . ", "; } else { $sql .= $resultados[$casos[1]]. ", "; }

    	} else {
    		$sql .= "0, ";
    	}

    }

	$sql = substr( $sql, 0, strlen( $sql ) - 2 );
	$sql .= ")";
	

	mysql_query( $sql );
	}

}


function getEstacion( $meses, $mes ){

	return $meses[ $mes ];
}


function crea_tabla_costodeconsumo(  $idterreno ) {

	$sql = "CREATE TABLE ce_costodeconsumo_". $idterreno  . "t ".
		   "(
				 id INT PRIMARY KEY AUTO_INCREMENT,
				 anyo INT(11),
				 mes SMALLINT(2),";


	$casos = getCasos_cc( $idterreno );

	for( $i = 0; $i < count($casos); $i ++ ) {
		$sql.= "consumo" . $casos[$i] ." FLOAT(14,3),";
	}

    $sql = substr( $sql, 0, strlen( $sql ) - 1 );

	$sql .= ")";

	mysql_query($sql);
}

function borrar_tabla_costodeconsumo($idterreno) {
	$sql = "DROP TABLE IF EXISTS ce_costodeconsumo_". $idterreno . "t";
	mysql_query($sql);
}

function getCasos_cc(  $idterreno ) {

	$salida = Array();
	$i = 0;

	$sql = "SELECT DISTINCT caso FROM ce_casos_" . $idterreno . "t";

	$resultado = mysql_query($sql);

	if( $resultado ) {

		while( $registro = mysql_fetch_array( $resultado ) ) {
             $salida[$i] = $registro["caso"];
             $i ++;
		}
	}

	return $salida;
}

function getPrecios(  $idterreno, $casos, $idcaso ) {
	$result = mysql_fetch_array(mysql_query("SELECT id_pqt_caso FROM ce_casos_". $idterreno . "t WHERE caso = ". $idcaso ." LIMIT 1;"));
	$idPqt = $result['id_pqt_caso'];
	$precios = Array();
	
	if($idPqt != NULL){
		extract(mysql_fetch_array(mysql_query("SELECT precio AS suma FROM ce_paquetes WHERE id_pqt = ".$idPqt.";")));
		$precios[$idcaso] += (float) $suma; 
		
	}else{
		$i = 0;
		//HMN1, estaba clavado el id del terreno
		//HMN2, se agrego la multiplicacion de A.dispositivos.
		$sql = "SELECT A.caso, SUM( A.dispositivos * (B.precio_dispositivo + B.precio_instalacion) ) AS precio from ce_casos_" . $idterreno ."t A, ce_dispositivos B WHERE A.caso in (";
	
		for( $i = 0; $i < count ($casos); $i++ ) {
			$sql .= $casos[$i] . ", ";
			$precios["".$i] = 0;
		}
	
		$sql = substr( $sql, 0, strlen( $sql ) -2 );
	
		$sql .=") AND B.id_dis = A.id_dispositivo GROUP BY A.caso";
		
		
		$resultado = mysql_query($sql);
	
		if( $resultado ) {
	
			while( $registro = mysql_fetch_array( $resultado ) ) {
				$precios[ $registro["caso"] ] += $registro["precio"];			
			}
		}
	}
  return $precios;
}


function consulta_costoconsumo_existe(  $idterreno ) {

	$salida = 0;

	$sql = "SELECT id FROM ce_costodeconsumo_" . $idterreno . "t limit 1";

	$resultado = mysql_query($sql);

	if( $resultado ) {
		$salida = 1;
		if( $registro = mysql_fetch_array( $resultado ) ) {
             $salida = 2;
		}
	}

	return $salida;
}


function getFechaInicial(  $idterreno, $idcaso ) {

	$salida = "";
	$sql = "SELECT anyo, mes FROM ce_medidorCFE_" . $idterreno . "t" . $idcaso . "c limit 1";

	$resultado = mysql_query($sql);

	if( $resultado ) {

		if( $registro = mysql_fetch_array( $resultado ) ) {
             $salida = $registro["anyo"] . "-" . $registro["mes"];
		}
	}

	return $salida;
}


function getFechaFinal(  $idterreno, $idcaso ) {

	$salida = "";
	$sql = "SELECT anyo, mes FROM ce_medidorCFE_" . $idterreno . "t" . $idcaso . "c order by anyo desc, mes desc limit 1";

	$resultado = mysql_query($sql);

	if( $resultado ) {

		if( $registro = mysql_fetch_array( $resultado ) ) {
             $salida = $registro["anyo"] . "-" . $registro["mes"];
		}
	}

	return $salida;
}


function getTarifasTipo( $tarifa ) {
	$salida = Array();
	$sql = "SELECT estacion, lim_basico_B, lim_int_B, lim_int_A, lim_DAC, carga_fijo FROM ce_tarifas_tipo WHERE tipo = " . $tarifa;

	$resultado = mysql_query($sql);

	if( $resultado ) {

			while( $registro = mysql_fetch_array( $resultado ) ){
				$estacion = $registro["estacion"];

				$salida[$estacion]["lim_basico_B"] = $registro["lim_basico_B"];
				$salida[$estacion]["lim_int_B"]    = $registro["lim_int_B"];
				$salida[$estacion]["lim_int_A"]    = $registro["lim_int_A"];
				$salida[$estacion]["lim_DAC"]      = $registro["lim_DAC"];
				$salida[$estacion]["carga_fijo"]   = $registro["carga_fijo"];


			}

			mysql_free_result( $resultado );
	}

  return $salida;
}

function getTarifa( $idterreno ) {

	$tarifa = "0";
	$sql = "SELECT tipo FROM ce_consumo WHERE secuencia = 'ce_cfe_consumohistorico_" . $idterreno . "t'";

	$resultado = mysql_query($sql);

	if( $resultado ) {

			if( $registro = mysql_fetch_array( $resultado ) ){
				$tarifa = $registro["tipo"];
			}

			mysql_free_result( $resultado );
	}

  return $tarifa;
}


function getNombreTarifa( $idtarifa ) {

	$tarifa = "";
	$sql = "SELECT tarifa FROM ce_tarifas WHERE id_tarifa = ".  $idtarifa;

	$resultado = mysql_query($sql);

	if( $resultado ) {

			if( $registro = mysql_fetch_array( $resultado ) ){
				$tarifa = $registro["tarifa"];
			}

			mysql_free_result( $resultado );
	}

  return $tarifa;
}


function getEstaciones( $idtarifa ) {

	$mesesArray = array();

	$sql = "SELECT mesIncluido, estacion FROM ce_tarifas_tipo WHERE tipo = ". $idtarifa;
	$estacion = "";
	$mesIncluido = "";

	$resultado = mysql_query($sql);

	if( $resultado ) {

			while( $registro = mysql_fetch_array( $resultado ) ){



				$estacion    = $registro["estacion"];
				$mesIncluido = $registro["mesIncluido"];

				$mesA = explode( ";", $mesIncluido );

				$tot = count ( $mesA );

				for( $i = 0; $i < $tot; $i ++ ) {
					$mesesArray[ $mesA[$i] ] = $estacion;
				}


			}

			mysql_free_result( $resultado );
	}

	return $mesesArray;

}


function getDemanda( $idtarifa ) {

	$demanda = 0;
	$sql = "SELECT demanda FROM ce_tarifas WHERE id_tarifa = ". $idtarifa;

	$resultado = mysql_query($sql);

	if( $resultado ) {

			if( $registro = mysql_fetch_array( $resultado ) ){
                $demanda = $registro["demanda"];
			}

			mysql_free_result( $resultado );
	}

  return $demanda;

}

function getLimiteDAC( $tarifa ) {
	$limiteDAC = "0";
	$sql = "SELECT lim_DAC FROM ce_tarifas_tipo WHERE tipo = " . $tarifa;

	$resultado = mysql_query($sql);

	if( $resultado ) {

			if( $registro = mysql_fetch_array( $resultado ) ){
				$limiteDAC = $registro["lim_DAC"];
			}

			mysql_free_result( $resultado );
	}

  return $limiteDAC;
}

//A.anyo, A.mes, A.consumo as consumo,
function getMedidor( $idterreno, $casos, $anyo_inicio, $tarifa, $campo_demanda ){
	$arreglo = Array();


	if( $campo_demanda == 0 ) {
    	$sql = "SELECT Z.verano, Z.invierno, Z.carga_fijo, A.basico_Bajo as bb, A.intermedio_Bajo as ib, A.basico_Alto as ba, A.intermedio_Alto as ia, A.exedente_Alto as ea, " .
           "B.anyo as anyo, B.mes as mes, B.consumo as consumo" . $casos[0] . ", B.demanda as demanda" . $casos[0] ;
	} else {

		if( $campo_demanda == 2 ) {
		$sql = "SELECT Z.verano, Z.invierno, Z.carga_fijo, A.basico_Bajo as bb, A.intermedio_Bajo as ib, 1 as ba, A.intermedio_Alto as ia, 1 as ea, " .
			 "B.anyo as anyo, B.mes as mes, B.consumo as consumo" . $casos[0] . ", B.demanda as demanda" . $casos[0] ;
		} else {

			if( $campo_demanda == 1 ) {
			$sql = "SELECT Z.verano, Z.invierno, Z.carga_fijo, 1 as bb, 1 as ib, 1 as ba, 1 as ia, 1 as ea, A.demanda as sim_demanda, A.consumo as sim_consumo, " .
				 "B.anyo as anyo, B.mes as mes, B.consumo as consumo" . $casos[0] . ", B.demanda as demanda" . $casos[0] ;

			}

		}


	}


    if( count( $casos ) > 1 ) { $sql .= ", C.consumo as consumo" . $casos[1] . ", C.demanda as demanda". $casos[1]; }


    $sql.= " FROM ce_simtarifas_DAC Z,  ce_simtarifas_" . $tarifa . " A, ce_medidorCFE_" . $idterreno . "t" . $casos[0] . "c B";


	if( count( $casos ) > 1 ) { $sql .= ", ce_medidorCFE_" . $idterreno . "t" . $casos[1] ."c C"; }


    $sql.=  " WHERE A.ano >= " . $anyo_inicio . " AND A.ano = B.anyo AND A.mes = B.mes AND Z.ano = A.ano AND Z.mes = B.mes";

    if( count( $casos ) > 1 ) { $sql .= " AND B.anyo = C.anyo AND B.mes = C.mes"; }
	
	
	$resultado = mysql_query($sql);

 	if( $resultado ) {

	$idx = 0;

		while( $registro = mysql_fetch_array( $resultado ) ){
			//$idx = "". $registro["anyo"] . "-" . $registro["mes"];
		    $arreglo[$idx] = new consumo();
			$arreglo[$idx]->v_consumo[ $casos[0] ]  = $registro["consumo" . $casos[0] ];
			$arreglo[$idx]->v_demanda[ $casos[0] ]  = $registro["demanda" . $casos[0] ];
			$arreglo[$idx]->anyo = $registro["anyo"];
			$arreglo[$idx]->mes  = $registro["mes"];

			if( count( $casos ) > 1 ) {
				$arreglo[$idx]->v_consumo[ $casos[1] ] = $registro["consumo" . $casos[1] ];
				$arreglo[$idx]->v_demanda[ $casos[1] ] = $registro["demanda" . $casos[1] ];
			}

			if( $campo_demanda == 1 ) {
				$arreglo[$idx] ->sim_consumo = $registro["sim_consumo"];
				$arreglo[$idx] ->sim_demanda = $registro["sim_demanda"];
			}

			$arreglo[$idx]->basicoBajo     = $registro["bb"];
			$arreglo[$idx]->intermedioBajo = $registro["ib"];
			$arreglo[$idx]->exedenteBajo   = 0; //$registro["eb"];
			$arreglo[$idx]->basicoAlto     = $registro["ba"];
			$arreglo[$idx]->intermedioAlto = $registro["ia"];
			$arreglo[$idx]->altoAlto       = 0; // $registro["aa"];
			$arreglo[$idx]->exedenteAlto   = $registro["ea"];
			$arreglo[$idx]->tarifaDAC["v"] = $registro["verano"];
			$arreglo[$idx]->tarifaDAC["i"] = $registro["invierno"];
			$arreglo[$idx]->cargafijoDAC = $registro["carga_fijo"];
			$idx ++;
		}

		mysql_free_result( $resultado );
	}

    return $arreglo;
}


function grafica_costo_de_consumo(  $idterreno ) {
$salida = "";
$salida .= "<script type=\"text/javascript\" src=\"https://www.google.com/jsapi\"></script>\n";
$salida.="	    <script type=\"text/javascript\">\n";
$salida.="	      google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});\n";
$salida.="	      google.setOnLoadCallback(drawChart);\n";
$salida.="	      function drawChart() {\n";
$salida.="	        var data = new google.visualization.DataTable();\n";
$salida.="	        data.addColumn('string', 'fecha');\n";



			        	$total_casos = getTotalCasos_cc(  $idterreno );


						for( $i = 1; $i <= $total_casos ; $i ++ ) {
	        				$salida.= "		        data.addColumn('number', 'caso". $i ."');\n";
	       				 }

						 $salida .= "data.addRows([\n";

						///// SE AGREGAN LOS DATOS YA ESTABLECIDOS
						$sql = "SELECT anyo, mes";

						for( $i = 1; $i <= $total_casos ; $i ++ ) {
							        				$sql .=", consumo" . $i;
	       				}

	       				$sql .=  " FROM ce_costodeconsumo_" . $idterreno . "t";

						$resultado = mysql_query( $sql );

						 if( $resultado ) {
						 	while( $registro = mysql_fetch_array( $resultado ) ) {
						 		$salida.= "		          ['".$registro[ "anyo"]. "-".$registro[ "mes"]."' ";
						 		for( $i = 1; $i <= $total_casos ; $i ++ ) {
									$salida.= ", " . $registro["consumo" . $i];
								}
								$salida.= "],\n";
						 	}



						 	mysql_free_result( $resultado );

						 }

								$salida = substr( $salida, 0, strlen( $salida) -2 ). "\n";



$salida.="	        ]);\n";
$salida.="\n";
$salida.="\n";
$salida.="			var options = {\n";
$salida.="	          width: 1000, height: 1000,\n";
$salida.="	          title: 'Costo de consumo terreno: ". $idterreno . "'\n";
$salida.="	        };\n";
$salida.="\n";
$salida.="	        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));\n";
$salida.="	        chart.draw(data, options);\n";
$salida.="	      }\n";
$salida.="    </script>\n";

  return $salida;

}

  /*****Seccion de prueba *******
  require("conexion.php");
  require("consumo.php");
  costo_de_consumo("25", "2", "2013");
  mysql_close($conn);********/


?>
