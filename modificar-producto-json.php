<?php
 session_start();
 if (!isset($_SESSION['usuario'])) {
     header("Location: index.html");
     exit();
 }
 $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : "Nombre de Usuario Predeterminado";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productoId = $_POST['productoId'];
    $titulo = $_POST['tituloModificar'];
    $categoria = $_POST['categoriaModificar'];
    $precio = $_POST['precioModificar'];
    $imagen = isset($_FILES['imagenModificar']) ? $_FILES['imagenModificar'] : null;

    $jsonFilePath = './shop/productos.json';
    if (!file_exists($jsonFilePath)) {
        echo json_encode(['status' => 'error', 'message' => 'Archivo JSON no encontrado']);
        exit();
    }

    $jsonData = file_get_contents($jsonFilePath);
    $productos = json_decode($jsonData, true);

    $productoIndex = array_search($productoId, array_column($productos, 'id'));
    if ($productoIndex !== false) {
        $productos[$productoIndex]['titulo'] = $titulo;
        $productos[$productoIndex]['categoria']['nombre'] = ucfirst($categoria);
        $productos[$productoIndex]['categoria']['id'] = $categoria;
        $productos[$productoIndex]['precio'] = $precio;

        if ($imagen && $imagen['tmp_name']) {
            $uploadDir = './img/' . strtolower($categoria) . '/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imagenTmpPath = $imagen['tmp_name'];
            $imagenName = time() . '_' . basename($imagen['name']);
            $destinoImagen = $uploadDir . $imagenName;

            if (move_uploaded_file($imagenTmpPath, $destinoImagen)) {
                $productos[$productoIndex]['imagen'] = $destinoImagen;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al mover el archivo de imagen']);
                exit();
            }
        }

        file_put_contents($jsonFilePath, json_encode($productos));
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Producto no encontrado']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}
?>