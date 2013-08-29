<?php
function query(){
	
	switch($_REQUEST['act']){
		
		case 1:{	
			$ano = $_REQUEST['ano'];			
			$ano_inicio = $_REQUEST['desde'];
			$ano_final = $_REQUEST['hasta'];			
			for($k=$ano_inicio; $k<=$ano_final; $k++){	
				
				$query = mysql_query("SELECT * FROM ce_horasDelMes WHERE ano = $k");	
				$num_rows = mysql_num_rows($query);
				if($num_rows==0){
		
					for($j=1; $j<=12; $j++){
					
						$dias = cal_days_in_month(CAL_GREGORIAN, $j, $k);
						$horas = $dias * 24;
						$domingo = $lunes = $martes = $miercoles = $jueves = $viernes =	$sabado = 0;
						
						for($i=1; $i<=$dias; $i++){
							$dia_semana = jddayofweek ( cal_to_jd(CAL_GREGORIAN, $j,$i,$k) , 0 );
							switch($dia_semana){
								case 0: $domingo++; break;
								case 1: $lunes++; break;
								case 2: $martes++; break;
								case 3: $miercoles++; break;
								case 4: $jueves++; break;
								case 5: $viernes++; break;
								case 6: $sabado++; break;
							}
						}//for 2
						
						
						mysql_query("INSERT INTO ce_horasDelMes (ano, mes, num_dias, num_horas, num_lunes, num_martes, num_miercoles, num_jueves, num_viernes, num_sabados, num_domingos) 
												 VALUES($k, $j, $dias, $horas, $lunes, $martes, $miercoles, $jueves, $viernes, $sabado, $domingo)") or die('Hubo un error en el query, favor de regresar e intentarlo nuevamente.');
												 
						
						
					}//for 1
				
					$url = 'index.php?mod=4&msj=1&ai='.$ano_inicio.'&af='.$ano_final;
				
				}else{
					$url = 'index.php?mod=4&msj=2&y='.$k.'';break;		
				}
				
			}//for 0
		
		}break;
		
		case 2:{
			mysql_query("DELETE FROM ce_horasDelMes") or die("No se eliminaron las fechas, consulte a su administrador");
			$url = 'index.php?mod=4&msj=3';
		}break;
		
	}
		
	return $url;
}
?>