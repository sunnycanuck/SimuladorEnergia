<?php
if(empty($_REQUEST['orderby']))
	$_REQUEST['orderby'] = 'nombre';

$orderby = $_REQUEST['orderby'];
$query = mysql_query("SELECT * FROM `ce_usuarios` WHERE `ce_usuarios`.`tipo` = 2 ORDER BY `ce_usuarios`.`$orderby` ASC ");
?>
<table id="activar_proveedores" cellpadding="0" cellspacing="0" border="0" width="940">
	<thead>
  	<tr>
    	<td id="izq"><a href="index.php?mod=3&orderby=nombre" target="_self">Nombre <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td><a href="index.php?mod=3&orderby=ciudad" target="_self">Ciudad <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td><a href="index.php?mod=3&orderby=direccion" target="_self">Direcci&oacute;n <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td><a href="index.php?mod=3&orderby=rfc" target="_self">R.F.C. <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td><a href="index.php?mod=3&orderby=correo" target="_self">email <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td><a href="index.php?mod=3&orderby=telefono" target="_self">Tel&eacute;fono <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td><a href="index.php?mod=3&orderby=fax" target="_self">url <img src="../images/flecha_abajo.png" border="0" /></a></td>
      <td id="der"><a href="index.php?mod=3&orderby=activado" target="_self">Acciones <img src="../images/flecha_abajo.png" border="0" /></a></td>
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
			echo '<td>'.$row['ciudad'].'</td>';
			echo '<td>'.$row['direccion'].'</td>';
			echo '<td>'.$row['rfc'].'</td>';
			echo '<td>'.$row['usuario'].'</td>';
			echo '<td>'.$row['tel'].'</td>';
			echo '<td>'.$row['url'].'</td>';
			echo '<td>
							<div align="center">
								<a href="sql.php?mod=1&act='.$act.'&pid='.$row['id_usuario'].'"><img src="../images/'.$img.'.png" border="0" title="'.$title.'" /></a> 
								<a href="sql.php?mod=1&act=3&pid='.$row['id_usuario'].'"><img src="../images/borrar.png" width="16" border="0" title="Eliminar Registro" /></a>
							</div>
						</td>';
			echo '</tr>';
			
			$i++;
		}
		?>
  </tbody>
</table>