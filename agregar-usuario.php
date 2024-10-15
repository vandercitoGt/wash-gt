<?php
// Ruta del archivo XML
$xmlFile = 'calificaciones.xml';

// Obtener los datos enviados por POST
$nombreEmpleado = isset($_POST['nombreEmpleado']) ? $_POST['nombreEmpleado'] : null;

if ($nombreEmpleado !== null) {
    // Cargar el XML
    $xml = simplexml_load_file($xmlFile);

    // Generar un ID único e irrepetible
    $nuevoID = uniqid();

    // Crear un nuevo elemento de usuario
    $nuevoUsuario = $xml->addChild('usuario');
    $nuevoUsuario->addAttribute('id', $nuevoID);
    $nuevoUsuario->addChild('nombre', $nombreEmpleado);

    // Crear una nueva hoja de calificación para el usuario con calificación predeterminada de cero
    $nuevaCalificacion = $nuevoUsuario->addChild('calificacion', '0');

    // Guardar los cambios en el XML
    $xml->asXML($xmlFile);

    // Mostrar un cuadro de diálogo con el mensaje
    echo "<script>alert('Usuario agregado correctamente con ID: $nuevoID');</script>";
} else {
    // Si no se recibió el nombre del empleado, devolver un mensaje de error
    echo "<script>alert('Error: No se recibió el nombre del empleado.');</script>";
}

// Redireccionar a panel-usuario.html después de 2 segundos
echo "<script>setTimeout(function() { window.location.href = 'panel-admin.php'; }, 1000);</script>";
?>
