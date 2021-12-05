<?php
$input = "Muhammad Irhamul Umam";
$n_lowercase = preg_replace('/[^a-z]/', '', $input);
echo "\"$input\" mengandung ".mb_strlen($n_lowercase). " buah huruf kecil";
?>