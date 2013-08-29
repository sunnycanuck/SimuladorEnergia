<!--
	Autor: Héctor Mora.
	Fecha: 05-Febrero-2012
	Descripción: Interfaz de prueba del script del simulador.
-->
<?php
	include("simulador_bd.php");
?>
<html>
	<head>
		<title>Prueba Simulador</title>
	</head>
	<body>
		<div width="100%" align="center"><H1>Prueba del Simulador del calculador energ&eacute;tico</H1></div>

		<div width="100%" align="center">
		<form method="post" action="simulador.php">
			<table border="1">
				<tr>
					<td><I>Se genera tabla ce_SIMtarifas_XXt con el numero de registros que tenga la tabla ce_horasDelMes</I></td>
				</tr>
				<tr>
					<td>Selecciona tarifa: &nbsp;&nbsp;
						<SELECT name="tarifa">
							<?php

								$tarifas = getTarifas();
								$total_tarifas = count( $tarifas );

								for( $i = 0; $i < $total_tarifas; $i ++ ) {
									echo "<OPTION value=\"" . $tarifas[$i]["tarifa"] . "\">". $tarifas[$i]["tarifa"] . "</OPTION>";
								}

							?>

						</SELECT>
					</td>
				</tr>
				<tr>
					<td align="center">
						<input type="submit" value="Simular" style="cursor: pointer" title="Clic para simular tarifa"/>
					</td>
				</tr>
			</table>
		</form>
		</div>
	</body>
</html>


<?php

function getTarifas() {

	$tarifas = array();
	$conexion = abre_conexion_servidor();
	selecciona_base_datos();
	$i = 0;

	$resultado = mysql_query( "SELECT id_tarifa, tarifa FROM ce_tarifas", $conexion );

	if( $resultado ) {

		while( $registro = mysql_fetch_array( $resultado ) ){
			$tarifas[ $i ] [  "id" ] = $registro[ "id_tarifa" ];
			$tarifas[ $i ] [  "tarifa"] = $registro[ "tarifa" ];
			$i ++;
		}

		mysql_free_result( $resultado );
	}

	cierra_conexion($conexion);

	return $tarifas;
}

?>