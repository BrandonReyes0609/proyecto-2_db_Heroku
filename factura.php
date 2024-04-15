<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once 'includes/conexion.php'; // Asegúrate de que este archivo tiene la conexión correcta a PostgreSQL
require_once 'assets/tcpdf/tcpdf.php';  // Asegúrate de que la ruta es correcta

if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: index.php");
    exit;
}

// Crear nueva instancia PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Grupo 12');
$pdf->SetTitle('Factura');
$pdf->SetSubject('Factura Detallada');
$pdf->SetKeywords('TCPDF, PDF, factura');

// Cabecera y Pie de página
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->SetFont('dejavusans', '', 10);

$pdf->AddPage();

// Consulta para obtener los datos de la factura
$cuenta_id = $_GET['cuenta_id'];  // Asume que el ID de la cuenta viene como parámetro GET

// Asegura que la cuenta está cerrada antes de generar la factura
$queryCuenta = "SELECT * FROM cuentas WHERE cuenta_id = '$cuenta_id' AND estado = 'cerrada'";
$resultCuenta = pg_query($conexion, $queryCuenta);

if (pg_num_rows($resultCuenta) > 0) {
    $datosCuenta = pg_fetch_assoc($resultCuenta);

    $html = '<h1>Factura de la Cuenta: ' . $datosCuenta['cuenta_id'] . '</h1>';
    $html .= '<h2>Datos del Cliente</h2>';
    $html .= '<p><strong>Nombre:</strong> ' . $datosCuenta['nombre_cliente'] . '</p>';
    $html .= '<p><strong>Dirección:</strong> ' . $datosCuenta['direccion'] . '</p>';
    $html .= '<p><strong>Teléfono:</strong> ' . $datosCuenta['telefono'] . '</p>';

    // Asumiendo que la forma de pago puede ser múltiple y está guardada en otra tabla relacionada
    $html .= '<h2>Formas de Pago</h2>';
    $queryPagos = "SELECT forma_pago, monto FROM pagos WHERE cuenta_id = '$cuenta_id'";
    $resultPagos = pg_query($conexion, $queryPagos);
    while ($pago = pg_fetch_assoc($resultPagos)) {
        $html .= '<p>' . $pago['forma_pago'] . ': ' . $pago['monto'] . '€</p>';
    }

    $pdf->writeHTML($html, true, false, true, false, '');
} else {
    $pdf->Write(0, 'La cuenta no está cerrada o no existe.', '', 0, 'C', true, 0, false, false, 0);
}

// Cerrar y mostrar el documento
$pdf->Output('factura_' . $cuenta_id . '.pdf', 'I');
?>
