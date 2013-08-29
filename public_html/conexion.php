<?php
//$conn = mysql_connect("localhost", "root","" );
$conn = mysql_connect("localhost", "energiav_calcula","c4lcul4d0r!" );
if(!$conn){
	die('Error en la conexi&oacute;n a la base de datos, consulte a su administrador...');
}
//mysql_select_db("calculador", $conn);
mysql_select_db("energiav_calculado", $conn);
			

?>
