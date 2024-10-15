<?php
// Establecer la zona horaria a la del centro de México
date_default_timezone_set('America/Mexico_City');

// Obtener los datos de la cita del cuerpo de la solicitud POST
$citaData = json_decode(file_get_contents('php://input'), true);

// Obtener la fecha y hora actual
$fechaActual = date('Y-m-d H:i:s');

// Obtener el nombre de usuario y número de teléfono del cuerpo de la solicitud POST
$usuario = $citaData['usuarioHidden'];
$telefono = $citaData['telefono'];

// Generar un ID único para la cita
$id = uniqid();

// Crear un nuevo objeto SimpleXMLElement para la cita
$newCita = new SimpleXMLElement('<cita></cita>');
$newCita->addChild('id', $id); // Agregar el ID
$newCita->addChild('tipoCarro', $citaData['tipoCarro']);
$newCita->addChild('tipoServicio', $citaData['tipoServicio']);
$newCita->addChild('horaCita', $citaData['horaCita']);
$newCita->addChild('usuario', $usuario); // Agregar el nombre de usuario
$newCita->addChild('telefono', $telefono); // Agregar el número de teléfono
$newCita->addChild('fecha', $fechaActual); // Agregar la fecha actual

// Ruta al archivo XML
$xmlFile = "citas.xml";

// Verificar si el archivo XML existe
if(file_exists($xmlFile)) {
    // Cargar el archivo XML
    $xml = simplexml_load_file($xmlFile);
    
    // Agregar la nueva cita al XML
    $newCitaNode = $xml->addChild('cita');
    $newCitaNode->addChild('id', $id);
    $newCitaNode->addChild('tipoCarro', $citaData['tipoCarro']);
    $newCitaNode->addChild('tipoServicio', $citaData['tipoServicio']);
    $newCitaNode->addChild('horaCita', $citaData['horaCita']);
    $newCitaNode->addChild('usuario', $usuario);
    $newCitaNode->addChild('telefono', $telefono); // Agregar el número de teléfono
    $newCitaNode->addChild('fecha', $fechaActual); // Agregar la fecha actual
    
    // Guardar los cambios en el archivo XML
    $xml->asXML($xmlFile);
    
    // Devolver una respuesta exitosa
    http_response_code(200);
    echo "Cita agendada correctamente";
} else {
    // Devolver un error si el archivo XML no existe
    http_response_code(404);
    echo "No se encontró el archivo XML de citas";
}
?>
