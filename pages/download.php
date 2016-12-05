<?php
	/** Include PHPExcel */
	require_once dirname(__FILE__) . '../../Excel/PHPExcel_1.8.0_doc/Classes/PHPExcel.php';


	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

if(isset($_GET["dwn"])) {
	$filename = 'Excel/'.$_GET ["dwn"];

// Entête pour Ouvrir avec MSExcel

header("content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".$filename);

flush(); // Envoie le buffer
readfile($filename); // Envoie le fichier

	
	
//	$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
//    $writer->save('php://output');



}?> 