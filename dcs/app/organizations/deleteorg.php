<?php
session_start();

if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'deleteOrg') {
    $orgId = $_GET['id'];

    require_once '../../classes/organization.class.php';
    $orgObj = new Organization();

    try {
        // First, delete fees associated with the organization
        $deleteFeesSuccess = $orgObj->deleteFees($orgId);
        if (!$deleteFeesSuccess) {
            echo 'error_deleting_fees';
            exit;
        }

        // Now delete the organization
        if ($orgObj->delete($orgId)) {
            echo 'success'; // Return success if both actions are successful
        } else {
            echo 'error_deleting_organization'; // If organization deletion fails
        }
    } catch (Exception $e) {
        echo 'error'; // Handle any exceptions or errors
    }
}
?>
