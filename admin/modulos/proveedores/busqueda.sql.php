<?php
require("../../../conexion.php");
$busqueda = $_REQUEST['user'];

$query= "SELECT * FROM ce_usuarios WHERE usuario = '$busqueda';";

$result = mysql_query($query);
if (mysql_num_rows($result) > 0){
	echo "El usuario ya existe";
}else{
	echo "El usuario est&aacute; disponible";
}
mysql_close($conn);

?>