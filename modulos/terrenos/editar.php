<div id="acerca">
  <h6><a href="index.php?mod=4" style="color:darkblue;">Terrenos</a> > Editar Terreno</h6>
  <div class="spacer_10"></div>
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
	$tid = $_REQUEST['tid'];
	$row = mysql_fetch_array(mysql_query("SELECT * FROM ce_terreno WHERE id=$tid AND id_usuario=$uid;"));
	$lat = $row['latitude'];
	$long= $row['longitude'];
	?>

  <form action="sql.php?mod=4&act=2&tid=<?php echo $row['id']; ?>" method="post">
  <table cellpadding="0" cellspacing="0" border="0" id="terrenos_tabla">
  	<tr>
    	<td width="16" rowspan="3" id="terreno_num" class="par">&nbsp;</td>
    	<td id="terreno_nombre" colspan="2">
      	<div class="spacer_10"></div>
      	<img src="images/icon_factory.png" border="0" style="float:left; margin:0 10px 0 10px;" />
        <h5 style="margin:0">Nombre del Terreno: <input type="text" name="nombre" value="<?php echo $row['nombre']; ?>" /></h5>
      </td>
    	<td width="203" id="terreno_botones">      	
      </td>
   	</tr>
  	<tr>
  	  <td width="338" rowspan="2">
      	<div id="terreno_datos">
        	<div class="spacer_10"></div>
        	<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom:5px;">
          <tr>
          	<td width="32"><img src="images/icon_ubicacion.png" border="0" /></td>
          	<td width="85">Estaci&oacute;n Clim&aacute;tica:</td>
            <td width="170">
            	<select name="estacion">
              <?php
							extract(mysql_fetch_array(mysql_query("SELECT estacionid FROM ce_terreno WHERE id = $tid;")));
							$query = mysql_query("SELECT * FROM ce_estacionesclima");
							while($row = mysql_fetch_array($query)){							
							?>
              	<option value="<?php echo $row['idEstacion']; ?>" <?php if($row['idEstacion']==$estacionid) echo 'selected="selected"'; ?>><?php echo $row['nombre']; ?></option>
              <?php
							}
							?>
            	</select>
            </td>
          </tr>
          <tr>
          	<td><img src="images/icono_coordenadas.png" border="0" /></td>
          	<td>Coordenadas:</td>
	          <td><input type="hidden" id="ubicacion"/><input type="hidden" id="zoom"/>
            	<strong>Latitud:</strong> <span style="font-size:9px;">(Ej. 32Âº23'34.5" = 32.39292)</span><input type="text" name="latitude" id="latitude" value="<?php echo $lat; ?>" /><br />
							<strong>Longitud:</strong> <span style="font-size:9px;">(Ej. 32Âº23'34.5" = 32.39292)</span><input type="text" name="longitude" id="longitude" value="<?php echo $long; ?>" />
            </td>
          </tr>
          <tr>
            <td colspan="3">
            	<div class="spacer_10"></div>
            	<div align="right">
              	<input type="hidden" name="dx" value="<?php echo $row['dx']; ?>" /><input type="hidden" name="dy" value="<?php echo $row['dy']; ?>" />
            	  <input type="image" value="" src="images/guardar.png" style="margin-right:4px;">
              </div>
            </td>
          </tr>
          </table>
        </div>
      </td>
  	  <td colspan="2" class="center"><h5>Ubicaci&oacute;n en mapa</h5></td>
  	  <!-- <td class="center"><h5>Camino Solar</h5></td> -->
	  </tr>
  	<tr>
  	  <td colspan="2">
  	  	<div id="map" style="width:535px; height:260px"></div>
  	  </td>
      	<!--div align="center">
      		<iframe width="435" height="260" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
          	src="http://maps.google.com/maps?q=loc:<?php echo $lat; ?>,<?php echo $long; ?>&amp;ie=UTF8&amp;t=m&amp;z=14&amp;iwloc=near&amp;vpsrc=0&amp;output=embed">
         </iframe><br />
         <small>
         	<a href="http://maps.google.com/maps?q=loc:<?php echo $lat; ?>,<?php echo $long; ?>&amp;ie=UTF8&amp;t=m&amp;z=14&amp;iwloc=near&amp;vpsrc=0&amp;output=embed" target="_blank" style="color:#0000FF;text-align:left">Ver mapa mas grande
          </a>
         </small>
      	</div>
      </td>
  	  <!-- <td><div align="center"><img src="images/camino_solar_generico.jpg" border="0" /></div></td> -->
	  </tr>
  </table>
  </form>
</div><!-- main_izq -->




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
map.setZoom(14);


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

</script>