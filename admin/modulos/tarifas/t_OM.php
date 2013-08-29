<form action="sql.php?mod=5&act=7" method="post">
  <input type="hidden" name="tarifa" value="<?php echo $tarifa; ?>" />
  <label for="mes_tarifa">Fecha</label><br />
  <input type="text" id="mes_tarifa" name="fecha" class="tarifas" style="width:80px;"/><br />
  <div class="spacer_10"></div>
  Peso Kw Demanda M&aacute;xima<br />
  <input type="text" name="peso_demanda" /><br />
  <div class="spacer_10"></div>
  Peso Kwh<br />
  <input type="text" name="peso_kwh" /><br />  
  <div class="spacer_10"></div>
  <input type="image" src="../images/guardar.png" style="border:0;" />
</form>