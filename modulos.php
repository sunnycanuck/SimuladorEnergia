<?php
function modulos(){
	$sid = $_SESSION['log'];
	$id = $_SESSION['userid'];
	$query = mysql_fetch_array(mysql_query("SELECT COUNT(id_usuario) AS total FROM `ce_usuarios` WHERE id_usuario = '$id' AND session = '$sid';")) or die("Error en el query por:" . mysql_error());
	if($query['total']==1){
		switch($_REQUEST['mod']){
			case 1: require("modulos/index/index.php");break;
			case 2:{
				switch($_REQUEST['act']){
					case 1: require("modulos/acerca/ciudadanos.php");break;
					case 2: require("modulos/acerca/proveedores.php");break;
					case 3: require("modulos/acerca/instituciones.php");break;
					default: require("modulos/acerca/index.php");break;
				}
			}break;
			case 3:{
				switch($_REQUEST['act']){
					case 1: require("modulos/proveedores/alta_dispositivo.php");break;
					case 2: require("modulos/proveedores/mis_dispositivos.php");break;
					case 3: require("modulos/proveedores/editar_dispositivo.php");break;
					case 4: require("modulos/proveedores/alta_paquete.php");break;
					case 5: require("modulos/proveedores/alta_paquete2.php");break;
					case 6: require("modulos/proveedores/mis_paquetes.php");break;
					case 7: require("modulos/proveedores/editar_paquete.php");break;
					default: require("modulos/proveedores/index.php");break;
				}
			}break;
			case 4:{
				switch($_REQUEST['act']){
					case 1: require("modulos/terrenos/alta.php");break;
					case 2: require("modulos/terrenos/editar.php");break;
					case 3: require("modulos/terrenos/grafica_cs.php");break;
					default: require("modulos/terrenos/index.php");break;
				}
			}break;
			case 5:{
				switch($_REQUEST['act']){
					case 1: require("modulos/recibos/cfe_index.php");break;
					case 2: require("modulos/recibos/cfe_alta.php");break;
					case 3: require("modulos/recibos/cfe_editar.php");break;
					default: require("modulos/recibos/index.php");break;
				}
			}break;
			case 6:{
				switch($_REQUEST['act']){
					case 1: require("modulos/casos/index.php");break;
					case 2: require("modulos/casos/alta.php");break;
					case 3: require("modulos/casos/pqt_dis.php");break;
					case 4: require("modulos/casos/editar2.php");break;
					case 5: require("modulos/casos/grafica_historico_cfe.php");break;
					case 6: require("modulos/casos/demanda_promedio.php");break;
					case 7: require("modulos/casos/grafica_medidor.php");break;
					case 8: require("modulos/casos/grafica_costo_consumo.php");break;
					case 9: require("modulos/casos/editar_dis2.php");break;
					case 10: require("modulos/casos/editar_pqt2.php");break;
					default: require("modulos/casos/index.php");break;
				}break;
			}
			case 7: {
				require("modulos/casos/reporte.php");
				break;
			}
			default: require("modulos/index/index.php");break;
		}// switch
	}else{
		switch($_REQUEST['mod']){
			case 2:{
				switch($_REQUEST['act']){
					case 1: require("modulos/acerca/ciudadanos.php");break;
					case 2: require("modulos/acerca/proveedores.php");break;
					case 3: require("modulos/acerca/instituciones.php");break;
					default: require("modulos/acerca/index.php");break;
				}
			}break;
			default: require("modulos/index/index.php");
		}
	}
}
?>