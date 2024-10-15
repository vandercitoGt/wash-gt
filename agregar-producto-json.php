<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : "Nombre de Usuario Predeterminado";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $categoria = strtolower($_POST['categoria']);
    $precio = $_POST['precio'];
    $imagen = $_FILES['imagen'];

    $uploadDir = normalizarRuta('./img/' . $categoria . '/');
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $imagenTmpPath = $imagen['tmp_name'];
    $imagenName = time() . '_' . basename($imagen['name']);
    $destinoImagen = normalizarRuta($uploadDir . $imagenName);

    if (move_uploaded_file($imagenTmpPath, $destinoImagen)) {
        $jsonFilePath = normalizarRuta('./shop/productos.json');
        if (!file_exists($jsonFilePath)) {
            file_put_contents($jsonFilePath, json_encode([]));
        }
        $jsonData = file_get_contents($jsonFilePath);
        $productos = json_decode($jsonData, true);

        $id = generarIDUnico($categoria, $productos);

        $nuevoProducto = [
            'id' => $id,
            'titulo' => $titulo,
            'imagen' => $destinoImagen,
            'categoria' => [
                'nombre' => ucfirst($categoria),
                'id' => $categoria
            ],
            'precio' => (int)$precio
        ];

        $productos[] = $nuevoProducto;

        file_put_contents($jsonFilePath, json_encode($productos));

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al mover el archivo de imagen']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}

function generarIDUnico($categoria, $productos) {
    $categoriaSinS = rtrim($categoria, 's');
    $contador = 1;
    $id = $categoriaSinS . '-' . str_pad($contador, 2, '0', STR_PAD_LEFT);

    while (existeProducto($id, $productos)) {
        $contador++;
        $id = $categoriaSinS . '-' . str_pad($contador, 2, '0', STR_PAD_LEFT);
    }

    return $id;
}

function existeProducto($id, $productos) {
    foreach ($productos as $producto) {
        if ($producto['id'] === $id) {
            return true;
        }
    }
    return false;
}

function normalizarRuta($ruta) {
    return str_replace('\\', '/', $ruta);
}
?>
