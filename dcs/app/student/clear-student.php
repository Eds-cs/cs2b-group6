<?php
require_once "../../classes/student.class.php";
require_once "../../classes/utilities.php";

$student = new Student();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the raw POST data
    $json = file_get_contents('php://input');

    // Decode the JSON data
    $data = json_decode($json, true); // Set true to get an associative array
    $orgId = clean_input($data["orgId"] ?? '');
    $roleId = clean_input($data["roleId"] ?? '');
    $studentId = clean_input($data["studentId"]);
    $approverId = clean_input($data["approverId"]);

    $result = $student->clearStudent($studentId, $approverId, $roleId, $orgId);

    echo $result;
}
?>