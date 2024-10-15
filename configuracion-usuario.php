<?php
 session_start();

 // Verificar si la sesión está iniciada
 if (!isset($_SESSION['usuario'])) {
     // Redirigir al usuario a la página de login
     header("Location: index.html");
     exit();
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WASH-GT</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="style-configuracion.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        /* Estilo adicional para los formularios */
        .configuracion-form {
            margin-top: 20px;
        }
        .formulario {
            display: none;
            margin-top: 20px;
        }
        .formulario label {
            display: block;
            margin-bottom: 5px;
        }
        .formulario input {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <aside>
        <header>
            <h1 class="logo">WASH-GT</h1>
        </header>
        <nav>
            <ul class="menu">
                <li>
                    <button class="boton-menu boton-categoria" onclick="guardarCita()"><i class="bi bi-plus"></i>Solicitar Servicio</button>
                </li>
                <li>
                    <a class="boton-menu boton-categoria" href="citas-solicitadas.php"><i class="bi bi-calendar-check"></i>Mis citas</a>
                </li>
                <li>
                    <a class="boton-menu boton-categoria" href="./panel-usuario.php"><i class="bi bi-hand-index-thumb-fill"></i>Fila de espera</a>
                </li>
                <li>
                    <a class="boton-menu" href="./calificacion.php"><i class="bi bi-person-plus"></i>Calificar servicio</a>
                </li>
                <li>
                    <?php
                    session_start();
                    if(isset($_SESSION['usuario'])) {
                        $usuario = $_SESSION['usuario'];
                    } else {
                        $usuario = "Nombre de Usuario Predeterminado"; 
                    }
                    ?>
                    <button class="boton-menu boton-categoria">
                        <i class="bi bi-person-circle"></i>
                        <?php echo $usuario; ?>
                    </button>
                </li>
                <li>
                    <a class="boton-menu" href="./shop/tienda.php"><i class="bi bi-cart-plus"></i>Productos</a>
                </li>
                <li>
                    <a class="boton-menu boton-categoria boton-cerrar" href="./logout.php"><i class="bi bi-x-octagon"></i>Cerrar sesión</a>
                </li>
                <li>
                    <a class="boton-menu boton-carrito active" href="./configuracion-usuario.php"><i class="bi bi-cart-fill"></i>Configuración <span class="numerito">0</span></a>
                </li>
            </ul>
        </nav>
        <footer>
            <p class="texto-footer">© 2023 WASHGT</p>
        </footer>
    </aside>
    <main>
        <h2 class="titulo-principal">Configuración</h2>
        <div class="configuracion-form">
            <button onclick="mostrarFormulario('usuarioForm')" class="boton-agregar">Cambiar nombre de usuario</button>
            <button onclick="mostrarFormulario('contrasenaForm')"class="boton-eliminar">Cambiar contraseña</button>

            <form id="usuarioForm" class="formulario" method="POST">
                <label for="nuevoUsuario" class="label-input">Nuevo nombre de usuario:</label>
                <input type="text" id="nuevoUsuario" name="nuevoUsuario" class="input-configuracion"><br>
                <label for="confirmarUsuario" class="label-input">Confirmar nuevo nombre de usuario:</label>
                <input type="text" id="confirmarUsuario" name="confirmarUsuario" class="input-configuracion"><br>
                <input type="submit" value="Actualizar nombre de usuario" class="boton-agregar">
            </form>

            <form id="contrasenaForm" class="formulario" method="POST">
                <label for="nuevaContrasena" class="label-input">Nueva contraseña:</label>
                <input type="password" id="nuevaContrasena" name="nuevaContrasena" class="input-configuracion"><br>
                <label for="confirmarContrasena" class="label-input">Confirmar nueva contraseña:</label>
                <input type="password" id="confirmarContrasena" name="confirmarContrasena" class="input-configuracion"><br>
                <input type="submit" value="Actualizar contraseña" class="boton-agregar">
            </form>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioActual = $_SESSION['usuario'];
            $servername = "localhost";
            $username = "webproject";
            $password = "ba65df4e5";  // Cambia esto si tienes una contraseña configurada
            $dbname = "webproject";
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            if (!empty($_POST['nuevoUsuario']) && !empty($_POST['confirmarUsuario'])) {
                $nuevoUsuario = $_POST['nuevoUsuario'];
                $confirmarUsuario = $_POST['confirmarUsuario'];
                if ($nuevoUsuario === $confirmarUsuario) {
                    $sql = "UPDATE usuarios SET usuario='$nuevoUsuario' WHERE usuario='$usuarioActual'";
                    if ($conn->query($sql) === TRUE) {
                        echo "Nombre de usuario actualizado correctamente";
                        $_SESSION['usuario'] = $nuevoUsuario;
                    } else {
                        echo "Error al actualizar el nombre de usuario: " . $conn->error;
                    }
                } else {
                    echo "Los nombres de usuario no coinciden.";
                }
            }

            if (!empty($_POST['nuevaContrasena']) && !empty($_POST['confirmarContrasena'])) {
                $nuevaContrasena = $_POST['nuevaContrasena'];
                $confirmarContrasena = $_POST['confirmarContrasena'];
                if ($nuevaContrasena === $confirmarContrasena) {
                    $hashedPassword = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
                    $sql = "UPDATE usuarios SET password='$hashedPassword' WHERE usuario='$usuarioActual'";
                    if ($conn->query($sql) === TRUE) {
                        echo "Contraseña actualizada correctamente";
                    } else {
                        echo "Error al actualizar la contraseña: " . $conn->error;
                    }
                } else {
                    echo "Las contraseñas no coinciden.";
                }
            }

            $conn->close();
        }
        ?>

    </main>
</div>

<script>
    function mostrarFormulario(formularioId) {
        var formularios = document.querySelectorAll('.formulario');
        formularios.forEach(function(formulario) {
            formulario.style.display = 'none';
        });
        document.getElementById(formularioId).style.display = 'block';
    }
</script>
</body>
</html>
