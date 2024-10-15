<?php
// Obtener el ID de la cita a finalizar y la hora de la cita
if(isset($_GET['id']) && isset($_GET['horaCita'])){
    $id = $_GET['id'];
    $horaCita = $_GET['horaCita'];
} else {
    die("ID de cita o hora de cita no especificado.");
}

// Obtener el nombre del usuario del cuerpo de la solicitud POST
$usuario = $_POST['usuarioHidden'];

// Cargar el archivo XML de citas pendientes
$xml = simplexml_load_file('citas.xml');

// Buscar la cita por su ID y guardar sus detalles antes de eliminarla
$foundCita = null;
foreach ($xml->cita as $cita) {
    if ($cita->id == $id) {
        // Guardar los detalles de la cita
        $foundCita = $cita;
        break;
    }
}

if(!$foundCita){
    die("La cita no se encontró en el archivo XML.");
}

// Obtener la fecha y hora actual del sistema
date_default_timezone_set('America/Mexico_City'); // Establecer la zona horaria a la de México
$fechaActual = date('Y-m-d H:i:s'); // Formato de fecha y hora: Año-Mes-Día Hora:Minuto:Segundo

// Obtener el nombre del cliente de la cita encontrada
$nombreCliente = (string)$foundCita->nombreCliente;

// Guardar el archivo XML de citas pendientes actualizado
unset($foundCita[0]); // Eliminar la cita del XML de citas pendientes
$xml->asXML('citas.xml');

// Obtener el archivo XML de citas completadas
$completedXML = simplexml_load_file('citas-completadas.xml');

// Crear un nuevo nodo cita para la cita completada
$newCompletedCita = $completedXML->addChild('cita');
$newCompletedCita->addChild('id', $id);
$newCompletedCita->addChild('tipoCarro', (string)$foundCita->tipoCarro);
$newCompletedCita->addChild('tipoServicio', (string)$foundCita->tipoServicio);
$newCompletedCita->addChild('horaCita', $horaCita);
$newCompletedCita->addChild('usuario', $usuario); // Agregar el nombre de usuario
$newCompletedCita->addChild('fecha', $fechaActual);

// Guardar el archivo XML de citas completadas actualizado
$completedXML->asXML('citas-completadas.xml');

// Respuesta para confirmar que la cita se ha finalizado y guardado correctamente
echo 'Cita finalizada y guardada en el archivo XML de citas completadas.';
?>
