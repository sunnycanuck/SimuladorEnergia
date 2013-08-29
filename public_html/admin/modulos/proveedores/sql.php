<?php
function query(){
	
	switch($_REQUEST['act']){
		//------------------------ACTIVAR REGISTRO ------------------------
		case 1:{
			$pid = $_REQUEST['pid'];
			if(mysql_query("UPDATE ce_usuarios SET activado=1 WHERE id_usuario=$pid;")){
				$url = "index.php?mod=3&act=2&msj=3";
			}else{
				$url = "index.php?mod=3&act=2&msj=4";
			}			
		}//CASE 1
		;break;
		//------------------------DESACTIVAR REGISTRO ------------------------
		case 2:{
			$pid = $_REQUEST['pid'];
			if(mysql_query("UPDATE ce_usuarios SET activado=0 WHERE id_usuario=$pid;")){
				$url = "index.php?mod=3&act=2&msj=3";
			}else{
				$url = "index.php?mod=3&act=2&msj=4";
			}			
		}//CASE 2
		;break;
		//------------------------BORRAR REGISTRO ------------------------
		case 3:{
			$pid = $_REQUEST['pid'];
			if(mysql_query("DELETE FROM ce_usuarios WHERE id_usuario=$pid;")){
				$url = "index.php?mod=3&act=2&msj=3";
			}else{
				$url = "index.php?mod=3&act=2&msj=5";
			}			
		}//CASE 3
		;break;
		//------------------------AGREGAR REGISTRO ------------------------
		case 4:{
			$nombre = $_REQUEST['nombre'];
			$ciudad = $_REQUEST['ciudad'];
			$direccion = $_REQUEST['direccion'];
			$rfc = $_REQUEST['rfc'];
			$correo = $_REQUEST['mail'];
			$tel = $_REQUEST['lada'].'-'.$_REQUEST['tel'];
			$fax = $_REQUEST['lada_fax'].'-'.$_REQUEST['fax'];
			$url = $_REQUEST['url'];
			$activado = $_REQUEST['activado'];
			$pass = md5($_REQUEST['pass']);
			
			mysql_query("INSERT INTO ce_usuarios (nombre, usuario, tel, url, pass, tipo, ciudad, direccion, rfc, fax, activado) 
											VALUES('".$nombre."', '".$correo."', '".$tel."', '".$url."', '".$pass."', 2, '".$ciudad."', '".$direccion."', '".$rfc."', '".$fax."', ".$activado.");") or die ("Error al guardar el proveedor debido al siguiente error:".'<br>'. mysql_error());
				$url = "index.php?mod=3&act=2&msj=6";
			
		};
		break;
	}//SWITCH
	
	return $url;
}
?>