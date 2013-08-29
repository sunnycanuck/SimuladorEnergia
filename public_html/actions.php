<?php
session_start();
require ("conexion.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Documento sin título</title>
<link rel="stylesheet" href="css/text.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.validate.css" />
<link rel="stylesheet" href="css/login.css" />
<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="js/jquery.validate.js" type="text/javascript"></script>
<script src="js/jquery.validation.functions.js" type="text/javascript"></script>
<script type="text/javascript">
<?php
if($_REQUEST['id']==1){
?>
$(document).ready(function()
{
	$("#login_form").submit(function()
	{
		//remove all the class add the messagebox classes and start fading
		$("#msgbox").removeClass().addClass('messagebox').text('Validando...').fadeIn(1000);
		//check the username exists or not from ajax
		$.post("modulos/login/jquery_login.php",{ username:$('#username').val(),password:$('#password').val(),rand:Math.random() } ,function(data)
        {
		  if(data=='continue') //if correct login detail
		  {
		  	$("#msgbox").fadeTo(200,0.1,function()  //start fading the messagebox
			{ 
			  //add message and change the class of the box and start fading
			  $(this).html('Iniciando sesión...').addClass('messageboxok').fadeTo(900,1,
              function()
			  { 
			  	 //redirect to secure page
				 parent.location.href="log.php?mod=1";
			  });
			  
			});
		  }
		  else 
		  {
		  	$("#msgbox").fadeTo(200,0.1,function() //start fading the messagebox
			{ 
			  //add message and change the class of the box and start fading
			  $(this).html('Sus datos no son correctos, intentelo de nuevo...').addClass('messageboxerror').fadeTo(900,1);
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

<?php
}

if($_REQUEST['id']==2){
?>
/* <![CDATA[ */
jQuery(function(){
		jQuery("#name").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Favor de llenar el campo"
		});
		/*jQuery("#user").validate({
				expression: "if (VAL) return true; else return false;",
				message: "Favor de llenar el campo"
		});*/
		jQuery("#email").validate({
				expression: "if (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/)) return true; else return false;",
				message: "Ingrese una dirección de correo válida"
		});
		jQuery("#pass").validate({
				expression: "if (VAL.length > 5 && VAL) return true; else return false;",
				message: "Ingrese una contraseña"
		});
		jQuery("#confirmpass").validate({
				expression: "if ((VAL == jQuery('#pass').val()) && VAL) return true; else return false;",
				message: "Las contraseñas no coinciden"
		});
		jQuery('.AdvancedForm').validated(function(){
			liga(function(url){
				document.location.href=url;
			});
		});
});
		/* ]]> */

<?php
}
?>
<!-- buscador jquery -->
$(document).ready(function(){ 
$("#search_results").slideUp(); 
    $("#search_button").click(function(e){ 
        e.preventDefault(); 
        ajax_search(); 
    }); 
    $("#user").keyup(function(e){ 
        e.preventDefault(); 
        ajax_search(); 
    }); 

});

function ajax_search(){ 
  $("#search_results").show(); 
  var search_val=$("#user").val(); 
  $.post("./admin/modulos/proveedores/busqueda.sql.php", {user : search_val}, function(data){
   if (data.length>0){
     $("#search_results").html(data);
   } 
  }) 
}
<!-- buscador jquery -->
</script>
</head>

<body>
<?php
	switch($_REQUEST['id']){
		case 1: require("modulos/login/login.php");break;
		case 2: require("modulos/signin/signin.php");break;
	}	
?>
</body>
</html>