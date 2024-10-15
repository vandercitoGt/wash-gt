<?php
 session_start();
 if (!isset($_SESSION['usuario'])) {
     header("Location: index.html");
     exit();
 }
 $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : "Nombre de Usuario Predeterminado";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $productoId = $data['id'];

    $jsonFilePath = './shop/productos.json';
    if (!file_exists($jsonFilePath)) {
        echo json_encode(['status' => 'error', 'message' => 'Archivo JSON no encontrado']);
        exit();
    }

    $jsonData = file_get_contents($jsonFilePath);
    $productos = json_decode($jsonData, true);

    $productoIndex = array_search($productoId, array_column($productos, 'id'));
    if ($productoIndex !== false) {
        $producto = $productos[$productoIndex];
        unset($productos[$productoIndex]);
        $productos = array_values($productos);

        file_put_contents($jsonFilePath, json_encode($productos));
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Producto no encontrado']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}
?>