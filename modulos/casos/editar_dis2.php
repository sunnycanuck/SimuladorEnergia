<script language="JavaScript">

/**
---------------------------------------------------------------------
MODIFICACIONES
---------------------------------------------------------------------
Clave: HMN01
Autor: Héctor Mora
Fecha: 01/Octubre/2012
Cambio: Cuando se daba de alta un dispositivo fotovoltaico, siempre se tomaban los datos
       del primer registro.
---------------------------------------------------------------------
Clave: HMN02
Autor: Héctor Mora
Fecha: 19/Octubre/2012
Cambio: Se cambiaron las columnas Altura <---> Azimuth
---------------------------------------------------------------------
Clave: HMN03
Autor: Héctor Mora
Fecha: 29/Noviembre/2012
Cambio: Se eliminaron las columnas X Y Z
---------------------------------------------------------------------
clave: RE01
Author: Rodger Evans
Fecha: 12/Abril/2013
cambio: names a titles
---------------------------------------------------------------------
*/



	function validaChecados() { //HMN01
	   var total_dispositivos = document.getElementById("totaldispositivos").value/1;
	   var i = 0;
	   var checados = "";
	   var ids = "";

       for( i = 0; i < total_dispositivos; i ++ ) {
       		if( document.getElementById("chk_" + i ).checked ) {
       			checados += "" + i + ",";
       			ids      += document.getElementById("chk_" + i ).value + ",";
       		}
       }

       document.getElementById("checados").value = checados;
       document.getElementById("ids").value = ids;

	}
</script>
<form action="sql.php?mod=6&act=1" method="post" onsubmit="validaChecados();">
  <input type="hidden" name="gtid" value="<?php echo $_REQUEST['gtid'] ?>" />
  <input type="hidden" name="id_tipo" value="<?php echo $_REQUEST['id_tipo']; ?>" />
  <input type="hidden" name="table" value="<?php echo $_REQUEST['table'] ?>" />
  <input type="hidden" name="caso" value="<?php echo $_REQUEST['caso']; ?>" />
  <input type="hidden" name="tid" value="<?php echo $_REQUEST['tid']; ?>" />
  <input type="hidden" name="terreno" value="<?php echo $_REQUEST['terreno']; ?>" />
  <input type="hidden" name="checados" id = "checados"><!--HMN01 -->
  <input type="hidden" name="ids" id = "ids"><!--HMN01 -->
  <input name="equis[]" type="hidden" />
  <input name="ye[]" type="hidden"/>
  <input name="zeta[]" type="hidden"/>

  <img src="images/pasos_gridtie_fv2.jpg" border="0" />
  <div class="spacer_10"></div>
  <table border="0" cellpadding="0" cellspacing="0" id="activar_proveedores">
    <thead>
      <tr>
        <td id="izq">Cantidad</td>
        <td>Marca</td>
        <td>Modelo</td>
        <td>Precio</td>
        <td>Instalaci&oacute;n</td>
        <td>Proveedor</td>
        <td>Acimut <a class="wideWidth" rel="qtip_files/casos/azimuth.html"><img class="info-qtip-img" src="images/info.png" /></a></td> <!--RE01-->
        <td id="der">Altura <a class="wideWidth" rel="qtip_files/casos/altura.html"><img class="info-qtip-img" src="images/info.png" /></a></td>
        <!--HMN03td>X</td>
        <td>Y</td>
        <td id="der">Z</td-->
      </tr>
    </thead>
    <?php
    $i = 0;
    $query = mysql_query("SELECT id_dis, marca, modelo, precio_dispositivo, precio_instalacion, proveedor FROM `ce_dispositivos` WHERE tipo = 1 AND activado = 1;");
    while($row= mysql_fetch_array($query)){
      if($i%2==0){
        $clase = 'par';
      }else{
        $clase = 'non';
      }
    ?>
    <tr class="<?php echo $clase; ?>">
      <td><div align="center"><input type="checkbox" name="dispositivo[]" value="<?php echo $row['id_dis']; ?>"  id="chk_<?php echo $i; ?>"/><input name="cantidad[]" type="text" value="1" maxlength="2" style="width:30px;" /></div></td>
      <td><?php echo $row['marca']; ?></td>
      <td><?php echo $row['modelo']; ?></td>
      <td>$<?php echo $row['precio_dispositivo']; ?></td>
      <td>$<?php echo $row['precio_instalacion']; ?></td>
      <td><?php echo $row['proveedor']; ?></td>
      <td>
        <div align="center">
          <input name="az[]" type="text" style="width:40px;" value="180"/><br />
          Grados
        </div>
      </td>
      <td>
        <div align="center">
        <?php
        extract(mysql_fetch_array(mysql_query("SELECT latitude FROM ce_terreno WHERE id = ".$_REQUEST['tid'].";")));
        $altitude = ceil(90-$latitude+5);
        ?>
          <input name="alt[]" type="text" style="width:40px;" value="<?php echo $altitude; ?>"/><br />
          Grados
        </div>
      </td>
      <!--HMN03 td>
        <div align="center">
          <input name="equis[]" type="text" style="width:40px;"/><br />
          m
        </div>
      </td>
      <td>
        <div align="center">
          <input name="ye[]" type="text" style="width:40px;"/><br />
          m
        </div>
      </td>
      <td>
        <div align="center">
          <input name="zeta[]" type="text" style="width:40px;"/><br />
          m
        </div>
      </td-->

    </tr>
    <?php
      $i++;
     }
    ?>
    <tr>
      <td colspan="11"><div align="right">
      <input type="hidden" id="totaldispositivos" value="<?php echo $i; ?>" /><!--HMN01 -->
      <input type="image" id="guardar" src="images/guardar.png" style="border:0;" /></div></td>
    </tr>
  </table>
</form>
<div id='loadingDiv' style="display:none;">
	Estamos guardando su informaci&oacute;n, por favor espere un momento...  <img src='images/loader.gif' />
</div>
<script type="text/javascript">
$('#guardar').click(function() {
	$('#loadingDiv').show("slow");
});
</script>