<?php
//CONEXION A LA BASE DE DATOS
require("conexion.php");
//INICIO DE SESSION
session_name("calculador");
session_start();

//INCLUSION DE FUNCIONES
require("functions.php");

if(!empty($_REQUEST['mod'])){
  switch($_REQUEST['mod']){
   case 1:{
	 	$idterreno = $_REQUEST['tid'];
		$idcaso    = $_REQUEST['cid'];
		require("fotovolrespuesta.php");
		require("gridtierespuesta.php");
		crear_tabla_fvrespuesta( $idterreno, $idcaso );
		crear_tabla_gtrespuesta( $idterreno, $idcaso );
			//crear_tabla_lmrespuesta( $idterreno, $idcaso );// No implementada aún
		$anyo_inicio = date("Y");
	  require("medidor.php");
		require( "modulos/casos/demanda_promedio_fn.php");

    demanda_promedio($idterreno);
		medidor( $idterreno, $idcaso, $anyo_inicio );
		include_once("consumo.php");
		require("costo_consumo.php");
		costo_de_consumo($idterreno, $idcaso, $anyo_inicio);
			$url = "index.php?mod=6&act=2&terreno=".$_REQUEST['terreno']."&table=".$_REQUEST['table']."&tid=".$_REQUEST['tid']."&msj=19";
	 }break;
  }
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