<?php
function query(){
	$tid = $_REQUEST['tid'];
	echo $table = $_REQUEST['table'].'<br>';
	$terreno = $_REQUEST['terreno'];	
	switch($_REQUEST['act']){
		case 1:{
			// -------- Insertar Caso Nuevo de Fotovoltaico con Gridtie -----------
			echo $caso = $_REQUEST['caso'].'<br>';
			$dis = $_REQUEST['dispositivo'].'<br>';
			
			print_r($_REQUEST['dispositivo']);
			echo '<br>';
			$id_tipo = $_REQUEST['id_tipo'].'<br>';
			print_r($id_tipo);
			echo "<br>";
			$cantidad = $_REQUEST['cantidad'].'<br>';
			print_r($cantidad);
			$alt = $_REQUEST['alt'].'<br>';
			$az = $_REQUEST['az'].'<br>';
			$equis = $_REQUEST['equis'].'<br>';
			echo $ye = $_REQUEST['ye'].'<br>';
			echo $zeta = $_REQUEST['zeta'].'<br>';
			echo $gridtie = $_REQUEST['gtid'].'<br>';
			echo $num_dis = count($dis);
			$i=0;
			echo "INSERT INTO `".$table."` (caso, id_dispositivo, id_tipo, dispositivos, dispositivos_variables, secuencia, medio_ambiente) VALUES($caso, $dis[$i], $id_tipo, $cantidad[$i],'$alt[$i];$az[$i];$equis[$i];$ye[$i];$zeta[$i]','', '');".'<br>';
			
			while($i <= $num_dis){
				if(!empty($dis[$i])){
					$alt[$i] = $alt[$i]*PI/180;
					$az[$i] = $az[$i]*PI/180;
					mysql_query("INSERT INTO `".$table."` (caso, id_dispositivo, id_tipo, dispositivos, dispositivos_variables, secuencia, medio_ambiente) VALUES($caso, $dis[$i], $id_tipo, $cantidad[$i],'$alt[$i];$az[$i];$equis[$i];$ye[$i];$zeta[$i]','', '');") or die("Hubo un error al guardar la informaci&oacute;n, consulte a su administrador1.");
					$ultimo_disp_agregado = mysql_insert_id().';';
					// ----- Inserta el gridtie --------
					$qry = mysql_query("SELECT * FROM $table WHERE caso = $caso AND id_dispositivo = $gridtie;") or die("Hubo un error al guardar la informaci&oacute;n, consulte a su administrador2.");
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
				$i++;
			}
			$url = 'index.php?mod=6&act=2&table='.$table.'&terreno='.$terreno.'&dispositivo_tipo='.$dispositivo_tipo.'&tid='.$tid.'&msj=1';
		}//CASE 1
		;break;
		case 2:{
			// ------ Eliminar Dispositivo del Caso Seleccionado -------
			$id = $_REQUEST['id'];
			
			extract(mysql_fetch_array(mysql_query("SELECT secuencia AS secuencia FROM $table WHERE id = $id;")));
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
			$equis = $_REQUEST['equis'];
			$ye = $_REQUEST['ye'];
			$zeta = $_REQUEST['zeta'];
			
			$alt = $alt*180/PI;
			$az = $az*180/PI;
			
			mysql_query("UPDATE $table SET dispositivos = '$cantidad', dispositivos_variables = '$alt;$az;$equis;$ye;$zeta' WHERE id=$dis;") or die("Hubo un error al guardar la informaci&oacute;n, consulte a su administrador.");
			$url = 'index.php?mod=6&act=2&table='.$table.'&terreno='.$terreno.'&dispositivo_tipo='.$dispositivo_tipo.'&tid='.$tid.'&msj=3';
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
			//Borra Caso de la tabla de casos
			mysql_query("ALTER TABLE ce_costodeconsumo_".$tid."t DROP consumo".$cid.";") or die ("Error al eliminar columna de costo de consumo, consulte a su administrador.");
			
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
	}//SWITCH
	
	return $url;
}
?>