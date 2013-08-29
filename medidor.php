<?php
/* A program to calculate the simulated and historic readings on a CFE meter for the calculado_energetico.

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

  ***************************************************************************************
  Modificaciones
  ***************************************************************************************
  * Por: Héctor Mora
  * Fecha:02-Abril-2012
  * Descripción: Desarrollo de la función medidor.
  * --------------------------------------------------------------------------------------
*/

function medidor($idterreno, $idcaso, $anyo_inicio ) {

	$nombre_tabla = "ce_medidorCFE_" . $idterreno . "t" . $idcaso . "c";
	$consumo = "0";
	$demanda = "0";
	$anyo    = "0";
	$mes     = "0";
	$total_horas_mes = 0;
	$total_grid_ties = Array();
    ///////////////// CREACION DE LA TABLA ////////////////////
	crear_tabla_medidor($nombre_tabla);
	//////////////////////////////////////////////////////////

	$demanda_promedio = get_demanda_promedio($idterreno ); /// Lee tabla demanda promedio
	$horas_mes        = get_horas_mes($anyo_inicio );      /// Lee tabla horasDelMes
	$total_horas_mes = count( $horas_mes );

	////////// PARA EL CASO 1, EL CONSUMO SE DETERMINA POR: [horas del mes] X [demanda promedio] /////////////////////
	if( $idcaso == 1 ) {
		for( $i = 0; $i < $total_horas_mes; $i ++ ) {

			$anyo    = $horas_mes[$i][0];
			$mes     = $horas_mes[$i][1];
			$consumo = $horas_mes[$i][2] * $demanda_promedio[$mes]; // Horas del mes * demanda_promedio
			//echo "<br>mes: $mes, anyo: $anyo :". $consumo;
		//	echo "<br>mes: $mes, anyo: horas_mes2 :". $horas_mes[$i][2] . ", demprom: ". $demanda_promedio[$mes] ;
			
			inserta_registro_medidor($nombre_tabla, $anyo, $mes, $consumo, $demanda );
		}
	} else {
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/////// PARA LOS DEMAS CASOS, EL CONSUMO SE DETERMINA POR: [horas del mes] X [demanda promedio] - [suma de gridties] //////
		$total_grid_ties    = get_total_gridties($idterreno, $idcaso );

		$grid_tie_registros = get_grid_tie_registros($idterreno, $total_grid_ties );

		for( $i = 0; $i < $total_horas_mes; $i ++ ) {

			$anyo    = $horas_mes[$i][0];
			$mes     = $horas_mes[$i][1];
			$consumo = $horas_mes[$i][2] * $demanda_promedio[$mes] - $grid_tie_registros[ "" . $anyo . "-" . $mes ] ; // Horas del mes * demanda_promedio - SUM(Gridties)

			inserta_registro_medidor($nombre_tabla, $anyo, $mes, $consumo, $demanda );
		}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	////////// TOMA LOS DATOS DE LA COLUMNA demanda_pico_promedio  DE LA TABLA demanda_promedio Y LOS GUARDA EN LA TABLA ce_medidorCFE DE CASO 1 Y LOS REPITE EN LA COLUMNA demanda PARA TODOS LOS MESES Y TODOS LOS ANIOS/////////////////////
	extract(mysql_fetch_array(mysql_query("SELECT ce_tarifas.demanda AS tiene_demanda FROM ce_consumo JOIN ce_tarifas ON ce_consumo.tipo = ce_tarifas.id_tarifa WHERE ce_consumo.secuencia = 'ce_cfe_consumohistorico_".$idterreno."t';")));
			
	if($tiene_demanda == 1){
		$num_casos = count(getCasos_cc_medidor($idterreno));
		
		for($i = 1; $i <= 12; $i++){
		
			extract(mysql_fetch_array(mysql_query("SELECT SUM(demanda) / COUNT(demanda) AS total FROM `ce_cfe_consumohistorico_".$idterreno."t` WHERE mes = ".$i.";")));
			for($j = 1; $j <= $num_casos; $j++){
				mysql_query("UPDATE ce_medidorCFE_".$idterreno."t".$j."c SET demanda = ".$total." WHERE mes = ".$i.";") or die("Error al guardar la tabla 'ce_medidorCFE_".$idterreno."t_1c'".' '.mysql_error());
			}
			
			unset($total_demanda, $cuantos, $promedio_demanda);
			
		}
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	


}

function get_grid_tie_registros($idterreno, $grid_ties ) {

	$alias_tablas   = Array( "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O");
	$total_gridties = count($grid_ties);

	$sql = "SELECT A.ano, A.mes, A.potenciaCS";
	for( $i = 1; $i < $total_gridties; $i ++ ) {

		$sql .= " + " . $alias_tablas[$i] . ".potenciaCS";
	}

	$sql .= " as suma FROM ce_gridtie_" . $idterreno . "t_" . $grid_ties[0] . "g A" ;

	for( $i = 1; $i < $total_gridties; $i ++ ) {

		$sql .= ", ce_gridtie_" . $idterreno . "t_" . $grid_ties[$i] . "g " . $alias_tablas[$i];

	}

	if( $total_gridties > 1 ) {


		$sql .= " WHERE A.ano = B.ano AND A.mes = B.mes";

		for( $i = 2; $i < $total_gridties; $i ++ ) {
			$sql .= " AND A.ano = " . $alias_tablas[$i] . ".ano AND A.mes = " . $alias_tablas[$i] . ".mes";
		}
	}

	 $resultado = mysql_query($sql);
	 $registros = Array();

		if( $resultado ) {

				while( $registro = mysql_fetch_array( $resultado ) ){
					$indice = $registro["ano"] . "-" . $registro["mes"];
					$registros[$indice] = (float) $registro["suma"];
				}

				mysql_free_result( $resultado );
		 }

	return $registros;
}


function get_total_gridties($idterreno, $idcaso ) {

$sql = "SELECT id FROM ce_casos_" . $idterreno . "t WHERE caso = " . $idcaso . " AND id_tipo = 4";

 $resultado = mysql_query($sql);
 $registros = Array();

 	if( $resultado ) {

 			$i = 0;
 			while( $registro = mysql_fetch_array( $resultado ) ){
 			    $registros[$i] = (int) $registro["id"];
 				$i ++;
 			}

 			mysql_free_result( $resultado );
     }

	return $registros;
}

function crear_tabla_medidor($nombre_tabla ) {

$sql = "DROP TABLE IF EXISTS " . $nombre_tabla;
mysql_query($sql);

$sql = "CREATE TABLE ". $nombre_tabla .
	   "(
			 id INT PRIMARY KEY AUTO_INCREMENT,
			 anyo INT(11),
			 mes SMALLINT(2),
			 consumo FLOAT(9,3),
			 demanda FLOAT(9,3))";

	mysql_query($sql);
}

function get_horas_mes($anyo_inicio ) {

 $sql = "SELECT ano, mes, num_horas FROM ce_horasDelMes WHERE ano >= ". $anyo_inicio;

 $resultado = mysql_query($sql);
 $registros = Array();

 	if( $resultado ) {

 			$i = 0;
 			while( $registro = mysql_fetch_array( $resultado ) ){
 			    $registros[$i][0] = (int) $registro["ano"];
 			    $registros[$i][1] = (int) $registro["mes"];
 			    $registros[$i][2] = $registro["num_horas"];
 				$i ++;
 			}

 			mysql_free_result( $resultado );
     }

	return $registros;
}

function get_demanda_promedio($idterreno ) {

	$sql = "SELECT mes, demanda_promedio FROM ce_demandapromedio_t" . $idterreno . "_c1";

	$resultado = mysql_query($sql);
	$demanda = Array();

	if( $resultado ) {

			while( $registro = mysql_fetch_array( $resultado ) ){
			    $demanda[(int) $registro["mes"] ] = (float) $registro["demanda_promedio"];
			}

			mysql_free_result( $resultado );
    }

	return $demanda;
}


function inserta_registro_medidor( $nombre_tabla, $anyo, $mes, $consumo, $demanda ) {

	$sql = "INSERT INTO " . $nombre_tabla . " (anyo, mes, consumo, demanda ) VALUES (" . $anyo . ", " . $mes . ", " . $consumo . ", " . $demanda . ")";

	mysql_query($sql);

}

function getCasos_cc_medidor(  $idterreno ) {

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


/*
//reads ce_consumo, ce_Casos

//make a table called ce_medidorCFE_01caso that is the same format in number of rows as ce_HorasDelMes with ce_medidorCFE_01caso:fetcha taken from aÃ±o y mes

//read the values for ce_consumo for tipo=3 (demanda_promedio_XXcc), if non exists then generate one (if more then one exists, delet both and make a new one).

//To make demanda_promedio_XXcc open ce_CFE_recibo_XXcc, for each value divide the value by the number of hours in that month (from ce_horasDelMes) and find the average of that month in all the years entered.

//fill in the value of lectura_actual from ce_consumo in factores in its corresponding row

//for rows befor the date of the lectura_actual use values from ce_CFE_recibo_XXcc value(i)=value(i+1)-ce_recibe_XXc:fetcha=(i)

//if ce_medidor_01caso ($caso=1) doesn't exist then create it (it is the baseline that we will be modifing)

//for rows after the date of the lectura_actual

if($caso=1){//this is the case where nothing has been done and consumo is the same
	//use value(i+1)= ce_demanda_promedio_XXcc*ce_HorasDelMes:num_horas+value(i)
}
else{ //this is the case where there have been changes in dispositivos and consumo is modified
	//copy ce_medidorCFE_01caso and name with new caso
	//for this caso look for all sequencias that exist and for each row
	$tipo=ce_dispositivos:tipo
 //   if($tipo=1){      //not using this because only grid tie will be included
		//ce_medidorCFE_01caso:valor-ce_fotovoltaico_respuesta_02c*NUM_dispositivo (- because it is producing energi)
   // }
	if($tipo=2){
		//ce_medidorCFE_01caso:valor+ce_lampara_01caso*NUM_dispositivo (+ because it is consuming more energy)
	}
	if($tipo=4){
		//ce_medidorCFE_01caso:valor-ce_gridtie_XXc*NUM_dispositivo (- because it is producing energy)
	}
}
*/


?>