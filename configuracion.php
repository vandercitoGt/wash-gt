<?php
  session_start();

//  // Verificar si la sesión está iniciada
  if (!isset($_SESSION['usuario'])) {
//      // Redirigir al usuario a la página de login
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
    <link rel="stylesheet" href="style-configuracion.css">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script>
        function mostrarFormulario(idFormulario) {
            // Ocultar todos los formularios
            var formularios = document.getElementsByClassName("formulario-configuracion");
            for (var i = 0; i < formularios.length; i++) {
                formularios[i].style.display = "none";
            }
            // Mostrar el formulario correspondiente al botón clickeado
            document.getElementById(idFormulario).style.display = "block";
        }
    </script>
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
                        <a class="boton-menu boton-categoria" href="./panel-admin.php"><i class="bi bi-hand-index-thumb-fill"></i>Pendientes</a>
                    </li>
                    <li>
                        <a class="boton-menu" href="./reportes.php"><i class="bi bi-eye"></i>Ver calificaciones</a>
                    </li>
                    <li>
                        <a class="boton-menu boton-categoria boton-cerrar" href="./reportes-ventas.php"><i class="bi bi-file-earmark-arrow-down-fill"></i>Reportes</a>
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
                        <a class="boton-menu boton-categoria boton-cerrar" href="./logout.php"><i class="bi bi-x-octagon"></i>Cerrar sesión</a>
                    </li>
                    <li>
                        <a class="boton-menu boton-carrito active" href="./configuracion.php"><i class="bi bi-cart-fill"></i>Configuración <span class="numerito">0</span></a>
                    </li>
                </ul>
            </nav>
            <footer>
                <p class="texto-footer">© 2023 WASHGT</p>
            </footer>
        </aside>
        <main>
            <h2 class="titulo-principal">Configuración</h2>
            <div class="presentation">
                <!-- Botones para mostrar los formularios -->
                <button onclick="mostrarFormulario('formAgregarEmpleado')" class="boton-agregar">Agregar empleado</button>
                <button onclick="mostrarFormulario('formEliminarEmpleado')" class="boton-eliminar">Eliminar empleado</button>

                <!-- Formulario para agregar empleado -->
                <div id="formAgregarEmpleado" class="formulario-configuracion" style="display: none;">
                    <form action="agregar-usuario.php" method="POST">
                        <label for="nombreEmpleado" class="label-input">Nombre del Empleado:</label>
                        <input type="text" id="nombreEmpleado" name="nombreEmpleado" class="input-configuracion" required>
                        <button type="submit" class="boton-agregar">Agregar</button>
                    </form>
                </div>

                <!-- Formulario para eliminar empleado -->
                <div id="formEliminarEmpleado" class="formulario-configuracion" style="display: none;">
                    <form action="eliminar_empleado.php" method="POST">
                        <label for="selectEmpleadoEliminar" class="label-input">Selecciona un Empleado:</label>
                        <select id="selectEmpleadoEliminar" name="empleadoEliminar" class="input-configuracion">
                            <?php
                            $xml = simplexml_load_file('calificaciones.xml');
                            foreach ($xml->usuario as $usuario) {
                                echo "<option value='" . $usuario['id'] . "'>" . $usuario->nombre . "</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" class="boton-eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este empleado?')">Eliminar</button>
                    </div>
<!-- Aquí puedes agregar otros formularios similares según sea necesario -->
</div>
</main>
</div>
</body>
</html>
