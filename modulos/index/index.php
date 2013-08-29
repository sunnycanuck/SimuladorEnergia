<?php
if(empty($_SESSION['log'])){
?>
<div id="main_izq" class="prefix_1 grid_7 alpha">
  <h2>&iquest;Qué es?</h2>
  <p>
  	El calculador energ&eacute;tico es un simulador de consumo y producci&oacute;n de energ&iacute;a. Está dise&ntilde;ado para la regi&oacute;n de Baja California con precios de tarifas de electricidad de CFE y valores 
    climatol&oacute;gicos y de radiaci&oacute;n solar del estado.
  </p>
  <h2>&iquest;Qu&eacute; lo hace diferente de otras p&aacute;ginas web con calculadores?</h2>
  <p>
   Otras p&aacute;ginas usan c&aacute;lculos muy sencillos, con aproximaciones de costos, consumos y producci&oacute;n. Este sistema es un simulador, que calcula valores m&aacute;s exactos cada minuto de cada d&iacute;a. El sistema est&aacute; hecho para Baja California, con costos de electricidad de la CFE, precios de proveedores locales, valores del sol y el clima de tu ubicaci&oacute;n.
  </p>
</div><!-- main_izq -->

<div id="main_der" class="grid_7 suffix_1 omega" style="text-align:center;">
    <h2 style="line-height:1.0; margin-top:50px;">&iquest;Ya est&aacute; registrado?</h2>
    <a class="login" href="actions.php?id=1">
      <img src="images/boton_entrar.png" width="140" height="42" />
    </a>

    <h2 style="line-height:1.0; margin-top:50px;">&iquest;Quiere utilizar este sistema?</h2>
    <a class="signin" href="actions.php?id=2">
      <img src="images/boton_registrarse.png" width="206" height="42" />
    </a>

  <!-- <h2>&iquest;C&oacute;mo usarlo?</h2>
  <p>
   	<a href="index.php?mod=2&act=1">
    	<img style="float:left;" src="images/users.png" width="128" />
    	<h2 style="line-height:1.0; margin-top:50px;">Para ciudadanos <br />y Negocios</h2>
    </a>
  </p>
  <div class="clear"></div>
  <p>
   	<a href="index.php?mod=2&act=2">
    	<img style="float:left;" src="images/proveedores.png" width="128" />
    	<h2 style="line-height:1.0; margin-top:50px;">Para<br />Proveedores</h2>
   	</a>
  </p>
  <div class="clear"></div>
  <p>
   	<a href="index.php?mod=2&act=3">
    	<img style="float:left;" src="images/instituciones.png" width="128" />
      <h2 style="line-height:1.0; margin-top:50px;">Para<br />instituciones</h2>
    </a>
  </p> -->
</div><!-- main_der -->
<?php
}
?>