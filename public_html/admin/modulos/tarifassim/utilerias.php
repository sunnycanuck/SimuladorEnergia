<?php

function getAnyoInicial($arreglo ) {

	$total_elementos = count($arreglo);
    $anyo_tmp = 0;
    $anyo = 0;


   if( $total_elementos > 0 ) {

   		$anyo = (int) $arreglo[0]->getAnyo();

   		for( $i = 1; $i < $total_elementos; $i ++ ) {

			$anyo_tmp = (int)$arreglo[$i]->getAnyo();

   			if( $anyo_tmp < $anyo ) {
   				$anyo = $anyo_tmp;
   			}

   		}

   	}

	return $anyo;
}

function getAnyoFinal( $arreglo ) {

    $total_elementos = count($arreglo);
    $anyo_tmp = 0;
    $anyo = 0;


   if( $total_elementos > 0 ) {

   		$anyo = (int) $arreglo[0]->getAnyo();

   		for( $i = 1; $i < $total_elementos; $i ++ ) {

			$anyo_tmp = (int)$arreglo[$i]->getAnyo();

   			if( $anyo_tmp > $anyo ) {
   				$anyo = $anyo_tmp;
   			}

   		}

   	}

	return $anyo;
}


function getNumeroAnyos( $arreglo ) {
    return count( $arreglo );
}


function AVEDEV( $arreglo, $tipo_consumo ) {

	$total_anyos = count ($arreglo);
	$suma = 0.0;
	for( $i = 0; $i < $total_anyos; $i ++ ) {
		$suma += $arreglo[$i]->getTarifa( $tipo_consumo );

	}

	return (float) $suma / $total_anyos;
}

function getMedia_aritmetica( $arreglo ) {
		return (float) array_sum( $arreglo ) / count($arreglo);
}


function STDEV( $tarifas, $columna ) {

	$arreglo = array();
	$total_anyos = count ($tarifas );

	for( $i =0; $i < $total_anyos; $i ++ ) {

		array_push( $arreglo, $tarifas[$i]->getTarifa( $columna ) );
	}

	return getDesviacion_estandar( $arreglo, getMedia_aritmetica( $arreglo ) );

}

function getDesviacion_estandar( $arreglo, $media ) {
		return sqrt(
			array_sum(
				array_map( "desv_cuadrado", $arreglo, array_fill(0, count($arreglo), $media ) ) ) / (count($arreglo)-1)
				);
	}


	function desv_cuadrado($x, $media) {
		return pow($x - $media, 2);
	}

function indexa_horasMes($horasDelMes) {

	$arreglo = array();
	$total_anyos = count ($horasDelMes );


	for( $i = 0; $i <$total_anyos; $i ++ ) {


		if( isset( $arreglo[ $horasDelMes[$i][1] ] ) ) {
			$tmp = $arreglo[ $horasDelMes[$i][1] ];
			//echo "arreglo en el mes ". $horasDelMes[$i][1] . ", tiene ";
			//var_dump( $tmp ); echo "<br>";
			array_push( $arreglo, $horasDelMes[$i][0]  );
			$arreglo[ $horasDelMes[$i][1] ] = $tmp;
		} else {
			$arreglo[ $horasDelMes[$i][1] ] = array( $horasDelMes[$i][0] );
		}
	}
//var_dump( $arreglo );
	return $arreglo;
}

?>