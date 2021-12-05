<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table</title>
    <style>
        table {
            border-collapse: collapse;
            width: 25%;
        }
        td, th {
            border: 1px solid #dddddd;
            text-align: center;
            padding: 8px;
        }
    </style>
</head>

<body>
    <table>
        <?php
        for ($i = 1; $i <= 64; $i++) {
            if ($i - 1 % 8 == 0) {
                echo "<tr style='height: 20px'>";
            }
            if ($i % 3 == 0 || $i % 4 == 0) {
                echo "<td style='background-color:white; color: black; width: 10px;'>" . $i . "</td>";
            } else {
                echo "<td style='background-color:black; color: white; width: 10px;'>" . $i . "</td>";
            }
            if ($i % 8 == 0) {
                echo "</tr>";
            }
        }
        ?>
    </table>
</body>

</html>