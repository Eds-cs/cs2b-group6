<?php

// Include the database connection file
require_once "database.class.php";
require_once "app.class.php";

class Account
{
    public $account_id;
    public $first_name;
    public $last_name;
    public $middle_initial;
    public $email;
    public $password;
    public $created_at = '';
    public $updated_at = '';
    public $is_active = 1;
    public $role_id;

    public $student_number;
    public $is_regular = 1;
    public $student_id;

    public $contact_number;

    public $course_id;

    public $clearance_id;





    private $db;
    private $application;

    // Constructor: Initialize the database connection
    function __construct()
    {
        $this->db = Database::getInstance(); // Create a new instance of the Database class
        $this->application = Application::getInstance();
    }

    function login($email, $password)
    {
        $sql = "SELECT a.* ,
                    GROUP_CONCAT( DISTINCT r.role_name) as roles,
                    tp.teacher_id, 
                    sp.student_id,
                    GROUP_CONCAT(DISTINCT of.organization_id) as org_ids
                FROM account_roles ar
                        INNER JOIN roles r ON ar.role_id = r.role_id
                        INNER JOIN accounts a ON ar.account_id = a.account_id
                        LEFT JOIN teacher_profiles tp ON a.account_id = tp.account_id
                        LEFT JOIN students sp ON a.account_id = sp.account_id
                        LEFT JOIN organization_officers of ON sp.student_id = of.student_id
                WHERE email = :email AND ar.is_active = 1";
        $stmt = $this->db->connection()->prepare($sql);

        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $accObj = $stmt->fetch();

        if ($accObj && password_verify($password, $accObj['password'])) {  //<--- for entering with hashed passwords

            $this->application->startSession($accObj);

            return true;
        }

        // if ($accObj && $password === $accObj['password']) { // for entering withouut hashed passwords
        //     $this->application->startSession($accObj); // Initialize session
        //     return true;
        // }

        return false;
    }

    function showAccounts($keyword = '')
    {
        $sql = "SELECT a.*, ar.*,r.role_name
                FROM accounts a
                INNER JOIN account_roles ar ON a.account_id = ar.account_id
                INNER JOIN roles r ON ar.role_id = r.role_id
                WHERE CONCAT(first_name,last_name,middle_initial) LIKE '%' :keyword '%'
                ORDER BY first_name ASC;";

        $qry = $this->db->connection()->prepare($sql);
        $qry->bindParam(':keyword', $keyword);
        

        $data = [];


        if ($qry->execute()) {
            $data = $qry->fetchAll();
        }
        return $data;
    }

    function register()
    {
        // Check if email already exists
        $sql = "SELECT a.*,r.role_name, ar.* 
                FROM accounts a
                INNER JOIN account_roles ar ON a.account_id = ar.account_id
                INNER JOIN roles r ON ar.role_id = r.role_id 
                WHERE email = :email";
        $checkUser = $this->db->connection()->prepare($sql);
        $checkUser->bindParam(":email", $this->email);
        $checkUser->execute();

        if ($checkUser->rowCount() == 0) {
            // Hash the password
            $passwordHash = password_hash($this->password, PASSWORD_BCRYPT);

            // Set current timestamp for created_at and updated_at
            $currentTimestamp = date('Y-m-d H:i:s');

            // Insert into `accounts` table
            $sql = "INSERT INTO accounts(first_name, last_name, middle_initial, email, password, created_at, updated_at)
                    VALUES (:first_name, :last_name, :middle_initial,:email, :password,:created_at, :updated_at)";

            $db = $this->db->connection();
            $qry = $db->prepare($sql);

            // Bind parameters
            $qry->bindParam(":first_name", $this->first_name);
            $qry->bindParam(":last_name", $this->last_name);
            $qry->bindParam(":middle_initial", $this->middle_initial);
            $qry->bindParam(":email", $this->email);
            $qry->bindParam(":password", $passwordHash);
            $qry->bindParam(":created_at", $currentTimestamp);
            $qry->bindParam(":updated_at", $currentTimestamp);

            $qry->execute();
            $account_id = $db->lastInsertId();

            // Insert into `account_roles` table
            $sql1 = "INSERT INTO account_roles(account_id, role_id, date_updated, is_active)
                    VALUES(:account_id, :role_id, :date_updated, :is_active)";

            $qry1 = $db->prepare($sql1);
            $qry1->bindParam(':account_id', $account_id);
            $qry1->bindParam(':role_id', $this->role_id); // Using the initialized role_id
            $qry1->bindParam(':date_updated', $currentTimestamp);
            $qry1->bindParam(':is_active', $this->is_active); // Using the initialized is_active
            $qry1->execute();

            // Determine whether the role is for a student or teacher
            if ($this->role_id == 3) { // Role ID for Student
                // Insert into `students` table
                $sql2 = "INSERT INTO students(account_id, student_number,is_regular, course_id, contact_number)
                        VALUES(:account_id, :student_number,:is_regular, :course_id, :contact_number)";
                $qry2 = $db->prepare($sql2);
                $qry2->bindParam(':account_id', $account_id);
                $qry2->bindParam(':student_number', $this->student_number);
                $qry2->bindParam(':is_regular', $this->is_regular);
                $qry2->bindParam(':course_id', $this->course_id);
                $qry2->bindParam(':contact_number', $this->contact_number);
                $qry2->execute();

            } elseif (in_array($this->role_id, [4, 6])) { // Role ID for Adviser or Student Affairs
                // Insert into `teacher_profiles` table
                $sql3 = "INSERT INTO teacher_profiles(account_id)
                        VALUES(:account_id)";
                $qry3 = $db->prepare($sql3);
                $qry3->bindParam(':account_id', $account_id);
                $qry3->execute();
            }

            return $account_id; // Registration successful
        } else {
            return 0; // Email already exists
        }
    }


