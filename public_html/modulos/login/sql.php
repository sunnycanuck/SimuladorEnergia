<?php
function query(){
	
	switch($_REQUEST['act']){
		case 1:{
			$name = $_REQUEST['name'];
			$user = $_REQUEST['user'];
			$pass = $_REQUEST['pass'];
			
			if($_REQUEST['pass'] === $_REQUEST['confirmpass']){
				if(mysql_query("INSERT INTO ce_usuarios (nombre, usuario, pass, tipo) VALUES ('$name', '$user', '$pass', '1');")){
					$url = "index.php?mod=10&id=1";
				}else{
					$url = "index.php?mod=10&id=2";
				}
			}else{
				$url = "index.php?mod=10&id=3";
			}
		}//CASE 1
		;break;
	}//SWITCH
	
	return $url;
}
?>