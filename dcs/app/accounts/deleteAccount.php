<?php
session_start(); // Start the session

// Initialize $id
$id = '';

// Check if 'id' is provided in the query string and sanitize it
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input by converting it to an integer
} else {
    echo 'Invalid ID';
    exit;
}

require_once '../../classes/account.class.php';

$obj = new Account();

try {
    // Attempt to delete the account
    if ($obj->delete($id)) {
        $_SESSION['message'] = 'Account deleted successfully!';
        $_SESSION['message_type'] = 'success';

        echo 'success'; // Return success for the fetch call

        exit;
    } else {
        $_SESSION['message'] = 'Failed to delete the account';
        $_SESSION['message_type'] = 'error';

        echo 'error'; // Return error if deletion failed

        exit;
    }
} catch (Exception $e) {
    // Handle exceptions, such as when a professor is assigned to courses
    $_SESSION['message'] = 'Error: ' . $e->getMessage();
    $_SESSION['message_type'] = 'error';
    header('Refresh: 0; url=index.php');
    exit;
}
?>
