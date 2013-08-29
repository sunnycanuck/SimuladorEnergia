<?php
$uid = $_SESSION['userid'];
$terreno = $_REQUEST['terreno'];
$tarifa = $_REQUEST['tarifa'];
$table = "ce_cfe_consumohistorico_".$_REQUEST['terreno']."t";

if(!empty($terreno) || !empty($tarifa)){
?>
<div class="prefix_1 grid_7 alpha">
  <h1><a href="index.php?mod=5" style="text-decoration:underline;color:darkblue;">Recibos</a> > Agregar recibo</h1>
  <div class="spacer_10"></div>

	<form action="sql.php?mod=5&act=1" method="post" class="altaTerreno">
    <input type="hidden" name="tipo" value="1" />
    <input type="hidden" name="terreno" value="<?php echo $table; ?>" />
    <input type="hidden" name="tarifa" value="<?php echo $tarifa; ?>" />
    <input type="hidden" name="id_terreno" value="<?php echo $terreno; ?>" />
    <?php
			$query = mysql_query("SELECT * FROM ce_consumo WHERE factores LIKE '%$table%';");
			if(mysql_num_rows($query)==0){
		?>
    	<div id="recibo">
        <h2>Agregar Recibo</h2>
        <fieldset>
        <legend><span class="texto_rojo">Favor de llenar los campos como aparecen en su recibo</span></legend>
          <table id="alta_proveedores" cellpadding="0" cellspacing="0" align="center">
            <tr>
              <td><span class="pasos_recibo_cfe">1</span> Terreno*</td>
              <td>
                <?php
                  $query = mysql_fetch_array(mysql_query("SELECT nombre FROM `ce_terreno` WHERE id = $terreno;"));
                  echo '<span class="datos_recibo_cfe">'.$query['nombre'].'</span>';
                ?>
              </td>
            </tr>
            <tr>
              <td><span class="pasos_recibo_cfe">2</span> Tarifa*</td>
              <td>
                <?php
                  $query = mysql_fetch_array(mysql_query("SELECT tipo FROM ce_tarifas_tipo WHERE id_tarifa = $tarifa;"));
                  echo '<span class="datos_recibo_cfe">'.$query['tipo'].'</span>';
                ?>
              </td>
            </tr>
            <!--tr>
              <td><span class="pasos_recibo_cfe">3</span> Numero de Servicio*</td>
              <td><input id="no_servicio" name="no_servicio" type="text" class="general width96"></td>
            </tr-->
            <tr>
              <td><span class="pasos_recibo_cfe">3</span> Total a Pagar*</td>
              <td><input id="total_pagar" name="total_pagar" type="text" class="general width96"></td>
            </tr>
            <tr>
              <td><span class="pasos_recibo_cfe">4</span> Consumo en kWh*</td>
              <td><input id="consumo_watts" name="consumo_watts" type="text" class="general width96"></td>
            </tr>
            <tr>
              <td><span class="pasos_recibo_cfe">5</span> Lectura Actual*</td>
              <td><input id="lectura" name="lectura" type="text" class="general width96"></td>
            </tr>
            <!--tr>
              <td><span class="pasos_recibo_cfe">7</span> Numero de Medidor*</td>
              <td><input id="medidor" name="medidor" type="text" class="general width96"></td>
            </tr-->
            <tr>
              <td><span class="pasos_recibo_cfe">6</span> Periodo de Consumo*</td>
              <td>
                <label for="from">De</label>
                <input type="text" id="from" name="desde" class="general historial" style="width:80px;"/>
                <label for="to">A</label>
                <input type="text" id="to" name="hasta" class="general historial" style="width:80px;"/>
              </td>
            </tr>
          </table>

      	</fieldset>
      </div><!-- recibo -->
  	<?php
			}else{
				$row = mysql_fetch_array($query);
				$factores = explode(";", $row['factores']);
		?>
    		<div id="recibo">
          <h2 style="margin-bottom: 0;">Datos del Recibo</h2>
          <cite>Todos los datos capturados son privados y no ser&aacute;n compartidos ni utilizados fuera de esta aplicaci&oacute;n.</cite>
          <fieldset>
            <legend><span class="texto_rojo">Favor de llenar los campos como aparecen en su recibo</span></legend>
            <table id="alta_proveedores" cellpadding="0" cellspacing="0" align="center">
              <tr>
                <td><span class="pasos_recibo_cfe">1</span> Terreno*</td>
                <td>
                  <?php
                    $query2 = mysql_fetch_array(mysql_query("SELECT nombre FROM `ce_terreno` WHERE id = $terreno;"));
                    echo '<span class="datos_recibo_cfe">'.$query2['nombre'].'</span>';
                  ?>
                </td>
              </tr>
              <tr>
                <td><span class="pasos_recibo_cfe">2</span> Tarifa*</td>
                <td>
                  <span class="datos_recibo_cfe"><?php
                  	$query = mysql_fetch_array(mysql_query("SELECT tipo FROM ce_tarifas_tipo WHERE id_tarifa = " . $factores[4] ));
                  echo $query["tipo"]; ?></span>
                </td>
              </tr>
              <!--tr>
                <td><span class="pasos_recibo_cfe">3</span> Numero de Servicio*</td>
                <td><span class="datos_recibo_cfe"><?php echo $factores[1]; ?></span></td>
              </tr-->
              <tr>
                <td><span class="pasos_recibo_cfe">3</span> Total a Pagar*</td>
                <td><span class="datos_recibo_cfe">$<?php echo $factores[5]; ?></span></td>
              </tr>
              <tr>
                <td><span class="pasos_recibo_cfe">4</span> Consumo en kWh*</td>
                <td><span class="datos_recibo_cfe"><?php echo $factores[6]; ?></span></td>
              </tr>
              <tr>
                <td><span class="pasos_recibo_cfe">5</span> Lectura Actual*</td>
                <td><span class="datos_recibo_cfe"><?php echo $factores[8]; ?></span></td>
              </tr>
              <!--tr>
                <td><span class="pasos_recibo_cfe">7</span> Numero de Medidor*</td>
                <td><span class="datos_recibo_cfe"><?php echo $factores[9]; ?></span></td>
              </tr-->
              <tr>
                <td><span class="pasos_recibo_cfe">6</span> Periodo de Consumo*</td>
                <td>
                  <span class="datos_recibo_cfe">De <?php echo $factores[2]; ?></span>
                  <span class="datos_recibo_cfe">A <?php echo $factores[3]; ?></span>
                </td>
              </tr>
            </table>
            <!--div class="spacer_10"></div>
            <div align="right"><a href="index.php?mod=5&act=3&rid=<?php echo $row['id']; ?>&terreno=<?php echo $terreno; ?>"><img src="images/btn_editar_l.png" /></a></div-->
        	</fieldset>
        </div><!-- recibo -->
    <?php
			}
		?>
  <div class="spacer_20"></div>
  <h4 style="margin-bottom:5px;">Agregar Historico de Consumo</h4>
  <cite>Estos datos ser&aacute;n usados para los c&aacute;lculos de un consumo promedio que abarcar&aacute;n por lo menos un a&ntilde;o.</cite>

  <div id="consumo_inputs">
    <fieldset>
      <legend><span class="texto_rojo">Favor de llenar los campos con el historial de consumo</span></legend>
      <img id="agregar" src="images/btn_agregar.png"/> <img id="eliminar" src="images/eliminar_btn.jpg" />
      <div class="spacer_10"></div>
      <ul>
        <li>
        </li>
      </ul>
    </fieldset>
  </div>
  <div align="right"><input type="image" value="" src="images/guardar.png" style="margin-right:4px;"></div>
</form>
	<?php

	extract(mysql_fetch_array(mysql_query("SELECT COUNT(id) AS total FROM $table;")));

	if (!$total ==0)
	{
	?>


		<?php
		echo '<div class="spacer_20"></div>';
		echo '<h4 style="margin-bottom:5px;">Historico de Consumo</h4>';
		echo '<fieldset>';
		echo '<table id="alta_proveedores" cellpadding="0" cellspacing="0" align="center">';
		echo '<tr><th><h6 style="margin-bottom:0;">Fecha</h6></th><th><h6 style="margin-bottom:0;">Consumo</h6></th><th><h6 style="margin-bottom:0;">Borrar</h6></th></tr>';
		$query = mysql_query("SELECT * FROM ".$table." ORDER BY ano,mes ASC");
		$i = 0;
		while($row = mysql_fetch_array($query)){
			if($i%2 == 0){
				$clase = 'par';
			}else{
				$clase = 'non';
			}
			echo '<tr>';
			echo '<td class="'.$clase.'">'.$row['mes'].' - '.$row['ano'].'</td><td class="'.$clase.'">'.$row['consumo'].'</td>
						<td class="'.$clase.'"><a href="sql.php?mod=5&act=2&rid='.$row['id'].'&table='.$table.'&tarifa='.$tarifa.'&id_terreno='.$terreno.'"><img src="images/borrar.png" width="20" border="0" /></a></td>';
			echo '</tr>';
			$i++;
		}
		echo '</table>';
		echo '</fieldset>';


	}

?>
<div align="right"><input type="image" value="" src="images/regresar.png" style="margin-right:4px;" onclick="window.location='index.php?mod=5&act=1'" /></div>
</div>
<div class="grid_7 omega">
	<h2>Explicaci&oacute;n del recibo</h2>
	<img src="images/recibo_cfe_2.jpg" border="0" />
</div>
<?php
}// if empty
else{
?>
<div class="grid_16">
	Ocurri&oacute; un error, intentelo de nuevo.
</div>
<?php
}
?>