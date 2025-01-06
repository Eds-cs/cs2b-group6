<?php
require_once "database.class.php";

class Organization
{

    public $org_id;
    public $org_fee_id;

    public $organization_name;
    public $fee_name;
    public $fee_amount;
    public $created_at = '';
    public $updated_at = '';

    private $db;

    function __construct()
    {
        $this->db = Database::getInstance();
    }

    function getAllOrgs()
    {
        // SQL query to select accounts where account_type is 'Student'
        $sql = "SELECT o.*, SUM(of.fee_amount) AS total_fee,of.fee_name,of.fee_amount FROM organizations o
                LEFT JOIN organization_fees of ON o.organization_id = of.organization_id
                GROUP BY o.organization_id";

        // Prepare the SQL statement
        $qry = $this->db->connection()->prepare($sql);

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


    function OrgDetails()
    {
        // SQL query to fetch organizations and their fees
        $sql = "SELECT o.organization_id, o.organization_name, o.created_at, o.updated_at,
                   of.organization_fee_id,of.fee_name, of.fee_amount
            FROM organizations o
            LEFT JOIN organization_fees of ON o.organization_id = of.organization_id";

        // Prepare the SQL statement
        $qry = $this->db->connection()->prepare($sql);

        // Initialize a variable to store fetched data
        $data = [];

        // Execute the query and fetch all matching records
        if ($qry->execute()) {
            $result = $qry->fetchAll(PDO::FETCH_ASSOC); // Fetch as an associative array

            // Organize data by organization
            foreach ($result as $row) {
                $orgId = $row['organization_id'];
                if (!isset($data[$orgId])) {
                    // Initialize organization entry
                    $data[$orgId] = [
                        'organization_id' => $row['organization_id'],
                        'organization_name' => $row['organization_name'],
                        'created_at' => $row['created_at'],
                        'updated_at' => $row['updated_at'],
                        'fees' => []
                    ];
                }

                // Add fee details
                if ($row['fee_name'] && $row['fee_amount']) {
                    $data[$orgId]['fees'][] = [
                        'organization_fee_id' => $row['organization_fee_id'],
                        'fee_name' => $row['fee_name'],
                        'fee_amount' => $row['fee_amount']
                    ];
                }
            }
        } else {
            // Log an error if the query fails
            error_log("Query failed: " . implode(", ", $qry->errorInfo()));
        }

        // Return the organized data
        return $data;
    }

    function addOrg()
    {
        try {
            // Check if the organization name already exists
            $checkSql = "SELECT COUNT(*) FROM organizations WHERE organization_name = :name";
            $checkQry = $this->db->connection()->prepare($checkSql);
            $checkQry->bindParam(":name", $this->organization_name);
            $checkQry->execute();
            $count = $checkQry->fetchColumn();

            if ($count > 0) {
                // Organization name already exists
                $this->lastError = "Organization name already exists.";
                return false;
            }

            // If not exists, proceed with insertion
            $sql = "INSERT INTO organizations (organization_name) VALUES (:name)";
            $qry = $this->db->connection()->prepare($sql);
            $qry->bindParam(":name", $this->organization_name);

            return $qry->execute(); // Returns true on success

        } catch (PDOException $e) {
            // Log error (for debugging or audit purposes)
            error_log("Error adding organization: " . $e->getMessage());

            // Optionally set a class property to access the error elsewhere
            $this->lastError = $e->getMessage();

            // Return false on failure
            return false;
        }
    }



    function addFees($id)
    {
        // Check if the fee name already exists for this organization
        $checkSql = "SELECT COUNT(*) FROM organization_fees WHERE organization_id = :id AND fee_name = :name";
        $checkQry = $this->db->connection()->prepare($checkSql);
        $checkQry->bindParam(":id", $id);
        $checkQry->bindParam(":name", $this->fee_name);
        $checkQry->execute();
        $exists = $checkQry->fetchColumn();

        if ($exists > 0) {
            // Fee name already exists, update the amount
            $updateSql = "UPDATE organization_fees SET fee_amount = :amount 
                      WHERE organization_id = :id AND fee_name = :name";
            $updateQry = $this->db->connection()->prepare($updateSql);
            $updateQry->bindParam(":id", $id);
            $updateQry->bindParam(":name", $this->fee_name);
            $updateQry->bindParam(":amount", $this->fee_amount);

            try {
                return $updateQry->execute();
            } catch (PDOException $e) {
                error_log("Error updating fee: " . $e->getMessage());
                $_SESSION['error'] = 'Failed to update fee. Please try again.';
                return false;
            }
        } else {
            // Insert the new fee
            $sql = "INSERT INTO organization_fees (organization_id, fee_name, fee_amount) 
                VALUES (:id, :name, :amount)";
            $qry = $this->db->connection()->prepare($sql);
            $qry->bindParam(":id", $id);
            $qry->bindParam(":name", $this->fee_name);
            $qry->bindParam(":amount", $this->fee_amount);

            try {
                return $qry->execute();
            } catch (PDOException $e) {
                error_log("Error adding fee: " . $e->getMessage());
                $_SESSION['error'] = 'Failed to add fee. Please try again.';
                return false;
            }
        }
    }

    function updateFee($fee_id, $organization_name, $fee_name, $new_fee_amount, $old_fee_amount)
    {
        try {
            // Update the organization_name and fee details in the organization_fees table
            $sql = "UPDATE organization_fees 
                    SET fee_name = :fee_name, fee_amount = :fee_amount, old_fee = :old_fee 
                    WHERE organization_fee_id = :fee_id";

            // Prepare the statement for fee update
            $stmt = $this->db->connection()->prepare($sql);
            $stmt->bindParam(':fee_name', $fee_name);
            $stmt->bindParam(':fee_amount', $new_fee_amount);
            $stmt->bindParam(':old_fee', $old_fee_amount);  // Store the old fee in the `old_fee` column
            $stmt->bindParam(':fee_id', $fee_id);
            $stmt->execute();

            // Update the organization name in the organizations table
            $org_sql = "UPDATE organizations 
                        SET organization_name = :organization_name 
                        WHERE organization_id = (SELECT organization_id FROM organization_fees WHERE organization_fee_id = :fee_id)";
            $org_stmt = $this->db->connection()->prepare($org_sql);
            $org_stmt->bindParam(':organization_name', $organization_name);
            $org_stmt->bindParam(':fee_id', $fee_id);
            $org_stmt->execute();

            return true; // Return success
        } catch (PDOException $e) {
            // Log the error if something goes wrong
            error_log("Error updating fee and organization name: " . $e->getMessage());
            return false; // Return failure
        }
    }

    function delete($id)
    {
        try {
            // Check if the organization can be deleted (e.g., no active associations)
            $checkSql = "SELECT COUNT(*) FROM organization_fees WHERE organization_id = :id";
            $checkQry = $this->db->connection()->prepare($checkSql);
            $checkQry->bindParam(":id", $id);
            $checkQry->execute();
            $activeAssociations = $checkQry->fetchColumn();

            if ($activeAssociations > 0) {
                // If there are active associations, return false
                return false;
            }

            // Proceed with deletion
            $sql = "DELETE FROM organizations WHERE organization_id = :id";
            $qry = $this->db->connection()->prepare($sql);
            $qry->bindParam(":id", $id);

            return $qry->execute(); // Return true on successful deletion
        } catch (PDOException $e) {
            // Log and handle any errors
            error_log("Error deleting organization: " . $e->getMessage());
            return false;
        }
    }

    function deleteFees($organization_id)
    {
        try {
            $sql = "DELETE FROM organization_fees WHERE organization_id = :organization_id";
            // Delete all fees associated with the given organization
            $stmt = $this->db->connection()->prepare($sql);
            $stmt->bindParam(':organization_id', $organization_id, PDO::PARAM_INT);
            return $stmt->execute(); // Return true if successful, false otherwise
        } catch (PDOException $e) {
            return false; // Return false if there was an issue
        }
    }

}



?>