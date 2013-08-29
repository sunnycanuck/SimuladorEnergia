<?php
/*
--------------------------------------------------------------------------
MODIFICACIONES:
--------------------------------------------------------------------------
Clave: HMN01
Autor: Héctor Mora
Fecha: 29-Nov-2012
Descripción del cambio: Se calcula si es bimestral o mensual el recibo.
--------------------------------------------------------------------------
Clave: HMN02
Autor: Héctor Mora
Fecha: 27-Feb-2013
Descripción del cambio: Se eliminan campos innecesarios No. de medidor, No servicio
--------------------------------------------------------------------------
*/

function query(){

	switch($_REQUEST['act']){
		case 1:{ //----------------------------------------------------------------------------------------------------------------------------------------------------------------------------

            ////////////////////////// IDENTIFICA EL TIPO DE RECIBO  B = BIMESTRAL ; M = MENSUAL (HMN01) ////////////////////
			$desde_fecha = explode('-', $_REQUEST['desde'] );
			$hasta_fecha = explode('-', $_REQUEST['hasta'] );

			$total_fecha = count( $desde_fecha );
			$tipo_recibo = 'B';

			if( count( $desde_fecha ) > 1 && count( $hasta_fecha ) > 1 ) {

				$dif =  $hasta_fecha[1] - $desde_fecha[1] ;

				if( $dif < 0 ) {
					$hasta_fecha[1] = $hasta_fecha[1] + 12;
					$dif = $hasta_fecha[1] - $desde_fecha[1];
				}

				if( $dif < 2 ) {
					$tipo_recibo = 'M';
				}
			}
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			$consumo = $_REQUEST['consumo_historico'];
			$fecha = $_REQUEST['historial'];
			$consumo_watts = $_REQUEST['consumo_watts'];
			$tipo = $_REQUEST['tipo'];
			$demanda = $_REQUEST['demanda'];
			//HMN02 $factores = $_REQUEST['terreno'].';'.$_REQUEST['no_servicio'].';'.$_REQUEST['desde'].';'.$_REQUEST['hasta'].';'.$_REQUEST['tarifa'].';'.$_REQUEST['total_pagar'].';'.$_REQUEST['consumo_watts'].';'.$demanda.';'.$_REQUEST['lectura'].';'.$_REQUEST['medidor'];
			$factores = $_REQUEST['terreno'].';0;'.$_REQUEST['desde'].';'.$_REQUEST['hasta'].';'.$_REQUEST['tarifa'].';'.$_REQUEST['total_pagar'].';'.$_REQUEST['consumo_watts'].';'.$demanda.';'.$_REQUEST['lectura'].';0';
			$variable = "mes; consumo; demanda";
			$secuencia = $_REQUEST['terreno'];
			$table = $_REQUEST['terreno'];

			mysql_query("INSERT INTO ce_consumo (tipo, factores, variable, secuencia, tipo_recibo)
											VALUES ('$tipo', '$factores', '$variable', '$secuencia', '$tipo_recibo');");

			//mysql_query("INSERT INTO ".$table." (dia, mes, ano, consumo) VALUES ('".$dia."', '".$mes."', '".$ano."', '".$consumo[$k]."')") or die("Error al guardar recibo de CFE");   *****---------INSERTA PRIMER REGISTRO EN HISTORICO

			$consumo = $_REQUEST['consumo_historico'];
			$fecha = $_REQUEST['historial'];

			if(!empty($consumo) && !empty($fecha)){
				$i=0;
				foreach($_REQUEST['consumo_historico'] as $consumos){
					if(!empty($consumos)){
						$i++;
					}
				}//foreach
				$j=0;
				foreach($_REQUEST['historial'] as $fechas){
					if(!empty($fechas)){
						$j++;
					}
				}//foreach
				if($j !=0 && $i !=0){
					if($j == $i){
						for($k=0; $k<=$i-1; $k++){
							if(!empty($fecha[$k]) and !empty($consumo[$k])){
								//query para guardar a la base de datos de historico
								$date_par = explode('-', $fecha[$k]);
								$dia = $date_par[2];
								$mes = $date_par[1];
								$ano = $date_par[0];
								mysql_query("INSERT INTO ".$table." (dia, mes, ano, consumo) VALUES ('".$dia."', '".$mes."', '".$ano."', '".$consumo[$k]."')") or die("Error al guardar recibo de CFE");

								$url = "index.php?mod=5&act=2&msj=8&terreno=".$_REQUEST['id_terreno']."&tarifa=".$_REQUEST['tarifa'];
							}//if
						}//for
					}// if $j == $i
					else{
						$url = "index.php?mod=5&act=1&msj=9";
					}
				}//ij !=0
				else{

					$date_par = explode('-', $_REQUEST['desde']);
					$dia = $date_par[2];
					$mes = $date_par[1];
					$ano = $date_par[0];
					mysql_query("INSERT INTO $table (dia, mes, ano, consumo) VALUES ('".$dia."', '".$mes."', '".$ano."', '".$consumo_watts."')") or die ("Error al guardar recibo de CFE");
					$url = "index.php?mod=5&act=2&msj=8&terreno=".$_REQUEST['id_terreno']."&tarifa=".$_REQUEST['tarifa'];
				}
			}
			else{

				$date_par = explode('-', $_REQUEST['desde']);
				$dia = $date_par[2];
				$mes = $date_par[1];
				$ano = $date_par[0];
				mysql_query("INSERT INTO $table (dia, mes, ano, consumo) VALUES ('".$dia."', '".$mes."', '".$ano."', '".$consumo_watts."')") or die ("Error al guardar recibo de CFE");
				$url = "index.php?mod=5&act=2&msj=8&terreno=".$_REQUEST['id_terreno']."&tarifa=".$_REQUEST['tarifa'];
			}


		}//CASE 1 -------------------------------------------------------------------------------------------------------------------------------------------
		;break;
		// ELIMINAR REGISTRO DEL HISTORICO DE CONSUMO -------------------------------------------------------------------------------------------------------
		case 2:{
			$table = "ce_cfe_consumohistorico_".$_REQUEST['tid']."t";
			$id = $_REQUEST['rid'];
			mysql_query("DELETE FROM $table WHERE id = $id") or die(mysql_error());
			$url = "historicoConsumo.php?terreno=".$_REQUEST['terreno']."&tid=".$_REQUEST['tid'];// terminar aqui....
			if($_REQUEST['tipoTarifa']==9 || $_REQUEST['tipoTarifa']==11){
				$url .='&tipoTarifa='.$_REQUEST['tipoTarifa'];
			}
		}//CASE 2 -------------------------------------------------------------------------------------------------------------------------------------------
		break;
		// ELIMINAR REGISTRO DEL HISTORICO DE CONSUMO -------------------------------------------------------------------------------------------------------
		case 3:{
			$rid = $_REQUEST['rid'];
			$row = mysql_fetch_array(mysql_query("SELECT * FROM `ce_consumo` WHERE id=$rid;"));
			$factores_arreglo = explode(";", $row['factores']);
			$factores = $factores_arreglo[0].';'.$_REQUEST['no_servicio'].';'.$factores_arreglo[2].';'.$factores_arreglo[3].';'.$factores_arreglo[4].';'.$factores_arreglo[5].';'.$factores_arreglo[6].';'.$factores_arreglo[7].';'.$factores_arreglo[8].';'.$_REQUEST['medidor'];
			mysql_query("UPDATE ce_consumo SET factores = '".$factores."' WHERE id=".$rid.";") or die("Error al insertar factores en la tabla de Consumo, contacte a su administrador.");
			$url = "index.php?terreno=".$_REQUEST['terreno']."&tid=".$_REQUEST['tid']."&msj=2";
		}//CASE 3 -------------------------------------------------------------------------------------------------------------------------------------------
		break;
		// MODIFICAR TARIFA EN ce_consumo -------------------------------------------------------------------------------------------------------------------
		case 4:{
			$tid = $_REQUEST['tid'];
			$tabla = $_REQUEST['tabla'];
			extract(mysql_fetch_array(mysql_query("SELECT factores AS datos FROM ce_consumo WHERE secuencia = '".$tabla."'")));
			$factores_arreglo = explode(";", $datos);
			$factores = $factores_arreglo[0].';'.$factores_arreglo[1].';'.$factores_arreglo[2].';'.$factores_arreglo[3].';'.$tid.';'.$factores_arreglo[5].';'.$factores_arreglo[6].';'.$factores_arreglo[7].';'.$factores_arreglo[8].';'.$factores_arreglo[9];
			
			mysql_query("UPDATE ce_consumo SET tipo = '".$tid."', factores = '".$factores."' WHERE secuencia = '".$tabla."';") or die("Error al modificar la tarifa en la tabla de Consumo, contacte a su administrador.");
			$url = "index.php?mod=5&msg=2";
		}//CASE 4 -------------------------------------------------------------------------------------------------------------------------------------------
		break;
		// INSERTAR HISTORICO EN ce_cfe_consumohistorico_(id terreno)t --------------------------------------------------------------------------------------
		case 5:{
			$table = $_REQUEST['table'];
			$consumo = $_REQUEST['consumo_historico'];
			$fecha = $_REQUEST['historial'];
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			if(!empty($_REQUEST['demanda'])){
				$demanda = $_REQUEST['demanda'];
				if(!empty($consumo) && !empty($fecha)){
					$i=0;
					foreach($_REQUEST['consumo_historico'] as $consumos){
						if(!empty($consumos)){
							$i++;
						}
					}//foreach
					$j=0;
					foreach($_REQUEST['historial'] as $fechas){
						if(!empty($fechas)){
							$j++;
						}
					}//foreach
					$l = 0;
					foreach($_REQUEST['demanda'] as $demandas){
						if(!empty($demandas)){
							$l++;
						}
					}//foreach
					
					if($j !=0 && $i !=0 && $l!=0){
						if(($j == $i) && ($i == $l) && ($j == $l)){
							for($k=0; $k<=$i-1; $k++){
								if(!empty($fecha[$k]) and !empty($consumo[$k])){
									//query para guardar a la base de datos de historico
									$date_par = explode('-', $fecha[$k]);
									$mes = $date_par[1];
									$ano = $date_par[0];
									mysql_query("INSERT INTO `".$table."` (dia, mes, ano, consumo, demanda) VALUES ('1', '".$mes."', '".$ano."', '".$consumo[$k]."', '".$demanda[$k]."')") or die("Error al guardar recibo de CFE");	
									$url = "historicoConsumo.php?terreno=".$_REQUEST['terreno']."&tid=".$_REQUEST['tid']."&tipoTarifa=".$_REQUEST['tipoTarifa'];							
								}//if
							}//for
						}// if $j == $i
						else{
							$url = "index.php?mod=5&msg=3";
						}
					}//ij !=0
					else{	
						$url = "index.php?mod=5&msg=3";
					}
				}
				else{
					$url = "index.php?mod=5&msg=3";
				}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			}else{

				if(!empty($consumo) && !empty($fecha)){
					$i=0;
					foreach($_REQUEST['consumo_historico'] as $consumos){
						if(!empty($consumos)){
							$i++;
						}
					}//foreach
					$j=0;
					foreach($_REQUEST['historial'] as $fechas){
						if(!empty($fechas)){
							$j++;
						}
					}//foreach
					
					if($j !=0 && $i !=0){
						if($j == $i){
							for($k=0; $k<=$i-1; $k++){
								if(!empty($fecha[$k]) and !empty($consumo[$k])){
									//query para guardar a la base de datos de historico
									$date_par = explode('-', $fecha[$k]);
									$mes = $date_par[1];
									$ano = $date_par[0];
									mysql_query("INSERT INTO `".$table."` (dia, mes, ano, consumo) VALUES ('1', '".$mes."', '".$ano."', '".$consumo[$k]."')") or die("Error al guardar recibo de CFE");								
								}//if
							}//for
						}// if $j == $i
						else{
							$url = "index.php?mod=5&msg=3";
						}
					}//ij !=0
					else{
	
						$date_par = explode('-', $_REQUEST['desde']);
						$mes = $date_par[1];
						$ano = $date_par[0];
						mysql_query("INSERT INTO `$table` (dia, mes, ano, consumo) VALUES ('1', '".$mes."', '".$ano."', '".$consumo_watts."')") or die ("Error al guardar recibo de CFE");					
					}
				}
				else{
					$date_par = explode('-', $_REQUEST['desde']);
					$mes = $date_par[1];
					$ano = $date_par[0];
					mysql_query("INSERT INTO `$table` (dia, mes, ano, consumo) VALUES ('1', '".$mes."', '".$ano."', '".$consumo_watts."')") or die ("Error al guardar recibo de CFE");				
				}
				$url = "historicoConsumo.php?terreno=".$_REQUEST['terreno']."&tid=".$_REQUEST['tid'];
			}// else $demanda
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		}//CASE 5 ---------------------------------------------------------------------------------------------------------------------------------
		break;
		

	}//SWITCH


	return $url;
}
?>