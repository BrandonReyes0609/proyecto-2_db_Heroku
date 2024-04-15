
<?php
require('fpdf.php');

// Crear instancia de la clase FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Configurar la fuente
$pdf->SetFont('Arial', 'B', 16);

// Recibir los datos del formulario
$cuenta_id = $_POST['tipo_cuenta'];
$tipo_cuenta = $_POST['tipo_cuenta'];
$nombre_cliente = $_POST['nombre_cliente'];
$nit_cliente = $_POST['nit_cliente'];
$direccion_cliente = $_POST['direccion_cliente'];
$metodo_pago = $_POST['metodo_pago'];

// Agregar los datos al PDF
$pdf->Cell(0, 10, "Cuenta ID: $cuenta_id", 0, 1);
$pdf->Cell(0, 10, "Tipo de Cuenta: $tipo_cuenta", 0, 1);
$pdf->Cell(0, 10, "Nombre Cliente: $nombre_cliente", 0, 1);
$pdf->Cell(0, 10, "NIT Cliente: $nit_cliente", 0, 1);
$pdf->Cell(0, 10, "Direccion Cliente: $direccion_cliente", 0, 1);
$pdf->Cell(0, 10, "Metodo de Pago: $metodo_pago", 0, 1);

// Guardar y enviar el PDF al navegador
$pdf->Output('I', 'Factura.pdf');
?>
