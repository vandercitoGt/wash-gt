<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "washgt";

// Obtener los datos del formulario
$nombreCliente = $_POST['nombreCliente'];
$tipoCarro = $_POST['tipoCarro'];
$colorCarro = $_POST['colorCarro'];
$detallesEsteticos = $_POST['detallesEsteticos'];

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Crear la consulta SQL para insertar el nuevo servicio
$sql = "INSERT INTO servicios (nombre_cliente, tipo_carro, color, detalles) VALUES ('$nombreCliente', '$tipoCarro', '$colorCarro', '$detallesEsteticos')";

// Ejecutar la consulta y verificar si fue exitosa
if ($conn->query($sql) === TRUE) {
    echo "Nuevo servicio guardado exitosamente";
} else {
    echo "Error al guardar el servicio: " . $conn->error;
}

// Cerrar la conexión
$conn->close();

// Redireccionar a panel-usuario.html después de 2 segundos
echo "<script>setTimeout(function() { window.location.href = 'panel-admin.html'; }, 2000);</script>";
?>
