<?php
function passURL($URL){
 $URI = str_replace ( "%26", "&", $URL);
 $URI = str_replace ( "%3F", "?", $URI);
 $URI = str_replace ( "%3D", "=", $URI);
 $URI = str_replace ( "%23", "#", $URI);
 return $URI;
}

function returnURL($URL){
 $URI = str_replace ( "&", "%26", $URL);
 $URI = str_replace ( "?", "%3F", $URI);
 $URI = str_replace ( "=", "%3D", $URI);
 $URI = str_replace ( "#", "%23", $URI);
 return $URI;
}

function ide(){
	switch($_REQUEST['ide']){
		case 1: echo '<h6 class="msj_ok">Registro agregado satisfactoriamente.</h6>';break;
		case 2: echo '<h6 class="msj_error">Error al crear la base de datos, consulte a su administrador.</h6>';break;
		case 3: echo '<h6 class="msj_error">Las contrase&ntilde;as no coinciden, intentelo nuevamente.</h6>';break;
		case 4: echo '<h6 class="msj_error">El usuario ya se encuentra registrado, intentelo nuevamente.</h6>';break;
		case 5: echo '<h6 class="msj_error">Error al crear registro, consulte al administrador de la p&aacute;gina..</h6>';break;
	}
}
function mensajes(){
	switch($_REQUEST['msj']){
		case 1:	 $msj = '<div class="grid_16 exito mensaje alpha"><p>Caso agregado satisfactoriamente.</p></div>'; break;
		case 2:	 $msj = '<div class="grid_16 exito mensaje alpha"><p>Dispositivo eliminado correctamente de la base de datos.</p></div>'; break;
		case 3:	 $msj = '<div class="grid_16 exito mensaje alpha"><p>Dispositivo editado correctamente.</p></div>'; break;
		case 4:	 $msj = '<div class="grid_16 exito mensaje alpha"><p>Caso eliminado correctamente.</p></div>'; break;
		case 5:	 $msj = '<div class="grid_16 exito mensaje alpha"><p>Dispositivo agregado correctamente.</p></div>'; break;
		case 6:	 $msj = '<div class="grid_16 exito mensaje alpha"><p>Caso duplicado con &eacute;xito.</p></div>'; break;
		case 7:	 $msj = '<div class="grid_16 error mensaje alpha"><p>Error al guardar dispositivo en proveedores.</p></div>'; break;
		case 8:	 $msj = '<div class="grid_16 exito mensaje alpha"><p>Fechas guardadas exitosamente.</p></div>'; break;
		case 9:	 $msj = '<div class="grid_16 error mensaje alpha"><p>Error al guardar fechas de recibo CFE.</p></div>'; break;
		case 10: $msj = '<div class="grid_16 error mensaje alpha"><p>Fechas y/o consumos no coinciden, intentelo de nuevo.</p></div>'; break;
		case 11: $msj = '<div class="grid_16 error mensaje alpha"><p>Error al guardar datos del historico CFE, consulte al administrador de la p&aacute;gina.</p></div>'; break;
		case 12: $msj = '<div class="grid_16 exito mensaje alpha"><p>Registro eliminado correctamente.</p></div>'; break;
		case 13: $msj = '<div class="grid_16 exito mensaje alpha"><p>Generaci&oacute;n de terreno exitoso.</p></div>'; break;
		case 14: $msj = '<div class="grid_16 error mensaje alpha"><p>Error al generar terreno y sus tablas.</p></div>'; break;
		case 15: $msj = '<div class="grid_16 exito mensaje alpha"><p>Terreno actualizado con &eacute;xito.</p></div>'; break;
		case 16: $msj = '<div class="grid_16 error mensaje alpha"><p>Error al editar el terreno.</p></div>'; break;
		case 17: $msj = '<div class="grid_16 exito mensaje alpha"><p>Terreno eliminado exitosamente.</p></div>'; break;
		case 18: $msj = '<div class="grid_16 error mensaje alpha"><p>Error al eliminar terreno y sus tablas.</p></div>'; break;
		case 19: $msj = '<div class="grid_16 exito mensaje alpha"><p>Caso calculado correctamente.</p></div>'; break;
		case 20: $msj = '<div class="grid_16 error mensaje alpha"><p>Error al carlcular caso, intente nuevamente.</p></div>'; break;
		case 21: $msj = '<div class="grid_16 error mensaje alpha"><p>Error al eliminar dispositivo de proveedores.</p></div>'; break;
		case 22: $msj = '<div class="grid_16 error mensaje alpha"><p>Usuario no autorizado para realizar esta modificaci&oacute;n.</p></div>'; break;
		case 23: $msj = '<div class="grid_16 exito mensaje alpha"><p>Datos modificados exitosamente.</p></div>'; break;
		case 24: $msj = '<div class="grid_16 exito mensaje alpha"><p>Paquete agregado exitosamente.</p></div>'; break;
		case 25: $msj = '<div class="grid_16 error mensaje alpha"><p>Error al guaradr el paquete, consulte a su administrador.</p></div>'; break;
		case 26: $msj = '<div class="grid_16 exito mensaje alpha"><p>Paquete eliminado exitosamente.</p></div>'; break;
		case 27: $msj = '<div class="grid_16 error mensaje alpha"><p>Error al eliminar el paquete, consulte a su administrador.</p></div>'; break;
		case 28: $msj = '<div class="grid_16 exito mensaje alpha"><p>Paquete modificacio exitosamente.</p></div>'; break;
		case 29: $msj = '<div class="grid_16 error mensaje alpha"><p>Error al modificar el paquete, consulte a su administrador.</p></div>'; break;

	}
	return '<div class="spacer_10 grid_16"></div>'.$msj.'<div class="spacer_10 grid_16"></div>';
}
function titulo(){
	switch($_REQUEST['mod']){
		case 1: echo "<h1 style='color:#24B5FF;font-size:36px;margin-bottom:0;'>ENERGÍA SIM</h1><h4 style='margin-top:0;margin-bottom:0;'>Calculador Energ&eacute;tico</h4>";break;
		case 2: echo "<h1>Acerca de</h1>";break;
		case 3: echo "<h1>Proveedores</h1>";break;
		case 4: echo "<h1>Terrenos</h1>";break;
		case 5: echo "<h1>Recibos</h1>";break;
		case 6: echo "<h1>Simulaciones</h1>";break;
		default: echo "<h1>ENERGÍA SIM - Calculador Energ&eacute;tico</h1>";break;
	}
}

function javascripts(){
	switch($_REQUEST['mod']){
		case 3: require("js/proveedores.js");break;
		case 4: require("js/terrenos.js");break;
		case 6:{
			if($_REQUEST['act']==10)
				require("js/casos.js");
		}break;
	}
}

function cuentaColumnas(){
	$query = mysql_query("SHOW COLUMNS FROM `ce_costodeconsumo_81t`;");
	$i=0;
	while($row = mysql_fetch_array($query)){
		$i++;
	}
	$cuantos =  $i-3;
	return $cuantos;
}
function botonesAcerca(){
	return $botonesAcerca = '<div align="center" id="botonesAcerca">
		<a href="index.php?mod=2&act=1"><img src="images/users.png" height="32" />Ciudadanos y Negocios</a> <a href="index.php?mod=2&act=2"><img src="images/proveedores.png" height="32" />Proveedores</a> <a href="index.php?mod=2&act=3"><img src="images/instituciones.png" height="32" />Instituciones</a>
	 </div>';
}
define("PI", 3.1416);
define("DB_REMOTE", "energiav_calculado");
define("DB_LOCAL", "calculador");
?>