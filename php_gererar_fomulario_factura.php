<?php
require('fpdf.php');
session_start();

// Configuración de la conexión desde un archivo centralizado
require 'includes/conexion.php'; 

if (isset($_REQUEST['tipo_cuenta']) && is_numeric($_REQUEST['tipo_cuenta'])) {
    $tipo_cuenta = $_REQUEST['tipo_cuenta'];

    // Consultas para obtener los datos necesarios para el reporte
    $query_cuentas = "SELECT cuentas.cuenta_id, cuentas.mesa_id, cuentas.fecha_apertura, cuentas.fecha_cierre, cuentas.total, items_cuenta.item_id, items_cuenta.cantidad, items_cuenta.fecha_hora, items_cuenta.cocinado, platos.plato_id, platos.nombre, platos.descripcion, platos.precio, platos.tipo, (items_cuenta.cantidad * platos.precio) AS total_item FROM cuentas INNER JOIN items_cuenta ON cuentas.cuenta_id = items_cuenta.cuenta_id INNER JOIN platos ON items_cuenta.item_id = platos.plato_id WHERE cuentas.cuenta_id = $tipo_cuenta";
    $query_total_cuenta = "SELECT SUM(items_cuenta.cantidad * platos.precio) AS sumatoria_total_items FROM cuentas INNER JOIN items_cuenta ON cuentas.cuenta_id = items_cuenta.cuenta_id INNER JOIN platos ON items_cuenta.item_id = platos.plato_id WHERE cuentas.cuenta_id = $tipo_cuenta";

    // Ejecutar las consultas
    $consulta_pedidos1 = pg_query($conn, $query_cuentas);
    $consulta_pedidos2 = pg_query($conn, $query_total_cuenta);

    // Verificar éxito de la consulta antes de cerrar la cuenta
    if (pg_num_rows($consulta_pedidos1) > 0 && pg_num_rows($consulta_pedidos2) > 0) {
        // Cierra la cuenta actualizando el campo 'fecha_cierre'
        $query_cerrar_cuenta = "UPDATE cuentas SET fecha_cierre = NOW() WHERE cuenta_id = $1";
        $resultado_query_cerrar_cuenta = pg_query_params($conn, $query_cerrar_cuenta, array($tipo_cuenta));

        if ($resultado_query_cerrar_cuenta) {
            $_SESSION['user_alert'] = "Se enviaron los datos correctamente";
        } else {
            $_SESSION['user_alert'] = "Error resultado 2: " . pg_last_error($conn);
        }

        // Generar PDF si todas las consultas son exitosas
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
            foreach ($header as $col) {
                $pdf->Cell(24, 6, $row[strtolower(str_replace(' ', '_', $col))], 1);
            }
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
    } else {
        echo "No se encontraron datos para la cuenta especificada.";
    }
} else {
    // Manejo de error si 'tipo_cuenta' no está presente o no es válido
    echo "Tipo de cuenta no especificado o inválido.";
    $tipo_cuenta = null; // Asegurarse de que no se proceda con un valor inválido
}

// Cierra la conexión a la base de datos
pg_close($conn);

?>