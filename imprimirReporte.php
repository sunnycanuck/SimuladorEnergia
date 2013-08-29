<link rel="stylesheet" type="text/css" href="css/print.css" media="print"> 
<link rel="stylesheet" type="text/css" href="css/print.css" media="screen"> 
<?php
	require("conexion.php");
	$terreno_id = $_REQUEST["tid"];
	$caso_id    = $_REQUEST["cid"];

	$tarifa = getTarifa( $terreno_id );
	$segundo_anyo = getSegundoAnyo( $terreno_id, $caso_id);
	$arr_consumos_mes = getConsumosMes( $terreno_id, 1, $segundo_anyo );
	$arr_costos_mes   = getCostosMes( $terreno_id, 1, $segundo_anyo );
	$generacion_fotovoltaica = getGeneracionFotovoltaica( $terreno_id, $caso_id, $segundo_anyo );
	$consumo_electrico_CFE   = getConsumosMes( $terreno_id, $caso_id, $segundo_anyo );
	$capacidad_sistema  = getCapacidadSistema( $terreno_id, $caso_id ) / 1000 . ' kWp'; //"1 kWp";
	//$ciudad_instalacion = getCiudadInstalacion( $terreno_id );
	$estacion = getCiudadEstacion( $terreno_id );
	$ahorro_anual       = getAhorroAnual($terreno_id, $caso_id, $segundo_anyo);
	$costo_total        = "$" . number_format( getCostoTotal( $terreno_id, $caso_id ), 2 );
	$tiempo_amortizacion = getTiempoAmortizacion( $terreno_id, $caso_id );
	$ahorro_mensual = getAhorroMensual( $terreno_id, $caso_id, $segundo_anyo );
	$pago_cfe_sistema = getPagoMensual( $terreno_id, $caso_id, $segundo_anyo );

?>

