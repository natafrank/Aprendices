<!--MySqlProvider.php-->
<!--Implementación del motor de bases de datos MySql-->

<?php

	require("AbstractDatabase.php");

	class MySqlProvider extends AbstractDatabase
	{
		/**
		* @override
		* Función encargada de establecer la conexión con la base de datos.
		* 
		* @param $host     Host de la base de datos.
		* @param $user     Usuario con el que se va a acceder.
		* @param $password Contraseña del usuario.
		* @param $dbname   Nombre de la base de datos.
		*
		*/
		public function connect($host, $user, $password, $dbname)
		{
			$this -> connection = new mysqli($host, $user, $password, $dbname);
			return $this -> connection;
		}


		/**
		* Función que devuelve el número de error en caso de existir un error al realizar
		* una consulta.
		*
		*/
		public function getErrorNumber()
		{
			return mysqli_errno($this -> connection);
		}

		/**
		* Función que devuelve una cadena con las características del último error.
		*
		*/
		public function getError()
		{
			return mysqli_error($this -> resource);
		}

		/**
		* Función que se encarga de ejecutar un query.
		*
		* @param $query_text Contiene el query a ejecutar.
		*/
		public function query($query_text)
		{	
			return mysqli_query($this -> connection, $query_text);
		}

		/**
		* Función que convierte en array la fila actual y mueve el cursor.
		*
		* @param $connection Objeto que contiene la conexión.
		*/
		public function fetchArray($result)
		{
			return mysqli_fetch_array($result);
		}

		/**
		* Función que comprueba si está establecida una conexión.
		*
		*/
		public function isConnected()
		{
			return !is_null($this -> connection);
		}

		/**
		* Función que escapa los caracteres de una cadena para prevenir una inyección.
		*
		* @param $var Contiene la cadena a escapar.
		*/
		public function escape($var)
		{
			return mysqli_real_escape_string($this -> connection, $var);
		}
	}

?>