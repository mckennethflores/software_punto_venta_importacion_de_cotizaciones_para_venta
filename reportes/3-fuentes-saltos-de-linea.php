<?php

require('../fpdf183/fpdf.php');
$fpdf = new FPDF();

$fpdf->AddPage('PORTRAIT', 'letter');
$fpdf->SetFont('Arial','',14);
$fpdf->Cell(75,5,utf8_decode('Esta linea es aralt tamaño de letra 14'));
$fpdf->Ln();
$fpdf->SetFont('HELVETICA', 'B', 12);
$fpdf->Write(5,utf8_decode('Esta linea está en helvetica, tamaño de letra 12'));

$fpdf->OutPut();