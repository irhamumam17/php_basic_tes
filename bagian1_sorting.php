<?php
$nilai = [72, 65, 73, 78, 75, 74, 90, 81, 87, 65, 55, 69, 72, 78, 79, 91, 100, 40, 67, 77, 86];

// nilai tertinggi
rsort($nilai);
$seven_value = array_slice($nilai, 0, 7);
echo "7 Nilai tertinggi: ";
foreach ($seven_value as $key => $value) {
    echo $value;
    if($key < count($seven_value) - 1) {
        echo ", ";
    }
}

echo "<br>";

// nilai terendah
sort($nilai);
$seven_value = array_slice($nilai, 0, 7);
echo "7 Nilai terendah: ";
foreach ($seven_value as $key => $value) {
    echo $value;
    if($key < count($seven_value) - 1) {
        echo ", ";
    }
}

echo "<br>";

// nilai rata rata
$rata_rata = array_sum($nilai) / count($nilai);
echo "Rata-rata: " . $rata_rata;