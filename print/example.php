<?php

/** example of use of printable_labels_pdf_class
 * 
 * @author Rafael Martin Soto
 * @author {@link http://www.inatica.com/ Inatica}
 * @blog {@link https://rafamartin10.blogspot.com/ Rafa Martin's blog}
 * @since August 2021
 * @version 1.0.0
 * @license GNU General Public License v3.0
 */

require_once __DIR__ . '/printable_labels_pdf_class.php';



$labels_config = [];

$labels_config['page_format']			= 'A4'; // Page format
$labels_config['page_orientation']		= 'P'; // Orientation 'L'andscape 'P'ortrait

$labels_config['width_label']			= 3;
$labels_config['height_label']			= 0.5;

$labels_config['num_cols']				= 1;
$labels_config['num_rows']				= 4;

$labels_config['margin_left_page']		= 0;
$labels_config['margin_top_page']		= 0;

$labels_config['margin_left_label']		= 0;
$labels_config['margin_bottom_label']	= 0;

$labels_config['padding_left_label']	= 0;
$labels_config['padding_top_label']		= 0;

$labels_config['skip_first_row']		= true;
$labels_config['skip_last_row']			= true;

$labels_config['default_font']			= 'Times';
$labels_config['default_font_size']		= 9;

$labels_config['begin_at_label_num']	= 1; // Defaults begin with label num. 1


// Create an Instance of printable_labels_pdf()
$printable_labels_pdf = new printable_labels_pdf($labels_config);

// Set draw borders to true
//$printable_labels_pdf->draw_border(true);

// Generate 2.499 Labels
//for ($i = 1; $i < 12; $i++) {

// Make a string of the html label
$label_html  = '<div align="center">
<img src="img/image1.png"  style="border:none; outline: none;" width="188.98" height="94.49"><br><br>
<img src="img/image1.png"  style="border:none; outline: none;" width="188.98" height="94.49"><br><br>
<img src="img/image1.png"  style="border:none; outline: none;" width="188.98" height="94.49"><br><br>
<img src="img/image1.png"  style="border:none; outline: none;" width="188.98" height="94.49"><br><br>
</div>
'; // 1st row. Bold

// send the html string to a new label
$printable_labels_pdf->write_label($label_html);
//}

// Generate Pdf file
$printable_labels_pdf->get_labels_pdf(); // Output a PDF file directly to the browser

/*

a) Show directly in the browser (default)

$printable_labels_pdf->get_labels_pdf('test.pdf', 'I'); // Output a PDF file directly to the browser



b) Get a String of the pdf to do something with it later:

$SomeVarPdfString = $printable_labels_pdf->get_labels_pdf('test.pdf', 'S'); // Get pdf in string format and assign to $SomeVarPdfString



c) Download
$printable_labels_pdf->get_labels_pdf('test.pdf', 'D'); // Download pdf



d) Save pdf file in server path
$printable_labels_pdf->get_labels_pdf('/some_dir/test.pdf', 'F'); // Save pdf in some dir of the server

*/
