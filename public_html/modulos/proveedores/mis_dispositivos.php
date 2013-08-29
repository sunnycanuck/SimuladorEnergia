<div id="acerca" class="prefix_1 grid_14 suffix_1 alpha">
  <h2 style="margin-bottom:0;">Mis Dispositivos</h2>
	<div style="margin-bottom:20px;"><a href="javascript:history.go(-1)">Regresar</a></div>
  <div align="right">
  	<a href="index.php?mod=3"><img border="0" src="images/agregar_mis_dispositivos.png">Agregar Dispositivo</a>
  </div>
  <?php
		if(empty($_REQUEST['orderby']))
			$_REQUEST['orderby'] = 'marca';
		
		$orderby = $_REQUEST['orderby'];
		$query = mysql_query("SELECT ce_dispositivos.id_dis, ce_dispositivos.tipo, ce_dispositivos.marca, ce_dispositivos.modelo, ce_dispositivos.precio_dispositivo, ce_dispositivos.precio_instalacion, ce_dispositivos.proveedor, ce_dispositivos.factores, ce_dispositivos.variables, ce_dispositivos.activado, ce_dispositivos_tipo.id_tipo, ce_dispositivos_tipo.nombre, ce_usuarios.id_usuario FROM ce_dispositivos JOIN ce_dispositivos_tipo JOIN ce_usuarios WHERE ce_dispositivos.tipo = ce_dispositivos_tipo.id_tipo AND ce_dispositivos.id_proveedor = ".$_SESSION['userid']." AND ce_usuarios.id_usuario = ".$_SESSION['userid']." ORDER BY `ce_dispositivos`.`".$orderby."` ASC ");
		?>
		<table id="activar_proveedores" cellpadding="0" cellspacing="0" border="0" width="940">
			<thead>
				<tr>
					<td id="izq"><a href="index.php?mod=3&act=2&orderby=marca" target="_self">Marca <img src="images/flecha_abajo.png" border="0" /></a></td>
					<td><a href="index.php?mod=3&act=2&orderby=modelo" target="_self">Modelo <img src="images/flecha_abajo.png" border="0" /></a></td>
					<td><a href="index.php?mod=3&act=2&orderby=precio_dispositivo" target="_self">Precio Dispositivo <img src="images/flecha_abajo.png" border="0" /></a></td>
					<td><a href="index.php?mod=3&act=2&orderby=precio_instalacion" target="_self">Precio Instalaci&oacute;n <img src="images/flecha_abajo.png" border="0" /></a></td>
					<td><a href="index.php?mod=3&act=2&orderby=proveedor" target="_self">Proveedor <img src="images/flecha_abajo.png" border="0" /></a></td>
					<td><a href="index.php?mod=3&act=2&orderby=factores" target="_self">Factores <img src="images/flecha_abajo.png" border="0" /></a></td>
					<td><a href="#" target="_self">Tipo <img src="images/flecha_abajo.png" border="0" /></a></td>
					
					<td id="der"><a>Opciones</a></td>
				</tr>
			</thead>
			<tbody>
				<?php
				$i=1;
				while($row = mysql_fetch_array($query)){
					if($i%2){
						$class = "par";
					}else{
						$class = "non";
					}				
					echo '<tr class="'.$class.'">';
					echo '<td>'.$row['marca'].'</td>';
					echo '<td>'.$row['modelo'].'</td>';
					echo '<td>'.$row['precio_dispositivo'].'</td>';
					echo '<td>'.$row['precio_instalacion'].'</td>';
					echo '<td>'.$row['proveedor'].'</td>';
					echo '<td>'.$row['factores'].'</td>';
					echo '<td>'.$row['nombre'].'</td>';
					echo '<td>
									<div align="center">
										<a href="index.php?mod=3&act=3&did='.$row['id_dis'].'"><img src="images/btn_editar.png" border="0" title="'.$title.'" /></a> 
										<a href="sql.php?mod=3&act=2&did='.$row['id_dis'].'"><img src="images/borrar.png" width="16" border="0" title="Eliminar Registro" /></a>
									</div>
								</td>';
					echo '</tr>';
					
					$i++;
				}
				?>
			</tbody>
		</table>
  
</div><!-- main_izq -->


    