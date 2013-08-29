<div class="prefix_12 grid_4 alpha right"> <a href="index.php?mod=2&act=2&actid=1"><img src="../images/agregar.png" border="0" /> Alta de tipo de dispositivo</a></div>
<?php
if(empty($_REQUEST['orderby']))
	$_REQUEST['orderby'] = 'nombre';

$orderby = $_REQUEST['orderby'];
$query = mysql_query("SELECT * FROM ce_dispositivos_tipo ORDER BY `ce_dispositivos_tipo`.`$orderby` ASC ");
?>
<table id="activar_proveedores" cellpadding="0" cellspacing="0" border="0" width="940">
	<thead>
  	<tr>
    	<td id="izq"><a href="index.php?mod=2&orderby=nombre" target="_self">Nombre <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td><a href="index.php?mod=2&orderby=factores_Nombres" target="_self">Factores Nombres <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td><a href="index.php?mod=2&orderby=factores_Unidades" target="_self">Factores Unidades <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td><a href="index.php?mod=2&orderby=variables_Nombres" target="_self">Variables Nombres <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td><a href="index.php?mod=2&orderby=variables_Unidades" target="_self">Variables Unidades <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td><a href="index.php?mod=2&orderby=medio_Ambiente" target="_self">Medio Ambiente <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td id="der"><a href="index.php?mod=2&orderby=activado" target="_self">Acciones <img src="../images/flecha_abajo.png" border="0" /></a></td>
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
			echo '<td>'.$row['nombre'].'</td>';
			echo '<td>'.$row['factores_Nombres'].'</td>';
			echo '<td>'.$row['factores_Unidades'].'</td>';
			echo '<td>'.$row['variables_Nombres'].'</td>';
			echo '<td>'.$row['variables_Unidades'].'</td>';
			echo '<td>'.$row['medio_Ambiente'].'</td>';
			echo '<td>
							<div align="center">
								<a href="sql.php?mod=3&act='.$act.'&pid='.$row['id_tipo'].'"><img src="../images/'.$img.'.png" border="0" title="'.$title.'" /></a> 
								<a href="sql.php?mod=3&act=3&pid='.$row['id_tipo'].'"><img src="../images/borrar.png" width="16" border="0" title="Eliminar Registro" /></a>
							</div>
						</td>';
			echo '</tr>';
			
			$i++;
		}
		?>
  </tbody>
</table>