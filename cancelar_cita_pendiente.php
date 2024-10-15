<?php
// Obtener el ID de la cita a eliminar
if(isset($_GET['id'])){
    $id = $_GET['id'];
} else {
    die("ID de cita no especificado.");
}

// Cargar el archivo XML
$xml = simplexml_load_file('citas.xml');

// Buscar la cita por su ID y eliminarla
$found = false;
foreach ($xml->cita as $cita) {
    if ($cita->id == $id) {
        // Eliminar la cita del XML
        unset($cita[0]);
        $found = true;
        break;
    }
}

if(!$found){
    die("La cita no se encontró en el archivo XML.");
}

// Guardar el archivo XML actualizado
$xml->asXML('citas.xml');

// Redirigir de vuelta a la página de citas pendientes después de cancelar la cita
header("Location: citas-solicitadas.php");
?>
