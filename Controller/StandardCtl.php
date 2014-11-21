<?php
	class StandardCtl{
		
		/**
		 * Métodos de limpieza que serán heredados por los demás controladores.
		 */
		
		/**
		 * Limpia Texto.
		 *
		 * Función que se encarga de verificar si una variable es de tipo cadena o no.
		 *
		 * @param mixed $text - variable a validar.
		 *
		 * @return bool - FALSE si $text no es una cadena.
		 * @return string $result - la cadena ya limpia que venía en $text.
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
		 * Función que se encarga de verificar si una variable es de tipo entero o no.
		 *
		 * @param mixed $number - variable a validar.
		 *
		 * @return bool - FALSE si $number no es un entero.
		 * @return int $result - el valor entero que venía en $number.
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
		 * @return float $result - el valor flotante que venía en $number.
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
		 * Función que se encarga de verificar si una cadena de texto es un login válido o no.
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
		 * Función que se encarga de verificar si una cadena de texto es una contraseña válido o no.
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
		
		/**
		 * Limpia Nombre.
		 *
		 * Función que se encarga de verificar si una cadena de texto tiene el formato especificado para los nombres.
		 * El nombre puede contener minúsculas, mayúsculas y espacios.
		 *
		 * @param mixed $name - variable a validar.
		 *
		 * @return bool - FALSE si $name no es un nombre válido.
		 * @return string $result - cadena de caracteres con el texto recibido.
		 */
		function cleanName($name){
			$regex = "/[a-zA-Z][a-zA-Z\s]*/"; //Sólo letras y espacios
			
			$result = FALSE;
			if (preg_match($regex, $name)) {
				$result = $name;	
			}

			return $result;
		}
		
		/**
		 * Limpia Telefono.
		 *
		 * Función que se encarga de verificar si una cadena de texto tiene el formato especificado para los telefonos.
		 * La cadena sólo puede estar formada por digitos y debe tener una longitud de 6 a 12.
		 *
		 * @param mixed $tel - variable a validar.
		 *
		 * @return bool - FALSE si $tel no es un telefono válido.
		 * @return string $result - cadena de caracteres con el texto recibido.
		 */
		function cleanTel($tel){
			$regex = "/\d{6,12}/";  //Sólo cadenas de dígitos de longitud entre 6 y 12
			
			$result = FALSE;
			if (preg_match($regex, $tel)) {
				$result = $tel;	
			}

			return $result;
		}
		
		/**
		 * Limpia Bit.
		 *
		 * Función que se encarga de verificar si una cadena de texto tiene el formato especificado para las cadenas de bits.
		 * La cadena sólo puede estar formada por los digitos 0 y 1.
		 *
		 * @param mixed $bit - variable a validar.
		 *
		 * @return bool - FALSE si $bit no es una cadena válida.
		 * @return string $result - cadena de caracteres con el texto recibido.
		 */
		function cleanBit($bit){
			$regex = "/(0|1)/"; //Sólo 0 ó 1
			
			$result = FALSE;
			if (preg_match($regex, $bit)) {
				$result = $bit;	
			}

			return $result;
		}
		
		/**
		 * Limpia Fecha.
		 *
		 * Función que se encarga de verificar si una cadena de texto tiene el formato especificado para las fechas.
		 * Esta cadena puede estar formada sólo con la fecha o conhoras, minutos y segundos incluidos.
		 *
		 * @param mixed $datetime - variable a validar.
		 *
		 * @return bool - FALSE si $datetime no es una cadena válida.
		 * @return string $result - cadena de caracteres con el texto recibido.
		 */
		function cleanDateTime($datetime){
			//Formato Valido = YYYY-MM-DD (HH:MM:SS - opcional)
		
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

			//Validacion de las horas si es que las hay
			if(count($datetime_parts) > 1){
			
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
			}
			
			$result = $datetime;

			return $result;
		}
		
		/**
		 * Valida login.
		 *
		 * Función que se encarga de verificar si hay una sesión iniciada.
		 *
		 * @return bool - FALSE si no hay una sesión iniciada.
		 * @return bool - TRUE si hay una sesión iniciada.
		 */
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

		/**
		 * Muestra la vista GetId.
		 *
		 * Función que se encarga de mostrar la vista que solicitara el id necesario para
		 * realizar las acciones de actualización, consulta y eliminación.
		 *
		 * @param string $controller - controlador desde donde se llama la funcion.
		 * @param string $action - action a realizar con el id que se obtenga del formulario.
		 * @param string $id_field - nombre del campo como se pedira en $_POST.
		 * @param string $caption - texto a mostrar como label del input.
		 */
		function showGetIdView($controller,$action,$id_field,$caption){
			//Cargamos el formulario
			$view = file_get_contents("View/GetIdView.html");
			$header = file_get_contents("View/header.html");
			$footer = file_get_contents("View/footer.html");

			//Creamos el diccionario
			//Para el delete ponemos la accion en delete
			$dictionary = array(
								'{controller}' => $controller,
								'{action}' => $action,
								'{id-field}' => $id_field,
								'{id-caption}' => $caption,
							);
			
			//Sustituir los valores en la plantilla
			$view = strtr($view,$dictionary);

			//Sustituir el usuario en el header
			$dictionary = array(
								'{user-name}' => $_SESSION['user'],
								'{log-link}' => 'index.php?ctl=logout',
								'{log-type}' => 'Logout'
							);
			$header = strtr($header,$dictionary);

			//Agregamos el header y el footer a la vista
			$view = $header.$view.$footer;

			//Mostramos la vista
			echo $view;
		}

		/**
		 * Muestra la vista Error.
		 *
		 * Función que se encarga de mostrar la vista con el error especifico.
		 *
		 * @param string $error - cadena con el error a mostrar.
		 *
		 */
		function showErrorView($error){
			//Cargamos el formulario
			$view = file_get_contents("View/Error.html");
			$header = file_get_contents("View/header.html");
			$footer = file_get_contents("View/footer.html");

			//Creamos el diccionario
			//Para el insert los cmapos van vacios y los input estan activos
			$dictionary = array(
								'{error}' => $error
							);
			
			//Sustituir los valores en la plantilla
			$view = strtr($view,$dictionary);

			//Sustituir el usuario en el header
			$dictionary = array(
								'{user-name}' => $_SESSION['user'],
								'{log-link}' => 'index.php?ctl=logout',
								'{log-type}' => 'Logout'
							);
			$header = strtr($header,$dictionary);

			//Agregamos el header y el footer a la vista
			$view = $header.$view.$footer;

			//Mostramos la vista
			echo $view;
		}

		/**
		 * Muestra la vista de Eliminacion.
		 *
		 * Función que se encarga de mostrar la vista que indica que un registro se eliminó correctamente.
		 *
		 */
		function showDeleteView(){
			//Cargamos el formulario
			$view = file_get_contents("View/DeleteRegistry.html");
			$header = file_get_contents("View/header.html");
			$footer = file_get_contents("View/footer.html");

			//Sustituir el usuario en el header
			$dictionary = array(
								'{user-name}' => $_SESSION['user'],
								'{log-link}' => 'index.php?ctl=logout',
								'{log-type}' => 'Logout'
							);
			$header = strtr($header,$dictionary);

			//Agregamos el header y el footer a la vista
			$view = $header.$view.$footer;

			//Mostramos la vista
			echo $view;
		}

		/**
		 * Muestra la vista de Login.
		 *
		 * Función que se encarga de mostrar la vista para hacer login.
		 *
		 * @param string $ctl - cadena con el controlador desde el que fue llamado.
		 * @param string $act - cadena con la accion que se iba a realizar.
		 *
		 */
		function showLoginView($ctl,$act){
			//Cargamos el formulario
			$view = file_get_contents("View/Login.html");
			$header = file_get_contents("View/header.html");
			$footer = file_get_contents("View/footer.html");

			//Creamos el diccionario
			//Ponemos la accion y el controlador desde donde se llamo el login para regresar ahi cuando inicie sesion
			$dictionary = array(
								'{controller}' => $ctl,
								'{action}' => $act
							);
			
			//Sustituir los valores en la plantilla
			$view = strtr($view,$dictionary);

			//Sustituir los valores en el header de usuario
			$dictionary = array(
								'{user-name}' => 'Login',
								'{log-link}' => 'index.php',
								'{log-type}' => 'Login'
							);
			$header = strtr($header,$dictionary);

			//Agregamos el header y el footer a la vista
			$view = $header.$view.$footer;

			//Mostramos la vista
			echo $view;
		}
	}

?>
