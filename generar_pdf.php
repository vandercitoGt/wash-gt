<?php
// Incluir la librería FPDF
require('fpdf.php');

// Función para calcular ingresos según el tipo de vehículo y servicio
function calcularIngreso($tipoCarro, $tipoServicio) {
    // Convertir los valores a minúsculas
    $tipoCarro = strtolower($tipoCarro);
    $tipoServicio = strtolower($tipoServicio);

    // Definir los precios según el tipo de carro y servicio
    $precios = array(
        'sedan' => array(
            'completo' => 210,
            'interior' => 120,
            'exterior' => 120
        ),
        'camioneta' => array(
            'completo' => 230,
            'interior' => 130,
            'exterior' => 130
        ),
        'suv' => array(
            'completo' => 230,
            'interior' => 130,
            'exterior' => 130
        ),
        'hatchback' => array(
            'completo' => 200,
            'interior' => 110,
            'exterior' => 110
        )
    );

    // Verificar si el tipo de carro y servicio existen en la matriz de precios
    if (isset($precios[$tipoCarro]) && isset($precios[$tipoCarro][$tipoServicio])) {
        return $precios[$tipoCarro][$tipoServicio];
    } else {
        return 0; // Valor predeterminado si no se encuentra el precio
    }
}

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
            $totalIngresos = 0; // Variable para almacenar el total de ingresos

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
                    // Incrementar el total de ingresos
                    $totalIngresos += $ingreso;
                }
            }

            // Generar el reporte en PDF en formato horizontal
            $pdf = new FPDF('L');
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'Reporte de Servicios', 0, 1, 'C');
            $pdf->Ln(10);

            // Agregar la información de las citas al PDF
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(40, 10, 'ID', 1);
            $pdf->Cell(40, 10, 'Tipo de Carro', 1);
            $pdf->Cell(40, 10, 'Tipo de Servicio', 1);
            $pdf->Cell(40, 10, 'Hora de la Cita', 1);
            $pdf->Cell(60, 10, 'Nombre del Cliente', 1);
            $pdf->Cell(30, 10, 'Fecha', 1);
            $pdf->Cell(30, 10, 'Precio', 1); // Nuevo: Agregar columna de precio
            $pdf->Ln();

            foreach($citasFiltradas as $cita) {
                $tipoCarro = (string)$cita->tipoCarro;
                $tipoServicio = (string)$cita->tipoServicio;
                $ingreso = calcularIngreso($tipoCarro, $tipoServicio);

                $pdf->Cell(40, 10, $cita->id, 1);
                $pdf->Cell(40, 10, $cita->tipoCarro, 1);
                $pdf->Cell(40, 10, $cita->tipoServicio, 1);
                $pdf->Cell(40, 10, $cita->horaCita, 1);
                $pdf->Cell(60, 10, $cita->usuario, 1);
                $pdf->Cell(30, 10, date('Y-m-d', strtotime($cita->fecha)), 1); // Mostrar solo la fecha
                $pdf->Cell(30, 10, '$' . $ingreso, 1); // Nuevo: Mostrar precio
                $pdf->Ln();
            }

            // Mostrar el total de ingresos
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'Total de ingresos: $' . $totalIngresos, 0, 1, 'R');
            $pdf->Ln();

            // Salida del PDF
            $pdf->Output('D');
            exit;
            
        } else {
            echo "No hay citas en el archivo XML.";
        }
    } else {
        echo "El archivo XML no existe.";
    }
}

// Mostrar el formulario de filtrado de fechas en todo momento
?>
<h2>Filtrar citas por fecha</h2>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="fechaInicio">Fecha de inicio:</label>
    <input type="date" id="fechaInicio" name="fechaInicio" required>
    <label for="fechaFin">Fecha de fin:</label>
    <input type="date" id="fechaFin" name="fechaFin" required>
    <button type="submit">Filtrar</button>
</form>
