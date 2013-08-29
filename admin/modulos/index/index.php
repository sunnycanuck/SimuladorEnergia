<?php
	if(!empty($_SESSION['log'])){
		
?>
	<div class="login_admin">
		<h2>Seleccione una opci&oacute;n</h2>
	</div><!-- login -->
<?php
	}else{ ?>
  	<div class="login_admin" align="center">
      <div class="spacer_50">&nbsp;</div>
      <img src="../images/alert.png" border="0" />
      <h2 style="margin-bottom:0;">Iniciar Sesi&oacute;n</h2>
      <p>Solo personal autorizado</p>
      <div id="login_table_admin">
        <form action="" method="post" id="login_form" target="_parent">
        <table cellpadding="5" cellspacing="5" border="0">
          <tr>
            <td>Usuario:<br /><input type="text" name="username" id="username" class="general"/></td>
          </tr>
          <tr>
            <td>Contrase&ntilde;a:<br /><input type="password" name="password" id="password" class="general"/></td>
          </tr>
        </table>    
      </div><!-- tabla login -->
      <div>
        <input type="image" src="../images/login.png" class="login_btn" id="submit" border="0" style="border:0;" />
        </form>
      </div><!-- login_boton -->
      <div class="spacer_20"></div>
      <div class="grid_5 alpha">&nbsp;</div>
      <div id="msgboxadmin" style="display:none;"></div>
      <div class="grid_5 omega">&nbsp;</div>
    </div><!-- login -->
 <?php
	}
 ?>
		
