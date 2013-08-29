<?php
	if(!empty($_SESSION['log'])){
		
?>
	<div class="login">
		<div class="spacer_100">&nbsp;</div>
		<h2>Usted ya ha iniciado una sesi&oacute;n</h2>
	</div><!-- login -->
<?php
	}else{ ?>
  	<div class="login">
      <div class="spacer_100">&nbsp;</div>
      <h2>Iniciar Sesi&oacute;n</h2>
      <div id="login">
        <form action="" method="post" id="login_form" target="_parent">
        <table cellpadding="0" cellspacing="0" border="0" id="t_login">
          <tr>
            <td>email:</td>
            <td><input type="text" name="username" id="username" class="general"/></td>
          </tr>
          <tr>
            <td>Contrase&ntilde;a:</td>
            <td><input type="password" name="password" id="password" class="general"/></td>
          </tr>
        </table>    
      </div><!-- tabla login -->
      <div>
        <input type="image" src="images/login.png" class="login_btn" id="submit" border="0" />
        </form>
      </div><!-- login_boton -->
      
      <div id="msgbox" style="display:none;"></div>
    </div><!-- login -->
 <?php
	}
 ?>
		
