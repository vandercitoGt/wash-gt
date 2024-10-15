<?php
 session_start();

// // Verificar si la sesión está iniciada
 if (!isset($_SESSION['usuario'])) {
//     // Redirigir al usuario a la página de login
     header("Location: index.html");
     exit();
 }
$xmlFile = 'calificaciones.xml';
$xml = simplexml_load_file($xmlFile);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="style-calificar-servicio.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <title>Encuesta de Satisfacción</title>
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
                    <a class="boton-menu boton-categoria" href="citas-solicitadas.php"><i class="bi bi-calendar-check"></i>Mis citas</a>
                </li>
                <li>
                    <a class="boton-menu boton-categoria" href="./panel-usuario.php"><i class="bi bi-hand-index-thumb-fill"></i>Fila de espera</a>
                </li>
                <li>
                    <a class="boton-menu active" href="./calificacion.php"><i class="bi bi-person-plus"></i>Calificar servicio</a>
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
                <!-- Agregar botón para ver citas -->

            </ul>
        </nav>
        <footer>
            <p class="texto-footer">© 2023 WASHGT</p>
        </footer>
    </aside>
    <main>
    <div class="container">
        <h1>Encuesta de Satisfacción</h1>
        <form id="encuestaForm" action="guardar_datos.php" method="POST">
            <p>Selecciona el empleado que deseas calificar:</p>
            <select name="empleado">
                <?php foreach ($xml->usuario as $usuario): ?>
                    <option value="<?php echo $usuario['id']; ?>"><?php echo $usuario->nombre; ?></option>
                <?php endforeach; ?>
            </select>
            <p>¿Qué tan satisfactorio fue el servicio realizado?</p>
            <div class="radio-buttons">
                <input type="radio" id="satisfaccion1" name="satisfaccion" value="1">
                <label for="satisfaccion1">1</label>
                <input type="radio" id="satisfaccion2" name="satisfaccion" value="2">
                <label for="satisfaccion2">2</label>
                <input type="radio" id="satisfaccion3" name="satisfaccion" value="3">
                <label for="satisfaccion3">3</label>
                <input type="radio" id="satisfaccion4" name="satisfaccion" value="4">
                <label for="satisfaccion4">4</label>
                <input type="radio" id="satisfaccion5" name="satisfaccion" value="5">
                <label for="satisfaccion5">5</label>
            </div>
            <button type="submit">Enviar</button>
        </form>
    </div>
    <a class="boton-menu boton-categoria" href="./panel-usuario.php"><i class="bi bi-arrow-left"></i>Regresar</a>
                
    </main>
    
</body>
</html>

