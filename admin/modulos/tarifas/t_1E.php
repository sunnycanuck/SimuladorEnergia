<form action="sql.php?mod=5&act=2" method="post">
  <input type="hidden" name="tarifa" value="<?php echo $tarifa; ?>" />
  <label for="mes_tarifa">Fecha</label><br />
  <input type="text" id="mes_tarifa" name="fecha" class="tarifas" style="width:80px;"/><br />
  <div class="spacer_10"></div>
  Basico Bajo<br />
  <input type="text" name=	"basico_bajo" /><br />
  <div class="spacer_10"></div>
  Intermedio Bajo<br />
  <input type="text" name="intermedio_bajo" /><br />
  <div class="spacer_10"></div>
  Exedente Bajo<br />
  <input type="text" name="exedente_bajo" /><br />
  <div class="spacer_10"></div>
  Basico Alto<br />
  <input type="text" name="basico_alto" /><br />
  <div class="spacer_10"></div>
  Intermedio Alto<br />
  <input type="text" name="intermedio_alto" /><br />
  <div class="spacer_10"></div>
  Exedente Alto<br />
  <input type="text" name="exedente_alto" /><br />
  <div class="spacer_10"></div>  
  <input type="image" src="../images/guardar.png" style="border:0;" />
</form>