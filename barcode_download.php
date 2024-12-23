<?php
//include('inc/dbConfig.php'); //connection details


// Include autoloader 
require_once 'dompdf/autoload.inc.php';

// Reference the Dompdf namespace 
use Dompdf\Dompdf;

// Instantiate and use the dompdf class 
$dompdf = new Dompdf();

$rtlPdfFile =  'barCodePdfGenerator.php';

include($rtlPdfFile);

// Load content from html file 
//$html = file_get_contents("http://localhost/queue1/devnew/history_final_pdf.php");

// Add this line of code to show image
$dompdf->set_option('isRemoteEnabled', true); // By default isRemoteEnable is false that's why image is not shown.

$dompdf->loadHtml($content);

// (Optional) Setup the paper size and orientation 
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF 
$dompdf->render();
$date = date('d-m-y-h-i-s');


// Output the generated PDF (1 = download and 0 = preview) 
$dompdf->stream("stock_pdf('" . $date . "').pdf", array("Attachment" => 0));
