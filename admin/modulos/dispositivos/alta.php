<div class="grid_11 suffix_3 alpha">
<fieldset>
<legend>Favor de llenar los campos se&ntilde;alados</legend>
<form action="sql.php?mod=2&act=4" method="post" class="AdvancedForm">
<table id="alta_proveedores" cellpadding="0" cellspacing="0" align="center">
	<tr>
  	<td>Tipo de Dispositivo</td>
    <td>
    	<select name="dispositivo_tipo" class="general">
      	<optgroup label="Seleccione una opci&oacute;n">
        	<?php
						$query = mysql_query("SELECT id_tipo, nombre FROM ce_dispositivos_tipo");
						while($row = mysql_fetch_array($query)){
							echo '<option value="'.$row['id_tipo'].'">'.ucfirst($row['nombre']).'</option>';
						}
					?>
        </optgroup>
    	</select>
    </td>
  </tr>
	<tr>
  	<td width="157">Marca*</td>
    <td width="307"><input id="marca" name="marca" type="text" class="general width96"></td>
  </tr>
  <tr>
  	<td>Modelo</td>
    <td><input id="modelo" name="modelo" type="text" class="general width96"></td>
    <td></td>
  </tr>
  <tr>
  	<td>Precio Dispositivo</td>
    <td><input id="precio_dispositivo" name="precio_dispositivo" type="text" class="general width96"></td>
    <td></td>
  </tr>
  <tr>
  	<td>Precio Instalaci&oacute;n</td>
    <td><input id="precio_instalacion" name="precio_instalacion" type="text" class="general width96"></td>
    <td></td>
  </tr>
  <tr>
  	<td>Proveedor</td>
    <td><input id="proveedor" name="proveedor" type="text" class="general width96"></td>
    <td></td>
  </tr>
  <tr>
  	<td>Factores</td>
    <td><input name="factores" id="factores" class="general width96"></td>
    <td>Separar los valores por comas(,)</td>
  </tr>
  <tr>
  	<td>Variables</td>
    <td><input name="variables" id="variables" class="general width96"></td>
    <td>Separar los valores por comas(,)</td>
  </tr>
  <tr>
  	<td colspan="2"><div align="right"><input type="image" value="" src="../images/guardar.png" style="margin-right:4px; border:none;"></div></td>
    <td></td>
  </tr>
</table>
</form>
</fieldset>
</div>