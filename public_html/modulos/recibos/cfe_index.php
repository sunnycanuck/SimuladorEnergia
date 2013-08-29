<div id="acerca" class="prefix_1 grid_14 suffix_1 alpha">
	<h1>Agregar Recibo</h1>
  <div align="left">
  
		<?php
		$id = $_SESSION['userid'];
		$query = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS total FROM `ce_terreno` WHERE id_usuario = '$id';"));
		if($query['total']==0){
    ?>
		<p>No ha agregado ning&uacute;n terreno, haga <a href="index.php?mod=4&act=1">click</a> aqu&iacute; para agregar uno.<a href="index.php?mod=4&act=1"><img src="images/terrenos.png" border="0" /></a></p>
		<?php
    }else{
		?>
  
  	<form action="index.php?mod=5&act=2" method="post">
      <label>Seleccione un terreno:</label>
    	<select name="terreno" class="general">
      	<optgroup label="Seleccione un terreno">
				<?php
					$uid = $_SESSION['userid'];
          $query = mysql_query("SELECT id, nombre FROM `ce_terreno` WHERE id_usuario = '$uid';");
          while($row = mysql_fetch_array($query)){
        ?>
        <option value="<?php echo $row['id'];?>"><?php echo $row['nombre']; ?></option>
        <?php
          }
        ?>
        </optgroup>
      </select>
      <label>Seleccione una tarifa:</label>
      <select name="tarifa" class="general">
      	<optgroup label="Seleccione una tarifa">
				<?php
          $query = mysql_query("SELECT id_tarifa, tipo, epoca FROM `ce_tarifas_tipo` WHERE epoca = 'invierno';");
          while($row = mysql_fetch_array($query)){
        ?>	<option value="<?php echo $row['id_tarifa'];?>"><?php echo $row['tipo']; ?></option>
        <?php
          }
        ?>
        </optgroup>
      </select>
      <input type="image" src="images/btn_agregar.png" style="position:relative; top:14px;" />
    </form>
    <?php 
		}//else 
		?>
  </div>
  <div class="spacer_20"></div>  
</div><!-- main_izq -->


    