<div id="acerca" class="prefix_1 grid_14 suffix_1 alpha">
  <div class="grid_10 alpha">
  	<h2 style="margin-bottom:0;">Dispositivos</h2>
  </div><!-- grid_14 alpha -->
  <div class="grid_14 alpha">
    <a href="index.php?mod=2&act=1"> <?php if($_REQUEST['mod']==2 and $_REQUEST['act']==1) echo '<img src="../images/arrow.png" border="0" />'; else echo '<img src="../images/blank_arrow.png" border="0" />' ?> Tipos de dispositivos</a><br />
    <a href="index.php?mod=2&act=2"> <?php if($_REQUEST['mod']==2 and $_REQUEST['act']==2) echo '<img src="../images/arrow.png" border="0" />'; else echo '<img src="../images/blank_arrow.png" border="0" />'; ?> Dispositivos</a>
  </div><!-- alta_proveedor -->
  <div class="spacer_20 grid_14 alpha"></div>  
</div><!-- main_izq -->
<div class="grid_16 alpha">
  <?php
	switch($_REQUEST['msj']){
		case 1: echo '<div class="msj">Alta de dispositivo realizada con &eacute;xito</div>'; break;
		case 2: echo '<div class="msjerror">Hubo un error al agregar el dispositivo, intentelo de nuevo.</div>'; break;
		case 3: echo '<div class="msj">Modificaci&oacute;n de dispositivo realizada con &eacute;xito</div>'; break;
		case 4: echo '<div class="msjerror">Hubo un error al modificar el dispositivo, intentelo de nuevo.</div>'; break;
		case 5: echo '<div class="msjerror">Hubo un error al eliminar el dispositivo, intentelo de nuevo.</div>'; break;
	}	
	
	switch($_REQUEST['act']){
		case 1: {
			switch($_REQUEST['actid']){
				case 1: require("modulos/dispositivos/alta.php");break;
				default: require("modulos/dispositivos/default.php");break;
			}
		}break;
		case 2: require("modulos/dispositivos_tipo/default.php");break;
	}
	?>
  </div>


    