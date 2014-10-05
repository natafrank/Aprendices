<?php

	echo '/***** USUARIO MODIFICADO *****/',
		" <br/>ID     : " ,$this-> model -> getIdUser(),
		" <br/>Nombre : " ,$this-> model -> getName(),
		" <br/>Login  : " ,$this-> model -> getLogin(),
		" <br/>Pass   : " ,$this-> model -> getPass(),
		" <br/>Tipo   : " ,$this-> model -> getIdUserType(),
		" <br/>Email  : " ,$this-> model -> getEmail(),
		" <br/>Tel    : " ,$this-> model -> getTel();

?>
