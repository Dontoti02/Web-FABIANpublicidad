<?php
class Query extends Conexion {
    private $pdo, $con;

    public function __construct() {
        $this->pdo = new Conexion();
        $this->con = $this->pdo->conect();
    }

    public function select(string $sql, array $datos = []) {
        $result = $this->con->prepare($sql);
        $result->execute($datos);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function selectAll(string $sql, array $datos = []) {
        $result = $this->con->prepare($sql);
        $result->execute($datos);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert(string $sql, array $datos) {
        $result = $this->con->prepare($sql);
        $result->execute($datos);
        return $this->con->lastInsertId();
    }

    public function save(string $sql, array $datos) {
        $result = $this->con->prepare($sql);
        return $result->execute($datos);
    }
}
?>