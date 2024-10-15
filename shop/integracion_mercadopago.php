<?php
require_once './vendor/autoload.php';

use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

// Setea tu token de acceso de MercadoPago
MercadoPagoConfig::setAccessToken("TEST-4037026436596388-050711-6469b583a4c86380c68db957eee4b10e-1803150950");

// Crea una instancia del cliente de preferencias
$client = new PreferenceClient();

try {
    // Crea el array de items con los detalles de los productos en el carrito
    $items = array(
        array(
            "id" => "1234567890",
            "title" => "Producto 1",
            "description" => "Descripción del Producto 1",
            "currency_id" => "MXN",
            "quantity" => 1,
            "unit_price" => 10.00
        ),
        array(
            "id" => "9012345678",
            "title" => "Producto 2",
            "description" => "Descripción del Producto 2",
            "currency_id" => "MXN",
            "quantity" => 2,
            "unit_price" => 20.00
        )
    );

    // Detalles del pagador (usuario)
    $payer = array(
        "name" => "Nombre del Usuario",
        "surname" => "Apellido del Usuario",
        "email" => "usuario@example.com",
    );

    // Crea la preferencia de pago en MercadoPago
    $preference = $client->create(createPreferenceRequest($items, $payer));

    // Si se creó la preferencia con éxito, puedes obtener la URL de checkout pro con $preference->init_point
    // o simplemente retornar el ID de la preferencia
    echo $preference->id;

} catch (MPApiException $error) {
    // Maneja las excepciones de la API de MercadoPago aquí
    echo "Error al crear la preferencia: " . $error->getMessage();
}

// Función para crear el array de la solicitud de preferencia
function createPreferenceRequest($items, $payer): array
{
    $request = [
        "items" => $items,
        "payer" => $payer,
        // Otros campos de la preferencia como back_urls, statement_descriptor, etc.
    ];

    return $request;
}
?>
