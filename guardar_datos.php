<?php
// Ruta del archivo XML
$xmlFile = 'calificaciones.xml';

// Obtener los datos enviados por POST
$idEmpleado = isset($_POST['empleado']) ? $_POST['empleado'] : null;
$satisfaccion = isset($_POST['satisfaccion']) ? intval($_POST['satisfaccion']) : null;

if ($idEmpleado !== null && $satisfaccion !== null) {
    // Cargar el XML
    $xml = simplexml_load_file($xmlFile);

    // Buscar el usuario por ID
    $usuario = null;
    foreach ($xml->usuario as $user) {
        if ((string)$user['id'] === $idEmpleado) {
            $usuario = $user;
            break;
        }
    }

    // Verificar si se encontró el usuario
    if ($usuario !== null) {
        // Obtener la calificación existente y el número de calificaciones
        $calificacionExistente = isset($usuario->calificacion) ? intval($usuario->calificacion) : 0;
        $numCalificaciones = isset($usuario->numCalificaciones) ? intval($usuario->numCalificaciones) : 0;

        // Calcular el nuevo promedio de calificaciones
        $nuevoPromedio = ($calificacionExistente * $numCalificaciones + $satisfaccion) / ($numCalificaciones + 1);

        // Actualizar la calificación del usuario
        $usuario->calificacion = $nuevoPromedio;
        $usuario->numCalificaciones = $numCalificaciones + 1;

        // Guardar los cambios en el XML
        $xml->asXML($xmlFile);

        // Devolver una respuesta
        echo "<script>alert('Gracias por su calificacion');</script>";
    } else {
        // Si no se encontró el usuario, devolver un mensaje de error
        echo "<script>alert('No se encontro el usuario con el id especificado');</script>";
    }
} else {
    // Si no se recibió la calificación o el ID del empleado, devolver un mensaje de error
    echo "<script>alert('Error, falta informacion necesaria');</script>";
}

// Redireccionar a panel-usuario.html después de 2 segundos
echo "<script>setTimeout(function() { window.location.href = 'panel-usuario.php'; }, 2000);</script>";
?>
