<form action="sql.php?mod=5&act=4" method="post">
  <input type="hidden" name="tarifa" value="<?php echo $tarifa; ?>" />
  <label for="mes_tarifa">Fecha</label><br />
  <input type="text" id="mes_tarifa" name="fecha" class="tarifas" style="width:80px;"/><br />
  <div class="spacer_10"></div>
  Basico<br />
  <input type="text" name="basico" /><br />
  <div class="spacer_10"></div>
  Intermedio<br />
  <input type="text" name="intermedio" /><br />
  <div class="spacer_10"></div>
  Exedente<br />
  <input type="text" name="exedente" /><br />
  <div class="spacer_10"></div>
  Carga Fijo<br />
  <input type="text" name="carga_fijo" /><br />
  <div class="spacer_10"></div>  
  <input type="image" src="../images/guardar.png" style="border:0;" />
</form>