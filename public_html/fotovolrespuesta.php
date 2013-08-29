<?php
/* A program to

  Project Leader Rodger Evans, 2011-06-01
  sunnycanuck@gmail.com
  Collaborators Voxel Soluciones
  http://www.voxelsoluciones.com
  info@voxelsoluciones.com

  Published under the Creative Commons Attribution-ShareAlike 2.5 Generic (CC BY-SA 2.5) licence
  http://creativecommons.org/licenses/by-sa/2.5/

  Publicado bajo la Licencia Creative Commons Atribuci??n-CompartirIgual 2.5 M?Â©xico (CC BY-SA 2.5)
  http://creativecommons.org/licenses/by-sa/2.5/mx/

*/

/*HMN- Función para crear la respuesta a n dispositivos fotovoltaicos de un caso de un terreno
 *
 *Parametros recibidos:
 *$con       - Conexion a la base de datos
 *$idterreno - ID del terreno
 *$idcaso    - Número de caso
 *----------------------------------------------------------------
 * Modificaciones:
 *----------------------------------------------------------------
 * Clave: HMN01
 * Autor: Hector Mora
 * Descripción: Se hicieron cambios a las fórmulas petición de Rodger evans
 * Fecha: 17 de Agosto de 2012
 * -----------------------------------------------------------------
 * Clave: HMN02
 * Autor: Hector Mora
 * Descripción: Cambios debido a adecuaciones en la base de datos.
 * Fecha: 29  de Agosto de 2012
 * -----------------------------------------------------------------
 * Clave: HMN03
 * Autor: Hector Mora
 * Descripción: Correción a la línea 98, Division by Zero
 *              La variable nT había sido comentada, Se cambió por IR.
 * Fecha: 29  de Agosto de 2012
 * -----------------------------------------------------------------
 * clave: RE01
 * Autor: Rodger Evans
 * Descripción: correccion de Tper Tpar y notando con ** lineas incorrectos
 * Fecha: 27 de sept 2012
 * -----------------------------------------------------------------
 * clave: HMN04
 * Autor: Hector Mora
 * Descripción: Se aplicaron los cambios sugeridos por Rodger.
 * Fecha: 28 de septiembre de 2012
 * -----------------------------------------------------------------
 * clave: RE02
 * Autor: Rodger Evans
 * Descripción: fixes in fotovolrespuesta
 * Fecha: 17 de oct 2012
 * -------------------------------------------------------------------
 * clave: RE03
 * Autor: Rodger Evans
 * Descripción: making the cosGz go in steps
 * Fecha: 18 de oct 2012
 * -------------------------------------------------------------------
 * clave: RE04
 * Autor: Rodger Evans
 * Descripción: adding Rs and Rp and changing Tperp to Rs etc...
 * Fecha: 19 de oct 2012
 */


   /*****Seccion de prueba *******
   require("conexion.php");
   require("consumo.php");
   crear_tabla_fvrespuesta("229", "6");
   mysql_close($conn);********/



