<p>
  Enseguida mostramos la lista actual de los proveedores quienes están participando en el proyecto. Los precios de instalación var&iacute;an con cada instalación, para los precios exactos usted tiene que contactar al proveedor directamente.
</p>
<p>
  Si desea participar como proveedor tiene que crear una cuenta de proveedor. La participaci&oacute;n es gratis y &iexcl;bienvenido!
</p>
<table id="activar_proveedores" cellpadding="0" cellspacing="0" border="0" width="100%">
  <thead>
    <tr>
      <td id="izq">Nombre</td>
      <td>Direcci&oacute;n</td>
      <td>Ciudad</td>
      <td>email</td>
      <td>Tel&eacute;fono</td>
      <td id="der">url</td>
    </tr>
  </thead>
  <tbody>
    <?php
      $query = mysql_query("SELECT * FROM ce_usuarios WHERE tipo = 2 AND activado = 1;");
      if(mysql_num_rows($query)==0){
        echo '<tr class="par"><td colspan="8">No se encontraron registros</td></tr>';
      }else{
        $i=1;
        while($row = mysql_fetch_array($query)){
          if($i%2){
            $class = "par";
          }else{
            $class = "non";
          }
          echo '<tr class="'.$class.'">';
            echo '<td>'.$row['nombre'].'</td>';
            echo '<td>'.$row['direccion'].'</td>';
            echo '<td>'.$row['ciudad'].'</td>';
            echo '<td>'.$row['correo'].'</td>';
            echo '<td>'.$row['tel'].'</td>';
            echo '<td><a href="'.$row['url'].'" target="_blank">'.$row['url'].'</a></td>';
          echo '</tr>';
          
          $i++;
        }
      }
    ?>
  </tbody>
</table>