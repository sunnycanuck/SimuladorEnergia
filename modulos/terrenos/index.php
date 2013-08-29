<div id="acerca">
  <div align="right"><a href="index.php?mod=4&act=1"><img src="images/btn_agregar.png" border="0" /></a></div>
  <div class="spacer_20"></div>
  <?php
	$uid = $_SESSION['userid'];
	
	
	$query = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS total FROM `ce_terreno` WHERE id_usuario = '$uid';"));
	if($query['total']!=0){	
		$query = mysql_query("SELECT ce_terreno.id, ce_terreno.nombre, ce_terreno.latitude, ce_terreno.longitude, ce_terreno.dx, ce_terreno.dy, ce_estacionesclima.nombre AS estacion
FROM ce_terreno
JOIN ce_estacionesclima ON ce_terreno.estacionid = ce_estacionesclima.idEstacion
WHERE id_usuario = $uid;");
		$i = 1;
		while($row = mysql_fetch_array($query)){
			if($i%2==0){
				$clase = 'non';
			}else{
				$clase = 'par';
			}
				
			$area = $row['dx'] * $row['dy'];
		?>
		<table cellpadding="0" cellspacing="0" border="0" id="terrenos_tabla">
			<tr>
				<td width="16" rowspan="3" id="terreno_num" class="<?php echo $clase; ?>"><h1><?php echo $i; ?></h1></td>
				<td id="terreno_nombre" colspan="2">
					<img src="images/icon_factory.png" border="0" style="float:left; margin:0 10px 0 10px;" />
					<h1 style="margin:0"><?php echo $row['nombre']; ?></h1>
				</td>
				<td width="203" id="terreno_botones">
					<div align="center">
						<div class="spacer_10"></div>
						<a href="index.php?mod=4&act=2&tid=<?php echo $row['id']; ?>"><img src="images/modificar_btn.jpg" border="0" /></a>
						<a href="sql.php?mod=4&act=3&tid=<?php echo $row['id']; ?>" onclick="return confirm('¿Esta seguro de realizar esta acción?')"><img src="images/eliminar_btn.jpg" border="0" /></a>
					</div>
				</td>
			</tr>
			<tr>
				<td width="338" rowspan="2">
					<div id="terreno_datos">
						<div class="spacer_10"></div>
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
						<tr>
							<td width="32"><img src="images/icon_ubicacion.png" border="0" /></td>
							<td width="85">Estaci&oacute;n Clim&aacute;tica:</td>
							<td width="170"><?php echo $row['estacion']; ?></td>
						</tr>
						<tr>
							<td><img src="images/icono_coordenadas.png" border="0" /></td>
							<td>Coordenadas:</td>
							<td>
								<strong>Latitud:</strong> <?php echo $row['latitude']; ?><br />
								<strong>Longitud:</strong> <?php echo $row['longitude']; ?>
                <a class="tips" rel="qtip_files/terrenos/latlong.html" data-hasqtip="true">
									<img class="info-qtip-img" src="images/info.png" style="margin-left:0;">
								</a>
							</td>
						</tr>
						<?php /*?><tr>
							<td><img src="images/icono_area.png" border="0" /></td>
							<td>Area:</td>
							<td><?php echo $area; ?> m&sup2;</td>
						</tr><?php */?>
						</table>
					</div>
				</td>
				<td class="center" colspan="2"><h5>Ubicaci&oacute;n en mapa</h5></td>
				<!-- <td class="center"><h5>Camino Solar</h5></td> -->
			</tr>
			<tr>
				<td colspan="2">
					<div align="center" style="margin-top:10px;">
						<iframe width="535" height="260" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
							src="http://maps.google.com/maps?q=loc:<?php echo $row['latitude']; ?>,<?php echo $row['longitude']; ?>&amp;ie=UTF8&amp;t=m&amp;z=14&amp;iwloc=near&amp;vpsrc=0&amp;output=embed">
					 </iframe><br />
					 <small>
						<a href="http://maps.google.com/maps?q=loc:<?php echo $row['latitude']; ?>,<?php echo $row['longitude']; ?>&amp;ie=UTF8&amp;t=m&amp;z=14&amp;iwloc=near&amp;vpsrc=0&amp;output=embed" target="_blank" style="color:#0000FF;text-align:left">Ver mapa mas grande
						</a>
					 </small>
					</div>
				</td>
			<!-- 	<td>
        	<div class="spacer_30"></div>
          <div align="center">
          	<img src="images/camino_solar_generico.jpg" border="0" /><br /><a href="index.php?mod=4&act=3&tid=<?php echo $row['id']; ?>">Ver gr&aacute;fica</a>
          </div>
        </td> -->
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
</div><!-- main_izq -->


    