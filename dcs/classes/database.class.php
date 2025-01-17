<?php
class Database
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "ccs";

    private $connection = null;

    private static $instance = null;

    private function __construct()
    {
        try {
            $this->connection = new PDO(
                "mysql:host=$this->host;dbname=$this->database",
                $this->username,
                $this->password,
            );
        } catch (PDOException $e) {
            echo "Connection_error", $e->getMessage();
        }
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function connection()
    {
        return $this->connection;
    }
}

?>