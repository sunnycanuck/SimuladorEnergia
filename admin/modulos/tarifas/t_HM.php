<form action="sql.php?mod=5&act=8" method="post">
  <input type="hidden" name="tarifa" value="<?php echo $tarifa; ?>" />
  <label for="mes_tarifa">Fecha</label><br />
  <input type="text" id="mes_tarifa" name="fecha" class="tarifas" style="width:80px;"/><br />
  <div class="spacer_10"></div>
  Cargo x Kw de Demanda Facturable<br />
  <input type="text" name="cargo_kw_fact" /><br />
  <div class="spacer_10"></div>
  Cargo x Kw Hora de Energ&iacute;a de Punta<br />
  <input type="text" name="cargo_ener_punta" /><br />
  <div class="spacer_10"></div>
  Cargo x Kw Hora de Energ&iacute;a Intermedia<br />
  <input type="text" name="cargo_ener_inter" /><br />
  <div class="spacer_10"></div>
  Cargo x Kw Hora de Energ&iacute;a Base<br />
  <input type="text" name="cargo_ener_base" /><br />
  <div class="spacer_10"></div>
  <input type="image" src="../images/guardar.png" style="border:0;" />
</form>