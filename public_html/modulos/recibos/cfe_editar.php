<?php 
$uid = $_SESSION['userid'];
$terreno = $_REQUEST['terreno'];
$tarifa = $_REQUEST['tarifa'];
$table = "ce_cfe_consumohistorico_".$_REQUEST['terreno']."t";
?>
<div class="prefix_1 grid_7 alpha">
	<form action="sql.php?mod=5&act=3" method="post" class="altaTerreno">
  	<input type="hidden" name="rid" value="<?php echo $_REQUEST['rid']; ?>" />
    <input type="hidden" name="terreno" value="<?php echo $terreno; ?>" />
    <?php
			$query = mysql_query("SELECT * FROM ce_consumo WHERE id=".$_REQUEST['rid'].";");
			$row = mysql_fetch_array($query);
			$factores = explode(";", $row['factores']);
		?>
    <div id="recibo">
      <h2>Datos del Recibo</h2>
      <fieldset>
        <legend><span class="texto_rojo">Favor de llenar los campos como aparecen en su recibo</span></legend>
        <table id="alta_proveedores" cellpadding="0" cellspacing="0" align="center">
          <tr>
            <td><span class="pasos_recibo_cfe">1</span> Terreno*</td>
            <td>
              <?php 
                $query2 = mysql_fetch_array(mysql_query("SELECT nombre FROM `ce_terreno` WHERE id = ".$terreno.";"));
                echo '<span class="datos_recibo_cfe">'.$query2['nombre'].'</span>'; 
              ?>
            </td>
          </tr>
          <tr>
            <td><span class="pasos_recibo_cfe">2</span> Tarifa*</td>
            <td>
              <span class="datos_recibo_cfe"><?php echo $factores[4]; ?></span>
            </td>
          </tr>
          <tr>
            <td><span class="pasos_recibo_cfe">3</span> Numero de Servicio*</td>
            <td><span class="datos_recibo_cfe"><input id="no_servicio" name="no_servicio" type="text" class="general width96" value="<?php echo $factores[1]; ?>"></span></td>
          </tr>
          <tr>
            <td><span class="pasos_recibo_cfe">4</span> Numero de Medidor*</td>
            <td><span class="datos_recibo_cfe"><input id="medidor" name="medidor" type="text" class="general width96" value="<?php echo $factores[9]; ?>"></span></td>
          </tr>              
        </table>
        <div class="spacer_10"></div>            
      </fieldset>
    </div><!-- recibo -->
    
    <div class="spacer_20"></div>
  
  	<div align="right"><input type="image" value="" src="images/guardar.png" style="margin-right:4px;"></div>
	</form>	
</div>

<div class="grid_7 omega">
	<h2>Explicaci&oacute;n del recibo</h2>
	<img src="images/recibo_cfe_2.jpg" border="0" />
</div>