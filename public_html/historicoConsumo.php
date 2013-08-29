<?php
session_start();
require ("conexion.php");
$table = "ce_cfe_consumohistorico_".$_REQUEST['tid']."t";
$tipoTarifa = $_REQUEST['tipoTarifa'];
if($tipoTarifa ==9 || $tipoTarifa==11)
	$demanda = 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Energía SIM - Calculador Energético</title>
<link rel="stylesheet" href="css/text.css" />
<link rel="stylesheet" href="css/960.css" />
<link rel="stylesheet" href="css/styles.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<?php if($_REQUEST['succ']==1){ ?>
<script>
	$(document).ready(function(e) {
    parent.location.href="index.php?mod=5&msj=1";
  });
</script>
	<?php exit; } ?>
<!-- <script type="text/javascript" src="ui/jquery-ui-1.8.16.custom.js"></script> -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/jquery-ui.min.js"></script>
<!-- <link rel="stylesheet" href="js/themes/base/jquery.ui.all.css"> -->
<!-- <link href="js/themes/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"> -->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.17/themes/redmond/jquery-ui.css">
<script src="js/jquery.validate.js" type="text/javascript"></script>
<script src="js/jquery.validation.functions.js" type="text/javascript"></script>
<!-- <script src="ui/jquery.ui.core.js"></script>
<script src="ui/jquery.ui.widget.js"></script>
<script src="ui/jquery.ui.datepicker.js"></script>
 -->
<script type="text/javascript">
	$(document).ready(function(){
		$('.historial').datepicker( {
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'yy-mm-dd',
			closeText: 'Ok',
			currentText: 'Hoy',
			onClose: function(dateText, inst) {
				var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
				var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
				$(this).datepicker('setDate', new Date(year, month, 1));
			}
		});
	});
	$(document).ready(function(){
		field_count = 0;
		$("#agregar").trigger('click'); 
		$("#agregar").click(function(){
			field_count++;
			var new_field = jQuery("<div></div>");
			new_field.attr("id","parametros"+field_count);
			var datepicker_id = ('id="datepicker_'+field_count+'"');
			var tabla = jQuery("<table></table>");
			tabla.attr("id", "tabla"+field_count);
			tabla.attr("class", "tabla");								
			$('#consumo_inputs ul li').append(new_field);
			$('#parametros'+field_count).append(tabla);								
			$('#tabla'+field_count).append('<tr><td><span class="fecha">Fecha:</span> <input name="historial[]" type="text" '+datepicker_id+' class="general2 consumo_historico" /></td><td><span class="consumo">Consumo:</span> <input type="text" class="general2 consumo_historico" name="consumo_historico[]" /></td><?php if($demanda==1){?><td><span class="consumo">Demanda:</span> <input type="text" class="general2 consumo_historico" name="demanda[]" /></td><?php } ?></tr>');
			$('#tabla'+field_count).append("<tr colspan=\"2\"><td><div id=\"spacer\" class=\"spacer_10\"></div></td></tr>");
			$("#datepicker_"+field_count).datepicker({
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true,
				dateFormat: 'yy-mm-dd',
				closeText: 'Ok',
				currentText: 'Hoy',
				onClose: function(dateText, inst) {
					var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
					var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
					$(this).datepicker('setDate', new Date(year, month, 1));
				}
			});						
		});		
		$("#eliminar").click(function() {
			if(field_count >= 1){
				$("#consumo_inputs ul li table:last").remove();
				field_count--;
			}
		});
	});	
	<!-- agregar un input con jquery -->
	
	<!-- Validar campos con jquery -->
	$(document).ready(function(){
		jQuery(function(){
			jQuery("#historial").validate({
					expression: "if (VAL) return true; else return false;",
					message: "Favor de llenar el campo"
			});
			jQuery("#consumos").validate({
					expression: "if (VAL) return true; else return false;",
					message: "Favor de llenar el campo"
			});
			jQuery("#demanda").validate({
					expression: "if (VAL) return true; else return false;",
					message: "Favor de llenar el campo"
			});
			jQuery('.AdvancedForm').validated(function(){
				liga(function(url){
					document.location.href=url;
				});
			});
		});
});
	<!-- Validar campos con jquery -->
</script>
<style>
body{
	color:#FFFFFF;	
	font:12px/1.5 'Trebuchet MS', Arial, 'Liberation Sans', FreeSans, sans-serif;
	background:#000330 url(../images/signin_bg.jpg) top no-repeat;
	margin-left:auto;
	margin-right:auto;
}
.ui-datepicker-calendar{
	display: none;
}
.agregar-contenido{
	width:100%;
}
</style>
</head>

