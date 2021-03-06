<?php
require_once '../db.php';
require_once '../checksession.php';
require_once '../PHPSpreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Item Name');
$sheet->setCellValue('B1', 'Quantity Required');
$sheet->setCellValue('C1', 'Price');
$sheet->setCellValue('D1', 'Total Price');
if ($_SESSION['level'] == 1) {
    $sheet->setCellValue('E1', 'Lab');
    $sheet->setCellValue('F1', 'Specifications');
} else {
    $sheet->setCellValue('E1', 'Specifications');
}


$sheet->getStyle("A1:E1")->getFont()->setBold(true);

if ($_SESSION['level'] == 1) {
    $sql = "select item_name, specs, min_quantity, quantity, price, lab from item where min_quantity>quantity;";
} else {
    $sql = "select item_name, specs, min_quantity, quantity, price from item where min_quantity>quantity and lab='" . $_SESSION['lab'] . "';";
}
$result = $conn->query($sql);

$conn->close();

$row = "A";
$col = "2";

if ($result->num_rows != 0) {
    while ($info = $result->fetch_assoc()) {
        $sheet->setCellValue($row . $col, $info['item_name']);
        $row = chr(ord($row) + 1);
        $sheet->setCellValue($row . $col, ($info['min_quantity'] - $info['quantity']));
        $row = chr(ord($row) + 1);
        $sheet->setCellValue($row . $col, $info['price']);
        $row = chr(ord($row) + 1);
        $sheet->setCellValue($row . $col, "=B" . $col . "*C" . $col);
        $row = chr(ord($row) + 1);
        if ($_SESSION['level'] == 1) {
            $sheet->setCellValue($row . $col, $info['lab']);
            $row = chr(ord($row) + 1);
        }
        $sheet->setCellValue($row . $col, $info['specs']);

        $row = "A";
        $col = (string) ((int) $col + 1);
    }
}

$sheet->setCellValue("C" . $col, "Total Amount:");
$sheet->getStyle("C" . $col)->getFont()->setBold(true);
$sheet->setCellValue("D" . $col, "=sum(D2:D" . ($col - 1) . ")");

foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$save_name;
$writer = new Xlsx($spreadsheet);
if ($_SESSION['level'] == 1) {
    $save_name = "../excel/" . date("Y m d") . " HOD Stock Alert.xlsx";
} else {
    $save_name = "../excel/" . date("Y m d") . " " . $_SESSION['labname'] . " Stock Alert.xlsx";
}
$writer->save($save_name);
echo $save_name;
