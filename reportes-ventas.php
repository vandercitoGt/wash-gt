<?php
 session_start();

// // Verificar si la sesión está iniciada
 if (!isset($_SESSION['usuario'])) {
//     // Redirigir al usuario a la página de login
     header("Location: index.html");
     exit();
 }
// Incluir la librería FPDF
require('fpdf.php');

// Función para calcular ingresos según el tipo de vehículo y servicio
function calcularIngreso($tipoCarro, $tipoServicio) {
    switch($tipoCarro) {
        case 'sedan':
            switch($tipoServicio) {
                case 'completo':
                    return 210;
                case 'interior':
                case 'exterior':
                    return 120;
            }
            break;
        case 'camioneta':
        case 'SUV':
            switch($tipoServicio) {
                case 'completo':
                    return 230;
                case 'interior':
                case 'exterior':
                    return 130;
            }
            break;
        case 'hatchback':
            switch($tipoServicio) {
                case 'completo':
                    return 200;
                case 'interior':
                case 'exterior':
                    return 110;
            }
            break;
    }
    return 0; // Valor predeterminado si no coincide con ningún tipo de vehículo o servicio
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="main.css">
    <link rel="stylesheet" type="text/css" href="style-reportes-ventas.css">
    <link rel="stylesheet" type="text/css" href="style-configuracion.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <title>Reporte de Citas</title>
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
                        <a class="boton-menu boton-categoria boton-cerrar active" href="./reportes-ventas.php"><i class="bi bi-file-earmark-arrow-down-fill"></i>Reportes</a>
                    </li>
                    <li>
                        <button class="boton-menu boton-categoria">
                            <i class="bi bi-person-circle"></i>
                            <?php
                            if(isset($_SESSION['usuario'])) {
                                echo $_SESSION['usuario'];
                            } else {
                                echo "Nombre de Usuario Predeterminado";
                            }
                            ?>
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
            <h2 class='titulo'>Filtrar citas por fecha</h2>
            <form class='formulario-fechas' action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <label for="fechaInicio">Fecha de inicio:</label>
                <input type="date" id="fechaInicio" name="fechaInicio" required>
                <label for="fechaFin">Fecha de fin:</label>
                <input type="date" id="fechaFin" name="fechaFin" required>
                <button class='boton' type="submit">Filtrar</button>
            </form>

            <?php
            // Verificar si se enviaron fechas
            if(isset($_POST['fechaInicio']) && isset($_POST['fechaFin'])) {
                // Obtener las fechas enviadas
                $fechaInicio = $_POST['fechaInicio'];
                $fechaFin = $_POST['fechaFin'];

                // Cargar el archivo XML
                $xmlFile = "citas-completadas.xml";

                // Verificar si el archivo XML existe
                if(file_exists($xmlFile)) {
                    // Cargar el archivo XML
                    $xml = simplexml_load_file($xmlFile);

                    // Verificar si hay citas en el archivo XML
                    if ($xml !== false && isset($xml->cita)) {
                        // Convertir las fechas de inicio y fin al formato YYYY-MM-DD
                        $fechaInicio = date('Y-m-d', strtotime($fechaInicio));
                        $fechaFin = date('Y-m-d', strtotime($fechaFin));

                        // Inicializar arreglo para almacenar ingresos por tipo de vehículo y servicio
                        $ingresos = array();

                        // Filtrar las citas que tienen una fecha dentro del rango especificado
                        $citasFiltradas = array();
                        foreach($xml->cita as $cita) {
                            $fechaCita = date('Y-m-d', strtotime((string)$cita->fecha));
                            if ($fechaCita >= $fechaInicio && $fechaCita <= $fechaFin) {
                                $citasFiltradas[] = $cita;
                                // Calcular ingresos para cada cita y agregarlos al arreglo
                                $tipoCarro = (string)$cita->tipoCarro;
                                $tipoServicio = (string)$cita->tipoServicio;
                                $ingreso = calcularIngreso($tipoCarro, $tipoServicio);
                                if(!isset($ingresos[$tipoCarro][$tipoServicio])) {
                                    $ingresos[$tipoCarro][$tipoServicio] = 0;
                                }
                                $ingresos[$tipoCarro][$tipoServicio] += $ingreso;
                            }
                        }

                        // Mostrar las citas filtradas en una tabla
                        echo "<h2 class='titulo'>Citas filtradas</h2>";
                        echo "<table class='tabla-citas'>
                                <tr>
                                    <th>ID</th>
                                    <th>Tipo de Carro</th>
                                    <th>Tipo de Servicio</th>
                                    <th>Hora de la Cita</th>
                                    <th>Nombre del Cliente</th>
                                    <th>Fecha</th>
                                </tr>";
                        foreach($citasFiltradas as $cita) {
                            echo "<tr>";
                            echo "<td>" . $cita->id . "</td>";
                            echo "<td>" . $cita->tipoCarro . "</td>";
                            echo "<td>" . $cita->tipoServicio . "</td>";
                            echo "<td>" . $cita->horaCita . "</td>";
                            echo "<td>" . $cita->usuario . "</td>";
                            echo "<td>" . $cita->fecha . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";

                        // Mostrar botón para generar el reporte en PDF
                        echo "<form class='formulario-pdf' action='generar_pdf.php' method='post'>";
                        echo "<input type='hidden' name='fechaInicio' value='$fechaInicio'>";
                        echo "<input type='hidden' name='fechaFin' value='$fechaFin'>";
                        echo "<button class='boton-agregar' type='submit' name='generarPDF'>Generar Reporte PDF</button>";
                        echo "</form>";

                    } else {
                        echo "<p class='mensaje'>No hay citas en el archivo XML.</p>";
                    }
                } else {
                    echo "<p class='mensaje'>El archivo XML no existe.</p>";
                }
            }
            ?>
        </main>
    </div>
</body>
</html>
