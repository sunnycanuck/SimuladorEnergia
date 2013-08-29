<div class="prefix_1 grid_15"><h2>Seleccione el rango de fechas a generar</h2></div>
<div class="prefix_1 grid_14 suffix_1">
  <form action="sql.php?mod=4&act=1" method="post">
  <div>
  	<label for="desde">A&ntilde;o inicio</label>
    <input name="desde" style="width:100px;" />
    <label for="hasta">A&ntilde;o Final</label>
    <input name="hasta" style="width:100px;" />
    <input type="image" src="../images/btn_generar.png" value="" style="border:none; position:relative; top:19px;" />
  </div>
  </form>
  <div class="spacer_20"></div>
  <div id="msj">
  	<?php
			if(isset($_REQUEST['msj'])){
				switch($_REQUEST['msj']){
					case 1: echo '<h5 style="margin-bottom:0;">Se generar&oacute;n satisfactoriamente los siguientes a&ntilde;os:</h5>'.
												'<h4>'.$_REQUEST['ai'].' al '.$_REQUEST['af'].'</h4>';
												;break;
					case 2: echo '<h6 class="alert">Hubo un error al generar el a&ntilde;o: '.$_REQUEST['y'].'</h6>';break;
					case 3: echo '<h6>Se eliminaron las fechas correctamente</h6>';break;
				}
			}
		?>
  </div><!-- msj -->
  <div class="spacer_20"></div>
  <div><a href="sql.php?mod=4&act=2">Eliminar Fechas</a></div>
  <div class="spacer_20"></div>
  <div id="fechas_generador">
  	<table cellpadding="0" cellspacing="0" border="1" width="100%">
    	<thead>
        <tr>
          <td>A&ntilde;o</td>
          <td>Mes</td>
          <td># de dias</td>
          <td># de horas</td>
          <td># de lunes</td>
          <td># de martes</td>
          <td># de miercoles</td>
          <td># de jueves</td>
          <td># de viernes</td>
          <td># de sabados</td>
          <td># de domingos</td>
        </tr>
			</thead>
		<?php
			$i = 0;
			$query = mysql_query("SELECT * FROM `ce_horasDelMes` ORDER BY `ce_horasDelMes`.`ano` ASC, `ce_horasDelMes`.`mes` ASC");
			while($row = mysql_fetch_array($query)){
				if($i%2==0){
					$clase = 'par';
				}else{
					$clase = 'non';
				}
				
				echo '<tr class="'.$clase.'">';
				echo '<td>'.$row['ano'].'</td>';
				echo '<td>'.$row['mes'].'</td>';
				echo '<td>'.$row['num_dias'].'</td>';
				echo '<td>'.$row['num_horas'].'</td>';
				echo '<td>'.$row['num_lunes'].'</td>';
				echo '<td>'.$row['num_martes'].'</td>';
				echo '<td>'.$row['num_miercoles'].'</td>';
				echo '<td>'.$row['num_jueves'].'</td>';
				echo '<td>'.$row['num_viernes'].'</td>';
				echo '<td>'.$row['num_sabados'].'</td>';
				echo '<td>'.$row['num_domingos'].'</td>';
				echo '</tr>';
				
				$i++;
			}
		?>
    </table>
  </div><!-- fechas_generador -->
  
</div>