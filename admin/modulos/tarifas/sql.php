<?php
function query(){
	
	$table = "ce_tarifas_".$_REQUEST['tarifa'];
	$fecha = $_REQUEST['fecha'];
	
	switch($_REQUEST['act']){		
		case 1:{
		/* ---------------------------- ALTA DE TARIFAS 1, 1A, 1B, 1C, 1D	---------------------------- */
			$basico_bajo 			= $_REQUEST['basico_bajo'];
			$intermedio_bajo 	= $_REQUEST['intermedio_bajo'];
			$basico_alto 			= $_REQUEST['basico_alto'];
			$intermedio_alto 	= $_REQUEST['intermedio_alto'];
			$exedente_alto 		= $_REQUEST['exedente_alto'];
			
			mysql_query("
				INSERT INTO `".$table."` (fecha, basico_Bajo, intermedio_Bajo, basico_Alto, intermedio_Alto, exedente_Alto) 
				VALUES('$fecha', '$basico_bajo', '$intermedio_bajo', '$basico_alto', '$intermedio_alto', '$exedente_alto')"
			) or die('Hubo un error en el query, favor de regresar e intentarlo nuevamente.<br>'.mysql_error());
												 
			$url = 'index.php?mod=5&act=1&tarifa='.$_REQUEST['tarifa'].'&msj=1';	
			
		}break;
		/* ---------------------------- ALTA DE TARIFAS 1, 1A, 1B, 1C, 1D	---------------------------- */
		case 2:{
		/* ---------------------------- ALTA DE TARIFA 1E	---------------------------- */
			
			$basico_bajo 			= $_REQUEST['basico_bajo'];
			$intermedio_bajo 	= $_REQUEST['intermedio_bajo'];
			$exedente_bajo 		= $_REQUEST['exedente_bajo'];
			$basico_alto 			= $_REQUEST['basico_alto'];
			$intermedio_alto 	= $_REQUEST['intermedio_alto'];
			$exedente_alto 		= $_REQUEST['exedente_alto'];
			
			mysql_query("INSERT INTO `".$table."` (fecha, basico_Bajo, intermedio_Bajo, exedente_Bajo, basico_Alto, intermedio_Alto, exedente_Alto) 
												 VALUES('$fecha', '$basico_bajo', '$intermedio_bajo', '$exedente_bajo', '$basico_alto', '$intermedio_alto', '$exedente_alto')") or die('Hubo un error en el query, favor de regresar e intentarlo nuevamente.<br>'.mysql_error());
												 
			$url = 'index.php?mod=5&act=1&tarifa='.$_REQUEST['tarifa'].'&msj=1';	
		}break;
		case 3:{
		/* ---------------------------- ALTA DE TARIFA 1F	---------------------------- */
			
			$basico_bajo 			= $_REQUEST['basico_bajo'];
			$intermedio_bajo 	= $_REQUEST['intermedio_bajo'];
			$exedente_bajo 		= $_REQUEST['exedente_bajo'];
			$basico_alto 			= $_REQUEST['basico_alto'];
			$intermedio_alto 	= $_REQUEST['intermedio_alto'];
			$alto_alto				= $_REQUEST['alto_alto'];
			$exedente_alto 		= $_REQUEST['exedente_alto'];
			
			mysql_query("INSERT INTO `".$table."` (fecha, basico_Bajo, intermedio_Bajo, exedente_Bajo, basico_Alto, intermedio_Alto, alto_Alto, exedente_Alto) 
												 VALUES('$fecha', '$basico_bajo', '$intermedio_bajo', '$exedente_bajo', '$basico_alto', '$intermedio_alto', '$alto_alto', '$exedente_alto')") or die('Hubo un error en el query, favor de regresar e intentarlo nuevamente.<br>'.mysql_error());
												 
			$url = 'index.php?mod=5&act=1&tarifa='.$_REQUEST['tarifa'].'&msj=1';	
		}break;
		/* ---------------------------- ALTA DE TARIFA 1F	---------------------------- */
		case 4:{
		/* ---------------------------- ALTA DE TARIFA 2	---------------------------- */
			
			$basico 		= $_REQUEST['basico'];
			$intermedio = $_REQUEST['intermedio'];
			$exedente 	= $_REQUEST['exedente'];
			$carga_fijo = $_REQUEST['carga_fijo'];			
			
			mysql_query("INSERT INTO `".$table."` (fecha, Basico, Intermedio, Exedente, carga_fijo) 
												 VALUES('$fecha', '$basico', '$intermedio', '$exedente', '$carga_fijo')") or die('Hubo un error en el query, favor de regresar e intentarlo nuevamente.<br>'.mysql_error());
												 
			$url = 'index.php?mod=5&act=1&tarifa='.$_REQUEST['tarifa'].'&msj=1';	
		}break;
		/* ---------------------------- ALTA DE TARIFA 2	---------------------------- */
		case 5:{
		/* ---------------------------- ALTA DE TARIFA 3	---------------------------- */
			
			$demanda = $_REQUEST['demanda'];
			$consumo = $_REQUEST['consumo'];
			
			mysql_query("INSERT INTO `".$table."` (fecha, demanda, consumo) 
												 VALUES('$fecha', '$demanda', '$consumo')") or die('Hubo un error en el query, favor de regresar e intentarlo nuevamente.<br>'.mysql_error());
												 
			$url = 'index.php?mod=5&act=1&tarifa='.$_REQUEST['tarifa'].'&msj=1';	
		}break;
		/* ---------------------------- ALTA DE TARIFA 3	---------------------------- */
		case 6:{
		/* ---------------------------- ALTA DE TARIFA DAC	---------------------------- */
			
			$verano 		= $_REQUEST['verano'];
			$invierno 	= $_REQUEST['invierno'];
			$carga_fijo = $_REQUEST['carga_fijo'];
			
			mysql_query("INSERT INTO `".$table."` (fecha, verano, invierno, carga_fijo) 
												 VALUES('$fecha', '$verano', '$invierno', '$carga_fijo')") or die('Hubo un error en el query, favor de regresar e intentarlo nuevamente.<br>'.mysql_error());
												 
			$url = 'index.php?mod=5&act=1&tarifa='.$_REQUEST['tarifa'].'&msj=1';	
		}break;
		/* ---------------------------- ALTA DE TARIFA DAC	---------------------------- */
		case 7:{
		/* ---------------------------- ALTA DE TARIFA OM	---------------------------- */
			
			$peso_demanda = $_REQUEST['peso_demanda'];
			$peso_kwh 		= $_REQUEST['peso_kwh'];
			
			mysql_query("INSERT INTO `".$table."` (fecha, peso_kw_demanda_max, peso_kwh) 
												 VALUES('$fecha', '$peso_demanda', '$peso_kwh')") or die('Hubo un error en el query, favor de regresar e intentarlo nuevamente.<br>'.mysql_error());
												 
			$url = 'index.php?mod=5&act=1&tarifa='.$_REQUEST['tarifa'].'&msj=1';	
		}break;
		/* ---------------------------- ALTA DE TARIFA OM	---------------------------- */
		case 8:{
		/* ---------------------------- ALTA DE TARIFA HM	---------------------------- */
			
			$cargo_kw_fact 		= $_REQUEST['cargo_kw_fact'];
			$cargo_ener_punta = $_REQUEST['cargo_ener_punta'];
			$cargo_ener_inter = $_REQUEST['cargo_ener_inter'];
			$cargo_ener_base 	= $_REQUEST['cargo_ener_base'];
			
			mysql_query("INSERT INTO `".$table."` (fecha, cargo_x_kw_de_demanda_facturable, cargo_x_kw_hora_de_energia_de_punta, cargo_x_kw_hora_de_energia_intermedia, cargo_x_kw_hora_de_energia_base) 
												 VALUES('$fecha', '$cargo_kw_fact', '$cargo_ener_punta', '$cargo_ener_inter', '$cargo_ener_base')") or die('Hubo un error en el query, favor de regresar e intentarlo nuevamente.<br>'.mysql_error());
												 
			$url = 'index.php?mod=5&act=1&tarifa='.$_REQUEST['tarifa'].'&msj=1';	
		}break;
		/* ---------------------------- ALTA DE TARIFA HM	---------------------------- */
		case "e":{
		/* ---------------------------- ELIMINAR REGISTRO	---------------------------- */
			$table 	= $_REQUEST['table'];
			$id 		= $_REQUEST['id'];
			$tarifa = $_REQUEST['tarifa'];
			
			mysql_query("DELETE FROM $table WHERE id='$id';") or die('Hubo un error en el query, favor de regresar e intentarlo nuevamente.');												 
			$url = 'index.php?mod=5&act=1&tarifa='.$tarifa;
		}break;
		/* ---------------------------- ELIMINAR REGISTRO	---------------------------- */
		
	}// SWITCH
		
	return $url;
}
?>