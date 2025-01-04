<?php
require_once "../../classes/student.class.php";
require_once "../../classes/utilities.php";

$student = new Student();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the raw POST data
    $json = file_get_contents('php://input');

    // Decode the JSON data
    $data = json_decode($json, true); // Set true to get an associative array
    $amount = clean_input($data["amount"]);
    $orgFeeId = clean_input($data["orgFeeId"]);
    $studentId = clean_input($data["studentId"]);
    $receivedById = clean_input($data["receivedById"]);

    $result = $student->payFee($studentId, $receivedById, $orgFeeId, $amount);

    echo $result;
}
?>