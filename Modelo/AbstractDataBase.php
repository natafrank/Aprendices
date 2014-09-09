<!--AbstractDataBase.php-->

<?php

	abstract class AbstractDataBase
	{
		//Objeto de que guarda la conexión
		protected $conection;

		/**
		* Función encargada de establecer la conexión con la base de datos.
		* 
		* @param $host     Host de la base de datos.
		* @param $user     Usuario con el que se va a acceder.
		* @param $password Contraseña del usuario.
		* @param $dbname   Nombre de la base de datos.
		*
		*/
		public abstract function connect($host, $user, $password, $dbname);

		/**
		* Función que devuelve el número de error en caso de existir un error al realizar
		* una consulta.
		*
		*/
		public abstract function getErrorNumber();

		/**
		* Función que devuelve una cadena con las características del último error.
		*
		*/
		public abstract function getError();

		/**
		* Función que se encarga de ejecutar un query.
		*
		* @param $query_text Contiene el query a ejecutar.
		*/
		public abstract function query($quey_text);

		/**
		* Función que convierte en array la fila actual y mueve el cursor.
		*
		* @param $connection Objeto que contiene la conexión.
		*/
		public abstract function fetchArray($connection);

		/**
		* Función que comprueba si está establecida una conexión.
		*
		*/
		public abstract function isConnected();

		/**
		* Función que escapa los caracteres de una cadena para prevenir una inyección.
		*
		* @param $var Contiene la cadena a escapar.
		*/
		public abstract function escape($var);
	}

?>