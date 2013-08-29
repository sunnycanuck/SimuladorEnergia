<?php
session_start();
require("conexion.php");
$query = mysql_fetch_array(mysql_query("SELECT * FROM ce_usuarios WHERE id_usuario = '".$_SESSION['userid']."';"));
if($_REQUEST['mod']==1 && $_SESSION['log'] == $query['session']){
	header("Location: index.php?msj=ok");
}else{
	header("Location: index.php?mod=50");
}

?>