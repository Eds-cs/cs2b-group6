<?php
require_once 'C:\xampp\htdocs\dcs\classes\account.class.php';
require_once 'C:\xampp\htdocs\dcs\classes\utilities.php';


$account_id = $first_name = $last_name = $middle_initial = $email = $password = $created_at = $updated_at = $role_id = $is_active = '';
$account_idErr = $first_nameErr = $last_nameErr = $middle_initialErr = $emailErr = $passwordErr = $role_idErr = '';
$accObj = new Account();

// Initialize $record to null
$record = null;

// Handle GET request for fetching account details  
if (($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['id'])) {
    $account_id = $_GET['id'];
    $record = $accObj->fetchRecordID($account_id);

    if ($record) {
        // Populate form fields with account data
        $first_name = $record['first_name'];
        $last_name = $record['last_name'];
        $middle_initial = $record['middle_initial'];
        $email = $record['email'];
        $password = $record['password']; // For display purposes, you might want to keep this empty.
        $role_id = $record['role_id'];
        $is_active = $record['is_active'];
    } else {
        echo 'No account found';
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Clean input data
    $first_name = clean_input($_POST['first_name']);
    $last_name = clean_input($_POST['last_name']);
    $middle_initial = isset($_POST['middle_initial']) ? clean_input($_POST['middle_initial']) : '';
    $email = clean_input($_POST['email']);
    $password = clean_input($_POST['password']);
    $role_id = isset($_POST['role']) ? clean_input($_POST['role']) : '';
    $is_active = isset($_POST['is_active']) ? clean_input($_POST['is_active']) : 0;


    // Validation
    if (empty($first_name)) {
        $first_nameErr = 'Enter valid name';
    }
    if (empty($last_name)) {
        $last_nameErr = 'Enter valid name';
    }
    if (empty($email)) {
        $emailErr = 'Enter valid email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = 'Invalid email format';
    }

    if (empty($password)) {
        $passwordErr = 'Enter a password';
    }
    if (empty($role_id)) {
        $role_idErr = 'Choose a role';
    }

    $account_id = clean_input($_POST['account_id']); // Get account ID from POST

    // Fetch the record to check if it exists before updating
    $record = $accObj->fetchRecordID($account_id);
    if ($record) {
        if (empty($password)) {
            $password = $record['password']; // Retain existing password
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
        }

        if (empty($first_nameErr) && empty($last_nameErr) && empty($emailErr)) {
            // Prepare the object for update
            $accObj->account_id = $account_id;
            $accObj->first_name = $first_name;
            $accObj->last_name = $last_name;
            $accObj->middle_initial = $middle_initial;
            $accObj->email = $email;
            $accObj->password = $password;
            $accObj->role_id = $role_id;
            $accObj->updated_at = date('Y-m-d H:i:s');
            $accObj->is_active = $is_active;

            if ($accObj->editAccount()) {
                $_SESSION['success'] = 'Account updated successfully!';
                header('Location:../index.php'); // Redirect to avoid resubmission
                exit;
            }
        }
    } else {
        $account_idErr = 'Account not found';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <!-- Bootstrap CSS from CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />

    <!-- jQuery from CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap JS and Bundle (with Popper) from CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

</head>

<body>

    <!-- Check if account record was found before showing the form -->
    <?php if ($record): ?>
        <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class="col-12 col-md-6">
                <h1>Edit Account</h1>
                <form method="POST">
                    <input type="hidden" name="account_id" value="<?= $account_id ?>">
                    <div class="mb-3">
                        <label for="FName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="FName" name="first_name" value="<?= $first_name ?>">
                    </div>
                    <div class="mb-3">
                        <label for="LName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="LName" name="last_name" value="<?= $last_name ?>">
                    </div>
                    <div class="mb-3">
                        <label for="MName" class="form-label">Middle Initial</label>
                        <input type="text" class="form-control" id="MName" name="middle_initial"
                            value="<?= $middle_initial ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-control">
                            <option value="">Choose role</option>
                            <option value="6" <?= $role_id == 6 ? 'selected' : ''; ?>>Student Affairs</option>
                            <option value="5" <?= $role_id == 5 ? 'selected' : ''; ?>>Dean</option>
                            <option value="4" <?= $role_id == 4 ? 'selected' : ''; ?>>Adviser</option>
                            <option value="3" <?= $role_id == 3 ? 'selected' : ''; ?>>Student</option>
                            <option value="2" <?= $role_id == 2 ? 'selected' : ''; ?>>Organization Officer</option>
                            <option value="1" <?= $role_id == 1 ? 'selected' : ''; ?>>System Admin</option>
                        </select>
                        <div class="mb-3">
                            <label for="isActive" class="form-label">Active Status</label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="isActive" name="is_active" value="1"
                                    <?= $is_active == 1 ? 'checked' : 0; ?>>
                                <label class="form-check-label" for="isActive">Is Active</label>
                            </div>

                            <!-- for student information -->
                            <div id="studentFields" style="display: none;">
                                <div class="mb-3">
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <input type="text" name="contact_number" id="contact_number" class="form-control"
                                        placeholder="Enter contact number">
                                </div>
                                <div class="mb-3">
                                    <label for="course_year_level" class="form-label">Course/Year Level</label>
                                    <input type="text" name="course_year_level" id="course_year_level" class="form-control"
                                        placeholder="Enter course and year level">
                                </div>
                                <div class="mb-3">
                                    <label for="student_number" class="form-label">Student Number</label>
                                    <input type="text" name="student_number" id="student_number" class="form-control"
                                        placeholder="Enter student number">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="../"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></a>
                            <button type="submit" class="btn btn-success" name="save changes" value="save changes">Save
                                Changes</button>
                        </div>
                </form>
            </div>
        </div>

    <?php else: ?>
        <p>No account found for the provided ID.</p>
    <?php endif; ?>
</body>

</html>

<script>
    document.getElementById('role').addEventListener('change', function () {
        const studentFields = document.getElementById('studentFields');

        // Show student fields only if the selected role value is "3" (Student)
        if (this.value === '3') {
            studentFields.style.display = 'block';
        } else {
            studentFields.style.display = 'none';
        }
    });
</script>