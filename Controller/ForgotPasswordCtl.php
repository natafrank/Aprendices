<?php

include("Controller/StandardCtl.php");

class ForgotPasswordCtl extends StandardCtl{

	public function run()
	{
		
		//Comprobamos que el $_POST no esté vacío.
		if(empty($_POST))
		{
			//Cargamos el formulario
			$view = file_get_contents("View/ForgotPassword.html");
			$header = file_get_contents("View/header.html");
			$footer = file_get_contents("View/footer.html");
			
			//Creamos el diccionario
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
		else
		{
			//Limpiamos los datos.
			$userEmail = $this->cleanEmail($_POST['userEmail']);

			$userData = $this -> checkEmail($userEmail);

			if($userData != null)
			{
				//Enviamos el correo con la contraseña.
				require_once("Controller/mail.php");

				//Mandamos como parámetro el asunto, cuerpo y tipo de destinatario*.
				$subject = "Solicitud de contraseña olvidada";
				$body = "Se hizo una solicitud de contraseña olvidada".
					"\n\nSi usted no fue, ignore este correo".
					"\n\nDe lo contrario. Sus datos son:".
					"\n\nNombre: ".$userData['User'].
					"\nLogin: ".$userData['Login'].
					"\nContraseña: ".$userData['Password'].
					"\n\n\nAtentamente: Aprendicies.pe.hu";

				//Manadamos el correo solo a administradores y al cliente que se agregó - 5.
				if(Mailer::sendMail($subject, $body, 5, $userData['idUser']))
				{
					//echo "<br>Correo enviado con éxito.";
				}
				else
				{
					$error = "Error al enviar el correo.";
					$this -> showErrorView($error);
				}
			
				//$error = "Revisa tu bandeja de entrada para recuperar tu contraseña.".
				//	"\nTambien puedes revisar tu bandeja de Spam en caso de no encontrarlo";
				//$this -> showErrorView($error);
			
			}
			else
			{
				$error = "La cuenta de correo especificada no existe.";
				$this -> showErrorView($error);
			}
		}


		
	}
}
?>
