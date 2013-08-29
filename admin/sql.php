<?php
date_default_timezone_set("America/Tijuana");
//CONEXION A LA BASE DE DATOS
require("../conexion.php");
//INICIO DE SESSION
session_name("calculador");
session_start();

//INCLUSION DE FUNCIONES
require("../functions.php");

if(!empty($_REQUEST['mod']) and !empty($_REQUEST['act'])){
  switch($_REQUEST['mod']){  		
   case 1: require("modulos/proveedores/sql.php"); break;
	 case 2: require("modulos/dispositivos/sql.php"); break;
	 case 3: require("modulos/dispositivos_tipo/sql.php"); break;
	 case 4: require("modulos/fechas/sql.php"); break;
	 case 5: require("modulos/tarifas/sql.php"); break;   
  }
	
	$url = query();
	if(empty($url) and $url == "")
		$url = passURL($_REQUEST['url']);
}
else if($_SERVER['HTTP_REFERER'] == "") 
	$url = "index.php"; 
else
 $url = $_SERVER['HTTP_REFERER'];

//CERRAR CONEXION
mysql_close($conn);
header('Location: ' . $url);

?>