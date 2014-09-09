<!--DataBaseLayer.php-->
<!--Capa principal de más alto nivel.-->

<?php
	
	#Agregamos todos los archivos con los proveedores
	#de bases de datos.
	include("MySqlProvider.php");

	class DataBaseLayer
	{
		//Almacena internamenta al proveedor de base de datos.
		private $provider;

		//Usado para los callbacks.
		private $params;

		//Almacena la instancia para el Singleton.
		private static $_con;

		/**
		* Constructor privado.
		* Utiliza patrón Singleton.
		*
		* @param $provider Proveedor de base de datos.
		*
		*/
		private function _construct($provider)
		{
			if(!class_exists($provider))
			{
				throw new Exception("El proveedor especificado no ha sido implementado.");
			}

			$this -> provider = new $provider;
			$this -> provider -> connect("host", "user", "password", "dbname");

			if(!$this -> provider -> isConnected())
			{
				/*Controlar error de conexión*/
			}
		}

		/**
		* Función del Singleton que devuelve o crea la instancia.
		*
		* @param $provider Proveedor de base de datos.
		*
		*/
		public static function getConnection($provider)
		{
			if(self::$_con)
			{
				return self::$_con;
			}
			else
			{
				$class = __CLASS__;
				self::$_con = new $class($provider);
				return self::$_con;
			}
		}

		/**
		* Función callback.
		*
		* @param
		*
		*/
		public function replaceParams($coincidencias)
		{
			$b = current($this -> params);
			next($this -> params);
			return $b;
		}

		/**
		* Se encarga de limpiar y poner los parámetros en su sitio.
		*
		* @param
		* @param
		*
		*/
		private function prepare($sql, $params)
		{
			for($i = 0; $i < sizeof($params); $i++)
			{
				if(is_bool($params[$i]))
				{
					$params[$i] = $params[$i] ? 1:0;
				}
				elseif(is_double($params[$i]))
				{
					$params[$i] = str_replace(',', '.', $params[$i]);
				}
				elseif(is_numeric($params[$i]))
				{
					$params[$i] = $this -> provider -> escape($params[$i]);
				}
				elseif(is_null($params[$i]))
				{
					$params[$i] = "NULL";
				}
				else
				{
					$params[$i] = "'".$this -> provider -> escape($parasm[$i])."'";
				}
			}

			$this -> params = $params;
			$query_text = preg_replace_callback("/(\?)/i", array($this,"replaceParams"), $sql);
		
			return $query_text;
		}

		/**
		* Envía la consulta al servidor.
		*
		* @param $query_text Variable que contiene el query.
		* @param $params     
		*
		*/
		private function sendQuery($query_text, $params)
		{
			$query = $this -> prepare($query_text, $params);
			$result = $this -> provider -> query($query);

			if($this -> provider -> getErrorNo())
			{
				/*Controlar errores*/
			}

			return result;
		}

		/**
		* Ejecuta una consulta, extrayendo solo la primera consulta
		* de la primera fila.
		*
		* @param $query_text Variable que contiene el query.
		* @param $params     
		*
		*/
		public function executeScalar($query_text, $params = null)
		{
			$result = $this -> sendQuery($query_text, $params);

			if(!is_null($result))
			{
				if(!is_object($result))
				{
					return $result;
				}
				else
				{
					$row = $this -> provider -> fetchArray($result);
					return $row[0];
				}
			}

			return null;
		}

		/**
		* Ejecuta una consulta y devuelve un array con las filas.
		*
		* @param $query_text Variable que contiene el query.
		* @param $params     
		*
		*/
		public function execute($query_text, $params = null)
		{
			$result = $this -> sendQuery($query_text, $params);

			if(is_object($result))
			{
				$arr = array();

				while($row = $this -> provider -> fetchArray($result))
				{
					$arr[] = $row;
				}
				return $arr;
			}
			return null;
		}
	}

?>