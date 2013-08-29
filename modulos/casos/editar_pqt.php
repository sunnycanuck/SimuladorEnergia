<h2 style="margin-bottom:0;">Seleccione el paquete</h2>
<form action="index.php?mod=6&act=10" method="post">
	<input type="hidden" name="table" value="<?php echo $_REQUEST['table']; ?>" />
	<input type="hidden" name="caso" value="<?php echo $_REQUEST['caso']; ?>" />
	<input type="hidden" name="tid" value="<?php echo $_REQUEST['tid']; ?>" />
  <input type="hidden" name="terreno" value="<?php echo $_REQUEST['terreno']; ?>" />
<table border="0" cellpadding="0" cellspacing="0" id="activar_proveedores">
  <thead>
    <tr>
      <td id="izq">Selecionar</td>
      <td>Paquete</td>
      <td>Precio</td>
      <td>Dis1</td>
      <td>Dis2</td>
      <td id="der">Proveedor</td>
    </tr>
  </thead>
<?php
$uid = $_SESSION['userid'];
$query = mysql_query("SELECT ce_paquetes.id_pqt, ce_paquetes.id_proveedor, ce_paquetes.nombre_pqt, ce_paquetes.precio, ce_paquetes.dis1, ce_paquetes.dis2, ce_usuarios.nombre FROM `ce_paquetes` INNER JOIN ce_usuarios WHERE id_proveedor = id_usuario");
$i = 0;
while($row = mysql_fetch_array($query)){
  if($i%2==0){
      $clase = 'par';
    }else{
      $clase = 'non';
    }
		$dis1 = explode('-', $row['dis1']);
		$numGridtie = $dis1[0];
		$gridTie = $dis1[1];
		$linea = mysql_fetch_array(mysql_query("SELECT ce_dispositivos.id_dis, ce_dispositivos.tipo, ce_dispositivos.marca, ce_dispositivos.modelo, ce_dispositivos_tipo.nombre FROM ce_dispositivos INNER JOIN ce_dispositivos_tipo WHERE tipo = id_tipo AND id_dis = ".$gridTie.";"));
?>
  <tr class="<?php echo $clase; ?>">
  	<td><input type="radio" name="id_pqt" value="<?php echo $row['id_pqt']; ?>" /></td>
    <td><?php echo $row['nombre_pqt']; ?></td>
    <td><?php echo $row['precio']; ?></td>
    <td>
			<table>
      	<thead>
          <tr>
            <td>Cantidad</td>
            <td>Marca</td>
            <td>Modelo</td>
            <td>Tipo</td>
          </tr>
        </thead>
				<tr>
					<td><?php echo $numGridtie; ?></td>
          <td><?php echo $linea['marca']; ?></td>
          <td><?php echo $linea['modelo']; ?></td>
          <td><?php echo $linea['nombre']; ?></td>
       </tr>
     </table>
    <td>
    	<table>
      	<thead>
          <tr>
            <td>Cantidad</td>
            <td>Marca</td>
            <td>Modelo</td>
            <td>Tipo</td>
          </tr>
        </thead>
			<?php 
				$que = explode(';', $row['dis2']);
				$j = 0; 
				$cuantos = count($que)-1;
				while($j < $cuantos){
					$dis2 = explode('-', $que[$j]);
					$numDis2 = $dis2[0];
					$linea2 = mysql_fetch_array(mysql_query("SELECT ce_dispositivos.id_dis, ce_dispositivos.tipo, ce_dispositivos.marca, ce_dispositivos.modelo, ce_dispositivos_tipo.nombre FROM ce_dispositivos INNER JOIN ce_dispositivos_tipo WHERE tipo = id_tipo AND id_dis = ".$dis2[1].";"));
				?>
        <tr>
					<td><?php echo $numDis2; ?></td>
          <td><?php echo $linea2['marca']; ?></td>
          <td><?php echo $linea2['modelo']; ?></td>
          <td><?php echo $linea2['nombre']; ?></td>
       </tr>
        <?php
					$j++;
				}
			?>
      </table>
    </td>
    <td><?php echo $row['nombre']; ?></td>
  </tr>
<?php
	$i++;
}
?>
	<tr>
  	<td colspan="6"><div align="right"><input type="image" src="images/btn_agregar_l.png" /></div></td>
  </tr>
</table>
</form>