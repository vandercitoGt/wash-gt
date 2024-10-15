<?php
// Cargar el contenido del archivo XML
$citasXML = simplexml_load_file("citas.xml");

// Función para calcular el tiempo de espera de una cita
function calcularTiempoEspera($tipoCarro, $tipoServicio) {
    if ($tipoServicio == "Completo") {
        if ($tipoCarro == "Sedan" || $tipoCarro == "Hatchback") {
            return 40; // Minutos
        } elseif ($tipoCarro == "Camioneta" || $tipoCarro == "SUV") {
            return 50; // Minutos
        }
    } else {
        if ($tipoCarro == "Sedan" || $tipoCarro == "Hatchback") {
            return 20; // Minutos
        } elseif ($tipoCarro == "Camioneta" || $tipoCarro == "SUV") {
            return 25; // Minutos
        }
    }
    return 0; // Valor predeterminado en caso de que no se pueda calcular el tiempo de espera
}

// Calcular el tiempo total de espera sumando los tiempos de espera de todas las citas
$tiempoTotalEspera = 0;
foreach ($citasXML->cita as $cita) {
    $tipoCarro = (string)$cita->tipoCarro;
    $tipoServicio = (string)$cita->tipoServicio;
    $tiempoTotalEspera += calcularTiempoEspera($tipoCarro, $tipoServicio);
}

// Enviar el tiempo total de espera al cliente
echo $tiempoTotalEspera;
?>
