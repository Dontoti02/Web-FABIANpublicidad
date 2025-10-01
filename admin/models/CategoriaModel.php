<?php
class CategoriaModel extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function getCategorias() {
        $sql = "SELECT * FROM categorias";
        return $this->selectAll($sql);
    }

    public function registrarCategoria(string $nombre) {
        $sql = "INSERT INTO categorias (nombre) VALUES (?)";
        $datos = array($nombre);
        return $this->insert($sql, $datos);
    }

    public function eliminarCategoria(int $id) {
        $sql = "UPDATE categorias SET estado = 0 WHERE id_categoria = ?";
        $datos = array($id);
        return $this->save($sql, $datos);
    }

    public function getCategoria($id) {
        $sql = "SELECT * FROM categorias WHERE id_categoria = ?";
        $datos = array($id);
        return $this->select($sql, $datos);
    }

    public function actualizarCategoria($id, $nombre) {
        $sql = "UPDATE categorias SET nombre = ? WHERE id_categoria = ?";
        $datos = array($nombre, $id);
        return $this->save($sql, $datos);
    }
}
?>