    function editAccount()
    {
        // Get the current timestamp for updated_at and date_updated
        $currentTimestamp = date('Y-m-d H:i:s');

        // Check if a new password is provided, and hash it if so
        $passwordHash = $this->password;

        // Build the SQL query to update the account
        $sql = "UPDATE accounts
                SET email = :email,
                    first_name = :first_name,
                    middle_initial = :middle_initial,
                    last_name = :last_name," .
            ($passwordHash ? " password = :password," : "") . "
                    updated_at = :updated_at
                WHERE account_id = :account_id";

        $qry = $this->db->connection()->prepare($sql);

        // Bind the parameters for the account update
        $qry->bindParam(":email", $this->email);
        $qry->bindParam(":first_name", $this->first_name);
        $qry->bindParam(":middle_initial", $this->middle_initial);
        $qry->bindParam(":last_name", $this->last_name);
        $qry->bindParam(":updated_at", $currentTimestamp);
        $qry->bindParam(":account_id", $this->account_id);

        // Bind the password only if provided
        if ($passwordHash) {
            $qry->bindParam(":password", $passwordHash);
        }

        // Execute the account update
        $accountUpdated = $qry->execute();

        if ($accountUpdated) {
            // Update the role in the account_roles table
            $sqlRole = "UPDATE account_roles
                        SET role_id = :role_id,
                            date_updated = :date_updated,
                            is_active = :is_active
                        WHERE account_id = :account_id";

            $qryRole = $this->db->connection()->prepare($sqlRole);

            // Bind the parameters for the role update
            $qryRole->bindParam(":role_id", $this->role_id);
            $qryRole->bindParam(":date_updated", $currentTimestamp);
            $qryRole->bindParam(":is_active", $this->is_active);
            $qryRole->bindParam(":account_id", $this->account_id);

            // Execute the role update and return the result
            return $qryRole->execute();
        }

        // If the account update fails, return false
        return false;
    }


    function fetchRecordID($recordID)
    {
        $sql = "SELECT a.*, ar.*, r.role_name
                FROM accounts a
                INNER JOIN account_roles ar ON a.account_id = ar.account_id
                INNER JOIN roles r ON ar.role_id = r.role_id
                WHERE a.account_id = :recordID";
        $qry = $this->db->connection()->prepare($sql);
        $qry->bindParam(':recordID', $recordID);
        $data = [];
        if ($qry->execute()) {
            $data = $qry->fetch();
        }

        return $data;
    }

    function delete($recordID)
    {
        // Start a transaction to ensure all queries are executed together
        $this->db->connection()->beginTransaction();

        try {
            // Check the role of the account
            $sql = "SELECT role_id FROM account_roles WHERE account_id = :recordID";
            $qry = $this->db->connection()->prepare($sql);
            $qry->bindParam(':recordID', $recordID);
            $qry->execute();
            $role = $qry->fetch();

            // If the account is a student, delete from `students` table
            if ($role && $role['role_id'] == 3) {
                $sql = "DELETE FROM students WHERE account_id = :recordID";
                $qry = $this->db->connection()->prepare($sql);
                $qry->bindParam(':recordID', $recordID);
                $qry->execute();
            }
            // If the account is a teacher, check for course assignments
            elseif ($role && in_array($role['role_id'], [4, 6])) {
                // Check if the teacher is assigned to any courses
                $sql = "SELECT course_id FROM course WHERE teacher_id = :recordID";
                $qry = $this->db->connection()->prepare($sql);
                $qry->bindParam(':recordID', $recordID);
                $qry->execute();
                $assignedCourses = $qry->fetchAll();

                if (!empty($assignedCourses)) {
                    // If courses are assigned, throw an exception
                    throw new Exception("Cannot delete professor account. They are assigned to courses.");
                }

                // Proceed with deleting from `teacher_profiles`
                $sql = "DELETE FROM teacher_profiles WHERE account_id = :recordID";
                $qry = $this->db->connection()->prepare($sql);
                $qry->bindParam(':recordID', $recordID);
                $qry->execute();
            }

            // Delete from account_roles table
            $sql = "DELETE FROM account_roles WHERE account_id = :recordID";
            $qry = $this->db->connection()->prepare($sql);
            $qry->bindParam(':recordID', $recordID);
            $qry->execute();

            // Delete the account from the accounts table
            $sql = "DELETE FROM accounts WHERE account_id = :recordID";
            $qry = $this->db->connection()->prepare($sql);
            $qry->bindParam(':recordID', $recordID);
            $result = $qry->execute();

            // Commit the transaction if all deletions were successful
            $this->db->connection()->commit();

            return $result;
        } catch (Exception $e) {
            // Rollback the transaction if an error occurs
            $this->db->connection()->rollBack();
            // Optionally, log the error or rethrow it
            throw $e;
        }
    }


}



