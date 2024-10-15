<?php
$usuario = $_POST['usuario'];
$password = $_POST['password'];

$conexion = mysqli_connect("localhost", "ejasyjrp_washgt", "Uv}M(pUat2HJ", "ejasyjrp_washgt");

// Verificar la conexión
if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

$consulta = "SELECT * FROM usuarios WHERE usuario='$usuario' AND password='$password'";

// Realizar la consulta y verificar posibles errores
$resultado = mysqli_query($conexion, $consulta);
if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

$filas = mysqli_num_rows($resultado);

if ($filas) {
    session_start(); 
    $_SESSION['usuario'] = $usuario;
    
    // Verificar si el usuario es "admin" y la contraseña es "123"
    if ($usuario === "admin" && $password === "1234") {
        // Redireccionar a panel-admin.php
        header("Location: panel-admin.php");
        exit();
    } else {
        // Redireccionar a panel-usuario.php para cualquier otro usuario
        header("Location: panel-usuario.php");
        exit();
    }
} else {
    ?>
    <script>
        setTimeout(function(){
            alert("Usuario o clave incorrectos");
            window.location.href = "index.html";
        }, 100);
    </script>
    <?php
}

// Liberar el resultado solo si es una consulta SELECT
if (is_object($resultado)) {
    mysqli_free_result($resultado);
}

// Cerrar la conexión
mysqli_close($conexion);
?>
