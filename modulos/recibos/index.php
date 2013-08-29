<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<div id="acerca" class="grid_16 alpha">
  <?php
	$uid = $_SESSION['userid'];
	$query = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS total FROM `ce_terreno` WHERE id_usuario = '$uid';"));
	if($query['total']!=0){	
		$query = mysql_query("SELECT * FROM ce_terreno WHERE id_usuario = ".$uid.";");
		$i = 1;
		while($row = mysql_fetch_array($query)){
			$table = "ce_cfe_consumohistorico_".$row['id']."t";
			if($i%2==0){
				$clase = 'non';
			}else{
				$clase = 'par';
			}			
	?>
  <table cellpadding="0" cellspacing="0" border="0" id="terrenos_tabla">
    <tr>
      <td width="16" rowspan="3" id="terreno_num" class="<?php echo $clase; ?>"><h1><?php echo $i; ?></h1></td>
      <td id="terreno_nombre" colspan="2">
        <img src="images/icon_factory.png" border="0" style="float:left; margin:0 10px 0 10px;" />
        <h1 style="margin:0"><?php echo $row['nombre']; ?></h1>
      </td>
      <td width="50%" id="terreno_botones">
        <div align="center">
          <div class="spacer_10"></div>          
        </div>
      </td>
    </tr>
    <tr>
    	<td colspan="2" style="padding:5px 0 5px 5px;">
      	<div class="historico-container">
        <div class="tarifa-selector<?php $i; ?>">
          <a class="tarifa-btn">Tarifa:</a>
            <select class="tarifa<?php echo $i; ?> ">
              <optgroup label="Seleccione una opci&oacute;n">
              <?php
              extract(mysql_fetch_array(mysql_query("SELECT tipo AS tipoTarifa FROM ce_consumo WHERE secuencia = '".$table."' LIMIT 1;")));
              $q = mysql_query("SELECT id_tarifa, tarifa FROM ce_tarifas;");
              while($record = mysql_fetch_array($q)){							
              ?>
              <option value="sql.php?mod=5&act=4&tid=<?php echo $record['id_tarifa']; ?>&tabla=<?php echo $table; ?>" <?php if($tipoTarifa==$record['id_tarifa']) echo 'selected="selected"'; ?>><?php echo $record['tarifa']; ?></option>
              <?php
              }
              ?>
            </select>            
            <!------------------- jQuery para guardar tarifa --------------------->
            <script>
            $(function(){
              $('.tarifa<?php echo $i; ?>').bind('change', function () {
                  var url = $(this).val();
                  if (url) {
                    var r = confirm("¿Desea cambiar la tarifa para este terreno?");
                    if(r == true){
                      window.location = url;
                    }
                  }
                  return false;
              });
            });						
          </script>
          <!------------------- jQuery para guardar tarifa --------------------->
        </div><!-- #tarifa-selector -->
        <div class="tarifa-msj<?php echo $i; ?> consumo-msj-style" style="margin-top:8px;">
          1. Modifique la tarifa
        </div><!-- .consumo-msj-style -->
        <div class="clear"></div>
        <div class="spacer_10"></div>
        <div id="btn-modificar-consumo">
        	<a class="consumo consumo-btn<?php echo $i; ?>" href="historicoConsumo.php?tid=<?php echo $row['id']; ?>&terreno=<?php echo $row['nombre']; ?>&tipoTarifa=<?php echo $tipoTarifa; ?>">
          	<div class="btn-show-options btn-agregar-historico">
            	Modificar Hist&oacute;rico de Consumo
            </div>
          </a>          
        </div><!-- #btn-modificar-consumo -->
        <div class="consumo-msj<?php echo $i; ?> consumo-msj-style">
          2. Ingrese datos hist&oacute;ricos
        </div><!-- .consumo-msj-style -->
        <div class="clear"></div>
          <?php
            extract(mysql_fetch_array(mysql_query("SELECT COUNT(id) AS total FROM $table;")));
          
            if (!$total ==0){            
              echo '<div class="spacer_20"></div>'."\n";
              echo '<h4 style="margin-bottom:5px;">Hist&oacute;rico de Consumo</h4>'."\n";
              echo '<table id="alta_proveedores" cellpadding="0" cellspacing="0" align="center">'."\n";
              echo '<tr><th width="50%"><h6 style="margin-bottom:0;">Fecha</h6></th><th><h6 style="margin-bottom:0;">Consumo</h6></th>';
							
							extract(mysql_fetch_array(mysql_query("SELECT COUNT(demanda) AS hay_demanda FROM ".$table." WHERE demanda != 'NULL'")));
							if($hay_demanda>0){
								echo '<th><h6 style="margin-bottom:0;">Demanda</h6></th>';
							}
							echo '</tr>'."\n";
							
              $qry = mysql_query("SELECT * FROM ".$table." ORDER BY ano, mes ASC");
              $j = 0;
               while($fila = mysql_fetch_array($qry)){
								if($j%2 == 0){
									$clase = 'par';
								}else{
									$clase = 'non';
								}
								echo '<tr>'."\n";
								echo '<td class="'.$clase.'">'.$fila['mes'].' - '.$fila['ano'].'</td><td class="'.$clase.'">'.$fila['consumo'].'</td>'."\n";
								if($fila['demanda'] != NULL){
								echo '<td class="'.$clase.'">'.$fila['demanda'].'</td>'."\n";
							}
								
								echo '</tr>'."\n";
								$j++;
							}
              echo '</table>'."\n";
            }          
          ?>
        </div><!-- .historico-container -->
      </td>
      <td>
      <?php 
				$qry = mysql_query("SELECT dia, mes, ano, consumo FROM `$table` ORDER BY ano,mes ASC;");
				if(mysql_num_rows($qry)==0){
					echo '<div class="grafica-msj-style">
									No existe hist&oacute;rico de consumo en este terreno, primero selecciona tu tarifa que se encuentra en tu recibo de CFE y despu&eacute;s ingresa el historial de tu consumo
								</div>';
				}else{
			?>
      <script type="text/javascript">
				// Load the Visualization API and the piechart package.
				google.load('visualization', '1.0', {'packages':['corechart']});
				
				// Set a callback to run when the Google Visualization API is loaded.
				google.setOnLoadCallback(drawChart);
				function drawChart() {
						var data = google.visualization.arrayToDataTable([
							['Fecha','Consumo'],
							<?php
								while($fila = mysql_fetch_array($qry)){
							?>
							['<?php echo $fila['mes']."-".$fila['ano']; ?>',<?php echo $fila['consumo']; ?>],
							<?php
								}
							?>
						]);				
						var options = {
												 'width':465,
												 'height':310,
												 'backgroundColor':'none',
												 'pointSize': 5,
												 'lineWidth': 2,
												 'fontSize': 10,
												 'title': 'Histórico de Consumo',
												 'animation.easing': 'in',
												 'animation.duration': 5000,
												 'legend.position': 'bottom',
												 'hAxis.title': 'Fecha',
												 'vAxis.title': 'Consumo'
												};
				
						var chart = new google.visualization.LineChart(document.getElementById('grafica<?php echo $i; ?>'));
						chart.draw(data, options);
					}				
				</script>
        <div id="grafica<?php echo $i; ?>"></div>
        <?php } ?>
      </td>
    </tr>    
  </table>
  <?php
			$i++;
		}
	}//if
	else{
		echo '<p>No ha agregado ning&uacute;n terreno, haga <a href="index.php?mod=4&act=1">click</a> aqu&iacute; para agregar uno.<a href="index.php?mod=4&act=1"><img src="images/terrenos.png" border="0" /></a></p>';
	}//else
	?>
</div><!-- acerca -->   