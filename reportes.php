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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Usuarios</title>
    <link rel="stylesheet" href="style-calificaciones.css">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                        <a class="boton-menu active" href="./reportes.php"><i class="bi bi-eye"></i>Ver calificaciones</a>
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
                        <a class="boton-menu boton-carrito" href="./configuracion.php"><i class="bi bi-cart-fill"></i>Configuración <span class="numerito">0</span></a>
                    </li>
                </ul>
            </nav>
            <footer>
                <p class="texto-footer">© 2023 WASHGT</p>
            </footer>
        </aside>
        <main>
    <div class="container">
        <h1 class="titulo">Calificaciones de empleados</h1>
        <table id="usuariosTable" class="tabla">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del empleado</th>
                    <th>Calificación Promedio</th>
                </tr>
            </thead>
            <tbody>
                <!-- Los datos de la tabla se cargarán aquí -->
            </tbody>
        </table>
        <a class="enlace-xml" href="calificaciones.xml">Ver Archivo XML</a>
        <button type="button" id="regresarBtn" class="btn-regresar">Regresar</button>
    </div>
    <script>
        $(document).ready(function() {
            cargarDatos();
            $('#regresarBtn').click(function() {
                window.location.href = 'panel-admin.php'; // Redireccionar a index.html
            });
        });

        function cargarDatos() {
            $.ajax({
                type: "GET",
                url: "calificaciones.xml",
                dataType: "xml",
                success: function(xml) {
                    $(xml).find('usuario').each(function() {
                        var id = $(this).attr('id');
                        var nombre = $(this).find('nombre').text();
                        var calificacion = $(this).find('calificacion').text();
                        $('#usuariosTable tbody').append('<tr><td>' + id + '</td><td>' + nombre + '</td><td>' + calificacion + '</td></tr>');
                    });
                },
                error: function() {
                    alert("Error al cargar los datos del XML.");
                }
            });
        }
    </script>
    </main>
</body>
</html>
