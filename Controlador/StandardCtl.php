<?php
	class StandardCtl{
		
		//Metodos de limpieza que seran heredados por los demas controladores
		//Se agrgaran mas metodos segun se requiera
		
		function limpiaTexto($texto){
			$resultado = '';
			if(is_string($texto)){
				$resultado = $texto;
			}
			return $resultado;
		}
		
		function limpiaNumeroInt($numero){
			$resultado = 0;
			if(is_int($numero)){
				$resultado = $numero;
			}
			return $resultado;
		}
		
		function limpiaNumeroFloat($numero){
			$resultado = 0.0;
			if(is_float($numero)){
				$resultado = $numero;
			}
			return $resultado;
		}	
		
	}

?>
