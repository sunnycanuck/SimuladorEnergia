<?php
function query(){

	switch($_REQUEST['act']){
		case 1:{
			$uid = $_REQUEST['uid'];
			$nombre = $_REQUEST['nombre'];
			$latitude = $_REQUEST['latitude'];
			$longitude = $_REQUEST['longitude'];
			
			$dx = $_REQUEST['dx'];
			$dy = $_REQUEST['dy'];
			$phi = $_REQUEST['phi'];
			$estacion = $_REQUEST['estacion'];

			$query = mysql_query("INSERT INTO ce_terreno (id_usuario, nombre, latitude, longitude, dx, dy, phi, ubicacion, csolar_table, estacionid) VALUES('$uid', '$nombre', '$latitude', '$longitude', '$dx', '$dy', '$phi', '', '', '$estacion');");
			if($query){
				//-------------------CREAR TABLA DE RECIBO PARA ESTE TERRENO -----------------------
				$tid = mysql_insert_id();
				$tabla_recibo = "ce_cfe_consumohistorico_".$tid."t";
				mysql_query('
				CREATE TABLE '.$tabla_recibo.'(
							id INT PRIMARY KEY AUTO_INCREMENT,
							dia INT(10),
							mes INT(10),
							ano INT(10),
							consumo INT(10),
							demanda VARCHAR(45)
						);
					') or die ("Error al generar la tabla del recibo del terreno");
				//-------------------CREAR TABLA DE RECIBO PARA ESTE TERRENO -----------------------

				//-------------------CREAR TABLA DE RECIBO PARA CASOS -----------------------
				mysql_query('
				CREATE TABLE ce_casos_'.$tid.'t(
							id INT PRIMARY KEY AUTO_INCREMENT,
							caso INT(11),
							id_dispositivo INT(11),
							id_tipo INT(11),
							dispositivos INT(11),
							dispositivos_variables TEXT,
							secuencia VARCHAR(45),
							medio_ambiente VARCHAR(45),
							id_pqt_caso INT(11),
							INDEX ( `caso` )
						);
					') or die ("Error al generar la tabla de casos del terreno");

					mysql_query("INSERT INTO ce_casos_".$tid."t (caso, secuencia) VALUES ('1','".$tabla_recibo."');") or die ("Error al insertar datos en la tabla de casos");

				//-------------------CREAR TABLA DE RECIBO PARA CASOS -----------------------
				
				//------------------- INSERTAR REGISTRO EN ce_consumo -----------------------
					$factores = $tabla_recibo.";0;;;1;;;;;0";
					mysql_query("INSERT INTO ce_consumo (id, tipo, factores, variable, secuencia, tipo_recibo) VALUES (NULL, 1, '$factores', 'mes; consumo; demanda', '$tabla_recibo', 'B')") or die ("Error al insertar registro en ce_consumo");;
				//------------------- INSERTAR REGISTRO EN ce_consumo -----------------------
				
				//-------------------CREAR TABLA DE DEMANDA PROMEDIO ------------------------
				$anyo_inicio = date("Y");
				$nombre_tabla = "ce_demandapromedio_t".$tid."_c1";

				mysql_query("DROP TABLE IF EXISTS ". $nombre_tabla) or die("Error al borrar la tabla de demanda promedio.");
				mysql_query(
					"CREATE TABLE ".$nombre_tabla."(
					 id INT PRIMARY KEY AUTO_INCREMENT,
					 mes INT,
					 demanda_promedio FLOAT(9,9));") or die("Error al crear la tabla.");

				$i = 1;
				while($i <= 12){
					$mes_anterior = $i - 1;
					$query = mysql_query("SELECT * FROM ce_cfe_consumohistorico_".$tid."t WHERE mes = $i ORDER BY ano ASC;");
					$consumo = 0;
					$sum_horas_mes = 0;
					while($row = mysql_fetch_array($query)){
						$consumo = $row['consumo'] + $consumo;
						$ano[] = $row['ano'];
						$resultado = mysql_fetch_array(mysql_query("SELECT num_horas FROM ce_horasDelMes WHERE mes = '".$i."' AND ano= '".$row['ano']."';"));
						$resultado2 = mysql_fetch_array(mysql_query("SELECT num_horas FROM ce_horasDelMes WHERE mes = '".$mes_anterior."' AND ano= '".$row['ano']."';"));
						$sum_horas_mes = $resultado['num_horas'] + $resultado2['num_horas'] +  $sum_horas_mes;
					}
					if(!empty($consumo)){

						$cuantos_registros = count($ano);
						$total_meses = $cuantos_registros*2;


						//echo "Suma de consumos ".$consumo.'<br>';
						$consumo_promedio = $consumo / $total_meses;
						//echo "Suma de horas del mes: ".$sum_horas_mes.'<br>';
						$demanda = $consumo_promedio / $sum_horas_mes;
						//echo "Demanda promedio: ".$demanda.'<br>';
						//echo "Mes Actual: ".$i.'<br>';
						//echo "Mes Anterior: ".$mes_anterior.'<br><br>';

						mysql_query("
							INSERT INTO ".$nombre_tabla." (mes, demanda_promedio) VALUES('".$mes_anterior."', '".$demanda."')
						") or die ("Error al guardar en la tabla: ".$nombre_tabla);
						mysql_query("
							INSERT INTO ".$nombre_tabla." (mes, demanda_promedio) VALUES('".$i."', '".$demanda."')
						") or die ("Error al guardar en la tabla: ".$nombre_tabla);


					}
					$i++;
					unset($ano);
				}

				include_once("medidor.php");
				medidor($tid, 1, $anyo_inicio);

				//-------------------CREAR TABLA DE DEMANDA PROMEDIO ------------------------

				//aqui se manda llamar el script para generar el camino solar
				system('python caminoSolar.py '.$tid);
				$url = "index.php?mod=4&msj=13";

			}else{
				$url = "index.php?mod=4&act=1&msj=14";
			}
		}//CASE 1
		;break;
		// -------------- EDITAR TERRENO ---------------
		case 2:{
			$tid = $_REQUEST['tid'];
			$nombre = $_REQUEST['nombre'];
			$latitude = $_REQUEST['latitude'];
			$longitude = $_REQUEST['longitude'];
			$estacion = $_REQUEST['estacion'];
			$dx = $_REQUEST['dx'];
			$dy = $_REQUEST['dy'];

			if(mysql_query("UPDATE ce_terreno SET nombre='$nombre', latitude='$latitude', longitude='$longitude', dx='$dx', dy='$dy', estacionid='$estacion' WHERE id=$tid;")){
				$url = "index.php?mod=4&act=2&tid=".$tid."&msj=15";
			}else{
				$url = "index.php?mod=4&act=2&tid=".$tid."&msj=16";
			}
		}//CASE 2
		;break;
		// -------------- ELIMINAR TERRENO Y SUS TABLAS ---------------
		case 3:{
			$tid = $_REQUEST['tid'];
			$tabla_casos = "ce_casos_".$tid."t";
			$tabla_recibo = "ce_cfe_consumohistorico_".$tid."t";
			$tabla_csolar = "ce_camino_solar_".$tid."t";
			$tabla_demanda_promedio = "ce_demandapromedio_t".$tid."_c1";
			$tabla_costo_consumo = "ce_costodeconsumo_".$tid."t";
			$tabla_medidor = "ce_medidorCFE_".$tid;
			$tabla_gridtie = "ce_gridtie_t".$tid;
			$tabla_fotovoltaico_res ="ce_fotovoltaico_respuesta_t".$tid;

			if(mysql_query("DELETE FROM ce_terreno WHERE id=$tid;")){

				mysql_query("DROP TABLE IF EXISTS ".$tabla_casos.";") or die("Error al borrar la tabla de casos");
				mysql_query("DROP TABLE IF EXISTS ".$tabla_recibo.";") or die("Error al borrar la tabla de recibos");
				mysql_query("DROP TABLE IF EXISTS ".$tabla_csolar.";") or die("Error al borrar la tabla de camino solar");
				mysql_query("DROP TABLE IF EXISTS ".$tabla_demanda_promedio.";") or die("Error al borrar la tabla de demanda promedio");
				mysql_query("DROP TABLE IF EXISTS ".$tabla_costo_consumo.";") or die("Error al borrar la tabla de costo de consumo");

				$qry = mysql_query("SHOW TABLES FROM ".DB_REMOTE." LIKE '".$tabla_medidor."%'");
				while($row = mysql_fetch_array($qry)){
					mysql_query("DROP TABLE IF EXISTS ".$row[0].";") or die("Error al borrar la tabla de medidor");
				}

				$qry = mysql_query("SHOW TABLES FROM ".DB_REMOTE." LIKE '".$tabla_gridtie."%'");
				while($row = mysql_fetch_array($qry)){
					mysql_query("DROP TABLE IF EXISTS ".$row[0].";") or die("Error al borrar la tabla de grid ties en casos");
				}

				$qry = mysql_query("SHOW TABLES FROM ".DB_REMOTE." LIKE '".$tabla_fotovoltaico_res."%'");
				while($row = mysql_fetch_array($qry)){
					mysql_query("DROP TABLE IF EXISTS ".$row[0].";") or die("Error al borrar la tabla de fotovoltaico respuesta");
				}


				$url = "index.php?mod=4&msj=17";
			}else{
				$url = "index.php?mod=4&msj=18";
			}
		}//CASE 3
		;break;
	}//SWITCH

	return $url;
}
?>