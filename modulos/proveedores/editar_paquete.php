<div id="proveedores" class="prefix_1 grid_14 suffix_1">
	<h2 style="margin-bottom:0;">Editar Paquete</h2>
	<div style="margin-bottom:20px;"><a href="index.php?mod=3">Regresar</a></div>
	<?php
		$pid = $_REQUEST['pid'];
		$row = mysql_fetch_array(mysql_query("SELECT * FROM ce_paquetes WHERE id_pqt = ".$pid." LIMIT 1;"));
	?>
  <div class="grid_4 suffix_10 alpha">
  <form action="sql.php?mod=3&act=6" method="post">
  	<input type="hidden" name="pid" value="<?php echo $pid; ?>" />
  	<input type="text" name="nombre" value="<?php echo $row['nombre_pqt']; ?>" class="general" />
    <div class="spacer_10"></div>
    <input type="text" name="precio" value="<?php echo $row['precio']; ?>" class="general" />
    <div class="spacer_10"></div>
    <div align="right">
	    <input type="image" src="images/guardar.png" />
    </div>
  </form>
  </div>
</div><!-- proveedores -->