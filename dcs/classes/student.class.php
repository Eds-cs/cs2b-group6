<?php
require_once "database.class.php";
class Student
{
    private $db;

    function __construct()
    {
        $this->db = Database::getInstance();
    }

    function getAllStudents($keyword = '')
    {
        // SQL query to select accounts where account_type is 'Student'
        $sql = "SELECT a.*, s.student_id, s.student_number, s.is_regular, s.contact_number, c.course_name, c.yr_level, 
		            (CASE WHEN s.clearance_id IS NULL THEN 'Pending' ELSE 'Cleared' END) AS overall_status
                FROM students s
                    INNER JOIN accounts a ON a.account_id = s.account_id
                    INNER JOIN course c ON c.course_id = s.course_id
                WHERE CONCAT(a.first_name,a.last_name,a.middle_initial) LIKE '%' :keyword '%'
                ORDER BY c.course_name, c.yr_level;";

        // Prepare the SQL statement
        $qry = $this->db->connection()->prepare($sql);
        $qry->bindParam(':keyword', $keyword);

        // Initialize a variable to store fetched data
        $data = [];

        // Execute the query and fetch all matching records
        if ($qry->execute()) {
            $data = $qry->fetchAll(); // Fetch as an associative array
        } else {
            // Log an error if the query fails
            error_log("Query failed: " . implode(", ", $qry->errorInfo()));
        }

        // Return the fetched data (or an empty array if no records found)
        return $data;
    }

    function getStudentProfile($studentId)
    {
        // SQL query to search for accounts by name and account type
        $sql = "SELECT a.*, s.*, c.* FROM students s
        INNER JOIN accounts a ON s.account_id = a.account_id
        INNER JOIN course c ON c.course_id = s.course_id
        WHERE s.student_id = :studentId;";


        // Prepare the SQL statement
        $qry = $this->db->connection()->prepare($sql);

        // Bind the values to the query parameters
        $qry->bindParam(':studentId', $studentId);

        // Initialize a variable to store fetched data
        $data = null;

        // Execute the query and fetch the single matching record
        if ($qry->execute()) {
            $data = $qry->fetch();  // fetch() to get a single row
        }

        // Return the fetched data (or null if no records found)
        return $data;
    }

    function getStudentOrgFees($studentId)
    {
        // SQL query to search for accounts by name and account type
        $sql = "SELECT o.*, of.organization_fee_id, of.fee_name, of.fee_amount, 
                IFNULL(SUM(ph.amount_paid), 0) AS total_paid, 
                (CASE WHEN sc.approver_id IS NULL THEN FALSE ELSE TRUE END) is_cleared
                FROM organization_fees of
                LEFT JOIN organizations o ON o.organization_id = of.organization_id
                LEFT JOIN payment_history ph ON ph.organization_fee_id = of.organization_fee_id AND ph.student_id = :studentId
                LEFT JOIN student_clearance sc ON sc.student_id = :studentId AND sc.organization_id = o.organization_id 
                GROUP BY of.organization_id, of.organization_fee_id;";


        // Prepare the SQL statement
        $qry = $this->db->connection()->prepare($sql);

        // Bind the values to the query parameters
        $qry->bindParam(':studentId', $studentId);

        // Initialize a variable to store fetched data
        $data = null;

        // Execute the query and fetch the single matching record
        if ($qry->execute()) {
            $data = $qry->fetchAll();  // fetch() to get a single row
        }

        // Return the fetched data (or null if no records found)
        return $data;
    }

    function getOrgStatusByStudent($studentId)
    {
        $sql = "SELECT c.*, o.organization_name, CONCAT(a.last_name, ', ', a.first_name, ' ', a.middle_initial) as approver
                FROM organizations o
                LEFT JOIN student_clearance c ON o.organization_id = c.organization_id and c.student_id = ?
                LEFT JOIN accounts a ON a.account_id = c.approver_id";

        $qry = $this->db->connection()->prepare($sql);

        $data = [];

        if ($qry->execute([$studentId])) {
            $data = $qry->fetchAll();
        } else {
            error_log("Query failed: " . implode(", ", $qry->errorInfo()));
        }

        return $data;
    }

    function getFacultyClearances($studentId)
    {
        $sql = "SELECT c.date_approved,
                    CONCAT(a.last_name, ', ', a.first_name, ' ', a.middle_initial) as approver,
                    GROUP_CONCAT(r.role_name) AS role_name,
                    c.approver_id
                FROM student_clearance c
                LEFT JOIN accounts a ON a.account_id = c.approver_id
                LEFT JOIN account_roles ar ON ar.account_id = a.account_id
                LEFT JOIN roles r ON r.role_id = ar.role_id
                WHERE c.student_id = ? and c.organization_id = 0;";

        $qry = $this->db->connection()->prepare($sql);

        $data = [];

        if ($qry->execute([$studentId])) {
            $data = $qry->fetchAll();
        } else {
            error_log("Query failed: " . implode(", ", $qry->errorInfo()));
        }

        return $data;
    }

    function payFee($studId, $acctId, $orgFeeId, $amount)
    {
        // insert payment history
        $sql = "INSERT INTO payment_history (organization_fee_id, student_id, received_by, amount_paid, date_paid) 
                VALUE (?,?,?,?,?)";
        $qry = $this->db->connection()->prepare($sql);
        $currentDatetime = date("Y-m-d h:i:sa");
        if ($qry->execute([$orgFeeId, $studId, $acctId, $amount, $currentDatetime])) {
            return true;
        }

        return false;
    }

    function clearStudent($studId, $approverId, $roleId, $orgId = null)
    {
        // insert payment history
        $sql = "INSERT INTO student_clearance (student_id, approver_id, role_id, organization_id, date_approved) 
                VALUE (?,?,?,?,?)";
        $qry = $this->db->connection()->prepare($sql);
        $currentDatetime = date("Y-m-d h:i:sa");
        if ($qry->execute([$studId, $approverId, $roleId, $orgId, $currentDatetime])) {
            return true;
        }

        return false;
    }
}
?>