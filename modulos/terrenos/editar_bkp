<div id="acerca" class="prefix_1 grid_14 suffix_1 alpha">
  <div class="spacer_20"></div>
  <?php
	$uid = $_SESSION['userid'];
	$tid = $_REQUEST['tid'];
	$row = mysql_fetch_array(mysql_query("SELECT * FROM ce_terreno WHERE id=$tid AND id_usuario=$uid;"));
	?>
  
  <form action="sql.php?mod=4&act=2&tid=<?php echo $row['id']; ?>" method="post">
  <table cellpadding="0" cellspacing="0" border="0" id="terrenos_tabla">
  	<tr>
    	<td width="16" rowspan="3" id="terreno_num" class="par">&nbsp;</td>
    	<td id="terreno_nombre" colspan="2">
      	<img src="images/icon_factory.png" border="0" style="float:left; margin:0 10px 0 10px;" />
        <h1 style="margin:0"><input type="text" name="nombre" value="<?php echo $row['nombre']; ?>" /></h1>
      </td>
    	<td width="203" id="terreno_botones">
      	<div align="center">
	      	<div class="spacer_10"></div>
    	    <a href="index.php?mod=4&act=3&tid=<?php echo $row['id']; ?>"><img src="images/eliminar_btn.jpg" border="0" /></a>
        </div>
      </td>
   	</tr>
  	<tr>
  	  <td width="338" rowspan="2">
      	<div id="terreno_datos">
        	<div class="spacer_10"></div>
        	<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom:5px;">
          <tr>
          	<td width="32"><img src="images/icon_ubicacion.png" border="0" /></td>
          	<td width="85">Ubicaci&oacute;n:</td>
            <td width="170">
            	<select name="ubicacion">
              	<option value="Ensenada">Ensenada</option>
                <option value="Mexicali">Mexicali</option>
                <option value="Rosarito">Rosarito</option>
                <option value="Tecate">Tecate</option>
                <option value="Tijuana">Tijuana</option>
            	</select>
            </td>
          </tr>
          <tr>
          	<td><img src="images/icono_coordenadas.png" border="0" /></td>
          	<td>Coordenadas:</td>
	          <td>
            	<strong>Latitud:</strong> <span style="font-size:9px;">(Ej. 32º23'34.5" = 32.39292)</span><input type="text" name="latitude" value="<?php echo $row['latitude']; ?>" /><br />
							<strong>Longitud:</strong> <span style="font-size:9px;">(Ej. 32º23'34.5" = 32.39292)</span><input type="text" name="longitude" value="<?php echo $row['longitude']; ?>" />
            </td>
          </tr>
          <tr>
          	<td rowspan="2"><img src="images/icono_area.png" border="0" /></td>
          	<td rowspan="2">Area:</td>
	          <td>
            	<strong>Ancho:</strong> <span style="font-size:9px;">(dx)(Metros)</span><br /><input type="text" name="dx" value="<?php echo $row['dx']; ?>" /><br />
              <strong>Largo:</strong> <span style="font-size:9px;">(dy)(Metros)</span><br /><input type="text" name="dy" value="<?php echo $row['dy']; ?>" />
            </td>
          </tr>
          <tr>
            <td>
            	<div class="spacer_10"></div>
            	<div align="right"><input type="image" value="" src="images/guardar.png" style="margin-right:4px;"></div>
            </td>
          </tr>
          </table>
        </div>
      </td>
  	  <td width="153" class="center"><h5>Ubicaci&oacute;n en mapa</h5></td>
  	  <td class="center"><h5>Camino Solar</h5></td>
	  </tr>
  	<tr>
  	  <td>
      	<div align="center">
      		<iframe width="280" height="160" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
          	src="http://maps.google.com/maps?q=<?php echo $row['latitude']; ?>+<?php echo $row['longitude']; ?>&amp;ie=UTF8&amp;t=m&amp;z=14&amp;vpsrc=0&amp;ll=<?php echo $row['latitude']; ?>,<?php echo $row['longitude']; ?>&amp;output=embed">
         </iframe><br />
         <small>
         	<a href="http://maps.google.com/maps?q=<?php echo $row['latitude']; ?>+<?php echo $row['longitude']; ?>&amp;ie=UTF8&amp;t=m&amp;z=14&amp;vpsrc=0&amp;ll=<?php echo $row['latitude']; ?>,<?php echo $row['longitude']; ?>&amp;source=embed" target="_blank" style="color:#0000FF;text-align:left">Ver mapa mas grande
          </a>
         </small>
      	</div>
      </td>
  	  <td><div align="center"><img src="images/camino_solar_generico.jpg" border="0" /></div></td>
	  </tr>
  </table>
  </form>
</div><!-- main_izq -->


    