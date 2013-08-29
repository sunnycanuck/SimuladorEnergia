<div id="acerca" class="prefix_1 grid_14 suffix_1 alpha">
  <div class="grid_10 alpha">
  	<h2>Proveedores</h2>
  </div><!-- grid_14 alpha -->
  <div id="alta_proveedor" class="grid_4 omega">
    <a href="index.php?mod=3&act=1"><img src="../images/agregar.png" border="0" /> Alta de proveedor</a>
  </div><!-- alta_proveedor -->  
</div><!-- main_izq -->
<div class="grid_16 alpha">
  <?php
	switch($_REQUEST['msj']){
		case 1: echo '<div class="msj">Alta de proveedor realizada con &eacute;xito</div>'; break;
		case 2: echo '<div class="msjerror">Hubo un error al agregar al proveedor, intentelo de nuevo.</div>'; break;
		case 3: echo '<div class="msj">Modificaci&oacute;n de proveedores realizada con &eacute;xito</div>'; break;
		case 4: echo '<div class="msjerror">Hubo un error al modificar al proveedor, intentelo de nuevo.</div>'; break;
		case 5: echo '<div class="msjerror">Hubo un error al eliminar al proveedor, intentelo de nuevo.</div>'; break;
	}	
	
	switch($_REQUEST['act']){
		case 1: require("modulos/proveedores/alta.php");break;
		case 2:require("modulos/proveedores/default.php");break;
		default:require("modulos/proveedores/default.php");break;
	}
	?>
  </div>


    