<?php

require('../fpdf183/fpdf.php');
$fpdf = new FPDF();

$fpdf->SetFont('Arial');
$fpdf->AddPage('LANDSCAPE','letter');
$fpdf->Write(5, utf8_decode('Página #1'));
$fpdf->AddPage('PORTRAIT','legal');
$fpdf->Write(5, utf8_decode('Página #2'));
$fpdf->AddPage('LANDSCAPE','a5', 180);
$fpdf->Write(5,utf8_decode('Página #3'));

$fpdf->OutPut();