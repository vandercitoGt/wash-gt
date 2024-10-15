<?php
// Ruta del archivo XML
$xmlFile = 'calificaciones.xml';

// Obtener el ID del empleado a eliminar
$idEmpleadoEliminar = isset($_POST['empleadoEliminar']) ? $_POST['empleadoEliminar'] : null;

if ($idEmpleadoEliminar !== null) {
    // Cargar el XML
    $xml = simplexml_load_file($xmlFile);

    // Buscar y eliminar el usuario con el ID especificado
    $index = 0;
    foreach ($xml->usuario as $usuario) {
        if ((string)$usuario['id'] === $idEmpleadoEliminar) {
            unset($xml->usuario[$index]);
            break;
        }
        $index++;
    }

    // Guardar los cambios en el XML
    $xml->asXML($xmlFile);

    // Devolver una respuesta
    echo "<script>alert('Usuario eliminado correctamente');</script>";
} else {
    // Si no se recibió el ID del empleado, devolver un mensaje de error
    echo "<script>alert('No se recibio correctamente el id del empleado);</script>";
}

// Redireccionar a panel-usuario.html después de 2 segundos
echo "<script>setTimeout(function() { window.location.href = 'panel-admin.php'; }, 1000);</script>";
?>

