<?php
  /*
  ---------------------------------------------------------------------------------
  MODIFICACIONES:
  ---------------------------------------------------------------------------------
  Clave: HMN01
  Hecha por: Héctor Mora.
  Fecha: 30/Octubre/2012
  Descripción: Se cambió el formateo de 0 decimales a 2, para las variables delL, y delH
  ----------------------------------------------------------------------------------
  Clave: HMN02
  Hecha por: Héctor Mora.
  Fecha: 30/Octubre/2012
  Descripción: Se cambió el formateo de 2 decimales a 3, para las variables delL, y delH
  ----------------------------------------------------------------------------------
  Clave: HMN03
  Hecha por: Héctor Mora.
  Fecha: 02/Noviembre/2012
  Descripción: Se cambio variable por dispositivo_tipo
  ----------------------------------------------------------------------------------*/
  

function query(){
	$dispositivo_tipo = $_REQUEST['dispositivo_tipo'];
	$did = $_REQUEST['did'];
	switch($_REQUEST['act']){
		case 1:{
			// ********************************* Agregar dispositivo ******************************************


			switch($_REQUEST['dispositivo_tipo']){
				case 1:{
					$delL = number_format($_REQUEST['dell'], '3'); //HMN01 HMN02
					$delH = number_format($_REQUEST['delh'], '3'); //HMN01 HMN02
					$area = $delL * $delH;
					$potencia = $_REQUEST['potencia'];
					$ir = 1.5;
					$qe =  $potencia / (1000*$area);
					$factores = $delH.';'.$delL.';'.$ir.';'.$qe.';'.$potencia.';'.$area.';'.$_REQUEST['tipoFotovol'];
				}break;
				case 4:{
					$watts = $_REQUEST['watts'];
					$porcentaje = $_REQUEST['porcentaje'];
					$factores = $watts.';'.$porcentaje;
				}break;
				default: $factores = $_REQUEST['factores'];break;
			}

			$idp = $_REQUEST['idp'];
			$marca = 	$_REQUEST['marca'];
			$modelo = $_REQUEST['modelo'];
			$precio_dispositivo = $_REQUEST['precio_dispositivo'];
			$precio_instalacion = $_REQUEST['precio_instalacion'];
			$proveedor = $_REQUEST['proveedor'];

			if(mysql_query("INSERT INTO ce_dispositivos (tipo, marca, modelo, precio_dispositivo, precio_instalacion, proveedor, id_proveedor, factores, activado)
											VALUES ('".$dispositivo_tipo."', '".$marca."', '".$modelo."', '".$precio_dispositivo."', '".$precio_instalacion."', '".$proveedor."', '".$idp."', '".$factores."', 0);")){

				$to = "revans@cicese.mx, sunnycanuck@gmail.com";
				//$to = "minoru@voxelsoluciones.com";
				$subject = "Nuevo dispositivo agregado por proveedor";

				$message = "
				<html>
				<head>
				<title>Nuevo dispositivo agregado por proveedor</title>
				</head>
				<body>
					<div align=\"left\">
						<p>El proveedor <strong>".$proveedor."</strong> ha agregado un nuevo dispositivo marca <strong>".$marca."</strong> modelo <strong>".$modelo."</strong>.</p>
						<p>Para autorizarlo favor de ir al administrador y activar el dispositivo.</p><br />
						<p>
							-----<br />
							Atte:<br />
							Calculador Energetico CICESE
						</p>
					</div>
				</body>
				</html>
				";

				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
				$headers .= 'From: <info@calculadorenergetico.com>' . "\r\n";

				mail($to,$subject,$message,$headers);

				$url = "index.php?mod=3&msj=5";
			}else{
				$url = "index.php?mod=3&msj=7";
			}

		}//CASE 1
		;break;
		// ********************************* Eliminar Dispositivo ******************************************
		case 2:{
			if(mysql_query("DELETE FROM ce_dispositivos WHERE id_dis = ".$did.";")){
				$url = "index.php?mod=3&msj=2";
			}else{
				$url = "index.php?mod=3&msj=21";
			}
		};
		//CASE 2
		break;
		// ********************************* Editar Dispositivo ******************************************
		case 3:{
			$marca = $_REQUEST['marca'];
			$modelo = $_REQUEST['modelo'];
			$precio_dispositivo = $_REQUEST['precio_dispositivo'];
			$precio_instalacion = $_REQUEST['precio_instalacion'];
			$idp = $_REQUEST['idp'];
			$proveedor = $_REQUEST['proveedor'];
			switch($dispositivo_tipo){ //HMN03
				case 1:{
					$delL = number_format($_REQUEST['dell'], '3'); //HMN01 HMN02
					$delH = number_format($_REQUEST['delh'], '3'); //HMN01 HMN02
					$area = $delL * $delH;
					$potencia = $_REQUEST['potencia'];
					$ir = 1.5;
					$qe =  $potencia / (1000*$area);
					$factores = $delH.';'.$delL.';'.$ir.';'.$qe.';'.$potencia.';'.$area.';'.$_REQUEST['tipoFotovol'];
				}break;
				default: $factores = $_REQUEST['factores'];break;
			}

			if(mysql_query("UPDATE `ce_dispositivos` SET tipo = '".$dispositivo_tipo."', marca = '".$marca."', modelo = '".$modelo."', precio_dispositivo = '".$precio_dispositivo."', precio_instalacion = '".$precio_instalacion."', proveedor = '".$proveedor."', id_proveedor = '".$idp."', factores = '".$factores."' WHERE id_dis = '".$did."';")){
				$url = "index.php?mod=3&msj=3";
			}else{
				die(mysql_error());
				$url = "index.php?mod=3&msj=7";
			}

		};
		//CASE 3
		break;
		// ********************************* Agregar Paquete ******************************************
		case 4:{

			$uid = $_REQUEST['uid'];
			$paquete = $_REQUEST['nombre_pqt'];
			$gridTie = $_REQUEST['gridTie'];

			if(empty($_REQUEST['totalGeneral']) || $_REQUEST['totalGeneral']=="")
				$precio = $_REQUEST['total_auto'];
			else
				$precio = $_REQUEST['totalGeneral'];

			$dis1 = "1-".$gridTie;
			$dis2 = "";

			$cuantos = count($_REQUEST['fotovol']);

			$fotovol = $_REQUEST['fotovol'];
			$numFotovol = $_REQUEST['numFotovol'];

			for($i=0;$i<$cuantos;$i++){
				$dis2 .= $numFotovol[$i].'-'.$fotovol[$i].';';
			}

			if(mysql_query("INSERT INTO `ce_paquetes` (id_pqt, id_proveedor, nombre_pqt, precio, dis1, dis2, tipo_pqt) VALUES (NULL, ".$uid.", '".$paquete."', '".$precio."', '".$dis1."', '".$dis2."', '1');")){
				$url = "index.php?mod=3&msj=24";
			}else{
				$url = "index.php?mod=3&msj=25";
			}



		}//CASE 4
		break;
		// ********************************* BORRAR Paquete ******************************************
		case 5:{
			if(mysql_query("DELETE FROM ce_paquetes WHERE id_pqt = ".$_REQUEST['pid'].";")){
				$url = "index.php?mod=3&act=6&msj=26";
			}else{
				$url = "index.php?mod=3&msj=27";
			}

		}// CASE 5
		break;
		// ********************************* EDITAR Paquete ******************************************
		case 6:{
			if(mysql_query("UPDATE ce_paquetes SET nombre_pqt = '".$_REQUEST['nombre']."', precio = '".$_REQUEST['precio']."' WHERE id_pqt = ".$_REQUEST['pid'].";")){
				$url = "index.php?mod=3&msj=28";
			}else{
				$url = "index.php?mod=3&msj=29";
			}
		}//CASE 6
		break;
	}//SWITCH


	return $url;
}
?>