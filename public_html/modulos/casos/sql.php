<?php
/**
---------------------------------------------------------------------
MODIFICACIONES
---------------------------------------------------------------------
Clave: HMN01
Autor: Héctor Mora
Fecha: 01/Octubre/2012
Cambio: Cuando se daba de alta un dispositivo fotovoltaico, siempre se tomaban los datos
       del primer registro.
---------------------------------------------------------------------
Clave: HMN02
Autor: Héctor Mora
Fecha: 29/Noviembre/2012
Cambio: Se eliminaron las columnas X Y Z
---------------------------------------------------------------------

*/

function query(){

	$tid = $_REQUEST['tid'];
	$table = $_REQUEST['table'];
	$terreno = $_REQUEST['terreno'];
	switch($_REQUEST['act']){
		case 1:{
			// -------- Insertar Caso Nuevo de Fotovoltaico con Gridtie -----------
			$caso = $_REQUEST['caso'];
			$dispositivo_tipo = $_REQUEST['dispositivo_tipo'];
			$dis = $_REQUEST['dispositivo'];
			$id_tipo = $_REQUEST['id_tipo'];
			$cantidad = $_REQUEST['cantidad'];
			$alt = $_REQUEST['alt'];
			$az = $_REQUEST['az'];
			$equis = 0; //HMN03 $_REQUEST['equis'];
			$ye       = 0; //HMN03 $_REQUEST['ye'];
			$zeta     = 0; //HMN03 $_REQUEST['zeta'];
			$gridtie  = $_REQUEST['gtid'];
			$num_dis  = count($dis);

			$checados = $_REQUEST["checados"]; // HMN01
			$checado = strtok($checados, ","); // HMN01
			$ids     = explode(",", $_REQUEST['ids'] ); // HMN01
			$j = 0;// HMN01

			while( $checado !== false ) { // HMN01
			 if( strlen( $checado ) > 0 ) { // HMN01
				 $i = $checado / 1; // HMN01
				if(!empty($ids[$j])){
					$alt[$i] = $alt[$i]*PI/180;
					$az[$i] = $az[$i]*PI/180;
					//HMN03 mysql_query("INSERT INTO $table (caso, id_dispositivo, id_tipo, dispositivos, dispositivos_variables, secuencia, medio_ambiente) VALUES($caso, $ids[$j], $id_tipo, $cantidad[$i],'$alt[$i];$az[$i];$equis[$i];$ye[$i];$zeta[$i]','', '')") or die("Hubo un error al guardar la informaci&oacute;n, consulte a su administrador."); // HMN01
					$id_tipo = getIdTipo( $ids[$j] );

					mysql_query("INSERT INTO $table (caso, id_dispositivo, id_tipo, dispositivos, dispositivos_variables, secuencia, medio_ambiente) VALUES($caso, $ids[$j], '$id_tipo', $cantidad[$i],'$az[$i];$alt[$i];$equis;$ye;$zeta','', '')") or die("Hubo un error al guardar la informaci&oacute;n, consulte a su administrador."); // HMN01
					$ultimo_disp_agregado = mysql_insert_id().';';
					// ----- Inserta el gridtie --------
					$qry = mysql_query("SELECT * FROM $table WHERE caso = $caso AND id_dispositivo = $gridtie;") or die("Hubo un error al guardar la informaci&oacute;n, consulte a su administrador.");


					if(mysql_num_rows($qry)){
							$row = mysql_fetch_array($qry);
							$last_one = $row['dispositivos_variables'].$ultimo_disp_agregado;
							mysql_query("UPDATE $table SET dispositivos_variables = '$last_one' WHERE id =".$row['id'].";") or die("Error");

					}else{
						mysql_query("INSERT INTO $table (caso, id_dispositivo, id_tipo, dispositivos, dispositivos_variables) VALUES($caso, $gridtie, 4, 1, '$ultimo_disp_agregado');") or die("Error");
					}
					// ----- Inserta el gridtie --------

				}else{
					break;
				}

			 }

			 $checado = strtok(","); // HMN01
			 $j ++; // HMN01
			}

		require("fotovolrespuesta.php");
		require("gridtierespuesta.php");
		crear_tabla_fvrespuesta( $tid, $caso );
		crear_tabla_gtrespuesta( $tid, $caso );
		$anyo_inicio = date("Y");
	    require("medidor.php");
		require( "modulos/casos/demanda_promedio_fn.php");


        demanda_promedio($tid);
		medidor( $tid, $caso, $anyo_inicio );
		include_once("consumo.php");
		require("costo_consumo.php");
		costo_de_consumo($tid, $caso, $anyo_inicio);


			$url = 'index.php?mod=6&act=2&table='.$table.'&terreno='.$terreno.'&dispositivo_tipo='.$dispositivo_tipo.'&tid='.$tid.'&msj=1';

		}//CASE 1
		;break;
		case 2:{
			// ------ Eliminar Dispositivo del Caso Seleccionado -------
			$id = $_REQUEST['id'];

			extract(mysql_fetch_array(mysql_query("SELECT secuencia AS secuencia FROM $table WHERE id = $id;")));
			if(!empty($secuencia))
				mysql_query("DROP TABLE ".$secuencia) or die("Error al borrar la tabla ".$secuencia.". Consulte a su administrador");

			mysql_query("DELETE FROM $table WHERE id = $id;") or die ("Error al eliminar dispositivo de la base de datos, consulte a su administrador.");
			$url = "index.php?mod=6&act=2&table=".$table.'&terreno='.$_REQUEST['terreno'].'&dispositivo_tipo='.$dispositivo_tipo.'&tid='.$tid.'&msj=2';
		}// CASE 2
		break;
		case 3:{


			// ---------- Editar Dispositivo -------------
			$caso = $_REQUEST['caso'];
			$dis = $_REQUEST['dispositivo'];
			$cantidad = $_REQUEST['cantidad'];
			$alt = $_REQUEST['alt'];
			$az = $_REQUEST['az'];
			$equis = 0; //HMN03 $_REQUEST['equis'];
			$ye = 0; //HMN03 $_REQUEST['ye'];
			$zeta = 0; //HMN03 $_REQUEST['zeta'];

			// $az = $az*180/PI; <-- Se corrigió la formula
			//$alt = $alt*180/PI;<-- Se corrigió la formula

			$az = $az*PI/180;
			$alt = $alt*PI/180;

			mysql_query("UPDATE $table SET dispositivos = '$cantidad', dispositivos_variables = '$az;$alt;$equis;$ye;$zeta' WHERE id=$dis;") or die("Hubo un error al guardar la informaci&oacute;n, consulte a su administrador.");
			$url = 'index.php?mod=6&act=2&table='.$table.'&terreno='.$terreno.'&dispositivo_tipo='.$dispositivo_tipo.'&tid='.$tid.'&msj=3';

		require("fotovolrespuesta.php");
		require("gridtierespuesta.php");
		crear_tabla_fvrespuesta( $tid, $caso );
		crear_tabla_gtrespuesta( $tid, $caso );
		$anyo_inicio = date("Y");
	    require("medidor.php");
		require( "modulos/casos/demanda_promedio_fn.php");


        demanda_promedio($tid);
		medidor( $tid, $caso, $anyo_inicio );
		include_once("consumo.php");
		require("costo_consumo.php");
		costo_de_consumo($tid, $caso, $anyo_inicio);


		}// CASE 3
		break;
		case 4:{
			// ----------- Eliminar Caso -------------
			$cid = $_REQUEST['cid'];

			$query = mysql_query("SELECT secuencia FROM $table WHERE caso = $cid AND id_tipo!=4;");
			while($row = mysql_fetch_array($query)){
				mysql_query("DROP TABLE IF EXISTS ".$row['secuencia'].";");
			}
			extract(mysql_fetch_array(mysql_query("SELECT id AS gtid FROM $table WHERE caso = $cid AND id_tipo = 4 LIMIT 1;")));
			//Borra Tabla de Grid Tie
			mysql_query("DROP TABLE IF EXISTS ce_gridtie_".$_REQUEST['tid']."t_".$gtid."g;") or die("Error al eliminar la tabla ce_gridtie_".$_REQUEST['tid']."t_".$gtid."g");
			//Borra Tabla de Medidor
			mysql_query("DROP TABLE IF EXISTS ce_medidorCFE_".$_REQUEST['tid']."t".$cid."c;") or die("ce_medidorCFE_".$_REQUEST['tid']."t".$cid."c");
			//Borra Caso de la tabla de casos
			mysql_query("DELETE FROM $table WHERE caso = $cid;") or die ("Error al eliminar el caso de la base de datos, consulte a su administrador.");
			//Borra Caso de la tabla de costo de consumo

			//HMN01 $fields = mysql_list_fields(DB_LOCAL, 'ce_costodeconsumo_'.$tid.'t');

			$fields = mysql_query("SELECT * FROM ce_costodeconsumo_" . $tid . "t" );

			$columns = mysql_num_fields($fields);
			for ($i = 0; $i < $columns; $i++) {
				$field_array[] = mysql_field_name($fields, $i);
			}

			if (in_array('consumo'.$cid.'', $field_array))
			{
			mysql_query("ALTER TABLE ce_costodeconsumo_".$tid."t DROP consumo".$cid.";") or die ("Error al eliminar columna de costo de consumo, consulte a su administrador.");
			}

			$url = 'index.php?mod=6&act=2&table='.$table.'&terreno='.$terreno.'&dispositivo_tipo='.$dispositivo_tipo.'&tid='.$tid.'&msj=4';
		}// CASE 4
		break;
		case 5:{
			// ---------- Insertar Dispositivos Diferentes a Fotovoltaico ---------
			$caso = $_REQUEST['caso'];
			$dispositivo_tipo = $_REQUEST['dispositivo_tipo'];
			$dis = $_REQUEST['dispositivo'];
			$id_tipo = $_REQUEST['id_tipo'];
			$cantidad = $_REQUEST['cantidad'];

			$lunes_hr_ini = $_REQUEST['lunes_hr_ini'];
			$lunes_min_ini = $_REQUEST['lunes_min_ini'];
			$martes_hr_ini = $_REQUEST['martes_hr_ini'];
			$martes_min_ini = $_REQUEST['martes_min_ini'];
			$miercoles_hr_ini = $_REQUEST['miercoles_hr_ini'];
			$miercoles_min_ini = $_REQUEST['miercoles_min_ini'];
			$jueves_hr_ini = $_REQUEST['jueves_hr_ini'];
			$jueves_min_ini = $_REQUEST['jueves_min_ini'];
			$viernes_hr_ini = $_REQUEST['viernes_hr_ini'];
			$viernes_min_ini = $_REQUEST['viernes_min_ini'];
			$sabado_hr_ini = $_REQUEST['sabado_hr_ini'];
			$sabado_min_ini = $_REQUEST['sabado_min_ini'];
			$domingo_hr_ini = $_REQUEST['domingo_hr_ini'];
			$domingo_min_ini = $_REQUEST['domingo_min_ini'];

			$lunes_hr_fin = $_REQUEST['lunes_hr_fin'];
			$lunes_min_fin = $_REQUEST['lunes_min_fin'];
			$martes_hr_fin = $_REQUEST['martes_hr_fin'];
			$martes_min_fin = $_REQUEST['martes_min_fin'];
			$miercoles_hr_fin = $_REQUEST['miercoles_hr_fin'];
			$miercoles_min_fin = $_REQUEST['miercoles_min_fin'];
			$jueves_hr_fin = $_REQUEST['jueves_hr_fin'];
			$jueves_min_fin = $_REQUEST['jueves_min_fin'];
			$viernes_hr_fin = $_REQUEST['viernes_hr_fin'];
			$viernes_min_fin = $_REQUEST['viernes_min_fin'];
			$sabado_hr_fin = $_REQUEST['sabado_hr_fin'];
			$sabado_min_fin = $_REQUEST['sabado_min_fin'];
			$domingo_hr_fin = $_REQUEST['domingo_hr_fin'];
			$domingo_min_fin = $_REQUEST['domingo_min_fin'];

			$num_dis = count($dis);
			$i=0;
			while($i <= $num_dis){
				if(!empty($dis[$i])){
					$variables =
						'Lun '.$lunes_hr_ini[$i].':'.$lunes_min_ini[$i].'-'.$lunes_hr_fin[$i].':'.$lunes_min_fin[$i].';'.
						'Mar '.$martes_hr_ini[$i].':'.$martes_min_ini[$i].'-'.$martes_hr_fin[$i].':'.$martes_min_fin[$i].';'.
						'Mie '.$miercoles_hr_ini[$i].':'.$miercoles_min_ini[$i].'-'.$miercoles_hr_fin[$i].':'.$miercoles_min_fin[$i].';'.
						'Jue '.$jueves_hr_ini[$i].':'.$jueves_min_ini[$i].'-'.$jueves_hr_fin[$i].':'.$jueves_min_fin[$i].';'.
						'Vie '.$viernes_hr_ini[$i].':'.$viernes_min_ini[$i].'-'.$viernes_hr_fin[$i].':'.$viernes_min_fin[$i].';'.
						'Sab '.$sabado_hr_ini[$i].':'.$sabado_min_ini[$i].'-'.$sabado_hr_fin[$i].':'.$sabado_min_fin[$i].';'.
						'Dom '.$domingo_hr_ini[$i].':'.$domingo_min_ini[$i].'-'.$domingo_hr_fin[$i].':'.$domingo_min_fin[$i].';'
						;
					mysql_query("
						INSERT INTO $table (caso, id_dispositivo, id_tipo, dispositivos, dispositivos_variables, secuencia, medio_ambiente)
						VALUES($caso, $dis[$i], $id_tipo, $cantidad[$i],'$variables','', '')
					")
					or die("Hubo un error al guardar la informaci&oacute;n, consulte a su administrador.");

				}else{
					break;
				}
				$i++;
			}
			$url = 'index.php?mod=6&act=2&table='.$table.'&terreno='.$terreno.'&dispositivo_tipo='.$dispositivo_tipo.'&tid='.$tid.'&msj=5';
		}// CASE 5
		break;
		// ---------- Duplicar Caso -------------
		case 6:{

			$cid = $_REQUEST['cid'];
			extract(mysql_fetch_array(mysql_query("SELECT MAX( `caso` ) AS ultimo FROM $table;")));
			$caso = $ultimo + 1;
			$query = mysql_query("SELECT * FROM $table WHERE caso = $cid;");
			while($row = mysql_fetch_array($query)){
				mysql_query("
					INSERT INTO $table (caso, id_dispositivo, id_tipo, dispositivos, dispositivos_variables, secuencia, medio_ambiente)
					VALUES(".$caso.", ".$row['id_dispositivo'].", ".$row['id_tipo'].", '".$row['dispositivos']."','".$row['dispositivos_variables']."','".$row['secuencia']."', '')
				")
				or die("Hubo un error al guardar la informaci&oacute;n, consulte a su administrador.");
			}
			$url = 'index.php?mod=6&act=2&table='.$table.'&terreno='.$terreno.'&tid='.$tid.'&msj=6';
		}// CASE 6
		break;
		// ---------- Agregar Caso con paquete -------------
		case 7:{

			$idPaquete = $_REQUEST['idPqt'];
			$caso = $_REQUEST['caso'];
			$terrenoid = $_REQUEST['tid'];
			$tablaCasos = "ce_casos_".$tid."t";
			$br = '<br>';
			$gridtie = $_REQUEST['idGridtie'];
			$idDis = $_REQUEST['id_dis'];
			$disTipo = $_REQUEST['disTipo'];
			$alt = $_REQUEST['alt'];
			$az = $_REQUEST['az'];
			$equis = $_REQUEST['equis'];
			$ye = $_REQUEST['ye'];
			$zeta = $_REQUEST['zeta'];
			$numDis = $_REQUEST['numDis'];
			$num_dis = count($idDis)-1;
			$i=0;
			while($i <= $num_dis){
				if(!empty($alt[$i])){
					$alt[$i] = $alt[$i]*PI/180;
					$az[$i] = $az[$i]*PI/180;
					mysql_query("INSERT INTO `".$tablaCasos."` (caso, id_dispositivo, id_tipo, dispositivos, dispositivos_variables, secuencia, medio_ambiente, id_pqt_caso) VALUES(".$caso.", ".$idDis[$i].", ".$disTipo[$i].", ".$numDis[$i].",'".$az[$i].";".$alt[$i].";".$equis[$i].";".$ye[$i].";".$zeta[$i]."','', '', ".$idPaquete.")")
					or die("Hubo un error al guardar la informaci&oacute;n, consulte a su administrador. Codigo 1.");
					$ultimo_disp_agregado = mysql_insert_id().';';
					// ----- Inserta el gridtie --------
					$qry = mysql_query("SELECT * FROM $tablaCasos WHERE caso = $caso AND id_dispositivo = $gridtie;") or die("Hubo un error al guardar la informaci&oacute;n, consulte a su administrador. Codigo 2.");
					if(mysql_num_rows($qry)){
							$row = mysql_fetch_array($qry);
							$last_one = $row['dispositivos_variables'].$ultimo_disp_agregado;
							mysql_query("UPDATE $tablaCasos SET dispositivos_variables = '$last_one' WHERE id =".$row['id'].";") or die("Hubo un error al guardar la informaci&oacute;n, consulte a su administrador. Codigo 3.");
					}else{
						mysql_query("INSERT INTO $tablaCasos (caso, id_dispositivo, id_tipo, dispositivos, dispositivos_variables, id_pqt_caso) VALUES($caso, $gridtie, 4, 1, '$ultimo_disp_agregado', ".$idPaquete.");") or die("Error");
					}
					// ----- Inserta el gridtie --------
				}else{
					break;
				}
				$i++;
			}// while
			
			require("fotovolrespuesta.php");
			require("gridtierespuesta.php");
			crear_tabla_fvrespuesta( $tid, $caso );
			crear_tabla_gtrespuesta( $tid, $caso );
			$anyo_inicio = date("Y");
				require("medidor.php");
			require( "modulos/casos/demanda_promedio_fn.php");
	
	
					demanda_promedio($tid);
			medidor( $tid, $caso, $anyo_inicio );
			include_once("consumo.php");
			require("costo_consumo.php");
			costo_de_consumo($tid, $caso, $anyo_inicio);
			
			$url = 'index.php?mod=6&act=2&table='.$table.'&terreno='.$terreno.'&dispositivo_tipo='.$dispositivo_tipo.'&tid='.$terrenoid.'&msj=1';
		}// CASE 7
		break;
	}//SWITCH

	return $url;
}


function getIdTipo( $id ) {
	$tipo = "" ;

	$result = mysql_query( "SELECT tipo FROM ce_dispositivos WHERE id_dis = " . $id );

	if( $result ){

		if( $rows = mysql_fetch_array( $result ) ) {
			$tipo = $rows["tipo"];
		}

	}

	return $tipo;
}
?>