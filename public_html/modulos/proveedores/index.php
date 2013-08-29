<div id="acerca" class="grid_16">
  	<div id="titulo_proveedores">
    	<h2>Proveedores</h2>
      <div class="spacer_20"></div>
    </div><!-- fin titulo_proveedores -->
    <div id="agregar_dispositivo">
    	<?php
				if($_SESSION['tipo']==2){
					include("modulos/proveedores/usuario_proveedor.inc.php");				
        }else{
					include("modulos/proveedores/usuario_normal.inc.php");
        }
			?>
    </div><!-- agregar_dispositivo -->    
</div><!-- acerca -->
<div class="clear"></div>  