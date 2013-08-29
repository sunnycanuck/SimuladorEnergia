<?php
/*---------------------------------------------------------------------
MODIFICACIONES
---------------------------------------------------------------------
Clave: HMN01
Autor: Héctor Mora
Fecha: 29/Noviembre/2012
Cambio: Se eliminaron las columnas X Y Z, y estaba al revés PI/180 debe ser 180/PI
---------------------------------------------------------------------
clave: RE01
Author: Rodger Evans
Fecha: 12/Abril/2013
cambio: names a titles
*/
?>
<div id="acerca">
  <div class="spacer_20"></div>

  <?php
	$uid = $_SESSION['userid'];
	$id_dispositivo = $_REQUEST['id_dispositivo'];
	$table = $_REQUEST['table'];
	$sql = mysql_query("
	SELECT $table.*, ce_dispositivos_tipo.nombre, ce_dispositivos.tipo, ce_dispositivos.marca, ce_dispositivos.modelo, ce_dispositivos.tipo, ce_dispositivos.precio_dispositivo, ce_dispositivos.precio_instalacion,
	ce_dispositivos.proveedor, ce_dispositivos.variables
	FROM $table
	INNER JOIN ce_dispositivos ON $table.id_dispositivo = ce_dispositivos.id_dis
	INNER JOIN ce_dispositivos_tipo ON ce_dispositivos.tipo = ce_dispositivos_tipo.id_tipo
	WHERE $table.id = $id_dispositivo; ");

	$linea = mysql_fetch_array($sql);
	?>
  <h2>Editar Dispositivo: <?php echo ucfirst($linea['nombre']); ?></h2>
  <form action="sql.php?mod=6&act=3" method="post">
  <input type="hidden" name="table" value="<?php echo $_REQUEST['table'] ?>" />
  <input type="hidden" name="terreno" value="<?php echo $_REQUEST['terreno'] ?>" />
  <input type="hidden" name="tid" value="<?php echo $_REQUEST['tid'] ?>" />
  <input type="hidden" name="caso" value="<?php echo $_REQUEST['caso'] ?>" />

    <table border="0" cellpadding="0" cellspacing="0" id="activar_proveedores">
    	<thead>
        <tr>
          <td id="izq">Cantidad</td>
          <td>Marca</td>
          <td>Modelo</td>
          <td>Precio</td>
          <td>Instalaci&oacute;n</td>
          <td>Proveedor</td>
          <td>Acimut</td>
          <td id="der">Altura</td> <!--RE01-->
          <!--HMN03td>X</td>
          <td>Y</td>
          <td id="der">Z</td-->
        </tr>
      </thead>
    	<?php
			$query = mysql_query("
								SELECT $table.*, ce_dispositivos_tipo.nombre, ce_dispositivos.tipo, ce_dispositivos.marca, ce_dispositivos.modelo, ce_dispositivos.tipo, ce_dispositivos.precio_dispositivo, ce_dispositivos.precio_instalacion,
								ce_dispositivos.proveedor, ce_dispositivos.variables
								FROM $table
								INNER JOIN ce_dispositivos ON $table.id_dispositivo = ce_dispositivos.id_dis
								INNER JOIN ce_dispositivos_tipo ON ce_dispositivos.tipo = ce_dispositivos_tipo.id_tipo
								WHERE $table.id = $id_dispositivo; ");
			$i = 0;
			while($row= mysql_fetch_array($query)){
				if($i%2==0){
					$clase = 'par';
				}else{
					$clase = 'non';
				}

				$variables = explode(";", $row['dispositivos_variables']);
			?>
      <tr class="<?php echo $clase; ?>">
      	<td><div align="center"><input type="hidden" name="dispositivo" value="<?php echo $row['id']; ?>" /><input name="cantidad" type="text" value="<?php echo $row['dispositivos']; ?>" maxlength="2" style="width:30px;" /></div></td>
        <td><?php echo $row['marca']; ?></td>
        <td><?php echo $row['modelo']; ?></td>
        <td>$<?php echo $row['precio_dispositivo']; ?></td>
        <td>$<?php echo $row['precio_instalacion']; ?></td>
        <td><?php echo $row['proveedor']; ?></td>
        <td>
        	<div align="center">
          	<input name="az" type="text" value="<?php echo $variables[0] = round(($variables[0] * 180) / PI); ?>" style="width:40px;"/><br />
						Grados
          </div>
        </td>
        <td>
        	<div align="center">
          	<input name="alt" type="text" value="<?php echo $variables[1] = round(($variables[1] * 180) / PI); ?>" style="width:40px;"/><br />
						Grados
          </div>
        </td>
        <!--HMN03 td>
        	<div align="center">
          	<input name="equis" type="text" value="<?php echo $variables[2]; ?>" style="width:40px;"/><br />
            m
          </div>
        </td>
        <td>
        	<div align="center">
          	<input name="ye" type="text" value="<?php echo $variables[3]; ?>" style="width:40px;"/><br />
						m
          </div>
        </td>
        <td>
        	<div align="center">
          	<input name="zeta" type="text" value="<?php echo $variables[4]; ?>" style="width:40px;"/><br />
						m
          </div>
        </td-->

      </tr>
      <?php
				$i++;
			 }
			?>
      <tr>
      	<td colspan="11"><div align="right"><input id="guardar" type="image" src="images/guardar.png" style="border:0;" /></div></td>
      </tr>
    </table>
  </form>
</div><!-- acerca -->
<div id='loadingDiv' style="display:none;">
	Estamos guardando su informaci&oacute;n, por favor espere un momento...  <img src='images/loader.gif' />
</div>
<script type="text/javascript">
	$('#guardar').click(function() {
		$('#loadingDiv').show("slow");
	});
</script>


