<?php
require_once '../classes/account.class.php';
require_once '../classes/course.class.php';


$account_id = $first_name = $last_name = $middle_initial = $email = $password = $created_at = $updated_at = $role_id = $is_active = $student_number = $is_regular = $contact_number = $course_id = '';
$account_idErr = $first_nameErr = $last_nameErr = $middle_initialErr = $emailErr = $passwordErr = $role_idErr = $student_numberErr = $contact_numberErr = $course_idErr = '';
$accObj = new Account();



if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['save account'])) {
    $first_name = clean_input($_POST['first_name']);
    $last_name = clean_input($_POST['last_name']);
    $middle_initial = isset($_POST['middle_initial']) ? clean_input($_POST['middle_initial']) : '';
    $email = clean_input($_POST['email']);
    $password = clean_input($_POST['password']);
    $role_id = isset($_POST['role']) ? clean_input($_POST['role']) : '';
    $contact_number = clean_input($_POST['contact_number']);
    $course_id = clean_input($_POST['course']);
    $student_number = clean_input($_POST['student_number']);


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

    if ($role_id == 3) {
        if (empty($contact_number)) {
            $contact_numberErr = 'Enter a Contact Number';
        }

        if (empty($course_id)) {
            $course_idErr = 'Enter a Course';
        }

        if (empty($student_number)) {
            $student_number = 'Enter a student_number';
        }
    }

    if (empty($first_nameErr) && empty($last_nameErr) && empty($emailErr) && empty($passwordErr) && empty($role_idErr)) {
        $accObj->first_name = $first_name;
        $accObj->last_name = $last_name;
        $accObj->middle_initial = $middle_initial;
        $accObj->email = $email;
        $accObj->password = $password;
        $accObj->role_id = $role_id;
        $accObj->updated_at = date('Y-m-d H:i:s');

        if ($role_id == 3) { //for student profile
            $accObj->student_number = $student_number;
            $accObj->course_id = $course_id;
            $accObj->contact_number = $contact_number;
        }

        if ($accObj->register()) {

            $_SESSION['success'] = 'Account added successfully!';
            echo $_SESSION['success'];
            exit;
        } else {
            echo 'error <br>';
        }
    }

}




?>


<style>
    .error {
        color: red;
    }
</style>
<div class="">
    <div class="">
        <h1>Accounts</h1>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addAccountModal">
            Add Account
        </button>
        
        </form>
        <table class="table">
            <thead class="table-success" id="tablehead">
                <tr>
                    <th>Full Name</th>
                    <th scope="col">Email</th>
                    <!-- <th scope="col">Password</th> -->
                    <th scope="col">Date Created</th>
                    <th scope="col">Date Updated</th>
                    <th>Active Status</th>
                    <th>Role</th>
                    <th scope="col">Actions</th>

                </tr>
            </thead>
            <?php
            $keyword = '';
            $array = $accObj->showAccounts($keyword); //shows the accounts data
            // var_dump($accObj);
            foreach ($array as $arr) {
                ?>

                <tbody>
                    <tr>
                        <td><?= $arr['first_name'], ' ', $arr['middle_initial'], ' ', $arr['last_name'] ?></td>
                        <td><?= $arr['email'] ?></td>
                        <!-- <td><?= $arr['password'] ?></td> -->
                        <td><?= $arr['created_at'] ?></td>
                        <td><?= $arr['updated_at'] ?></td>
                        <td><?php if ($arr['is_active'] == 1) {
                            echo 'Active';
                        } else {
                            echo 'Inactive';
                        }
                        ?></td>
                        <td><?= $arr['role_name'] ?></td>
                        <td>
                            <a href="./accounts/edit.php?id=<?= $arr['account_id'] ?>"><button
                                    class="btn btn-outline-success">Edit</button></a>
                            <a href="/accounts/deleteAccount.php?id=<?= $arr['account_id'] ?> " class="deleteBtn"
                                data-id="<?= $arr['account_id'] ?>" data-name="<?= $arr['first_name'] ?>">
                                <button class="btn btn-outline-danger">Delete</button>
                            </a>
                        </td>


                    </tr>
                    <?php

            }
            ?>
            </tbody>
        </table>
        <script src="./accounts/account.js"></script>
    </div>
</div>

<!-- Add Account Modal -->
<div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAccountModalLabel">Add Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="newFName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="FName" name="first_name">
                        <?php if (!empty($first_nameErr)): ?>
                            <span class="error"><?= $first_nameErr ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="newLName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="LName" name="last_name">
                        <?php if (!empty($last_nameErr)): ?>
                            <span class="error"><?= $last_nameErr ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="newMName" class="form-label">Middle Initial</label>
                        <input type="text" class="form-control" id="MName" name="middle_initial">
                    </div>
                    <div class="mb-3">
                        <label for="newEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                        <?php
                        if (!empty($emailErr)): ?>
                            <span class="error"><?= $emailErr ?></span>
                            <br>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <?php
                        if (!empty($passwordErr)): ?>
                            <span class="error"><?= $passwordErr ?></span>
                            <br>
                        <?php endif; ?>
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
                    </div>

                    <!-- Additional Inputs for Student -->

                    <div id="studentFields" style="display: none;">
                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="number" name="contact_number" id="contact_number" class="form-control"
                                placeholder="Enter contact number">
                        </div>
                        <div class="mb-3">
                            <label for="course" class="form-label">Course/Year Level</label>
                            <select name="course" id="course" class="form-control">
                                <option value="">Choose course</option>
                                <?php $courseObj = new Course();
                                $courses = $courseObj->showCourses($keyword);
                                if (!empty($courses)): ?>
                                    <?php foreach ($courses as $course): ?>
                                        <option value="<?= $course['course_id']; ?>">
                                            <?= clean_input($course['course_name']) . " - Year " . htmlspecialchars($course['yr_level']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">No courses available</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="student_number" class="form-label">Student Number</label>
                            <input type="number" name="student_number" id="student_number" class="form-control"
                                placeholder="Enter student number">
                        </div>
                    </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success" name="save account" value="save account">Add
                    Account</button>
            </div>
            </form>
        </div>

    </div>
</div>
</div>
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