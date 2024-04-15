<?php

    //require_once 'assets/tcpdf/tcpdf.php';  // Ruta correcta a la librería TCPDF

    require('fpdf.php');
     
    $pdf=new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(40,10,'Hello World!');
    $pdf->Output();

 
?>
