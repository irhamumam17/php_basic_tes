<?php
// input
$input = "Jakarta adalah ibukota negara Republik Indonesia";

// string to array
$input_array = explode(' ', $input);

// split array
$unigram = array_map(function ($item) {
    return $item;
}, array_chunk($input_array, 1));
$bigram = array_map(function ($item) {
    return $item;
}, array_chunk($input_array, 2));
$trigram = array_map(function ($item) {
    return $item;
}, array_chunk($input_array, 3));

// print
echo "Unigram : ". implode(', ', array_map(function ($item) {
    return implode(' ', $item);
}, $unigram));
echo "<br>";
echo "Bigram : ". implode(', ', array_map(function ($item) {
    return implode(' ', $item);
}, $bigram));
echo "<br>";
echo "Trigram : ". implode(', ', array_map(function ($item) {
    return implode(' ', $item);
}, $trigram));