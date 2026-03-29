<?php
class Database {
    private $host = "localhost";
    private $db_name = "bondly_db"; // Ensure this matches phpMyAdmin
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Added charset=utf8 to prevent text display bugs
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) { 
            // This die() will tell you exactly why it's failing
            return null; 
        }
        return $this->conn;
    }
}