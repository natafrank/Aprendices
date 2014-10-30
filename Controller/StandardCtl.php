<?php
	class StandardCtl{
		
		/**
		 * Metodos de limpieza que seran heredados por los demas controladores.
		 */
		
		/**
		 * Limpia Texto.
		 *
		 * Función que se encarga de varificar si una variable es de tipo cadena o no.
		 *
		 * @param mixed $text - variable a validar.
		 *
		 * @return bool - FALSE si $text no es una cadena.
		 * @return string $result - la cadena ya limpia que venia en $text.
		 */
		function cleanText($text){
			if(is_string($text)){
				$result = addslashes(trim($text));
				if(strlen($result)){
					return $result;
				}
			}
			return FALSE;
		}
		
		/**
		 * Limpia Entero.
		 *
		 * Función que se encarga de varificar si una variable es de tipo entero o no.
		 *
		 * @param mixed $number - variable a validar.
		 *
		 * @return bool - FALSE si $number no es un entero.
		 * @return int $result - el valor entero que venia en $number.
		 */
		function cleanInt($number){
			if(is_numeric($number)){
				$result = $number;
				return $result;
			}

			return FALSE;
		}
		
		/**
		 * Limpia Flotante.
		 *
		 * Función que se encarga de varificar si una variable es de tipo flotante o no.
		 *
		 * @param mixed $number - variable a validar.
		 *
		 * @return bool - FALSE si $number no es un número flotante.
		 * @return float $result - el valor flotante que venia en $number.
		 */
		function cleanFloat($number){
			if(is_float($number)){
				$result = $number;
				return $result;
			}
			return FALSE;
		}	
		
		/**
		 * Limpia Correo.
		 *
		 * Función que se encarga de varificar si una cadena de texto es un correo válido o no.
		 *
		 * @param mixed $email - variable a validar.
		 *
		 * @return bool - FALSE si $email no es un correo válido.
		 * @return string $email - la cadena de caracteres con el correo.
		 */
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

		/**
		 * Limpia Login.
		 *
		 * Función que se encarga de varificar si una cadena de texto es un login válido o no.
		 * El login tiene que empezar con letra, tiene mínimo 6 y máximo de 20 caracteres y puede usar la cantidad de números que quiera.
		 *
		 * @param mixed $login - variable a validar.
		 *
		 * @return bool - FALSE si $login no es un login válido.
		 * @return string $login - la cadena de caracteres con el login.
		 */
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
		
		/**
		 * Limpia Contraseña.
		 *
		 * Función que se encarga de varificar si una cadena de texto es una contraseña válido o no.
		 * La contraseña tiene tamaño mínimo de 8 caracteres, debe contener un número y puede tener cualquier caracter.
		 *
		 * @param mixed $password - variable a validar.
		 *
		 * @return bool - FALSE si $password no es una contraseña válida.
		 * @return string $password - la cadena de caracteres con la contraseña.
		 */
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
			if( isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1 )
				return true;
			return false;
		}
		
		//Valida que el usuario de la sesion actual sea de tipo empleado
		function isEmployee()
		{
			if( isset($_SESSION['user_type']) && $_SESSION['user_type'] == 2 )
				return true;
			return false;
		}
		
		//Valida que el usuario de la sesion actual sea de tipo cliente
		function isClient()
		{
			if( isset($_SESSION['user_type']) && $_SESSION['user_type'] == 3 )
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
			
			$query = "SELECT * FROM User WHERE Login='".$login."' AND Password='".$pass."';";
			
			//Ejecutamos la consulta
			$result = $db_driver -> execute($query);
			
			//Si nos regresa una fila ingresar los valores del usuario en la sesion
			if($result != null)
			{
				$_SESSION['id_user'] = $result[0]['idUser'];
				$_SESSION['user'] = $result[0]['User'];
				$_SESSION['user_type'] = $result[0]['idUserType'];
				return true;
			}
			else
			{
				return false;
			}
		}
	}

?>