function crear_tabla_fvrespuesta( $idterreno, $idcaso) {

    $dispositivos = getFotovs(  $idterreno, $idcaso );

	$pi = 3.141592654;

    $total_dispositivos = count($dispositivos);
    $nombre_tabla = "";

    $caminoSolar = getCaminoSolar(  $idterreno );
    $total_caminoSolar = count ($caminoSolar );

	for( $i = 0; $i < $total_dispositivos; $i ++ ) {

		$nombre_tabla = "ce_fotovoltaico_respuesta_t". $idterreno. "c" . $idcaso . "fv". $dispositivos[$i][0];

		borraTabla(  $nombre_tabla );
		creaTabla(  $nombre_tabla );
		$datos_dispositivo = getDatosDispositivo ( $idterreno, $dispositivos[$i][0], $dispositivos[$i][1] );

		//$terreno = $row['terreno'];
		$delL    = $datos_dispositivo['delL'];
		$delH    = $datos_dispositivo['delH'];
		$azFV    = $datos_dispositivo['azFV'];
		$altFV   = $datos_dispositivo['altFV'];
		//HMN04 $indRef      = $datos_dispositivo['IR']; // HMN02
		$indRef = 1.5; //HMN04

		//$QE      = $datos_dispositivo['QE']; // HMN02
		$x       = $datos_dispositivo['x'];
		$y       = $datos_dispositivo['y'];
		$z       = $datos_dispositivo['z'];

		//HMN04 $area    = $datos_dispositivo["area"]; // HMN02
		$area = $delL * $delH; //HMN04
		$potencia= $datos_dispositivo["potencia"]; // HMN02
		$QE = $potencia/(1000*$area);


	for( $j = 0; $j < $total_caminoSolar; $j++ ) {

				$tiempo = $caminoSolar[$j]['tiempo'];
				$azS    = $caminoSolar[$j]['az'];
				$altS   = $caminoSolar[$j]['alt'];
				$Icl    = (float)$caminoSolar[$j]['intcero']/1000; // Iradiacion del Sol. (Intensidad)
				$Ics    = (float)$caminoSolar[$j]['intuno']/1000;


                //HMN04 Esta linea estaba comentada pero se activo
				$refAire   = 1.000277;       //indice de refracion en aire desde FotoVoltaico
				//HMN01 $nT   = $indRef;            //indice de refracion en vidrio desde FotoVoltaico =$indRef
				//HMN01 $daz  = $azFV - $azS;   //diferencia en azmuth
				//HMN01 $dalt = $altFV - $altS; //diferencia en altura
				//HMN01 $cosDaz = cos($daz);
				//HMN01 $cosDalt= cos($dalt);
				//HMN01 $dif    = acos($cosDalt-cos($altS)*cos($altFV)*(1-cos($daz))); //diferencia en angulo en la normal de la FV

				// y el sol igual angulo del rayo incidente
				//HMN01 $Aper   = cos($dif) * $area; //area efectiva del FV (%)

			//RE02	$thetaT = asin(sin($dif)*$refAire/$indRef);//HMN03 //angulo del rayo transmitido
			//RE02	$sin2TH = sin(2*$thetaT)*sin(2*$dif);
			//RE02	$sinSQ  = (sin($dif+$thetaT))^2;
			//RE02	$cosSQ  = (cos($dif-$thetaT))^2;

				//HMN01 BLOQUE AGREGADO //////////////
				$Ba = $azFV;
				$Bz = pi()/2 - $altFV;
				$Aa = $azS; //azmut sol
				$Az = pi()/2 - $altS; //altitude sol

				//RE03 BLOQUE AGREGADO //////
				$cosBz=cos($Bz);
				$cosAz=cos($Az);
				$sinBz=sin($Bz);
				$sinAz=sin($Az);
				$BaAa=$Ba-$Aa;
				$cosBaAa=cos($BaAa);

				//RE03  $CosGz=cos($Bz)*cos($Az)+sin($Bz)*sin($Az)*cos($Ba-$Aa);
				$CosGz=($cosBz*$cosAz)+($sinBz*$sinAz*$cosBaAa); //RE03
				$Gz=acos($CosGz); // HMN04
				$Aper=$CosGz*$area;

				//RE02 if($Gz > pi/2){
//					$Aper = 0;
//				}

				if( $Aper < 0 ) {
					$Aper = 0;
				}


				//HMN04 $Tpar   = 1-($sin2TH)/($sinSQ*$cosSQ); //T parallel Tpar = transmision en el vidrio. es que el vidrio tiene una reflexion de la liz
				//HMN04 $Tperp  = 1-($sin2TH)/$sinSQ;  //T perpendicularr

				// HMN04
				//nueva nombres Rs Rp, son reflection coeficients for s and p polarizations
				$sinGz=sin($Gz);
				$RsBrkt= $sinGz*$refAire / $indRef ;
				$RsSqrt= 1 - pow($RsBrkt,2);
				$RsTop=($refAire * $CosGz) - $indRef * sqrt($RsSqrt);
				$RsBottom=$refAire * $CosGz + $indRef * sqrt( $RsSqrt);
				$Rs=pow(($RsTop/$RsBottom),2);

				/*$Rs =
					(
					  ( $refAire * $CosGz - $indRef * sqrt( 1 - ( $refAire / $indRef * $sinGz ) ^2))
					  /
					  ( $refAire * $CosGz + $indRef * sqrt( 1 - ( $refAire / $indRef * $sinGz ) ^2)) ) ^ 2;
*/ //RE04
				$RpBrk= $refAire / $indRef * $sinGz;
				$RpSqrt= 1 - pow( $RpBrk, 2);
				$RpTop= $refAire * sqrt( $RpSqrt ) - $indRef * $CosGz ;
				$RpBottom= $refAire * sqrt( $RpSqrt) + $indRef * $CosGz ;
				$Rp=pow(($RpTop/$RpBottom),2);

				/*$Rp =
				 ( ( $refAire * sqrt( 1 - ( $refAire / $indRef * $sinGz ) ^ 2 ) - $indRef * $CosGz ) /

				 ( $refAire * sqrt( 1 - ( $refAire / $indRef * $sinGz ) ^ 2 ) + $indRef * $CosGz ) )^2;//RE02
*/
				$Rtot=($Rs+$Rp)/2;//RE04
				$Ttot=1-$Rtot;

				$potenciaCL=$Icl*$QE*$Aper*$Ttot; // QE = Eficiencia CuÃ¡ntica,,,  Aper = Area efectiva,(Efecto coseno)
				$potenciaCS=$Icl*$QE*$Aper*$Ttot;//this should be CS !!  CS =Clear SKY
				//added $Ttot RE04

			insertaRegistro(  $nombre_tabla, $tiempo,$area, $QE, $Aper, $potenciaCS, $potenciaCL );

		} // Fin for Camino Solar

		actualizaCasos(  $idterreno, $dispositivos[$i][0], $nombre_tabla );

	} // Fin For Dispositivos.

}

