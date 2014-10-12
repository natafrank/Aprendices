<?php
	class StandardCtl{
		
		//Metodos de limpieza que seran heredados por los demas controladores
		//Se agrgaran mas metodos segun se requiera
		
		function cleanText($text){
			if(is_string($text)){
				$result = addslashes(trim($text));
				if(strlen($result)){
					return $result;
				}
			}
			return FALSE;
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
		
		function cleanEmail($email)
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

		//El login tiene que empezar con letra, tiene mínimo 6 y máximo de 20 caracteres
		//y puede usar la cantidad de números que quiera.
		function cleanLogin($login)
		{
			$regex = "/^[a-zA-z][a-zA-Z\d]{5,19}/";

			if(preg_match($regex, $login))
			{
				return $login;
			}
			else
			{
				return FALSE;
			}
		}
		
		//La contraseña tiene tamaño mínimo de 8 caracteres,
		//debe contener un número y puede tener cualquier caracter.
		function cleanPassword($password)
		{
			$regex = "/.*(?=.{8,})(?=.*\d).*/";

			if(preg_match($regex, $password))
			{
				return $password;
			}
			else
			{
				return FALSE;
			}
		}

		function cleanName($name){
			$regex = "/[a-zA-Z][a-zA-Z\s]*/"; //Sólo letras y espacios
			
			$result = FALSE;
			if (preg_match($regex, $name)) {
				$result = $name;	
			}

			return $result;
		}
		
		function cleanTel($tel){
			$regex = "/\d{6,12}/";  //Sólo cadenas de dígitos de longitud entre 6 y 12
			
			$result = FALSE;
			if (preg_match($regex, $tel)) {
				$result = $tel;	
			}

			return $result;
		}
		
		function cleanBit($bit){
			$regex = "/(0|1)/"; //Sólo 0 ó 1
			
			$result = FALSE;
			if (preg_match($regex, $bit)) {
				$result = $bit;	
			}

			return $result;
		}
		
		function cleanDateTime($datetime){
			//Formato Valido = YYYY-MM-DD HH:MM:SS
		
			$datetime_parts = explode(" ",$datetime);
			
			$date_parts = explode("-",$datetime_parts[0]);
			
			$result = FALSE;
			
			//El explode debe regresar solo tres elementos en el arreglo
			if(count($date_parts) != 3){
				return $result;
			}
			
			//Validacion de la fecha
			if (!checkdate ($date_parts[1],$date_parts[2],$date_parts[0]))  //Parametros: Mes, Dia, Año
			{ 
				return result; 
			}
			
			$time_parts = explode(":",$datetime_parts[1]);
			
			//El explode debe regresar solo tres elementos en el arreglo
			if(count($time_parts) != 3){
				return $result;
			}
			
			$regex = "/(((0|1)(\d))|(2[0-3]))/";  //Validacion para las horas (00-23)
			if (!preg_match($regex, $time_parts[0])) {
				return $result;	
			}
			
			$regex = "/([0-5](\d))/";  //Validacion para los minutos (00-59)
			if (!preg_match($regex, $time_parts[1])) {
				return $result;	
			}
			
			$regex = "/([0-5](\d))/";  //Validacion para los segundos (00-59)
			if (!preg_match($regex, $time_parts[2])) {
				return $result;	
			}
			
			$result = $datetime;

			return $result;
		}
		
		//si inicia una sesion si es que no existe
		function isLogged()
		{
			if( isset($_SESSION['user']) )
				return true;
			return false;
		}

		//Valida que el usuario de la sesion actual sea de tipo administrador
		function isAdmin()
		{
			if( isset($_SESSION['usertype']) && $_SESSION['usertype'] == 1 )
				return true;
			return false;
		}
		
		//Valida que el usuario de la sesion actual sea de tipo empleado
		function isEmployee()
		{
			if( isset($_SESSION['usertype']) && $_SESSION['usertype'] == 2 )
				return true;
			return false;
		}
		
		//Valida que el usuario de la sesion actual sea de tipo cliente
		function isClient()
		{
			if( isset($_SESSION['usertype']) && $_SESSION['usertype'] == 3 )
				return true;
			return false;
		}

		//Termina la sesion actual
		function logout()
		{
			session_unset();
			session_destroy();		
			setcookie(session_name(), '', time()-3600);
		}

		//Inicia una sesion en base al login y contraseña del usuario
		function login($login, $pass)
		{
			//Importamos la capa de la base de datos.
			require_once("Model/Database Motor/DatabaseLayer.php");

			//Creamos la conexión.
			$db_driver = DatabaseLayer::getConnection("MySqlProvider");
			
			$query = "SELECT * FROM User WHERE Login='".$login."' AND Pass='".$pass."';";
			
			//Ejecutamos la consulta
			$result = $db_driver -> execute($query);
			
			//Si nos regresa una fila ingresar los valores del usuario en la sesion
			if($result == null)
			{
				$_SESSION['user'] = $result[0]['User'];
				$_SESSION['usertype'] = $result[0]['idUserType'];
				return true;
			}
			else
			{
				return false;
			}
		}
	}

?>
