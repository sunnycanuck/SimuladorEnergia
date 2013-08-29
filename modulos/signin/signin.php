<div class="signin">
	<?php if($_REQUEST['tipo_usuario'] == 2){ ?>
  <h4>Proveedor</h4>
  <p style="margin-bottom:0;">Favor de llenar los siguientes campos, todos son obligatorios.</p>
  <div id="signin_table">
  	<form action="sql.php?mod=2&act=1" method="post" target="_parent" class="AdvancedForm">
    <input name="tipo_usuario" type="hidden" value="<?php echo $_REQUEST['tipo_usuario']; ?>" />
    <table cellpadding="0" cellspacing="0" border="0" width="80%">
    	<tr>
      	<td width="31%">Nombre completo:</td>
        <td width="69%"><input type="text" name="name" class="general" id="name"/></td>
      </tr>
      <tr>
      	<td>email:</td>
        <td><input type="text" name="user" class="general" id="user" onfocus= 'setAttribute("autocomplete","off")';/> <div id="search_results"></div></td>
      </tr>
      <tr>
      	<td>Contrase&ntilde;a:</td>
        <td><input type="password" name="pass" id="pass" class="general"/></td>
      </tr>
      <tr>
      	<td>Repetir contrase&ntilde;a:</td>
        <td><input type="password" name="confirmpass" id="confirmpass" class="general"/></td>
      </tr>
      <tr>
      	<td>Direcci&oacute;n:</td>
        <td><input type="text" name="direccion" id="direccion" class="general"/></td>
      </tr>
      <tr>
      	<td>R.F.C:</td>
        <td><input type="text" name="rfc" id="rfc" class="general"/></td>
      </tr>
      <tr>
      	<td>Tel&eacute;fono:</td>
        <td><input type="text" name="tel" id="tel" class="general"/></td>
      </tr>
      <tr>
      	<td>Fax:</td>
        <td><input type="text" name="fax" id="fax" class="general"/></td>
      </tr>
      <tr>
      	<td>URL:</td>
        <td><input type="text" name="url" id="url" class="general"/></td>
      </tr>
      <tr>
      	<td>Ciudad:</td>
        <td><input type="text" name="ciudad" id="ciudad" class="general"/></td>
      </tr>
    </table>    
  </div><!-- tabla login -->
  <div id="login_boton">
  	<input type="image" src="images/signin.png" class="signin_btn" border="0" />
  </div><!-- login_boton -->
  </form>
  <?php }elseif($_REQUEST['tipo_usuario'] == 3){ ?>
	<h4>Usuario</h4>
  <p style="margin-bottom:0;">Favor de llenar los siguientes campos, todos son obligatorios.</p>
  <div id="signin_table">
  	<form action="sql.php?mod=2&act=1" method="post" target="_parent" class="AdvancedForm">
    <input name="tipo_usuario" type="hidden" value="<?php echo $_REQUEST['tipo_usuario']; ?>" />
    <table cellpadding="0" cellspacing="0" border="0" width="80%">
    	<tr>
      	<td width="31%">Nombre completo:</td>
        <td width="69%"><input type="text" name="name" class="general" id="name"/></td>
      </tr>
      <tr>
      	<td>email:</td>
        <td><input type="text" name="user" class="general" id="user" onfocus= 'setAttribute("autocomplete","off")';/> <div id="search_results"></div></td>
      </tr>
      <tr>
      	<td>Contrase&ntilde;a:</td>
        <td><input type="password" name="pass" id="pass" class="general"/></td>
      </tr>
      <tr>
      	<td>Repetir contrase&ntilde;a:</td>
        <td><input type="password" name="confirmpass" id="confirmpass" class="general"/></td>
      </tr>
    </table>    
  </div><!-- tabla login -->
  <div id="login_boton">
  	<input type="image" src="images/signin.png" class="signin_btn" border="0" />
  </div><!-- login_boton -->
  </form>
	<?php }else{ ?>
  <h2>Registrarse</h2>
  
  <p>Seleccione el tipo de usuario</p>
  <div id="tipo_usuario">
  <form action="actions.php?id=2" method="post" class="AdvancedForm">
  <p style="text-align:left; margin-bottom:0;"><input name="tipo_usuario" type="radio" value="3" />Usuario Normal</p>
  <p style="text-align:left;"><input name="tipo_usuario" type="radio" value="2" /> Proveedor</p>
  <div id="login_boton">
  	<input type="image" src="images/signin.png" class="signin_btn" border="0" />
  </div><!-- login_boton -->
  </form>
  </div>
  <?php } ?>
</div>