<div id="print-container">
  <div id="reporte">  
    <div class="spacer_20"></div>
    <div style="float:left; width:46%;">
    <strong>Historial de consumo el&eacute;ctrico CFE (kWh)</strong>
    Tarifa el&eacute;ctrica CFE: <?php echo $tarifa; ?>
    </div>
    <div style="float:left; width:54%" align="right">
    	<a href="javascript:window.print()">Imprimir <img src="images/print.png" alt="Imprimir" width="16" /></a> | <a href="javascript:window.close()">Cerrar Ventana <img src="images/close-window.png" alt="Cerra Ventana" /></a>
    </div>
    <br clear="all" />
    <table>
      <tr>
        <td class="headback">Mes</td>
        <td>Ene <?php echo $segundo_anyo; ?></td>
        <td>Feb <?php echo $segundo_anyo; ?></td>
        <td>Mar <?php echo $segundo_anyo; ?></td>
        <td>Abr <?php echo $segundo_anyo; ?></td>
        <td>May <?php echo $segundo_anyo; ?></td>
        <td>Jun <?php echo $segundo_anyo; ?></td>
        <td>Jul <?php echo $segundo_anyo; ?></td>
        <td>Ago <?php echo $segundo_anyo; ?></td>
        <td>Sep <?php echo $segundo_anyo; ?></td>
        <td>Oct <?php echo $segundo_anyo; ?></td>
        <td>Nov <?php echo $segundo_anyo; ?></td>
        <td>Dic <?php echo $segundo_anyo; ?></td>
      </tr>
      <tr>
        <td class="headback">Consumo el&eacute;ctrico (kWh)</td>
        <?php
          for( $i = 0; $i <12; $i ++ ) {
          echo "<td align='right'>" . $arr_consumos_mes[$i] . "</td>";
          }
        ?>
      </tr>    
      <tr>
        <td class="headback">Factura CFE (pesos)</td>
        <?php
        for( $i = 0; $i <12; $i ++ ) {
          echo "<td width='80px'>$" . $arr_costos_mes[$i] . "</td>";
        }
        ?>
      </tr>
    </table>
    
    <strong>Informaci&oacute;n sobre el sistema fotovoltaico</strong>
    
    <table>
      <thead>
        <tr>
          <th>Capacidad del sistema:</th>
          <th>Ciudad de instalaci&oacute;n:</th>
          <th>Costo total del sistema (pesos)</th>
          <th>Vida &uacute;til del sistema:</th>
        </tr>
      </thead>
      <tbody>
        <tr>      
          <td><?php echo $capacidad_sistema; ?></td>      
          <td><?php echo $estacion; ?></td>
          <td><?php echo $costo_total; ?></td>
          <td>25 a&ntilde;os</td>
        </tr>
      </tbody>
    </table>
    
    <strong>Ahorro obtenido y tiempo de amortizaci&oacute;n del sistema fotovoltaico de <?php echo $capacidad_sistema; ?></strong>    
    <table>
      <thead>
        <tr>
          <th>Ahorro anual en facturaci&oacute;n el&eacute;ctrica (pesos)</th>
          <th>Tiempo de amortizaci&oacute;n:</th>        
        </tr>
      </thead>
      <tbody>
        <tr>      
          <td>$<?php echo number_format( $ahorro_anual, 2 ); ?></td>      
          <td><?php echo $tiempo_amortizacion; ?></td>
        </tr>
      </tbody>
    </table>
    
    <table>
      <tr>
        <td class="headback">Mes</td>
        <td>Ene <?php echo $segundo_anyo; ?></td>
        <td>Feb <?php echo $segundo_anyo; ?></td>
        <td>Mar <?php echo $segundo_anyo; ?></td>
        <td>Abr <?php echo $segundo_anyo; ?></td>
        <td>May <?php echo $segundo_anyo; ?></td>
        <td>Jun <?php echo $segundo_anyo; ?></td>
        <td>Jul <?php echo $segundo_anyo; ?></td>
        <td>Ago <?php echo $segundo_anyo; ?></td>
        <td>Sep <?php echo $segundo_anyo; ?></td>
        <td>Oct <?php echo $segundo_anyo; ?></td>
        <td>Nov <?php echo $segundo_anyo; ?></td>
        <td>Dic <?php echo $segundo_anyo; ?></td>
      </tr>
      <tr>
        <td class="headback">Generaci&oacute;n fotovoltaica (kWh)</td>
        <?php
          for( $i = 0; $i <12; $i ++ ) {
          echo "<td>" . number_format( $generacion_fotovoltaica[$i], 2 ) . "</td>";
          }
        ?>
      </tr>
      <tr>
        <td class="headback">Consumo el&eacute;ctrico a CFE (kWh)</td>
        <?php
        for( $i = 0; $i <12; $i ++ ) {
          echo "<td>" . $consumo_electrico_CFE[$i] . "</td>";
        }
        ?>
      </tr>
      <tr>
        <td class="headback">Ahorro mensual(pesos)</td>
        <?php
        for( $i = 0; $i <12; $i ++ ) {
          echo "<td width='80px'>$" . number_format( $ahorro_mensual[$i], 2 ) . "</td>";
        }
        ?>
      </tr>    
      <tr>
        <td class="headback">Pago a CFE con Sistema</td>
        <?php
        for( $i = 0; $i <12; $i ++ ) {
          echo "<td>$" . number_format( $pago_cfe_sistema[$i], 2 ) . "</td>";
        }
        ?>
      </tr>   
    </table>
    
  </div><!-- #reporte -->
