<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'includes/conexion.php';  
require('fpdf.php');  

// Crear instancia de la clase FPDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Título del Documento
$pdf->Cell(190, 10, 'Factura de Pedido', 0, 1, 'C');  // Título centrado

// Datos adicionales del cliente
$pdf->Ln(10);  // Espacio adicional antes de los datos del cliente
$pdf->SetFont('Arial', 'I', 12);  // Fuente cursiva para los datos del cliente
$pdf->Cell(95, 10, "Nombre Cliente: " . $_POST['nombre_cliente'], 0, 0);
$pdf->Cell(95, 10, "NIT Cliente: " . $_POST['nit_cliente'], 0, 1);
$pdf->Cell(95, 10, "Direccion Cliente: " . $_POST['direccion_cliente'], 0, 0);
$pdf->Cell(95, 10, "Metodo de Pago: " . $_POST['metodo_pago'], 0, 1);


// Espacio adicional
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);  // Cambiar la fuente para el contenido

// Verificar la presencia y validez de 'tipo_cuenta'
if (isset($_POST['tipo_cuenta']) && is_numeric($_POST['tipo_cuenta'])) {
    $tipo_cuenta = $_POST['tipo_cuenta'];

    // Consulta SQL para obtener los detalles de los pedidos
    $sql = "SELECT cuentas.cuenta_id, cuentas.mesa_id, cuentas.fecha_apertura, cuentas.fecha_cierre, cuentas.total, items_cuenta.item_id, items_cuenta.cantidad, items_cuenta.fecha_hora, items_cuenta.cocinado, platos.plato_id, platos.nombre, platos.descripcion, platos.precio, platos.tipo, (items_cuenta.cantidad * platos.precio) AS total_item FROM cuentas INNER JOIN items_cuenta ON cuentas.cuenta_id = items_cuenta.cuenta_id INNER JOIN platos ON items_cuenta.item_id = platos.plato_id WHERE cuentas.cuenta_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$tipo_cuenta]);

    // Encabezados de la tabla de pedidos
    $pdf->SetFillColor(232, 232, 232);  // Color gris para el fondo de la celda
    $pdf->SetFont('Arial', 'B', 12);  // Fuente en negrita para los encabezados
    $pdf->Cell(46, 10, 'Plato', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Precio Unit.', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Subtotal', 1, 1, 'C', true);  // El '1' al final indica el salto de línea

    $pdf->SetFont('Arial', '', 12);  // Fuente normal para los datos

    // Agregar datos al PDF
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $pdf->Cell(46, 10, $row['nombre'], 1);
        $pdf->Cell(30, 10, $row['cantidad'], 1, 0, 'C');
        $pdf->Cell(40, 10, '$' . number_format($row['precio'], 2), 1, 0, 'R');
        $pdf->Cell(40, 10, '$' . number_format($row['total_item'], 2), 1, 1, 'R');
    }

    // Total
    $sqlTotal = "SELECT SUM(items_cuenta.cantidad * platos.precio) AS sumatoria_total_items FROM cuentas INNER JOIN items_cuenta ON cuentas.cuenta_id = items_cuenta.cuenta_id INNER JOIN platos ON items_cuenta.item_id = platos.plato_id WHERE cuentas.cuenta_id = ? GROUP BY cuentas.cuenta_id";
    $stmtTotal = $conn->prepare($sqlTotal);
    $stmtTotal->execute([$tipo_cuenta]);
    $totalRow = $stmtTotal->fetch(PDO::FETCH_ASSOC);

    $pdf->Cell(116, 10, 'Total', 1, 0, 'C', true);
    $pdf->Cell(40, 10, '$' . number_format($totalRow['sumatoria_total_items'], 2), 1, 1, 'R', true);
} else {
    $pdf->Cell(190, 10, 'Tipo de cuenta no especificado o inválido.', 1, 1, 'C');
}

// Guardar y enviar el PDF al navegador
$pdf->Output('I', 'Factura.pdf');
?>
