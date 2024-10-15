<?php
 session_start();

//  // Verificar si la sesión está iniciada
  if (!isset($_SESSION['usuario'])) {
//     // Redirigir al usuario a la página de login
     header("Location: index.html");
      exit();
  }

// Obtener el contenido del archivo XML
$citasXML = simplexml_load_file("citas.xml");

$tiempoEsperaTotal = 0; // Inicializar el tiempo total de espera

// Calcular el tiempo de espera acumulado de todas las citas
foreach ($citasXML->cita as $cita) {
    $tipoCarro = (string)$cita->tipoCarro;
    $tipoServicio = (string)$cita->tipoServicio;

    if ($tipoServicio == "Completo") {
        if ($tipoCarro == "Sedan" || $tipoCarro == "Hatchback") {
            $tiempoEsperaTotal += 40; // Minutos
        } elseif ($tipoCarro == "Camioneta" || $tipoCarro == "SUV") {
            $tiempoEsperaTotal += 50; // Minutos
        }
    } else {
        if ($tipoCarro == "Sedan" || $tipoCarro == "Hatchback") {
            $tiempoEsperaTotal += 20; // Minutos
        } elseif ($tipoCarro == "Camioneta" || $tipoCarro == "SUV") {
            $tiempoEsperaTotal += 25; // Minutos
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WASH-GT</title>
<link rel="stylesheet" href="main.css">
<link rel="stylesheet" href="style-panel-user.css">
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
                    <a class="boton-menu boton-categoria" href="citas-solicitadas.php"><i class="bi bi-calendar-check"></i>Mis citas</a>
                </li>
                <li>
                    <button class="boton-menu boton-categoria active"><i class="bi bi-hand-index-thumb-fill"></i>Fila de espera</button>
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
                <!-- Agregar botón para ver citas -->

            </ul>
        </nav>
        <footer>
            <p class="texto-footer">© 2023 WASHGT</p>
        </footer>
    </aside>
    <main>
        <h2 class="titulo-principal">Tiempo aproximado en pasar su carro:</h2>
        <div class="presentation">
            <!-- Mostrar el tiempo de espera si existe -->
            <?php if($tiempoEsperaTotal > 0): ?>
                <p class="carrito-vacio">El tiempo de espera aproximado es de <span id="tiempoEspera"><?php echo $tiempoEsperaTotal; ?></span> minutos</p><br>
            <?php else: ?>
                <!-- Si no hay servicios pendientes, mostrar el mensaje predeterminado -->
                <p class="carrito-vacio">No hay servicios pendientes, su carro puede pasar ahora mismo<i class="bi bi-emoji-frown"></i></p><br>
            <?php endif; ?>
        </div>
        <!-- Sección para mostrar detalles de la cita -->
        <div id="detallesCita" style="display: none;">
            <h3>Detalles de la cita:</h3>
            <p id="horaCita"></p>
            <p id="tipoCarro"></p>
            <p id="tipoServicio"></p>
            <p id="costo"></p>
        </div>
                <!-- Selectores para el tipo de carro, tipo de servicio y hora de la cita -->
                <label for="tipoCarroSelect">Seleccione el tipo de carro:</label>
        <select id="tipoCarroSelect">
            <option value="Sedan">Sedan</option>
            <option value="Hatchback">Hatchback</option>
            <option value="Camioneta">Camioneta</option>
            <option value="SUV">SUV</option>
        </select>
        <label for="tipoServicioSelect">Seleccione el tipo de servicio:</label>
        <select id="tipoServicioSelect">
            <option value="Interior">Interior</option>
            <option value="Exterior">Exterior</option>
            <option value="Completo">Completo</option>
        </select>
        <label for="horaCitaSelect">Seleccione el horario:</label>
        <select id="horaCitaSelect">
            <!-- Aquí se cargarán dinámicamente las opciones -->
        </select>
    </main>
</div>

<script>
    // Función para cargar los horarios disponibles
    function cargarHorariosDisponibles() {
        var citasXML = `<?php echo htmlspecialchars_decode(file_get_contents("citas.xml")); ?>`;
        var parser = new DOMParser();
        var xmlDoc = parser.parseFromString(citasXML, "text/xml");
        var citas = xmlDoc.getElementsByTagName("cita");
        var horariosOcupados = Array.from(citas).map(cita => cita.getElementsByTagName("horaCita")[0].textContent.trim().toLowerCase());

        var selectHorario = document.getElementById("horaCitaSelect");
        selectHorario.innerHTML = ""; // Limpiar las opciones actuales

        var horariosDisponibles = [
            "8:00", "9:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00"
        ];

        // Filtrar los horarios disponibles
        var horariosLibres = horariosDisponibles.filter(horario => !horariosOcupados.includes(horario.toLowerCase()));

        // Agregar las opciones disponibles al select
        horariosLibres.forEach(function(horario) {
            var option = document.createElement("option");
            option.text = horario;
            option.value = horario;
            selectHorario.add(option);
        });
    }

    window.onload = function() {
        cargarHorariosDisponibles();
        setInterval(actualizarTiempoEspera, 60000); // Actualizar cada 1 minuto (60000 milisegundos)
    };

    function actualizarTiempoEspera() {
        // Hacer una petición AJAX para obtener el tiempo de espera actualizado sin recargar la página
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "panel-usuario.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var parser = new DOMParser();
                var doc = parser.parseFromString(xhr.responseText, "text/html");
                var nuevoTiempoEspera = doc.getElementById("tiempoEspera").innerText;
                document.getElementById("tiempoEspera").innerText = nuevoTiempoEspera;
            }
        };
        xhr.send();
    }

    function guardarCita() {
        var tipoCarro = document.getElementById("tipoCarroSelect").value;
        var tipoServicio = document.getElementById("tipoServicioSelect").value;
        var horaCita = document.getElementById("horaCitaSelect").value;

        // Obtener el nombre de usuario
        var usuarioHidden = '<?php echo $usuario; ?>';

        // Crear objeto con los datos de la cita
        var cita = {
            tipoCarro: tipoCarro,
            tipoServicio: tipoServicio,
            horaCita: horaCita,
            usuarioHidden: usuarioHidden // Agregar el nombre de usuario
        };

        // Enviar los datos al servidor mediante AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "citas-nuevas.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert(xhr.responseText); // Mostrar mensaje de confirmación
                actualizarTiempoEspera(); // Actualizar el tiempo de espera
            }
        };
        xhr.send(JSON.stringify(cita));
    }
</script>

</body>
</html>

