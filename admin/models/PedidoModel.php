<?php
class PedidoModel extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function getPedidos() {
        try {
            $sql = "SELECT v.id_venta, v.total, v.fecha, v.estado, v.metodo_pago, 
                           CONCAT(c.nombre, ' ', c.apellidos) as cliente, c.email as cliente_email
                    FROM venta v 
                    LEFT JOIN clientes c ON v.id_cliente = c.id_cliente 
                    ORDER BY v.fecha DESC";
            $result = $this->selectAll($sql);
            return $result ? $result : [];
        } catch (Exception $e) {
            return [];
        }
    }

    public function getPedido($id) {
        try {
            $sql = "SELECT v.*, CONCAT(c.nombre, ' ', c.apellidos) as cliente, 
                           c.email as cliente_email, c.telefono as cliente_telefono, 
                           c.direccion as cliente_direccion
                    FROM venta v 
                    LEFT JOIN clientes c ON v.id_cliente = c.id_cliente 
                    WHERE v.id_venta = ?";
            $result = $this->select($sql, [$id]);
            return $result ? $result : null;
        } catch (Exception $e) {
            return null;
        }
    }

    public function getDetallePedido($id_venta) {
        try {
            $sql = "SELECT dv.*, p.nombre as producto, p.descripcion as producto_descripcion,
                           p.imagen as producto_imagen, c.nombre as categoria
                    FROM detalle_venta dv
                    INNER JOIN productos p ON dv.id_producto = p.id_producto
                    LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
                    WHERE dv.id_venta = ?
                    ORDER BY dv.id_detalle_venta";
            $result = $this->selectAll($sql, [$id_venta]);
            return $result ? $result : [];
        } catch (Exception $e) {
            return [];
        }
    }

    public function actualizarEstado($id, $estado) {
        try {
            $sql = "UPDATE venta SET estado = ? WHERE id_venta = ?";
            return $this->save($sql, [$estado, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getEstadisticasPedidos() {
        try {
            $sql = "SELECT 
                        estado,
                        COUNT(*) as cantidad,
                        SUM(total) as total_monto
                    FROM venta 
                    GROUP BY estado";
            $result = $this->selectAll($sql);
            return $result ? $result : [];
        } catch (Exception $e) {
            return [];
        }
    }

    public function eliminarPedido($id) {
        try {
            // Primero eliminar los detalles del pedido
            $sqlDetalle = "DELETE FROM detalle_venta WHERE id_venta = ?";
            $this->save($sqlDetalle, [$id]);
            
            // Luego eliminar el pedido principal
            $sqlVenta = "DELETE FROM venta WHERE id_venta = ?";
            $result = $this->save($sqlVenta, [$id]);
            
            return $result;
        } catch (Exception $e) {
            return 0;
        }
    }
}
?>