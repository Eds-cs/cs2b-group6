<?php
session_start();

if (isset($_GET['id'])) {
    $orgId = $_GET['id'];

    require_once '../../classes/organization.class.php';
    $orgObj = new Organization();

    try {
        // Delete fees associated with the organization
        if ($orgObj->deleteFees($orgId)) {
            echo 'success'; // Return success if fees were deleted
        } else {
            echo 'error_deleting_fees'; // Failure response
        }
    } catch (Exception $e) {
        echo 'error'; // Handle any exceptions or errors
    }
}
?>
