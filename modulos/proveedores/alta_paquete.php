<div id="proveedores" class="prefix_1 grid_14 suffix_1">
	<h2 style="margin-bottom:0;">Agregar Paquete</h2>
	<div style="margin-bottom:20px;"><a href="index.php?mod=3">Regresar</a></div>
  <?php
  	$uid = $_SESSION['userid'];
		$num_gtie =  mysql_num_rows(mysql_query("SELECT * FROM ce_dispositivos WHERE id_proveedor = ".$uid." AND tipo = 4 AND activado = 1;"));
		$num_foto =  mysql_num_rows(mysql_query("SELECT * FROM ce_dispositivos WHERE id_proveedor = ".$uid." AND tipo = 1 AND activado = 1;"));
		
		if($num_foto==0 || $num_gtie==0){
			if($num_gtie==0){
	?>
  		<div class="alert"><p>No hay dispositivos de tipo <strong>Grid Tie</strong> almacenados, o no se encuentran activados, favor de agregar al menos uno o contactar al administrador.</p></div>
  <?php
			}if($num_foto==0){
	?>
  			<div class="spacer_10"></div>
      	<div class="alert"><p>No hay dispositivos de tipo <strong>Fotovoltaico</strong> almacenados, o no se encuentran activados, favor de agregar al menos uno o contactar al administrador.</p></div>
        <div class="spacer_3"></div>
  <?php
			}
	?>
  		<div class="spacer_10"></div>
			<a href="index.php?mod=3&act=1"><img src="images/agregar_mis_dispositivos.png" border="0" /> Agregar Dispositivo</a>
      <div class="spacer_3"></div>
      <a href="index.php?mod=3&act=2"><img src="images/mis_dispositivos.png" /> Ver Mis Dispositivos</a>
	<?php
  	}else{
	?>  
  	
    <form action="index.php?mod=3&act=5" method="post">
    	Nombre del paquete<br />
    	<input type="text" id="nombre_pqt" name="nombre_pqt" class="general" />
    	<div class="spacer_20"></div>
      <fieldset>
        <legend>Seleccione Grid Tie</legend>
        <table id="activar_proveedores" cellpadding="0" cellspacing="0" border="0" width="100%">
          <thead>
            <tr>
              <td id="izq">Marca</td>
              <td>Modelo</td>
              <td>Precio</td>
              <td id="der">Costo Instalaci&oacute;n</td>
            </tr>
          </thead>
          <tbody>
      <?php
        $query = mysql_query("SELECT * FROM ce_dispositivos WHERE id_proveedor = ".$uid." AND tipo = 4 AND activado = 1;");
        $i = 1;
        while($row = mysql_fetch_array($query)){
          if($i%2){
            $class = "par";
          }else{
            $class = "non";
          }
      ?>
          <tr class="<?php echo $class; ?>">
            <input type="hidden" name="totalGtie[<?php echo $i; ?>]" value="<?php echo $row['precio_dispositivo'] + $row['precio_instalacion']; ?>">
            <td><input type="radio" name="gridTie" value="<?php echo $row['id_dis']; ?>" style="margin-right:10px;"> <?php echo $row['marca']; ?></td>
            <td><?php echo $row['modelo']; ?></td>
            <td><?php echo $row['precio_dispositivo']; ?></td>
            <td><?php echo $row['precio_instalacion']; ?></td>
          </tr>
      <?php
          $i++;
        }
      ?>
          </tbody>
        </table>
      </fieldset>
      <div class="spacer_40"></div>
      <fieldset>
        <legend>Seleccione Fotovoltaico(s)</legend>
        <table id="activar_proveedores" cellpadding="0" cellspacing="0" border="0" width="100%">
          <thead>
            <tr>
              <td id="izq">Cantidad</td>
              <td>Marca</td>
              <td>Modelo</td>
              <td>Precio</td>
              <td id="der">Costo Instalaci&oacute;n</td>
            </tr>
          </thead>
          <tbody>
      <?php
        $query = mysql_query("SELECT * FROM ce_dispositivos WHERE id_proveedor = ".$uid." AND tipo = 1 AND activado = 1;");
        $i = 1;
        while($row = mysql_fetch_array($query)){
          if($i%2){
            $class = "par";
          }else{
            $class = "non";
          }
      ?>
          <tr class="<?php echo $class; ?>">
            <td><input type="checkbox" name="fotovol[]" value="<?php echo $row['id_dis']; ?>" style="margin-right:10px;"> <input type="number" maxlength="2" name="numFotovol[]" style="width:50px;"></td>
            <td><?php echo $row['marca']; ?></td>
            <td><?php echo $row['modelo']; ?></td>
            <td><?php echo $row['precio_dispositivo']; ?></td>
            <td><?php echo $row['precio_instalacion']; ?></td>
          </tr>
      <?php
          $i++;
        }
      ?>
          </tbody>
        </table>
      </fieldset>
      <div align="right">
        <input type="image" src="images/btn_siguiente.png">
      </div>
    </form>
  <?php
		}
	?>
   
</div><!-- proveedores -->