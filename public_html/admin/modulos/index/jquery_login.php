<?php 
session_start();
require("../../../conexion.php");
$sid = session_id();


$user=htmlspecialchars($_REQUEST['username'],ENT_QUOTES);

$pass=md5($_REQUEST['password']);

$sql=mysql_query("SELECT * FROM ce_usuarios WHERE usuario='".$user."' AND tipo=1;");

$row=mysql_fetch_array($sql);

if(mysql_num_rows($sql)>0){
	if(strcmp($row['pass'],$pass)==0){
		if($query = mysql_query("UPDATE ce_usuarios SET session='$sid' WHERE usuario='$user';")){
			$_SESSION['user']=$row['nombre'];
			$_SESSION['log'] = $sid;
			$_SESSION['mail'] = $row['usuario'];
			$_SESSION['userid'] = $row['id_usuario'];
		
		}
		echo "continue";
		 
	}
	else
		echo "no"; 
}
else
	echo "no";


?>