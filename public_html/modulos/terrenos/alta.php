<?php
/*-----------------------------------------------------------------------------
 Modificaciones
 ------------------------------------------------------------------------------
 Clave: HMN01
 Autor: Héctor Mora
 Descripción: Se ocultaron campos dx, dy y phi
 Fecha: 02-Noviembre-2012
 -------------------------------------------------------------------------------
*/

$uid = $_SESSION['userid'];
if(empty($_REQUEST['ubicacion'])){
	$_REQUEST['ubicacion'] = "31.862396,-116.607513";
}
$ubicacion = explode(",", $_REQUEST['ubicacion']);
$lat = $ubicacion[0];
$long = $ubicacion[1];
?>
<h6><a href="index.php?mod=4" style="color:darkblue;">Terrenos</a> > Alta de Terreno</h6>
<div class="spacer_10"></div>

<form action="sql.php?mod=4&act=1" method="post" class="altaTerreno">
<h2 style="margin-bottom:0;">Paso 1</h2>
<div>
  <input type="hidden" id="ubicacion2" name="ubicacion" />
  <input type="hidden" id="zoom2" name="zoom" />
</div>
<h4>Clic en la estaci&oacute;n mas cercana a su domicilio.</h4>
<div id="map2" style="width:940px; height:320px"></div>  
Estaci&oacute;n Clim&aacute;tica<br /><input style="width:100%;" type="text" id="estacionDes" name="estacionDes" class="general"  value="" readonly/>

<div class="spacer_20"></div>

<h2 style="margin-bottom:0;">Paso 2</h2>
<div>
  <input type="hidden" id="ubicacion" name="ubicacion" />
  <input type="hidden" id="zoom" name="zoom" />
  <input type="hidden" id="estacion" name="estacion" />
  <input type="hidden" id="dx" name="dx" value="10" />
 	<input type="hidden" id="dy" name="dy" value="10" />
 	<input type="hidden" id="phi" name="phi" value="0" />
  <input type="hidden" id="ubicacion" name="ubicacion" value="Ensenada" />
</div>
<h4>Arrastre el marcador en el mapa para ubicar su terreno.</h4>
<div id="map" style="width:940px; height:320px"></div>  


<div class="spacer_20"></div>

<h2 style="margin-bottom:0;">Paso 3</h2>
<h4>Favor de llenar los campos y haga clic en "Guardar".</h4>
<fieldset>

	<input type="hidden" name="uid" value="<?php echo $uid; ?>" />
  <table id="alta_proveedores" cellpadding="0" cellspacing="0" align="center">
    <tr>
      <td>Nombre*</td>
      <td><input id="nombre" name="nombre" type="text" class="general width96"></td>
    </tr>
    <tr>
      <td>Latitud*</td>
      <td>
      	<input id="latitude" name="latitude" type="text" class="general width96" value="<?php echo $lat; ?>"><br />
		Ej. 32º23'34.5" = 32.39292
      </td>
    </tr>
    <tr>
      <td>Longitud*</td>
      <td>
      	<input id="longitude" name="longitude" type="text" class="general width96" value="<?php echo $long; ?>"><br />
		Ej. 32º23'34.5" = 32.39292
      </td>
    </tr>
    <?php /*?><tr>
      <td>Ubicaci&oacute;n</td>
      <td>
        <select name="ubicacion" class="general width96">
        	<option value="Ensenada">Ensenada</option>
          <option value="Mexicali">Mexicali</option>
          <option value="Rosarito">Rosarito</option>
          <option value="Tecate">Tecate</option>
          <option value="Tijuana">Tijuana</option>
        </select>
      </td>
    </tr><?php */?>    
    <tr>
      <td colspan="2"><div align="right"><input id="guardar" type="image" value="" src="images/guardar.png" style="margin-right:4px;"></div></td>
    </tr>
  </table>
</form>
</fieldset>
<div id='loadingDiv' style="display:none;">
	Estamos guardando su información, por favor espere un momento...  <img src='images/loader.gif' />
</div>
<script type="text/javascript">
$('#guardar').click(function() {
	$('#loadingDiv').show("slow");
});
</script>
<script type="text/javascript">
//<![CDATA[

////map


var map = new GMap2(document.getElementById("map"));
//var start = new GLatLng(65,25);
map.setCenter(new GLatLng(<?php echo $lat; ?>,<?php echo $long; ?>), 4);
map.addControl(new GMapTypeControl());
map.addControl(new GLargeMapControl());

map.enableContinuousZoom(true);
map.enableDoubleClickZoom(true);
map.setZoom(10);


// "tiny" marker icon
var icon = new GIcon();
icon.image = "http://labs.google.com/ridefinder/images/mm_20_red.png";
icon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
icon.iconSize = new GSize(12, 20);
icon.shadowSize = new GSize(22, 20);
icon.iconAnchor = new GPoint(6, 20);
icon.infoWindowAnchor = new GPoint(5, 1);



/////Draggable markers

var point = new GLatLng(<?php echo $lat; ?>,<?php echo $long; ?>);
var markerD2 = new GMarker(point, {icon:G_DEFAULT_ICON, draggable: true});
map.addOverlay(markerD2);

markerD2.enableDragging();

GEvent.addListener(markerD2, "drag", function(){
var ubicacion = markerD2.getPoint().toUrlValue();
document.getElementById("ubicacion").value = ubicacion;
document.getElementById("zoom").value=map.getZoom();


var ubicaciones = ubicacion.split(",");
document.getElementById("latitude").value = ubicaciones[0];
document.getElementById("longitude").value = ubicaciones[1];


});

//]]>

var map2 = new GMap2(document.getElementById("map2"));

map2.setCenter(new GLatLng(<?php echo $lat; ?>,<?php echo $long; ?>), 4);
map2.addControl(new GMapTypeControl());
map2.addControl(new GLargeMapControl())
map2.enableContinuousZoom(true);
map2.enableDoubleClickZoom(true);
map2.setZoom(7);

<?php

$query = mysql_query("SELECT idEstacion, nombre, latitud, longitud FROM ce_estacionesclima");
$i = 2;

	while( $row = mysql_fetch_array($query) ){
		$id   = $row['idEstacion'];
		$nm   = $row['nombre'];
		$lat  = $row['latitud'];
		$long = $row['longitud'];

		if( strlen( $lat ) < 2 || strlen( $long ) < 2 ) {
			continue;
		}

		echo "var icon$i = new GIcon();\n";
		echo "icon$i.image = \"http://labs.google.com/ridefinder/images/mm_20_red.png\";\n";
		echo "icon$i.shadow = \"http://labs.google.com/ridefinder/images/mm_20_shadow.png\";\n";
		echo "icon$i.iconSize = new GSize(12, 20);\n";
		echo "icon$i.shadowSize = new GSize(22, 20);\n";
		echo "icon$i.iconAnchor = new GPoint(6, 20);\n";
		echo "icon$i.infoWindowAnchor = new GPoint(5, 1);\n";

		echo "var point$i = new GLatLng($lat, $long);\n";
		echo "var markerD2$i = new GMarker(point$i, {icon$i:G_DEFAULT_ICON, draggable: false});\n";
		echo "map2.addOverlay(markerD2$i);\n\n\n";
		echo "GEvent.addListener(markerD2$i, \"click\", function() { document.getElementById('estacion').value = '$id'; document.getElementById('estacionDes').value = '$nm'; });";

		$i ++;
	}



?>
</script>