<?php
// init variables
$letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
// split letter to array
$array_letters = str_split($letters);
// input string to encrypt
$input_string = "DFHKNQ";
// split input string to array
$array_input_string = str_split($input_string);
// init output string
$output_string = "";
// loop input string
foreach ($array_input_string as $key => $letter) {
    // get index of letter
    $index = array_search($letter, $array_letters);
    // add letter to output string
    if($key == 0) {
        $output_string .= $array_letters[$index + 1];
    }else if($key == 1) {
        $output_string .= $array_letters[$index - 2];
    }else if($key == 2) {
        $output_string .= $array_letters[$index + 3];
    }else if($key == 3) {
        $output_string .= $array_letters[$index - 4];
    }else if($key == 4) {
        $output_string .= $array_letters[$index + 5];
    }else if($key == 5) {
        $output_string .= $array_letters[$index - 6];
    }
}
// output result
echo $output_string;
?>