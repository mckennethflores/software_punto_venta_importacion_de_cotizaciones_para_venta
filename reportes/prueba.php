<?php

require('../fpdf183/fpdf.php');
$fpdf = new FPDF();
$fpdf->AddPage();

$fpdf->SetFont('Arial','',14);
$fpdf->Cell(30, 5, 'Hola Mckenneth Elmer Oswaldo');
$fpdf->AddPage();
$fpdf->Write(5, 'Hola Mundo otra caquis');
$fpdf->OutPut();