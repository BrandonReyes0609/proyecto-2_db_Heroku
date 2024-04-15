<?php
require('fpdf.php');

require 'includes/conexion.php'; // Incluir el script de conexión desde la carpeta includes
$host = "cb4l59cdg4fg1k.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com";
$database = "dceql5bo9j3plb";
$user = "u1e25j4kkmlge1";
$port = "5432";
$password = "p4ac621d657dad701bc6ed9505ad96894fe1a390fd1e05ef41b37334c60753c5b";

// Crear la cadena de conexión
$dsn = "host=$host port=$port dbname=$database user=$user password=$password";

// Establecer conexión
$conn = pg_connect($dsn);

if (isset($_REQUEST['tipo_cuenta']) && is_numeric($_REQUEST['tipo_cuenta'])) {
    $tipo_cuenta = $_REQUEST['tipo_cuenta'];
    echo("exito");

    $query_cuentas = "SELECT cuentas.cuenta_id,cuentas.mesa_id,cuentas.fecha_apertura,cuentas.fecha_cierre,cuentas.total,items_cuenta.item_id,items_cuenta.cantidad,items_cuenta.fecha_hora,items_cuenta.cocinado,platos.plato_id,platos.nombre,platos.descripcion,platos.precio,platos.tipo,(items_cuenta.cantidad * platos.precio) AS total_item FROM cuentas INNER JOIN items_cuenta ON cuentas.cuenta_id = items_cuenta.cuenta_id INNER JOIN platos ON items_cuenta.item_id = platos.plato_id WHERE cuentas.cuenta_id = $tipo_cuenta";
    $query_total_cuenta = "SELECT cuentas.cuenta_id, SUM(items_cuenta.cantidad * platos.precio) AS sumatoria_total_items FROM cuentas INNER JOIN items_cuenta ON cuentas.cuenta_id = items_cuenta.cuenta_id INNER JOIN platos ON items_cuenta.item_id = platos.plato_id WHERE cuentas.cuenta_id = $tipo_cuenta GROUP BY cuentas.cuenta_id";
    $query_cerrar_cuenta = "UPDATE cuentas SET fecha_cierre = NOW() WHERE cuenta_id = $1";
    //$resultado1 = pg_query_params($conn, $query_cuentas, array($tipo_cuenta));
    $consulta_pedidos1 = pg_query($conn,$query_cuentas);
    $consulta_pedidos2 = pg_query($conn,$query_total_cuenta);

    $resultado_query_cerrar_cuenta  = pg_query_params($conn, $query_cerrar_cuenta, array($tipo_cuenta));
    if ($resultado_query_cerrar_cuenta) {
        $_SESSION['user_alert'] = "Se enviaron los datos correctamente";
    } else {
        $_SESSION['user_alert'] = "Error resultado 2: " . pg_last_error($conn);
    }
    
} else {
    // Manejo de error si 'tipo_cuenta' no está presente o no es válido
    echo "Tipo de cuenta no especificado o inválido.";
    $tipo_cuenta = null; // Asegurarse de que no se proceda con un valor inválido
}


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