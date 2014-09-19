<?php
	class StandardCtl{
		
		//Metodos de limpieza que seran heredados por los demas controladores
		//Se agrgaran mas metodos segun se requiera
		
		function cleanText($text){
			$result = '';
			if(is_string($text)){
				$result = $text;
			}
			return $result;
		}
		
		function cleanInt($number){
			$result = 0;
			if(is_numeric($number)){
				$result = $number;
			}
			return $result;
		}
		
		function cleanFloat($number){
			$result = 0.0;
			if(is_float($number)){
				$result = $number;
			}
			return $result;
		}	
		
		function cleantEmail($email)
		{
			$regex = "/^[a-zA-Z].*@\w+\..+/";

			if(preg_match($regex, $email))
			{
				return $email;
			}
			else
			{
				return FALSE;
			}
		}
	}

?>
