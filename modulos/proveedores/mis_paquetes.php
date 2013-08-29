<div id="proveedores" class="prefix_1 grid_14 suffix_1">
	<h2 style="margin-bottom:0;">Mis Paquetes</h2>
	<div style="margin-bottom:20px;"><a href="index.php?mod=3">Regresar</a></div>
  <div align="right">
  	<a href="index.php?mod=3&act=4"><img border="0" src="images/agregar_mis_dispositivos.png">Agregar Paquete</a></div>
    <table id="activar_proveedores" cellpadding="0" cellspacing="0" border="0" width="100%">
      <thead>
        <tr>
          <td id="izq"><a href="index.php?mod=3&act=6&orderby=nombre_pqt" target="_self">Nombre <img src="images/flecha_abajo.png" border="0" /></a></td>
          <td><a href="index.php?mod=3&act=6&orderby=precio" target="_self">Precio <img src="images/flecha_abajo.png" border="0" /></a></td>
          <td><a href="index.php?mod=3&act=6&orderby=dis1" target="_self">Cantidad | Grid Tie <img src="images/flecha_abajo.png" border="0" /></a></td>
          <td><a href="index.php?mod=3&act=6&orderby=dis2" target="_self">Fotovoltaico(s) <img src="images/flecha_abajo.png" border="0" /></a></td>
          <td id="der"><a>Opciones</a></td>
        </tr>
      </thead>
      <tbody>
  <?php
		$uid = $_SESSION['userid'];
		$orderby = $_REQUEST['orderby'];
    $query = mysql_query("SELECT * FROM ce_paquetes WHERE id_proveedor = ".$uid." ORDER BY '".$orderby."';");
    $i = 1;
    while($row = mysql_fetch_array($query)){
      if($i%2){
        $class = "par";
      }else{
        $class = "non";
      }
  ?>
      <tr class="<?php echo $class; ?>">
        <td><div align="center"><?php echo $row['nombre_pqt']; ?></div></td>
        <td><?php echo $row['precio']; ?></td>
        <td>
        	<div align="left" style="margin-left:10px;">
					<?php 
					$gridTie = explode("-", $row['dis1']);
					echo $gridTie[0].' | ';
					$qry = mysql_fetch_array(mysql_query("SELECT * FROM ce_dispositivos WHERE id_dis = ".$gridTie[1]." LIMIT 1;"));
					echo '<strong>Marca: </strong>'.$qry['marca']." <strong>Modelo: </strong>".$qry['modelo'];
					?>
          </div>
        </td>
        <td>
        	<div align="left">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <?php
            $dis2 = explode(";", $row['dis2']);
            $cuantosDis = count($dis2);
            for($i=0;$i<$cuantosDis-1;$i++){
              if($i%2){
                $class = "par2";
              }else{
                $class = "non2";
              }
            ?>
              <tr class="<?php echo $class; ?>">
            <?php
              $fotovol = explode("-", $dis2[$i]);
              $qry1 = mysql_fetch_array(mysql_query("SELECT * FROM ce_dispositivos WHERE id_dis = ".$fotovol[1]." LIMIT 1;"));
						?>	
								<td style="padding:0;">
									<table class="tabla_pqt_fotovol">
										<tr>
                    	<td><?php echo $fotovol[0]; ?></td>
              				<td><?php echo $qry1['marca']; ?></td>
											<td><?php echo $qry1['modelo']; ?></td>
                    </tr>
                	</table>
                </td>
            <?php
            }
            ?>
              </tr>
            </table>
          </div>
        </td>
        <td>
        	<div align="center">
						<a href="index.php?mod=3&act=7&pid=<?php echo $row['id_pqt']; ?>"><img src="images/btn_editar.png" border="0" title="Editar" /></a> 
						<a href="sql.php?mod=3&act=5&pid=<?php echo $row['id_pqt']; ?>"><img src="images/borrar.png" width="16" border="0" title="Eliminar Registro" /></a>
					</div>
        </td>
      </tr>
  <?php
      $i++;
    }
  ?>
      </tbody>
    </table>   
</div><!-- proveedores -->