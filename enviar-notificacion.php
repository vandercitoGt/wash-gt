<?php
// Define la clave del servidor de Firebase Cloud Messaging (FCM)
define('FCM_SERVER_KEY', 'AAAApD_SCG8:APA91bF5XAni5tc3ApmhReurkPG44lqXXhVwu2nyFJ0JT1lpvAxrZX-HZJlg5qPfwctmJu9Gqsbxxx67Y54krbxKV_GEUs3LkVtY9pGiOpPs-gsm3Oa5Gs4aiyundew8tnXgyn3urTHw');

// Manejar la solicitud de la nueva cita
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos de la cita del cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"));

    // Aquí puedes agregar tu lógica para guardar la cita en la base de datos o hacer lo que sea necesario
    
    // Enviar una notificación al panel de administrador
    enviarNotificacion($data);

    // Responder con un mensaje de éxito
    echo "Cita solicitada con éxito!";
}

// Función para enviar la notificación al panel de administrador
function enviarNotificacion($cita) {
    // Crear un objeto con los datos de la notificación
    $notification = array(
        'title' => 'Nueva cita solicitada',
        'body' => 'Tipo de carro: ' . $cita->tipoCarro . ', Tipo de servicio: ' . $cita->tipoServicio . ', Hora de la cita: ' . $cita->horaCita,
        'icon' => 'https://example.com/icon.png', // URL de la imagen para el icono de la notificación
        'badge' => 'https://example.com/badge.png' // URL de la imagen para el badge de la notificación (opcional)
    );

    // Configurar el array de datos para enviar la notificación
    $fields = array(
        'to' => '/topics/admin', // Puedes enviar la notificación a un tema específico, un dispositivo único, o a varios dispositivos
        'notification' => $notification
    );

    // Configurar las opciones de solicitud
    $opciones = array(
        'http' => array(
            'header' => "Content-Type: application/json\r\n" .
                        "Authorization: key=" . FCM_SERVER_KEY . "\r\n",
            'method' => 'POST',
            'content' => json_encode($fields),
        ),
    );

    // Crear un contexto para la solicitud
    $contexto = stream_context_create($opciones);

    // Realizar la solicitud a la API de FCM y obtener la respuesta
    $response = file_get_contents('https://fcm.googleapis.com/fcm/send', false, $contexto);

    // Puedes agregar lógica adicional aquí para manejar la respuesta de FCM, como registrar errores, etc.
}
?>
