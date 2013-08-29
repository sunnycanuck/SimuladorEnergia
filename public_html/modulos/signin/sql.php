<?php
function query(){
	$tipo = $_REQUEST['tipo_usuario'];
	if($tipo == 2){		
		$query = mysql_fetch_array(mysql_query("SELECT COUNT(id_usuario) AS total FROM ce_usuarios WHERE ce_usuarios.usuario = '".$user."';"));
		$name = $_REQUEST['name'];
		$user = $_REQUEST['user'];
		$tel = $_REQUEST['tel'];
		$url = $_REQUEST['ur'];
		$pass = md5($_REQUEST['pass']);
		$ciudad = $_REQUEST['ciudad'];
		$direccion = $_REQUEST['direccion'];
		$rfc = $_REQUEST['rfc'];
		$fax = $_REQUEST['fax'];
		
		
		if($query['total'] == 0){
			if($_REQUEST['pass'] === $_REQUEST['confirmpass']){
				if(mysql_query("INSERT INTO ce_usuarios (nombre, usuario, tel, url, pass, tipo, ciudad, direccion, rfc, fax, activado) VALUES ('$name', '$user', '$tel', '$url', '$pass', '$tipo', '$ciudad', '$direccion', '$rfc', '$fax', 0);")){
					$url = "index.php?mod=1&ide=1";									
				}else{
					$url = "index.php?mod=1&ide=2";
				}
			}else{
				$url = "index.php?mod=1&ide=3";
			}
		}else{
			$url = "index.php?mod=1&ide=4";
		}
	}
		
	if($tipo == 3){
		
		$name = $_REQUEST['name'];
		$user = $_REQUEST['user'];
		$pass = md5($_REQUEST['pass']);
		
		$query = mysql_fetch_array(mysql_query("SELECT COUNT(id_usuario) AS total FROM ce_usuarios WHERE ce_usuarios.usuario = '$user';"));				
		
		if($query['total'] == 0){
			if($_REQUEST['pass'] === $_REQUEST['confirmpass']){
				if(mysql_query("INSERT INTO ce_usuarios (nombre, usuario, pass, tipo, activado) VALUES ('$name', '$user', '$pass', '$tipo', 0);")){
					$uid = mysql_insert_id();
					if(mysql_query('
						CREATE TABLE ce_casos_'.$uid.'(
							id INT PRIMARY KEY AUTO_INCREMENT,
							caso INT,
							id_dispositivo INT,
							id_tipo INT,
							dispositivos INT,
							dispositivos_variables VARCHAR(45),
							secuencia VARCHAR(45),
							medio_ambiente VARCHAR(45)
						)
					')){
						$url = "index.php?mod=1&ide=1";	
					}else{
						$url = "index.php?mod=1&ide=5";
					}
				}else{
					$url = "index.php?mod=1&ide=2";
				}
			}else{
				$url = "index.php?mod=1&ide=3";
			}
		}else{
			$url = "index.php?mod=1&ide=4";
		}
		
	}
			
	return $url;
	}

?>