function actualizaCasos(  $idterreno, $idfv, $tabla_fv_respuesta) {

	$tabla_casos = "ce_casos_" . $idterreno . "t";

	$sql = "UPDATE ". $tabla_casos ." SET secuencia = '". $tabla_fv_respuesta ."' WHERE id = " . $idfv;

	mysql_query( $sql );

}

function insertaRegistro(  $nombre_tabla, $tiempo,$area, $QE , $Aper, $potenciaCS, $potenciaCL ) {

	$sql = "INSERT INTO ". $nombre_tabla ." (tiempo, azFVt, altFVt, aeff, potenciaCS, potenciaCL) VALUES ('$tiempo','$area', '$QE','$Aper', '$potenciaCS', '$potenciaCL')";
	mysql_query( $sql );
}

function getFotovs( $idterreno, $idcaso ) {

	$dispositivos = Array();

	$sql = "SELECT id as id_disp_caso, id_dispositivo as id_disp FROM ce_casos_" . $idterreno . "t WHERE caso = " . $idcaso ." AND id_tipo = 1";

	$resultado = mysql_query($sql);

 	if( $resultado ) {

		$i = 0;
		while( $registro = mysql_fetch_array( $resultado ) ){
		    $dispositivos[$i][0] = $registro["id_disp_caso"];
			$dispositivos[$i][1] = $registro["id_disp"];
			$i ++;
		}

		mysql_free_result( $resultado );
	}

	return $dispositivos;
}

function borraTabla( $nombre_tabla) {

	$sql = "DROP TABLE IF EXISTS ". $nombre_tabla;
	mysql_query($sql);
}

function creaTabla(  $nombre_tabla ) {

$sql = "CREATE TABLE ". $nombre_tabla .
	   "(
			 id INT PRIMARY KEY AUTO_INCREMENT,
			 tiempo TIMESTAMP,
			 azFVt FLOAT(9,6),
			 altFVt FLOAT(9,6),
			 aeff FLOAT(9,6),
			 potenciaCS FLOAT(9,3),
			 potenciaCL FLOAT(9,3))";

	mysql_query($sql);
}


function getDatosDispositivo ( $idterreno, $id_dispositivo_caso, $id_dispositivo) {

	$datos = Array();

	$sql    = "SELECT factores FROM ce_dispositivos WHERE id_dis = ".$id_dispositivo;

	$resultado = mysql_query($sql);

 	if( $resultado ) {

		if( $registro = mysql_fetch_array( $resultado ) ){
		    $tmp = $registro["factores"];
		}

		$tok = strtok($tmp, ";"); if( $tok !== false ) { $datos["delH"] = $tok; }
		$tok = strtok(";"); if( $tok !== false ) { $datos["delL"] = $tok; }
		$tok = strtok(";"); if( $tok !== false ) { $datos["IR"] = $tok; }
		$tok = strtok(";"); if( $tok !== false ) { $datos["QE"] = $tok; }
		$tok = strtok(";"); if( $tok !== false ) { $datos["potencia"] = $tok; }
		$tok = strtok(";"); if( $tok !== false ) { $datos["area"] = $tok; }
		$tok = strtok(";"); if( $tok !== false ) { $datos["tipoFotovol"] = $tok; }

		mysql_free_result( $resultado );
	}

	$sql = "SELECT dispositivos_variables FROM ce_casos_". $idterreno ."t  WHERE id = ".$id_dispositivo_caso;

	$resultado = mysql_query($sql);

 	if( $resultado ) {

		if( $registro = mysql_fetch_array( $resultado ) ){
		    $tmp = $registro["dispositivos_variables"];
		}

		$tok = strtok($tmp, ";"); if( $tok !== false ) { $datos["azFV"] = $tok; }
		$tok = strtok(";"); if( $tok !== false ) { $datos["altFV"] = $tok; }
		$tok = strtok(";"); if( $tok !== false ) { $datos["x"] = $tok; }
		$tok = strtok(";"); if( $tok !== false ) { $datos["y"] = $tok; }
		$tok = strtok(";"); if( $tok !== false ) { $datos["z"] = $tok; }

		mysql_free_result( $resultado );
	}

	return $datos;
}


function getCaminoSolar( $idterreno ) {

	$camino_solar = Array();
	$sql = "SELECT tiempo, az, alt, intcero, intuno FROM ce_camino_solar_".$idterreno."t";

	$resultado = mysql_query($sql);

 	if( $resultado ) {

		$i = 0;
		while( $registro = mysql_fetch_array( $resultado ) ){
		    $camino_solar[$i]["tiempo"]  = $registro["tiempo"];
		    $camino_solar[$i]["az"]      = $registro["az"];
		    $camino_solar[$i]["alt"]     = $registro["alt"];
		    $camino_solar[$i]["intcero"] = $registro["intcero"];
		    $camino_solar[$i]["intuno"]  = $registro["intuno"];

		    $i ++;
		}

		mysql_free_result( $resultado );
	}


	return $camino_solar;
}

?>