<body>
<div class="agregar-contenido">
	<img src="images/icon_factory.png" border="0" style="float:left; margin:0 10px 0 10px;" />
  <h1 style="margin:0"><?php echo $_REQUEST['terreno']; ?></h1>
  <div class="clear"></div>
  <div style="padding:0 10px;">
    <cite>Estos datos ser&aacute;n usados para los c&aacute;lculos de un consumo promedio que abarcar&aacute;n por lo menos un a&ntilde;o.</cite>
		<div class="historico-container-box">
      <?php				
        extract(mysql_fetch_array(mysql_query("SELECT COUNT(id) AS total FROM $table;")));
      
        if (!$total ==0)
        {
          echo '<div class="spacer_20"></div>'."\n";
          echo '<h4 style="margin-bottom:5px;">Hist&oacute;rico de Consumo</h4>'."\n";
          echo '<table id="alta_proveedores" cellpadding="0" cellspacing="0" align="center" style="color:#000;">'."\n";
					if($demanda==1){
						$th = '<th><p class="historico-ths">Demanda</p></th>';
						$td = '<td class="'.$clase.'">'.$fila['demanda'].'</td>'."\n";
						$borrar = '&tipoTarifa='.$_REQUEST['tipoTarifa'];
					}
          echo '<tr><th><p class="historico-ths">Fecha</p></th><th><p class="historico-ths">Consumo</p></th>'.$th.'<th><p class="historico-ths">Borrar</p></th></tr>'."\n";
          $qry = mysql_query("SELECT * FROM ".$table." ORDER BY ano,mes ASC");
          $j = 0;
          while($fila = mysql_fetch_array($qry)){
            if($j%2 == 0){
              $clase = 'par';
            }else{
              $clase = 'non';
            }
            echo '<tr>'."\n";
            echo '<td class="'.$clase.'">'.$fila['mes'].' - '.$fila['ano'].'</td><td class="'.$clase.'">'.$fila['consumo'].'</td>'."\n";
						if($demanda==1){
						echo '<td class="'.$clase.'">'.$fila['demanda'].'</td>'."\n";
					}
						echo '<td class="'.$clase.'">
										<a href="sql.php?mod=5&act=2&rid='.$fila['id'].'&terreno='.$_REQUEST['terreno'].'&tid='.$_REQUEST['tid'].$borrar.'" onclick="return confirm(\'¿Esta seguro de realizar esta acción?\')"><img src="images/borrar.png" width="20" border="0" /></a>
									</td>'."\n";
            echo '</tr>'."\n";
            $j++;
          }
          echo '</table>'."\n";
        }else{
					 echo '<h4 style="margin-bottom:5px;">No existe historico de consumo, por favor capture datos en los campos mostrados a continuaci&oacute;n</h4>'."\n";
				}
      ?>
    </div><!-- .historico-container -->        
  <form action="sql.php?mod=5&act=5" method="post" class="AdvancedForm">
  	<input type="hidden" name="table" value="<?php echo $table; ?>" />
    <input type="hidden" name="terreno" value="<?php echo $_REQUEST['terreno']; ?>" />
    <input type="hidden" name="tid" value="<?php echo $_REQUEST['tid']; ?>" />
		<h4 style="margin-bottom:5px;">Agregar Registros al Hist&oacute;rico</h4>
    <div id="consumo_inputs">
      <img class="agregar<?php echo $i; ?>" id="agregar" src="images/btn_agregar.png"/> <img class="eliminar<?php echo $i; ?>" id="eliminar" src="images/eliminar_btn.jpg" /> 
      <div class="clear"></div>
      <ul style="margin:0; padding-left:0;">
        <li>
          <div id="parametros0">
            <table id="tabla0" class="tabla">
              <tbody>
                <tr>
                  <td>
                    <span class="fecha">Fecha:</span>
                    <input id="historial" class="general2 consumo_historico historial" type="text" name="historial[]">
                  </td>
                  <td>
                    <span class="consumo">Consumo:</span>
                    <input id="consumos" class="general2 consumo_historico" type="text" name="consumo_historico[]">
                  </td>
                  <?php 
									if($demanda==1){ 
									?>
                  <td>
                    <span class="consumo">Demanda:</span>
                    <input id="demanda" class="general2 consumo_historico" type="text" name="demanda[]">
                    <input type="hidden" name="tipoTarifa" value="<?php echo $_REQUEST['tipoTarifa']; ?>" />
                  </td>
                  <?php 
									}
									?>
                </tr>
                <tr colspan="2">
                  <td>
                    <div id="spacer" class="spacer_10"></div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </li>
      </ul>      
     <input type="image" src="images/guardar.png" /> <a onclick="javascript:parent.$.fancybox.close();" style="cursor:pointer;"><img src="images/cerrar-lightbox.png" border="0" /></a>
    </div><!-- #consumo_inputs -->
  </div>
  </form>
</div><!-- #agregar-contenido -->
</body>
</html>