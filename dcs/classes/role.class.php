<?php
require_once "database.class.php";
class Role
{
    private static $instance = null;
    private $db;
    private $roles = [];

    private function __construct()
    {
        $this->db = Database::getInstance();
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Role();
        }
        return self::$instance;
    }

    public function cacheRoles()
    {
        $query = $this->db->connection()->prepare("SELECT * FROM roles");
        $this->roles = [];

        // Execute the query and fetch all matching records
        if ($query->execute()) {
            $this->roles = $query->fetchAll(); // Fetch as an associative array
        } else {
            // Log an error if the query fails
            error_log("Query failed: " . implode(", ", $query->errorInfo()));
        }
    }

    public function getRoleId($roleName)
    {
        foreach ($this->roles as $role) {
            if ($role['role_name'] == $roleName) {
                return $role['role_id'];
            }
        }
        return null;
    }
}
?>