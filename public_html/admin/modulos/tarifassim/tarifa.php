<?php

/************************************************************************************
 * Autor: Héctor Mora.                                                              *
 * Fecha: 04-Febrero-2012                                                           *
 * Descripción: Estructura para almacenar cada registro de la tabla ce_tarifas_xx   *
 ************************************************************************************/

class Tarifa {

   // Fecha
   private $anyo;

   // Tarifas
   private $tarifas;

   public function __construct( $registro, $anyo ) {

   		$this->tarifas = $registro;
   		$this->anyo = $anyo;
    }

    public function getTarifa ( $tarifa ) { return $this->tarifas[$tarifa]; }
    public function getAnyo   ()          { return $this->anyo;             }
    public function getTarifas()          { return $this->tarifas;          }
}

function setTarifa( $arr_tarifas, $registro ) {

        $meses = array( "Jan" => 1, "Feb" => 2, "Mar" => 3, "Apr" => 4, "May" => 5, "Jun" => 6, "Jul" => 7, "Aug" => 8, "Sept" =>9, "Sep" => 9, "Oct" =>10, "Nov" => 11, "Dec" => 12);

   		$fecha = $registro["fecha"]; // OBTIENE COLUMNA AÑO

		////////////////////// SEPARA MES Y AÑO ////////////////////////////
		$pos  = strrpos( $fecha, "-");
		$mes  = substr( $fecha, 0, $pos );
		$anyo = substr( $fecha, $pos + 1 );

		if( strlen( $anyo ) == 2 ) {
			$anyo = "20" . $anyo;
		}
		////////////////////////////////////////////////////////////////////

	     if( isset ( $arr_tarifas[ $meses[$mes] ] ) ) {

			$arrtmp = $arr_tarifas[$meses[$mes] ] ;

			array_push( $arrtmp, new Tarifa( $registro, $anyo ) );
			$arr_tarifas[$meses[$mes] ] = $arrtmp ;

	 	 } else {

			$arr_tarifas[ $meses[$mes] ] = array( new Tarifa( $registro, $anyo ) );
		 }


	return $arr_tarifas;
  }

?>