<?php

require('../fpdf183/fpdf.php');
$fpdf = new FPDF();

$fpdf->AddPage('PORTRAIT', 'letter');


$fpdf->SetFont('arial','B',14);
$fpdf->Write(5,utf8_decode('Empleados Registrados en la Aplicación Movil'));
$fpdf->Ln(8);

$fpdf->SetFont('arial','',10);
$fpdf->Cell(22,5,'CLIENTE',1,0,'C', false);
$fpdf->Cell(102,5,'RINTI S A', 1, 0, 'L', false);
$fpdf->Cell(14,5, utf8_decode('RUC'),1,0, 'C', false);
$fpdf->Cell(50,5, utf8_decode('20100617332'),1,0, 'R', false);


$fpdf->SetFont('arial','', 9);
$fpdf->Ln(5);
$fpdf->Cell(22, 5, utf8_decode('DIRECCIÓN:'), 1, 0, false);
$fpdf->Cell(166, 5,utf8_decode('AV. NICOLAS AYLLONC.CENTRAL FND. PARIACHI KM. 17.5 LIMA - LIMA - ATE - - -'),1, 0, false);

$fpdf->Ln(5);
$fpdf->SetFont('arial','',9);
$fpdf->Cell(17,5,'FECHA:',1,0,'C', false);
$fpdf->Cell(20,5,'01/03/2022', 1, 0, 'L', false);
$fpdf->Cell(20,5, utf8_decode('MONEDA:'),1,0, 'C', false);
$fpdf->Cell(15,5, utf8_decode('DOLAR:'),1,0, 'R', false);
$fpdf->Cell(37,5, utf8_decode('CONDICIONES PAGO:'),1,0, 'R', false);
$fpdf->Cell(31,5, utf8_decode('CRÉDITO 30 DÍAS'),1,0, 'L', false);
$fpdf->Cell(23,5, utf8_decode('TELÉFONOS:'),1,0, 'L', false);
$fpdf->Cell(25,5, utf8_decode('938222552'),1,0, 'L', false);

$fpdf->Ln(5);
$fpdf->SetFont('arial','',10);
$fpdf->Cell(35,5,'SOLICITADO POR:',1,0,'C', false);
$fpdf->Cell(65,5,'DIEGO PAREDES', 1, 0, 'L', false);
$fpdf->Cell(43,5, utf8_decode('TIEMPO DE ENTREGA:'),1,0, 'C', false);
$fpdf->Cell(45,5, utf8_decode('15 DÍAS'),1,0, 'C', false);

//$fpdf->OutPut('I','Los Indestructibles.pdf');
$file_content = $fpdf->OutPut('S','empleados.pdf',false);
header('Content-type:application/pdf');
echo $file_content;