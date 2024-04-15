<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'includes/conexion.php';  
require('fpdf.php');  

// Crear instancia de la clase FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Verificar la presencia y validez de 'tipo_cuenta'
if (isset($_POST['tipo_cuenta']) && is_numeric($_POST['tipo_cuenta'])) {
    $tipo_cuenta = $_POST['tipo_cuenta'];

    // Preparar consulta SQL para obtener los detalles de los pedidos
    $sql = "SELECT cuentas.cuenta_id, cuentas.mesa_id, cuentas.fecha_apertura, cuentas.fecha_cierre, cuentas.total, items_cuenta.item_id, items_cuenta.cantidad, items_cuenta.fecha_hora, items_cuenta.cocinado, platos.plato_id, platos.nombre, platos.descripcion, platos.precio, platos.tipo, (items_cuenta.cantidad * platos.precio) AS total_item FROM cuentas INNER JOIN items_cuenta ON cuentas.cuenta_id = items_cuenta.cuenta_id INNER JOIN platos ON items_cuenta.item_id = platos.plato_id WHERE cuentas.cuenta_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$tipo_cuenta]);

    // Agregar datos al PDF
    $pdf->Cell(0, 10, 'Detalle de Pedidos:', 0, 1);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $pdf->Cell(0, 10, "Plato: " . $row['nombre'] . " - Cantidad: " . $row['cantidad'] . " - Precio Unitario: $" . $row['precio'] . " - Subtotal: $" . $row['total_item'], 0, 1);
    }

    // Preparar consulta SQL para obtener el total de la cuenta
    $sqlTotal = "SELECT SUM(items_cuenta.cantidad * platos.precio) AS sumatoria_total_items FROM cuentas INNER JOIN items_cuenta ON cuentas.cuenta_id = items_cuenta.cuenta_id INNER JOIN platos ON items_cuenta.item_id = platos.plato_id WHERE cuentas.cuenta_id = ? GROUP BY cuentas.cuenta_id";
    $stmtTotal = $conn->prepare($sqlTotal);
    $stmtTotal->execute([$tipo_cuenta]);
    $totalRow = $stmtTotal->fetch(PDO::FETCH_ASSOC);

    // Agregar total al PDF
    $pdf->Cell(0, 10, "Total: $" . $totalRow['sumatoria_total_items'], 0, 1);
} else {
    $pdf->Cell(0, 10, 'Tipo de cuenta no especificado o inválido.', 0, 1);
}

// Datos adicionales del cliente
$pdf->Cell(0, 10, "Nombre Cliente: " . $_POST['nombre_cliente'], 0, 1);
$pdf->Cell(0, 10, "NIT Cliente: " . $_POST['nit_cliente'], 0, 1);
$pdf->Cell(0, 10, "Direccion Cliente: " . $_POST['direccion_cliente'], 0, 1);
$pdf->Cell(0, 10, "Metodo de Pago: " . $_POST['metodo_pago'], 0, 1);

// Guardar y enviar el PDF al navegador
$pdf->Output('I', 'Factura.pdf');
?>
