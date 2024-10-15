<?php
// Obtener el ID de la cita a eliminar
if(isset($_GET['id'])){
    $id = $_GET['id'];
} else {
    die("ID de cita no especificado.");
}

// Cargar el archivo XML
$xml = simplexml_load_file('citas.xml');

// Buscar la cita por su ID y guardar sus detalles antes de eliminarla
$tipoCarro = "";
$tipoServicio = "";
$horaCita = "";
$usuario = "";
$fecha = "";

$found = false;
foreach ($xml->cita as $cita) {
    if ($cita->id == $id) {
        // Guardar los detalles de la cita
        $tipoCarro = (string)$cita->tipoCarro;
        $tipoServicio = (string)$cita->tipoServicio;
        $horaCita = (string)$cita->horaCita;
        $usuario = (string)$cita->usuario;
        $fecha = (string)$cita->fecha;

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

// Obtener el archivo XML para citas realizadas
$completedXML = simplexml_load_file('citas-completadas.xml');

// Crear un nuevo objeto SimpleXMLElement para la nueva cita
$newCompletedCita = $completedXML->addChild('cita');
$newCompletedCita->addChild('id', $id);
$newCompletedCita->addChild('tipoCarro', $tipoCarro);
$newCompletedCita->addChild('tipoServicio', $tipoServicio);
$newCompletedCita->addChild('horaCita', $horaCita);
$newCompletedCita->addChild('usuario', $usuario);
$newCompletedCita->addChild('fecha', $fecha);
// Guardar el archivo XML actualizado
$completedXML->asXML('citas-completadas.xml');

// Respuesta para confirmar que la cita se ha eliminado y guardado correctamente
echo 'Cita eliminada del archivo XML de citas pendientes y guardada en el archivo XML de citas completadas.';
?>


