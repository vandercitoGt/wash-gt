<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipoCarro = $_POST['tipoCarro'];
    $tipoServicio = $_POST['tipoServicio'];

    // Ruta al archivo XML
    $xmlFile = 'citas-admin.xml';

    // Crear un nuevo objeto SimpleXMLElement si el archivo no existe
    if (!file_exists($xmlFile)) {
        $xml = new SimpleXMLElement('<citas></citas>');
    } else {
        // Cargar el archivo XML existente
        $xml = simplexml_load_file($xmlFile);
    }

    // Agregar una nueva cita al XML
    $cita = $xml->addChild('cita');
    $cita->addChild('id', uniqid()); // Generar un ID único para la cita
    $cita->addChild('tipoCarro', $tipoCarro);
    $cita->addChild('tipoServicio', $tipoServicio);
    $cita->addChild('horaCita', date('Y-m-d H:i:s')); // Fecha y hora actuales
    $cita->addChild('nombreCliente', 'Nombre del cliente'); // Puedes cambiar esto según tus necesidades

    // Guardar el XML en el archivo
    $xml->asXML($xmlFile);

    // Redirigir de vuelta a la página principal
    header('Location: panel-admin.php');
    exit;
}
?>
