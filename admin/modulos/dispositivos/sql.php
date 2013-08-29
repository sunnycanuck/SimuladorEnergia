<?php
function query(){
	
	switch($_REQUEST['act']){
		//------------------------ACTIVAR REGISTRO ------------------------
		case 1:{
			$pid = $_REQUEST['pid'];
			if(mysql_query("UPDATE ce_dispositivos SET activado=1 WHERE id_dis=$pid;")){
				$url = "index.php?mod=2&act=1&msj=3";
			}else{
				$url = "index.php?mod=2&act=1&msj=4";
			}			
		}//CASE 1
		;break;
		//------------------------DESACTIVAR REGISTRO ------------------------
		case 2:{
			$pid = $_REQUEST['pid'];
			if(mysql_query("UPDATE ce_dispositivos SET activado =0 WHERE id_dis=$pid;")){
				$url = "index.php?mod=2&act=1&msj=3";
			}else{
				$url = "index.php?mod=2&act=1&msj=4";
			}
		}//CASE 2
		;break;
		//------------------------BORRAR REGISTRO ------------------------
		case 3:{
			$pid = $_REQUEST['pid'];
			if(mysql_query("DELETE FROM ce_dispositivos WHERE id_dis=$pid;")){
				$url = "index.php?mod=2&act=1&msj=3";
			}else{
				$url = "index.php?mod=2&act=1&msj=5";
			}			
		}//CASE 3
		;break;
		//------------------------AGREGAR REGISTRO ------------------------
		case 4:{
			$dispositivo_tipo = $_REQUEST['dispositivo_tipo'];
			$marca =  $_REQUEST['marca'];
			$modelo =	$_REQUEST['modelo'];
			$precio_dispositivo = $_REQUEST['precio_dispositivo'];
			$precio_instalacion =	$_REQUEST['precio_instalacion'];
			$proveedor = $_REQUEST['proveedor'];
			$factores = $_REQUEST['factores'];
			$variables = $_REQUEST['variables'];
				
			
			if(mysql_query("INSERT INTO ce_dispositivos (tipo, marca, modelo, precio_dispositivo, precio_instalacion, proveedor, factores, variables) 
											VALUES ($dispositivo_tipo, '$marca', '$modelo', '$precio_dispositivo', '$precio_instalacion', '$proveedor', '$factores', '$variables');")){
				$url = "index.php?mod=2&act=1&msj=1";
			}else{
				$url = "index.php?mod=2&act=1&msj=2";
			}
		}break;//CASE 4
	}//SWITCH
	
	return $url;
}
?>