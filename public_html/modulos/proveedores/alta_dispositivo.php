<div class="prefix_1 grid_14 suffix_1 alpha">
<h2 style="margin-bottom:0;">Agregar Dispositivo</h2>
<div style="margin-bottom:20px;"><a href="index.php?mod=3">Regresar</a></div>
<fieldset>
<legend>Favor de llenar los campos se&ntilde;alados</legend>
<form action="sql.php?mod=3&act=1" method="post" class="AdvancedForm">
<table id="alta_proveedores" cellpadding="0" cellspacing="0" align="center">
	<tr>
  	<td>Tipo de Dispositivo</td>
    <td>
    	<select id="dispositivo_tipo" name="dispositivo_tipo" class="general">
      	<option selected="selected">Seleccione un opci&oacute;n</option>
				<?php
					$query = mysql_query("SELECT id_tipo, nombre FROM ce_dispositivos_tipo WHERE activado = 1");
					while($row = mysql_fetch_array($query)){
						echo '<option value="'.$row['id_tipo'].'">'.ucfirst($row['nombre']).'</option>';
					}
				?>
    	</select>
    </td>
  </tr>
	<tr>
  	<td width="157">Marca*</td>
    <td width="307"><input id="marca" name="marca" type="text" class="general width96"></td>
    <td></td>
  </tr>
  <tr>
  	<td>Modelo</td>
    <td><input id="modelo" name="modelo" type="text" class="general width96"></td>
    <td></td>
  </tr>
  <tr>
  	<td>Precio Dispositivo</td>
    <td><input id="precio_dispositivo" name="precio_dispositivo" type="text" class="general width96"></td>
    <td><div style="font-size:10px;"><cite>Ej: 500.50</cite></div></td>
  </tr>
  <tr>
  	<td>Precio Instalaci&oacute;n</td>
    <td><input id="precio_instalacion" name="precio_instalacion" type="text" class="general width96"></td>
    <td><div style="font-size:10px;"><cite>Ej: 500.50</cite></div></td>
  </tr>
  <tr>
  	<td>Factores</td>
    <td>
    	<div id="factores"><div>Seleccione una opci&oacute;n</div></div>
    </td>
    <td>
      <div id="notas" style="font-size:10px;"></div>
    </td>
  </tr>
  <tr>
  	<td>Proveedor</td>
    <?php
			 $row = mysql_fetch_array(mysql_query("SELECT * FROM ce_usuarios WHERE id_usuario = ".$_SESSION['userid']." LIMIT 1;"));
		?>
    <td><div class="spacer_10"></div><?php echo $row['nombre']; ?><input name="proveedor" type="hidden" value="<?php echo $row['nombre']; ?>" /></td>
    <td>
    	<input type="hidden" name="idp" value="<?php echo $row['id_usuario']; ?>" />
      <input type="hidden" name="tipo" value="<?php echo $row['tipo']; ?>" />
    </td>
  </tr>
  <tr>
  	<td colspan="2"><div align="right"><input type="image" value="" src="images/guardar.png" style="margin-right:4px; border:none;"></div></td>
    <td></td>
  </tr>
</table>
</form>
</fieldset>
</div>