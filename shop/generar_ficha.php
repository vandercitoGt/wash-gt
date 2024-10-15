<?php
require('fpdf.php');

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Ficha de Pago', 0, 1, 'C');
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Contenido del PDF
    function AddContent($productos, $total)
    {
        $this->SetFont('Arial', '', 12);
        foreach ($productos as $producto) {
            $this->Cell(0, 10, 'Producto: ' . $producto['title'], 0, 1);
            $this->Cell(0, 10, 'Cantidad: ' . $producto['quantity'], 0, 1);
            $this->Cell(0, 10, 'Precio Unitario: $' . $producto['unit_price'], 0, 1);
            $this->Cell(0, 10, 'Subtotal: $' . $producto['subtotal'], 0, 1);
            $this->Ln(10);
        }
        $this->Cell(0, 10, 'Total: $' . $total, 0, 1);
    }
}
// Obtener los datos enviados desde el formulario
$productos = isset($_POST['productos']) ? json_decode($_POST['productos'], true) : [];
$total = isset($_POST['total']) ? $_POST['total'] : 0;

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->AddContent($productos, $total);
$pdf->Output('ficha_pago.pdf', 'I');
?>
