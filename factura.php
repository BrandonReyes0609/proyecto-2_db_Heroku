<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once 'includes/conexion.php'; // Ruta correcta a la conexión de la base de datos
require_once 'assets/tcpdf/tcpdf.php';  // Ruta correcta a la librería TCPDF

if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: index.php");
    exit;
}


$pdf = new TCPDF();

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Grupo 12');
$pdf->SetTitle('Factura');
$pdf->SetSubject('Factura Detallada');
$pdf->SetKeywords('TCPDF, PDF, factura');

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

if (isset($_GET['cuenta_id'])) {
    $cuenta_id = $_GET['cuenta_id'];

    // Usamos una declaración preparada para la seguridad
    $stmt = $conn->prepare("SELECT c.*, cl.nombre AS cliente_nombre, cl.nit, cl.direccion FROM cuentas c
                             JOIN cliente cl ON c.cuenta_id = cl.cuenta_id
                             WHERE c.cuenta_id = :cuenta_id");
    $stmt->execute([':cuenta_id' => $cuenta_id]);
    
    if ($stmt->rowCount() > 0) {
        $datosCuenta = $stmt->fetch(PDO::FETCH_ASSOC);

        $html = '<h1>Factura de la Cuenta: ' . htmlspecialchars($cuenta_id) . '</h1>';
        $html .= '<h2>Datos del Cliente</h2>';
        $html .= '<p><strong>Nombre:</strong> ' . htmlspecialchars($datosCuenta['cliente_nombre']) . '</p>';
        $html .= '<p><strong>NIT:</strong> ' . htmlspecialchars($datosCuenta['nit']) . '</p>';
        $html .= '<p><strong>Dirección:</strong> ' . htmlspecialchars($datosCuenta['direccion']) . '</p>';

        $html .= '<h2>Detalle de la Cuenta</h2>';
        $html .= '<table border="1" cellpadding="4">';
        $html .= '<tr>
                    <th>Plato</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                  </tr>';

        $stmtItems = $conn->prepare("SELECT pl.nombre, pl.precio, ic.cantidad, (pl.precio * ic.cantidad) AS total_item 
                                      FROM items_cuenta ic
                                      JOIN platos pl ON ic.plato_id = pl.plato_id
                                      WHERE ic.cuenta_id = :cuenta_id");
        $stmtItems->execute([':cuenta_id' => $cuenta_id]);

        while ($item = $stmtItems->fetch(PDO::FETCH_ASSOC)) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($item['nombre']) . '</td>
                        <td>' . htmlspecialchars($item['cantidad']) . '</td>
                        <td>' . htmlspecialchars($item['precio']) . '</td>
                        <td>' . htmlspecialchars($item['total_item']) . '</td>
                      </tr>';
        }

        $html .= '</table>';

        $html .= '<h2>Formas de Pago</h2>';
        $html .= '<table border="1" cellpadding="4">';
        $html .= '<tr>
                    <th>Método de Pago</th>
                    <th>Cantidad Pagada</th>
                  </tr>';

        $stmtPagos = $conn->prepare("SELECT metodo, cantidad_pagada FROM pago_cuenta WHERE cuenta_id = :cuenta_id");
        $stmtPagos->execute([':cuenta_id' => $cuenta_id]);

        $totalPago = 0;
        while ($pago = $stmtPagos->fetch(PDO::FETCH_ASSOC)) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($pago['metodo']) . '</td>
                        <td>' . htmlspecialchars($pago['cantidad_pagada']) . '</td>
                      </tr>';
            $totalPago += $pago['cantidad_pagada'];
        }

        $html .= '<tr>
                    <td><strong>Total Pagado</strong></td>
                    <td><strong>' . htmlspecialchars(number_format($totalPago, 2)) . '</strong></td>
                  </tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');
    } else {
        $pdf->Write(0, 'La cuenta no está cerrada o no existe.', '', 0, 'C', true, 0, false, false, 0);
    }
} else {
    $pdf->Write(0, 'El ID de la cuenta no está definido.', '', 0, 'C', true, 0, false, false, 0);
}

$pdf->Output('factura_' . $cuenta_id . '.pdf', 'I');

?>
