<?php
/*-----------------------------------------------------------------------------
 Modificaciones
 ------------------------------------------------------------------------------
 Clave: HMN01
 Autor: Héctor Mora
 Descripción: Se cambiaron las leyendas Paquetes --> Paquetes completos, Dispositivos --> Dispositivos individuales
 Fecha: 02-Noviembre-2012
 -------------------------------------------------------------------------------
*/

	if(!empty($_REQUEST['pqtOdis'])){
		switch($_REQUEST['pqtOdis']){
			case 1: require("modulos/casos/editar_pqt.php");break;
			case 2: require("modulos/casos/editar_dis.php");break;
		}

?>

<?php
	}else{
		$query = extract(mysql_fetch_array(mysql_query("SELECT COUNT(id) AS total FROM ".$_REQUEST['table']." WHERE caso =".$_REQUEST['caso'].";")));
		
?>
			<h2>Seleccione paquete o dispositivo</h2>
			<form action="index.php?mod=6&act=3" method="post">
				<input type="hidden" name="table" value="<?php echo $_REQUEST['table']; ?>" />
				<input type="hidden" name="caso" value="<?php echo $_REQUEST['caso']; ?>" />
				<input type="hidden" name="tid" value="<?php echo $_REQUEST['tid']; ?>" />
        <input type="hidden" name="terreno" value="<?php echo $_REQUEST['terreno']; ?>" />
				<fieldset>
					<legend>Seleccione una opci&oacute;n</legend>
					<input type="radio" name="pqtOdis" value="1" /> Paquetes completos
					<div class="spacer_10"></div>
					<input type="radio" name="pqtOdis" value="2" /> Dispositivos individuales
					<div class="spacer_20"></div>
					<input type="image" src="images/btn_siguiente.png" />
				</fieldset>
			</form>
<?php
		
	}
?>
