<?php
if(!defined('DB_SERVER')){
    require_once("../initialize.php");
}
class DBConnection{

    private $host = "localhost";
    private $username = "root";  // Replace with your actual database username
    private $password = "";      // Replace with your actual database password
    private $db_name = "pawparivaar"; // Replace with your actual database name
    
    public $conn;
    
    public function __construct(){

        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    public function __destruct(){
        $this->conn->close();
    }
}
?>