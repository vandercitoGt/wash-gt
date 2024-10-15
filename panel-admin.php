<?php
  session_start();

 // Verificar si la sesión está iniciada
   if (!isset($_SESSION['usuario'])) {
      // Redirigir al usuario a la página de login si no está autenticado
       header("Location: index.html");
       exit();
   }

//  // Variables de sesión para el usuario
  $usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WASH-GT</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="style-panel-admin.css">
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
                    <li>
                        <button class="boton-menu boton-categoria active"><i class="bi bi-hand-index-thumb-fill"></i>Pendientes</button>
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
                        <a class="boton-menu boton-carrito" href="./configuracion.php"><i class="bi bi-cart-fill"></i>Configuración <span class="numerito">0</span></a>
                    </li>
                </ul>
            </nav>
            <footer>
                <p class="texto-footer">© 2023 WASHGT</p>
            </footer>
        </aside>
        <main>
            <h2 class="titulo-principal">Próximos servicios</h2>
            <?php
            // Ruta al archivo XML
            $xmlFile = "citas.xml";

            // Verificar si el archivo XML existe
            if (file_exists($xmlFile)) {
                // Cargar el archivo XML
                $xml = simplexml_load_file($xmlFile);

                // Verificar si hay citas en el archivo XML
                if ($xml !== false && isset($xml->cita)) {
                    echo '<div class="presentation">';
                    echo '<ul>';
                    // Iterar sobre cada cita y mostrar los detalles
                    foreach ($xml->cita as $index => $cita) {
                        echo '<li class="cita">';
                        // Obtener solo la hora de la cita (suponiendo que el formato actual sea 'YYYY-MM-DD HH:MM:SS')
                        $horaCita = explode(' ', htmlspecialchars($cita->horaCita))[1];
                        echo 'Hora de la cita: ' . $horaCita . '<br>';
                        echo 'Tipo de carro: ' . htmlspecialchars($cita->tipoCarro) . '<br>';
                        echo 'Tipo de servicio: ' . htmlspecialchars($cita->tipoServicio) . '<br>';
                        echo 'Nombre del usuario: ' . htmlspecialchars($cita->usuario) . '<br>';
                        echo 'Teléfono: ' . htmlspecialchars($cita->telefono) . '<br>'; // Mostrar el número de teléfono
                        // Agregar botón para finalizar servicio con el ID de la cita
                        echo '<button class="finalizar-servicio" onclick="confirmarFinalizarServicio(\'' . htmlspecialchars($cita->id) . '\')">Finalizar servicio</button>';
                        // Agregar botón para enviar mensaje
                        echo '<button class="finalizar-servicio" onclick="enviarMensaje(\'' . htmlspecialchars($cita->telefono) . '\')">Enviar msj</button>';
                        echo '</li>';
                    }
                    
                    echo '</ul>';
                    echo '</div>';
                } else {
                    echo '<div class="presentation">';
                    echo '<p class="carrito-vacio">No hay servicios pendientes. <i class="bi bi-emoji-frown"></i></p>';
                    echo '</div>';
                }
            } else {
                echo '<div class="presentation">';
                echo '<p class="carrito-vacio">No hay servicios pendientes. <i class="bi bi-emoji-frown"></i></p>';
                echo '</div>';
            }
            ?>
            <button id="mostrarFormulario" type="button">Agregar servicio</button>
            
            <!-- Formulario para agregar servicio -->
            <div id="formularioServicio" style="display:none;">
                <form id="agregarServicioForm">
                    <div class="form-group">
                        <label for="tipoCarro" class="label-input">Tipo de carro:</label>
                        <select class="input-configuracion" name="tipoCarro" id="tipoCarro" required>
                            <option value="Sedan">Sedan</option>
                            <option value="Hatchback">Hatchback</option>
                            <option value="Camioneta">Camioneta</option>
                            <option value="SUV">SUV</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tipoServicio" class="label-input">Tipo de servicio:</label>
                        <select class="input-configuracion" name="tipoServicio" id="tipoServicio" required>
                            <option value="Interior">Interior</option>
                            <option value="Exterior">Exterior</option>
                            <option value="Completo">Completo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="telefono" class="label-input">Número de teléfono:</label>
                        <input type="tel" class="input-configuracion" name="telefono" id="telefono" required>
                    </div>
                    <input type="hidden" name="usuarioHidden" id="usuarioHidden" value="<?php echo htmlspecialchars($usuario); ?>">
                    <input type="hidden" name="horaCita" id="horaCita">
                    <button class="boton-agregar" type="submit">Agregar</button>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Función para confirmar antes de finalizar el servicio
        function confirmarFinalizarServicio(id) {
            // Mostrar cuadro de diálogo de confirmación
            if (confirm('¿Estás seguro de finalizar este servicio?')) {
                // Si el usuario confirma, llamar a la función finalizarServicio con el ID de la cita
                finalizarServicio(id);
            }
        }

        // Función para finalizar el servicio
        function finalizarServicio(id) {
            // Log para verificar el ID de la cita
            console.log("ID de la cita a eliminar:", id);
            
            // Realizar una solicitud AJAX para eliminar la cita del XML y guardarla en el nuevo XML
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Recargar la página después de eliminar la cita
                    location.reload();
                }
            };
            xhttp.open("GET", "eliminar-cita.php?id=" + id, true);
            xhttp.send();
        }

        function enviarMensaje(telefono) {
    // Realizar una solicitud AJAX para enviar el mensaje
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        console.log("Estado de la solicitud:", this.readyState);
        console.log("Estado de la respuesta:", this.status);
        if (this.readyState == 4 && this.status == 200) {
            alert("Mensaje enviado correctamente a " + telefono);
        } else if (this.readyState == 4) {
            console.error("Hubo un error al enviar el mensaje:", this.responseText);
            alert("Hubo un error al enviar el mensaje. Verifica la consola para más detalles.");
        }
    };
    xhttp.open("POST", "enviar-mensaje.php", true);
    xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xhttp.send(JSON.stringify({ telefono: telefono }));
}

        // Obtener referencia al botón y al formulario
        const mostrarFormularioBtn = document.getElementById('mostrarFormulario');
        const formularioServicio = document.getElementById('formularioServicio');
        const agregarServicioForm = document.getElementById('agregarServicioForm');

        // Agregar oyente de evento al botón
        mostrarFormularioBtn.addEventListener('click', function() {
            // Mostrar u ocultar el formulario dependiendo de su estado actual
            if (formularioServicio.style.display === 'none') {
                formularioServicio.style.display = 'block';
            } else {
                formularioServicio.style.display = 'none';
            }
        });

        // Agregar oyente de evento al formulario para manejar el envío
        agregarServicioForm.addEventListener('submit', function(event) {
            event.preventDefault();

            // Obtener los datos del formulario
            const formData = new FormData(agregarServicioForm);
            let telefono = formData.get('telefono');
            if (!telefono.startsWith('521')) {
                telefono = '521' + telefono;
            }
            const data = {
                tipoCarro: formData.get('tipoCarro'),
                tipoServicio: formData.get('tipoServicio'),
                telefono: telefono,
                usuarioHidden: formData.get('usuarioHidden'),
                horaCita: new Date().toLocaleString('en-GB')
            };

            // Enviar los datos a través de una solicitud AJAX
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "citas-nuevas.php", true);
            xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    alert("Cita agendada correctamente");
                    location.reload(); // Recargar la página después de agregar la cita
                } else if (this.readyState == 4) {
                    alert("Hubo un error al agendar la cita: " + this.responseText);
                }
            };
            xhttp.send(JSON.stringify(data));
        });
    </script>
</body>
</html>
