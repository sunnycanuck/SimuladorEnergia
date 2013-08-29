<?php
date_default_timezone_set("America/Tijuana");
//CONEXION A LA BASE DE DATOS
require("conexion.php");
//INICIO DE SESSION
session_name("calculador");
session_start();

//INCLUSION DE FUNCIONES
require("functions.php");

if(!empty($_REQUEST['mod']) and !empty($_REQUEST['act'])){
  switch($_REQUEST['mod']){  		
   case 1: require("modulos/login/sql.php"); break;
	 case 2: require("modulos/signin/sql.php"); break;
	 case 3: require("modulos/proveedores/sql.php"); break; 
	 case 4: require("modulos/terrenos/sql.php"); break;
	 case 5: require("modulos/recibos/cfe.sql.php"); break;
	 case 6: require("modulos/casos/sql.php"); break;   
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