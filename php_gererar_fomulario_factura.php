<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'includes/conexion.php';  // Incluir el script de conexión
require('fpdf.php');  // Incluir la librería FPDF para la creación del PDF

// Crear instancia de la clase FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

if (isset($_POST['tipo_cuenta']) && is_numeric($_POST['tipo_cuenta'])) {
    $tipo_cuenta = $_POST['tipo_cuenta'];

    // Consultas para obtener los datos de la cuenta y los ítems
    $query_cuentas = "SELECT cuentas.cuenta_id, cuentas.mesa_id, cuentas.fecha_apertura, cuentas.fecha_cierre, cuentas.total, items_cuenta.item_id, items_cuenta.cantidad, items_cuenta.fecha_hora, items_cuenta.cocinado, platos.plato_id, platos.nombre, platos.descripcion, platos.precio, platos.tipo, (items_cuenta.cantidad * platos.precio) AS total_item FROM cuentas INNER JOIN items_cuenta ON cuentas.cuenta_id = items_cuenta.cuenta_id INNER JOIN platos ON items_cuenta.item_id = platos.plato_id WHERE cuentas.cuenta_id = $tipo_cuenta";

    $consulta_pedidos = pg_query($conn, $query_cuentas);

    // Agregar datos al PDF
    $pdf->Cell(0, 10, 'Detalle de Pedidos:', 0, 1);
    while ($row = pg_fetch_assoc($consulta_pedidos)) {
        $pdf->Cell(0, 10, "Plato: " . $row['nombre'] . " - Cantidad: " . $row['cantidad'] . " - Precio Unitario: $" . $row['precio'] . " - Subtotal: $" . $row['total_item'], 0, 1);
    }

    // Consulta para obtener el total de la cuenta
    $query_total_cuenta = "SELECT SUM(items_cuenta.cantidad * platos.precio) AS sumatoria_total_items FROM cuentas INNER JOIN items_cuenta ON cuentas.cuenta_id = items_cuenta.cuenta_id INNER JOIN platos ON items_cuenta.item_id = platos.plato_id WHERE cuentas.cuenta_id = $tipo_cuenta GROUP BY cuentas.cuenta_id";
    $total_result = pg_query($conn, $query_total_cuenta);
    $total = pg_fetch_result($total_result, 0, 'sumatoria_total_items');

    // Agregar total al PDF
    $pdf->Cell(0, 10, "Total: $" . $total, 0, 1);
} else {
    $pdf->Cell(0, 10, 'Tipo de cuenta no especificado o inválido.', 0, 1);
}

// Datos adicionales del cliente (podrían venir de un formulario HTML)
$pdf->Cell(0, 10, "Nombre Cliente: " . $_POST['nombre_cliente'], 0, 1);
$pdf->Cell(0, 10, "NIT Cliente: " . $_POST['nit_cliente'], 0, 1);
$pdf->Cell(0, 10, "Direccion Cliente: " . $_POST['direccion_cliente'], 0, 1);
$pdf->Cell(0, 10, "Metodo de Pago: " . $_POST['metodo_pago'], 0, 1);

// Guardar y enviar el PDF al navegador
$pdf->Output('I', 'Factura.pdf');
?>