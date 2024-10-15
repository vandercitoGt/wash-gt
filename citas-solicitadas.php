<?php
//  session_start();

//  // Verificar si la sesión está iniciada
//  if (!isset($_SESSION['usuario'])) {
//      // Redirigir al usuario a la página de login
//      header("Location: index.html");
//      exit();
//  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WASH-GT</title>
<link rel="stylesheet" href="main.css">
<link rel="stylesheet" href="style-configuracion.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    
<div class="wrapper">
    <aside>
        <header>
            <h1 class="logo">WASH-GT</h1>
        </header>
        <nav>
            <ul class="menu">
                <!-- Agregar botón para solicitar servicio -->
                <li>
                    <button class="boton-menu boton-categoria" onclick="guardarCita()"><i class="bi bi-plus"></i>Solicitar Servicio</button>
                </li>
                <li>
                    <a class="boton-menu boton-categoria active" href="citas-solicitadas.php"><i class="bi bi-calendar-check"></i>Mis Citas</a>
                </li>
                <li>
                    <a class="boton-menu boton-categoria" href="panel-usuario.php"><i class="bi bi-hand-index-thumb-fill"></i>Fila de espera</a>
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
                    <a class="boton-menu boton-carrito" href="./configuracion-usuario.php"><i class="bi bi-cart-fill"></i>Configuración <span class="numerito">0</span></a>
                </li>
            </ul>
        </nav>
        <footer>
            <p class="texto-footer">© 2023 WASHGT</p>
        </footer>
    </aside>
    <main>
        <h2 class="titulo-principal">Citas pendientes:</h2>
        <div class="presentation">
            <?php
            session_start();
            if(isset($_SESSION['usuario'])) {
                $usuario = $_SESSION['usuario'];
            } else {
                $usuario = "Nombre de Usuario Predeterminado"; 
            }

            // Ruta al archivo XML
            $xmlFile = "citas.xml";

            // Verificar si el archivo XML existe
            if(file_exists($xmlFile)) {
                // Cargar el archivo XML
                $xml = simplexml_load_file($xmlFile);

                // Verificar si hay citas en el archivo XML
                if ($xml !== false && isset($xml->cita)) {
                    echo '<div class="presentation">';
                    echo '<ul>';
                    // Iterar sobre cada cita y mostrar las citas del usuario actual
                    foreach ($xml->cita as $cita) {
                        if ($cita->usuario == $usuario) {
                            echo '<li class="cita">';
                            echo 'Hora de la cita: ' . $cita->horaCita . '<br>';
                            echo 'Tipo de carro: ' . $cita->tipoCarro . '<br>';
                            echo 'Tipo de servicio: ' . $cita->tipoServicio . '<br>';
                            echo 'Nombre del usuario: ' . $cita->usuario . '<br>';
                            // Agregar botón para cancelar la cita
                            echo '<a href="cancelar_cita_pendiente.php?id=' . $cita->id . '" class="boton-agregar">Cancelar Cita</a>';
                            echo '</li>';
                        }
                    }
                    echo '</ul>';
                    echo '</div>';
                } else {
                    echo '<div class="presentation">';
                    echo '<p class="carrito-vacio">No hay citas pendientes. <i class="bi bi-emoji-frown"></i></p>';
                    echo '</div>';
                }
            } else {
                echo '<div class="presentation">';
                echo '<p class="carrito-vacio">No hay citas pendientes. <i class="bi bi-emoji-frown"></i></p>';
                echo '</div>';
            }
            ?>
        </div>
    </main>
</div>
</body>
</html>
