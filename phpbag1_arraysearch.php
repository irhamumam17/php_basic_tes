<?php
function search($arr, $string){
    $array_string = str_split($string);

    foreach($arr as $key => $value){
        foreach($value as $key2 => $value2){
            if($value2 == $array_string[0]){
                if($value[$key2 + 1] == $array_string[1]){
                    if($value[$key2 + 2] == $array_string[2]){
                        if($value[$key2 + 3] == $array_string[3]){
                            return true;
                        }
                    }
                }
            }else{
                return false;
            }
        }
    }
}
$arr = [
    ['f', 'g', 'h', 'i'],
    ['j', 'k', 'p', 'q'],
    ['r', 's', 't', 'u'],
];
echo search($arr, 'fghp') ? 'true' : "false";
?>