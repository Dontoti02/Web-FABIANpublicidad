<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'fabian_db';
    private $username = 'root';
    private $password = '';
    private $conn;
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                                  $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8mb4");
        } catch(PDOException $exception) {
            // Fallback to mysqli connection for compatibility
            return $this->getMysqliConnection();
        }
        
        return $this->conn;
    }
    
    private function getMysqliConnection() {
        $conexion = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        if ($conexion) {
            mysqli_set_charset($conexion, "utf8mb4");
        }
        return $conexion;
    }
}
?>
