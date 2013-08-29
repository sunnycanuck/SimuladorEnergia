<div class="prefix_12 grid_4 alpha right"> <a href="index.php?mod=2&act=1&actid=1"><img src="../images/agregar.png" border="0" /> Alta de Dispositivo</a></div>
<?php
if(empty($_REQUEST['orderby']))
	$_REQUEST['orderby'] = 'marca';

$orderby = $_REQUEST['orderby'];
$query = mysql_query("SELECT ce_dispositivos.id_dis, ce_dispositivos.tipo, ce_dispositivos.marca, ce_dispositivos.modelo, ce_dispositivos.precio_dispositivo, ce_dispositivos.precio_instalacion, ce_dispositivos.proveedor, ce_dispositivos.factores, ce_dispositivos.variables, ce_dispositivos.activado, ce_dispositivos_tipo.id_tipo, ce_dispositivos_tipo.nombre FROM ce_dispositivos JOIN ce_dispositivos_tipo WHERE ce_dispositivos.tipo = ce_dispositivos_tipo.id_tipo ORDER BY `ce_dispositivos`.`$orderby` ASC ");
?>
<table id="activar_proveedores" cellpadding="0" cellspacing="0" border="0" width="940">
	<thead>
  	<tr>
    	<td id="izq"><a href="index.php?mod=1&orderby=nombre" target="_self">Marca <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td><a href="index.php?mod=1&orderby=modelo" target="_self">Modelo <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td><a href="index.php?mod=1&orderby=precio_dispositivo" target="_self">Precio Dispositivo <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td><a href="index.php?mod=1&orderby=precio_instalacion" target="_self">Precio Instalaci&oacute;n <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td><a href="index.php?mod=1&orderby=proveedor" target="_self">Proveedor <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td><a href="index.php?mod=1&orderby=factores" target="_self">Factores <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td><a href="#" target="_self">Tipo <img src="../images/flecha_abajo.png" border="0" /></a></td>
      
      <td id="der"><a>Acciones <img src="../images/flecha_abajo.png" border="0" /></a></td>
    </tr>
  </thead>
  <tbody>
  	<?php
		$i=1;
		while($row = mysql_fetch_array($query)){
			if($row['activado']==0){
				$img = "desactivar";
				$act = "1";
				$title = "Activar Registro";
			}else{
				$img = "activar";
				$act = "2";
				$title = "Desactivar Registro";
			}
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
								<a href="sql.php?mod=2&act='.$act.'&pid='.$row['id_dis'].'"><img src="../images/'.$img.'.png" border="0" title="'.$title.'" /></a> 
								<a href="sql.php?mod=2&act=3&pid='.$row['id_dis'].'"><img src="../images/borrar.png" width="16" border="0" title="Eliminar Registro" /></a>
							</div>
						</td>';
			echo '</tr>';
			
			$i++;
		}
		?>
  </tbody>
</table>