<?php
  /*---------------------------------------------------------------------------------
  MODIFICACIONES:
  ---------------------------------------------------------------------------------
  Clave: HMN01
  Hecha por: Héctor Mora.
  Fecha: 02/Noviembre/2012
  Descripción: Se agrego variable hidden con el dispositivo_tipo
  ----------------------------------------------------------------------------------*/
  $did = $_REQUEST['did'];
	$row = mysql_fetch_array(mysql_query("SELECT * FROM ce_dispositivos WHERE id_dis =".$did." LIMIT 1;"));
?>
<div class="prefix_1 grid_12 suffix_3 alpha">
<h2 style="margin-bottom:0;">Editar Dispositivo</h2>
<div style="margin-bottom:20px;"><a href="index.php?mod=3">Regresar</a></div>
<fieldset>
<legend>Favor de llenar los campos se&ntilde;alados</legend>
<form action="sql.php?mod=3&act=3" method="post" class="AdvancedForm">
<input type="hidden" name="dispositivo_tipo" id="dispositivo_tipo" value="<?php echo $row['tipo']?>"/>
<table id="alta_proveedores" cellpadding="0" cellspacing="0" align="center">
	<tr>
  	<td>Tipo de Dispositivo</td>
    <td>
    	<?php
				$query = mysql_fetch_array(mysql_query("SELECT id_tipo, nombre FROM ce_dispositivos_tipo WHERE id_tipo = ".$row['tipo'].";"));
				echo ucfirst($query['nombre']).'<br>';
			?>
      <input type="hidden" name="did" value="<?php echo $did; ?>" />
      <input type="hidden" name="idp" value="<?php echo $row['id_proveedor']; ?>" />
      <input type="hidden" name="id_tipo" value="<?php echo $query['id_tipo']; ?>" />
      <input name="proveedor" type="hidden" value="<?php echo $row['proveedor']; ?>" />
    </td>
  </tr>
	<tr>
  	<td width="157">Marca*</td>
    <td width="307"><input id="marca" name="marca" type="text" class="general width96" value="<?php echo $row['marca']; ?>"></td>
  </tr>
  <tr>
  	<td>Modelo</td>
    <td><input id="modelo" name="modelo" type="text" class="general width96" value="<?php echo $row['modelo']; ?>"></td>
    <td></td>
  </tr>
  <tr>
  	<td>Precio Dispositivo</td>
    <td><input id="precio_dispositivo" name="precio_dispositivo" type="text" class="general width96" value="<?php echo $row['precio_dispositivo']; ?>"></td>
   <td></td>
  </tr>
  <tr>
  	<td>Precio Instalaci&oacute;n</td>
    <td><input id="precio_instalacion" name="precio_instalacion" type="text" class="general width96" value="<?php echo $row['precio_instalacion']; ?>"></td>
    <td></td>
  </tr>
  <tr>
  	<td>Factores</td>
		<?php
      if($query['id_tipo']==1){
        $factores = explode(';', $row['factores']); 				
    ?>
    <td>
    	<div>      	
      	<input type="text" name="dell" value="<?php echo $factores[0]; ?>" class="general widthsmall" />
        <input type="text" name="delh" value="<?php echo $factores[1]; ?>" class="general widthsmall" />
        <input type="text" name="potencia" value="<?php echo $factores[4]; ?>" class="general widthsmall" />
        <select name="tipoFotovol" class="general tipoFotovol">
        	<option <?php if($factores[4]==1) echo 'selected="selected"'; ?> value="1">Monocristalino</option>
          <option <?php if($factores[4]==2) echo 'selected="selected"'; ?> value="2">Policristalino</option>
          <option <?php if($factores[4]==3) echo 'selected="selected"'; ?> value="3">Pelicula Delgada</option>
        </select>
      </div>
    </td>
    <td>
    </td>
		<?php
    	}else{        
    ?>
    <td>
      <div>
        <input type="text" name="factores" value="<?php echo $row['factores']; ?>" class="general" />
      </div>
    </td>
    <td>
    	<span style="font-size:10px;">Separar los valores con punto y coma(;)</span><br />
      <span style="font-size:10px;"><cite>Ejemplo: <strong>QE; potencia</strong></cite></span>
    </td>
    <?php
			}
		?>
  </tr>
  <tr>
  	<td>Proveedor</td>
    <?php
			 $row = mysql_fetch_array(mysql_query("SELECT * FROM ce_usuarios WHERE id_usuario = ".$_SESSION['userid']." LIMIT 1;"));
		?>
    <td><?php echo $row['nombre']; ?></td>
    <td>    	      
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