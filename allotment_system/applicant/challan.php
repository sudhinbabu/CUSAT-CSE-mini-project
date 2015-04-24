<?php
session_start();
require_once '../lib/db_class.php';
require_once '../lib/functions.php';
require_once '../lib/fpdf/fpdf.php';
$db= new Database();


$result = $db->query('SELECT challan_no FROM applications WHERE user_id = "'.get_user_id().'"');
$user = mysqli_fetch_array($result,MYSQLI_ASSOC);
$challan_no = $user['challan_no'];
$result = $db->query('SELECT * FROM users WHERE user_id = "'.get_user_id().'"');
$user = mysqli_fetch_array($result,MYSQLI_ASSOC);
$result = $db->query('SELECT * FROM settings WHERE label="APPLICATION_FEE"');
$settings = mysqli_fetch_array($result,MYSQLI_ASSOC);
define('APPLICATION_FEE', $settings['value']);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',24);
 $pdf->Ln();
$pdf->Cell(50,10,'STATE BANK OF INDIA');
 $pdf->Ln();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Students Copy');
 $pdf->Ln();
$pdf->Cell(0,10,'Applicant name:'.$user['name']);
 $pdf->Ln();
$pdf->Cell(0,10,'challan_no:'.$challan_no);
 $pdf->Ln();
$pdf->Cell(0,10,'amount:'.$amount=APPLICATION_FEE);
 $pdf->Ln();

 $pdf->SetFont('Arial','B',24);
 $pdf->Ln();
$pdf->Cell(50,10,'STATE BANK OF INDIA');
 $pdf->Ln();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Bank Copy');
 $pdf->Ln();
$pdf->Cell(0,10,'Applicant name:'.$user['name']);
 $pdf->Ln();
$pdf->Cell(0,10,'challan_no:'.$challan_no);
 $pdf->Ln();
$pdf->Cell(0,10,'amount:'.$amount=APPLICATION_FEE);
 $pdf->Ln();


 $pdf->SetFont('Arial','B',24);
 $pdf->Ln();
$pdf->Cell(50,10,'STATE BANK OF INDIA');
 $pdf->Ln();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'College Copy');
 $pdf->Ln();
$pdf->Cell(0,10,'Applicant name:'.$user['name']);
 $pdf->Ln();
$pdf->Cell(0,10,'challan_no:'.$challan_no);
 $pdf->Ln();
$pdf->Cell(0,10,'amount:'.$amount=APPLICATION_FEE);
 $pdf->Ln();


$pdf->Output();
?>