<div>
  <h3 style="margin-bottom:0;"><img src="images/mis_dispositivos.png" title="Dispositivos" alt="Dispositivos" /> Dispositivos</h3>
  <div align="right">
	  <a href="index.php?mod=3&act=1"><img src="images/agregar_mis_dispositivos.png" border="0" /> Agregar Dispositivo</a>
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
					<td id="izq"><a href="index.php?mod=3&orderby=marca" target="_self">Marca <img src="images/flecha_abajo.png" border="0" /></a></td>
					<td><a href="index.php?mod=3&orderby=modelo" target="_self">Modelo <img src="images/flecha_abajo.png" border="0" /></a></td>
					<td><a href="index.php?mod=3&orderby=precio_dispositivo" target="_self">Precio Dispositivo <img src="images/flecha_abajo.png" border="0" /></a></td>
					<td><a href="index.php?mod=3&orderby=precio_instalacion" target="_self">Precio Instalaci&oacute;n <img src="images/flecha_abajo.png" border="0" /></a></td>
					<td><a href="index.php?mod=3&orderby=proveedor" target="_self">Proveedor <img src="images/flecha_abajo.png" border="0" /></a></td>
					<td><a href="index.php?mod=3&orderby=factores" target="_self">Factores <img src="images/flecha_abajo.png" border="0" /></a></td>
					<td><a href="#" target="_self">Tipo <img src="images/flecha_abajo.png" border="0" /></a></td>					
					<td id="der"><a>Opciones</a></td>
				</tr>
			</thead>
			<tbody>
				<?php
				if(mysql_num_rows($query)==0){
					echo '<tr class="par"><td colspan="8">No se encontraron registros</td></tr>';
				}else{
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
				}
				?>
			</tbody>
		</table>
	<div class="spacer_20"></div>
  
  <h3 style="margin-bottom:0;"><img src="images/mis_paquetes.png" title="Paquetes" alt="Paquetes" /> Paquetes</h3>
  <div align="right">
  	<a href="index.php?mod=3&act=4"><img src="images/agregar_mis_dispositivos.png" border="0" /> Agregar Paquete</a>
  </div>
  <table id="activar_proveedores" cellpadding="0" cellspacing="0" border="0" width="100%">
    <thead>
      <tr>
        <td id="izq"><a href="index.php?mod=3&order=nombre_pqt" target="_self">Nombre <img src="images/flecha_abajo.png" border="0" /></a></td>
        <td><a href="index.php?mod=3&order=precio" target="_self">Precio <img src="images/flecha_abajo.png" border="0" /></a></td>
        <td><a href="index.php?mod=3&order=dis1" target="_self">Cantidad | Grid Tie <img src="images/flecha_abajo.png" border="0" /></a></td>
        <td><a href="index.php?mod=3&order=dis2" target="_self">Fotovoltaico(s) <img src="images/flecha_abajo.png" border="0" /></a></td>
        <td id="der"><a>Opciones</a></td>
      </tr>
    </thead>
    <tbody>
  	<?php
		$uid = $_SESSION['userid'];
		$order = $_REQUEST['order'];
    $query = mysql_query("SELECT * FROM ce_paquetes WHERE id_proveedor = ".$uid." ORDER BY '".$order."';");
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
</div>
<div class="clear"></div>
<div class="spacer_25"></div>