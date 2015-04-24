<?php

require_once '../lib/fpdf/fpdf.php';


$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',24);
 $pdf->Ln();
$pdf->Cell(50,10,'ALLOTMENT DETAILS');
 $pdf->Ln();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Applicant name:'.$_GET['applicant_name']);
 $pdf->Ln();
$pdf->Cell(0,10,'course:'.$_GET['course_name']);
 $pdf->Ln();
$pdf->Cell(0,10,'applicant_id:'.$_GET['user_id']);
 $pdf->Ln();

$pdf->Output();
?>