<?php
include('inc/dbConfig.php'); //connection details
set_time_limit(0);


$outletArr = [
    [31, 136],
    [27, 135],
    [27, 134],
    [27, 133],
    [25, 122],
    [8, 55],
    [8, 136],
    [8, 53],
    [6, 46],
    [5, 39],
    [5, 37],
    [5, 36],
    [4, 31],
    [4, 34],
    [4, 32],
    [3, 44],
    [3, 42],
    [3, 41],
    [31, 136],
    [31, 136]
];

/*$outletArr = [

    [3, 42]

];*/


foreach ($outletArr as $outLetRow) {
    addOutletDataDateWise(1, $outLetRow[0], $outLetRow[1]);

    // echo 'RevId=' . $outLetRow[0] . '=' . $outLetRow[1];

    //echo '<br><br>';
}
