<?php
session_start(); 

if(isset($_POST['registro'])) {
    $nombre_completo = $_POST['nombre_completo'];
    $correo = $_POST['correo'];
    $usuario_new = $_POST['usuario'];
    $password_new = $_POST['password'];
    $acepto_terminos = isset($_POST['acepto_terminos']) ? 1 : 0; 

    $conexion = mysqli_connect("localhost", "ejasyjrp_washgt", "Uv}M(pUat2HJ", "ejasyjrp_washgt");

    // Verificar la conexión
    if (!$conexion) {
        echo "<script>console.error('Error en la conexión: " . mysqli_connect_error() . "');</script>";
        die("Error en la conexión: " . mysqli_connect_error());
    }

    // Verificar si el usuario ya existe
    $consulta_existencia = "SELECT * FROM usuarios WHERE usuario='$usuario_new'";
    $resultado_existencia = mysqli_query($conexion, $consulta_existencia);

    if (!$resultado_existencia) {
        echo "<script>console.error('Error en la consulta de existencia: " . mysqli_error($conexion) . "');</script>";
        die("Error en la consulta de existencia: " . mysqli_error($conexion));
    }

    if (mysqli_num_rows($resultado_existencia) > 0) {
        // El usuario ya existe, mostrar un mensaje y redirigir a index.html
        echo "<script>alert('El usuario ya existe'); window.location.href = 'index.html'; </script>";
    } else {
        // Insertar nuevo usuario
        $consulta_insertar = "INSERT INTO usuarios (nombre_completo, correo, usuario, password, terminos) VALUES ('$nombre_completo', '$correo', '$usuario_new', '$password_new', '$acepto_terminos')";
        $resultado_insertar = mysqli_query($conexion, $consulta_insertar);

        if ($resultado_insertar) {
            // Registro exitoso, mostrar un mensaje y redirigir a index.html
            echo "<script>alert('Registro exitoso'); window.location.href = 'index.html'; </script>";
        } else {
            // Error al registrar, mostrar un mensaje de error
            echo "<script>console.error('Error al registrar: " . mysqli_error($conexion) . "');</script>";
            echo "<script>alert('Error al registrar: " . mysqli_error($conexion) . "'); window.location.href = 'index.html'; </script>";
        }
    }

    // Cerrar la conexión
    mysqli_close($conexion);
} else {
    // Si se intenta acceder a este script sin enviar el formulario, redirigir a index.html
    header("Location: index.html");
    exit();
}
?>