<?php
   require("conexion.php");

   $sql = "DELETE FROM ce_usuarios WHERE id_usuario=13";
   //$sql = "UPDATE ce_usuarios SET id_usuario=2 WHERE usuario='kaos_93@hotmail.com';"; //ERA el 2

   mysql_query($sql);

?>
