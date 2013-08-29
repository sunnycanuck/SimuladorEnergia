<div class="prefix_1 grid_15"><h2>Tarifas</h2></div>
<div class="prefix_1 grid_14 suffix_1">
<?php
$tarifa = $_REQUEST['tarifa'];
$table = "ce_tarifas_".$tarifa;        
$parametros = 'id="tarifas"';
?>
	<p>Tarifa selecionada: <strong><?php echo $tarifa; ?></strong></p>
  <?php
	switch($tarifa){
		case "1E": require("modulos/tarifas/t_1E.php");break;
		case "1F": require("modulos/tarifas/t_1F.php");break;
		case "2": require("modulos/tarifas/t_2.php");break;
		case "3": require("modulos/tarifas/t_3.php");break;
		case "DAC": require("modulos/tarifas/t_DAC.php");break;
		case "OM": require("modulos/tarifas/t_OM.php");break;
		case "HM": require("modulos/tarifas/t_HM.php");break;
		default: require("modulos/tarifas/t_base.php");break;
	}
	?>
  <div class="spacer_20"></div>
  <?php
  display_db_table($table, TRUE, $parametros, $tarifa);					
  ?>    
</div>