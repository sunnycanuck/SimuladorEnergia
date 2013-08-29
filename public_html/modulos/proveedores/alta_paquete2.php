<div id="proveedores" class="prefix_1 grid_14 suffix_1">
<?php
	if(!empty($_REQUEST['totalGtie'])){

		extract(mysql_fetch_array(mysql_query("SELECT precio_dispositivo + precio_instalacion AS totalGtie FROM ce_dispositivos WHERE id_dis=".$_REQUEST['gridTie'].";")));
		
		$fotovol = $_REQUEST['fotovol'];
		$numFotovol = $_REQUEST['numFotovol'];
		
		foreach($numFotovol as $numFotovols){
			if(!empty($numFotovols)||$numFotovols!="")
				$numFotovoltaicos[] = $numFotovols;
		}
		
		$cuantosFotovol = count($fotovol);
		$cuantosNumFotovol = count($numFotovoltaicos);
		
		if($cuantosFotovol == $cuantosNumFotovol){
	?>
			<h2 style="margin-bottom:0;">Agregar Paquete</h2>
			<div style="margin-bottom:20px;"><a href="index.php?mod=3">Regresar</a></div>
	<?php
			$total_disp = 0;
			foreach($fotovol as $fotovols){
				extract(mysql_fetch_array(mysql_query("SELECT precio_dispositivo + precio_instalacion AS total_disp FROM ce_dispositivos WHERE id_dis =".$fotovols.";")));				
				$total_disp_todos[] = $total_disp;
			}
			$i=0;
			while($i<$cuantosFotovol){
				$GtotalDis[] =  $total_disp_todos[$i] * $numFotovoltaicos[$i];
				$i++;
			}
			
			$total_dis = array_sum($GtotalDis);
			$total = number_format($totalGtie + $total_dis, 2, '.', '');
			//$total = round($totalGtie + $total_dis);
	?>
		<form action="sql.php?mod=3&act=4" method="post">
			<fieldset>
      	Paquete: <h3 style="margin-bottom:0;"><?php echo $_REQUEST['nombre_pqt'];?></h3>
				<legend>Precio Total</legend>
        <input type="hidden" name="gridTie" value="<?php echo $_REQUEST['gridTie']; ?>" />
        <input type="hidden" name="uid" value="<?php echo $_SESSION['userid']; ?>" />
        <input type="hidden" name="nombre_pqt" value="<?php echo $_REQUEST['nombre_pqt']; ?>" />
				<input type="hidden" name="total_auto" value="<?php echo $total; ?>" />
				<?php
					echo '<h3>$'.$total.'</h3>';
					foreach($fotovol as $fotovols){
						echo '<input type="hidden" name="fotovol[]" value="'.$fotovols.'">'."\r";
					}
					foreach($numFotovol as $numFotovols){
						if(!empty($numFotovols))
							echo '<input type="hidden" name="numFotovol[]" value="'.$numFotovols.'">'."\r";
					}
				?>
				<input name="totalGeneral" type="text" class="general"> Modificar precio total.
				<div class="spacer_20"></div>
				<input type="image" src="images/guardar.png" />
				
			</fieldset>    
		</form>  
	<?php					
		}// if
		else{
	?>
		<h2 style="margin-bottom:0;">Agregar Paquete</h2>
		<div style="margin-bottom:20px;"><a href="javascript:history.go(-1)">Regresar</a></div>
		<div class="error alpha"><p>Favor de llenar los campos correctamente.</p></div>		
	<?php
		}
	}// if empty
	else{	
?>
		<h2 style="margin-bottom:0;">Agregar Paquete</h2>
		<div style="margin-bottom:20px;">Ocurrio un error, haga click <a href="javascript:history.go(-1)">aqu&iacute;</a> para regresar</div>
<?php
	}
?>
</div><!-- proveedores -->