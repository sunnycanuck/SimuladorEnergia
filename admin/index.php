<?php
	session_start();
	require("../conexion.php");
	include("modulos.php");
	if(empty($_REQUEST['mod']))
		$_REQUEST['mod'] = 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Admin Calculadora Energetica</title>
<link rel="stylesheet" type="text/css" href="../fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<link rel="stylesheet" href="../css/reset.css" />
<link rel="stylesheet" href="../css/text.css" />
<link rel="stylesheet" href="../css/960.css" />
<link rel="stylesheet" href="../css/styles.css" />
<link rel="stylesheet" type="text/css" href="../css/jquery.validate.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript" src="../fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="../fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script src="../js/jquery.validate.js" type="text/javascript"></script>
<script src="../js/jquery.validation.functions.js" type="text/javascript"></script>
<?php if($_REQUEST['mod']==1){ ?>
<script type="text/javascript">
$(document).ready(function()
{
	$("#login_form").submit(function()
	{
		//remove all the class add the messagebox classes and start fading
		$("#msgboxadmin").removeClass().addClass('messageboxadmin grid_6').text('Validando...').fadeIn(1000);
		//check the username exists or not from ajax
		$.post("modulos/index/jquery_login.php",{ username:$('#username').val(),password:$('#password').val(),rand:Math.random() } ,function(data)
        {
		  if(data=='continue') //if correct login detail
		  {
		  	$("#msgboxadmin").fadeTo(200,0.1,function()  //start fading the messagebox
			{ 
			  //add message and change the class of the box and start fading
			  $(this).html('Iniciando sesión...').addClass('messageboxokadmin grid_6').fadeTo(900,1,
              function()
			  { 
			  	 //redirect to secure page
				 parent.location.href="log.php?mod=1";
			  });
			  
			});
		  }
		  else 
		  {
		  	$("#msgboxadmin").fadeTo(200,0.1,function() //start fading the messagebox
			{ 
			  //add message and change the class of the box and start fading
			  $(this).html('Sus datos no son correctos, intentelo de nuevo...').addClass('messageboxerroradmin grid_6').fadeTo(900,1);
			});		
          }				
        });
 		return false; //not to post the  form physically
	});
	//now call the ajax also focus move from 
	$("#password").blur(function()
	{
		$("#login_form").trigger('submit');
	});
});
</script>
<?php }
if($_REQUEST['mod']==4){ ?>
	<link rel="stylesheet" href="../js/themes/base/jquery.ui.all.css">
	<link href="../js/themes/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css">
	<script src="../ui/jquery.ui.core.js"></script>
	<script src="../ui/jquery.ui.widget.js"></script>
	<script src="../ui/jquery.ui.datepicker.js"></script>
  
  <script>
		$(function() {
			var dates = $( "#from, #to" ).datepicker({
				defaultDate: "+1w",
				changeMonth: true,
				numberOfMonths: 2,
				onSelect: function( selectedDate ) {
					var option = this.id == "from" ? "minDate" : "maxDate",
						instance = $( this ).data( "datepicker" ),
						date = $.datepicker.parseDate(
							instance.settings.dateFormat ||
							$.datepicker._defaults.dateFormat,
							selectedDate, instance.settings );
					dates.not( this ).datepicker( "option", option, date );
				}
			});
		});
	</script>
<?php	
}if($_REQUEST['mod']==5){

?>
<link rel="stylesheet" href="../js/themes/base/jquery.ui.all.css">
<link href="../js/themes/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css">
<script src="../ui/jquery.ui.core.js"></script>
<script src="../ui/jquery.ui.widget.js"></script>
<script src="../ui/jquery.ui.datepicker.js"></script>
<script type="text/javascript">
	$(function() {
		$('.tarifas').datepicker( {
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true,
				dateFormat: 'M-y',
				monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
				onClose: function(dateText, inst) { 
						var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
						var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
						$(this).datepicker('setDate', new Date(year, month, 1));
				}
		});
	});

</script>
<style>
.ui-datepicker-calendar{
	display: none;
	}
</style>
<?php } ?>
<script type="text/javascript">
	$(document).ready(function(){
		$(".msj").delay(2000).fadeToggle("slow", "linear");
	});
	$(document).ready(function(){
		$(".msjerror").delay(2000).fadeToggle("slow", "linear");
	});
</script>
</head>
<body>
<div class="container_16">
	<div class="grid_16">
<!----------------------------- MENU ------------------------------>
  <div id="menu">
  	<div class="grid_4 alpha">
    	&nbsp;
    </div><!-- grid_4 -->
    <div class="grid_8">
    	<?php
			if(isset($_SESSION['user'])){ ?>
        <div class="prefix_1 grid_6 suffix_1" id="user_field">
          <p>
          	Bienvenido(a): <img src="../images/user.png" border="0" /> <?php echo $_SESSION['user']; ?> |  
          	<a class="link_blanco" href="logout.php"><img src="../images/borrar.png" width="16" border="0" title="Eliminar Registro" /> Cerrar Sesi&oacute;n</a>
          </p>
        </div><!-- user_field -->
      <?php
			}else
				echo "&nbsp;";
			?>
    </div><!-- titulo_pagina -->
    <div class="grid_4 omega">
    	&nbsp;
    </div><!-- grid_4 -->
  </div><!-- menu -->
  <div class="clear"></div>
<!----------------------------- MENU ------------------------------>
<!------------------------ MENU SECUNDARIO ------------------------>
	<div class="spacer_20"></div>
	<div id="menu_admin">	
  	<?php if(isset($_SESSION['user'])){ ?>
  	<ul>
    	<li><a href="index.php?mod=2"><img src="../images/proveedor.png" border="0" /> Dispositivos</a></li>
    	<li><a href="index.php?mod=3"><img src="../images/proveedor.png" border="0" /> Proveedores</a></li>
      <li><a href="index.php?mod=4"><img src="../images/proveedor.png" border="0" /> Generar Fechas</a></li>
      <li><a href="index.php?mod=5"><img src="../images/proveedor.png" border="0" /> Tarifas</a></li>
      <!--<li><a href="index.php?mod=6"><img src="../images/proveedor.png" border="0" /> Camino Solar</a></li>-->
    </ul>
    <?php } ?>
  </div><!-- menu_secundario -->
<!------------------------ MENU SECUNDARIO ------------------------>
	<div class="spacer_25"></div>
<!----------------------------- CONTENIDO ------------------------->
		<?php
      modulos();
    ?>
<!----------------------------- CONTENIDO ------------------------->
		<div class="spacer_25"></div>
  </div><!-- grid_16 -->   
</div><!-- container_16 -->


</body>
</html>
<?php mysql_close($conn); ?>