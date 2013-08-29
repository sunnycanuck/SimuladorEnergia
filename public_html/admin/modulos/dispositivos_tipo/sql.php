<?php
function query(){
	
	switch($_REQUEST['act']){
		//------------------------ACTIVAR REGISTRO ------------------------
		case 1:{
			$pid = $_REQUEST['pid'];
			if(mysql_query("UPDATE `ce_dispositivos_tipo` SET activado=1 WHERE id_tipo=$pid;")){
				$url = "index.php?mod=2&act=2&msj=3";
			}else{
				$url = "index.php?mod=2&act=2&msj=4";
			}			
		}//CASE 1
		;break;
		//------------------------DESACTIVAR REGISTRO ------------------------
		case 2:{
			$pid = $_REQUEST['pid'];
			if(mysql_query("UPDATE ce_dispositivos_tipo SET activado=0 WHERE id_tipo=$pid;")){
				$url = "index.php?mod=2&act=2&msj=3";
			}else{
				$url = "index.php?mod=2&act=2&msj=4";
			}			
		}//CASE 2
		;break;
		//------------------------BORRAR REGISTRO ------------------------
		case 3:{
			$pid = $_REQUEST['pid'];
			if(mysql_query("DELETE FROM ce_dispositivos_tipo WHERE id_tipo=$pid;")){
				$url = "index.php?mod=2&act=2&msj=3";
			}else{
				$url = "index.php?mod=2&act=2&msj=5";
			}			
		}//CASE 3
		;break;
		//------------------------AGREGAR REGISTRO ------------------------
		case 4:{
			$nombre = 		$_REQUEST['nombre'];
			$factores_Nombres  =	$_REQUEST['factores_Nombres'];
			$factores_Unidades = 	$_REQUEST['factores_Unidades'];
			$variables_Nombres =	$_REQUEST['variables_Nombres'];
			$variables_Unidades = $_REQUEST['variables_Unidades'];
			$medio_Ambiente = 		$_REQUEST['medio_Ambiente'];
			
			if(mysql_query("INSERT INTO ce_dispositivos_tipo (nombre, factores_Nombres, factores_Unidades, variables_Nombres, variables_Unidades, medio_Ambiente) 
											VALUES ('$nombre', '$factores_Nombres', '$factores_Unidades', '$variables_Nombres', '$variables_Unidades', '$medio_Ambiente');")){
				$url = "index.php?mod=2&act=2&msj=1";
			}else{
				$url = "index.php?mod=2&act=2&msj=2";
			}
		}break;//CASE 4
	}//SWITCH
	
	return $url;
}
?>