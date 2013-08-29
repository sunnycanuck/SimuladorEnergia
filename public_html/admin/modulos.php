<?php
function modulos(){
		
	$sid = $_SESSION['log'];
	$id = $_SESSION['userid'];
		
	$query = extract(mysql_fetch_array(mysql_query("SELECT COUNT(id_usuario) AS total FROM `ce_usuarios` WHERE id_usuario = '".$id."' AND session = '".$sid."';")));
	 
	if($total==0){
		require("modulos/index/index.php");
	}else{	
		switch($_REQUEST['mod']){
			case 1: require("modulos/index/index.php");break;
			case 2: require("modulos/dispositivos/index.php");break;
			case 3: require("modulos/proveedores/index.php");break;
			case 4: require("modulos/fechas/index.php");break;
			case 5:{ 
				switch($_REQUEST['act']){
					case 1:require("modulos/tarifas/tarifas.php");break;
					case 2:require("modulos/tarifas/opciones.sql.php");break;
					default:require("modulos/tarifas/index.php");break;
				}break;
			}break;
			case 6: require("modulos/caminosolar/index.php");break;
			default: require("modulos/index/index.php");break;
		}
	}
}

function display_db_query($query_string, $header_bool, $table_params, $tablename, $tarifa) {
	// hacemos el query
	$result_id = mysql_query($query_string)
	or die("display_db_query:" . mysql_error());
	// encuentra el numero de columnas de la tabla
	$column_count = mysql_num_fields($result_id)
	or die("display_db_query:" . mysql_error());
	print("<table $table_params >\n");
	if($header_bool) {
		print("<tr>");
		for($column_num = 0; $column_num < $column_count; $column_num++) {
			$field_name = mysql_field_name($result_id, $column_num);
			print("<th>$field_name</th>");
		}
		print("<th>Opciones</th>");//opciones fuera del FOR
		print("</tr>\n");
	}
	// imprimimos la tabla
	$i=0;
	while($row = mysql_fetch_array($result_id)) {
		if($i%2==0){
			$clase = 'par';
		}else{
			$clase = 'non';
		}		
		print('<tr class="'.$clase.'">');
		for($column_num = 0; $column_num < $column_count; $column_num++) {
			print("<td>$row[$column_num]</td>\n");
		}
		print("<td><a href=\"sql.php?mod=5&act=e&id=".$row['id']."&table=".$tablename."&tarifa=".$tarifa."\" onclick=\"return confirm('¿Desea eliminar el registro? Recuerde que no hay marcha atras!!!')\"><img src=\"../images/borrar.png\" width=\"16\" title=\"Eliminar Registro\" /></a></td>\n");
		print("</tr>\n");
		$i++;
	}
	print("</table>\n"); 
}
function display_db_table($tablename, $header_bool, $table_params, $tarifa)
	{
	$query_string = "SELECT * FROM $tablename ORDER BY 'fecha' DESC";
	display_db_query($query_string, $header_bool, $table_params, $tablename, $tarifa);
}

?>