</div><!-- #print-container -->
<?php

	function getTarifa( $tid ) {

		$tarifa = "";
		$tabla = "ce_cfe_consumohistorico_". $tid ."t";

		$query =  mysql_fetch_array( mysql_query("SELECT B.tarifa FROM ce_consumo A, ce_tarifas B WHERE A.secuencia = '$tabla' AND A.tipo = B.id_tarifa") );

		$tarifa = $query["tarifa"];

		return $tarifa;
	}

	function getSegundoAnyo( $tid, $cid ){

		$anyo = 0;
		$result = mysql_query("SELECT anyo FROM ce_medidorCFE_" . $tid ."t" . $cid . "c limit 1");

		if( $result ) {

			if( $rows =  mysql_fetch_array( $result ) ) {
				$anyo = $rows["anyo"];
				$anyo ++;
			}

			mysql_free_result( $result );

		}

		return $anyo;
	}

	function getConsumosMes( $tid, $cid, $anyo ) {

		$consumos = Array();
		$i = 0;

		$result = mysql_query("SELECT consumo FROM ce_medidorCFE_" . $tid ."t" . $cid . "c WHERE $anyo = " . $anyo );

		if( $result ) {

			while( $rows =  mysql_fetch_array( $result ) ) {
				$consumos[ $i ] = number_format( $rows["consumo"], 2 );

				$i ++;
			}

			mysql_free_result( $result );

		}

		return $consumos;
	}

	function getCostosMes( $tid, $cid, $anyo ) {

		$am  = Array(0,0,0,0,0,0,0,0,0,0,0,0);
				$costos = Array();
				$j = 1;

				$result = mysql_query( "SELECT consumo".  $cid . " as consumo FROM ce_costodeconsumo_" . $tid . "t WHERE anyo = " . ($anyo -1) . " AND mes = 12"  );

						if( $result ) {

							if( $rows = mysql_fetch_array( $result ) ) {
								$costos[ 0 ] = $rows["consumo"];
							}

							mysql_free_result( $result );

				}

				$result = mysql_query( "SELECT consumo".  $cid . " as consumo FROM ce_costodeconsumo_" . $tid . "t WHERE anyo = " . $anyo );

				if( $result ) {

					while( $rows = mysql_fetch_array( $result ) ) {
						$costos[ $j ] = $rows["consumo"];
						$j ++;
					}

					mysql_free_result( $result );

				}

				for( $i = 1; $i <= 12; $i ++ ) {

					$am[ $i - 1] = number_format ( $costos[ $i] - $costos[ ($i - 1) ], 2 );

				}


		return $am;

	}


	function getCiudadEstacion( $tid ) {

		$ciudad = "";
		$result = mysql_query("SELECT ce_terreno.estacionid, ce_estacionesclima.nombre FROM ce_terreno JOIN ce_estacionesclima ON ce_terreno.estacionid = ce_estacionesclima.idEstacion WHERE id = $tid");

		if( $result ) {

			if( $rows =  mysql_fetch_array( $result ) ) {
				$estacion = $rows["nombre"];
			}

			mysql_free_result( $result );

		}

		return $estacion;
	}
	
	function getCiudadInstalacion( $tid ) {

		$ciudad = "";
		$result = mysql_query("SELECT ubicacion FROM ce_terreno WHERE id = $tid");

		if( $result ) {

			if( $rows =  mysql_fetch_array( $result ) ) {
				$ciudad = $rows["ubicacion"];
			}

			mysql_free_result( $result );

		}

		return $ciudad;
	}

	function getCostoTotal( $tid, $cid ) {

		$suma = 0;
		$result = mysql_query("SELECT ( (B.precio_dispositivo + B.precio_instalacion ) * A.dispositivos ) AS suma FROM ce_casos_". $tid . "t A, ce_dispositivos B WHERE A.caso = $cid AND A.id_dispositivo = B.id_dis");

		if( $result ) {

			while( $rows =  mysql_fetch_array( $result ) ) {
				$suma += $rows["suma"];
			}

			mysql_free_result( $result );

		}

		return $suma;

	}


	function getAhorroAnual( $tid, $cid, $segundo_anyo ) {

		$ahorro_anual = 0;
		$costo_caso_1 = getCostoAnual( $tid, 1, $segundo_anyo );
		$costo_caso_n = getCostoAnual( $tid, $cid, $segundo_anyo );
		$ahorro_anual = $costo_caso_1 - $costo_caso_n;

		return $ahorro_anual;
	}

	function getCostoAnual( $tid, $cid, $anyo ) {

		$suma = 0;
		$consumo_1 = 0; $consumo_12 = 0;
		$mes = "";
		$result = mysql_query("SELECT mes, consumo" . $cid . " AS consumo FROM ce_costodeconsumo_" . $tid . "t WHERE anyo = $anyo AND mes in(1, 12)");

		if( $result ) {

			while( $rows =  mysql_fetch_array( $result ) ) {
				$mes = $rows["mes"];
				if( $mes == 1 ) { $consumo_1 = $rows["consumo"]; }
				if( $mes ==12 ) { $consumo_12= $rows["consumo"]; }
			}

			mysql_free_result( $result );

		}

		return $consumo_12 - $consumo_1;
	}


	function getCapacidadSistema( $tid, $cid ) {

		$capacidad = " kWp";
		$cuenta = 0;
		$factores = "" ;
		$dispositivos = 0;
		$potencia = 0;
		$fact_arr = Array();


		$result = mysql_query( "select B.factores, A.dispositivos FROM ce_casos_". $tid . "t A, ce_dispositivos B WHERE A.id_tipo = 1 AND A.id_dispositivo = B.id_dis" );

		if( $result ) {

			while( $rows = mysql_fetch_array( $result ) ) {
				$factores     = $rows["factores"];
				$dispositivos = $rows["dispositivos"];

				$fact_arr = explode( ';', $factores );
				$potencia = $fact_arr[4];


				$cuenta = $cuenta + ( $dispositivos *  $potencia );
			}

			mysql_free_result( $result );
		}

		return $cuenta;
	}

	function getGeneracionFotovoltaica( $tid, $cid, $anyo ) {
		$fv  = Array(0,0,0,0,0,0,0,0,0,0,0,0);
		$ids = Array();
		$i = 0;
		$j = 0;

		$result = mysql_query( "SELECT id FROM ce_casos_" . $tid . "t WHERE id_tipo = 4" );

		if( $result ) {

			while( $rows = mysql_fetch_array( $result ) ) {
				$ids[ $i ] = $rows["id"];
				$i ++;
			}

			mysql_free_result( $result );
		}

		for( $cont = 0; $cont < $i; $cont ++ ) {

			$j = 0;
			$result = mysql_query( "SELECT potenciaCS AS potencia FROM ce_gridtie_" . $tid . "t_" . $ids[$cont] . "g WHERE ano = ". $anyo ." ORDER BY mes ASC" );

			if( $result ) {
				while( $rows = mysql_fetch_array( $result ) ) {
					$fv[ $j ] += $rows["potencia"];
					$j ++;
				}

				mysql_free_result( $result );
			}

		}

		return $fv;
	}

	function getAhorroMensual( $tid, $cid, $anyo ) {
		$caso_1 =  getPagoMensual( $tid, 1, $anyo );
		$caso_n =  getPagoMensual( $tid, $cid, $anyo );
		$r = Array();


		for( $i = 0; $i < 12; $i ++ ) {
			$r[$i] = number_format( $caso_1[ $i ] - $caso_n[ $i ], 2 );
		}

		return $r;
	}

	function getPagoMensual( $tid, $cid, $anyo ) {
		$am  = Array(0,0,0,0,0,0,0,0,0,0,0,0);
		$costos = Array();
		$j = 1;

		$result = mysql_query( "SELECT consumo" . $cid . " as consumo FROM ce_costodeconsumo_" . $tid . "t WHERE anyo = " . ($anyo -1) . " AND mes = 12"  );

				if( $result ) {

					if( $rows = mysql_fetch_array( $result ) ) {
						$costos[ 0 ] = $rows["consumo"];
					}

					mysql_free_result( $result );

		}

		$result = mysql_query( "SELECT consumo" . $cid . " as consumo FROM ce_costodeconsumo_" . $tid . "t WHERE anyo = " . $anyo  );

		if( $result ) {

			while( $rows = mysql_fetch_array( $result ) ) {
				$costos[ $j ] = $rows["consumo"];
				$j ++;
			}

			mysql_free_result( $result );

		}

		for( $i = 1; $i <= 12; $i ++ ) {

			$am[ $i - 1] = $costos[ $i] - $costos[ ($i - 1) ];

		}


		return $am;
	}


	function getTiempoAmortizacion( $tid, $cid ) {
		$anyos = 0;
		$numero_meses = 0;
		$tiempo = "" ;

		$result = mysql_query( "SELECT id FROM ce_costodeconsumo_" . $tid . "t WHERE consumo1 > consumo" . $cid );

			if( $result ) {

				if( $rows = mysql_fetch_array( $result ) ) {
					$numero_meses = $rows["id"];
				}

				mysql_free_result( $result );

			}

		$anyos = floor( $numero_meses / 12 );


		$numero_meses = $numero_meses - ($anyos * 12);

		$tiempo = $anyos . " a&ntilde;os";

		if( $numero_meses > 0 ) {
			$tiempo = $tiempo . ", " . $numero_meses . " meses" ;
		}


		return $tiempo;
	}

?>
