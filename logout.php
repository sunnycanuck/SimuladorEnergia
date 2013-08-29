<?php
	session_start();
	require("conexion.php");
	mysql_query("UPDATE ce_usuarios SET session = 0 WHERE usuario = '".$_SESSION['mail']."' AND id_usuario = '".$_SESSION['userid']."'");
	session_destroy();
	header("Location: index.php");
?>