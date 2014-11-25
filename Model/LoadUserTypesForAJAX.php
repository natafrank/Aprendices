<?php
	//Importamos la capa de la base de datos.
	require_once("Model/Database Motor/DatabaseLayer.php");

	//Creamos la conexión.
	$db_driver = DatabaseLayer::getConnection("MySqlProvider");

	//Hago un query para obtener los tipos de usuario
	$query = 'SELECT * FROM UserType';
	$result = $db_driver -> execute($query);

	//Proceso el resultado
	while($row = $result->fetch_assoc())
		$usertypes[] = $row;

	//Muestro el resultado
	echo json_encode($usertypes);
?>