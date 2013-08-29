<div id="acerca">
  <h2>Seleccionar Terreno</h2>
  <div class="spacer_20"></div>
  <?php
	$uid = $_SESSION['userid'];
		
	$query = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS total FROM `ce_terreno` WHERE id_usuario = '$uid';"));
	if($query['total']!=0){	
		$query = mysql_query("SELECT * FROM ce_terreno WHERE id_usuario=$uid;");
		$i = 1;
		while($row = mysql_fetch_array($query)){
			if($i%2==0){
				$clase = 'non';
			}else{
				$clase = 'par';
			}
	?>
    <div id="casos_terrenos">
		<table cellpadding="0" cellspacing="0" border="0" id="casos_tabla">
			<tr>
				<td width="16" id="terreno_num" class="<?php echo $clase; ?>"><h1><?php echo $i; ?></h1></td>
				<td>
					<img src="images/icon_factory.png" border="0" style="float:left; margin:0 10px 0 10px;" />
					<h1 style="margin:0"><?php echo $row['nombre']; ?></h1>
				</td>
				<td width="203">
					<div align="center">
						<div class="spacer_5"></div>
						<a href="index.php?mod=6&act=2&terreno=<?php echo $row['nombre']; ?>&table=ce_casos_<?php echo $row['id']; ?>t&tid=<?php echo $row['id']; ?>"><img src="images/btn_seleccionar.png" border="0" /></a>
					</div>
				</td>
			</tr>
		</table>
    </div>
    <div class="spacer_10"></div>
<?php 
				$i++;
			}
			
	}//if
	else{
		echo '<p>No ha agregado ning&uacute;n terreno, haga <a href="index.php?mod=4&act=1">click</a> aqu&iacute; para agregar uno.<a href="index.php?mod=4&act=1"><img src="images/terrenos.png" border="0" /></a></p>';
	}//else
		?>
</div><!-- acerca -->


    