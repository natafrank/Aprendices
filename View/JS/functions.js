function handleFileSelect()
{               
    if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
        alert('El API para manejo de archivos no es soportado por este navegador.');
        return;
    }   

   input = document.getElementById('file_loader');
   if (!input) {
      alert("No se encontró el input para cargar el archivo.");
   }
   else if (!input.files) {
      alert("El navegador no soporta la propiedad files de los input tipo file.");
   }
   else if (!input.files[0]) {
      alert("No se seleccionó ningun archivo");               
   }
   else {
      file = input.files[0];
      fr = new FileReader();
      fr.onload = receivedText;
      fr.readAsText(file);
   }
}

function receivedText() {   
   document.getElementById('file_text').value = fr.result;
}

function changeFilterUser(){
	// Obtener la referencia a la lista 
	var select = document.getElementById('filter_select');
	// Obtener el índice de la opción que se ha seleccionado 
	var index = select.selectedIndex; 
	// Con el índice y el array "options", obtener la opción seleccionada 
	var selectedOption = select.options[index];
	// Obtener el valor y el texto de la opción seleccionada 
	var value = selectedOption.value;
	
	//Obtener el input
	var input = document.getElementById('filter_condition');
	
	//Borrar contenido actual del input
	input.value = "";

	//Cambiar el tipo de input de acuerdo al campo seleccionado
	switch(value){
		case "idUser" : 
				input.type = "number";
				input.min = "1";
				break;

		case "User" : 
				input.type = "text";
				break;
						
		case "Login" : 
				input.type = "text";
				break;
						
		case "Password" : 
				input.type = "password";
				break;
						
		case "Email" : 
				input.type = "mail";
				break;
						
		case "Tel" : 
				input.type = "tel";
				break;
						
		case "idUserType" : 
				input.type = "number";
				input.min = "1";
				break;
	}
}

function changeFilterChecklist(){
	// Obtener la referencia a la lista 
	var select = document.getElementById('filter_select');
	// Obtener el índice de la opción que se ha seleccionado 
	var index = select.selectedIndex; 
	// Con el índice y el array "options", obtener la opción seleccionada 
	var selectedOption = select.options[index];
	// Obtener el valor y el texto de la opción seleccionada 
	var value = selectedOption.value;
	
	//Obtener el input
	var input = document.getElementById('filter_condition');
	
	//Borrar contenido actual del input
	input.value = "";

	//Cambiar el tipo de input de acuerdo al campo seleccionado
	switch(value){
		case "idChecklist" : 
				input.type = "number";
				input.min = "1";
				break;

		case "idVehicle" : 
				input.type = "number";
				input.min = "1";
				break;
						
		case "idVehicleStatus" : 
				input.type = "number";
				input.min = "1";
				break;
						
		case "Date" : 
				input.type = "date";
				break;
						
		case "InOut" : 
				input.type = "number";
				input.min = "0";
				input.max= "1"
				break;
	}
}

function changeFilterDamageDetail(){
	// Obtener la referencia a la lista 
	var select = document.getElementById('filter_select');
	// Obtener el índice de la opción que se ha seleccionado 
	var index = select.selectedIndex; 
	// Con el índice y el array "options", obtener la opción seleccionada 
	var selectedOption = select.options[index];
	// Obtener el valor y el texto de la opción seleccionada 
	var value = selectedOption.value;
	
	//Obtener el input
	var input = document.getElementById('filter_condition');
	
	//Borrar contenido actual del input
	input.value = "";

	//Cambiar el tipo de input de acuerdo al campo seleccionado
	switch(value){
		case "idDamageDetail" : 
				input.type = "number";
				input.min = "1";
				break;

		case "idChecklist" : 
				input.type = "number";
				input.min = "1";
				break;
						
		case "idVehiclePart" : 
				input.type = "number";
				input.min = "1";
				break;
						
		case "idDamage" : 
				input.type = "number";
				input.min = "1";
				break;
	}
}

function changeFilterDamage(){
	// Obtener la referencia a la lista 
	var select = document.getElementById('filter_select');
	// Obtener el índice de la opción que se ha seleccionado 
	var index = select.selectedIndex; 
	// Con el índice y el array "options", obtener la opción seleccionada 
	var selectedOption = select.options[index];
	// Obtener el valor y el texto de la opción seleccionada 
	var value = selectedOption.value;
	
	//Obtener el input
	var input = document.getElementById('filter_condition');
	
	//Borrar contenido actual del input
	input.value = "";

	//Cambiar el tipo de input de acuerdo al campo seleccionado
	switch(value){
		case "idDamage" : 
				input.type = "number";
				input.min = "1";
				break;

		case "Damage" : 
				input.type = "text";
				break;
	}
}

function changeFilterVehiclePart(){
	// Obtener la referencia a la lista 
	var select = document.getElementById('filter_select');
	// Obtener el índice de la opción que se ha seleccionado 
	var index = select.selectedIndex; 
	// Con el índice y el array "options", obtener la opción seleccionada 
	var selectedOption = select.options[index];
	// Obtener el valor y el texto de la opción seleccionada 
	var value = selectedOption.value;
	
	//Obtener el input
	var input = document.getElementById('filter_condition');
	
	//Borrar contenido actual del input
	input.value = "";

	//Cambiar el tipo de input de acuerdo al campo seleccionado
	switch(value){
		case "idVehiclePart" : 
				input.type = "number";
				input.min = "1";
				break;

		case "VehiclePart" : 
				input.type = "text";
				break;
	}
}