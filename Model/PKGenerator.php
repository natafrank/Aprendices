<?php
	class PKGenerator{
		
		/**
		 * Obtiene PK.
		 *
		 * Función para obtener la PK correspondiente al nuevo registro de la tabla dada.
		 *
		 * @param string $table   - Tabla donde se insertará el nuevo registro.
		 * @param string $pkfield   - campo llave de la tabla especificada.
		 *
		 * @return int - PK obtenido.
		 */
		public static function getPK($table, $pkfield)
		{	
			//Importamos la capa de la base de datos.
			require_once("Model/Database Motor/DatabaseLayer.php");
			//Creamos la conexión.
			$db_driver = DatabaseLayer::getConnection("MySqlProvider");

			//Se obtendra el id del usuario al que pertenece el vehiculo.
			$query = "SELECT MAX(".$pkfield.") PK FROM ".$table.";";

			//Ejecutamos el query y recogemos el resultado.
			$result = $db_driver -> execute($query);

			//Le sumamos uno a la PK obtenida.
			return $result[0]['PK'] + 1;
		}
	}

?>