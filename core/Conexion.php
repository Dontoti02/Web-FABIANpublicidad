<?php
require_once __DIR__ . '/../config/Config.php';

class Conexion {
    private $conect;

    public function __construct() {
        $pdo = "mysql:host=" . Config::DB_HOST . ";dbname=" . Config::DB_NAME . ";charset=utf8mb4";
        try {
            $this->conect = new PDO($pdo, Config::DB_USER, Config::DB_PASS);
            $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error en la conexión: " . $e->getMessage();
        }
    }

    public function conect() {
        return $this->conect;
    }
}
?>