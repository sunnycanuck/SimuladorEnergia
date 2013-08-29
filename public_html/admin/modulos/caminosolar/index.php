<div class="prefix_1 grid_15"><h2>Camino Solar</h2></div>
<div class="prefix_1 grid_14 suffix_1">
<table cellpadding="0" cellspacing="0" border="0" id="activar_proveedores">
	<thead>
  	<tr>
    	<td id="izq">id</td>
      <td>Tiempo</td>
      <td>Azimuth</td>
      <td>Altura</td>
      <td>Int Cero</td>
      <td id="der">Int Uno</td>
    </tr>
    </thead>
    <?php
		$i = 0;
		$query = mysql_query("SELECT * FROM ce_camino_solar_48t ORDER BY id DESC LIMIT 500");
		while($row = mysql_fetch_array($query)){
			if($i%2==0){
				$clase = 'par';
			}else{
				$clase = 'non';
			}
			echo '<tr class="'.$clase.'">';
			echo '<td>'.$row['id'].'</td>';
			echo '<td>'.$row['tiempo'].'</td>';
			echo '<td>'.$row['az'].'</td>';
			echo '<td>'.$row['alt'].'</td>';
			echo '<td>'.$row['intcero'].'</td>';
			echo '<td>'.$row['intuno'].'</td>';
			echo '</tr>';
			$i++;
		}
		?>
  </thead>
  
  
</table>
</div>