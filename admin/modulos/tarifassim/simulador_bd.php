<?php

/*
  Desarrollador: Héctor Mora.
  Fecha: 04-Feb-2012
  Descripción: Agrupa las funciones de interacción con la base de datos
  ------------------------------------------------------------------------
  Modificaciones
  ------------------------------------------------------------------------
  Clave: HMN01
  Nombre: Héctor Mora
  Descripción: Se agregó método para obtener todas las tarifas, sin separarlas por mes
  Fecha: 22-Nov-2012
  ------------------------------------------------------------------------
*/

//////////////////////// ABRE UNA CONEXION A LA BASE DE DATOS MYSQL ////////////////////
function abre_conexion_servidor() {

	//////////// DATOS PARA LA CONEXION A LA BASE DE DATOS ////////////
	$conexion_basedatos_servidor = '127.0.0.1';
	$conexion_basedatos_usuario  = 'energiav_calcula';
	$conexion_basedatos_contrasenya = 'c4lcul4d0r!';
	$conexion = null;
	/////////////////////////////////////////////////////////////////////

	return mysql_connect( $conexion_basedatos_servidor, $conexion_basedatos_usuario, $conexion_basedatos_contrasenya );
}
///////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////// SELECCIONA UNA BASE DE DATOS //////////////////////////////////////
function selecciona_base_datos() {
	$nombre_basedatos = 'energiav_calculado';

    return mysql_select_db( $nombre_basedatos );
}
///////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////// LIBERA LA CONEXION AL SERVIDOR ///////////////////////
function cierra_conexion( $conexion ) {
	mysql_close( $conexion );
}
/////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////// CREA TABLA SIM /////////////////////////////////////////////////////
// Con los mismos campos que tarifa
function crea_tabla_SIM( $conexion, $TIPO_TARIFA, $arreglo ) {

$SQL = "CREATE TABLE IF NOT EXISTS `ce_simtarifas_". $TIPO_TARIFA ."` (`id` int(11) NOT NULL auto_increment, `ano` smallint(4) NOT NULL, `mes` smallint(2) NOT NULL";
$total_columnas = count ($arreglo );
for( $i = 0; $i < $total_columnas; $i++ ) {

$SQL .=", `". $arreglo[$i] ."` float NOT NULL";
}


$SQL .= ", PRIMARY KEY  (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1";


 mysql_query( $SQL, $conexion );

}
////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////// OBTIENE DATOS DE LA TABLA horasDelMes ///////////////////////////////////////////////
function get_horasDelMes( $conexion ) {

      $registros   = array();
	  $horasDelMes = mysql_query( "select ano, mes from ce_horasDelMes",$conexion );

      if( $horasDelMes ) {
			while( $resultado = mysql_fetch_array( $horasDelMes ) ) {
				array_push( $registros, array($resultado["ano"], $resultado["mes"]) );
			}
			mysql_free_result( $horasDelMes );
	  }

   return $registros;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////// GENERA REGISTROS EN TABLA SIM //////////////////////////////////////////////
function genera_registros_SIM( $horasDelMes, $TIPO_TARIFA ) {

	$c_arreglo = array();
  	$total_registros = count( $horasDelMes );
	for( $i = 0; $i < $total_registros; $i ++ ) {
		$registro = $horasDelMes[$i];
		inserta_SIM( $TIPO_TARIFA, $registro[0], $registro[1]);

        if( isset( $c_arreglo[ $registro[1] ] ) ) {
			$tmp = $c_arreglo[ $registro[1] ];
			array_push( $tmp, $registro[0] );
			$c_arreglo[ $registro[1] ] = $tmp;

        } else {

        	$c_arreglo[ $registro[1] ] = array( $registro[0] );
        }

	}

	return $c_arreglo;
}

////////////////////////////////////////// CONSULTA AL ARCHIVO TARIFAS //////////////////////////////////
function lee_tabla_tarifas( $conexion, $TIPO_TARIFA ) {

	$c_tarifas = array();

	$tarifa = mysql_query( "select * from ce_tarifas_" . $TIPO_TARIFA, $conexion );

	if( $tarifa ) {

		while( $registro = mysql_fetch_array( $tarifa ) ) {
			$c_tarifas = setTarifa( $c_tarifas, $registro );
		}

		mysql_free_result( $tarifa );

	}

	return $c_tarifas;
}

//HMN01
function lee_tarifas_todas( $conexion, $TIPO_TARIFA, $nombres_de_las_columnas ) {
	$c_tarifas = array();

	$tarifa = mysql_query( "select * from ce_tarifas_" . $TIPO_TARIFA, $conexion );

	if( $tarifa ) {
		$total_columnas = count( $nombres_de_las_columnas );
		$j = 0;

		while( $registro = mysql_fetch_array( $tarifa ) ) {

			for( $i = 0; $i < $total_columnas; $i ++ ) {
			  $c_tarifas[ $i ][ $j ] = $registro[ $nombres_de_las_columnas[ $i ] ];
		    }

		    $j ++;
		}

		mysql_free_result( $tarifa );

	}

	return $c_tarifas;
}

/** Lee un registro capturado **/
function getDatosTabla( $arreglo ) {

	$arr = $arreglo[1];
	$campos_no = "_0_1_2_3_4_5_6_7_8_9_10_id_fecha";

	$arr = $arr[0]->getTarifas();

	$i = 0;
	$campos = array();
	foreach( $arr as $key => $value ) {

		if( strrpos( $campos_no, "_".$key ) === false ) {
			$campos[ $i] = $key;
			$i ++;
		}

	}

	return $campos;
}


function borra_tabla_SIM( $TIPO_TARIFA ) {
	$SQL = "DROP TABLE ce_simtarifas_". $TIPO_TARIFA;
	mysql_query($SQL);
}

function inserta_SIM( $TIPO_TARIFA, $anyo, $mes) {
	$SQL = "INSERT INTO ce_simtarifas_".$TIPO_TARIFA." (ano, mes) VALUES (". $anyo .", ". $mes .")";
	mysql_query($SQL);
}



function actualizaSIM( $TIPO_TARIFA, $anyo, $mes, $arreglo_columnas, $valores_columnas ){

	$fecha = $anyo . '-' . $mes;

	$SQL = "UPDATE ce_simtarifas_".$TIPO_TARIFA." set ". $arreglo_columnas[0]. " = " . $valores_columnas[0];

	$total_columnas = count( $valores_columnas );

	for( $i = 1; $i < $total_columnas; $i ++ ) {
		$SQL .= ", " .$arreglo_columnas[$i] . "= ". $valores_columnas[$i];
	}

	$SQL .= " WHERE ano = $anyo AND mes = $mes";

	 mysql_query($SQL);
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////// FUNCIONES DE MENSAJES DE ERROR ///////////////////////////////
function imprime_error_conexion_base_datos() {
    echo "<H1>No es posible conectarse a la base de datos</H1><BR />\n";
    echo "<H2>Favor de revisar el nombre de la base de datos</H2>\n";
}

function imprime_error_conexion_servidor() {
	echo "<H1>No es posible realizar una conexi&oacute;n al servidor de bases de datos </H1><BR />\n";
	echo "<H2>Favor de revisar el ip o nombre de host, usuario y contraseña para accesar</H2>\n";
}
///////////////////////////////////////////////////////////////////////////////////////////////


?>