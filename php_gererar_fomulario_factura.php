<?php
require('fpdf.php');

// Crear una nueva instancia de PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Agregar cabeceras para la tabla de pedidos
$header = array('Cuenta ID', 'Mesa ID', 'Fecha Apertura', 'Fecha Cierre', 'Total', 'Item ID', 'Cantidad', 'Fecha Hora', 'Cocinado', 'Plato ID', 'Nombre', 'Descripción', 'Precio', 'Tipo', 'Total Item');
foreach($header as $col) {
    $pdf->Cell(24, 7, $col, 1);
}
$pdf->Ln();

// Cargar los datos de la base de datos
while ($row = pg_fetch_assoc($consulta_pedidos1)) {
    $pdf->Cell(24, 6, $row['cuenta_id'], 1);
    $pdf->Cell(24, 6, $row['mesa_id'], 1);
    $pdf->Cell(24, 6, $row['fecha_apertura'], 1);
    $pdf->Cell(24, 6, $row['fecha_cierre'], 1);
    $pdf->Cell(24, 6, $row['total'], 1);
    $pdf->Cell(24, 6, $row['item_id'], 1);
    $pdf->Cell(24, 6, $row['cantidad'], 1);
    $pdf->Cell(24, 6, $row['fecha_hora'], 1);
    $pdf->Cell(24, 6, $row['cocinado'], 1);
    $pdf->Cell(24, 6, $row['plato_id'], 1);
    $pdf->Cell(24, 6, $row['nombre'], 1);
    $pdf->Cell(24, 6, $row['descripcion'], 1);
    $pdf->Cell(24, 6, $row['precio'], 1);
    $pdf->Cell(24, 6, $row['tipo'], 1);
    $pdf->Cell(24, 6, $row['total_item'], 1);
    $pdf->Ln();
}

// Agregar cabecera para el total de la cuenta
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(30, 10, 'Total General:', 0);
while ($row = pg_fetch_assoc($consulta_pedidos2)) {
    $pdf->Cell(50, 10, $row['sumatoria_total_items'], 0, 1, 'C');
}

// Salida del PDF
$pdf->Output('D', 'Pedidos.pdf');  // 'D' para forzar la descarga
?>