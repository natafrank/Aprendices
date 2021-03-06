<!--Archivo que manejará las funciones de envío de correos.-->
<?php

	class Mailer
	{
		/**
		 * Envia Correo.
		 *
		 * Función para enviar correos a los usuarios del sistema dependiendo de su perfil.
		 *
		 * @param string $mail_subject   - Asunto del correo.
		 * @param string $mail_body      - Contenido del correo.
		 * @param int    $mail_type_user - entero utilizado para saber a que perfiles se enviara el correo.
		 * @param int    $id_client      - id para filtrar al cliente al que se enviará el correo.
		 *
		 * @return bool - TRUE si el correo se envio corectamente, FALSE en casoo contrario.
		 */
		public static function sendMail($mail_subject, $mail_body, $mail_type_user, $id_client = 0)
		{
			//Querys para traer la información en base al tipo de usuario.
			$getAdmins    = "SELECT * FROM User WHERE idUserType=1;";
			$getEmployees = "SELECT * FROM User WHERE idUserType=2;";
			//Para los clientes se utiliza el filtro por id para enviar correos a clientes en especifico
			$getClients   = "SELECT * FROM User WHERE idUser=".$id_client.";";

			//Comprobamos que los parámetros estén seteados
			if(isset($mail_subject) && isset($mail_body) && isset($mail_type_user))
			{
				//PHPMailer
				require_once("Controller/PHPMailer/PHPMailerAutoload.php");

				//Creamos el objeto para enviar el correo
				$mail = new PHPMailer();
				$mail -> IsSMTP(); //
				$mail -> SMTPDebug = 0;
				$mail -> SMTPAuth = true;
				$mail -> SMTPSecure = 'tls';
				$mail -> Host = 'mx1.hostinger.mx';
				$mail -> Port = 2525; 

				//Datos del correo
				require_once("mailData.inc");
				$mail -> Username = $username;
				$mail -> Password = $password;           
				$mail -> SetFrom("admin@aprendices.url.ph", "Administrador Aprendices");

				//Agregamos los datos que vienen por parámetros
				$mail -> Subject = $mail_subject;
				$mail -> Body    = $mail_body;

				#Agregamos a los destinatarios en base al tipo de usuario
				#Se usará de la siguiente manera:
				/***************************************
				SISTEMA OCTAL
				Cada digito representa un tipo de usuario,
				en el siguiente orden:
				1.- Admin    = A
				2.- Employee = E
				3.- Client   = C

				AEC = '111' = 7
				AE- = '110' = 6
				A-C = '101' = 5
				A-- = '100' = 4
				-EC = '011' = 3
				-E- = '010' = 2
				--C = '001' = 1
				--- = '000' = 0
				/***************************************/

				//Creamos la conexión.
				require_once("Model/Database Motor/DatabaseLayer.php");
				$db_driver = DatabaseLayer::getConnection("MySqlProvider");

				//Agregamos los destinatarios.
				switch($mail_type_user)
				{
					case 0:
					{
						//No hay destinatarios, nada que hacer.

						break;
					}
					case 1:
					{
						//CLIENTES
						$clients = $db_driver -> execute($getClients);

						//Agreamos los clientes.
						if($clients != null)
						{
							foreach($clients as $client)
							{
								$mail -> AddAddress($client['Email']);
							}
						}

						break;
					}
					case 2:
					{
						//EMPLEADOS
						$employees = $db_driver -> execute($getEmployees);

						//Agregamos los empleados.
						if($employees != null)
						{
							foreach($employees as $employee)
							{
								$mail -> AddAddress($employee['Email']);
							}
						}

						break;
					}
					case 3:
					{
						//CLIENTES
						$clients = $db_driver -> execute($getClients);

						//Agreamos los clientes.
						if($clients != null)
						{
							foreach($clients as $client)
							{
								$mail -> AddAddress($client['Email']);
							}
						}

						//EMPLEADOS
						$employees = $db_driver -> execute($getEmployees);

						//Agregamos los empleados.
						if($employees != null)
						{
							foreach($employees as $employee)
							{
								$mail -> AddAddress($employee['Email']);
							}
						}

						break;
					}
					case 4:
					{
						//ADMINS
						$admins = $db_driver -> execute($getAdmins);

						//Agregamos los empleados.
						if($admins != null)
						{
							foreach($admins as $admin)
							{
								$mail -> AddAddress($admin['Email']);
							}
						}

						break;
					}
					case 5:
					{
						//ADMINS
						$admins = $db_driver -> execute($getAdmins);

						//Agregamos los empleados.
						if($admins != null)
						{
							foreach($admins as $admin)
							{
								$mail -> AddAddress($admin['Email']);
							}
						}

						//CLIENTES
						$clients = $db_driver -> execute($getClients);

						//Agreamos los clientes.
						if($clients != null)
						{
							foreach($clients as $client)
							{
								$mail -> AddAddress($client['Email']);
							}
						}

						break;
					}
					case 6:
					{
						//ADMINS
						$admins = $db_driver -> execute($getAdmins);

						//Agregamos los empleados.
						if($admins != null)
						{
							foreach($admins as $admin)
							{
								$mail -> AddAddress($admin['Email']);
							}
						}

						//EMPLEADOS
						$employees = $db_driver -> execute($getEmployees);

						//Agregamos los empleados.
						if($employees != null)
						{
							foreach($employees as $employee)
							{
								$mail -> AddAddress($employee['Email']);
							}
						}

						break;
					}
					case 7:
					{
						//ADMINS
						$admins = $db_driver -> execute($getAdmins);

						//Agregamos los empleados.
						if($admins != null)
						{
							foreach($admins as $admin)
							{
								$mail -> AddAddress($admin['Email']);
							}
						}

						//EMPLEADOS
						$employees = $db_driver -> execute($getEmployees);

						//Agregamos los empleados.
						if($employees != null)
						{
							foreach($employees as $employee)
							{
								$mail -> AddAddress($employee['Email']);
							}
						}

						//CLIENTES
						$clients = $db_driver -> execute($getClients);

						//Agreamos los clientes.
						if($clients != null)
						{
							foreach($clients as $client)
							{
								$mail -> AddAddress($client['Email']);
							}
						}

						break;
					}
				}

				//Intentamos enviar el correo
				if($mail -> Send())
				{
					//Retornamos true si se envió el correo
					return true;
				}
				else
				{
					//Retornamos false si no se envió el correo
					//echo $mail -> ErrorInfo;
					return false;
				}
			}
			else
			{
				//Retornamos false si no se envió el correo
				echo "Variables no seteadas";
				return false;
			}
		}
	}

?>