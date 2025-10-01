<?php
class ProductoModel extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function getProductos() {
        $sql = "SELECT p.*, c.nombre as categoria FROM productos p INNER JOIN categorias c ON p.id_categoria = c.id_categoria";
        return $this->selectAll($sql);
    }

    public function registrarProducto(string $nombre, string $descripcion, float $precio, int $stock, int $id_categoria, string $imagen) {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, id_categoria, imagen) VALUES (?, ?, ?, ?, ?, ?)";
        $datos = array($nombre, $descripcion, $precio, $stock, $id_categoria, $imagen);
        return $this->insert($sql, $datos);
    }

    public function eliminarProducto(int $id) {
        $sql = "UPDATE productos SET estado = 0 WHERE id_producto = ?";
        $datos = array($id);
        return $this->save($sql, $datos);
    }

    public function getProducto(int $id) {
        $sql = "SELECT p.*, c.nombre as categoria FROM productos p INNER JOIN categorias c ON p.id_categoria = c.id_categoria WHERE p.id_producto = ?";
        return $this->select($sql, array($id));
    }

    public function actualizarProducto(int $id, string $nombre, string $descripcion, float $precio, int $stock, int $id_categoria, string $imagen = null) {
        if ($imagen !== null) {
            $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, id_categoria = ?, imagen = ? WHERE id_producto = ?";
            $datos = array($nombre, $descripcion, $precio, $stock, $id_categoria, $imagen, $id);
        } else {
            $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, id_categoria = ? WHERE id_producto = ?";
            $datos = array($nombre, $descripcion, $precio, $stock, $id_categoria, $id);
        }
        return $this->save($sql, $datos);
    }
}